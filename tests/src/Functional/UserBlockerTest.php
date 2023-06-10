<?php

namespace Drupal\Tests\user_blocker\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test description.
 *
 * @group user_blocker
 */
class UserBlockerTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['user_blocker'];

  /**
   * A test user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $testUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and log in an administrative user.
    $this->testUser = $this->drupalCreateUser([
      'block users',
    ]);
  }

  /**
   * Test callback.
   */
  public function testUserBlocker() {
    $this->drupalLogin($this->testUser);
    $normal_user = $this->drupalCreateUser();

    // Set up handy variables for our test page and user session.
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    // Load and check the form field.
    $this->drupalGet('admin/people/blocker');
    $assert_session->elementExists('css', 'input[name="uid"]');
    $assert_session->pageTextContains('Username');

    // Test case of a user trying to block themselves.
    // Note that Javascript is disabled for this type of functional test, so
    // the username, not user ID should be passed in as the form value (as one
    // would expect when using an entity_autocomplete widget).
    $page->fillField('edit-uid', $this->testUser->label());
    $page->pressButton('Submit');
    $assert_session->pageTextContains('You cannot block your own account.');

    // Test case of a user trying to block another user.
    $page->fillField('edit-uid', $normal_user->label());
    $page->pressButton('Submit');
    $assert_session->pageTextContains('User ' . $normal_user->label() . ' has been blocked.');
  }

}
