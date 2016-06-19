<?php

namespace Drupal\service_examples\Controller;

use \Drupal\Core\Controller\ControllerBase;

class ServiceExamplesController extends ControllerBase {
  
  /**
   * @var \Drupal\service_examples\HolidayProviderInterface
   */
  private $holidayProvider;
  
  function __construct(\Drupal\service_examples\HolidayProviderInterface $holidayProvider) {
    $this->holidayProvider = $holidayProvider;
  }

  public static function create(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
    return new static(
      $container->get('service_examples.holiday_provider')
    );
  }
  
  public function listHolidays() {
    
    $header = [
      $this->t('Date'),
      $this->t('Holiday'),
    ];
    $rows = [];
    foreach ($this->holidayProvider->getHolidays() as $date => $holiday) {
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