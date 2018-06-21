<?php

namespace Drupal\block_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block that displays how log a user has been a member of the site.
 *
 * @Block(
 *   id = "block_assignment_user_age_block",
 *   admin_label = @Translation("Block Assignment: User Age"),
 *   category = @Translation("Assignments")
 * )
 */
class UserAgeBlock extends BlockBase {
  
  
  protected function blockAccess(\Drupal\Core\Session\AccountInterface $account) {
    // Only logged in users should see this block
    if ($account->isAnonymous()) {
      return \Drupal\Core\Access\AccessResult::forbidden();
    }
    return parent::blockAccess($account);
  }

    /**
   * {@inheritdoc}
   */
  public function build() {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $time = \Drupal::time()->getRequestTime();
    
    // Simple version
    $build =  [
      '#markup' => $this->t('You registered %interval seconds ago.', 
          [
            '%interval' => $time - $user->getCreatedTime(),
          ]
          ),
    ];
    
    // Bonus version
    $created = \DateTime::createFromFormat('U', $user->getCreatedTime());
    $now = \DateTime::createFromFormat('U', $time);
    $interval = $now->diff($created);
    
    $build =  [
      '#markup' => $this->t('You registered %interval ago.', 
          [
            '%interval' => $interval->format('%y years, %m months, %d days, %h hours, %s seconds'),
          ]
          ),
    ];
    
    return $build;
  }

}
