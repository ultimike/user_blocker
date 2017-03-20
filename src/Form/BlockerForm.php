<?php

namespace Drupal\user_blocker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BlockerForm.
 *
 * @package Drupal\user_blocker\Form
 */
class BlockerForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_blocker_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Enter the username of the user you want to block.'),
      '#maxlength' => 64,
      '#size' => 64,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
        drupal_set_message($key . ': ' . $value);
    }

  }

}
