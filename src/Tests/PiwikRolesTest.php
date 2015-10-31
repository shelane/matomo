<?php

/**
 * @file
 * Test file for Piwik module.
 */
class PiwikRolesTest extends DrupalWebTestCase {

  public static function getInfo() {
    return array(
      'name' => t('Piwik role tests'),
      'description' => t('Test roles functionality of Piwik module.'),
      'group' => 'Piwik',
    );
  }

  function setUp() {
    parent::setUp('piwik');

    $permissions = array(
      'access administration pages',
      'administer piwik',
    );

    // User to set up piwik.
    $this->admin_user = $this->drupalCreateUser($permissions);
  }

  function testPiwikRolesTracking() {
    $ua_code = '1';
    variable_set('piwik_site_id', $ua_code);
    variable_get('piwik_url_http', 'http://example.com/piwik/');
    variable_get('piwik_url_https', 'https://example.com/piwik/');

    // Test if the default settings are working as expected.

    // Add to the selected roles only.
    variable_set('piwik_visibility_roles', 0);
    // Enable tracking for all users.
    variable_set('piwik_roles', array());

    // Check tracking code visibility.
    $this->drupalGet('');
    $this->assertRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is displayed for anonymous users on frontpage with default settings.');
    $this->drupalGet('admin');
    $this->assertRaw('"403/URL = "', '[testPiwikRoleVisibility]: 403 Forbidden tracking code is displayed for anonymous users in admin section with default settings.');

    $this->drupalLogin($this->admin_user);

    $this->drupalGet('');
    $this->assertRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is displayed for authenticated users on frontpage with default settings.');
    $this->drupalGet('admin');
    $this->assertNoRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is NOT displayed for authenticated users in admin section with default settings.');

    // Test if the non-default settings are working as expected.

    // Enable tracking only for authenticated users.
    variable_set('piwik_roles', array(DRUPAL_AUTHENTICATED_RID => DRUPAL_AUTHENTICATED_RID));

    $this->drupalGet('');
    $this->assertRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is displayed for authenticated users only on frontpage.');

    $this->drupalLogout();
    $this->drupalGet('');
    $this->assertNoRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is NOT displayed for anonymous users on frontpage.');

    // Add to every role except the selected ones.
    variable_set('piwik_visibility_roles', 1);
    // Enable tracking for all users.
    variable_set('piwik_roles', array());

    // Check tracking code visibility.
    $this->drupalGet('');
    $this->assertRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is added to every role and displayed for anonymous users.');
    $this->drupalGet('admin');
    $this->assertRaw('"403/URL = "', '[testPiwikRoleVisibility]: 403 Forbidden tracking code is shown for anonymous users if every role except the selected ones is selected.');

    $this->drupalLogin($this->admin_user);

    $this->drupalGet('');
    $this->assertRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is added to every role and displayed on frontpage for authenticated users.');
    $this->drupalGet('admin');
    $this->assertNoRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is added to every role and NOT displayed in admin section for authenticated users.');

    // Disable tracking for authenticated users.
    variable_set('piwik_roles', array(DRUPAL_AUTHENTICATED_RID => DRUPAL_AUTHENTICATED_RID));

    $this->drupalGet('');
    $this->assertNoRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is NOT displayed on frontpage for excluded authenticated users.');
    $this->drupalGet('admin');
    $this->assertNoRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is NOT displayed in admin section for excluded authenticated users.');

    $this->drupalLogout();
    $this->drupalGet('');
    $this->assertRaw('u+"piwik.php"', '[testPiwikRoleVisibility]: Tracking code is displayed on frontpage for included anonymous users.');
  }

}
