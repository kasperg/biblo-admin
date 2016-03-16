<?php

/**
 * @file
 * Contains \Drupal\dbcdk_community\Form\SettingsForm.
 */

namespace Drupal\dbcdk_community\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure file system settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dbcdk_community_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['dbcdk_community.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dbcdk_community.settings');

    // Community service host url.
    $form['community_service_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Community Service Url'),
      '#default_value' => $config->get('community_service_url'),
      '#description' => $this->t('The Community Service Url until the endpoints start. Example: %example', array('%example' => 'https://dbcdk-community.dk/api/')),
    ];
    );

    // Vertical tab for advanced options.
    $form['advanced_options'] = array(
      '#type' => 'details',
      '#title' => $this->t('Advanced options'),
    );

    // Enable debug information.
    $form['advanced_options']['community_service_debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Community Service Debug-Mode'),
      '#default_value' => $config->get('community_service_debug'),
      '#description' => $this->t('Return debug information with each Community Service call.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fields = [
      'community_service_url',
      'community_service_debug',
    ];

    foreach ($fields as $field) {
      $this
        ->config('dbcdk_community.settings')
        ->set($field, $form_state->getValue($field))
        ->save();
    }

    parent::submitForm($form, $form_state);
  }

}
