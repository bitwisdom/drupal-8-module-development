<?php

namespace Drupal\zoo\Form;

use Drupal\Core\Form\FormBase;
use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Database\Connection;
use \Drupal\Core\Datetime\DateFormatterInterface;
use \Drupal\Core\Cache\CacheTagsInvalidatorInterface;

class AnimalEditForm extends FormBase {
  
  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $connection;
  
  /**
   * @var \Drupal\Core\Datetime\DateFormatterInterface 
   */
  private $dateFormatter;
  
  /**
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  private $cacheTagInvalidator;
  
  function __construct(Connection $connection, DateFormatterInterface $dateFormatter, CacheTagsInvalidatorInterface $cacheTagInvalidator) {
    $this->connection = $connection;
    $this->dateFormatter = $dateFormatter;
    $this->cacheTagInvalidator = $cacheTagInvalidator;
  }

  public static function create(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('date.formatter'),
      $container->get('cache_tags.invalidator')
    );
  }

  public function getFormId() {
    // Unique ID of the form
    return 'zoo_animal_edit_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state, $animal_id = NULL) {
    $animal = NULL;
    if (!empty($animal_id)) {
      $animal = $this->connection->select('zoo_animal', 'a')
          ->fields('a')
          ->condition('a.animal_id', $animal_id)
          ->execute()
          ->fetch();
    }
    
    $form['animal_id'] = [
      '#type' => 'value',
      '#value' => !empty($animal) ? $animal->animal_id : NULL,
    ];
    
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
      '#default_value' => !empty($animal) ? $animal->name : NULL,
    ];
    $form['type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Type'),
      '#required' => TRUE,
      '#default_value' => !empty($animal) ? $animal->type : NULL,
    ];
    $default_birthdate = NULL;
    if (!empty($animal)) {
      $default_birthdate = \Drupal\Core\Datetime\DrupalDateTime::createFromTimestamp($animal->birthdate, 'UTC');      
    }
    $form['birthdate'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Birth Date'),
      '#default_value' => $default_birthdate,
      '#date_time_element' => 'none',
    ];
    $form['weight'] = [
      '#type' => 'textfield',
      '#size' => 5,
      '#field_suffix' => $this->t('kg'),
      '#required' => TRUE,
      '#default_value' => !empty($animal) ? $animal->weight : NULL,
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#rows' => 10,
      '#title' => $this->t('Description'),
      '#default_value' => !empty($animal) ? $animal->description : NULL,
    ];
    
    $habitat_options = $this->connection->select('zoo_habitat', 'h')
        ->fields('h')
        ->orderBy('h.name', 'ASC')
        ->execute()
        ->fetchAllKeyed();
    
    $form['habitat_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Habitat'),
      '#options' => $habitat_options,
      '#default_value' => !empty($animal) ? $animal->habitat_id : NULL,
      '#required' => TRUE,
    ];
    
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Animal'),
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    
    $weight = $form_state->getValue('weight');
    if (!is_numeric($weight) || $weight <= 0) {
      $form_state->setErrorByName('weight', 
          $this->t('Weight must be a positive number.')
      );
    }
    
  }

  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    ksm($form_state->getValues());
    $animal = [
      'name' => $form_state->getValue('name'),
      'type' => $form_state->getValue('type'),
      'birthdate' => $form_state->getValue('birthdate')->format('U'),
      'weight' => $form_state->getValue('weight'),
      'description' => $form_state->getValue('description'),
      'habitat_id' => $form_state->getValue('habitat_id'),
    ];
    $animal_id = $form_state->getValue('animal_id');
    if (!empty($animal_id)) {
      $animal['animal_id'] = $animal_id;
      $this->connection->update('zoo_animal')
        ->fields($animal)
        ->condition('animal_id', $form_state->getValue('animal_id'))
        ->execute();
    }
    else {
      $animal_id = $this->connection->insert('zoo_animal')
          ->fields($animal)
          ->execute();
    }
    
    \Drupal::messenger()->addMessage($this->t('Animal saved.'));
    
    $form_state->setRedirect('zoo.animal_view', 
        ['animal_id' => $animal_id]
    );
    
    $this->cacheTagInvalidator->invalidateTags(['animal.' . $animal_id, 'animal.list']);
  }

}