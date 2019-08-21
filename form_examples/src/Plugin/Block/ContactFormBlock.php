<?php

namespace Drupal\form_examples\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Contact Form' block.
 *
 * @Block(
 *   id = "form_examples_contact_form",
 *   admin_label = @Translation("Form Examples: Contact Form"),
 *   category = @Translation("Examples")
 * )
 */
class ContactFormBlock extends BlockBase implements ContainerFactoryPluginInterface {
    
  /**
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;
  
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $formBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = $this->formBuilder->getForm('\Drupal\form_examples\Form\ContactForm');
    return [
      'some_text' => [
        '#markup' => '<p>' . $this->t('Here is some HTML.') . '</p>',
      ],
      'form' => $form
    ];
  }

}
