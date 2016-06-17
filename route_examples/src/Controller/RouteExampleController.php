<?php

namespace Drupal\route_examples\Controller;

use \Drupal\Core\Controller\ControllerBase;

class RouteExampleController extends ControllerBase {
  
  public function helloWorld() {
    return [
      '#markup' => $this->t('Hello world!'),
    ];
  }
  
  public function helloUser() {
    $session = \Drupal::currentUser();
    return [
      '#markup' => $this->t('Hello @user', ['@user' => $session->getDisplayName()]),
    ];
  }
  
  public function helloUserTitle() {
    $session = \Drupal::currentUser();
    return $this->t('Hello @user', ['@user' => $session->getDisplayName()]);
  } 

}
