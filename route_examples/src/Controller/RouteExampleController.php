<?php

namespace Drupal\route_examples\Controller;

use \Drupal\Core\Controller\ControllerBase;

class RouteExampleController extends ControllerBase {
  
  public function helloWorld() {
    return [
      '#markup' => $this->t('Hello world!'),
    ];
  }
}
