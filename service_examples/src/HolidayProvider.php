<?php

namespace Drupal\service_examples;

use Drupal\service_examples\HolidayProviderInterface;

class HolidayProvider implements HolidayProviderInterface {
  
  public function getHolidays() {
    return [
      '01-01' => "New Year's Day",
      '07-04' => "Independence Day",
      '12-25' => "Christmas",
    ];
  }

}
