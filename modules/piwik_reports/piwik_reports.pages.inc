<?php
// $Id$

/**
 * Drupal Module: Piwik Reports
 * Sponsored by IO1, http://www.io1.biz
 * All code is released under the GNU General Public License.
 */

/**
 * Report menu links
 */
function piwik_reports_menu_block_page() {

  $item = menu_get_item();
  if ($content = system_admin_menu_block($item)) {
    $output = theme('admin_block_content', $content);
  }
  else {
    $output = '<p>'. t('You do not have any reports.') .'</p>';
  }

  return $output;
}

function piwik_reports_dateselect_form() {

  $period = array(
    0 => t('Today'),
    1 => t('Yesterday'),
    2 => t('Last week'),
    3 => t('Last month'),
    4 => t('Last year'),
  );

  $form['#attributes'] = array('class' => 'search-form');
  $form['basic'] = array(
    '#type' => 'fieldset',
    '#title' => t('Filter time period')
  );
  $form['basic']['inline'] = array(
    '#prefix' => '<div class="container-inline">',
    '#suffix' => '</div>'
  );
  $form['basic']['inline']['period'] = array(
    '#type' => 'select',
    '#title' => '',
    '#default_value' => variable_get('piwik_reports_period', ''), // TODO: Should be user specific.
    '#options' => $period,
  );
  $form['basic']['inline']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Filter'),
  );

  return $form;
}

function piwik_reports_overview() {
  $form = piwik_reports_dateselect_form();
  $period = variable_get('piwik_reports_period', '');
  $date = _piwik_reports_select_period($period);
  $now = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $auth = variable_get('piwik_auth', '');
  $url = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=VisitsSummary&action=getLastVisitsGraph&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'%2C'. $now .'&viewDataTable=generateDataChartEvolution'. ($auth ? '&token_auth='. $auth : ''));
  $output = theme('visitors_overview', $url, $period);
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => $output,
  );
  return $form;
}

function piwik_reports_overview_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_settings() {
  $form = piwik_reports_dateselect_form();
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $auth = variable_get('piwik_auth', '');
  $url1 = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=UserSettings&action=getBrowserType&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&viewDataTable=generateDataChartPie'. ($auth ? '&token_auth='. $auth : ''));
  $url2 = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=UserSettings&action=getConfiguration&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&viewDataTable=generateDataChartVerticalBar'. ($auth ? '&token_auth='. $auth : ''));
  $url3 = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=UserSettings&action=getOS&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&viewDataTable=generateDataChartVerticalBar'. ($auth ? '&token_auth='. $auth : ''));
  $url4 = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=UserSettings&action=getResolution&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&viewDataTable=generateDataChartVerticalBar'. ($auth ? '&token_auth='. $auth : ''));
  $output = theme('visitors_settings', $url1, $url2, $url3, $url4);
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => $output,
  );
  return $form;
}

function piwik_reports_settings_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_times() {
  $form = piwik_reports_dateselect_form();
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $auth = variable_get('piwik_auth', '');
  $url = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=VisitTime&action=getVisitInformationPerLocalTime&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&viewDataTable=generateDataChartVerticalBar'. ($auth ? '&token_auth='. $auth : ''));
  $output = theme('visitors_times', $url);
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => $output,
  );
  return $form;
}

function piwik_reports_times_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_locations() {
  $form = piwik_reports_dateselect_form();
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $auth = variable_get('piwik_auth', '');
  $url = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=UserCountry&action=getCountry&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&viewDataTable=generateDataChartPie'. ($auth ? '&token_auth='. $auth : ''));
  $output = theme('visitors_locations', $url);
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => $output,
  );
  return $form;
}

function piwik_reports_locations_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_pages() {
  drupal_add_js(drupal_get_path('module', 'piwik_reports') .'/piwik_reports.js');
  $form = piwik_reports_dateselect_form();
  $auth = variable_get('piwik_auth', '');
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period  = _piwik_reports_get_period_name($period);
  $url = variable_get('piwik_url_http', '') .'/index.php?module=API&method=Actions.getActions&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&format=JSON&filter_sort_column=nb_uniq_visitors&filter_sort_order=desc'. ($auth ? '&token_auth='. $auth : '') .'&jsoncallback=?';
  $form['page'] = array(
    '#type' => 'hidden',
    '#value' => 'actions',
  );
  $form['url'] = array(
    '#type' => 'hidden',
    '#value' => $url,
  );
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => '<div id="pagestable"></div>',
  );

  return $form;
}

function piwik_reports_pages_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_outlinks() {
  drupal_add_js(drupal_get_path('module', 'piwik_reports') .'/piwik_reports.js');
  $form = piwik_reports_dateselect_form();
  $auth = variable_get('piwik_auth', '');
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $url = variable_get('piwik_url_http', '') .'/index.php?module=API&method=Actions.getOutlinks&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&format=JSON&filter_sort_column=nb_uniq_visitors&filter_sort_order=desc'. ($auth ? '&token_auth='. $auth : '') .'&jsoncallback=?';
  $form['page'] = array(
    '#type' => 'hidden',
    '#value' => 'actions',
  );
  $form['url'] = array(
    '#type' => 'hidden',
    '#value' => $url,
  );
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => '<div id="pagestable"></div>',
  );

  return $form;
}

function piwik_reports_outlinks_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_downloads() {
  drupal_add_js(drupal_get_path('module', 'piwik_reports') .'/piwik_reports.js');
  $form = piwik_reports_dateselect_form();
  $auth = variable_get('piwik_auth', '');
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $url = variable_get('piwik_url_http', '') .'/index.php?module=API&method=Actions.getDownloads&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&format=JSON&filter_sort_column=nb_uniq_visitors&filter_sort_order=desc'. ($auth ? '&token_auth='. $auth : '') .'&jsoncallback=?';
  $form['page'] = array(
    '#type' => 'hidden',
    '#value' => 'actions',
  );
  $form['url'] = array(
    '#type' => 'hidden',
    '#value' => $url,
  );
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => '<div id="pagestable"></div>',
  );

  return $form;
}

function piwik_reports_downloads_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_evolution() {
  $form = piwik_reports_dateselect_form();
  $period = variable_get('piwik_reports_period', '');
  $date = _piwik_reports_select_period($period);
  $now = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $auth = variable_get('piwik_auth', '');
  $url = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=Referers&action=getLastDirectEntryGraph&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'%2C'. $now .'&viewDataTable=generateDataChartEvolution'. ($auth ? '&token_auth='. $auth : ''));
  $output = theme('referers_evolution', $url);
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => $output,
  );

  return $form;
}

function piwik_reports_evolution_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_search() {
  drupal_add_js(drupal_get_path('module', 'piwik_reports') .'/piwik_reports.js');
  $form = piwik_reports_dateselect_form();
  $auth = variable_get('piwik_auth', '');
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $url = variable_get('piwik_url_http', '') .'/libs/open-flash-chart/open-flash-chart.swf?data='. urlencode(variable_get('piwik_url_http', '') .'/?module=Referers&action=getSearchEngines&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date=yesterday&viewDataTable=generateDataChartPie'. ($auth ? '&token_auth='. $auth : ''));
  $output = theme('referers_search', $url);
  $url2 = variable_get('piwik_url_http', '') .'/index.php?module=API&method=Referers.getKeywords&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&format=JSON&filter_sort_column=nb_visits&filter_sort_order=desc'. ($auth ? '&token_auth='. $auth : '') .'&jsoncallback=?';

  $form['page'] = array(
    '#type' => 'hidden',
    '#value' => 'search',
  );
  $form['url'] = array(
    '#type' => 'hidden',
    '#value' => $url2,
  );
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => $output,
  );
  $form['tablecontent'] = array(
    '#type' => 'markup',
    '#value' => '<div id="pagestable"></div>',
  );

  return $form;
}

function piwik_reports_search_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function piwik_reports_websites() {
  drupal_add_js(drupal_get_path('module', 'piwik_reports') .'/piwik_reports.js');
  $form = piwik_reports_dateselect_form();
  $auth = variable_get('piwik_auth', '');
  $period = variable_get('piwik_reports_period', '');
  ($period == 1) ? $date = _piwik_reports_select_period($period) : $date = _piwik_reports_select_period(0);
  $period = _piwik_reports_get_period_name($period);
  $url = variable_get('piwik_url_http', '') .'/index.php?module=API&method=Referers.getWebsites&idSite='. variable_get('piwik_site_id', '') .'&period='. $period .'&date='. $date .'&format=JSON'. ($auth ? '&token_auth='. $auth : '') .'&jsoncallback=?';
  $form['url'] = array(
    '#type' => 'hidden',
    '#value' => $url,
  );
  $form['page'] = array(
    '#type' => 'hidden',
    '#value' => 'websites',
  );
  $form['content'] = array(
    '#type' => 'markup',
    '#value' => '<div id="pagestable"></div>',
  );

  return $form;
}

function piwik_reports_websites_submit($form, &$form_state) {
  variable_set('piwik_reports_period', $form_state['clicked_button']['#post']['period']);
}

function _piwik_reports_select_period($period) {
  switch ($period) {
    case 0:
      $date = date("Y-m-d");
      break;

    case 1:
      $d = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
      $date = date("Y-m-d", $d);
      break;

    case 2:
      $d = mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"));
      $date = date("Y-m-d", $d);
      break;

    case 3:
      $d = mktime(0, 0, 0, date("m") - 2, date("d"), date("Y"));
      $date = date("Y-m-d", $d);
      break;

    case 4:
      $d = mktime(0, 0, 0, date("m"), date("d"), date("Y") - 1);
      $date = date("Y-m-d", $d);
      break;
  }
  return $date;
}

function _piwik_reports_get_period_name($period) {
  switch ($period) {
    case 0:
      $p = "day";
      break;

    case 1:
      $p = "day";
      break;

    case 2:
      $p = "week";
      break;

    case 3:
      $p = "month";
      break;

    case 4:
      $p = "year";
      break;
  }
  return $p;
}

