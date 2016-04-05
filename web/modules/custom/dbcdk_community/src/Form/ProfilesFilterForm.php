<?php

/**
 * @file
 * Contains \Drupal\dbcdk_community\Form\ProfilesFilterForm.
 */

namespace Drupal\dbcdk_community\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Exposed filter form for the Community Profiles listing page.
 */
class ProfilesFilterForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dbcdk_community_profiles_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $search_query = $this->getRequest()->get('search');

    $form['filter'] = [
      '#type' => 'details',
      '#title' => $this->t('Filter list'),
      '#open' => empty($search_query) ? FALSE : TRUE,
    ];

    $form['filter']['search'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $search_query,
    ];

    $form['filter']['quarantined'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Only show profiles with an active quarantine.'),
      '#default_value' => $this->getRequest()->get('quarantined'),
    ];

    $form['filter']['actions']['#type'] = 'actions';
    $form['filter']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Filter'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $route_parameters = [];
    $fields = [
      'search',
      'quarantined',
    ];
    foreach ($fields as $field) {
      if ($value = $form_state->getValue($field)) {
        $route_parameters[$field] = $value;
      }
    }
    $form_state->setRedirect('page_manager.page_view_dbcdk_community_profiles', $route_parameters);
  }

}
