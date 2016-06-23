<?php

namespace Drupal\zoo\Controller;

use Drupal\Core\Controller\ControllerBase;

class AnimalViewController extends ControllerBase {
  
  /**
   *
   * @var \Drupal\Core\Database\Connection
   */
  private $connection;
  
  function __construct(\Drupal\Core\Database\Connection $connection) {
    $this->connection = $connection;
  }

  public static function create(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  
  /**
   * @param int $animal_id
   */
  public function animalViewTitle($animal_id) {
    $animal = $this->animalLoad($animal_id);
    return $animal->name;
  }
  
  /**
   * @param int $animal_id
   */
  public function animalView($animal_id) {
    $animal = $this->animalLoad($animal_id);
    return [
      '#theme' => 'zoo_animal',
      '#animal' => $animal,
    ];
  }
  
  /**
   * @param int $animal_id
   */
  private function animalLoad($animal_id) {
    return $this->connection->select('zoo_animal', 'a')
        ->fields('a')
        ->condition('animal_id', $animal_id)
        ->execute()
        ->fetch();
  }
  
}