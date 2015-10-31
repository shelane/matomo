<?php

/**
 * @file
 * Test file for Piwik module.
 */
class PiwikPhpFilterTest extends DrupalWebTestCase {

  public static function getInfo() {
    return array(
      'name' => 'Piwik php filter tests',
      'description' => 'Test php filter functionality of Piwik module.',
      'group' => 'Piwik',
    );
  }

  function setUp() {
    parent::setUp('piwik', 'php');

    // Administrator with all permissions.
    $permissions_admin_user = array(
      'access administration pages',
      'administer piwik',
      'use PHP for tracking visibility',
    );
    $this->admin_user = $this->drupalCreateUser($permissions_admin_user);

    // Administrator who cannot configure tracking visibility with PHP.
    $permissions_delegated_admin_user = array(
      'access administration pages',
      'administer piwik',
    );
    $this->delegated_admin_user = $this->drupalCreateUser($permissions_delegated_admin_user);
  }

  function testPiwikPhpFilter() {
    $ua_code = '1';
    $this->drupalLogin($this->admin_user);

    $edit = array();
    $edit['piwik_site_id'] = $ua_code;
    $edit['piwik_url_http'] = 'http://example.com/piwik/';
    $edit['piwik_url_https'] = 'https://example.com/piwik/';
    $edit['piwik_url_skiperror'] = TRUE; // Required for testing only.
    $edit['piwik_visibility_pages'] = 2;
    $edit['piwik_pages'] = '<?php return 0; ?>';
    $this->drupalPost('admin/config/system/piwik', $edit, t('Save configuration'));

    // Compare saved setting with posted setting.
    $piwik_pages = variable_get('piwik_pages', $this->randomName(8));
    $this->assertEqual('<?php return 0; ?>', $piwik_pages, '[testPiwikPhpFilter]: PHP code snippet is intact.');

    // Check tracking code visibility.
    variable_set('piwik_pages', '<?php return TRUE; ?>');
    $this->drupalGet('');
    $this->assertRaw('u+"piwik.php"', '[testPiwikPhpFilter]: Tracking is displayed on frontpage page.');
    $this->drupalGet('admin');
    $this->assertRaw('u+"piwik.php"', '[testPiwikPhpFilter]: Tracking is displayed on admin page.');

    variable_set('piwik_pages', '<?php return FALSE; ?>');
    $this->drupalGet('');
    $this->assertNoRaw('u+"piwik.php"', '[testPiwikPhpFilter]: Tracking is not displayed on frontpage page.');

    // Test administration form.
    variable_set('piwik_pages', '<?php return TRUE; ?>');
    $this->drupalGet('admin/config/system/piwik');
    $this->assertRaw(t('Pages on which this PHP code returns <code>TRUE</code> (experts only)'), '[testPiwikPhpFilter]: Permission to administer PHP for tracking visibility.');
    $this->assertRaw(check_plain('<?php return TRUE; ?>'), '[testPiwikPhpFilter]: PHP code snippted is displayed.');

    // Login the delegated user and check if fields are visible.
    $this->drupalLogin($this->delegated_admin_user);
    $this->drupalGet('admin/config/system/piwik');
    $this->assertNoRaw(t('Pages on which this PHP code returns <code>TRUE</code> (experts only)'), '[testPiwikPhpFilter]: No permission to administer PHP for tracking visibility.');
    $this->assertNoRaw(check_plain('<?php return TRUE; ?>'), '[testPiwikPhpFilter]: No permission to view PHP code snippted.');

    // Set a different value and verify that this is still the same after the post.
    variable_set('piwik_pages', '<?php return 0; ?>');

    $edit = array();
    $edit['piwik_site_id'] = $ua_code;
    $edit['piwik_url_http'] = 'http://example.com/piwik/';
    $edit['piwik_url_https'] = 'https://example.com/piwik/';
    $edit['piwik_url_skiperror'] = TRUE; // Required for testing only.
    $this->drupalPost('admin/config/system/piwik', $edit, t('Save configuration'));

    // Compare saved setting with posted setting.
    $piwik_visibility_pages = variable_get('piwik_visibility_pages', 0);
    $piwik_pages = variable_get('piwik_pages', $this->randomName(8));
    $this->assertEqual(2, $piwik_visibility_pages, '[testPiwikPhpFilter]: Pages on which this PHP code returns TRUE is selected.');
    $this->assertEqual('<?php return 0; ?>', $piwik_pages, '[testPiwikPhpFilter]: PHP code snippet is intact.');
  }

}
