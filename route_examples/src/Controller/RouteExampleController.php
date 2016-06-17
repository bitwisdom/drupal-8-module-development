<?php

namespace Drupal\route_examples\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\UserInterface;

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
  
  public function userInfo(UserInterface $user) {
    $date_formater = \Drupal::service('date.formatter');
    $markup = '<div>' . 
        $this->t('Name: @name', ['@name' => $user->getDisplayName()]) . 
        '</div>';
    $markup = '<div>' . 
        $this->t('Email: @email', ['@email' => $user->getEmail()]) . 
        '</div>';
    $markup .= '<div>' .
        $this->t('Created: @created', ['@created' => $date_formater->format($user->getCreatedTime())]) . 
        '</div>';
    $markup .= '<div>' . 
        $this->t('Last Login: @login', ['@login' => $date_formater->format($user->getLastLoginTime())]) .
        '</div>';
    return [
      '#markup' => $markup,
    ];
  }
  
  public function userInfoTitle(UserInterface $user) {
    return $this->t('Information About @user', ['@user' => $user->getDisplayName()]);
  }

}
