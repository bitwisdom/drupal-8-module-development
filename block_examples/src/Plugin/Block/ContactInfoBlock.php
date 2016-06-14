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

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => 
        '<div>' . $this->t('Phone Number: @phone', ['@phone' => '555-666-7777']) . '</div>' .
        '<div>' . $this->t('Email: @email', ['@email' => 'someone@example.com']) . '</div>',
    ];
  }

  
}