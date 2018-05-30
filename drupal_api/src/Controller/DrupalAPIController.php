<?php

namespace Drupal\drupal_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\drupal_api\Service\DrupalAPIManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Description of DrupalAPIController
 *
 * @author wayne
 */
class DrupalAPIController extends ControllerBase {
  
  /**
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;
  
  /**
   *
   * @var \Drupal\drupal_api\Service\DrupalAPIManagerInterface
   */
  protected $apiManager;
 
  
  /**
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   * @param \Drupal\drupal_api\Service\DrupalAPIManagerInterface $apiManager
   */
  function __construct(DateFormatterInterface $dateFormatter, DrupalAPIManagerInterface $apiManager) {
    $this->dateFormatter = $dateFormatter;
    $this->apiManager = $apiManager;
  }
  
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('drupal_api.manager')
    );
  }
  
  
  public function latestModules() {
    $modules = $this->apiManager->getLatestModules();
    
    $output = '';
    
    foreach ($modules as $module) {
      $output .= '<div class="module">';
      $output .= '<h2><a href="' . $module['url'] . '">' . $module['name'] . '</a></h2>';
      $output .= '<div class="created">' . $this->dateFormatter->format($module['created'], 'custom', 'Y-m-d H:i:s') . '</div>';
      $output .= '<div class="description">' . $module['description'] . '</div>';
    }
    
    return [
      '#markup' => $output,
    ];
   }
   
   public function latestThemes() {
    $modules = $this->apiManager->getLatestThemes();
    
    $output = '';
    
    foreach ($modules as $module) {
      $output .= '<div class="module">';
      $output .= '<h2><a href="' . $module['url'] . '">' . $module['name'] . '</a></h2>';
      $output .= '<div class="created">' . $this->dateFormatter->format($module['created'], 'custom', 'Y-m-d H:i:s') . '</div>';
      $output .= '<div class="description">' . $module['description'] . '</div>';
    }
    
    return [
      '#markup' => $output,
    ];
   }
   
  /* Version 1
   * 
   
  public function latestModules() {
     $output = '';
     
     $output .= '<div class=“module”>
       <h2>Module #1</h2>
       <div class=“created”>2018-05-24 12:00:00</div>
       <div class=“description”>This is module #1</div>
       </div>';
     
     $output .= '<div class=“module”>
       <h2>Module #2</h2>
       <div class=“created”>2018-05-23 12:00:00</div>
       <div class=“description”>This is module #2</div>
       </div>';
     
     $output .= '<div class=“module”>
       <h2>Module #3</h2>
       <div class=“created”>2018-05-22 12:00:00</div>
       <div class=“description”>This is module #3</div>
       </div>';
     
     return [
       '#markup' => $output,
     ];
   }
   * 
   */
}
