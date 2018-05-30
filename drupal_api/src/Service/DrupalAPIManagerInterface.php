<?php

namespace Drupal\drupal_api\Service;

/**
 *
 * @author wayne
 */
interface DrupalAPIManagerInterface {
  
  /**
   * @return array
   */
  public function getLatestModules();
  
    /**
   * @return array
   */
  public function getLatestThemes();
  
}
