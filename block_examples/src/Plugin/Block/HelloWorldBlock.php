<?php

namespace Drupal\block_examples\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableMetadata;

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
    $cache = new CacheableMetadata();
    $cache->setCacheMaxAge(5);
    $build = [
      '#markup' => $this->t('Hello world! The current time is @time', 
          [
            '@time' => \Drupal::service('date.formatter')->format(REQUEST_TIME, 'custom', 'H:i:s')
          ]
        ),
    ];
    $cache->applyTo($build);
    return $build;
  }
}
