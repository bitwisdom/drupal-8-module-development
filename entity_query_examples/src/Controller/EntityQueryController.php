<?php

namespace Drupal\entity_query_examples\Controller;

use Drupal\Core\Controller\ControllerBase;

class EntityQueryController extends ControllerBase {
    
  public function userList() {
    $query = $this->entityTypeManager()->getStorage('user')->getQuery();
    $results = $query->execute();
    ksm($results);
    
    $header = [
      $this->t('Username'),
      $this->t('Email'),
    ];
    $rows = [];
    
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  }
}