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
    $user = \Drupal::currentUser();
    return [
      '#markup' => $this->t('Hello @name!!!', 
          ['@name' => $user->getDisplayName()]
          ),
    ];
  }

}
