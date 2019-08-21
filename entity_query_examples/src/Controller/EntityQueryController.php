<?php

namespace Drupal\entity_query_examples\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\CacheableMetadata;

class EntityQueryController extends ControllerBase {
    
  public function userList() {
    $query = $this->entityTypeManager()->getStorage('user')->getQuery();
    $query->condition('name', 'r%', 'LIKE');
    $results = $query->execute();
    
    $header = [
      $this->t('Username'),
      $this->t('Email'),
    ];
    $rows = [];
    
    $users = $this->entityTypeManager()->getStorage('user')->loadMultiple($results);
    
    foreach ($users as $user) {
      $rows[] = [
        $user->getDisplayName(),
        $user->getEmail()
      ];
    } 
    
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  }
  
  public function nodeList() {
    $cache = new CacheableMetadata();
    $query = $this->entityTypeManager()->getStorage('node')->getQuery();
    $query->notExists('field_state')
        ->condition('type', 'article')
        ->sort('title');
    $results = $query->execute();
    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($results);
    
    $header = [
      $this->t('ID'),
      $this->t('Type'),
      $this->t('Title'),
      $this->t('Author'),
      $this->t('Post Date'),
    ];
    $rows = [];
    $authors = [];
    foreach ($nodes as $node) {
      $cache->addCacheableDependency($node);
      $cache->addCacheableDependency($node->getOwner());
      $rows[] = [
        $node->id(),
        $node->bundle(),
        $node->getTitle(),
        $node->getOwner()->getDisplayName(),
        \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'long'),
      ];
      $authors[$node->getOwner()->id()] = $node->getOwner()->id();
    }
    
    $cache_tags = ['node_list'];
    foreach ($authors as $uid) {
      $cache_tags[] = 'user:' . $uid;
    }
    
    $build = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
    $node_list_cache_tags = \Drupal::entityTypeManager()->getDefinition('node')->getListCacheTags();
    $cache->addCacheTags($node_list_cache_tags);
    $node_list_cache_contexts = \Drupal::entityTypeManager()->getDefinition('node')->getListCacheContexts();
    $cache->addCacheContexts($node_list_cache_contexts);
    $cache->addCacheContexts(['timezone']);
    $cache->applyTo($build);
    return $build;
  }
}
