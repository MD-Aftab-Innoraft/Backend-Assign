<?php

declare(strict_types=1);

namespace Drupal\custom_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

/**
 * @file
 * Configure employee settings for this site.
 */
final class CustomConfigFormAjax extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'custom_form_config_ajax';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['custom_form.settings.ajax'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Field for full name.
    $form['fullname'] = [
      '#title' => $this->t('Full Name'),
      '#type' => 'textfield',
      '#size' => 25,
      '#maxlength' => 30,
      '#suffix' => '<div class="error" id="name"></div>',
      '#ajax' => [
        'callback' => '::validateName',
        'event' => 'keyup',
      ],
    ];

    // Field for phone number.
    $form['phone'] = [
      '#title' => $this->t('Phone Number'),
      '#type' => 'tel',
      '#size' => 10,
      '#attributes' => [
        'pattern' => '[7-9]{1}[0-9]{9}',
        'maxlength' => 10,
      ],
      '#suffix' => '<div class="error" id="phone"></div>',
      '#ajax' => [
        'callback' => '::validatePhone',
        'event' => 'keyup',
      ],
    ];

    // Field for email.
    $form['email'] = [
      '#title' => $this->t('Email ID'),
      '#type' => 'email',
      '#size' => 30,
      '#suffix' => '<div class="error" id="email"></div>',
      '#ajax' => [
        'callback' => '::validateEmail',
        'event' => 'keyup',
      ],
    ];

    // Field for gender.
    $form['gender'] = [
      '#title' => $this->t('Gender'),
      '#type' => 'radios',
      '#options' => [
        'male' => 'Male',
        'female' => 'Female',
      ],
    ];

    // Submit button for the form.
    $form['actions'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Checks the name validity.
   *
   * Checks if the name follows proper naming pattern
   * and is within 30 characters.
   *
   * @param string $name
   *   Contains the employee name.
   *
   * @return bool
   *   Returns the result of validation.
   */
  public function checkNameValidity(string $name) {
    // Regex expression for name check.
    $nameregex = "/^[a-zA-Z ]{5,30}$/";
    $name = trim($name);
    if (!preg_match($nameregex, $name)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Checks the phone validity.
   *
   * Checks if the number is an Indian phone number.
   *
   * @param string $phone_no
   *   Contains the phone number.
   *
   * @return bool
   *   Returns the result of validation.
   */
  public function checkPhoneValidity(string $phone_no) {
    // Regex expression for phone number check.
    $mobileregex = "/^[1-9][0-9]{9}$/";
    $phone_no = trim($phone_no);
    if (!preg_match($mobileregex, $phone_no)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Checks the email validity.
   *
   * Contains the RFC validation and proper domain validation.
   *
   * @param string $email
   *   Contains the email address.
   *
   * @return bool
   *   Returns the result of validation.
   */
  public function checkEmailValidity(string $email) {
    // Object of EmailValidator class.
    $validator = new EmailValidator();
    // List of allowed domains.
    $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'mail.com', 'innoraft.com'];
    $email = trim($email);
    if (!$validator->isValid($email, new RFCValidation())) {
      return FALSE;
    }
    else {
      // Splitting the email into parts.
      $parts = explode('@', $email);
      // Extracting the email domain.
      $domain = array_pop($parts);
      // Checking if the domian is one of allowed domains.
      if (!in_array($domain, $allowed_domains)) {
        return FALSE;
      }
      return TRUE;
    }
  }

  /**
   * Validates the name using Ajax.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state object.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Returns the message.
   */
  public function validateName(array &$form, FormStateInterface $form_state) {
    // Object of AjaxResponse class.
    $ajax_response = new AjaxResponse();
    $result = $this->checkNameValidity($form_state->getValue('fullname'));
    if ($result == FALSE) {
      $ajax_response->addCommand(new HtmlCommand('#name', 'Invalid Name.'));
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#name', 'Valid'));
    }
    return $ajax_response;
  }

  /**
   * Validates the email.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state object.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Returns the message.
   */
  public function validateEmail(array &$form, FormStateInterface $form_state) {
    // Object of AjaxResponse class.
    $ajax_response = new AjaxResponse();
    $result = $this->checkEmailValidity($form_state->getValue('email'));

    if ($result == FALSE) {
      $ajax_response->addCommand(new HtmlCommand('#email', 'Invalid email address.'));
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#email', 'Valid'));
    }
    return $ajax_response;
  }

  /**
   * Validates the phone number.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state object.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Returns the message.
   */
  public function validatePhone(array &$form, FormStateInterface $form_state) {
    // Object of AjaxResponse class.
    $ajax_response = new AjaxResponse();
    $result = $this->checkPhoneValidity($form_state->getValue('phone'));

    if ($result == FALSE) {
      $ajax_response->addCommand(new HtmlCommand('#phone', 'Invalid mobile number.'));
    }
    else {
      $ajax_response->addCommand(new HtmlCommand('#phone', 'Valid'));
    }
    return $ajax_response;
  }

  /**
   * Checks form validity on submission.
   *
   * @param array $form
   *   The form object.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state object.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $fullname = trim($form_state->getValue('fullname'));
    $phone_no = trim($form_state->getValue('phone'));
    $email = trim($form_state->getValue('email'));
    // Result for full name validity check.
    $fullnameValidityResult = $this->checkNameValidity($fullname);
    // Result for email validity check.
    $emailValidityResult = $this->checkEmailValidity($email);
    // Result for phone number validity check.
    $phoneValidityResult = $this->checkPhoneValidity($phone_no);

    // Checking if any fields are empty.
    if (empty($fullname) || empty($phone_no) || empty($email)) {
      $form_state->setErrorByName('empty_error', $this->t('Empty fields present'));
    }
    // Checking the validity results.
    elseif ($fullnameValidityResult === FALSE || $emailValidityResult === FALSE ||
    $phoneValidityResult === FALSE) {
      $form_state->setErrorByName('validity', 'Invalid Input Present');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('custom_form.settings.ajax')
      ->set('fullname', $form_state->getValue('fullname'))
      ->set('phone', $form_state->getValue('phone'))
      ->set('email', $form_state->getValue('email'))
      ->set('gender', $form_state->getValue('gender'))
      ->save();
    \Drupal::messenger()->addMessage($this->t('Form Submitted Successfully'));
  }
}
