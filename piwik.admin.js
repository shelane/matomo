// $Id$
(function ($) {

/**
 * Provide the summary information for the tracking settings vertical tabs.
 */
Drupal.behaviors.trackingSettingsSummary = {
  attach: function (context) {
    // Make sure this behavior is processed only if drupalSetSummary is defined.
    if (typeof jQuery.fn.drupalSetSummary == 'undefined') {
      return;
    }

    $('fieldset#edit-page-vis-settings', context).drupalSetSummary(function (context) {
      if (!$('textarea[name="piwik_pages"]', context).val()) {
        return Drupal.t('Not restricted');
      }
      else {
        return Drupal.t('Restricted to certain pages');
      }
    });

    $('fieldset#edit-role-vis-settings', context).drupalSetSummary(function (context) {
      var vals = [];
      $('input[type="checkbox"]:checked', context).each(function () {
        vals.push($.trim($(this).next('label').text()));
      });
      if (!vals.length) {
        vals.push(Drupal.t('Not restricted'));
      }
      return vals.join(', ');
    });

    $('fieldset#edit-user-vis-settings', context).drupalSetSummary(function (context) {
      var $radio = $('input[name="piwik_custom"]:checked', context);
      if ($radio.val() == 0) {
        return Drupal.t('Not customizable');
      }
      else if ($radio.val() == 1) {
        return Drupal.t('On by default with opt out');
      }
      else {
        return Drupal.t('Off by default with opt in');
      }
    });

    $('fieldset#edit-linktracking', context).drupalSetSummary(function (context) {
      var vals = [];
      if ($('input#edit-piwik-track', context).is(':checked')) {
        vals.push(Drupal.t('Outgoing links'));
        vals.push(Drupal.t('Downloads'));
      }
      if (!vals.length) {
        return Drupal.t('Not tracked');
      }
      return Drupal.t('@items tracked', {'@items' : vals.join(', ')});
    });
    
    $('fieldset#edit-search', context).drupalSetSummary(function (context) {
      var vals = [];
      if ($('input#edit-piwik-site-search', context).is(':checked')) {
        vals.push('Site search');
      }
      if (!vals.length) {
        return Drupal.t('Not tracked');
      }
      return Drupal.t('@items tracked', {'@items' : vals.join(', ')});
    });
  }
};

})(jQuery);
