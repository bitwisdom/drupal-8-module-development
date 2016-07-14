<?php

namespace Drupal\block_examples\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello World' block.
 *
 * @Block(
 *   id = "block_examples_hello_world",
 *   admin_label = @Translation("Block Example: Hello World"),
 *   category = @Translation("Examples")
 * )
 */
class HelloWorldBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Hello world! The current time is @time', 
          [
            '@time' => \Drupal::service('date.formatter')->format(REQUEST_TIME, 'custom', 'H:i:s')
          ]
        ),
      '#cache' => [
        'max-age' => 20,
      ],
    ];
  }

}
