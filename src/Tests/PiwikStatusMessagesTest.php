<?php

/**
 * @file
 * Test file for Piwik module.
 */
class PiwikStatusMessagesTest extends DrupalWebTestCase {

  public static function getInfo() {
    return array(
      'name' => 'Piwik status messages tests',
      'description' => 'Test status messages functionality of Piwik module.',
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

  function testPiwikStatusMessages() {
    $ua_code = '1';
    variable_set('piwik_site_id', $ua_code);

    // Enable logging of errors only.
    variable_set('piwik_trackmessages', array('error' => 'error'));

    $this->drupalPost('user/login', array(), t('Log in'));
    $this->assertRaw('_paq.push(["trackEvent", "Messages", "Error message", "Username field is required."]);', '[testPiwikStatusMessages]: trackEvent "Username field is required." is shown.');
    $this->assertRaw('_paq.push(["trackEvent", "Messages", "Error message", "Password field is required."]);', '[testPiwikStatusMessages]: trackEvent "Password field is required." is shown.');

    // @todo: investigate why drupal_set_message() fails.
    //drupal_set_message('Example status message.', 'status');
    //drupal_set_message('Example warning message.', 'warning');
    //drupal_set_message('Example error message.', 'error');
    //drupal_set_message('Example error <em>message</em> with html tags and <a href="http://example.com/">link</a>.', 'error');
    //$this->drupalGet('');
    //$this->assertNoRaw('_paq.push(["trackEvent", "Messages", "Status message", "Example status message."]);', '[testPiwikStatusMessages]: Example status message is not enabled for tracking.');
    //$this->assertNoRaw('_paq.push(["trackEvent", "Messages", "Warning message", "Example warning message."]);', '[testPiwikStatusMessages]: Example warning message is not enabled for tracking.');
    //$this->assertRaw('_paq.push(["trackEvent", "Messages", "Error message", "Example error message."]);', '[testPiwikStatusMessages]: Example error message is shown.');
    //$this->assertRaw('_paq.push(["trackEvent", "Messages", "Error message", "Example error message with html tags and link."]);', '[testPiwikStatusMessages]: HTML has been stripped successful from Example error message with html tags and link.');
  }
}
