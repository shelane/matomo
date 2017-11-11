<?php

namespace Drupal\piwik\Tests;

use Drupal\Core\Session\AccountInterface;
use Drupal\simpletest\WebTestBase;

/**
 * Test custom url functionality of Piwik module.
 *
 * @group Piwik
 */
class PiwikCustomUrls extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['piwik'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $permissions = [
      'access administration pages',
      'administer google analytics',
      'administer modules',
      'administer site configuration',
    ];

    // User to set up piwik.
    $this->admin_user = $this->drupalCreateUser($permissions);
  }

  /**
   * Tests if user password page urls are overridden.
   */
  public function testPiwikUserPasswordPage() {
    $base_path = base_path();
    $site_id = '2';
    $this->config('piwik.settings')->set('site_id', $site_id)->save();
    $this->config('piwik.settings')->set('url_http', 'http://www.example.com/piwik/')->save();
    $this->config('piwik.settings')->set('url_https', 'https://www.example.com/piwik/')->save();

    $this->drupalGet('user/password', ['query' => ['name' => 'foo']]);
    $this->assertRaw('ga("set", "page", "' . $base_path . 'user/password"');

    $this->drupalGet('user/password', ['query' => ['name' => 'foo@example.com']]);
    $this->assertRaw('ga("set", "page", "' . $base_path . 'user/password"');

    $this->drupalGet('user/password');
    $this->assertNoRaw('ga("set", "page",', '[testPiwikCustomUrls]: Custom url not set.');
  }

}
