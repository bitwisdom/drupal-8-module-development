<?php

namespace Drupal\block_examples\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Contact Info' block.
 *
 * @Block(
 *   id = "block_examples_contact_info",
 *   admin_label = @Translation("Block Example: Contact Info"),
 *   category = @Translation("Examples")
 * )
 */
class ContactInfoBlock extends BlockBase {

  public function defaultConfiguration() {
    return [
      'phone' => '',
      'email' => '',
    ];
  }
  
  public function blockForm($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone Number'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['phone'],
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['email'],
    ];
    return $form;
  }
  
  public function blockSubmit($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if (!$form_state->getErrors()) {
      $this->configuration['phone'] = $form_state->getValue('phone');
      $this->configuration['email'] = $form_state->getValue('email');
    }
    parent::blockSubmit($form, $form_state);
  }
  
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => 
        '<div>' . $this->t('Phone Number: @phone', ['@phone' => $this->configuration['phone']]) . '</div>' .
        '<div>' . $this->t('Email: @email', ['@email' => $this->configuration['email']]) . '</div>',
    ];
  }

  
}