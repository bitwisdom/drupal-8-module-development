<?php

namespace Drupal\block_examples\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello User' block.
 *
 * @Block(
 *   id = "block_examples_hello_user",
 *   admin_label = @Translation("Block Example: Hello User"),
 *   category = @Translation("Examples")
 * )
 */
class HelloUserBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $date_formatter = \Drupal::service('date.formatter');
    return [
      '#markup' => $this->t('Hello %name!!! You logged in at @login.', 
          [
            '%name' => $user->getDisplayName(),
            '@login' => $date_formatter->format($user->getLastLoginTime(), 'short'),
          ]
          ),
    ];
  }

}
