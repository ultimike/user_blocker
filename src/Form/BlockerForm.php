<?php declare(strict_types = 1);

namespace Drupal\user_blocker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a User blocker form.
 */
final class BlockerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'user_blocker_blocker';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Block User'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if (mb_strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('name', $this->t('Message should be at least 10 characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $username = $form_state->getValue('username');
    $user = user_load_by_name($username);
    $user->block();
    $user->save();
    drupal_set_message($this->t('User @username has been blocked.', ['@username' => $username]));
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $user = user_load_by_name($username);
    if (empty($user)) {
      $form_state->setError(
        $form['username'],
        $this->t('User @username was not found.', ['@username' => $username])
      );
    }
    else {
      $current_user = \Drupal::currentUser();
      if ($user->id() == $current_user->id()) {
        $form_state->setError(
          $form['username'],
          $this->t('You cannot block your own account.')
        );
      }
    }
  }

}
