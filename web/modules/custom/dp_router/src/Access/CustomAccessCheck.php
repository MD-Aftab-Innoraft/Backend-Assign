<?php
namespace Drupal\dprouter\Access;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessCheckInterface;
use Symfony\Component\Routing\Route;

/**
 * Access Checker class.
 */
class CustomAccessCheck implements AccessCheckInterface {

  /**
   * Access Function to handle page access.
   *
   * @param AccountInterface $account
   *   Current user logged into the site.
   * 
   * @return void
   */
  public function access(AccountInterface $account) {
    $roles = $account->getRoles();
    if ($account->hasPermission('access the custom page') && !in_array('editor', $roles)) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

  /**
   * Function that specifies on which routes the access function executes.
   *
   * @param Route $route
   *    Current page route.
   * 
   * @return void
   */
  public function applies(Route $route) {
    return $route->getPath() === '/dprouter';
  }
}