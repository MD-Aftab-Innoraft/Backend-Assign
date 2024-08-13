<?php

namespace Drupal\dprouter\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 *  Class SimpleController for managing dpmodule functionality.
 */
class SimpleController extends ControllerBase {

  /**
   * @var AccountInterface
   *   Current User of the site.
   */
  protected $currentUser;

  /**
   * Constructor of the Controller class.
   *
   * @param AccountInterface $current_user
   *   Current User of the site.
   * 
   * @return void
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * Function to greet currently logged-in user.
   * 
   * @return void
   */
  public function displayUsername() {
    $username = $this->currentUser->getAccountName();
    return [
      '#markup' => $this->t('Hello, @username', ['@username' => $username]),
    ];
  }

  /**
   * Displays the dynamic id component of the page url.
   *
   * @param int $id
   *   Dynamic path component present in page url.
   * 
   * @return void
   */
  public function displayCustom(int $id) {
    $username = $this->currentUser->getAccountName();
    return [
      '#markup' => $this->t('Hello, @username. This is the page no. @id', ['@username' => $username, '@id' => $id]),
    ];
  }
}