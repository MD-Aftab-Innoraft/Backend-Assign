<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * @file
 * This file contains the definition of OneTimeLoginLink Form.
 */
class OneTimeLoginLinkForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'one_time_login';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['element'] = [
      '#type' => 'markup',
      '#markup' => "<div class='message'></div>",
    ];
    $form['user_id'] = [
      '#title' => t('User Id'),
      '#type' => 'number',
      '#size' => 25,
      '#description' => t('Enter the user id'),
    ];

    $form['actions'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#ajax' => [
        'callback' => '::submitAjaxForm',
      ],
    ];
    return $form;
  }

  /**
   * Generates the one time login link of the corresponding user id.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Returns the message to be displayed.
   */
  public function generateLink(array &$form, FormStateInterface $form_state) {
    // Submitted user id.
    $uid = $form_state->getValue('user_id');
    // User object corresponding to the user id.
    $user = User::load($uid);
    if ($user) {
      // Generate a one-time login link.
      $otll = user_pass_reset_url($user);
      $result = $this->t('Generated Link: <a href="@link">@link</a>', ['@link' => $otll]);
    }
    else {
      $result = $this->t('User does not exist');
    }
    return $result;
  }

  /**
   * Displays the message after form submission using ajax.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The message to be displayed.
   */
  public function submitAjaxForm(array &$form, FormStateInterface $form_state): AjaxResponse {
    // Object of AjaxResponse class.
    $ajax_response = new AjaxResponse();
    // Contains the message to be displayed.
    $result = $this->generateLink($form, $form_state);
    $ajax_response->addCommand(new HtmlCommand('.message', $result));
    return $ajax_response;
  }

  /**
   * Returns a form submission message.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus('Form submitted');
  }
}
