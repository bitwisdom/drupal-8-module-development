<?php

namespace Drupal\service_examples\Controller;

use \Drupal\Core\Controller\ControllerBase;

class ServiceExamplesController extends ControllerBase {
  
  public function listHolidays() {
    
    $holidays = [
      '01-01' => "New Year's Day",
      '07-04' => "Independence Day",
      '12-25' => "Christmas",
    ];
    
    $header = [
      $this->t('Date'),
      $this->t('Holiday'),
    ];
    $rows = [];
    foreach ($holidays as $date => $holiday) {
      $rows[] = [
        $date,
        $holiday,
      ];
    }
        
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  }
}