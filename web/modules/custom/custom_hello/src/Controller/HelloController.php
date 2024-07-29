<?php

namespace Drupal\custom_hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Class HelloController.
 */
class HelloController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a HelloController object.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   */
  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Greet the user.
   *
   * @return array
   *   A render array containing the greeting message.
   */
  public function greet() {
    // Get the dynamically updated username.
    $user = $this->currentUser();
    $username = $this->currentUser->getDisplayName();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello ') . $user->getDisplayName(),
      '#cache' => [
        'tags' => ['user:' . $this->currentUser()->id()],
      ]
    ];
  }

}
