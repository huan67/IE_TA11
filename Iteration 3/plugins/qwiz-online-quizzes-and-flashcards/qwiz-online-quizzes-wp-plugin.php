<?php
/**
 * Plugin Name: Qwizcards - online quizzes and flashcards
 * Plugin URI: http://qwizcards.com
 * Description: Easy online quizzes and flashcards for WordPress
 * Version: 3.59
 * Author: Dan Kirshner
 * Author URI: http://qwizcards.com
 * License: GPL2
 */
$qwizcards_version = '3.59';
/*  Copyright 2021  Dan Kirshner  (email : dan_kirshner@yahoo.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
define ('PLUGIN_DIR', 'qwiz-online-quizzes-and-flashcards/');
define ('PLUGIN_FILE', 'qwiz-online-quizzes-wp-plugin.php');
define ('BETA_SUBDIR', 'beta/' . PLUGIN_DIR);
include "plugin_globals.php";
$qwiz_textentry_suggestions_ver = 3.1;
$no_minify_f = false;
$server_name = $_SERVER['SERVER_NAME'];
$server_name = str_replace ('www.', '', $server_name);
if (strpos ($server_name, '.qwizcards.com') !== false) {
   $server_name = str_replace ('.qwizcards', '', $server_name);
}
$query_string = $_SERVER['QUERY_STRING'];
$minified_f = strpos ($query_string, 'qwizmin=0') === false;
if ($server_loc == 'http://localhost/admin' ) {
   $minified_f = strpos ($query_string, 'qwizmin=1') !== false;
}
if ($debug[0]) {
   error_log ('[qwiz-online-quizzes-wp-plugin.php] $server_name: ' . $server_name);
   error_log ('[qwiz-online-quizzes-wp-plugin.php] $query_string: ' . $query_string);
}
if ($server_name == 'sciencemusicvideos.com' || $server_name == 'learn-biology.com') {
   $server_loc = 'https://' . $server_name . '/admin';
   $secure_server_loc = $server_loc;
}
$options = get_option ('qwiz_options');
/*
$plugin_dir = plugin_dir_path (__FILE__);
chmod ($plugin_dir . 'browse_dataset_questions.php', 0644);
chmod ($plugin_dir . 'payment_received.php', 0644);
chmod ($plugin_dir . 'registration_complete.php', 0644);
 */
/*
if ($debug[0]) {
   error_log ("[qwiz-online-quizzes-wp-plugin.php] fileperms (${plugin_dir}browse_dataset_questions.php: " . sprintf ('%o', fileperms ($plugin_dir . 'browse_dataset_questions.php')));
   error_log ("[qwiz-online-quizzes-wp-plugin.php] fileperms (${plugin_dir}payment_received.php: "         . sprintf ('%o', fileperms ($plugin_dir . 'payment_received.php')));
   error_log ("[qwiz-online-quizzes-wp-plugin.php] fileperms (${plugin_dir}registration_complete.php: "    . sprintf ('%o', fileperms ($plugin_dir . 'registration_complete.php')));
}
 */
add_editor_style (qwiz_plugin_url ('qwizzled_edit_area.css'));
add_editor_style (qwiz_plugin_url ('jquery-ui.min.lightness.css'));
function qwiz_language_init () {
   global $debug;
   $loaded = load_plugin_textdomain ('qwiz', false, dirname (plugin_basename (__FILE__)) . '/' . qwiz_beta_subdir () . 'languages/');
   if ($debug[0]) {
      error_log ('[qwiz_language_init] $loaded: ' . $loaded);
   }
}
function set_qwiz_params () {
   global $debug, $options, $qwiz_T, $qwizcards_version, $server_loc,
          $secure_server_loc, $server_name, $localhost_qjax_bypass;
   $plugin_url        = qwiz_plugin_url ( '/');
   $mobile_enabled    = $options['go_mobile'];
   $icon_qwiz         = $options['icon_qwiz'];
   $content           = $options['content'];
   $use_dict          = $options['use_dict'];
   $use_terms         = $options['use_terms'];
   $hint_timeout_sec  = $options['hint_timeout_sec'];
   $hangman_hints     = $options['hangman_hints'];
   $translate_strings = $options['translate_strings'];
   if (isset ($options['qwiz_syntax_check_manual_only'])) {
      $qwiz_syntax_check_manual_only = $options['qwiz_syntax_check_manual_only'];
   } else {
      $qwiz_syntax_check_manual_only = '';
   }
   if (isset ($options['regular_page_error_check'])) {
      $regular_page_error_check = $options['regular_page_error_check'];
   } else {
      $regular_page_error_check = '';
   }
   if (isset ($options['info_sent_time'])) {
      $info_sent_time = $options['info_sent_time'];
   } else {
      $info_sent_time = 0;
      $options['info_sent_time'] = 0;
   }
   $post_id = $info_sent_time != -1 ? get_the_ID () : 0;
   if ($debug[0]) {
      error_log ('[set_qwiz_params] $plugin_url: ' . $plugin_url);
      error_log ('[set_qwiz_params] $post_id: ' . $post_id);
      $post_obj = get_post ($post_id);
      if ($post_obj) {
         error_log ('[set_qwiz_params] $post_obj->ID: ' . $post_obj->ID);
      }
   }
   if (! $content) {
      $content = 'div.entry-content, div.post-entry, div#content';
   }
   if ($translate_strings) {
      $translate_strings = explode ("\n", $translate_strings);
      $n_translate_strings = count ($translate_strings);
      for ($i=0; $i<$n_translate_strings; $i++) {
         $strings = explode (';', $translate_strings[$i]);
         $old_string = $strings[0];
         if ($old_string == 'Check answer') {
            $old_string = 'Flip';
         }
         $new_string = trim ($strings[1]);
         $qwiz_T[$old_string] = $new_string;
      }
   }
   $beta = isset ($_SESSION['qwiz_beta']);
   $qjax_bypass = $server_name == 'sciencemusicvideos.com' || $server_name == 'learn-biology.com' || $server_name == 'qwizcards.com' || $server_name == 'sciencemusicvideos.qwizcards.com' || $localhost_qjax_bypass;
   $qwiz_params = array (
      'url'                           => $plugin_url,
      'mobile_enabled'                => $mobile_enabled,
      'icon_qwiz'                     => $icon_qwiz,
      'content'                       => $content,
      'beta'                          => $beta,
      'server_loc'                    => $server_loc,
      'secure_server_loc'             => $secure_server_loc,
      'use_dict'                      => $use_dict,
      'use_terms'                     => $use_terms,
      'hint_timeout_sec'              => $hint_timeout_sec,
      'hangman_hints'                 => $hangman_hints,
      'post_id'                       => $post_id,
      'ajaxurl'                       => admin_url ('admin-ajax.php'),
      'includes_url'                  => includes_url (),
      'qwizcards_version'             => $qwizcards_version,
      'wp_server_address'             => $_SERVER['SERVER_ADDR'],
      'wp_site_url'                   => get_site_url (),
      'qjax_bypass'                   => $qjax_bypass,
      'qwiz_syntax_check_manual_only' => $qwiz_syntax_check_manual_only,
      'regular_page_error_check'      => $regular_page_error_check,
      'wppf'                          => 1
   );
   return $qwiz_params;
}
function add_qwiz_js_and_style ($hook) {
   global $debug, $qwizcards_version, $options, $qwiz_params, $qwiz_T,
          $minified_f, $no_minify_f;
   if ($debug[0]) {
      error_log ('[add_qwiz_js_and_style] $hook: ' . $hook);
   }
   update_option ('image_default_link_type', '');
   wp_enqueue_script ('jquery-ui-core');
   wp_enqueue_script ('jquery-ui-autocomplete');
   wp_enqueue_script ('jquery-ui-button');
   wp_enqueue_script ('jquery-ui-dialog');
   wp_enqueue_script ('jquery-ui-draggable');
   wp_enqueue_script ('jquery-ui-droppable');
   wp_enqueue_script ('jquery-ui-menu');
   wp_enqueue_script ('jquery-ui-position');
   wp_enqueue_script ('jquery-ui-progressbar');
   wp_enqueue_script ('jquery-ui-resizable');
   wp_enqueue_script ('jquery-ui-tabs');
   wp_enqueue_script ('jquery-ui-tooltip');
   wp_enqueue_script ('jquery-ui-widget');
   if ($options['force_jquery_migrate_load'] == 1) {
      wp_enqueue_script ('migrate_handle', 'https://code.jquery.com/jquery-migrate-3.0.0.min.js',              array (), $qwizcards_version, true);
   }
   wp_enqueue_style ('wp-mediaelement');
   wp_enqueue_script ('wp-playlist');
   if ($minified_f && ! $no_minify_f) {
      $qwiz_qcards_common   = qwiz_plugin_url ('qwiz_qcards_common.min.js');
      $qwiz                 = qwiz_plugin_url ('qwiz.min.js');
      $qwizcards            = qwiz_plugin_url ('qwizcards.min.js');
   } else {
      $qwiz_qcards_common   = qwiz_plugin_url ('qwiz_qcards_common.js');
      $qwiz                 = qwiz_plugin_url ('qwiz.js');
      $qwizcards            = qwiz_plugin_url ('qwizcards.js');
   }
   $jquery_ui_touchpunch = qwiz_plugin_url ('jquery.ui.touch-punch.min.js');
   wp_enqueue_script ('qwiz_qcards_common_handle',   $qwiz_qcards_common,   array (), $qwizcards_version, true);
   wp_enqueue_script ('qwiz_handle',                 $qwiz,                 array (), $qwizcards_version, true);
   wp_enqueue_script ('qwizcards_handle',            $qwizcards,            array (), $qwizcards_version, true);
   wp_enqueue_script ('jquery_ui_touchpunch_handle', $jquery_ui_touchpunch, array (), $qwizcards_version, true);
   if ($options['force_jquery_load'] == 1) {
      $jquery = 'https://code.jquery.com/jquery-3.5.1.min.js';
      wp_enqueue_script ('jquery_handle', $jquery);
      $jquery_ui = 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js';
      wp_enqueue_script ('jquery_ui_handle', $jquery_ui);
   }
   $qwiz_T = array ();
   include qwiz_beta_subdir () . "languages/strings_to_translate.php";
   $qwiz_params = set_qwiz_params ();
   $local_qwiz_params = $qwiz_params;
   $local_qwiz_params['T'] = $qwiz_T;
   wp_localize_script ('qwiz_handle',      'qwiz_params', $local_qwiz_params);
   wp_localize_script ('qwizcards_handle', 'qwiz_params', $local_qwiz_params);
   $qwiz_css      = qwiz_plugin_url ('qwiz.css');
   $qwizcards_css = qwiz_plugin_url ('qwizcards.css');
   wp_register_style ('qwiz_css_handle',         $qwiz_css,             array (), $qwizcards_version);
   wp_enqueue_style ('qwiz_css_handle');
   wp_register_style ('qwizcards_css_handle',    $qwizcards_css,        array (), $qwizcards_version);
   wp_enqueue_style ('qwizcards_css_handle');
   $lightness_css      = qwiz_plugin_url ('jquery-ui.min.lightness.css');
   wp_register_style ('lightness_handle',      $lightness_css,     array (), $qwizcards_version);
   wp_enqueue_style ('lightness_handle');
}
function add_edit_page_scripts ($hook) {
   global $debug, $qwizcards_version, $options, $qwiz_params, $qwiz_T,
          $no_minify_f;
   if ($debug[0]) {
      error_log ('[add_edit_page_scripts] $hook: ' . $hook);
   }
   if ($hook == 'post.php' || $hook == 'post-new.php') {
      $no_minify_f = true;
      add_qwiz_js_and_style ($hook);
      $no_minify_f = false;
      $pre_qwizzled          = qwiz_plugin_url ('pre_qwizzled.js');
      $qwizzled              = qwiz_plugin_url ('qwizzled.js');
      $qwizard               = qwiz_plugin_url ('qwizard.js');
      $autocomplete_combobox = qwiz_plugin_url ('autocomplete-combobox.js');
      $simple_color          = qwiz_plugin_url ('jquery.simple-color.min.js');
      $select2               = qwiz_plugin_url ('select2.full.min.js');
      wp_enqueue_script ('pre_qwizzled_handle',          $pre_qwizzled,              array (), $qwizcards_version, true);
      wp_enqueue_script ('qwizzled_handle',              $qwizzled,              array (), $qwizcards_version, true);
      wp_enqueue_script ('qwizard_handle',               $qwizard,               array (), $qwizcards_version, true);
      wp_enqueue_script ('autocomplete_combobox_handle', $autocomplete_combobox, array (), $qwizcards_version, true);
      wp_enqueue_script ('simple_color_handle',          $simple_color,          array (), $qwizcards_version, true);
      wp_enqueue_script ('select2_handle',               $select2,               array (), $qwizcards_version, true);
      wp_enqueue_script ('jquery-ui-accordion');
      wp_enqueue_script ('jquery-effects-fade');
      $qwizzled_params = $qwiz_params;
      $qwizzled_params['T'] = $qwiz_T;
      $post_id  = get_the_ID ();
      $update_msg = qwiz_get_dataset_update_msg ($post_id);
      if ($debug[0]) {
         error_log ('[add_edit_page_scripts] update_msg: ' . $update_msg);
      }
      $qwizzled_params['update_msg'] = $update_msg;
      wp_localize_script ('pre_qwizzled_handle', 'qwizzled_params', $qwizzled_params);
      $qwizzled_css       = qwiz_plugin_url ('qwizzled.css');
      $qwizard_css        = qwiz_plugin_url ('qwizard.css');
      $select2_css        = qwiz_plugin_url ('select2.css');
      wp_register_style ('qwizzled_css_handle',   $qwizzled_css,      array (), $qwizcards_version);
      wp_register_style ('qwizard_css_handle',    $qwizard_css,       array (), $qwizcards_version);
      wp_register_style ('select2_css_handle',    $select2_css,       array (), $qwizcards_version);
      wp_enqueue_style ('qwizzled_css_handle');
      wp_enqueue_style ('qwizard_css_handle');
      wp_enqueue_style ('select2_css_handle');
   }
}
function qwizzled_button () {
   if (current_user_can ('edit_posts' ) || current_user_can ('edit_pages')) {
      add_filter ('mce_buttons_2', 'register_qwizzled_buttons');
      add_filter ('mce_external_plugins', 'add_qwizzled_buttons');
   }
}
function register_qwizzled_buttons ($buttons) {
   array_push ($buttons, "button_q");
   return $buttons;
}
function add_qwizzled_buttons ($plugin_array) {
   global $qwizcards_version;
   $plugin_array['qwizzled_button_script'] = qwiz_plugin_url ( 'qwiz_tinymce.js?ver='. $qwizcards_version) ;
   return $plugin_array;
}
function qwiz_plugin_url ($path) {
   $plugin_url = plugins_url ($path, __FILE__);
   $bsub = qwiz_beta_subdir ();
   if ($bsub) {
      $last_slash_pos = strrpos ($plugin_url, '/');
      $plugin_url = substr ($plugin_url, 0, $last_slash_pos + 1) . $bsub
                                   . substr ($plugin_url, $last_slash_pos + 1);
   }
   return $plugin_url;
}
function qwiz_beta_subdir () {
   global $options;
   $bsub = '';
   if (isset ($_SESSION['qwiz_beta']) || (isset ($options['deploy_beta']) && $options['deploy_beta'])) {
      $beta_plugin_file = plugin_dir_path (__FILE__) . BETA_SUBDIR . PLUGIN_FILE;
      if (file_exists ($beta_plugin_file)) {
         $bsub = BETA_SUBDIR;
      }
   }
   return $bsub;
}
function qwiz_get_dataset_questions () {
   global $debug, $secure_server_loc;
   if ($debug[0]) {
      error_log ('[qwiz_get_dataset_questions] $_POST: ' . print_r ($_POST, true));
      error_log ('[qwiz_get_dataset_questions] microtime (): ' . microtime ());
   }
   $dataset             = $_POST['dataset'];
   $dataset             = filter_var (urldecode ($dataset), FILTER_SANITIZE_STRING);
   $qname               = $_POST['qname'];
   if ($qname != 'qwiz_' && $qname != 'qcard_') {
      return;
   }
   $i_qwiz_qdeck        = $_POST['i_qwiz_qdeck'];
   if (! is_numeric ($i_qwiz_qdeck)) {
      return;
   }
   $qrecord_id          = $_POST['qrecord_id'];
   $qrecord_id          = filter_var (urldecode ($qrecord_id), FILTER_SANITIZE_STRING);
   $b64_units           = $_POST['units'];
   $b64_units           = filter_var (urldecode ($b64_units), FILTER_SANITIZE_STRING);
   $b64_topics          = $_POST['topics'];
   $b64_topics          = filter_var (urldecode ($b64_topics), FILTER_SANITIZE_STRING);
   $n_questions_in_set  = $_POST['n_questions_in_set'];
   if (! is_numeric ($i_qwiz_qdeck)) {
      return;
   }
   $questions_to_do = 'spaced_repetition';
   if (isset ($_POST['questions_to_do'])) {
      $questions_to_do  = $_POST['questions_to_do'];
      $questions_to_do  = filter_var (urldecode ($questions_to_do), FILTER_SANITIZE_STRING);
   }
   $qwiz_session_id     = $_POST['qwiz_session_id'];
   $qwiz_session_id  = filter_var (urldecode ($qwiz_session_id), FILTER_SANITIZE_STRING);
   $random_f = false;
   if (isset ($_POST['random_f'])) {
      $random_f         = $_POST['random_f'] ? 1 : 0;
   }
   $page_url = '';
   if (isset ($_POST['page_url'])) {
      $page_url         = $_POST['page_url'];
      $page_url         = filter_var (urldecode ($page_url), FILTER_SANITIZE_STRING);
   }
   $b64_use_dataset_question_ids = '';
   $maker_session_id = '';
   if (isset ($_POST['use_dataset_question_ids'])) {
      $b64_use_dataset_question_ids = $_POST['use_dataset_question_ids'];
      $b64_use_dataset_question_ids = filter_var (urldecode ($b64_use_dataset_question_ids), FILTER_SANITIZE_STRING);
      $maker_session_id             = $_POST['maker_session_id'];
   }
   $dataset_reset_questions_date = '';
   if (isset ($_POST['dataset_reset_questions_date'])) {
      $dataset_reset_questions_date = $_POST['dataset_reset_questions_date'];
   }
   $body = array ('dataset'                      => $dataset,
                  'qname'                        => $qname,
                  'i_qwiz_qdeck'                 => $i_qwiz_qdeck,
                  'qrecord_id'                   => $qrecord_id,
                  'units'                        => $b64_units,
                  'topics'                       => $b64_topics,
                  'n_questions_in_set'           => $n_questions_in_set,
                  'questions_to_do'              => $questions_to_do,
                  'qwiz_session_id'              => $qwiz_session_id,
                  'random_f'                     => $random_f,
                  'page_url'                     => $page_url,
                  'use_dataset_question_ids'     => $b64_use_dataset_question_ids,
                  'maker_session_id'             => $maker_session_id,
                  'dataset_reset_questions_date' => $dataset_reset_questions_date);
   $url = $secure_server_loc . '/get_dataset_questions_v3.php';
   $http_request = new WP_Http;
   if ($debug[5]) {
      error_log ('[qwiz_get_dataset_questions] $body: ' . print_r ($body, true));
   }
   $result = $http_request->request ($url, array ('method'  => 'POST',
                                                  'timeout' => 40,    // Seconds.
                                                  'body'    => $body));
   if ($debug[5]) {
      error_log ('[qwiz_get_dataset_questions] gettype (result): ' . gettype ($result));
      if (! is_wp_error ($result)) {
         error_log ('[qwiz_get_dataset_questions] result: ' . print_r ($result, true));
         error_log ('[qwiz_get_dataset_questions] microtime (): ' . microtime ());
      }
   }
   if (is_wp_error ($result)) {
      wp_die ();  // Required to terminate and return proper response.
      return;
   }
   $data_array = json_decode ($result['body']);
   if ($debug[5]) {
      error_log ('[qwiz_get_dataset_questions] data_array: ' . print_r ($data_array, true));
   }
   $ok_f = $data_array->ok_f;
   if ($ok_f == 1) {
      $questions_html = $data_array->questions_html;
      $questions_html = qwiz_filter_embeds ($questions_html);
      if ($debug[5]) {
         error_log ('[qwiz_get_dataset_questions] questions_html: ' . print_r ($questions_html, true));
      }
      $filtered_questions_html = apply_filters ('the_content', $questions_html);
      if ($debug[5]) {
         error_log ('[qwiz_get_dataset_questions] (after the_content) microtime (): ' . microtime ());
      }
      $qf_f = false;
      if (isset ($_POST['qf_f'])) {
         $qf_f = $_POST['qf_f'] == 1;
      }
      if ($qf_f && $qname == 'qwiz_') {
         $filtered_questions_html = qwiz_feedback ($filtered_questions_html);
      }
      if ($debug[5]) {
         error_log ('[qwiz_get_dataset_questions] (after qwiz_feedback) microtime (): ' . microtime ());
         error_log ('[qwiz_get_dataset_questions] filtered_questions_html: ' . print_r ($filtered_questions_html, true));
      }
      $data_array->questions_html = $filtered_questions_html;
   }
   wp_send_json ($data_array);
   wp_die ();  // Required to terminate and return proper response.
}
function qwiz_filter_embeds ($html) {
   global $debug;
   $n_links = preg_match_all ('/^\s*(http:\/\/|https:\/\/)\S+\s*$/m', $html, $matches, PREG_SET_ORDER);
   if ($debug[5]) {
      error_log ('[qwiz_filter_embeds] $matches: ' . print_r ($matches, true));
   }
   for ($i=0; $i<$n_links; $i++) {
      $url = trim ($matches[$i][0]);
      $embed_html = wp_oembed_get ($url);
      $html = preg_replace ('/^\s*(http:\/\/|https:\/\/|\/\/)\S+\s*$/m', $embed_html, $html, 1);
   }
   $n_embeds = preg_match_all ('/\[embed[^\]]*\](.*?)\[\/embed\]/', $html, $matches, PREG_SET_ORDER);
   if ($debug[5]) {
      error_log ('[qwiz_filter_embeds] $matches: ' . print_r ($matches, true));
   }
   for ($i=0; $i<$n_embeds; $i++) {
      $embed = $matches[$i][0];
      $attr = array ();
      $width  = qwiz_get_attr ($embed, 'width');
      if ($width) {
         $attr['width'] = $width;
      }
      $height = qwiz_get_attr ($embed, 'height');
      if ($height) {
         $attr['height'] = $height;
      }
      $url = trim ($matches[$i][1]);
      $embed_html = wp_oembed_get ($url, $attr);
      $html = preg_replace ('/\[embed.*?\[\/embed\]/', $embed_html, $html, 1);
   }
   return $html;
}
function qwiz_process_embeds () {
   global $debug;
   if ($debug[0]) {
      error_log ('[qwiz_process_embeds] $_POST: ' . print_r ($_POST, true));
   }
   $urls = $_POST['urls'];
   $urls = filter_var (urldecode ($urls), FILTER_SANITIZE_STRING);
   $urls = json_decode ($urls, true);
   $args = array ();
   if (isset ($_POST['args'])) {
      $args = filter_var (urldecode ($args), FILTER_SANITIZE_STRING);
      $args = json_decode ($args, true);
   }
   if ($debug[0]) {
      error_log ('                      $urls: ' . print_r ($urls, true));
      error_log ('                      $args: ' . print_r ($args, true));
   }
   $embed_htmls = array ();
   $n_urls = count ($urls);
   for ($i=0; $i<$n_urls; $i++) {
      $embed_htmls[] = wp_oembed_get ($urls[$i], $args[$i]);
   }
   if ($debug[0]) {
      error_log ('[qwiz_process_embeds] $embed_htmls: ' . print_r ($embed_htmls, true));
   }
   wp_send_json ($embed_htmls);
   wp_die ();  // Required to terminate and return proper response.
}
function qwiz_erase_update_msg () {
   global $debug;
   if ($debug[0]) {
      error_log ('[qwiz_erase_update_msg] $_POST: ' . print_r ($_POST, true));
   }
   $post_id = $_POST['post_id'];
   if (is_numeric ($post_id)) {
      qwiz_save_dataset_update_msg ($post_id, '');
   }
}
function qwiz_qjax0 () {
   global $debug;
   $qwizdata = json_decode (urldecode (base64_decode ($_POST['qwizdata'])), true);
   if (! isset ($qwizdata['dest'])) {
      error_log ('[qwiz_qjax0] (no dest) $qwizdata: ' . print_r ($qwizdata, true));
      wp_die ();
   }
   for ($i_try=0; $i_try<4; $i_try++) {
	 if ($i_try > 0) {
            error_log ("[qwiz_qjax0] i_try: $i_try");
            error_log ('[qwiz_qjax0] $qwizdata: ' . print_r ($qwizdata, true));
         }
      $ok_f = qwiz_qjax ($qwizdata);
      if ($ok_f) {
         break;
      }
      usleep (1833000);
      if ($debug[0]) {
         error_log ('[qwiz_qjax0] ok_f: $ok_f, i_try: ' . $i_try);
      }
   }
}
function qwiz_qjax ($qwizdata) {
   global $debug, $secure_server_loc;
   if ($debug[0]) {
      error_log ('[qwiz_qjax] $qwizdata: ' . print_r ($qwizdata, true));
   }
   $dest = esc_url ($qwizdata['dest']);
   $dest = substr ($dest, 7);
   $url = "$secure_server_loc/$dest.php";
   $http_request = new WP_Http;
   $result = $http_request->request ($url, array ('method'  => 'POST',
                                                  'timeout' => 40,    // Seconds.
                                                  'body'    => $qwizdata));
   if ($debug[0]) {
      error_log ('[qwiz_qjax] gettype (result): ' . gettype ($result));
      if (! is_wp_error ($result)) {
         error_log ('[qwiz_qjax] result: ' . print_r ($result, true));
      }
   }
   if (is_wp_error ($result)) {
      error_log ('[qwiz_qjax] $result->get_error_messages (): ' . print_r ($result->get_error_messages (), true));
      $script_name = $_SERVER['SCRIPT_FILENAME'];
      error_log ('[qwiz_qjax] $script_name: ' . $script_name);
      error_log ('[qwiz_qjax] $qwizdata: ' . print_r ($qwizdata, true));
      $ok_f = false;
   } else {
      wp_send_json ($result['body']);
      wp_die ();  // Required to terminate and return proper response.
      $ok_f = true;
   }
   return $ok_f;
}
add_action ('plugins_loaded', 'qwiz_language_init');
add_action ('wp_enqueue_scripts', 'add_qwiz_js_and_style');
add_action ('admin_enqueue_scripts', 'add_edit_page_scripts');
add_action ('admin_init', 'qwizzled_button');
add_action ('wp_ajax_get_dataset_questions', 'qwiz_get_dataset_questions');
add_action ('wp_ajax_nopriv_get_dataset_questions', 'qwiz_get_dataset_questions');
add_action ('wp_ajax_process_embeds', 'qwiz_process_embeds');
add_action ('wp_ajax_nopriv_process_embeds', 'qwiz_process_embeds');
add_action ('wp_ajax_erase_update_msg', 'qwiz_erase_update_msg');
add_action ('wp_ajax_nopriv_erase_update_msg', 'qwiz_erase_update_msg');
add_action ('wp_ajax_qjax', 'qwiz_qjax0');
add_action ('wp_ajax_nopriv_qjax', 'qwiz_qjax0');
function qwizzled_add_locale ($locales) {
    $locales ['qwizzled_langs'] = plugin_dir_path (__FILE__) . qwiz_beta_subdir () . 'languages/qwizzled_langs.php';
    return $locales;
}
add_filter ('mce_external_languages', 'qwizzled_add_locale');
/*
function qwiz_change_mce_options ($mceInit) {
   $mceInit['paste_preprocess']
      = 'function (pl, o) {
            console.log ("[qwiz_change_mce_options] o.content: ", o.content);
            o.content = "[[" + o.content + "]]";
         }';
   return $mceInit;
}
add_filter ('tiny_mce_before_init', 'qwiz_change_mce_options');
*/
/**
 * Customize TinyMCE's configuration
 *
 * @param   array
 * @return  array
 */
function configure_tinymce ($in) {
   $in['paste_preprocess']
      = "function (plugin, args) {
            var stripped = jQuery ('<div>' + args.content + '</div>');
            /*
            var whitelist = 'p,span,b,strong,i,em,h3,h4,h5,h6,ul,li,ol';
            var els = stripped.find ('*').not (whitelist);
            for (var i=els.length - 1; i>=0; i--) {
               var e = els[i];
               jQuery (e).replaceWith (e.innerHTML);
            }
            */
            stripped.find ('*').removeAttr('id').removeAttr ('class');
            args.content = stripped.html();
         }";
   $in['paste_retain_style_properties'] = 'all';
   return $in;
}
add_filter ('tiny_mce_before_init', 'configure_tinymce');
function qwiz_admin_bar_item ($wp_admin_bar) {
   $args = array (
      'id'     => 'qwiz_menu',
      'title'  => 'Qwizcards'
   );
   $wp_admin_bar->add_node ($args);
   $args = array (
      'id'     => 'qwiz_menu_keep_next_active',
      'parent' => 'qwiz_menu',
      'title'  => 'Keep "next" button active',
      'href'   => '#',
      'meta'   => array ('onclick' => 'qwiz_.keep_next_button_active (); qcard_.keep_next_button_active (); return false;',
                         'title'   => 'Allows you to skip questions/cards')
   );
   $wp_admin_bar->add_node ($args);
}
add_action ('admin_bar_menu', 'qwiz_admin_bar_item', 999);
function qwiz_process_shortcodes_initially ($content) {
   global $debug, $secure_server_loc, $server_name;
   $content = qwiz_process_simply_random ($content);
   if (   strpos ($content, '[qwiz')      !== false
       || strpos ($content, '[qdeck')     !== false
       || strpos ($content, '[qscores')   !== false
       || strpos ($content, '[qfeedback') !== false) {
      list ($content, $qwizdemos)  = qwiz_cut_demos ($content, 'qwiz');
      list ($content, $qdeckdemos) = qwiz_cut_demos ($content, 'qdeck');
      $author_id = get_the_author_meta ('ID');
      $user_id   = get_current_user_id ();
      if (! ($user_id == $author_id || current_user_can ('editor') || current_user_can ('administrator'))) {
         $match_pat = "/\[qfeedback\][\s\S]*?\[\/qfeedback\]/";
         $content = preg_replace ($match_pat, '', $content);
      } else {
         $content = preg_replace ("/\[qfeedback\]|\[\/qfeedback\]/", '', $content);
      }
      $content = preg_replace ('/<span id="qbookmark[^<]+<\/span>/m', '', $content);
      if (qwiz_check_shortcode_pairs_ok ($content, 'qwiz')
                         && qwiz_check_shortcode_pairs_ok ($content, 'qdeck')) {
         $content =   '<div class="qwiz_wrapper"  style="display: none;"></div>'
                    . '<div class="qdeck_wrapper" style="display: none;"></div>'
                    . $content;
         $content = qwiz_wrap_shortcode_pairs ($content, 'qwiz');
         $content = qwiz_wrap_shortcode_pairs ($content, 'qdeck');
      }
      $content = str_replace ('[qscores]', '<span class="qscores"><a href="' . $secure_server_loc . '/student_login.php" target="_blank">Login/View scores</a></span>', $content);
      $content = qwiz_unwrap_and_paste_demos ($content, $qwizdemos, 'qwiz');
      $content = qwiz_unwrap_and_paste_demos ($content, $qdeckdemos, 'qdeck');
      if ($debug[1]) {
         error_log ("[qwiz_process_shortcodes_initially] content:\n" . $content);
      }
   }
   return $content;
}
function qwiz_cut_demos ($content, $qwiz_qdeck) {
   $match_pat = "/\[${qwiz_qdeck}demo\]([\s\S]*?)\[\/${qwiz_qdeck}demo\]/";
   preg_match_all ($match_pat, $content, $matches, PREG_SET_ORDER);
   $replace_pat = "${qwiz_qdeck}_PLACEHOLDER";
   $content = preg_replace ($match_pat, $replace_pat, $content);
   return array ($content, $matches);
}
function qwiz_unwrap_and_paste_demos ($content, $demos, $qwiz_qdeck) {
   $n_demos = count ($demos);
   $match_pat = "/${qwiz_qdeck}_PLACEHOLDER/";
   for ($i=0; $i<$n_demos; $i++) {
      $demo = $demos[$i][1];
      $content = preg_replace ($match_pat, $demo, $content, 1);
   }
   return $content;
}
function qwiz_wrap_shortcode_pairs ($content, $qwiz_qdeck) {
   $qmatch_pat = "/(<(p|h|span)[^>]*>\s*)*\[${qwiz_qdeck}[\s\S]*?\[\/$qwiz_qdeck\](<\/(p|h|span)[^>]*>\s*)*/";
   $n_qwiz_qdecks = preg_match_all ($qmatch_pat, $content, $matches, PREG_SET_ORDER);
   $pieces = preg_split ($qmatch_pat, $content);
   $new_content = array ($pieces[0]);
   for ($i=0; $i<$n_qwiz_qdecks; $i++) {
      $qcontent = $matches[$i][0];
      $modified_qwiz_qdeck = qwiz_check_fix_wrap_matched_divs ($qcontent, $qwiz_qdeck);
      $modified_qwiz_qdeck = qwiz_encode_image_tags ($modified_qwiz_qdeck);
      if ($qwiz_qdeck == 'qwiz') {
         $modified_qwiz_qdeck = qwiz_feedback ($modified_qwiz_qdeck);
      }
      array_push ($new_content, $modified_qwiz_qdeck);
      array_push ($new_content, $pieces[$i+1]);
   }
   return implode ('', $new_content);
}
function qwiz_check_fix_wrap_matched_divs ($qcontent, $qwiz_qdeck) {
   global $debug;
   $div_match_pat = "/<div[^>]*>|<\/div>/";
   $n_tags = preg_match_all ($div_match_pat, $qcontent, $div_matches, PREG_SET_ORDER);
   $matched_pair_b = array ();
   for ($i=0; $i<$n_tags; $i++) {
      array_push ($matched_pair_b, false);
      $tag = $div_matches[$i][0];
      if (substr ($tag, 0, 2) == '</') {
         for ($jj=$i-1; $jj>=0; $jj--) {
            if (substr ($div_matches[$jj][0], 0, 2) == '<d' && ! $matched_pair_b[$jj]) {
               $matched_pair_b[$jj] = true;
               $matched_pair_b[$i] = true;
               break;
            }
         }
      }
   }
   $pieces = preg_split ($div_match_pat, $qcontent);
   $new_qcontent = array ("<div class=\"${qwiz_qdeck}_wrapper qwiz_shortcodes_hidden\">\n");
   $fallback =   '<div class="' . $qwiz_qdeck . '_wrapper_fallback ' . $qwiz_qdeck . '_wrapper_fallback_visible">'
               .    '<button onclick="qwiz_.qwiz_init (); qcard_.qdeck_init ();">'
               .        'Click here to start ' . ($qwiz_qdeck == 'qwiz' ? 'quiz' : 'flashcard deck')
               .    '</button>'
               . '</div>';
   array_push ($new_qcontent, $fallback);
   array_push ($new_qcontent, $pieces[0]);
   for ($i=0; $i<$n_tags; $i++) {
      if ($matched_pair_b[$i]) {
         if ($debug[2]) {
            error_log ('[qwiz_check_fix_wrap_matched_divs] matched pair tag html: ' . qwiz_summary ($div_matches[$i][0], 100));
         }
         $tag = $div_matches[$i][0];
         array_push ($new_qcontent, $tag);
      }
      array_push ($new_qcontent, $pieces[$i+1]);
   }
   array_push ($new_qcontent, "\n</div>  <!-- ${qwiz_qdeck}_wrapper -->\n");
   for ($i=0; $i<$n_tags; $i++) {
      if (! $matched_pair_b[$i]) {
         if ($debug[2]) {
            error_log ('[qwiz_check_fix_wrap_matched_divs] unmatched pair tag html: ' . qwiz_summary ($div_matches[$i][0], 100));
         }
         $tag = $div_matches[$i][0];
         array_push ($new_qcontent, $tag);
      }
   }
   return implode ('', $new_qcontent);
}
function qwiz_encode_image_tags ($qwiz_qdeck_content) {
   $n_images = preg_match_all ('/(\[caption[^\]]*\])*<img/', $qwiz_qdeck_content, $matches, PREG_OFFSET_CAPTURE);
   for ($i=$n_images-1; $i>=0; $i--) {
      $match = $matches[0][$i][0];
      if (substr ($match, 0, 1) == '[') {
         continue;
      } else {
         $i_offset = $matches[0][$i][1];
         $remaining = substr ($qwiz_qdeck_content, $i_offset);
         $new_remaining = preg_replace ('/<img/', '<input name="qwiz_img"', $remaining, 1);
         $qwiz_qdeck_content = substr ($qwiz_qdeck_content, 0, $i_offset) . $new_remaining;
      }
   }
   return $qwiz_qdeck_content;
}
function qwiz_feedback ($qwiz_qdeck_content) {
   global $debug;
   $modified_content = preg_replace ('/\[!+\].*?\[\/!+\]/s', '', $qwiz_qdeck_content);
   if ($debug[8]) {
      error_log ('[qwiz_feedback] $modified_content: ' . $modified_content);
   }
   $modified_content = preg_replace_callback ('/((<[^\/][^>]*>\s*)*?)(\[c\]|\[f\]|\[c\*\])([\s\S]*?)(?=((<[^\/][^>]*>\s*)*?(\[c|\[f\]|\[x|\[q|\[\/qwiz|<div class="qwizzled_question|\[code><\/code>q|$)))/', 'qwiz_feedback2', $modified_content);
   return $modified_content;
}
function qwiz_feedback2 ($matches) {
   global $debug;
   $max_splits = 6;
   if ($debug[8]) {
      error_log ('[qwiz_feedback2] $matches: ' . print_r ($matches, true));
   }
   $opening_tags = $matches[1];
   $shortcode    = $matches[3];
   $feedback     = $matches[4];
   $return_closing_tags = '';
   $empty_p_f = preg_match ('/(<p>&nbsp;<\/p>\s*)+$/s', $feedback, $empty_p_matches, PREG_OFFSET_CAPTURE);
   if ($empty_p_f) {
      if ($debug[8]) {
         error_log ('[qwiz_feedback2] $empty_p_matches: ' . print_r ($empty_p_matches, true));
      }
      $empty_ps_pos = $empty_p_matches[0][1];
      $return_closing_tags = substr ($feedback, $empty_ps_pos);
      $feedback            = substr ($feedback, 0, $empty_ps_pos);
   }
   $closing_tags_f = preg_match ('/(<\/[^>]+>\s*)+$/s', $feedback, $closing_tags_matches, PREG_OFFSET_CAPTURE);
   if ($closing_tags_f !== false) {
      if (count ($closing_tags_matches)) {
         $closing_tags     = $closing_tags_matches[0][0];
         $closing_tags_pos = $closing_tags_matches[0][1];
         $n_closing_tagnames = preg_match_all ('/<\/([a-z]+[0-9]*)/', $closing_tags, $closing_tagname_matches, PREG_OFFSET_CAPTURE);
         $last_closing_tagname = $n_closing_tagnames - 1;
         if ($debug[8]) {
            error_log ('[qwiz-feedback2] closing_tags_matches: ' . print_r ($closing_tags_matches, true));
            error_log ('[qwiz-feedback2] closing_tagname_matches: ' . print_r ($closing_tagname_matches, true));
         }
         $n_opening_tagnames = preg_match_all ('/<([a-z]+[0-9]*)/', $opening_tags, $opening_tagname_matches);
         $n = min ($n_opening_tagnames, $n_closing_tagnames);
         $closing_tagnames_pos = -1;
         for ($i=0; $i<$n; $i++) {
            $opening_tagname = $opening_tagname_matches[1][$i];
            $i_closing_tagname = $last_closing_tagname - $i;
            $closing_tagname  = $closing_tagname_matches[1][$i_closing_tagname][0];
            if ($debug[8]) {
               error_log ("[qwiz-feedback2] opening_tagname: $opening_tagname, closing_tagname: $closing_tagname");
            }
            if ($closing_tagname != $opening_tagname) {
               break;
            }
            $closing_tagnames_pos = $closing_tagname_matches[0][$i_closing_tagname][1];
         }
         if ($closing_tagnames_pos != -1) {
            if ($debug[8]) {
               error_log ("[qwiz-feedback2] closing_tags_pos, $closing_tags_pos, closing_tagnames_pos: $closing_tagnames_pos");
            }
            $pos = $closing_tags_pos + $closing_tagnames_pos;
            $return_closing_tags = substr ($feedback, $pos) . $return_closing_tags;
            $feedback            = substr ($feedback, 0, $pos);
         }
      }
   }
   $matches = preg_split ('/(<[^>]+>)/', $feedback, $max_splits, PREG_SPLIT_DELIM_CAPTURE);
   $n_matches = count ($matches);
   if ($n_matches) {
      $last_piece = $n_matches > $max_splits*2 - 2;
      if ($last_piece) {
         $n_matches -= 1;
      }
      $fparts = array ();
      $first_f = true;
      for ($i=1; $i<=$n_matches; $i+=2) {
         $fpart = $matches[$i - 1];
         if ($fpart != '') {
            $fpart = base64_encode ($fpart);
            if ($first_f) {
               $first_f = false;
               if ($shortcode == '[c*]') {
                  $shortcode = '[c]';
                  $len = strlen ($fpart);
                  $len2 = (int) $len / 2;
                  $fpart = substr ($fpart, 0, $len2) . ' ' . substr ($fpart, $len2);
               }
            }
            $fparts[] = $fpart;
         }
         if ($i < $n_matches) {
            $fparts[] = $matches[$i];
         }
      }
      $feedback = implode ('', $fparts) . '[Qq]';
      if ($last_piece) {
         $feedback .= $matches[$n_matches];
      }
   } else {
      $feedback = base64_encode ($feedback) . '[Qq]';
   }
   $result = $opening_tags . $shortcode . $feedback . $return_closing_tags;
   if ($debug[8]) {
      error_log ('[qwiz_feedback2] $result: ' . $result);
   }
   return $result;
}
function qwiz_check_shortcode_pairs_ok ($content, $qwiz_qdeck) {
   global $debug;
   $error_b = false;
   $n_qwiz_qdecks = preg_match_all ("/\[$qwiz_qdeck( |\])|\[\/$qwiz_qdeck\]/", $content, $matches, PREG_SET_ORDER);
   if ($debug[2]) {
      error_log ("[qwiz_check_shortcode_pairs_ok] n_${qwiz_qdeck}_s: $n_qwiz_qdecks");
   }
   if ($n_qwiz_qdecks) {
      if ($n_qwiz_qdecks % 2 != 0) {
         $error_b = true;
      } else {
         for ($i=0; $i<$n_qwiz_qdecks; $i++) {
            $shortcode = $matches[$i][0];
            if ($i % 2 == 0) {
               if (substr ($shortcode, 0, -1) != "[$qwiz_qdeck") {
                  $error_b = true;
                  break;
               }
            } else {
               if ($shortcode != "[/$qwiz_qdeck]") {
                  $error_b = true;
                  break;
               }
            }
         }
      }
   }
   $ok_b = ! $error_b;
   if ($debug[2]) {
      error_log ("[qwiz_check_shortcode_pairs_ok] ok_b: $ok_b");
   }
   return $ok_b;
}
function qwiz_process_simply_random ($content) {
   global $debug, $drawns, $rand_max;
   if (strpos ($content, '[simply-random') !== false) {
      if (qwiz_check_shortcode_pairs_ok ($content, 'simply-random')) {
         $rand_max = mt_getrandmax ();
         while (true) {
            $i_beg_pos = strpos ($content,'[simply-random]');
            if ($i_beg_pos === false) {
               $i_beg_pos = strpos ($content,'[simply-random ');
               if ($i_beg_pos === false) {
                  break;
               }
            }
            $i_end_pos = strpos ($content,'[/simply-random]', $i_beg_pos) + 16;
            if ($debug[7]) {
               error_log ('[qwiz_process_simply_random] $i_beg_pos: ' . $i_beg_pos);
               error_log ('[qwiz_process_simply_random] $i_end_pos: ' . $i_end_pos);
            }
            if ($i_end_pos < $i_beg_pos) {
               error_log ('[qwiz_process_simply_random] substr ($content, $i_beg_pos, 100): ' . substr ($content, $i_beg_pos, 100));
               break;
            }
            $i_len = $i_end_pos - $i_beg_pos;
            $subset = substr ($content, $i_beg_pos, $i_len);
            if ($debug[7]) {
               error_log ('[qwiz_process_simply_random] $subset: ' . $subset);
            }
            $matches = preg_split ('/\[simply-random-item([^\]]*)\]\n{0,1}/', $subset, 0, PREG_SPLIT_DELIM_CAPTURE);
            $n_pieces = count ($matches);
            if ($n_pieces == 1) {
               $content = substr ($content, 0, $i_beg_pos) . substr ($content, $i_beg_pos + $i_len);
               continue;
            }
            $shortcode = $matches[0];
            $draw_n = qwiz_get_attr2 ($shortcode, 'draw_n');
            if (! is_numeric ($draw_n)) {
               $draw_n = 1;
            }
            $with_replacement
                    = qwiz_get_attr2 ($shortcode, 'with_replacement') == 'true';
            $weights = array ();
            $sum_weights = 0.0;
            for ($i=1; $i<$n_pieces; $i+=2) {
               $attributes = $matches[$i];
               if ($attributes) {
                  $weight = qwiz_get_attr2 ($attributes, 'weight');
                  if (! is_numeric ($weight)) {
                     $weight = 0.0;
                  }
               } else {
                  $weight = 1.0;
               }
               $weights[] = $weight;
               $sum_weights += $weight;
            }
            $n_items = count ($weights);
            $items  = array ();
            $drawns = array_fill (0, $n_items, 0);
            for ($i_draw=0; $i_draw<$draw_n; $i_draw++) {
               $i_item = qwiz_simply_random_draw ($weights, $sum_weights,
                                                  $with_replacement);
               $ii_item = $i_item*2 + 2;
               $item = $matches[$ii_item];
               if ($i_item == $n_items - 1) {
                  $item = substr ($item, 0, -16);
               }
               if (substr ($item, -1) == "\n") {
                  $item = substr ($item, 0, -1);
               }
               $items[] = $item;
            }
            $content = substr ($content, 0, $i_beg_pos) . implode (' ', $items) . substr ($content, $i_beg_pos + $i_len);
         }
      }
   }
   return $content;
}
function qwiz_simply_random_draw ($weights, $sum_weights, $with_replacement) {
   global $debug, $rand_max, $drawns;
   $n_items = count ($weights);
   if (! $with_replacement) {
      $n_drawn = array_sum ($drawns);
      if ($n_drawn == $n_items) {
         $drawns = array_fill (0, $n_items, 0);
      }
   }
   $i_item = mt_rand (0, $n_items-1);
   while (true) {
      $skip_f = false;
      if (! $with_replacement) {
         if ($drawns[$i_item]) {
            $skip_f = true;
         }
      }
      if (! $skip_f) {
         if ($sum_weights > 0.0) {
            $relative_weight = $weights[$i_item] / $sum_weights;
         } else {
            $relative_weight = 1.0 / $n_items;
         }
         $x = mt_rand () / $rand_max;
         if ($x < $relative_weight) {
            $drawns[$i_item] = 1;
            break;
         }
      }
      $i_item++;
      if ($i_item >= $n_items) {
         $i_item = 0;
      }
   }
   if ($debug[7]) {
      error_log ('[qwiz_simply_random_draw] $i_item: ' . $i_item);
   }
   return $i_item;
}
function qwiz_summary ($txt, $summary_len) {
   $txtlen = strlen ($txt);
   if ($txtlen > 2*$summary_len) {
      $errtxt = substr ($txt, 0, $summary_len)
                . ' ... ' . substr ($txt, -$summary_len);
   } else {
      $errtxt = $txt;
   }
   return $errtxt;
}
add_filter ('the_content', 'qwiz_process_shortcodes_initially', 19);
add_action ('wp_ajax_textentry_suggestions', 'qwiz_textentry_suggestions');
add_action ('wp_ajax_nopriv_textentry_suggestions', 'qwiz_textentry_suggestions');
function qwiz_textentry_suggestions () {
   global $wpdb, $debug;
   if ($debug[4]) {
      error_log ('[qwiz_textentry_suggestions] $_POST: ' . print_r ($_POST, true));
   }
   $entry           = $_POST['entry'];
   $entry           = filter_var (urldecode ($entry), FILTER_SANITIZE_STRING);
   $entry_metaphone = $_POST['entry_metaphone'];
   $entry_metaphone = filter_var (urldecode ($entry_metaphone), FILTER_SANITIZE_STRING);
   $n_hints         = $_POST['n_hints'];
   if (! is_numeric ($n_hints)) {
      $n_hints = 0;
   }
   $terms           = json_decode (urldecode (base64_decode ($_POST['terms'])), true);
   $plural_f        = $_POST['plural_f'] == 1;
   if ($debug[4]) {
      error_log ('[qwiz_textentry_suggestions] $terms: ' . print_r ($terms, true));
      error_log ('[qwiz_textentry_suggestions] gettype ($terms): ' . gettype ($terms));
      error_log ('[qwiz_textentry_suggestions] count ($terms): ' . count ($terms));
   }
   $hint_clause = '';
   if ($n_hints > 0) {
      $hint = substr ($entry, 0, $n_hints);
      $hint_clause = "AND word LIKE %s";
      $hint_like = $hint . '%';
   }
   $n_chars = strlen ($entry);
   $limit = 15;
   $table = $wpdb->prefix . 'qwiz_textentry_suggestions';
   $sql  = "SELECT   word
            FROM     $table
            WHERE        word LIKE %s
                     OR  (metaphone LIKE %s $hint_clause)
            ORDER BY SUBSTR(word, 1, %d) != %s, word
            LIMIT $limit";
   $entry_like     = $entry           . '%';
   $metaphone_like = $entry_metaphone . '%';
   if ($n_hints > 0) {
      $sql = $wpdb->prepare ($sql, $entry_like, $metaphone_like, $hint_like,
                                   $n_chars, $entry);
   } else {
      $sql = $wpdb->prepare ($sql, $entry_like, $metaphone_like,
                                   $n_chars, $entry);
   }
   $rows = $wpdb->get_results ($sql, ARRAY_N);
   if ($debug[4]) {
      error_log ('[qwiz_textentry_suggestions] $sql: ' . $sql);
      error_log ('[qwiz_textentry_suggestions] count ($rows): ' . count ($rows));
   }
   $suggestions = array ();
   foreach ($rows as $row) {
      if ($plural_f) {
         $suggestions[] = pluralize ($row[0]);
      } else {
         $suggestions[] = $row[0];
      }
   }
   if (gettype ($terms) == 'array' && count ($terms)) {
      $suggestions = array_merge ($suggestions, $terms);
      $sort_key = array ();
      foreach ($suggestions as $suggestion) {
         $suggestion = strtolower ($suggestion);
         $a_z = substr ($suggestion, 0, $n_chars) == $entry ? 'a' : 'z';
         $sort_key[] = $a_z . $suggestion;
      }
      if ($debug[4]) {
         error_log ('[qwiz_textentry_suggestions] $suggestions: ' . print_r ($suggestions, true));
         error_log ('[qwiz_textentry_suggestions] $sort_key: ' . print_r ($sort_key, true));
      }
      array_multisort ($sort_key, SORT_NATURAL | SORT_FLAG_CASE , $suggestions);
      $suggestions = array_unique ($suggestions);
      $suggestions = array_slice ($suggestions, 0, $limit);
   }
   if ($debug[4]) {
      error_log ('[qwiz_textentry_suggestions] $suggestions: ' . print_r ($suggestions, true));
   }
   wp_send_json ($suggestions);
}
function pluralize ($term_i) {
   $last_char = substr ($term_i, -1);
   $last_2_chars = substr ($term_i, -2);
   if ($last_2_chars == 'es') {
      $term_i_plural = $term_i;
   } else if ($last_char == 'y') {
      $term_i_plural = substr ($term_i, 0, -1) . 'ies';
   } else if ($last_char == 's' || $last_2_chars == 'sh' || $last_2_chars == 'ch') {
      $term_i_plural = $term_i . 'es';
   } else if ($last_char == 'x') {
      $term_i_plural = $term_i;
   } else {
      $term_i_plural = $term_i . 's';
   }
   return $term_i_plural;
}
function textentry_suggestions_db_table_update () {
   global $wpdb, $debug;
   $ok_f = get_option ('textentry_suggestions_db_table_update');
   if ($ok_f != 'ok') {
      update_option ('textentry_suggestions_db_table_update', 'ok');
      $table = $wpdb->prefix . 'qwiz_textentry_suggestions';
      $charset_collate = $wpdb->get_charset_collate();
      $sql =  "CREATE TABLE $table (
                  word           varchar(31) NOT NULL,
                  metaphone      varchar(31),
                  UNIQUE KEY index_word (word),
                  KEY index_metaphone (metaphone)
               ) $charset_collate;";
      if ($debug[5]) {
         error_log ('[textentry_suggestions_db_table_update] $sql: ' . $sql);
      }
      require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta ($sql);
   }
}
add_action ('plugins_loaded', 'textentry_suggestions_db_table_update');
function qwiz_textentry_suggestions_data_update () {
   global $wpdb, $qwiz_textentry_suggestions_ver, $debug;
   $current_qwiz_textentry_suggestions_ver = get_option ('qwiz_textentry_suggestions_ver');
   if ($debug[0]) {
      error_log ('[qwiz_textentry_suggestions_data_update] $current_qwiz_textentry_suggestions_ver: ' . $current_qwiz_textentry_suggestions_ver);
   }
   if ($current_qwiz_textentry_suggestions_ver != $qwiz_textentry_suggestions_ver) {
      error_log ('[qwiz_textentry_suggestions_data_update] $current_qwiz_textentry_suggestions_ver: ' . $current_qwiz_textentry_suggestions_ver);
      error_log ('[qwiz_textentry_suggestions_data_update] $qwiz_textentry_suggestions_ver: ' . $qwiz_textentry_suggestions_ver);
      update_option ('qwiz_textentry_suggestions_ver', $qwiz_textentry_suggestions_ver);
      $table = $wpdb->prefix . 'qwiz_textentry_suggestions';
      $sql  = "DELETE FROM $table";
      $deleted_rows = $wpdb->query ($sql);
      if ($debug[0]) {
         error_log ('[qwiz_textentry_suggestions_data_update] $sql: ' . $sql);
         error_log ('[qwiz_textentry_suggestions_data_update] $deleted_rows: ' . $deleted_rows);
      }
      $suggestions_data_file = plugin_dir_path (__FILE__) . 'textentry_suggestions.txt';
      $sql_part1  = "INSERT INTO $table
                     (word, metaphone)
                     VALUES\n";
      $fh = fopen ($suggestions_data_file, 'r');
      $n_lines = 0;
      $value_lines = array ();
      while ($line = fgets ($fh)) {
         $n_lines++;
         $line = trim ($line);
         $fields = explode ("\t", $line);
         $value_line = '("' . $fields[0] . '","' . $fields[1] . '")';
         $value_lines[] = $value_line;
         if ($n_lines == 20000) {
            qwiz_textentry_suggestions_insert ($sql_part1, $value_lines);
            $value_lines = array ();
            $n_lines = 0;
         }
      }
      if ($n_lines) {
         qwiz_textentry_suggestions_insert ($sql_part1, $value_lines);
      }
      /* LOAD DATA INFILE - suffers permission problems on Hostmonster,
       * presumably others.
      $suggestions_data_file = preg_replace ('/^.:/', '', $suggestions_data_file);
      $suggestions_data_file = preg_replace ('/\\\\/', '/', $suggestions_data_file);
      $sql  = "LOAD DATA INFILE     '$suggestions_data_file'
               REPLACE INTO TABLE   $table
               (word, metaphone)";
      $affected_rows = $wpdb->query ($sql);
      if ($debug[3]) {
         error_log ('[qwiz_textentry_suggestions_data_update] $sql: ' . $sql);
      }
       */
   }
}
function qwiz_textentry_suggestions_insert ($sql_part1, $value_lines) {
   global $wpdb, $debug;
   $values = implode (',', $value_lines);
   $sql = $sql_part1 . $values;
   $affected_rows = $wpdb->query ($sql);
   if ($debug[3]) {
      error_log ('[qwiz_textentry_suggestions_insert] $sql: ' . substr ($sql, 0, 500) . '...\n');
   }
}
add_action ('plugins_loaded', 'qwiz_textentry_suggestions_data_update');
/* DKTMP
add_action ('wp_head', 'qwiz_viewport_scaling');
function qwiz_viewport_scaling () {
   print '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
}
 */
add_action ('plugins_loaded', 'qwiz_dataset_json_db_table_update');
function qwiz_dataset_json_db_table_update () {
   global $wpdb, $debug;
   $ok_f = get_option ('dataset_json_db_table_update');
   if ($ok_f != 'ok') {
      update_option ('dataset_json_db_table_update', 'ok');
      $table = $wpdb->prefix . 'qwiz_dataset_json2';
      $sql =  "CREATE TABLE $table (
                  post_id                 INTEGER NOT NULL,
                  maker_session_id        VARCHAR(255),
                  qwizzes_json_string     MEDIUMTEXT,
                  qdecks_json_string      MEDIUMTEXT,
                  update_msg              VARCHAR(255),
                  updated                 TIMESTAMP,
                  PRIMARY KEY  (post_id)
               );";
      if ($debug[5]) {
         error_log ('[qwiz_dataset_json_db_table_update] $sql: ' . $sql);
      }
      require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta ($sql);
   }
}
add_action ('wp_ajax_qwiz_save_dataset_json', 'qwiz_save_dataset_json');
add_action ('wp_ajax_nopriv_qwiz_save_dataset_json', 'qwiz_save_dataset_json');
function qwiz_save_dataset_json () {
   global $wpdb, $debug;
   if ($debug[0]) {
      error_log ('[qwiz_save_dataset_json] $_POST: ' . print_r ($_POST, true));
   }
   $_POST = array_map ('stripslashes_deep', $_POST);
   $maker_session_id    = $_POST['maker_session_id'];
   $maker_session_id    = filter_var (urldecode ($maker_session_id), FILTER_SANITIZE_STRING);
   $post_id             = $_POST['post_id'];
   if (! is_numeric ($post_id)) {
      return;
   }
   $qwizzes_json_string = $_POST['qwizzes_json_string'];
   $qdecks_json_string  = $_POST['qdecks_json_string'];
   if ($debug[5]) {
      error_log ('[qwiz_save_dataset_json] $qwizzes_json_string: ' . $qwizzes_json_string);
   }
   $table = $wpdb->prefix . 'qwiz_dataset_json2';
   $sql  = "REPLACE INTO   $table
            SET            post_id             = %d,
                           maker_session_id    = %s,
                           qwizzes_json_string = %s,
                           qdecks_json_string  = %s";
   $sql = $wpdb->prepare ($sql, $post_id, $maker_session_id, $qwizzes_json_string, $qdecks_json_string);
   $affected_rows = $wpdb->query ($sql);
   if ($affected_rows === false) {
      error_log ("[qwiz_save_dataset_json] unable to insert into $table");
      $affected_rows = 0;
   }
   wp_send_json ($affected_rows);
   wp_die ();  // Required to terminate and return proper response.
}
add_action ('wp_ajax_qwiz_get_dataset_questions_feedback', 'qwiz_get_dataset_questions_feedback');
add_action ('wp_ajax_nopriv_qwiz_get_dataset_questions_feedback', 'qwiz_get_dataset_questions_feedback');
function qwiz_get_dataset_questions_feedback () {
   global $wpdb, $debug;
   $post_id = $_POST['post_id'];
   if ($debug[0]) {
      error_log ('[qwiz_get_dataset_questions_feedback] post_id: ' . $post_id  . ', is_numeric: ' . is_numeric ($post_id));
   }
   if (! is_numeric ($post_id)) {
      return;
   }
   for ($i=0; $i<10; $i++) {
      $update_msg = qwiz_get_dataset_update_msg ($post_id);
      if ($debug[0]) {
         error_log ('[qwiz_get_dataset_questions_feedback] update_msg: ' . $update_msg . ' (' . time (). ')');
      }
      if ($update_msg) {
         qwiz_save_dataset_update_msg ($post_id, '');
         break;
      } else {
         usleep (200000);
      }
   }
   if (! $update_msg) {
      error_log ('[qwiz_get_dataset_questions_feedback] no update_msg');
   }
   wp_send_json ($update_msg);
   wp_die ();  // Required to terminate and return proper response.
}
function qwiz_get_dataset_json ($post_id) {
   global $wpdb, $debug;
   $table = $wpdb->prefix . 'qwiz_dataset_json2';
   $sql  = "SELECT   maker_session_id,
                     qwizzes_json_string,
                     qdecks_json_string
            FROM     $table
            WHERE    post_id = %d";
   $sql = $wpdb->prepare ($sql, $post_id);
   $rows = $wpdb->get_results ($sql, ARRAY_A);
   if ($debug[0] || $debug[5]) {
      error_log ('[qwiz_get_dataset_json] rows: ' . print_r ($rows, true));
   }
   if ($rows) {
      $row = $rows[0];
      $maker_session_id    = json_encode ($row['maker_session_id']);
      $qwizzes_json_string = $row['qwizzes_json_string'];
      $qdecks_json_string  = $row['qdecks_json_string'];
      if ($qwizzes_json_string == '') {
         $qwizzes_json_string = '""';
      }
      if ($qdecks_json_string == '') {
         $qdecks_json_string = '""';
      }
   } else {
      error_log ("[qwiz_get_dataset_json] unable to retrieve $post_id from $table");
      $qwizzes_json_string = '""';
      $qdecks_json_string  = '""';
   }
   $quizzes_questions_qdecks_cards = json_decode ("[$maker_session_id,$qwizzes_json_string,$qdecks_json_string]", true);
   if ($debug[0] || $debug[5]) {
      error_log ('[qwiz_get_dataset_json] quizzes_questions_qdecks_cards: ' . print_r ($quizzes_questions_qdecks_cards, true));
   }
   return $quizzes_questions_qdecks_cards;
}
function qwiz_save_dataset_update_msg ($post_id, $update_msg) {
   global $wpdb, $debug;
   if ($debug[0]) {
      error_log ("[qwiz_save_dataset_update_msg] post_id: $post_id, update_msg: $update_msg");
   }
   $table = $wpdb->prefix . 'qwiz_dataset_json2';
   $sql  = "INSERT INTO       $table
            SET               post_id    = %d,
                              update_msg = %s
            ON DUPLICATE KEY  UPDATE update_msg = %s";
   $sql = $wpdb->prepare ($sql, $post_id, $update_msg, $update_msg);
   $affected_rows = $wpdb->query ($sql);
   if ($affected_rows === false) {
      error_log ("[qwiz_save_dataset_update_msg] unable to insert into $table");
      $affected_rows = 0;
   }
}
function qwiz_get_dataset_update_msg ($post_id) {
   global $wpdb, $debug;
   $table = $wpdb->prefix . 'qwiz_dataset_json2';
   $sql  = "SELECT   update_msg
            FROM     $table
            WHERE    post_id = %d";
   $sql = $wpdb->prepare ($sql, $post_id);
   $rows = $wpdb->get_results ($sql, ARRAY_A);
   if ($rows) {
      $row = $rows[0];
      $update_msg = $row['update_msg'];
   } else {
      $update_msg = '';
   }
   if ($debug[0] || $debug[5]) {
      error_log ("[qwiz_get_dataset_update_msg] post_id: $post_id, count (rows): " . count ($rows));
   }
   return $update_msg;
}
add_action ('admin_footer-post.php', 'qwiz_admin_footer_post_func');
add_action ('admin_footer-post-new.php', 'qwiz_admin_footer_post_func');
function qwiz_admin_footer_post_func () {
   global $options, $debug, $post_type;
   if ($debug[0]) {
      error_log ("[qwiz_admin_footer_post_func] post_type: $post_type");
   }
   if ($post_type == 'post' || $post_type == 'page') {
      $post_id = get_the_ID ();
      $style = <<<EOS
         <style type="text/css">
            #qwiz-publish-intercept {
               position:   absolute;
               width:      100%;
               height:     100%;
               top:        0;
               z-index:    2;
               background: none;
            }
            #qwiz-publish-intercept-gutenberg {
               position:   absolute;
               z-index:    2;
               background: none;
            }
         </style>
EOS;
      $script = <<<EOD
   var post_id;
   var gutenberg_f = true;
   var \$qwiz_publish_intercept;
   var delay_script = function () {
      qwiz_syntax_check = function (e) {
         if (qwiz_debug) {
            console.log ('[qwiz_admin_footer_post_func] create_qwizard_structure_json_b:',  create_qwizard_structure_json_b);
            console.log ('[qwiz_admin_footer_post_func] original_post_status:',  original_post_status);
         }
         if (! create_qwizard_structure_json_b) {
            return true;
         }
         if (! qwiz_syntax_check_manual_only) {
            if (gutenberg_f) {
               \$publish.addClass ('is-busy');
            } else {
               \$ ('div#publishing-action span.spinner').css ({visibility: 'visible'});
            }
            \$qwiz_publish_intercept.hide ();
         }
         var \$edit_area;
         var \$textarea;
         var \$iframe;
         var htm;
         if (gutenberg_f) {
            \$edit_area = \$ ('div.wp-block');
         } else {
            var \$iframe = \$ ('iframe#content_ifr, iframe#wpb_tinymce_content_ifr');
            if (\$iframe.is (':visible')) {
               \$edit_area = \$iframe.contents ().find ('body');
            } else {
               \$textarea = \$ ('textarea.wp-editor-area');
            }
         }
         if (\$edit_area) {
            \$edit_area.find ('div.qwizzled_canvas .qwizzled_target, div.hangman_label').each (function () {
               var \$this = \$ (this);
               var styles = \$this.attr ('style');
               if (/inset/.test (styles)) {
                  var m = styles.match (/inset:\s*([^;]+);/);
                  if (qwiz_debug) {
                     console.log ('[qwiz_admin_footer_post_func] styles.match:', m);
                  }
                  if (m) {
                     var fields = m[1].split (/\s/);
                     if (fields.length == 4) {
                        var top_px = fields[0];
                        var left_px = fields[3];
                        \$this.css ({inset: '', left: left_px, top: top_px});
                     }
                  }
               }
            });
         } else {
            htm = \$textarea.val ();
            htm = htm.replace (/inset:[^;]+;/g, cvt_inset);
            function cvt_inset (css) {
               var fields = css.split (/\s/);
               if (qwiz_debug) {
                  console.log ('[qwiz_admin_footer_post_func > cvt_inset] fields:', fields);
               }
               if (fields.length == 5) {
                  var left_px = fields[4];
                  var top_px  = fields[1];
                  return 'top: ' + top_px + '; left: ' + left_px;
               } else {
                  return css;
               }
            }
            \$textarea.val (htm);
         }
         if (gutenberg_f) {
            htm = wp.data.select ('core/editor').getEditedPostContent ();
         } else {
            if (\$iframe.is (':visible')) {
               var \$edit_area = \$iframe.contents ().find ('body');
               htm = \$edit_area.html ();
               if (qwiz_debug) {
                  console.log ('[qwiz_admin_footer_post_func] \$iframe:', \$iframe, ', \$edit_area:', \$edit_area);
               }
            } else {
               if (qwiz_debug) {
                  console.log ('[qwiz_admin_footer_post_func] \$ (\'textarea.wp-editor-area\'):', \$ ('textarea.wp-editor-area'));
               }
            }
         }
         if (qwiz_debug) {
            console.log ('[qwiz_admin_footer_post_func] htm.substr (0, 2000): ', htm.substr (0, 2000));
         }
         var quizzes_b = /\[qwiz/.test (htm);
         var decks_b   = /\[qdeck/.test (htm);
         if (quizzes_b || decks_b) {
            if (qwiz_debug) {
               console.log ('[qwiz_admin_footer_post_func] typeof (qwizard):', typeof (qwizard));
               console.log ('[qwiz_admin_footer_post_func] typeof (qwizzled):', typeof (qwizzled));
            }
            var delay_process_html2 = function () {
               qwizard.set_qwizzled_plugin_url ();
               var errmsgs = [];
               var n_quizzes = 0;
               var n_decks = 0;
               if (quizzes_b) {
                  qwiz_.set_qwizdata (-1, 'errmsgs', '[]');
                  qwiz_.process_html2 (htm, 0, false, true);
                  if (qwiz_debug) {
                     console.log ('[qwiz_admin_footer_post_func] qwiz_.quizzes_questions:', qwiz_.quizzes_questions);
                     console.log ('[qwiz_admin_footer_post_func] qwizard.errmsgs:', qwizard.errmsgs);
                  }
                  if (qwizard.errmsgs.length) {
                     errmsgs = qwizard.errmsgs;
                  }
               }
               if (decks_b) {
                  qcard_.set_deckdata (-1, 'errmsgs', '[]');
                  qcard_.process_html2 (htm, 0, false, true);
                  if (qwiz_debug) {
                     console.log ('[qwiz_admin_footer_post_func] qcard_.decks_cards:', qwiz_.decks_cards);
                     console.log ('[qwiz_admin_footer_post_func] qwizard.errmsgs:', qwizard.errmsgs);
                  }
                  if (qwizard.errmsgs.length) {
                     errmsgs = errmsgs.concat (qwizard.errmsgs);
                  }
               }
               if (errmsgs.length) {
                  var s = errmsgs.length > 1 ? 's' : '';
                  errmsgs = errmsgs.join ('\\n');
                  var ok_f = confirm (  'Error' + s + ' found:\\n\\n'
                                      + errmsgs + '\\n\\n'
                                      + 'Save/update anyway? (click Cancel to continue editing)');
                  if (! ok_f) {
                     create_qwizard_structure_json_b = true;
                     if (gutenberg_f) {
                        \$publish.removeClass ('is-busy');
                     } else {
                        \$ ('div#publishing-action span.spinner').css ({visibility: 'hidden'});
                     }
                     return false;
                  }
               } else if (qwiz_syntax_check_manual_only) {
                  alert ('No errors found');
               }
               /* ------------------------------
               var r = qwiz_add_structure_indicators (htm);
               if (r.errmsgs.length) {
                  var es = errmsgs.length > 1 ? 'es' : '';
                  var errmsg =  'Mismatch' + es + ' between number of div opening and closing tags:\\n'
                              + r.errmsgs.join ('\\n')
                              + '\\n\\nNote: use Text mode to see div tags';
                  var ok_f = confirm (errmsg + '\\n\\n'
                                      + 'Save/update anyway? (click Cancel to continue editing)');
                  if (! ok_f) {
                     create_qwizard_structure_json_b = true;
                     if (gutenberg_f) {
                        \$publish.removeClass ('is-busy');
                     } else {
                        \$ ('div#publishing-action span.spinner').css ({visibility: 'hidden'});
                     }
                     return false;
                  }
               }
               ---------------------------------- */
               if (qwiz_.quizzes_questions) {
                  n_quizzes = qwiz_.quizzes_questions.length;
               }
               var qwizzes_json = [];
               for (var i_qwiz=0; i_qwiz<n_quizzes; i_qwiz++) {
                  if (qwiz_.quizzes_questions[i_qwiz]) {
                     if (qwiz_debug) {
                        console.log ('[qwiz_admin_footer_post_func] qwiz_.quizzes_questions[i_qwiz][0].dataset_b:', qwiz_.quizzes_questions[i_qwiz][0].dataset_b);
                     }
                     if (qwiz_.quizzes_questions[i_qwiz][0].dataset_b) {
                        qwiz_.quizzes_questions[i_qwiz].i_qwiz = i_qwiz;
                        qwizzes_json.push (qwiz_.quizzes_questions[i_qwiz]);
                     }
                  }
               }
               var qwizzes_json_string = '';
               if (qwizzes_json.length) {
                  qwizzes_json_string = JSON.stringify (qwizzes_json);
                  if (qwiz_debug) {
                     console.log ('[qwiz_admin_footer_post_func] qwizzes_json_string.substr (0, 5000):', qwizzes_json_string.substr (0, 5000));
                     console.log ('[qwiz_admin_footer_post_func] qwizzes_json_string.length:', qwizzes_json_string.length);
                  }
               }
               if (qcard_.decks_cards) {
                  n_decks = qcard_.decks_cards.length;
               }
               var qdecks_json = [];
               for (var i_deck=0; i_deck<n_decks; i_deck++) {
                  if (qcard_.decks_cards[i_deck]) {
                     if (qwiz_debug) {
                           console.log ('[qwiz_admin_footer_post_func] qcard_.decks_cards[' + i_deck + '][0].dataset_b:', qcard_.decks_cards[i_deck][0].dataset_b);
                     }
                     if (qcard_.decks_cards[i_deck][0].dataset_b) {
                        qcard_.decks_cards[i_deck].i_deck = i_deck;
                        qdecks_json.push (qcard_.decks_cards[i_deck]);
                     }
                  }
               }
               var qdecks_json_string = '';
               if (qdecks_json.length) {
                  qdecks_json_string = JSON.stringify (qdecks_json);
                  if (qwiz_debug) {
                     console.log ('[qwiz_admin_footer_post_func] qdecks_json_string.substr (0, 5000):', qdecks_json_string.substr (0, 5000));
                     console.log ('[qwiz_admin_footer_post_func] qdecks_json_string.length:', qdecks_json_string.length);
                  }
               }
               if (qwizzes_json_string || qdecks_json_string) {
                  var maker_session_id = '';
                  if (qwizzled && qwizzled.maker_session_id) {
                     maker_session_id = qwizzled.maker_session_id;
                  }
                  var data = {action:              'qwiz_save_dataset_json',
                              maker_session_id:    maker_session_id,
                              post_id:             post_id,
                              qwizzes_json_string: qwizzes_json_string,
                              qdecks_json_string:  qdecks_json_string
                             };
                  if (qwiz_debug) {
                     console.log ('[qwiz_admin_footer_post_func > qwiz_save_dataset_json] data:', data);
                  }
                  jQuery.ajax ({
                     type:       'POST',
                     url:        qwiz_ajaxurl,
                     data:       data,
                     dataType:   'json',
                     error:      function (xhr, desc) {
                                    if (qwiz_debug) {
                                       console.log ('[qwiz_admin_footer_post_func > qwiz_save_dataset_json] error desc:', desc);
                                    }
                                 },
                     success:    save_dataset_json_callback
                  });
               } else {
                  save_dataset_json_callback (1);
               }
               function save_dataset_json_callback (affected_rows) {
                  if (affected_rows < 1) {
                     alert ('Sorry, was not able to save dataset questions properly');
                  }
                  if (qwiz_debug) {
                     console.log ('[qwiz_admin_footer_post_func] calling qwiz_publish_post() (1)');
                  }
                  if (! qwiz_syntax_check_manual_only) {
                     qwiz_publish_post (\$publish, true);
                  }
               }
            }
            setTimeout (delay_process_html2, 250);
            return false;
         } else {
            if (qwiz_debug) {
               console.log ('[qwiz_admin_footer_post_func] calling qwiz_publish_post() (2)');
            }
            if (! qwiz_syntax_check_manual_only) {
               qwiz_publish_post (\$publish, false);
            }
            return true;
         }
      }
      post_id = jQuery ('#post_ID').val ();
      var \$publish = jQuery ('button.editor-post-publish-button, button.editor-post-publish-panel__toggle.is-button');
      if (\$publish.length == 0) {
         \$publish = jQuery ('#publish');
         gutenberg_f = false;
      }
      var create_qwizard_structure_json_b = true;
      jQuery (function (\$) {
         if (! qwiz_syntax_check_manual_only) {
            if (gutenberg_f) {
               var \$header_settings = jQuery ('div.edit-post-header__settings');
               var header_settings_width = parseInt (\$header_settings.width ());
               \$header_settings.css ({position: 'relative'});
               var gutenberg_publish_button_width  = \$publish.outerWidth ();
               var gutenberg_publish_button_height = \$publish.outerHeight ();
               var gutenberg_publish_button_position = \$publish.position ();
               var gutenberg_publish_button_top   = parseInt (gutenberg_publish_button_position.top);
               var gutenberg_publish_button_left  = parseInt (gutenberg_publish_button_position.left);
               var gutenberg_publish_button_right = header_settings_width - gutenberg_publish_button_left - gutenberg_publish_button_width;
               \$header_settings.append ('<div id="qwiz-publish-intercept-gutenberg"></div>');
               \$qwiz_publish_intercept = jQuery ('#qwiz-publish-intercept-gutenberg');
               \$qwiz_publish_intercept.css ({top: '' + gutenberg_publish_button_top + 'px', right: '' + gutenberg_publish_button_right + 'px', width: gutenberg_publish_button_width + 'px', height: gutenberg_publish_button_height + 'px'});
            } else {
               var \$publishing_action = jQuery ('#publishing-action');
               \$publishing_action.css ({position: 'relative'});
               \$publishing_action.append ('<div id="qwiz-publish-intercept"></div>');
               \$qwiz_publish_intercept = jQuery ('#qwiz-publish-intercept');
            }
            if (qwiz_debug) {
               console.log ('[qwiz_admin_footer_post_func] \$qwiz_publish_intercept:',  \$qwiz_publish_intercept);
            }
            \$qwiz_publish_intercept.on ('click.qwiz', function (e) {
               qwiz_syntax_check (e);
            });
         }
      });
   }
   if (qwiz_debug) {
      console.log ('[qwiz_admin_footer_post_func] calling setTimeout(delay_script, 5000)');
   }
   setTimeout (delay_script, 5000);
   function qwiz_publish_post (\$publish, quizzes_decks_f) {
      if (qwiz_debug) {
         console.log ('[qwiz_admin_footer_post_func > qwiz_publish_post] post_id:', post_id, ', quizzes_decks_f:', quizzes_decks_f, ', gutenberg_f:', gutenberg_f);
      }
      var ok_f = true;
      if (quizzes_decks_f && gutenberg_f) {
         if (qwizzled && qwizzled.dataset_b && ! qwizzled.maker_logged_in_b) {
            ok_f = ! confirm ('Note: you must log in to save dataset questions.\\nTo log in click "OK" and then click the "Q" icon.\\nClick "Cancel" to continue anyway');
         }
         if (ok_f) {
            var data = {action:  'qwiz_get_dataset_questions_feedback',
                        post_id: post_id};
            if (qwiz_debug) {
               console.log ('[qwiz_admin_footer_post_func > qwiz_publish_post] data:', data);
            }
            jQuery.ajax ({
               type:       'POST',
               url:        qwiz_ajaxurl,
               data:       data,
               dataType:   'json',
               error:      function (xhr, desc) {
                              if (qwiz_debug) {
                                 console.log ('[qwiz_admin_footer_post_func > qwiz_publish_post] error desc:', desc);
                              }
                           },
               success:    qwiz_dataset_questions_feedback_alert
            });
         }
      }
      if (ok_f) {
         \$publish.trigger ('click');
      }
      if (! qwiz_syntax_check_manual_only) {
         \$qwiz_publish_intercept.show ();
      }
   }
   function qwiz_dataset_questions_feedback_alert (data) {
      if (qwiz_debug) {
         console.log ('[qwiz_admin_footer_post_func > qwiz_dataset_questions_feedback_alert] data:', data);
      }
      if (data) {
         alert (data);
         if (typeof qwizzled_params.update_msg != 'undefined') {
            qwizzled_params.update_msg = '';
         }
      }
   }
   function qwiz_add_structure_indicators (htm) {
      var quiz_deck_htms = htm.split (/(\[\/qwiz\]|\[\/qdeck\])/);
      if (qwiz_debug) {
         console.log ('[qwiz_add_structure_indicators] quiz_deck_htms:', quiz_deck_htms);
      }
      var n_quizzes_decks = quiz_deck_htms.length;
      var qwiz_qdeck;
      var ii_qwiz = 0;
      var ii_deck = 0;
      var errmsgs = [];
      for (var i=0; i<n_quizzes_decks-1; i++) {
         if (quiz_deck_htms[i+1] == '[/qwiz]') {
            qwiz_qdeck = 'qwiz';
            ii_qwiz++;
         } else if (quiz_deck_htms[i+1] == '[/qdeck]') {
            qwiz_qdeck = 'qdeck';
            ii_deck++;
         }
         if (quiz_deck_htms[i] == '[/qwiz]' || quiz_deck_htms[i] == '[/qdeck]') {
            continue;
         }
         var r = qwiz_warn_clean_question_divs (quiz_deck_htms[i], qwiz_qdeck, ii_qwiz, ii_deck);
         if (r.errmsg) {
            errmsgs.push (r.errmsg);
         }
         quiz_deck_htms[i] = r.quiz_deck_htm;
      }
      if (qwiz_debug && errmsgs.length) {
         console.log ('[qwiz_add_structure_indicators] errmsgs:', errmsgs);
      }
      return {'htm':       quiz_deck_htms.join (''),
              'errmsgs':   errmsgs};
   }
   function qwiz_check_divs (htm) {
      var quiz_deck_htms = htm.split (/(\[\/qwiz\]|\[\/qdeck\])/);
      if (qwiz_debug) {
         console.log ('[qwiz_check_divs] quiz_deck_htms:', quiz_deck_htms);
      }
      var n_quizzes_decks = quiz_deck_htms.length;
      var qwiz_qdeck;
      var ii_qwiz = 0;
      var ii_deck = 0;
      var errmsgs = [];
      for (var i=0; i<n_quizzes_decks-1; i++) {
         if (quiz_deck_htms[i+1] == '[/qwiz]') {
            qwiz_qdeck = 'qwiz';
            ii_qwiz++;
         } else if (quiz_deck_htms[i+1] == '[/qdeck]') {
            qwiz_qdeck = 'qdeck';
            ii_deck++;
         }
         if (quiz_deck_htms[i] == '[/qwiz]' || quiz_deck_htms[i] == '[/qdeck]') {
            continue;
         }
         if (/\[(qwiz|qdeck)[^\]]*?\s+dataset\s*=/.test (quiz_deck_htms[i])) {
            quiz_deck_htms[i]
               = quiz_deck_htms[i]
                         .replace (/(\[q\s[^\]]*?)(json=.true.\s*)*([^\]]*\])/g,
                                   '\\\$1json="true" \\\$3');
            quiz_deck_htms[i] = quiz_deck_htms[i].replace (/\[q\]/g,
                                                           '[q json="true"]');
         }
         var r = qwiz_warn_clean_question_divs (quiz_deck_htms[i], qwiz_qdeck, ii_qwiz, ii_deck);
         if (r.errmsg) {
            errmsgs.push (r.errmsg);
         }
         quiz_deck_htms[i] = r.quiz_deck_htm;
      }
      if (qwiz_debug && errmsgs.length) {
         console.log ('[qwiz_check_divs] errmsgs:', errmsgs);
      }
      return {'htm':       quiz_deck_htms.join (''),
              'errmsgs':   errmsgs};
   }
   function qwiz_warn_clean_question_divs (quiz_deck_htm, qwiz_qdeck, ii_qwiz, ii_deck) {
      var qqc = qwiz_qcards_common;
      if (qwiz_debug) {
         console.log ('[qwiz_warn_clean_question_divs] quiz_deck_htm:', quiz_deck_htm);
      }
      var i_question = 0;
      var errmsgs = [];
      var qwizzled_parts = quiz_deck_htm.split (/(<div class="qwizzled_question[^]*?<!-- close qwizzled_question -->)/);
      var question_htms = [];
      var n_qwizzled_parts = qwizzled_parts.length;
      for (var i=0; i<n_qwizzled_parts; i++) {
         if (qwizzled_parts[i].indexOf ('<div class="qwizzled_question') == -1) {
            question_htms = question_htms.concat (qwizzled_parts[i].split (/(?:\[q\]|\[q\s[^\]]+\])/));
         } else {
            question_htms = question_htms.concat (qwizzled_parts[i]);
         }
      }
      if (qwiz_debug) {
         console.log ('[qwiz_warn_clean_question_divs] JSON.stringify (question_htms):', JSON.stringify (question_htms));
      }
      var n_question_pieces = question_htms.length;
      var opening_tags = '';
      for (var i=0; i<n_question_pieces; i++) {
         var piece_htm = opening_tags + question_htms[i];
         opening_tags = qqc.find_opening_tags_at_end (piece_htm);
         if (/\S/.test (opening_tags)) {
            if (qwiz_debug) {
               console.log ('[qwiz_warn_clean_question_divs] opening_tags:', opening_tags);
            }
            var opening_tags_pat = opening_tags + '\$';
            re = new RegExp (opening_tags_pat);
            piece_htm = piece_htm.replace (re, '');
         }
         var opening_matches = piece_htm.match (/<div/g);
         var n_opening_divs = 0;
         if (opening_matches) {
            n_opening_divs = opening_matches.length;
            var self_closing_matches = piece_htm.match (/<div[^>]*?\/>/g);
            if (self_closing_matches) {
               n_opening_divs -= self_closing_matches.length;
            }
         }
         var closing_matches = piece_htm.match (/<\/div/g);
         var n_closing_divs = 0;
         if (closing_matches) {
            n_closing_divs = closing_matches.length;
         }
         if (qwiz_debug) {
            console.log ('[qwiz_warn_clean_question_divs] i:', i, ', piece_htm:', piece_htm);
            console.log ('[qwiz_warn_clean_question_divs] n_opening_divs:' + n_opening_divs + ', n_closing_divs:', n_closing_divs);
         }
         var errmsg;
         if (n_opening_divs != n_closing_divs) {
            if (qwiz_qdeck == 'qwiz') {
               var qtext = i == 0 ? 'before first question' : 'question ' + i;
               errmsg = 'Qwiz ' + ii_qwiz + ', ' + qtext + ': ' + qqc.number_to_word (n_opening_divs) + ' opening ' + qqc.Tplural ('tag', 'tags', n_opening_divs) + ' (<div...>), ' + qqc.number_to_word (n_closing_divs) + ' closing ' + qqc.Tplural ('tag', 'tags', n_closing_divs) + ' (</div>)';
            } else {
               var ctext = i == 0 ? 'before first card' : 'card ' + i;
               errmsg = 'Deck ' + ii_deck + ', ' + ctext + ': ' + qqc.number_to_word (n_opening_divs) + ' opening ' + qqc.Tplural ('tag', 'tags', n_opening_divs) + ' (<div...>), ' + qqc.number_to_word (n_closing_divs) + ' closing ' + qqc.Tplural ('tag', 'tags', n_closing_divs) + ' (</div>)';
            }
            errmsgs.push (errmsg);
         }
      }
      if (qwiz_debug && errmsgs.length) {
         console.log ('[qwiz_warn_clean_question_divs] errmsgs:', errmsgs);
      }
      return {'quiz_deck_htm':  quiz_deck_htm,
              'errmsg':         errmsgs.join ('\\n')};
   }
EOD;
      print $style;
      $ajaxurl                       = admin_url ('admin-ajax.php');
      $qwiz_syntax_check_manual_only = '';
      if (isset ($options['qwiz_syntax_check_manual_only'])) {
         $qwiz_syntax_check_manual_only = $options['qwiz_syntax_check_manual_only'];
      }
      print "<script>\n";
      print    "var \$ = jQuery;\n";
      print    "var qwiz_syntax_check;\n";
      print    "var qwiz_debug   = '$debug[0]';\n";
      print    "var qwiz_ajaxurl = '$ajaxurl';\n";
      print    "var qwiz_syntax_check_manual_only = '$qwiz_syntax_check_manual_only';\n";
      print    $script;
      print "</script>\n";
   }
}
function check_for_qwizcards_db_dataset ($data, $postarr) {
   global $debug;
   $post_id   = $postarr['ID'];
   $post_name = $data['post_name'];
   $post_type = $data['post_type'];
   if ($debug[0]) {
      error_log ("[check_for_qwizcards_db_dataset] post_name $post_name, post_type: $post_type");
   }
   if (strpos ($post_name, 'autosave') !== false || $post_type != 'page') {
      return $data;
   }
   $html = stripslashes ($data['post_content']);
   $i_pos = strpos ($html, 'dataset=');
   if ($debug[0]) {
      error_log ('[check_for_qwizcards_db_dataset] $data: ' . print_r ($data, true));
      error_log ('[check_for_qwizcards_db_dataset] $i_pos: ' . $i_pos);
   }
   if ($i_pos === false) {
      return $data;
   }
   $dataset_html = qwiz_update_datasets ($html, $post_id, '', '');
   if ($dataset_html) {
      if ($debug[0]) {
         error_log ('[check_for_qwizcards_db_dataset] substr ($dataset_html, 0): ' . substr ($dataset_html, 0));
      }
      $data['post_content'] = addslashes ($dataset_html);
   }
   list ($qrecord_ids, $datasets, $qrecord_id_n_questions)
                                               = qwiz_parse_qrecord_ids ($html);
   if ($qrecord_ids) {
      qwiz_qdeck_unit_counts_to_db ($qrecord_ids, $datasets, $qrecord_id_n_questions);
   }
   if ($debug[0]) {
      error_log ('[check_for_qwizcards_db_dataset] $data: ' . print_r ($data, true));
   }
   return $data;
}
add_filter ('wp_insert_post_data', 'check_for_qwizcards_db_dataset', 10, 2);
include 'qwiz_update_datasets.php';
include 'qwiz_get_attr.php';
function qwiz_get_previous_version ($post_id) {
   global $debug, $wpdb;
   $table = $wpdb->prefix . 'posts';
   $sql  =  "SELECT     post_content,
                        ID,
                        post_name,
                        post_type
             FROM       $table
             WHERE          post_parent = $post_id
                        AND post_name   NOT LIKE '%autosave%'
                        AND post_type   = 'revision'
             ORDER BY   post_date DESC
             LIMIT      1";
   $rows = $wpdb->get_results ($sql, ARRAY_A);
   if ($debug[5]) {
      error_log ('[qwiz_get_previous_version] $sql: ' . $sql);
      error_log ('[qwiz_get_previous_version] count ($rows): ' . count ($rows));
      error_log ('[qwiz_get_previous_version] $wpdb->print_error (): ' . $wpdb->print_error ());
      if ($rows) {
         $row = $rows[0];
         error_log ('[qwiz_get_previous_version] ID: ' . $row['ID']);
         error_log ('[qwiz_get_previous_version] post_name: ' . $row['post_name']);
         error_log ('[qwiz_get_previous_version] post_type: ' . $row['post_type']);
      }
   }
   if ($rows) {
      $row = $rows[0];
      $prev_html = $row['post_content'];
   } else {
      $prev_html = '';
   }
   return $prev_html;
}
function qwiz_dataset_questions_to_db ($qdata, $post_id, $maker_session_id,
                                                                   $permalink) {
   global $debug, $secure_server_loc;
   $datasets         = array ();
   $dataset_ids      = array ();
   $question_numbers = array ();
   $htmls            = array ();
   $jsons            = array ();
   $units            = array ();
   $topics           = array ();
   $difficulties     = array ();
   $deleted_datasets    = array();
   $deleted_dataset_ids = array();
   $n_questions = count ($qdata['dataset_ids']);
   for ($i_question=0; $i_question<$n_questions; $i_question++) {
      if (isset ($qdata['new_modified'][$i_question])) {
         $datasets[]         = $qdata['datasets'][$i_question];
         $dataset_ids[]      = $qdata['dataset_ids'][$i_question];
         $question_numbers[] = $qdata['question_numbers'][$i_question];
         $htmls[]            = $qdata['htmls'][$i_question];
         $jsons[]            = $qdata['jsons'][$i_question];
         $units[]            = $qdata['units'][$i_question];
         $topics[]           = $qdata['topics'][$i_question];
         $difficulties[]     = $qdata['difficulties'][$i_question];
         if (strpos ($qdata['htmls'][$i_question], '[deleted]') !== false) {
            $deleted_datasets[]    = $qdata['datasets'][$i_question];
            $deleted_dataset_ids[] = $qdata['dataset_ids'][$i_question];
         }
      }
   }
   $body = array ('maker_session_id'    => $maker_session_id,
                  'datasets'            => json_encode ($datasets),
                  'dataset_ids'         => json_encode ($dataset_ids),
                  'question_numbers'    => json_encode ($question_numbers),
                  'page_url'            => json_encode ($permalink),
                  'htmls'               => json_encode ($htmls),
                  'structure_jsons'     => json_encode ($jsons),
                  'units'               => json_encode ($units),
                  'topics'              => json_encode ($topics),
                  'difficulties'        => json_encode ($difficulties),
                  'deleted_datasets'    => json_encode ($deleted_datasets),
                  'deleted_dataset_ids' => json_encode ($deleted_dataset_ids)
                 );
   $url = $secure_server_loc . '/update_dataset_questions.php';
   $http_request = new WP_Http;
   $result = $http_request->request ($url, array ('method'  => 'POST',
                                                  'timeout' => 40,    // Seconds.
                                                  'body'    => $body));
   if ($debug[0] || $debug[5]) {
      error_log ('[qwiz_dataset_questions_to_db] gettype (result): ' . gettype ($result));
      if (! is_wp_error ($result)) {
         error_log ('[qwiz_dataset_questions_to_db] result: ' . print_r ($result, true));
      }
   }
   if (is_wp_error ($result)) {
      error_log ('[qwiz_dataset_questions_to_db] $result->get_error_messages (): ' . print_r ($result, true));
      return;
   }
   if ($result['response']['message'] == 'OK') {
      $update_msg = $result['body'];
   } else {
      $update_msg = 'Unable to update questions/cards in Qwizcards database.';
   }
   qwiz_save_dataset_update_msg ($post_id, $update_msg);
   if ($debug[0] || $debug[5]) {
      error_log ('[qwiz_dataset_questions_to_db] update_msg: ' . $update_msg);
   }
}
function qwiz_find_deleted_dataset_questions ($permalink, $dataset_ids) {
   global $debug, $secure_server_loc;
   $body = array ('page_url'           => json_encode ($permalink),
                  'ids_only_f'         => 1
                 );
   $url = $secure_server_loc . '/get_dataset_questions_v3.php';
   if ($debug[5]) {
      error_log ('[qwiz_find_deleted_dataset_questions] $url: ' . $url);
   }
   $http_request = new WP_Http;
   $result = $http_request->request ($url, array ('method' => 'POST',
                                                  'timeout' => 10,    // Seconds.
                                                  'body'   => $body));
   if ($debug[5]) {
      error_log ('[qwiz_find_deleted_dataset_questions] gettype (result): ' . gettype ($result));
      if (! is_wp_error ($result)) {
         error_log ('[qwiz_find_deleted_dataset_questions] $result: ' . print_r ($result, true));
      }
   }
   if (is_wp_error ($result)) {
      error_log ('[qwiz_find_deleted_dataset_questions] $result->get_error_messages (): ' . print_r ($result, true));
      return array ();
   }
   $data_array = json_decode ($result['body']);
   if ($debug[5]) {
      error_log ('[qwiz_find_deleted_dataset_questions] $data_array: ' . print_r ($data_array, true));
   }
   $db_dataset_ids = $data_array->question_ids;
   $dataset_ids_to_blank = array ();
   foreach ($db_dataset_ids as $db_dataset_id) {
      if (! in_array ($db_dataset_id, $dataset_ids)) {
         $dataset_ids_to_blank[] = $db_dataset_id;
      }
   }
   if ($debug[0]) {
      error_log ('[qwiz_find_deleted_dataset_questions] $dataset_ids_to_blank: ' . print_r ($dataset_ids_to_blank, true));
   }
   return $dataset_ids_to_blank;
}
function qwiz_qdeck_unit_counts_to_db ($qrecord_ids, $datasets, $qrecord_id_n_questions) {
   global $debug, $secure_server_loc;
   $body = array ('qrecord_ids'            => json_encode ($qrecord_ids),
                  'datasets'               => json_encode ($datasets),
                  'qrecord_id_n_questions' => json_encode ($qrecord_id_n_questions)
                 );
   $url = $secure_server_loc . '/update_qwiz_qdeck_unit_counts.php';
   $http_request = new WP_Http;
   $result = $http_request->request ($url, array ('method' => 'POST',
                                                  'body'   => $body));
   if ($debug[0] || $debug[5]) {
      error_log ('[qwiz_qdeck_unit_counts_to_db] result: ' . print_r ($result, true));
   }
}
/*
function qwizcards_block_script() {
	$blockPath = '/dist/block.js';
	$stylePath = '/dist/block.css';
	wp_enqueue_script(
		'dk-gutenberg-block-js',
		plugins_url( $blockPath, __FILE__ ),
                [ 'wp-i18n',          // Internationalization.
                  'wp-blocks',        // For registerBlockType ().
                  'wp-editor',        // Various block components.
                  'wp-components',
                  'wp-edit-post',
                  'wp-element',
                  'wp-data',
                  'wp-plugins',
                  'wp-api'
                ],
		filemtime( plugin_dir_path(__FILE__) . $blockPath )
	);
	wp_enqueue_style(
		'dk-block-css',
		plugins_url( $stylePath, __FILE__ ),
		'',
		filemtime( plugin_dir_path(__FILE__) . $stylePath )
	);
}
add_action( 'enqueue_block_assets', 'qwizcards_block_script' );
add_action( 'plugins_loaded', function () {
	if ( function_exists( 'register_block_type' ) ) {
		register_block_type(
			'dk-gutenberg/click-to-tweet', array(
				'render_callback' => 'hello_gutenberg_block_callback',
				'attributes'	  => array(
					'tweet'	 => array(
						'type' => 'string',
					),
					'tweetsent' => array(
						'type' => 'string',
					),
					'button'	=> array(
						'type'	=> 'string',
						'default' => 'Tweet',
					),
					'theme'	 => array(
						'type'	=> 'boolean',
						'default' => false,
					),
				),
			)
		);
	}
} );
 */
include "qwiz_admin.php";
