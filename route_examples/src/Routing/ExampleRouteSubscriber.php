<?php

namespace Drupal\route_examples\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class ExampleRouteSubscriber extends RouteSubscriberBase {

  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('user.logout')) {
      $route->setPath('/logout');
    }
    
  }

}
