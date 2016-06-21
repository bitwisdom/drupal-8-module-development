<?php

namespace Drupal\zoo\Controller;

use Drupal\Core\Controller\ControllerBase;

class AnimalsListController extends ControllerBase {
  
  public function listAnimals() {
    
    $header = [
      $this->t('Name'),
      $this->t('Type'),
      $this->t('Age'),
      $this->t('Weight'),
    ];
    $rows = [];
    
    // Query for animals here.
    
    return [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];
  }
}
