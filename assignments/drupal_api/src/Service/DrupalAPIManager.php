<?php

namespace Drupal\drupal_api\Service;

use GuzzleHttp\Client;
use Drupal\Core\Database\Connection;
use \Drupal\Core\Cache\CacheTagsInvalidatorInterface;

/**
 * Description of DrupalAPIManager
 *
 * @author wayne
 */
class DrupalAPIManager implements DrupalAPIManagerInterface {
  
  
  /**
   * @var \GuzzleHttp\Client
   */
  protected $client;
  
  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;
  
    /**
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  private $cacheTagInvalidator;
  
  public function __construct(Client $client, Connection $connection, CacheTagsInvalidatorInterface $cacheTagInvalidator) {
    $this->client = $client;
    $this->connection = $connection;
    $this->cacheTagInvalidator = $cacheTagInvalidator;
  }

  /**
   * @return array
   */
  public function getLatestModules() {
    return $this->getLatestProjects('project_module');
  }
  
  /**
   * @return array
   */
  public function getLatestThemes() {
    return $this->getLatestProjects('project_theme');
  }
  
  protected function getLatestProjects($type = NULL) {
    $query = $this->connection->select('drupal_api', 'd')
        ->fields('d')
        ->orderBy('created', 'DESC')
        ->range(0, 10);
    if (!empty($type)) {
      $query->condition('type', $type);
    }
    return $query->execute()
        ->fetchAll(\PDO::FETCH_ASSOC);
  }
  
  /**
   * Fetch the latest projects fro Drupal.org API
   */
  public function fetchLatestProjects() {
    $data = NULL;
    try {
      $response = $this->client->get('https://www.drupal.org/api-d7/node.json?type[]=project_theme&type[]=project_module&limit=100&sort=created&direction=DESC&field_project_type=full');
      if ($response->getStatusCode() == 200) {
        $json = $response->getBody()->getContents();
        $data = json_decode($json);
      }
    } catch (\Exception $ex) {
      watchdog_exception('drupal_api', $ex);
    }
    if (!empty($data)) {
      foreach ($data->list as $project_data) {
        // Check if we already have the NID in the DB.
        $project = $this->connection->select('drupal_api', 'd')
            ->fields('d')
            ->condition('d.id', $project_data->nid)
            ->execute()
            ->fetch();
        
        if (empty($project)) {
          // Only import new projects
          $this->connection->insert('drupal_api')
              ->fields([
                'id' => $project_data->nid,
                'name' => $project_data->title,
                'type' => $project_data->type,
                'created' => $project_data->created,
                'url' => $project_data->url,
                'description' => !empty($project_data->body->value) ? $project_data->body->value : ''
              ])
              ->execute();
        }
      }
    }
    $this->cacheTagInvalidator->invalidateTags(['drupal_api.project.list']);
  }
  
  /**
   * Version 1
   * @return array
  
  public function getLatestModules() {
    $modules = [];
    
    $modules[] = [
      'name' => 'Module #1',
      'created' => '1527163200',
      'description' => 'This is module #1',
      'url' => 'https://example.com',
    ];
    
    $modules[] = [
      'name' => 'Module #2',
      'created' => '1527076800',
      'description' => 'This is module #2',
      'url' => 'https://example.com',
    ];
    
    $modules[] = [
      'name' => 'Module #3',
      'created' => '1526990400',
      'description' => 'This is module #3',
      'url' => 'https://example.com',
    ];
    
    return $modules;
  }
*/
}
