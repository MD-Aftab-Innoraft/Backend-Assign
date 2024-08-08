<?php

declare(strict_types=1);

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

/**
 * @file
 * Configure employee settings for this site.
 */
final class CustomConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'custom_form_config';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['custom_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Full name field.
    $form['fullname'] = [
      '#title' => $this->t('Full Name'), 
      '#type' => 'textfield',
      '#size' => 25,
      '#maxlength' => 25,
    ];

    // Phone number field.
    $form['phone'] = [
      '#title' => $this->t('Phone Number'),
      '#type' => 'tel',
      '#size' => 10,
      '#minlength' => 10,
      '#maxlength' => 10,
    ];

    // Email field.
    $form['email'] = [
      '#title' => $this->t('Email ID'),
      '#type' => 'email',
      '#size' => 30,
    ];

    // Gender field.
    $form['gender'] = [
      '#title' => $this->t('Gender'),
      '#type' => 'radios',
      '#options' => [
        'male' => 'Male',
        'female' => 'Female',
      ],
    ];

    // The form submit button.
    $form['actions'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // Full name of employee.
    $fullname = trim($form_state->getValue('fullname'));
    // Phone number of employee.
    $phone_no = trim($form_state->getValue('phone'));
    // Email of employee.
    $email = trim($form_state->getValue('email'));

    // Object of EmailValidator class.
    $validator = new EmailValidator();
    // Regex for checking name.
    $nameregex = "/^[a-zA-Z-' ]*$/";
    // Regex for checking mbile number.
    $mobileregex = "/^[1-9][0-9]{9}$/";
    // Allowed email domains.
    $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'mail.com', 'innoraft.com'];

    // Checking if any field is empty else validating for correct entry.
    if (empty($fullname) || empty($phone_no) || empty($email)) {
      $form_state->setErrorByName('empty_error', $this->t('Empty fields present'));
    }
    else {
      if (strlen($fullname) > 30) {
        $form_state->setErrorByName('name_error', $this->t('Maximum 30 characters allowed for name!'));
      }
      if (!preg_match($nameregex, $fullname)) {
        $form_state->setErrorByName('name_error', $this->t('Invalid user name!'));
      }
      if (!preg_match($mobileregex, $phone_no)) {
        $form_state->setErrorByName('phone_error', $this->t('Invalid mobile number!'));
      }
      if (!$validator->isValid($email, new RFCValidation())) {
        $form_state->setErrorByName('email_error', $this->t('Invalid email address!'));
      }
      else {
        // Splitting the email into parts.
        $parts = explode('@', $email);
        // Extracting the email domain.
        $domain = array_pop($parts);
        // Checking for allowed domains.
        if (!in_array($domain, $allowed_domains)) {
          $form_state->setErrorByName('email_error', $this->t('Domain name not allowed'));
        }
      }
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Setting the submitted configuration.
    $this->config('custom_form.settings')
      ->set('fullname', $form_state->getValue('fullname'))
      ->set('phone', $form_state->getValue('phone'))
      ->set('email', $form_state->getValue('email'))
      ->set('gender', $form_state->getValue('gender'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
