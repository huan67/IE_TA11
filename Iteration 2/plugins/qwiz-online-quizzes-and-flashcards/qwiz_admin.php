<?php
function qwiz_admin () {
   add_options_page ('Qwizcards', 'Qwizcards', 'manage_options',
                     'qwiz-online-quizzes-and-flashcards-admin', 'qwiz_options');
}
function qwiz_options () {
   if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
   }
   print '<div class="wrap">' . "\n";
   print    '<h2>Qwizcards options</h2>' . "\n";
   print    '<form action="options.php" method="post">' . "\n";
                settings_fields ('qwiz_options_group');
                do_settings_sections ('qwiz-options-page');
   print       '<input name="Submit" type="submit" value="Save changes" />' . "\n";
   print    '</form>';
   print '</div>';
}
function qwiz_admin_init () {
   register_setting ( 'qwiz_options_group', 'qwiz_options', 'qwiz_options_validate' );
   add_settings_section ('qwiz-go_mobile-section', 'Mobile-device full-screen option/icon',
                         'go_mobile_text', 'qwiz-options-page');
   add_settings_field ('qwiz-go_mobile-field', 'Enable/disable full-screen',
                       'go_mobile_field_input', 'qwiz-options-page',
                       'qwiz-go_mobile-section');
   add_settings_section ('qwiz-icon_qwiz-section', 'Qwizcards icon/link',
                         'icon_qwiz_text', 'qwiz-options-page');
   add_settings_field ('qwiz-icon_qwiz-field', 'Display Qwizcards icon/link',
                       'icon_qwiz_field_input', 'qwiz-options-page',
                       'qwiz-icon_qwiz-section');
   add_settings_section ('qwiz-use_dict-section', 'Free-form input options',
                         'free_form_input_text', 'qwiz-options-page');
   add_settings_field ('qwiz-use_dict-field', 'Default for use_dict',
                       'qwiz_use_dict_field_input', 'qwiz-options-page',
                       'qwiz-use_dict-section');
   add_settings_section ('qwiz-use_terms-section', '',
                         '', 'qwiz-options-page');
   add_settings_field ('qwiz-use_terms-field', 'Default for use_terms',
                       'qwiz_use_terms_field_input', 'qwiz-options-page',
                       'qwiz-use_terms-section');
   add_settings_section ('qwiz-hint_timeout-section', '',
                         '', 'qwiz-options-page');
   add_settings_field ('qwiz-hint_timeout-field', 'Seconds till hint shown',
                       'qwiz_hint_timeout_field_input', 'qwiz-options-page',
                       'qwiz-hint_timeout-section');
   $title =   '<input name="Submit" type="submit" value="Save changes" />'
            . '<br /><br />'
            . 'Hangman hint options';
   add_settings_section ('qwiz-hangman_hints-section', $title,
                         'hangman_hints_text', 'qwiz-options-page');
   add_settings_field ('qwiz-hangman_hints-field', 'Default for number of hints',
                       'qwiz_hangman_hints_field_input', 'qwiz-options-page',
                       'qwiz-hangman_hints-section');
   $title =   '<input name="Submit" type="submit" value="Save changes" />'
            . '<br /><br />'
            . 'Customize button labels, etc.';
   add_settings_section ('qwiz-translate_strings-section', $title,
                         'qwiz_translate_strings_text', 'qwiz-options-page');
   $title = 'Current string; new string (semicolon-separated pair each line)';
   add_settings_field ('qwiz-translate_strings-field', $title,
                       'qwiz_translate_strings_field_input', 'qwiz-options-page',
                       'qwiz-translate_strings-section');
   add_settings_section ('qwiz-manual_syntax_check-section', 'Disable automatic shortcode syntax check and dataset question save on Publish/Update',
                         'qwiz_manual_syntax_check_text', 'qwiz-options-page');
   add_settings_field ('qwiz-manual_syntax_check-field', 'Manual-only check/save',
                       'qwiz_manual_syntax_check_field_input', 'qwiz-options-page',
                       'qwiz-manual_syntax_check-section');
   add_settings_section ('qwiz-regular_page_error_check-section', 'Check quiz/deck shortcode syntax on every page/post',
                         'qwiz_regular_page_error_check_text', 'qwiz-options-page');
   add_settings_field ('qwiz-regular_page_error_check-field', 'Do syntax check every page load',
                       'qwiz_regular_page_error_check_field_input', 'qwiz-options-page',
                       'qwiz-regular_page_error_check-section');
   add_settings_section ('qwiz-load_jquery-section', 'Force jQuery load',
                         'qwiz_load_jquery_text', 'qwiz-options-page');
   add_settings_field ('qwiz-load_jquery-field', 'Load jQuery library',
                       'qwiz_load_jquery_field_input', 'qwiz-options-page',
                       'qwiz-load_jquery-section');
   add_settings_section ('qwiz-load_jquery-migrate-section', 'Force jquery-migrate load',
                         'qwiz_load_jquery_migrate_text', 'qwiz-options-page');
   add_settings_field ('qwiz-load_jquery-migrate-field', 'Load jquery-migrate library',
                       'qwiz_load_jquery_migrate_field_input', 'qwiz-options-page', 
                       'qwiz-load_jquery-migrate-section');
   $title = '<input name="Submit" type="submit" value="Save changes" />'
            . '<br /><br />'
            . 'HTML element that contains quiz and flashcard content (shortcodes, etc.)';
   add_settings_section ('qwiz-content-section', $title,
                         'qwiz_content_text', 'qwiz-options-page');
   add_settings_field ('qwiz-content-field', 'Qwizcards-content<br />HTML element(s)',
                       'qwiz_content_field_input', 'qwiz-options-page',
                       'qwiz-content-section');
   /*
   add_settings_section ('qwiz-use_beta-section',
                         'Test or deploy beta version of Qwizcards plugin',
                         'qwiz_use_beta_text', 'qwiz-options-page');
   add_settings_field ('qwiz-use_beta-field', 'Test or deploy beta, or use installed release',
                       'qwiz_use_beta_field_input', 'qwiz-options-page',
                       'qwiz-use_beta-section');
   add_settings_section ('qwiz-download_beta-section',
                         'Download beta version of Qwizcards plugin',
                         '', 'qwiz-options-page');
   add_settings_field ('qwiz-download_beta-field', 'Download',
                       'qwiz_download_beta_field_input', 'qwiz-options-page',
                       'qwiz-download_beta-section');
   */
   add_settings_section ('qwiz-revert-section',
                         'Use current beta version or revert to an earlier version',
                         'qwiz_revert_text', 'qwiz-options-page');
   add_settings_field ('qwiz-revert-field', 'Version',
                       'qwiz_revert_field_input', 'qwiz-options-page',
                       'qwiz-revert-section');
}
function qwiz_options_validate ($options) {
   global $debug;
   $options['go_mobile'] = $options['go_mobile_select'];
   if ($debug[0]) {
      error_log ('[qwiz_options_validate] $options[\'go_mobile_select\']: ' . $options['go_mobile_select']);
      error_log ('[qwiz_options_validate] $options[\'go_mobile\']: ' . $options['go_mobile']);
   }
   $new_icon_qwiz = trim ($options['icon_qwiz']);
   if (   $new_icon_qwiz != 'Icon and link'
       && $new_icon_qwiz != 'Icon only'
       && $new_icon_qwiz != 'Not displayed') {
      $new_icon_qwiz = 'Icon and link';
   }
   $options['icon_qwiz'] = $new_icon_qwiz;
   $options['use_dict'] = $options['use_dict_select'];
   $options['use_terms'] = $options['use_terms_select'];
   $options['hint_timeout_sec'] = $options['hint_timeout_sec_select'];
   $options['hangman_hints'] = $options['hangman_hints_select'];
   $translate_strings = '';
   if (isset ($options['translate_strings'])) {
      $translate_strings = trim ($options['translate_strings']);
   }
   $new_translate_strings = array ();
   if ($translate_strings != '') {
      $qwiz_T = array ();
      include qwiz_beta_subdir () . 'languages/strings_to_translate.php';
      $lines = explode ("\n", $translate_strings);
      $n_lines = count ($lines);
      for ($i=0; $i<$n_lines; $i++) {
         $line = trim ($lines[$i]);
         if ($line == '') {
            continue;
         }
         $strings = explode (';', $line);
         if (count ($strings) != 2) {
            add_settings_error ('qwiz-translate_strings-section', 'qwiz-translate_strings-errmsg1',
                                'Custom labels line' . ($i + 1) . ': didn\'t get two strings separated by semi-colon.');
            $new_translate_strings[] = $line;
         } else {
            $old_string = trim ($strings[0]);
            $new_string = trim ($strings[1]);
            if (strlen ($old_string) == 0 || strlen ($new_string) == 0 ) {
               add_settings_error ('qwiz-translate_strings-section', 'qwiz-translate_strings-errmsg2',
                                   'Custom labels line' . ($i + 1) . ': null string before or after semicolon not allowed.');
               $new_translate_strings[] = $old_string . '; ' . $new_string;
            } else {
               if ($old_string != 'Check answer' && ! array_key_exists ($old_string, $qwiz_T)) {
                  add_settings_error ('qwiz-translate_strings-section', 'qwiz-translate_strings-errmsg3',
                                      'Custom labels line' . ($i + 1) . ': could not find current string ("' . $old_string . '") in list of strings.  Either incorrectly entered or missing from list (see languages/strings_to_translate.php in the Qwizcards plugin directory).');
               }
               $new_translate_strings[] = $old_string . '; ' . $new_string;
            }
         }
      }
   }
   $options['translate_strings'] = implode ("\n", $new_translate_strings);
   if (isset ($options['regular_page_error_check'])) {
      $options['regular_page_error_check'] = 1;
   } else {
      $options['regular_page_error_check'] = '';
   }
   if (isset ($options['qwiz_syntax_check_manual_only'])) {
      $options['qwiz_syntax_check_manual_only'] = 1;
   } else {
      $options['qwiz_syntax_check_manual_only'] = '';
   }
   if (isset ($options['force_jquery_load'])) {
      $options['force_jquery_load'] = 1;
   } else {
      $options['force_jquery_load'] = 0;
   }
   if (isset ($options['force_jquery_migrate_load'])) {
      $options['force_jquery_migrate_load'] = 1;
   } else {
      $options['force_jquery_migrate_load'] = 0;
   }
   $new_content = trim ($options['content']);
   if ($new_content == '') {
      $new_content = 'div.entry-content, div.post-entry, div.container';
      add_settings_error ('qwiz-content-section', 'qwiz-content-errmsg',
                          'All-blank Qwiz-content HTML element(s) not allowed.  Resetting to default...');
   }
   $options['content'] = $new_content;
   $qwiz_beta = $options['qwiz_beta'];
   if ($qwiz_beta == 'test_beta') {
      qwiz_start_session ();
      $_SESSION['qwiz_beta'] = 1;
      $options['deploy_beta'] = '';
   } else {
      if ($qwiz_beta == 'deploy_beta') {
         $options['deploy_beta'] =  1;
      } else {
         $options['deploy_beta'] = '';
      }
   }
   $options['qwiz_beta'] = '';
   if (isset ($options['qwiz_download_beta'])) {
      $qwiz_download_beta = $options['qwiz_download_beta'];
      if ($qwiz_download_beta) {
         qwiz_download_unzip_beta ();
      }
   }
   $options['qwiz_download_beta'] = '';
   $qwiz_revert_version = $options['qwiz_revert_version'];
   if ($qwiz_revert_version) {
      qwiz_download_unzip_version ($qwiz_revert_version);
   }
   $options['qwiz_revert_version'] = '';
   return $options;
}
function qwiz_download_unzip_beta () {
   $beta_zip_file = 'https://downloads.wordpress.org/plugin/qwiz-online-quizzes-and-flashcards.0.00.zip';
   $get_response = file_get_contents ($beta_zip_file);
   if ($get_response === false) {
      add_settings_error ('qwiz-download_beta-section', 'qwiz-download_beta-errmsg1',
                          "Unable to download beta zip file: $beta_zip_file");
   } else {
      $plugin_dir = plugin_dir_path (__FILE__);
      $beta_zip_filepath = $plugin_dir . 'beta.zip';
      $put_response = file_put_contents ($beta_zip_filepath, print_r ($get_response, true));
      if ($put_response === false) {
         add_settings_error ('qwiz-download_beta-section', 'qwiz-download_beta-errmsg3',
                             "Unable to write beta zip file: $beta_zip_filepath");
      }
      umask (0022);
      $zip = new ZipArchive;
      if ($zip->open ($beta_zip_filepath) === true) {
         $beta_dir = $plugin_dir . 'beta';
         if (file_exists ($beta_dir)) {
            if (file_exists ($plugin_dir . BETA_SUBDIR)) {
               qwiz_rrm ($plugin_dir . BETA_SUBDIR);
            }
         } else {
            wp_mkdir_p ($beta_dir);
         }
         if ($zip->extractTo ($beta_dir) !== true) {
            add_settings_error ('qwiz-download_beta-section', 'qwiz-download_beta-errmsg4',
                                "Unable to extract files from: $beta_zip_filepath to: $beta_dir..");
         }
         $zip->close ();
      } else {
         add_settings_error ('qwiz-download_beta-section', 'qwiz-download_beta-errmsg2',
                             "Unable to open beta zip file: $beta_zip_filepath");
      }
      unlink ($beta_zip_filepath);
   }
}
function qwiz_download_unzip_version ($qwiz_revert_version) {
   $version_zip_file = "https://downloads.wordpress.org/plugin/qwiz-online-quizzes-and-flashcards.$qwiz_revert_version.zip";
   $get_response = file_get_contents ($version_zip_file);
   if ($get_response === false) {
      $errmsg = "Unable to download zip file: $version_zip_file";
      if (preg_match ('/^[0-9]\.[0-9][0-9]$/', $qwiz_revert_version) == 0) {
         $errmsg .= ". Note: version number (\"$qwiz_revert_version\") not in standard form (\"n.nn\")";
      }
      add_settings_error ('qwiz-revert-section', 'qwiz-revert-errmsg1', $errmsg);
   } else {
      $plugin_dir = plugin_dir_path (__FILE__);
      $version_zip_filepath = $plugin_dir . "$qwiz_revert_version.zip";
      $put_response = file_put_contents ($version_zip_filepath, print_r ($get_response, true));
      if ($put_response === false) {
         add_settings_error ('qwiz-revert-section', 'qwiz-revert-errmsg3',
                             "Unable to write zip file: $version_zip_filepath");
      }
      umask (0022);
      $zip = new ZipArchive;
      if ($zip->open ($version_zip_filepath) === true) {
         if ($zip->extractTo ($plugin_dir) === true) {
            $version_dir = $plugin_dir . PLUGIN_DIR;
            qwiz_cp_R ($version_dir, $plugin_dir);
            qwiz_rrm ($version_dir);
         } else {
            add_settings_error ('qwiz-revert-section', 'qwiz-revert-errmsg4',
                                "Unable to extract files from: $version_zip_filepath to: $version_dir");
         }
         $zip->close ();
      } else {
         add_settings_error ('qwiz-revert-section', 'qwiz-revert-errmsg2',
                             "Unable to open zip file: $version_zip_filepath");
      }
      unlink ($version_zip_filepath);
   }
}
function qwiz_rrm ($dir) {
   $files = array_diff (scandir($dir), array('.','..'));
   foreach ($files as $file) {
      if (is_dir ("$dir/$file") && ! is_link ($dir)) {
         qwiz_rrm ("$dir/$file");
      } else {
         unlink ("$dir/$file");
      }
   }
   return rmdir ($dir);
}
function qwiz_cp_R ($source_dir, $dest_dir) {
   @mkdir ("$dest_dir", 0777, true);
   $files = array_diff (scandir($source_dir), array('.','..'));
   foreach ($files as $file) {
      if (is_dir ("$source_dir/$file")) {
         qwiz_cp_R ("$source_dir/$file", "$dest_dir/$file");
      } else {
         copy ("$source_dir/$file", "$dest_dir/$file");
      }
   }
   return '';
}
function go_mobile_text () {
   print '<p>';
   print 'On small-screen devices users see a full-screen view of a quiz or ';
   print 'flashcard deck once the user starts that quiz or deck. ';
   print 'Also, an icon allows the user to switch the full-screen view off and ';
   print 'on. You can disable the full-screen views and icon here.';
   print '</p>';
}
function go_mobile_field_input () {
   global $options, $debug;
   $go_mobile = 'true';
   if (isset ($options['go_mobile'])) {
      $go_mobile = $options['go_mobile'];
      if ($debug[0]) {
         error_log ('[go_mobile_field_input] $go_mobile: X' . $go_mobile . 'X');
      }
   }
   print '<table border="0">';
   print    '<tr>';
   print       '<td style="padding: 0; width: 120px;">';
   print          '<select id="qwiz_go_mobile_qwiz" name="qwiz_options[go_mobile_select]">' . "\n";
   $selected = $go_mobile == 'Enabled' ? 'selected' : '';
   print             "<option $selected>";
   print                "Enabled";
   print             "</option>\n";
   $selected = $go_mobile == 'Disabled' ? 'selected' : '';
   print             "<option $selected>";
   print                "Disabled";
   print             "</option>\n";
   print          "</select>\n";
   print       '</td>';
   print       '<td style="padding: 0; font-size: 13px;">';
   print          'Whether full-screen views will be shown on small-screen devices.';
   print       '</td>';
   print    '</tr>';
   print '</table>';
}
function icon_qwiz_text () {
   print '<p>';
   print 'The Qwizcards icon appears on the first or introductory card/page ';
   print 'of a quiz or a flashcard deck.  It provides a link to the Qwizcards ';
   print 'email, support@qwizcards.com. ';
   print '</p>';
}
function icon_qwiz_field_input () {
   global $options;
   $icon_qwiz = $options['icon_qwiz'];
   if ($icon_qwiz == '') {
      $icon_qwiz = 'Icon and link';
   }
   print '<select id="qwiz_qwiz_icon_qwiz" name="qwiz_options[icon_qwiz]">' . "\n";
   $select_options = array ('Icon and link', 'Icon only', 'Not displayed');
   $n_select_options = count ($select_options);
   for ($i_opt=0; $i_opt<$n_select_options; $i_opt++) {
      $value = $select_options[$i_opt];
      $selected = $icon_qwiz == $value ? 'selected' : '';
      print    '<option value = "' . $value . '" ' . $selected . ">\n";
      print       $value;
      print    "</option>\n";
   }
   print "</select>\n";
}
function free_form_input_text () {
   print 'Free-form input is specified with the [textentry] shortcode.&nbsp; ';
   print 'Users type a few characters and pick words from a suggestion list.&nbsp; ';
   print 'You can specify option default choices here.&nbsp; ';
   print 'You can also specify whether the dictionary and/or terms list will be used for any particular quiz question or flashcard by entering something like [textentry use_dict="false" use_terms="true"]';
}
function qwiz_use_dict_field_input () {
   global $options;
   $use_dict = 'true';
   if (isset ($options['use_dict'])) {
      $use_dict = $options['use_dict'];
   }
   print '<table border="0">';
   print    '<tr>';
   print       '<td style="padding: 0; width: 120px;">';
   print          '<select id="qwiz_use_dict_qwiz" name="qwiz_options[use_dict_select]">' . "\n";
   $selected = $use_dict == 'true' ? 'selected' : '';
   print             "<option $selected>";
   print                "true";
   print             "</option>\n";
   $selected = $use_dict == 'false' ? 'selected' : '';
   print             "<option $selected>";
   print                "false";
   print             "</option>\n";
   print          "</select>\n";
   print       '</td>';
   print       '<td style="padding: 0; font-size: 13px;">';
   print          'Whether an English-language dictionary will be used for suggestion words.';
   print       '</td>';
   print    '</tr>';
   print '</table>';
}
function qwiz_use_terms_field_input () {
   global $options;
   $use_terms = 'true';
   if (isset ($options['use_terms'])) {
      $use_terms = $options['use_terms'];
   }
   print '<table border="0">';
   print    '<tr>';
   print       '<td style="padding: 0; width: 120px;">';
   print          '<select id="qwiz_use_terms_qwiz" name="qwiz_options[use_terms_select]">' . "\n";
   $selected = $use_terms == 'true' ? 'selected' : '';
   print             "<option $selected>";
   print                "true";
   print             "</option>\n";
   $selected = $use_terms == 'false' ? 'selected' : '';
   print             "<option $selected>";
   print                "false";
   print             "</option>\n";
   print          "</select>\n";
   print       '</td>';
   print       '<td style="padding: 0; font-size: 13px;">';
   print          'Whether a list of biology terms will be used for suggestion words.';
   print       '</td>';
   print    '</tr>';
   print '</table>';
}
function qwiz_hint_timeout_field_input () {
   global $options;
   $hint_timeout_sec = 20;
   if (isset ($options['hint_timeout_sec'])) {
      $hint_timeout_sec = $options['hint_timeout_sec'];
      if ($hint_timeout_sec == '') {
         $hint_timeout_sec = 20;
      }
   }
   print '<table border="0">';
   print    '<tr>';
   print       '<td style="padding: 0; width: 120px;">';
   print          '<select id="qwiz_hint_timeout_qwiz" name="qwiz_options[hint_timeout_sec_select]">' . "\n";
   $select_displays = array ('Never', 'Always show', 2, 3, 5, 10, 15, 20, 30, 60);
   $select_values   = array (     -1,             0, 2, 3, 5, 10, 15, 20, 30, 60);
   $n_select_options = count ($select_displays);
   for ($i_opt=0; $i_opt<$n_select_options; $i_opt++) {
      $display = $select_displays[$i_opt];
      $value   = $select_values[$i_opt];
      $selected = $hint_timeout_sec == $value ? 'selected' : '';
      print          '<option value="' . $value . '" ' . $selected . ">\n";
      print             $display;
      print          "</option>\n";
   }
   print           "</select>\n";
   print       '</td>';
   print       '<td style="padding: 0; font-size: 13px;">';
   print          'How long before a &ldquo;Hint&rdquo; button is shown if the user does nothing.&nbsp; ';
   print          '(A hint will be shown in any case if the user types a few &ldquo;incorrect&rdquo; letters.)';
   print       '</td>';
   print    '</tr>';
   print '</table>';
}
function hangman_hints_text () {
   print 'A &ldquo;Hint&rdquo; button is normally shown with [hangman] input, and two hint letters may be requested.&nbsp; ';
   print 'You can change this default.&nbsp; ';
   print 'You can eliminate the hint button, or specify how many hints will be given.&nbsp; ';
   print 'You can also customize individual quizzes and flashcard decks by adding a "hint=" attribute to the [hangman] shortcode, like this: [hangman hints=3]';
}
function qwiz_hangman_hints_field_input () {
   global $options;
   $hangman_hints = 2;
   if (isset ($options['hangman_hints'])) {
      $hangman_hints = $options['hangman_hints'];
      if ($hangman_hints == '') {
         $hangman_hints = 2;
      }
   }
   print '<table border="0">';
   print    '<tr>';
   print       '<td style="padding: 0; width: 120px;">';
   print          '<select id="qwiz_hangman_hints" name="qwiz_options[hangman_hints_select]">' . "\n";
   $select_displays = array ('No hint button', 1, 2, 3, 4, 5, 6, 7, 8, 9, 'No limit');
   $select_values   = array (               0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 100);
   $n_select_options = count ($select_displays);
   for ($i_opt=0; $i_opt<$n_select_options; $i_opt++) {
      $display = $select_displays[$i_opt];
      $value   = $select_values[$i_opt];
      $selected = $hangman_hints == $value ? 'selected' : '';
      print          '<option value="' . $value . '" ' . $selected . ">\n";
      print             $display;
      print          "</option>\n";
   }
   print           "</select>\n";
   print       '</td>';
   print       '<td style="padding: 0; font-size: 13px;">';
   print          'The maximum number of hint letters that will be provided.';
   print       '</td>';
   print    '</tr>';
   print '</table>';
}
function qwiz_translate_strings_text () {
   print '<p>';
   print 'You can change the labels that are currently displayed on buttons or ';
   print 'in headers (or just about anywhere else for that matter).';
   print 'Enter the current text string you want to change, followed by a ';
   print 'semicolon, and then the replacement text. ';
   print 'Enter each such replacement on a separate line. ';
   print '</p>';
   print '<p>';
   print 'Note: the "current text string" must be entered in its entirety, ';
   print 'exactly as it currently appears.  Thus, "Got it!" must be entered ';
   print 'with the uppercase G and the exclamation point. ';
   print '</p>';
   print '<p>';
   print 'Note also: while one could translate the plugin to another language ';
   print 'in this manner, there actually is a standard WordPress ';
   print '"internationalization" method; see the languages directory in the ';
   print 'Qwizcards plugin directory.';
   print '</p>';
   print '<p>';
   print 'Example: to replace the Flashcard button "Need more practice" ';
   print 'with "Try card again later", enter<br />';
   print '&emsp;&emsp; Need more practice; Try card again later ';
   print '</p>';
}
function qwiz_translate_strings_field_input () {
   global $options;
   $translate_strings = '';
   if (isset ($options['translate_strings'])) {
      $translate_strings = $options['translate_strings'];
   }
   print '<textarea id="qwiz_translate_strings" ';
   print '          name="qwiz_options[translate_strings]" ';
   print '          wrap="off" ';
   print '          style="width: 40rem; height: 5rem;">';
   print $translate_strings;
   print '</textarea>';
}
function qwiz_regular_page_error_check_text () {
   print '<p>';
   print 'You can have Qwizcards perform checks of quiz and flashcard deck shortcodes ';
   print 'on every page load, which may be useful if you have had to disable syntax checks ';
   print 'on Update/Publish in the WordPress editor, or if there is an error particular to a page&rsquo;s published content.&nbsp; ';
   print '</p>';
}
function qwiz_regular_page_error_check_field_input () {
   global $options;
   if (isset ($options['regular_page_error_check'])) {
      $regular_page_error_check = $options['regular_page_error_check'];
   } else {
      $regular_page_error_check = 0;
   }
   $checked = $regular_page_error_check == 1 ? 'checked' : '';
   print '<input id="regular_page_error_check" name="qwiz_options[regular_page_error_check]" '
      .      'type="checkbox" ' . $checked . ' /> ';
   print 'Check this box to perform syntax error checks on each page load' . "\n";
}
function qwiz_manual_syntax_check_text () {
   print '<p>';
   print 'Normally Qwizcards automatically checks for errors and saves dataset ';
   print 'questions when you click &ldquo;Publish&rdquo; or &ldquo;Update&rdquo; ';
   print 'in the WordPress editor.&nbsp; ';
   print 'Sometimes, however, this interferes with the Update/Publish function.&nbsp; ';
   print 'You can disable the automatic-check feature here.&nbsp; ';
   print 'A manual option to check syntax and save dataset questions will be ';
   print 'available in the Qwizcards editing menu pop-up.';
   print '</p>';
}
function qwiz_manual_syntax_check_field_input () {
   global $options;
   if (isset ($options['qwiz_syntax_check_manual_only'])) {
      $qwiz_syntax_check_manual_only = $options['qwiz_syntax_check_manual_only'];
   } else {
      $qwiz_syntax_check_manual_only = 0;
   }
   $checked = $qwiz_syntax_check_manual_only == 1 ? 'checked' : '';
   print '<input id="qwiz_manual_syntax_check" name="qwiz_options[qwiz_syntax_check_manual_only]" '
      .      'type="checkbox" ' . $checked . ' /> ';
   print 'Check this box to disable the automatic syntax check / dataset save' . "\n";
}
function qwiz_load_jquery_text () {
   print '<p>';
   print 'If you get an error message on a page or post saying that the jQuery ';
   print 'JavaScript library is not loaded (which WordPress usually does by ';
   print 'default), you can insure that it is loaded.';
   print '</p>';
}
function qwiz_load_jquery_field_input () {
   global $options;
   if (isset ($options['force_jquery_load'])) {
      $force_jquery_load = $options['force_jquery_load'];
   } else {
      $force_jquery_load = 0;
   }
   $checked = $force_jquery_load == 1 ? 'checked' : '';
   print '<input id="qwiz_jquery_load" name="qwiz_options[force_jquery_load]" '
      .      'type="checkbox" ' . $checked . ' /> ';
   print 'Check this box to ensure that jQuery is loaded' . "\n";
}
function qwiz_load_jquery_migrate_text () {
   print '<p>';
   print 'If Qwizcards &ldquo;free-form input&rdquo; does not show suggestions, ';
   print 'or if in the WordPress editor the &ldquo;Qwizcards editing menu&rdquo; ';
   print 'does not work or is not draggable, this <i>may</i> help.&nbsp ';
   print '(jquery-migrate provides compatibility with older versions of jQuery.)';
   print '</p>';
}
function qwiz_load_jquery_migrate_field_input () {
   global $options;
   if (isset ($options['force_jquery_migrate_load'])) {
      $force_jquery_migrate_load = $options['force_jquery_migrate_load'];
   } else {
      $force_jquery_migrate_load = 0;
   }
   $checked = $force_jquery_migrate_load == 1 ? 'checked' : '';
   print '<input id="qwiz_jquery_migrate_load" name="qwiz_options[force_jquery_migrate_load]" '
      .      'type="checkbox" ' . $checked . ' /> ';
   print 'Check this box to ensure that jquery-migrate is loaded' . "\n";
}
function qwiz_content_text () {
   print '<p>';
   print 'The Qwizcards "content" HTML element identifies the "container" for quiz ';
   print 'and flashcard deck shortcodes, etc.  In WordPress, this is where ';
   print 'page and post content appears.  The default setting includes divs ';
   print 'having class "entry-content", "post-content", or "container". ';
   print 'These vary sometimes depending on theme.  This option lets you ';
   print 'change or add to the default setting.';
   print '</p>';
   print '<p>';
   print 'Note: pages that include excerpts from several pages or posts ';
   print '(including the results of a search) include multiple such HTML ';
   print 'elements, which may contain incomplete quizzes or flashcard decks. ';
   print 'The Qwizcards plugin handles this, but will be confused if it thinks ';
   print 'the excerpts are all part of the same page or post. ';
   print 'So don\'t define "body" to be the Qwizcards-content HTML element!';
   print '</p>';
   print '<p>';
   print 'HTML elements are entered CSS-fashion, such as div.class or div#id. Examples:<br />';
   print '&emsp;&emsp; div#special-container - div element with id="special-container"<br />';
   print '&emsp;&emsp; span.content-span - span element with class="content-span"';
   print '</p>';
}
function qwiz_content_field_input () {
   global $options;
   $content = '';
   if (isset ($options['content'])) {
      $content = $options['content'];
   }
   if ($content == '') {
      $content = 'div.entry-content, div.post-entry';
   }
   print '<input id="qwiz_content" name="qwiz_options[content]" type="text" '
         . 'style="width: 30rem;" value="' . $content . '" />' . "\n";
}
/*
function qwiz_use_beta_text () {
}
function qwiz_use_beta_field_input () {
   global $current_version;
   $beta_plugin_file = plugin_dir_path (__FILE__) . BETA_SUBDIR . PLUGIN_FILE;
   $current_beta_version = '(Unknown)';
   if (file_exists ($beta_plugin_file)) {
      $beta_plugin_data = get_plugin_data ($beta_plugin_file);
      if ($beta_plugin_data['Version']) {
         $current_beta_version = $beta_plugin_data['Version'];
      }
      $current_beta_note = '';
   } else {
      $current_beta_note = '<p><strong>Note: beta version not downloaded.</strong></p>';
   }
   $plugin_file = plugin_dir_path (__FILE__) . PLUGIN_FILE;
   $plugin_data = get_plugin_data ($plugin_file);
   $current_version = $plugin_data['Version'];
   $qwiz_beta = isset ($_SESSION['qwiz_beta']);
   $options = get_option ('qwiz_options');
   $deploy_beta = '';
   if (isset ($options['deploy_beta'])) {
      $deploy_beta = $options['deploy_beta'];
   }
   print '<input id="qwiz_test_beta" name="qwiz_options[qwiz_beta]" type="radio"'
         . 'value="test_beta" ' . ($qwiz_beta ? 'checked' : '') . ' />' . "\n";
   print "Test <strong>$current_beta_version</strong> &emsp; (this login session only)";
   print '<br />';
   print '<input id="qwiz_deploy_beta" name="qwiz_options[qwiz_beta]" type="radio"'
         . 'value="deploy_beta" ' . ($deploy_beta ? 'checked' : '') . ' />' . "\n";
   print "Deploy <strong>$current_beta_version</strong> &emsp; (all users)";
   print '<br />';
   print '<input id="qwiz_use_current_install" name="qwiz_options[qwiz_beta]" type="radio"'
         . 'value="use_current_install" ' . (($deploy_beta || $qwiz_beta) ? '' : 'checked') . ' />' . "\n";
   print "Use current installed release <strong>$current_version</strong> &emsp; (all users)";
   print $current_beta_note;
}
function qwiz_download_beta_field_input () {
   if (! ini_get ('allow_url_fopen')) {
      print  '<span style="font-weight: bold; color: red;">Note: php file downloads not enabled on this system. allow_url_fopen needs to be set in ' . php_ini_loaded_file () . '</span><br />';
   }
   print '<input id="qwiz_download_beta" name="qwiz_options[qwiz_download_beta]" '
         . 'type="checkbox" />' . "\n";
   print 'Check this box to do download when click "Save changes"';
}
 */
function qwiz_revert_text () {
   print 'Install current beta version, or switch back to an earlier version of the Qwizcards plugin';
}
function qwiz_revert_field_input () {
   global $current_version;
   $plugin_file = plugin_dir_path (__FILE__) . PLUGIN_FILE;
   $plugin_data = get_plugin_data ($plugin_file);
   $current_version = $plugin_data['Version'];
   if (! ini_get ('allow_url_fopen')) {
      print  '<span style="font-weight: bold; color: red;">Note: php file downloads not enabled on this system. allow_url_fopen needs to be set in ' . php_ini_loaded_file () . '</span><br />';
   }
   print '<table border="0">';
   print '   <tr>';
   print '      <td style="padding: 0px;">';
   print '         <input id="qwiz_revert" name="qwiz_options[qwiz_revert_version]" type="text" style="width: 5rem;" />';
   print '      </td>';
   print '      <td style="padding: 0px;">';
   print '         Input version number (n.nn) to download when click "Save changes".&nbsp; The current beta version can be installed with 0.00';
   print '         &emsp; ';
   print "         (Leave blank to keep current version = $current_version.)";
   print '      </td>';
   print '   </tr>';
   print '</table>';
}
function qwiz_start_session () {
   if (! session_id ()) {
      session_start ();
   }
}
function qwiz_end_session () {
   session_destroy ();
}
add_action ('admin_menu', 'qwiz_admin');
add_action ('admin_init', 'qwiz_admin_init');
