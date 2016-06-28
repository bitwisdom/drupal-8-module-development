<?php

namespace Drupal\config_examples\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

class APIConfigForm extends FormBase {
  
  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;
  
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }
  
  public static function create(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

    
  public function getFormId() {
    return 'config_examples_api_config_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $api_config = $this->configFactory->get('config_examples.api');
    
    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('API Endpoint URL'),
      '#required' => TRUE,
      '#default_value' => $api_config->get('url'),
    ];
    $form['key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#required' => TRUE,
      '#default_value' => $api_config->get('key'),
    ];
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Usernames to Retrieve Data For'),
      '#description' => $this->t('Enter the usernames separated by commas.'),
      '#required' => TRUE,
      '#default_value' => implode(', ', $api_config->get('username')),
    ];
    $form['automatic'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Automatic Import'),
    ];
    $form['automatic']['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $api_config->get('automatic_enabled'),
    ];
    $form['automatic']['frequency'] = [
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
      '#default_value' => $api_config->get('automatic_frequency'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Configuration'),
    ];
    return $form;
  }

  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = array_map('trim', explode(',', $form_state->getValue('username')));
    
    $api_config = $this->configFactory->getEditable('config_examples.api');
    
    $api_config->set('url', $form_state->getValue('url'))
        ->set('key', $form_state->getValue('key'))
        ->set('username', $username)
        ->set('automatic_enabled', $form_state->getValue('enabled'))
        ->set('automatic_frequency', $form_state->getValue('frequency'))
        ->save();
    drupal_set_message($this->t('Configuration saved.'));
  }

}