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

    // Logo settings for theme override.
    $form['community_service_url'] = array(
      'community_service_url' => array(
        '#type' => 'textfield',
        '#title' => $this->t('Community Service Url'),
        '#default_value' => $config->get('community_service_url'),
        '#description' => $this->t('The Community Service Url until the endpoints start. Example: %example', array('%example' => 'https://dbcdk-community.dk/api/')),
      ),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this
      ->config('dbcdk_community.settings')
      ->set('community_service_url', $form_state->getValue(array('community_service_url')))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
