<?php

namespace Drupal\service_examples;

interface HolidayProviderInterface {
  
  /**
   * @return array
   */
  public function getHolidays();
  
}
