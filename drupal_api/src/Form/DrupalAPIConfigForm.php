<?php

namespace Drupal\drupal_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Description of DrupalAPIConfigForm
 *
 * @author wayne
 */
class DrupalAPIConfigForm extends ConfigFormBase {
  
  /**
   *
   * @var \Drupal\drupal_api\Service\DrupalAPIManagerInterface
   */
  protected $drupalApiManager;
  
  
  public function __construct(\Drupal\drupal_api\Service\DrupalAPIManagerInterface $drupalApiManager) {
    $this->drupalApiManager = $drupalApiManager;
  }

  public static function create(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
    return new static(
        $container->get('drupal_api.manager')
    );
  }
  
  protected function getEditableConfigNames() {
    return [
      'drupal_api.settings',
    ];
  }
    
  public function getFormId() {
    return 'drupal_api.config_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    
    $api_config = $this->config('drupal_api.settings');
    
    $form['import'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Drupal Project Import'),
    ];
    $form['import']['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $api_config->get('import_enabled'),
    ];
    $form['import']['frequency'] = [
      '#type' => 'select',
      '#title' => $this->t('Retrieve New Data Every'),
      '#options' => [
        1800 => $this->t('@count minutes', ['@count' => 30]),
        3600 => $this->t('@count hour', ['@count' => 1]),
        6400 => $this->t('@count hours', ['@count' => 2]),
        18000 => $this->t('@count hours', ['@count' => 5]),
        36000 => $this->t('@count hours', ['@count' => 10]),
        86400 => $this->t('@count hours', ['@count' => 24]),
      ],
      '#states' => [
        'visible' => [
          ':input[name="enabled"]' => ['checked' => TRUE],
        ],
        'required' => [
          ':input[name="enabled"]' => ['checked' => TRUE],
        ],
      ],
      '#default_value' => $api_config->get('import_frequency'),
    ];
    
    $form['actions']['import_now'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Now'),
      '#name' => 'import_now',
    ];
    
    return $form;
  }

  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    if ($triggering_element['#name'] == 'import_now') {
      $this->drupalApiManager->fetchLatestProjects();
      drupal_set_message('Drupal projects imported.');
    }
    else {
      parent::submitForm($form, $form_state);
      $api_config = $this->config('drupal_api.settings');
    
      $api_config->set('import_enabled', $form_state->getValue('enabled'))
        ->set('import_frequency', $form_state->getValue('frequency'))
        ->save();
    }
  }
  
}
