<?php

/**
 * @file
 * Contains \Drupal\dbcdk_community\Form\QuarantineForm.
 */

namespace Drupal\dbcdk_community\Form;

use DBCDK\CommunityServices\Api\QuarantineApi;
use DBCDK\CommunityServices\ApiException;
use DBCDK\CommunityServices\Model\Profile;
use DBCDK\CommunityServices\Model\Quarantine;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create or edit a Community Quarantine.
 */
class QuarantineForm extends FormBase implements ContainerInjectionInterface {

  /**
   * Community Service Quarantine API.
   *
   * @var \DBCDK\CommunityServices\Api\QuarantineApi $quarantineApi
   */
  protected $quarantineApi;

  /**
   * Community Service Quarantine object from the context.
   *
   * @var \DBCDK\CommunityServices\Model\Quarantine $quarantine
   */
  protected $quarantine;

  /**
   * Community Service Profile from the context.
   *
   * @var \DBCDK\CommunityServices\Model\Profile $profile
   */
  protected $profile;

  /**
   * QuarantineForm constructor.
   *
   * @param \DBCDK\CommunityServices\Api\QuarantineApi $quarantine_api
   *   The Community Service Quarantine API.
   * @param \DBCDK\CommunityServices\Model\Quarantine $quarantine
   *   The Community Quarantine we wish to alter.
   * @param \DBCDK\CommunityServices\Model\Profile $profile
   *   The Community Profile we wish to alter a quarantine on.
   */
  public function __construct(QuarantineApi $quarantine_api, Quarantine $quarantine = NULL, Profile $profile = NULL) {
    $this->quarantineApi = $quarantine_api;
    $this->quarantine = $quarantine;
    $this->profile = $profile;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    try {
      $quarantine_api = $container->get('dbcdk_community.api.quarantine');

      // Set the $this->quarantine if the request contains a quarantine id.
      if ($quarantine_id = $container->get('request_stack')->getCurrentRequest()->get('quarantine_id')) {
        $quarantine = $quarantine_api->quarantineFindById($quarantine_id);
      }
      else {
        $quarantine = NULL;
      }

      $profile_filter = [
        'where' => [
          'username' => $container->get('request_stack')->getCurrentRequest()->get('username'),
        ],
      ];
      $profile_api = $container->get('dbcdk_community.api.profile');
      $profile = $profile_api->profileFindOne(json_encode($profile_filter));
    }
    catch (ApiException $e) {
      \Drupal::logger('DBCDK Community Service')->error($e);
      $profile = NULL;
      $quarantine = NULL;
    }

    return new static(
      $quarantine_api,
      $quarantine,
      $profile
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dbcdk_community_quarantine_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Defensive coding to make sure we only display the quarantine form if we
    // could find a Community Profile that it should belong to.
    if (empty($this->profile)) {
      drupal_set_message($this->t('The system could not find the profile with this quarantine. Please try again later or contact an administrator.'), 'error');
      return $form;
    }

    $form['reason'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Reason'),
      '#format' => 'dbcdk_community_basic_markup',
      '#default_value' => empty($this->quarantine) ? '' : $this->quarantine->getReason(),
      '#required' => TRUE,
    ];

    $form['start'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Start date'),
      '#date_time_element' => 'none',
      '#default_value' => empty($this->quarantine) ? new DrupalDateTime() : DrupalDateTime::createFromDateTime($this->quarantine->getStart()),
      '#required' => TRUE,
    ];

    $form['end'] = [
      '#type' => 'datetime',
      '#title' => $this->t('End date'),
      '#date_time_element' => 'none',
      '#default_value' => empty($this->quarantine) ? new DrupalDateTime('+1 day') : DrupalDateTime::createFromDateTime($this->quarantine->getEnd()),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => empty($this->quarantine) ? $this->t('Create') : $this->t('Update'),
      '#button_type' => 'primary',
    ];
    $form['actions']['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancel'),
      '#url' => Url::fromRoute('page_manager.page_view_dbcdk_community_profile', [
        'username' => $this->profile->getUsername(),
      ]),
      '#attributes' => ['class' => ['button']],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      // Instantiate a new Quarantine if there is no quarantine in the context.
      // The lack of a quarantine in the context is also what we use to
      // determine if we're creating or editing an existing quarantine.
      if (empty($this->quarantine)) {
        $this->quarantine = new Quarantine();
        // Set the ID of the Drupal Moderator who created this quarantine.
        // We would like to be able to look up who created a quarantine but
        // because the system does not yet support "UNI Login", we then set the
        // Drupal user instead so we can create a script that will map these
        // IDs, later on.
        // @TODO Use the Profile ID when the system supports UNI Login.
        $this->quarantine->setQuarantineCreatorProfileId($this->currentUser()->id());
        // Set the Community Profile ID that should own this quarantine.
        $this->quarantine->setQuarantinedProfileId($this->profile->getId());
      }

      // Set values for the quarantine.
      $this->quarantine->setReason($form_state->getValue('reason')['value']);
      $start_date = new \DateTime();
      $this->quarantine->setStart($start_date->setTimestamp($form_state->getValue('start')->getTimestamp()));
      $end_date = new \DateTime();
      $this->quarantine->setEnd($end_date->setTimestamp($form_state->getValue('end')->getTimestamp()));

      // Determine if we should POST or PUT a request based on the existence of
      // a Quarantine ID. The ID will only exist if we have fetched the
      // quarantine from the Community Service.
      if (empty($this->quarantine->getId())) {
        drupal_set_message($this->t('The quarantine was successfully created.'));
        $this->quarantineApi->quarantineCreate($this->quarantine);
      }
      else {
        drupal_set_message($this->t('The quarantine was successfully updated.'));
        $this->quarantineApi->quarantineUpsert($this->quarantine);
      }

      // Redirect to the Community Profile after successful submission.
      $form_state->setRedirect('page_manager.page_view_dbcdk_community_profile', [
        'username' => $this->profile->getUsername(),
      ]);
    }
    catch (ApiException $e) {
      \Drupal::logger('DBCDK Community Service')->error($e);
      drupal_set_message($this->t('An error occurred while the system tried to handle the quarantine. Please try again later or contact an administrator.'), 'error');
    }
  }

}
