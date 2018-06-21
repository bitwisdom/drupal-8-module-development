<?php

namespace Drupal\block_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block that displays how log a user has been a member of the site.
 *
 * @Block(
 *   id = "block_assignment_user_age_block_config",
 *   admin_label = @Translation("Block Assignment: User Age - Configurable"),
 *   category = @Translation("Assignments")
 * )
 */
class UserAgeBlockConfigurable extends BlockBase {
  
  
  protected function blockAccess(\Drupal\Core\Session\AccountInterface $account) {
    // Only logged in users should see this block
    if ($account->isAnonymous()) {
      return \Drupal\Core\Access\AccessResult::forbidden();
    }
    return parent::blockAccess($account);
  }
  
  public function defaultConfiguration() {
    return [
      'message' => 'You registered [time] ago.',
    ];
  }
  
  public function blockForm($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['message'],
    ];
    return $form;
  }
  
  public function blockSubmit($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    if (!$form_state->getErrors()) {
      $this->configuration['message'] = $form_state->getValue('message');
    }
    parent::blockSubmit($form, $form_state);
  }

    /**
   * {@inheritdoc}
   */
  public function build() {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $time = \Drupal::time()->getRequestTime();
    
    $message = $this->configuration['message'];
    $message_for_formatting = str_replace('[time]', '%interval', $message);
    
    // Simple version
    $build =  [
      '#plain_text' => str_replace('[time]', $time - $user->getCreatedTime(), $message),
    ];
    
    // Bonus version
    $created = \DateTime::createFromFormat('U', $user->getCreatedTime());
    $now = \DateTime::createFromFormat('U', $time);
    $interval = $now->diff($created);
    
    $build =  [
      '#plain_text' => str_replace('[time]', $interval->format('%y years, %m months, %d days, %h hours, %s seconds'), $message),
    ];
    
    return $build;
  }

}
