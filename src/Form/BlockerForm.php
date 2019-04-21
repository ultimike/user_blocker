<?php declare(strict_types = 1);

namespace Drupal\user_blocker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

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
    // This is the non-autocomplete version.
    /*
    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Enter the username of the user you want to block.'),
      '#maxlength' => 64,
      '#size' => 64,
    );
    */

    // This is the autocomplete version.
    $form['uid'] = array(
      '#title' => $this->t('Username'),
      '#description' => $this->t('Enter the username of the user you want to block.'),
      '#type' => 'entity_autocomplete',
      '#target_type' => 'user',
    );

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Block User'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This the non-autocomplete version.
   */
  /*
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $username = $form_state->getValue('username');
    $user = user_load_by_name($username);
    $user->block();
    $user->save();
    drupal_set_message($this->t('User @username has been blocked.', ['@username' => $username]));
  }
  */
  /**
   * {@inheritdoc}
   *
   * This the autocomplete version.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $uid = $form_state->getValue('uid');
    /** @var User $user */
    $user = user_load($uid);
    $user->block();
    $user->save();
    drupal_set_message($this->t('User @username has been blocked.', ['@username' => $user->getAccountName()]));
  }

  /**
  * {@inheritdoc}
  *
  * This is the non-autocomplete version.
  */
  /*
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $username = $form_state->getValue('username');
    $user = user_load_by_name($username);
    if (empty($user)) {
      $form_state->setError(
        $form['username'],
        $this->t('User %username was not found.', ['%username' => $username])
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
  */
  /**
   * {@inheritdoc}
   *
   * This is the autocomplete version.
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $uid = $form_state->getValue('uid');
    $current_user = \Drupal::currentUser();
    if ($uid == $current_user->id()) {
      $form_state->setError(
        $form['uid'],
        $this->t('You cannot block your own account.')
      );
    }
  }

  /**
   * {@inheritdoc}
   *
   * This the non-autocomplete version.
   */
  /*
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $user = user_load_by_name($username);
    $user->block();
    $user->save();
    drupal_set_message($this->t('User %username has been blocked.', ['%username' => $username]));
  }
  */
  /**
   * {@inheritdoc}
   *
   * This the autocomplete version.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uid = $form_state->getValue('uid');
    $user = User::load($uid);
    $user->block();
    $user->save();
    $this->messenger()->addMessage(t('User %username has been blocked.', ['%username' => $user->getAccountName()]));
  }

}
