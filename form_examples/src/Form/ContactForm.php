<?php

namespace Drupal\form_examples\Form;

use \Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;

class ContactForm extends FormBase {
  
  public function getFormId() {
    // Unique ID of the form
    return 'form_examples_contact_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Address'),
      '#required' => TRUE,
    ];
    $form['message'] = [
      '#type' => 'textarea',
      '#rows' => 10,
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send Message'),
    ];
    return $form;
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    
    $email = $form_state->getValue('email');
    if (!preg_match('/\.edu$/', $email)) {
      $form_state->setErrorByName('email', 
          $this->t('Only .edu addresses are allowed.')
      );
    }
    
    if (strlen($form_state->getValue('message')) < 50) {
      $form_state->setErrorByName('message', 
          $this->t('Please write a message of at least 50 characters.')
      );
    }
    
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage($this->t('Thanks for submitting the form!'));
    
    $keys = ['name', 'email', 'message'];
    foreach ($keys as $key) {
      \Drupal::messenger()->addMessage($this->t('@key: @value', 
        [
          '@key' => $key,
          '@value' => $form_state->getValue($key),
        ]
      ));
    }
    
    $form_state->setRedirect('<front>');
    
  }

}