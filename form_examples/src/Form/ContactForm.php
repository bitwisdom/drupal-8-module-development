<?php

namespace Drupal\form_examples\Form;

use Drupal\Core\Form\FormBase;
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

  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message($this->t('Thanks for submitting the form!'));
  }

}