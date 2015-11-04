<?php

/**
 * @file
 * Contains \Drupal\piwik\Tests\PiwikCustomVariablesTest.
 */

namespace Drupal\piwik\Tests;

use Drupal\Component\Serialization\Json;
use Drupal\simpletest\WebTestBase;

/**
 * Test custom variables functionality of Piwik module.
 *
 * @group Piwik
 */
class PiwikCustomVariablesTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['piwik', 'token'];

  /**
   * {@inheritdoc}
   */
  function setUp() {
    parent::setUp();

    $permissions = [
      'access administration pages',
      'administer piwik',
    ];

    // User to set up piwik.
    $this->admin_user = $this->drupalCreateUser($permissions);
  }

  function testPiwikCustomVariables() {
    $site_id = '3';
    $this->config('piwik.settings')->set('site_id', $site_id)->save();
    $this->config('piwik.settings')->set('url_http', 'http://example.com/piwik/')->save();
    $this->config('piwik.settings')->set('url_https', 'https://example.com/piwik/')->save();

    // Basic test if the feature works.
    $custom_vars = [
      1 => [
        'slot' => 1,
        'name' => 'Foo 1',
        'value' => 'Bar 1',
        'scope' => 3,
      ],
      2 => [
        'slot' => 2,
        'name' => 'Foo 2',
        'value' => 'Bar 2',
        'scope' => 2,
      ],
      3 => [
        'slot' => 3,
        'name' => 'Foo 3',
        'value' => 'Bar 3',
        'scope' => 3,
      ],
      4 => [
        'slot' => 4,
        'name' => 'Foo 4',
        'value' => 'Bar 4',
        'scope' => 2,
      ],
      5 => [
        'slot' => 5,
        'name' => 'Foo 5',
        'value' => 'Bar 5',
        'scope' => 1,
      ],
    ];
    $this->config('piwik.settings')->set('custom.variable', $custom_vars)->save();
    $this->drupalGet('');

    foreach ($custom_vars as $slot) {
      $this->assertRaw('_paq.push(["setCustomVariable", ' . Json::encode($slot['slot']) . ', ' . Json::encode($slot['name']) . ', ' . Json::encode($slot['value']) . ', ' . Json::encode($slot['scope']) . ']);', '[testPiwikCustomVariables]: setCustomVariable ' . $slot['slot'] . ' is shown.');
    }

    // Test whether tokens are replaced in custom variable names.
    $site_slogan = $this->randomMachineName(16);
    $this->config('system.site')->set('slogan', $site_slogan)->save();

    $custom_vars = [
      1 => [
        'slot' => 1,
        'name' => 'Name: [site:slogan]',
        'value' => 'Value: [site:slogan]',
        'scope' => 3,
      ],
      2 => [
        'slot' => 2,
        'name' => '',
        'value' => $this->randomMachineName(16),
        'scope' => 1,
      ],
      3 => [
        'slot' => 3,
        'name' => $this->randomMachineName(16),
        'value' => '',
        'scope' => 2,
      ],
      4 => [
        'slot' => 4,
        'name' => '',
        'value' => '',
        'scope' => 3,
      ],
      5 => [
        'slot' => 5,
        'name' => '',
        'value' => '',
        'scope' => 3,
      ],
    ];
    $this->config('piwik.settings')->set('custom.variable', $custom_vars)->save();
    $this->verbose('<pre>' . print_r($custom_vars, TRUE) . '</pre>');

    $this->drupalGet('');
    $this->assertRaw("_paq.push(['setCustomVariable', 1, " . Json::encode("Name: $site_slogan") . ', ' . Json::encode("Value: $site_slogan") . ', 3]', '[testPiwikCustomVariables]: Tokens have been replaced in custom variable.');
    $this->assertNoRaw("_paq.push(['setCustomVariable', 2,", '[testPiwikCustomVariables]: Value with empty name is not shown.');
    $this->assertNoRaw("_paq.push(['setCustomVariable', 3,", '[testPiwikCustomVariables]: Name with empty value is not shown.');
    $this->assertNoRaw("_paq.push(['setCustomVariable', 4,", '[testPiwikCustomVariables]: Empty name and value is not shown.');
    $this->assertNoRaw("_paq.push(['setCustomVariable', 5,", '[testPiwikCustomVariables]: Empty name and value is not shown.');
  }
}
