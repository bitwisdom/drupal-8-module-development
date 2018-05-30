<?php

namespace Drupal\drupal_api\Service;

use GuzzleHttp\Client;

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
  
  public function __construct(Client $client) {
    $this->client = $client;
  }

  /**
   * @return array
   */
  public function getLatestModules() {
    $modules = [];
    $data = NULL;
    try {
      $response = $this->client->get('https://www.drupal.org/api-d7/node.json?type=project_module&limit=10&sort=created&direction=DESC&field_project_type=full');
      if ($response->getStatusCode() == 200) {
        $json = $response->getBody()->getContents();
        $data = json_decode($json);
      }
      else {
        drupal_set_message(t('Error retrieving module information.'), 'error');
      }
    } catch (\Exception $ex) {
      drupal_set_message(t('Error retrieving module information.'), 'error');
    }
    if (!empty($data)) {
      foreach ($data->list as $module_data) {
        $modules[] = [
          'name' => $module_data->title,
          'created' => $module_data->created,
          'url' => $module_data->url,
          'description' => !empty($module_data->body->value) ? $module_data->body->value : '',
        ];
     }
    }
    
    return $modules;
  }
  
  /**
   * @return array
   */
  public function getLatestThemes() {
    $modules = [];
   
    $data = NULL;
    try {
      $response = $this->client->get('https://www.drupal.org/api-d7/node.json?type=project_theme&limit=10&sort=created&direction=DESC&field_project_type=full');
      if ($response->getStatusCode() == 200) {
        $json = $response->getBody()->getContents();
        $data = json_decode($json);
      }
      else {
        drupal_set_message(t('Error retrieving theme information.'), 'error');
      }
    } catch (\Exception $ex) {
      drupal_set_message(t('Error retrieving theme information.'), 'error');
    }
    if (!empty($data)) {
      foreach ($data->list as $theme_data) {
        $modules[] = [
          'name' => $theme_data->title,
          'created' => $theme_data->created,
          'url' => $theme_data->url,
          'description' => !empty($theme_data->body->value) ? $theme_data->body->value : '',
        ];
     }
    }
    
    return $modules;
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
