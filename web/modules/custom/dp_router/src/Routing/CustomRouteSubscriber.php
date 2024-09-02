<?php

namespace Drupal\dprouter\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class to manage existing Routes.
 */
class CustomRouteSubscriber extends RouteSubscriberBase {

  /**
   * Function to alter existing routes.
   *
   * @param RouteCollection $collection
   *   A set of route instances.
   * 
   * @return void
   */
  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('dprouter.displayUsername')) {
      $route->setRequirement('_permission', 'access the custom page');
      $route->setRequirement('_role', 'administrator');
      $route->setRequirement('_custom_access', 'dprouter.custom_access_check::access');
    }
  }
}
