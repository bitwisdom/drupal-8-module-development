<?php

namespace Drupal\route_examples\Controller;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\user\UserInterface;
use \Drupal\node\NodeInterface;
use \Drupal\Core\Session\AccountInterface;
use \Drupal\Core\Access\AccessResult;

class RouteExampleController extends ControllerBase {
  
  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private $curUser;
  
  /**
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  private $dateFormatter;
  
  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entTypeManager;
  
  /**
   * @param \Drupal\Core\Session\AccountProxyInterface $curUser
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entTypeManager
   */
  function __construct(\Drupal\Core\Session\AccountProxyInterface $curUser, 
      \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter, 
      \Drupal\Core\Entity\EntityTypeManagerInterface $entTypeManager) {
    $this->curUser = $curUser;
    $this->dateFormatter = $dateFormatter;
    $this->entTypeManager = $entTypeManager;
  }
  
  public static function create(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('date.formatter'),
      $container->get('entity_type.manager')
    );
  }
  
  
  public function helloWorld() {
    return [
      '#markup' => $this->t('Hello world!'),
    ];
  }
  
  public function helloUser() {
    return [
      '#markup' => $this->t('Hello @user', ['@user' => $this->curUser->getDisplayName()]),
    ];
  }
  
  public function helloUserTitle() {
    return $this->t('Hello @user', ['@user' => $this->curUser->getDisplayName()]);
  } 
  
  public function userInfo(UserInterface $user) {
    $markup = '<div>' . 
        $this->t('Name: @name', ['@name' => $user->getDisplayName()]) . 
        '</div>';
    $markup = '<div>' . 
        $this->t('Email: @email', ['@email' => $user->getEmail()]) . 
        '</div>';
    $markup .= '<div>' .
        $this->t('Created: @created', ['@created' => $this->dateFormatter->format($user->getCreatedTime())]) . 
        '</div>';
    $markup .= '<div>' . 
        $this->t('Last Login: @login', ['@login' => $this->dateFormatter->format($user->getLastLoginTime())]) .
        '</div>';
    
    return [
      '#markup' => $markup,
    ];
  }
  
  public function userInfoTitle(UserInterface $user) {
    return $this->t('Information About @user', ['@user' => $user->getDisplayName()]);
  }
  
  public function userInfoAccess(AccountInterface $account, UserInterface $user) {
    if ($account->hasPermission('view any user info')) {
      return AccessResult::allowed();
    }
    if ($account->hasPermission('view own user info') && $account->id() == $user->id()) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

  
  public function nodeList($limit, $type) {
    $query = $this->entTypeManager->getStorage('node')->getQuery();
    if ($type != 'all') {
      $query->condition('type', $type);
    }
    $nids = $query->range(0, $limit)
        ->execute();
    $nodes = $this->entTypeManager->getStorage('node')->loadMultiple($nids);

    $header = [
      $this->t('ID'),
      $this->t('Type'),
      $this->t('Title'),
    ];
    $rows = [];
    foreach ($nodes as $node) {
      $rows[] = [
        $node->id(),
        $node->bundle(),
        $node->getTitle(),
      ];
    }
    
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#caption' => $this->t('These are some nodes.'),
    ];
  }
  
  public function nodeCompare(NodeInterface $node1, NodeInterface $node2) {
    $diff = $node1->getCreatedTime() - $node2->getCreatedTime();
    
    return [
      '#markup' => t('Created Time Difference: @diff seconds', ['@diff' => $diff]),
    ];
  }


}
