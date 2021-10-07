if (! window.console) {
   window.console = {log: function(){} };
}
qwiz_ = {};
var qwizf = function () {
var qname = 'qwiz_';
var debug = [];
debug.push (false);    // 0 - general.
debug.push (false);    // 1 - radio/choices html.
debug.push (false);    // 2 - feedback html.
debug.push (false);    // 3 - old/new html dump.
debug.push (false);    // 4 - question tags/topics.
debug.push (false);    // 5 - unused.
debug.push (false);    // 6 - [textentry] / autocomplete.
debug.push (false);    // 7 - Enter -> click.
debug.push (false);    // 8 - Drag and drop.
debug.push (false);    // 9 - [hangman].
debug.push (false);    // 10 - unused.
debug.push (false);    // 11 - use_dataset.
debug.push (false);    // 12 - QWizard.
var $ = jQuery;
this.no_intro_b = [];
this.qwizard_b = false;
this.processing_complete_b = false;
this.qrecord_b = false;
this.dataset_b = false;
this.preview = false;
this.any_pay_quiz_f = false;
var q = this;
var qqc;
var content;
var default_use_dict;
var default_use_terms;
var hint_timeout_sec;
var default_hangman_max_hints;
var post_id;
var correct;
var incorrect;
var errmsgs = [];
var n_qwizzes = 0;
var qwizzled_b;
var qwizcards_page_f = false;
var qwizdata = [];
var header_html;
var drag_and_drop_initialized_b = false;
var try_again_obj = '';
var $label_clicked = [];
var $prev_label_clicked = [];
var ignore_label_click_b = false;
var next_button_active_b = false;
var textentry_b = false;
var loaded_metaphone_js_b = false;
var default_textentry_terms_metaphones;
var current_question_textentry_terms_metaphones = {};
var textentry_answers = {};
var textentry_answer_metaphones = {};
var textentry_matches = {};
var lc_textentry_matches = {};
var textentry_i_qwiz;
var suppress_hangman_hint_b = false;
var Tcheck_answer_message;
var show_hint_timeout = {};
var panel_exit_mobile_open_b = false;
var panel_exit_mobile_just_closed_b;
var non_mobile_scrollLeft;
var non_mobile_scrollTop;
var qw;
var set_qwizard_data_b = false;
var preview_mode = 'questions_active';
this.wordpress_page_f = false;
$ (document).ready (function () {
   qqc = qwiz_qcards_common;
   var preview_i_qwiz = $.cookie ('qwiz_preview');
   if (typeof preview_i_qwiz != 'undefined') {
      q.preview_i_qwiz_plus1 = parseInt (preview_i_qwiz, 10) + 1;
      $.removeCookie ('qwiz_preview', {path: '/'});
   }
   if (debug[0]) {
      console.log ('[qwiz.js > document ready] typeof (document_qwizard_user_page):', typeof (document_qwizard_user_page));
      console.log ('[qwiz.js > document ready] q.preview_i_qwiz_plus1:', q.preview_i_qwiz_plus1);
   }
   correct = [T ('Good!'), T ('Correct!'), T ('Excellent!'), T ('Great!')];
   incorrect = [T ('No.'), T ('No, that\'s not correct.'), T ('Sorry, that\'s not correct.')];
   var page_url = document.location.href;
   qwizcards_page_f =    page_url.indexOf ('qwizcards.com/u/') != -1
                      || page_url.indexOf ('qwizcards.com/admin/') != -1
                      || page_url.indexOf ('localhost/u/') != -1
                      || page_url.indexOf ('localhost/admin/') != -1
                      || page_url.indexOf ('192.168.1.120/u/') != -1
                      || page_url.indexOf ('192.168.1.120/admin/') != -1;
   if (typeof (document_qwizard_user_page) == 'undefined'
               && window.location.href.indexOf ('action=edit') == -1
                          && window.location.href.indexOf ('post-new') == -1) {
      q.qwiz_init ();
   }
});
this.qwiz_init = function (skip_error_check_f, only_get_qwiz_param_f) {
   content                   = qqc.get_qwiz_param ('content', 'body');
   default_use_dict          = qqc.get_qwiz_param ('use_dict', 'true');
   default_use_terms         = qqc.get_qwiz_param ('use_terms', 'true');
   default_hangman_max_hints = parseInt (qqc.get_qwiz_param ('hangman_hints', 2), 10);
   hint_timeout_sec          = qqc.get_qwiz_param ('hint_timeout_sec', 20);
   post_id                   = qqc.get_qwiz_param ('post_id', 0);
   q.qwizcards_version       = qqc.get_qwiz_param ('qwizcards_version', '');
   document_qwiz_mobile_enabled = qqc.get_qwiz_param ('mobile_enabled', 'Enabled') == 'Enabled';
   Tcheck_answer_message = T ('Need help?  Try the "hint" button');
   if (only_get_qwiz_param_f) {
      return;
   }
   qqc.set_force_mobile ();
   process_html ();
   if (errmsgs.length && ! skip_error_check_f) {
      if (! q.qwizard_b) {
         if (qqc.get_qwiz_param ('regular_page_error_check')) {
            alert (Tplural ('Error found', 'Errors found', errmsgs.length) + ':\n\n' + errmsgs.join ('\n'));
         } else {
            console.log ('Errors found:\n', errmsgs.join ('\n'));
         }
      }
   }
   if (document_qwiz_force_mobile_f) {
      q.go_mobile (0);
   }
   if (n_qwizzes) {
      if (qqc.is_mobile () && ! document_qwiz_force_mobile_f) {
         $ ('.go-mobile-qwiz').show ();
      }
      for (var i_qwiz=0; i_qwiz<n_qwizzes; i_qwiz++) {
         if (qwizdata[i_qwiz].questions) {
            if (qwizdata[i_qwiz].qrecord_id) {
               qwizdata[i_qwiz].record_start_b = true;
            }
            if (q.no_intro_b[i_qwiz] || qwizdata[i_qwiz].n_questions == 1
                                                    || q.preview_i_qwiz_plus1) {
               q.next_question (i_qwiz);
            } else {
               if (! qwizdata[i_qwiz].display_pay_screen) {
                  $ ('div.intro-qwiz' + i_qwiz).show ();
                  $ ('div#next_button-qwiz' + i_qwiz).show ();
               }
            }
         }
      }
      if (! q.$dialog_no_credit) {
         $ ('body').append (dialog_no_credit_html ());
         q.$usernames_is_are = $ ('#qwiz_usernames_is_are');
         q.$dialog_no_credit = $ ('#qwiz_dialog_no_credit').dialog ({
            height:        300,
            width:         550,
            modal:         true,
            autoOpen:      false,
            buttons:       {'Close':   function () {
                                          q.$dialog_no_credit.dialog ('close');
                                       }
                           }
         });
      }
      if (q.preview_i_qwiz_plus1) {
         var i_preview_qwiz = q.preview_i_qwiz_plus1 - 1;
         if (! qwizdata[i_preview_qwiz].use_dataset) {
            q.init_preview (i_preview_qwiz);
         }
      } else {
         if (q.preview && ! q.qwizard_b) {
            q.init_preview (0);
         }
      }
   }
}
function process_html () {
   $ ('p:contains("[!]"), :header:contains("[!]")').each (function () {
      var comment_htm = $ (this).html ();
      if (comment_htm.search (/\s*(<.+?>)*\s*\[!+\][^]*?\[\/!+\]\s*(<.+?>)*\s*$/m) == 0) {
         $ (this).remove ();
      }
   });
   $ ('p:contains("qwiz"), :header:contains("qwiz")').each (function () {
      var tag_htm = $ (this).html ();
      if (tag_htm.search (/\s*\[\/{0,1}qwiz[^\]]*\]\s*/m) == 0) {
         $ (this).replaceWith (tag_htm);
      }
   });
   var div_html_selector = '';
   var $qwiz_divs= $ ('div.qwiz_wrapper');
   var $fallback_wrappers = $ ('div.qwiz_wrapper_fallback');
   if ($qwiz_divs.length) {
      div_html_selector = 'div.qwiz_wrapper';
      q.wordpress_page_f = qqc.get_qwiz_param ('wppf', '') == 1;
      $fallback_wrappers.css ({display: 'none'});
   } else {
      if ($fallback_wrappers.length == 0) {
         var style =   '<style type="text/css">\n'
                     +    '.qwiz_wrapper_fallback_visible {\n'
                     +       'visibility: visible;\n'
                     +    '}\n'
                     + '</style>\n';
         $ ('head').append (style);
      }
      div_html_selector = content;
   }
   n_qwizzes = 0;
   var i_qwiz = 0;
   $ (div_html_selector).each (function () {
      var htm = $ (this).html ();
      if (! htm) {
      } else {
         var qwiz_pos = htm.indexOf ('[qwiz');
         if (qwiz_pos != -1) {
            var r = q.process_html2 (htm, i_qwiz);
            htm = r.htm;
            if (q.qwizdemos) {
               var n_qwizdemos = q.qwizdemos.length;
               for (var i_qwizdemo=0; i_qwizdemo< n_qwizdemos; i_qwizdemo++) {
                  var qwizdemo_i = q.qwizdemos[i_qwizdemo];
                  var len = qwizdemo_i.length;
                  qwizdemo_i = qwizdemo_i.substring (10, len - 11);
                  htm = htm.replace ('<qwizdemo></qwizdemo>', qwizdemo_i);
               }
            }
            if (r.replace_body) {
               if (debug[0]) {
                  console.log ('[process_html] document_qwiz_user_logged_in_b:', document_qwiz_user_logged_in_b);
               }
                  var h =  '<div id="preview_header">'
                         +    '<h2 style="margin-bottom: 10px;">Preview quiz</h2>'
                         +    '<a href="javascript: void (0)" style="float: right; margin-right: 50px;" onclick="' + qname + '.reset_preview (' + i_qwiz + ')">Reload preview</a>'
                         +    '<label>'
                         +       '<input type="radio" name="preview_radio" value="questions_active"          onclick="' + qname + '.change_preview_mode (this, ' + i_qwiz + ')" checked /> '
                         +       'Questions active'
                         +    '</label>'
                         +    '&emsp;'
                         +    '<label>'
                         +       '<input type="radio" name="preview_radio" value="show_answers"              onclick="' + qname + '.change_preview_mode (this, ' + i_qwiz + ')" /> '
                         +       'Show answers'
                         +    '</label>'
                         +    '&emsp;'
                         +    '<label>'
                         +       '<input type="radio" name="preview_radio" value="show_answers_and_feedback" onclick="' + qname + '.change_preview_mode (this, ' + i_qwiz + ')" /> '
                         +       'Show answers and feedback'
                         +    '</label>'
                         +    '&emsp;'
                         + '</div>';
                  $ ('body').html (h + htm);
                  $ ('div#qwiz' + i_qwiz).css ({margin: 'auto'});
            } else {
               $ (this).html (htm);
            }
            if (i_qwiz != r.i_qwiz) {
               i_qwiz = r.i_qwiz;
               $ (this).find ('div.qwiz')
                  .on ('mouseenter',
                       function (e) {
                          if (e.target.tagName.toLowerCase () == 'div'
                                                 && e.target.className == 'qwiz') {
                             document_active_qwiz_qdeck = e.target;
                          } else {
                             var $qwizdiv = $ (e.target).parents ('div.qwiz');
                             if ($qwizdiv.length) {
                                document_active_qwiz_qdeck = $qwizdiv[0];
                             }
                          }
                          if (debug[7]) {
                             console.log ('[qwiz mouseenter] e.target:', e.target);
                             console.log ('[qwiz mouseenter] document_active_qwiz_qdeck:', document_active_qwiz_qdeck);
                          }
                      });
               var ii_qwiz = i_qwiz - 1;
               if (qwizdata[ii_qwiz]) {
                  var n_questions = qwizdata[ii_qwiz].n_questions;
                  for (var i_question=0; i_question<n_questions; i_question++) {
                     if (qwizdata[ii_qwiz].bg_img[i_question]) {
                        var bg_img = qwizdata[ii_qwiz].bg_img[i_question];
                        var img = new Image ();
                        img.src = bg_img.src;
                        img.i_qwiz = ii_qwiz;
                        img.i_question = i_question;
                        img.onload = function () {
                           var w = this.width;
                           var h = this.height;
                           var $qwizq = $ ('#qwiz' + img.i_qwiz + '-q' + img.i_question);
                           if (debug[0]) {
                              console.log ('[process_html] w:', w, ', h:', h, ', $qwizq:', $qwizq);
                           }
                           var min_height;
                           if (bg_img.height) {
                              min_height = bg_img.height;
                           } else if (bg_img.width) {
                              min_height = Math.floor (bg_img.width/w * h);
                           } else {
                              min_height = h;
                           }
                           min_height = '' + min_height + 'px';
                           if (bg_img.top) {
                              min_height = 'calc(' + bg_img.top + 'px + ' + min_height + ')';
                           }
                           $qwizq.css ({'min-height': min_height});
                        }
                     }
                  }
               }
            }
         }
         if ($qwiz_divs.length) {
            $ (this).contents ().unwrap ();
         }
      }
   });
   n_qwizzes = i_qwiz;
   $ ('div.qwiz_wrapper').removeClass ('qwiz_shortcodes_hidden');
   /*
   $ ('button.hangman_hint').tooltip ({tooltipClass:  'qwiz_hint_tooltip',
                                       show:          {delay: 500}
                                      });
                                      */
   $ ('div.hangman_label').each (function () {
                                    var $this = $ (this);
                                    var width = $this.outerWidth ();
                                    $this.outerWidth (1.2 * width);
                                 });
   for (var i_qwiz=0; i_qwiz<n_qwizzes; i_qwiz++) {
      if (qwizdata[i_qwiz].qrecord_id) {
         var n_questions = qwizdata[i_qwiz].n_questions;
         var data = {qwiz_qdeck: 'qwiz', n_questions_cards: n_questions};
         qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'check_registered', data);
      }
   };
   if (n_qwizzes) {
      qqc.init_enter_intercept ();
   }
   if (q.qrecord_b) {
      qqc.set_user_menus_and_icons ();
   }
   if (textentry_b) {
      if (content == 'body' && typeof (qcard_) != 'undefined') {
         var n_tries = 0;
         var run_init_textentry_autocomplete = function () {
            var ok_b = false;
            if (debug[6]) {
               console.log ('[run_init_textentry_autocomplete]', n_tries);
            }
            if (qcard_.processing_complete_b || n_tries > 30) {
               if (debug[6]) {
                  console.log ('[run_init_textentry_autocomplete] OK');
               }
               q.init_textentry_autocomplete ($ ('body'));
               ok_b = true;
            }
            if (! ok_b) {
               setTimeout (run_init_textentry_autocomplete, 100);
               n_tries++;
            }
         }
         run_init_textentry_autocomplete ();
      } else {
         q.init_textentry_autocomplete ($ ('body'));
      }
   }
   q.processing_complete_b = true;
}
this.process_html2 = function (htm, i_qwiz, qwizard_b, create_qwizard_json_f) {
   var qwizdemo_re = new RegExp ('\\[qwizdemo\\][\\s\\S]*?\\[\\/qwizdemo\\]', 'gm');
   q.qwizdemos = htm.match (qwizdemo_re);
   if (q.qwizdemos) {
      htm = htm.replace (qwizdemo_re, '<qwizdemo></qwizdemo>');
      if (debug[0]) {
         console.log ('[process_html2] q.qwizdemos.length: ', q.qwizdemos.length);
      }
   }
   htm = htm.replace (/<!--[^]*?-->/gm, '');
   htm = htm.replace (/\[!+\][^]*?\[\/!+\]/gm, '');
   var local_n_qwizzes = 0;
   var do_not_process_htm = check_qwiz_tag_pairs (htm);
   if (do_not_process_htm) {
      htm = do_not_process_htm;
   } else {
      qwizzled_b = false;
      var qwiz_matches = htm.match (/\[qwiz[^]*?\[\/qwiz\]/gm);
      if (qwiz_matches) {
         local_n_qwizzes = qwiz_matches.length;
         if (debug[0]) {
            console.log ('[process_html2] local_n_qwizzes: ', local_n_qwizzes);
            console.log ('                qwiz_matches[0]: ', qwiz_matches[0]);
         }
         if (q.preview_i_qwiz_plus1) {
            q.preview = true;
         }
         var do_qwiz_pair = true;
         var replace_body = false;
         q.quizzes_questions = [];
         for (var ii_qwiz=0; ii_qwiz<local_n_qwizzes; ii_qwiz++) {
            if (q.preview_i_qwiz_plus1) {
               if (q.preview_i_qwiz_plus1 == i_qwiz + 1) {
                  replace_body = true;
                  do_qwiz_pair = true;
               } else {
                  do_qwiz_pair = false;
               }
            }
            if (do_qwiz_pair) {
               var new_qwiz_html
                           = q.process_qwiz_pair (qwiz_matches[ii_qwiz], i_qwiz,
                                                  qwizard_b,
                                                  create_qwizard_json_f,
                                                  create_qwizard_json_f);
               if (create_qwizard_json_f) {
                  if (qwizard.questions_cards && qwizard.questions_cards.length) {
                     qwizard.questions_cards[0].dataset_b = qwizard.questions_cards_dataset_b;
                     q.quizzes_questions[i_qwiz] = JSON.parse (JSON.stringify (qwizard.questions_cards));
                  }
                  if (debug[0]) {
                     console.log ('[process_html2] i_qwiz:', i_qwiz, ', qwizard.questions_cards:', qwizard.questions_cards);
                     if (qwizard.questions_cards) {
                        console.log ('[process_html2] JSON.stringify (qwizard.questions_cards):', JSON.stringify (qwizard.questions_cards));
                        console.log ('[process_html2] qwizard.questions_cards.length:', qwizard.questions_cards.length);
                        var ll = qwizard.questions_cards.length;
                        for (var ii=0; ii<ll; ii++) {
                           if (qwizard.questions_cards[ii] == '') {
                              console.log ('[process_html2] NULL JSON ii:', ii);
                           }
                        }
                     }
                  }
               }
               htm = htm.replace (/(<[ph][^>]*>\s*)*?\[qwiz[^]*?\[\/qwiz\]/m, function () {return new_qwiz_html});
            } else {
               qwizdata[i_qwiz] = {};
            }
            i_qwiz++;
         }
         if (debug[0] && q.quizzes_questions.length) {
            var n = q.quizzes_questions.length;
            for (var i=0; i<n; i++) {
               console.log ('[process_html2] q.quizzes_questions[' + i + ']:', q.quizzes_questions[i]);
            }
         }
         if (debug[3]) {
            console.log ('[process_html2] htm:', htm);
         }
      }
   }
   if (debug[0]) {
      console.log ('[process_html2] replace_body:', replace_body);
   }
   return {'htm': htm, 'i_qwiz': i_qwiz, 'replace_body': replace_body};
}
this.reset_preview = function (i_qwiz) {
   $.cookie ('qwiz_preview', i_qwiz, {path: '/'});
   window.location = window.location.href;
}
function dialog_no_credit_html () {
   htm = [];
   htm.push ('<div id="qwiz_dialog_no_credit" title="Are you taking the right quiz?">');
   htm.push (   '<p style="margin-bottom: 1em;">');
   htm.push (      'Note: this quiz has not been assigned as work you need to do.'); //DKTMP <span id="qwiz_usernames_is_are"></span> ');
   htm.push (      'We&rsquo;ll record your work, but it may not count for your class.');
   htm.push (   '</p>');
   htm.push (   '<p>');
   htm.push (      'Please check with your teacher whether they assigned this quiz to your class.');
   htm.push (   '</p>');
   /*
   htm.push (   '<p style="margin-bottom: 1em;">');
   htm.push (      'If you&rsquo;re not sure you registered for your class, and your teacher gave you a registration code, please enter it here:');
   htm.push (      '<br />');
   htm.push (      '<br />');
   htm.push (      '<label>');
   htm.push (         'Registration code &nbsp;');
   htm.push (         '<input type="text" style="display: inline-block; padding: 2px; width: 15em;" onchange="' + qname + '.process_reg_code (this.value)" onfocus="' + qname + '.hide_reg_code_error (this);" />');
   htm.push (      '</label>');
   htm.push (      '<button>');
   htm.push (         'Go');
   htm.push (      '</button>');
   htm.push (      '<div id="reg_code_errmsg" class="qwiz_reg_code_errmsg"></div>');
   htm.push (   '</p>');
   htm.push ('</div>');
   */
   return htm.join ('\n');
}
this.process_reg_code = function (reg_code) {
   if (debug[0]) {
      console.log ('[process_reg_code] reg_code:', reg_code);
   }
   if (reg_code != '') {
      var data = {reg_code: reg_code};
      qqc.jjax (qname, 0, 0, 'reg_code_add_to_class', data);
   }
}
this.hide_reg_code_error = function () {
   $ ('div.qwiz_reg_code_errmsg').html ('').hide ();
}
this.init_preview = function (i_qwiz) {
   $ ('#qwiz' + i_qwiz + ' div.qwizq').addClass ('qwizq_preview')
                                      .on ('mouseenter', q.set_i_qwiz_i_question)
                                      .show ();
   $ ('#qwiz' + i_qwiz).css ({border: 'none'});
   if (! q.no_intro_b[i_qwiz] && ! qwizdata[i_qwiz].use_dataset) {
      $ ('div.intro-qwiz' + i_qwiz).addClass ('qwiz qwizq_preview').show ();
   }
   q.display_summary_and_exit (i_qwiz);
   $ ('#summary-qwiz' + i_qwiz).addClass ('qwiz qwizq_preview').show ();
   $ ('#next_button-qwiz' + i_qwiz).remove ();
   var n_questions = qwizdata[i_qwiz].n_questions;
   for (var i_question=0; i_question<n_questions; i_question++) {
      q.display_question (i_qwiz, i_question, false);
   }
   init_preview_questions_active (i_qwiz);
}
function init_preview_show_answers (i_qwiz) {
   $ ('div#qwiz' + i_qwiz + ' input[type="radio"][data-q="1"]')
      .each (function () {
                var id = this.id;
                var choice_id = id.substr (6);
                q.process_choice (undefined, choice_id);
              });
   $ ('div#qwiz' + i_qwiz + ' button.show_the_answer')
        .each (function () {
                  $ (this).trigger ('click');
               });
   $ ('div#qwiz' + i_qwiz + ' div.show_answer_got_it_or_not').hide ();
   $ ('div#qwiz' + i_qwiz).find ('input.qwiz_textentry, input.qwiz_single_char_entry')
      .each (function () {
                var id = this.id;
                var i_question = id.split ('-')[2].substr (1);
                var answer = qwizdata[i_qwiz].textentry[i_question].first_correct_answer;
                $ (this).val (answer);
                $ (this).parents ('div.qwizq').find ('button.textentry_check_answer, button.qwiz_textentry_hint').hide ();
                if (preview_mode == 'show_answers_and_feedback') {
                   $ (this).parents ('div.qwizq').find ('div.qwiz-feedback').first ().show ();
                }
             });
   $ ('div#qwiz' + i_qwiz).find ('span.qwiz_hangman')
      .each (function () {
                var $this = $ (this);
                preview_replace_hangman_entries ($this, i_qwiz, 'final');
                $this.find ('span.hangman_type_letters').hide ();
                if (preview_mode == 'show_answers_and_feedback') {
                   $this.parents ('div.qwizq').find ('.qwiz-feedback').show ();
                }
             });
   $ ('div#qwiz' + i_qwiz).find ('div.qwizzled_label')
     .each (function () {
               var $label = $ (this);
               var classnames = $label.attr ('class');
               var m = classnames.match (/qtarget_assoc(\d+)/);
               var target_id = m[1];
               var $target = $ ('.qwizzled_target-' + target_id);
               place_label ($target, $label);
            });
}
this.change_preview_mode = function (radio_el, i_qwiz) {
   if (debug[0]) {
      console.log ('[change_preview_mode] radio_el:', radio_el)
   }
   for (var qwizzled_div_id in qwizdata[i_qwiz].$qwizzled) {
      $ ('div#' + qwizzled_div_id).replaceWith (qwizdata[i_qwiz].$qwizzled[qwizzled_div_id]);
      $ ('div#' + qwizzled_div_id).addClass ('qwizq_preview').show ();
      qwizdata[i_qwiz].$qwizzled[qwizzled_div_id] = $ ('div#' + qwizzled_div_id).clone (true);
   }
   $ ('div#qwiz' + i_qwiz + ' button.show_the_answer').show ();
   $ ('div#qwiz' + i_qwiz + ' div.show_answer_got_it_or_not').hide ();
   $ ('div#qwiz' + i_qwiz + ' div.qwiz-feedback').hide ();
   preview_mode = radio_el.value;
   if (preview_mode == 'questions_active') {
      init_preview_questions_active (i_qwiz);
   } else {
      init_preview_show_answers (i_qwiz);
   }
}
function init_preview_questions_active (i_qwiz) {
   $ ('div#qwiz' + i_qwiz + ' input[type="radio"]').attr ('disabled', false).prop ('checked', false);
   $ ('div#qwiz' + i_qwiz).find ('input.qwiz_textentry, input.qwiz_single_char_entry').attr ('disabled', false).val ('');
   $ ('div#qwiz' + i_qwiz).find ('button.textentry_check_answer, button.qwiz_textentry_hint').css ({display: ''});
   $ ('div#qwiz' + i_qwiz).find ('span.qwiz_hangman')
      .each (function () {
                var $this = $ (this);
                preview_replace_hangman_entries ($this, i_qwiz, 'initial');
          });
   for (var qwizzled_div_id in qwizdata[i_qwiz].$qwizzled) {
      $ ('div#' + qwizzled_div_id).replaceWith (qwizdata[i_qwiz].$qwizzled[qwizzled_div_id]);
      $ ('div#' + qwizzled_div_id).addClass ('qwizq_preview').show ();
      qwizdata[i_qwiz].$qwizzled[qwizzled_div_id] = $ ('div#' + qwizzled_div_id).clone (true);
   }
}
function preview_replace_hangman_entries ($this, i_qwiz, initial_final) {
   var $qwizq = $this.parents ('div.qwizq');
   var qwizq_id = $qwizq[0].id;
   var i_question = qwizq_id.split ('-')[1].substr (1);
   var classnames = $this.attr ('class');
   var m = classnames.match (/qwiz_hangman_c(\d+)/);
   var i_choice = m[1];
   var hangman = qwizdata[i_qwiz].hangman[i_question];
   var hangman_final_entry = hangman.hangman_final_entry[i_choice];
   if (initial_final == 'initial') {
      var hangman_current_entry = hangman_final_entry.replace (/>[a-z0-9]</gi, '>&ensp;<');
      $this.find ('span.hangman_current_entry').html (hangman_current_entry);
   } else {
      $this.find ('span.hangman_current_entry').html (hangman_final_entry);
   }
}
this.set_i_qwiz_i_question = function () {
   if (debug[0]) {
      console.log ('[set_i_qwiz_i_question] this:', this);
   }
   var id = this.id;
   var i_qwiz = id.match (/qwiz([^-]+)/)[1];
   var i_question = id.match (/-q(.+)/)[1];
   qwizdata[i_qwiz].i_question = i_question;
}
this.init_qwizzled = function ($content, i_qwiz, i_question) {
   if (debug[0]) {
      console.log ('[init_qwizzled] i_qwiz:', i_qwiz);
   }
   sibs = {};
   var t_id;
   var ii = 0;
   $content.find ('div.qwizzled_canvas .qwizzled_target').each (function () {
      var $this = $ (this);
      $this.removeClass ('ui-draggable ui-draggable-handle');
      $this.css ({'border-style': 'dotted', 'border-color': 'gray'});
      var classes = $this.attr ('class');
      var m = classes.match (/qtarget_sib-([0-9]+)/);
      if (m) {
         var sib = m[1];
         if (sibs[sib]) {
            t_id = sibs[sib];
         } else {
            t_id = 't' + ii;
            sibs[sib] = t_id;
            ii++;
         }
      } else {
         t_id = 't' + ii;
         ii++;
      }
      $this.attr ('id', t_id);
      $this.on ('click', function (event) {
                               if (debug[8]) {
                                  console.log ('[target clicked] $ (event.target):', $ (event.target));
                               }
                               var $target = $ (event.target);
                               if ($target.hasClass ('qwizzled_target')) {
                                  if (! $target.droppable ('option', 'disabled')) {
                                     q.label_dropped ($target);
                                  }
                               }
                            });
   });
   $content.find ('td.qwizzled_labels div.qwizzled_label').each (function () {
      $ (this).on ('click', function (event) {
                               if (debug[8]) {
                                  console.log ('[label clicked] $ (event.target).html ():', $ (event.target).html ());
                               }
                               if (ignore_label_click_b) {
                                  ignore_label_click_b = false;
                               } else {
                                  var $label;
                                  if (event.target.tagName.toLowerCase () == 'div') {
                                     $label = $ (event.target);
                                  } else {
                                     $label = $ (event.target).parents ('div.qwizzled_label');
                                  }
                                  var ii_qwiz = $label[0].id.match (/qwiz([^-]+)/)[1];
                                  $label_clicked[ii_qwiz] = $label;
                                  var $td_qwizzled_labels = $label.parents ('td.qwizzled_labels');
                                  $td_qwizzled_labels.find ('.qwizzled_highlight_label').removeClass ('label_click_highlight');
                                  $td_qwizzled_labels.find ('.qwizzled_label_head').hide ();
                                  $td_qwizzled_labels.find ('.qwizzled_label_head_label_clicked').show ();
                                  $label.find ('.qwizzled_highlight_label').addClass ('label_click_highlight');
                                  q.label_dragstart ($label, true);
                               }
                            });
   });
   $content.find ('div.qwizzled_canvas div.ui-resizable-handle').remove ();
   $content.find ('div.qwizzled_image div.qwizzled_target').css ('border-width', '2px');
   $content.find ('.qwizzled_highlight_label').css ('border', 'none');
   $content.find ('.qwizzled_highlight_label *').css ('word-wrap', 'normal');
   $content.find ('div.qwizzled_image').each (function () {
      var wrapper_width  = $ (this).width ();
      var wrapper_height = $ (this).height ();
      $ (this).find ('img').attr ('width', wrapper_width).attr ('height', wrapper_height)
                           .removeAttr ('sizes').removeAttr ('srcset');
   });
   q.init_qwizzled2 ($content, i_qwiz, i_question);
}
this.init_qwizzled2 = function ($content, i_qwiz, i_question) {
   var initial_width = $ ('#xqwiz' + i_qwiz).outerWidth ();
   if (debug[0]) {
      console.log ('[init_qwizzled2] i_qwiz:', i_qwiz, ', i_question:', i_question, ', initial_width:', initial_width);
   }
   if (initial_width) {
      qwizdata[i_qwiz].initial_width = initial_width;
   }
   if (! qwizdata[i_qwiz].$qwizzled) {
      qwizdata[i_qwiz].$qwizzled = {};
   }
   var qwizq_id = 'qwiz' + i_qwiz + '-q' + i_question;
   qwizdata[i_qwiz].$qwizzled[qwizq_id] = $content.clone (true);
}
this.init_textentry_autocomplete = function ($scope) {
   if (debug[6]) {
      console.log ('[init_textentry_autocomplete]');
   }
   $scope.find ('input.qwiz_textentry').autocomplete ({
      source:        find_matching_terms,
      close:         menu_closed,
      open:          menu_shown,
      select:        q.item_selected
   });
   $scope.find ('input.qwiz_textentry').keyup (menu_closed);
   $scope.find ('input.qwiz_single_char_entry').keyup (single_char_textentry_keyup);
}
this.label_dragstart = function ($label, label_clicked_b) {
   var i_qwiz = $label[0].id.match (/qwiz([^-]+)/)[1];
   if (label_clicked_b) {
      if (debug[8]) {
         console.log ('[label_dragstart] $label_clicked[i_qwiz].html():', $label_clicked[i_qwiz].html());
      }
   } else {
      if ($label_clicked[i_qwiz]) {
         $label_clicked[i_qwiz].find ('.qwizzled_highlight_label').removeClass ('label_click_highlight');
         var $td_qwizzled_labels = $label.parents ('td.qwizzled_labels');
         $td_qwizzled_labels.find ('.qwizzled_label_head').hide ();
         $td_qwizzled_labels.find ('.qwizzled_label_head_standard').show ();
         $label_clicked[i_qwiz] = '';
      }
      $prev_label_clicked[i_qwiz] = 0;
   }
   if (try_again_obj && $prev_label_clicked[i_qwiz] !== $label_clicked[i_qwiz]) {
      var local_try_again_obj = try_again_obj;
      try_again_obj = '';
      if (debug[8]) {
         console.log ('[label_dragstart] $label.html():', $label.html());
         console.log ('[label_dragstart] local_try_again_obj:', local_try_again_obj);
         console.log ('[label_dragstart] local_try_again_obj.$label.attr (\'id\'):', local_try_again_obj.$label.attr ('id'));
      }
      local_try_again_obj.$label.find ('.qwizzled_highlight_label').css ({background: ''});
      local_try_again_obj.$label.find ('.qwizzled_highlight_label img').css ({outline: ''});
      if (local_try_again_obj.$label.attr ('id') != $label.attr ('id')) {
         local_try_again_obj.$label.animate ({left: '0px', top: '0px'}, {duration: 750})
         local_try_again_obj.$label.find ('.qwizzled_highlight_label').removeClass ('label_click_highlight');
      }
      local_try_again_obj.$feedback.hide ();
      local_try_again_obj.$target.droppable ('enable');
   }
   $prev_label_clicked[i_qwiz] = '';
}
this.label_dropped = function ($target, $label) {
   if (debug[8]) {
      console.log ('[label_dropped]: $target:', $target, ', $label:', $label);
   }
   if ($label) {
      ignore_label_click_b = true;
      /* DKTMP DEDRAG
      if (q.qwizard_b) {
         $qwizzled_highlight_label = $label.children ();
         if ($qwizzled_highlight_label.tooltip ('instance')) {
            if (debug[8]) {
               console.log ('[label_dropped] $qwizzled_highlight_label:', $qwizzled_highlight_label);
            }
            $qwizzled_highlight_label.tooltip ('enable');
         }
      }
      */
   } else {
      var $qwizq = $target.parents ('div.qwizq');
      var i_qwiz = $qwizq[0].id.match (/qwiz([^-]+)/)[1];
      if ($label_clicked[i_qwiz]) {
         if (debug[8]) {
            console.log ('[label_dropped]: $label_clicked[i_qwiz]:', $label_clicked[i_qwiz]);
         }
         $label = $label_clicked[i_qwiz];
         var $td_qwizzled_labels = $label.parents ('td.qwizzled_labels');
         $td_qwizzled_labels.find ('.qwizzled_label_head').hide ();
         var standard_mobile = document_qwiz_mobile ? 'mobile' : 'standard';
         $td_qwizzled_labels.find ('.qwizzled_label_head_' + standard_mobile).show ();
      } else {
         return false;
      }
   }
   var classes = $label.attr ('class');
   m = classes.match (/qwizzled_n_targets([0-9]*)/);
   if (! m) {
      $label.find ('.qwizzled_highlight_label').removeClass ('label_click_highlight');
   }
   var classes = $label.attr ('class');
   var m = classes.match (/qtarget_assoc([0-9]+)/);
   var assoc_id;
   if (m) {
      assoc_id = m[1];
   } else {
      assoc_id = $label.data ('label_target_id');
   }
   if (debug[8]) {
      console.log ('[label_dropped] $target:', $target, ', assoc_id:', assoc_id);
   }
   var label_id = $label.attr ('id');
   var feedback_selector = '#' + label_id.substr (6);
   var fields = feedback_selector.split ('-');
   var question_selector = fields[0] + '-' + fields[1];
   var i_qwiz = fields[0].substr (5);
   var i_question = fields[1].substr (1);
   if (debug[8]) {
      console.log ('[label_dropped] question_selector:', question_selector);
   }
   if (qwizdata[i_qwiz].record_start_b && document_qwiz_user_logged_in_b) {
      qwizdata[i_qwiz].record_start_b = false;
      var data = {qrecord_id_ok: qwizdata[i_qwiz].qrecord_id_ok, type: 'start', confirm: 'js'};
      qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
   }
   if (qwizdata[i_qwiz].user_question_number == 1
               && (q.no_intro_b[i_qwiz] || qwizdata[i_qwiz].n_questions == 1)) {
      $ ('div#icon_qwiz' + i_qwiz).hide ();
      alert_not_logged_in (i_qwiz);
   }
   qwizdata[i_qwiz].n_label_attempts++;
   $ ('[id^=qwiz' + i_qwiz + '-q' + i_question + '-a]').hide ();
   var qwizq_id = '#qwiz' + i_qwiz + '-q' + i_question;
   var correct_b = false;
   if (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b) {
      if (qqc.isInteger (qwizdata[i_qwiz].dataset_id[i_question])) {
         if (! qwizdata[i_qwiz].q_and_a_text[i_question]) {
            var img_src = $ (qwizq_id + ' div.qwizzled_image img').attr ('src');
            var q_and_a_text;
            if (img_src) {
               q_and_a_text = img_src;
            } else {
               img_src = '';
            }
            $ (qwizq_id + ' div.qwizzled_label').each (function () {
                                                          var label_text = $ (this).html ();
                                                          if (label_text) {
                                                             q_and_a_text += '\t' + label_text;
                                                          }
                                                       });
            q_and_a_text = qqc.remove_tags_eols (q_and_a_text);
            qwizdata[i_qwiz].q_and_a_text[i_question]  = qqc.q_and_a_hash (q_and_a_text);
            qwizdata[i_qwiz].q_and_a_crc32[i_question] = qwiz_crc32 ($ (qwizq_id).html ());
            if (debug[0]) {
               console.log ('[label_dropped] qwizdata[i_qwiz].q_and_a_crc32[i_question]:', qwizdata[i_qwiz].q_and_a_crc32[i_question]);
            }
         }
      } else {
         qwizdata[i_qwiz].q_and_a_text[i_question]  = qwizdata[i_qwiz].dataset_id[i_question];
         qwizdata[i_qwiz].q_and_a_crc32[i_question] = 'dataset';
      }
   }
   var finished_diagram_b = false;
   if ($target.hasClass ('qwizzled_target-' + assoc_id)) {
      if (debug[8]) {
         console.log ('[label_dropped] feedback_selector:', feedback_selector + 'c');
         console.log ('[label_dropped] qwizdata[i_qwiz].n_questions:', qwizdata[i_qwiz].n_questions);
      }
      $ (feedback_selector + 'c').show ();
      place_label ($target, $label);
      qwizdata[i_qwiz].n_labels_correct++;
      if (qwizdata[i_qwiz].n_labels_correct == qwizdata[i_qwiz].n_label_targets) {
         finished_diagram_b = true;
         var n_tries = qwizdata[i_qwiz].n_label_attempts;
         var n_label_targets = qwizdata[i_qwiz].n_label_targets;
         correct_b = n_tries == n_label_targets;
         var qwizzled_summary;
         if (correct_b) {
            qwizzled_summary = 'You placed all of the items correctly on the first try!';
         } else {
            qwizzled_summary = Tplural ('It took you one try', 'It took you %s tries', n_tries) + ' ' + Tplural ('to place this label correctly', 'to place these labels correctly', n_label_targets) + '.';
            qwizzled_summary = qwizzled_summary.replace ('%s', qqc.number_to_word (n_tries));
            if (qwizdata[i_qwiz].n_questions == 1
                                       && qwizdata[i_qwiz].repeat_incorrect_b) {
               qwizzled_summary += '<br />Re-do those you did not label correctly ';
               if (qwizdata[i_qwiz].qrecord_id
                                            && document_qwiz_user_logged_in_b) {
                  qwizzled_summary += 'to get this question marked &ldquo;correct&rdquo; '
               }
               qwizzled_summary +=  '<button class="qwiz_button" onclick="qwiz_.next_question (' + i_qwiz + ', true)">'
                                  +    'Re-do'
                                  + '</button>';
               if (! q.qwizard_b) {
                  qwizdata[i_qwiz].answered_correctly[i_question] = -1;
               }
            }
         }
         $ (qwizq_id + '-ff').html (qwizzled_summary).show ();
         if (qwizdata[i_qwiz].n_questions > 1 || qwizdata[i_qwiz].use_dataset) {
            if (! q.qwizard_b) {
               qwizdata[i_qwiz].answered_correctly[i_question] = correct_b ? 1 : -1;
               if (correct_b) {
                  qwizdata[i_qwiz].n_correct++;
               } else {
                  qwizdata[i_qwiz].n_incorrect++;
               }
               update_topic_statistics (i_qwiz, i_question, correct_b);
            }
            update_progress_show_next (i_qwiz);
         } else {
            display_qwizzled_progress (i_qwiz);
         }
         update_progress_show_next (i_qwiz);
      } else {
         var target_id = $target.attr ('id');
         if (typeof (qwizdata[i_qwiz].correct_on_try1[i_question]) == 'undefined') {
            qwizdata[i_qwiz].correct_on_try1[i_question] = {};
         }
         if (! qwizdata[i_qwiz].correct_on_try1[i_question][target_id]) {
            qwizdata[i_qwiz].correct_on_try1[i_question][target_id] = 1;
         }
         display_qwizzled_progress (i_qwiz);
      }
   } else {
      if (debug[8]) {
         console.log ('[label_dropped] feedback_selector:', feedback_selector + 'x');
         console.log ('[label_dropped] qwizdata[i_qwiz].n_questions:', qwizdata[i_qwiz].n_questions);
      }
      if ($label_clicked[i_qwiz]) {
         var target_offset = $target.offset ();
         var target_x = target_offset.left;
         var target_y = target_offset.top;
         var label_x = $label_clicked[i_qwiz].data ('label_x');
         var label_y = $label_clicked[i_qwiz].data ('label_y');
         if (debug[8]) {
            console.log ('[label_dropped] target_x:', target_x, ', target_y:', target_y);
            console.log ('[label_dropped] label_x:', label_x, ', label_y:', label_y);
         }
         $label.css ({left: (target_x - label_x) + 'px',
                      top:  (target_y - label_y) + 'px'});
         $label_clicked[i_qwiz] = '';
      }
      $label.find ('.qwizzled_highlight_label').css ({background: '#FF8080'});
      $label.find ('.qwizzled_highlight_label img').css ({outline: '2px solid #FF8080'});
      var $feedback = $ (feedback_selector + 'x');
      $feedback.show ();
      try_again_obj = { $label: $label, $feedback:  $feedback, $target: $target};
      $target.droppable ('disable');
      var target_id = $target.attr ('id');
      if (typeof (qwizdata[i_qwiz].correct_on_try1[i_question]) == 'undefined') {
         qwizdata[i_qwiz].correct_on_try1[i_question] = {};
      }
      qwizdata[i_qwiz].correct_on_try1[i_question][target_id] = -1;
   }
   if (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b) {
      var label = $label.find ('span.qwizzled_highlight_label').html ();
      label = qqc.remove_tags_eols (label);
      var classes = $target.attr ('class');
      var target_assoc_id = classes.match (/qwizzled_target-([0-9]*)/)[1];
      var target_label = $ (qwizq_id).find ('div.qtarget_assoc' + target_assoc_id).find ('span.qwizzled_highlight_label').html ();
      if (! target_label) {
         target_label = $ (qwizq_id).find ('div.qwizzled_label[data-label_target_id="' + target_assoc_id + '"]').find ('span.qwizzled_highlight_label').html ();
      }
      target_label = qqc.remove_tags_eols (target_label);
      var data = {q_and_a_text:  btoa (encodeURIComponent (qwizdata[i_qwiz].q_and_a_text[i_question])),
                  q_and_a_crc32: qwizdata[i_qwiz].q_and_a_crc32[i_question],
                  i_question:    qwizdata[i_qwiz].dataset_id[i_question],
                  unit:          qwizdata[i_qwiz].unit[i_question],
                  type:          'labeled_diagram',
                  response:      label + '\t' + target_label,
                  correct_b:     '',
                  confirm:       'js'};
      qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
      if (finished_diagram_b) {
         var data = {q_and_a_text:  btoa (encodeURIComponent (qwizdata[i_qwiz].q_and_a_text[i_question])),
                     q_and_a_crc32: qwizdata[i_qwiz].q_and_a_crc32[i_question],
                     i_question:    qwizdata[i_qwiz].dataset_id[i_question],
                     unit:          qwizdata[i_qwiz].unit[i_question],
                     type:          'labeled_diagram',
                     response:      'done',
                     correct_b:     correct_b ? 1 : '',
                     confirm:       'js'};
         var delay_jjax = function () {
            qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
         }
         setTimeout (delay_jjax, 500);
      }
   }
}
function place_labels (i_qwiz, i_question, qwizq_id) {
   if (debug[0]) {
      console.log ('[place_labels] i_qwiz:', i_qwiz, ', i_question:', i_question, ', qwizq_id:, ', qwizq_id);
   }
   for (var target_id in qwizdata[i_qwiz].correct_on_try1[i_question]) {
      if (qwizdata[i_qwiz].correct_on_try1[i_question][target_id] == 1) {
         var $target = $ ('div#' + qwizq_id + ' div#' + target_id);
         if ($target.length == 0) {
            $target = $ ('div#' + qwizq_id + ' span#' + target_id).first ();
         }
         var classes = $target.attr ('class');
         var m = classes.match (/qwizzled_target-([0-9]+)/);
         var assoc_id;
         if (m) {
            assoc_id = m[1];
         }
         if (debug[8]) {
            console.log ('[place_labels] $target:', $target, ', assoc_id:', assoc_id);
         }
         var $label = $ ('td.qwizzled_labels div.qtarget_assoc' + assoc_id);
         if (! $label.length) {
            $label = $ ('div#' + qwizq_id).find ('td.qwizzled_labels div.qwizzled_label[data-label_target_id="' + assoc_id + '"]');
         }
         place_label ($target, $label);
         qwizdata[i_qwiz].n_labels_correct++;
         qwizdata[i_qwiz].n_label_attempts++;
      } else {
         qwizdata[i_qwiz].correct_on_try1[i_question][target_id] = 0;
      }
   }
   display_qwizzled_progress (i_qwiz);
}
function place_label ($target, $label) {
   var $label_copy = $label.clone (false);
   if (q.qwizard_b) {
      $editable = $label_copy.find ('.qwiz_editable');
      $editable.removeAttr ('id')
               .removeAttr ('contenteditable')
               .removeClass ('qwiz_editable')
               .addClass ('qwizzled_label_placed');
      if (debug[12]) {
         console.log ('[place_label] $editable:', $editable);
      }
   }
   $label_copy.appendTo ($target);
   $label_copy.css ({position: 'absolute', left: '4px', top: '50%', height: 'auto', width: '100%', transform: 'translateY(-50%)'});
   $label_copy.removeClass ('qwizzled_label_unplaced');
   $label_copy.find ('.qwizzled_highlight_label').css ('cursor', 'default').removeClass ('label_click_highlight');
   $label.css ({left: '0px', top: '0px'});
   var multiple_targets_b = false;
   var classes = $label.attr ('class');
   m = classes.match (/qwizzled_n_targets([0-9]*)/);
   if (m) {
      multiple_targets_b = true;
      var current_n_targets = m[0];
      var n_targets = parseInt (m[1], 10);
      if (n_targets == 2) {
         $label.removeClass (current_n_targets);
      } else {
         var new_class = 'qwizzled_n_targets' + (--n_targets);
         $label.removeClass (current_n_targets).addClass (new_class);
      }
   }
   if (! q.qwizard_b && ! multiple_targets_b) {
      if (debug[8]) {
         console.log ('[place_label] (draggable disable) $label[0]:', $label[0]);
      }
      $label.css ({color: 'lightgray', left: '0px', top: '0px'});
      $label.find ('*').css ({color: 'lightgray'});
      $label.find ('.qwizzled_highlight_label').css ('cursor', 'default');
      $label.removeClass ('qwizzled_label_unplaced');
      if (! q.qwizard_b) {
         try {
            $label.draggable ('disable');
         } catch (e) {};
      }
      $label.off ('click');
   }
   if (! q.qwizard_b) {
      if ($target[0].tagName.toLowerCase () == 'div') {
         if ($target.droppable ('instance')) {
            $target.droppable ('disable');
         }
      } else {
         var classes = $target.attr ('class');
         var m = classes.match (/qtarget_sib-[0-9]+/);
         if (m) {
            var $span = $target.parents ('qwizq').find ('span.' + m[0]);
            if ($span.droppable ('instance')) {
               $span.droppable ('disable');
            }
         } else {
            var $siblings = $target.siblings ('span').andSelf ();
            $siblings.droppable ('disable');
         }
      }
   }
}
this.process_qwiz_pair = function (htm, i_qwiz, qwizard_b,
                                   existing_quiz_to_qwizard_f,
                                   qwizard_process_dataset_questions_f) {
   q.qwizard_b = qwizard_b;
   if (typeof qwizard != 'undefined') {
      qw = qwizard;
   }
   if (existing_quiz_to_qwizard_f) {
      n_qwizzes = 1;
      set_qwizard_data_b = true;
      q.no_intro_b = [];
      if (debug[0]) {
         console.log ('[process_qwiz_pair] htm.substr (0, 2000):', htm.substr (0, 2000));
      }
   } else {
      set_qwizard_data_b = false;
   }
   qwizdata[i_qwiz] = {};
   qwizdata[i_qwiz].questions            = [];
   qwizdata[i_qwiz].answered_correctly   = [];
   qwizdata[i_qwiz].n_correct            = 0;
   qwizdata[i_qwiz].n_incorrect          = 0;
   qwizdata[i_qwiz].i_question           = -1;
   qwizdata[i_qwiz].i_user_question      = -1;
   qwizdata[i_qwiz].user_question_number = 0;
   qwizdata[i_qwiz].initial_width        = 500;
   qwizdata[i_qwiz].hangman = {};
   qwizdata[i_qwiz].use_dataset = '';
   qwizdata[i_qwiz].dataset_id = {};
   qwizdata[i_qwiz].use_dataset_question_ids = {};
   qwizdata[i_qwiz].bg_img = {};
   qwizdata[i_qwiz].align = '';
   qwizdata[i_qwiz].qrecord_id = '';
   qwizdata[i_qwiz].qrecord_id_ok = 'check credit';
   qwizdata[i_qwiz].information_question_b = {};
   qwizdata[i_qwiz].unit = [];
   qwizdata[i_qwiz].parts_htm = {};
   var m = htm.match (/\[qwiz([^\]]*)\]/m);
   var qwiz_tag   = m[0];
   var attributes = m[1];
   qwiz_tag   = qqc.replace_smart_quotes (qwiz_tag);
   attributes = qqc.replace_smart_quotes (attributes);
   if (debug[0]) {
      console.log ('[process_qwiz_pair] qwiz_tag: ', qwiz_tag);
      console.log ('[process_qwiz_pair] attributes: ', attributes);
   }
   if (set_qwizard_data_b) {
      qw.set_qwizard_data ('qwiz_deck_attributes', attributes);
   }
   var use_dataset_questions_b = false;
   qwizdata[i_qwiz].summary_b = get_attr (qwiz_tag, 'summary') != 'false';
   var use_dataset = get_attr (qwiz_tag, 'use_dataset', true);
   if (use_dataset) {
      qwizdata[i_qwiz].use_dataset = use_dataset;
      var dataset_intro_f = get_attr (qwiz_tag, 'dataset_intro');
      if (! dataset_intro_f || dataset_intro_f == 'true') {
         dataset_intro_f = true;
      } else if (dataset_intro_f == 'false') {
         dataset_intro_f = false;
      }
      qwizdata[i_qwiz].dataset_intro_f = dataset_intro_f;
      var spaced_repetition_f = get_attr (qwiz_tag, 'spaced_repetition') != 'false';
      qwizdata[i_qwiz].dataset_questions_to_do = spaced_repetition_f ? 'spaced_repetition' : 'all';
      qwizdata[i_qwiz].random_b = get_attr (qwiz_tag, 'random') == 'true';
      var m = qwiz_tag.match (/\sstyle\s*=\s*"[^"]+"/gm);
      if (m) {
         var len = m.length;
         for (var i=0; i<len; i++) {
            var encoded_style = encodeURIComponent (m[i]);
            qwiz_tag = qwiz_tag.replace (m[i], encoded_style);
         }
      }
      var display_name = get_attr (qwiz_tag, 'display_name');
      if (display_name) {
         qwizdata[i_qwiz].use_dataset_options_display_name = decodeURIComponent (display_name);
         var qwiz_tag = qwiz_tag.replace (/\sdisplay_name\s*=\s*"[^"]*?"/, '');
      }
      qwiz_tag = decodeURIComponent (qwiz_tag);
   }
   var align = get_attr (attributes, 'align');
   if (align == 'center' || align == 'right' || align == 'tiled') {
      qwizdata[i_qwiz].align = align;
      if (align == 'tiled') {
         qwizdata[i_qwiz].spacing = 20;
      }
   }
   var spacing = parseInt (get_attr (attributes, 'spacing'));
   if (qqc.isInteger (spacing)) {
      qwizdata[i_qwiz].spacing = spacing;
   }
   qwizdata[i_qwiz].hide_forward_back_b = get_attr (qwiz_tag, 'hide_forward_back') == 'true';
   qwizdata[i_qwiz].hide_progress_b = get_attr (qwiz_tag, 'hide_progress') == 'true';
   qwizdata[i_qwiz].hide_qwizcards_icon_b = get_attr (qwiz_tag, 'hide_qwizcards_icon') == 'true';
   var qrecord_id = get_attr (attributes, 'qrecord_id');
   if (qrecord_id) {
      qwizdata[i_qwiz].qrecord_id = qrecord_id;
      qwizdata[i_qwiz].q_and_a_text  = {};
      qwizdata[i_qwiz].q_and_a_crc32 = {};
      if (! q.qrecord_b) {
         q.qrecord_b = true;
         if (typeof (document_qwiz_user_logged_in_b) == 'undefined'
                              || document_qwiz_user_logged_in_b == 'not ready') {
            qqc.check_session_id (i_qwiz);
         }
      }
      var display_pay_screen = get_attr (attributes, 'display_pay_screen');
      if (display_pay_screen && display_pay_screen != 'false') {
         if (display_pay_screen == 'true' || display_pay_screen == 'login') {
            display_pay_screen = 'login';
         } else if (display_pay_screen == 'register') {
            display_pay_screen = 'register';
         }
         qwizdata[i_qwiz].display_pay_screen = display_pay_screen;
      } else {
         display_pay_screen = '';
      }
   }
   if (q.qwizard_b || set_qwizard_data_b) {
      if (get_attr (attributes, 'dataset')) {
         q.dataset_b = true;
         if (set_qwizard_data_b) {
            qw.questions_cards_dataset_b = true;
         }
      }
   }
   var unit = get_attr (attributes, 'unit');
   if (unit) {
      qwizdata[i_qwiz].default_unit = unit.replace (/\s/g, '_');
      if (set_qwizard_data_b) {
         qw.set_qwizard_data ('default_unit', unit);
      }
   } else {
      qwizdata[i_qwiz].default_unit = 'null';
   }
   var new_htm = '';
   var no_intro_i_b = false;
   var m = htm.match (/\[qwiz[^\]]*\]((<\/[^>]+>\s*)*)/m, '');
   if (m) {
      var initial_closing_tags = m[1];
      if (debug[0]) {
         console.log ('[process_qwiz_pair] initial_closing_tags: ', initial_closing_tags);
      }
   }
   htm = htm.replace (/\[qwiz[^\]]*\]((<\/[^>]+>\s*)*)/m, '');
   var len = htm.length;
   htm = htm.substring (0, len-7);
   htm = qqc.trim (htm);
   m = htm.match (/\[(q|<code><\/code>q)([^\]]*)\]/gm);
   var n_questions = m ? m.length : 0;
   if (! use_dataset && ! q.qwizard_b && n_questions == 0) {
      errmsgs.push (T ('Did not find question tags ("[q]")') + '.  qwiz: ' + (i_qwiz + 1));
      header_html = '';
   } else {
      htm = qqc.process_inline_textentry_terms (htm, 'terms', qwizdata, i_qwiz);
      errmsgs = errmsgs.concat (qwizdata.additional_errmsgs);
      htm = qqc.process_inline_textentry_terms (htm, 'add_terms', qwizdata, i_qwiz);
      errmsgs = errmsgs.concat (qwizdata.additional_errmsgs);
      var whitespace = qqc.parse_html_block (htm.substr (0, 2000), ['^'], ['[h]', '[i]', '[q]', '[q '], '[<code></code>q', 'return whitespace');
      if (whitespace) {
         htm = htm.replace (whitespace, '');
      }
      htm = process_header (htm, i_qwiz, 0, true);
      if (set_qwizard_data_b && header_html != 'NA') {
         qw.set_qwizard_data ('header_text', header_html);
      }
      var intro_html = qqc.parse_html_block (htm.substr (0, 5000), ['[i]'], ['[q]', '[q ', '[<code></code>q', '<div class="qwizzled_question', '[x]']);
      if (intro_html == 'NA') {
         intro_html = qqc.parse_html_block (htm.substr (0, 5000), ['^'], ['[q]', '[q ', '[<code></code>q', '<div class="qwizzled_question', '[x]'], true);
         if (intro_html == '') {
            if (use_dataset) {
               intro_html = '<br /><br /><br />';
            } else {
               no_intro_i_b = true;
            }
         }
      } else {
         var htmx = htm.substr (0, 200);
         htmx = qqc.trim (htmx);
         var i_pos = qqc.opening_tag_shortcode_pos ('[i]', htmx);
         htmx = htmx.substr (i_pos, 5);
         var intro_htmlx = intro_html.replace (/<br[^>]*>/g, '');
         intro_htmlx = qqc.trim (intro_htmlx).substr (0, 5);
         if (htmx != intro_htmlx) {
            errmsgs.push (T ('Text before intro') + ' [i].  qwiz: ' + (i_qwiz + 1));
         }
         intro_html = intro_html.replace ('[i]', '');
         intro_html = qqc.balance_closing_tag (intro_html);
      }
      if (q.qwizard_b) {
         intro_html = qqc.shortcodes_to_video_elements (intro_html);
      }
      if (! no_intro_i_b || q.qwizard_b) {
         if (debug[0]) {
            console.log ('[process_qwiz_pair] intro_html:', intro_html);
         }
         new_htm += '<div class="intro-qwiz' + i_qwiz + ' qwiz-intro qwiz_editable">'
                  +    qqc.decode_image_tags (intro_html)
                  + '</div>\n';
      }
      if (set_qwizard_data_b) {
         qw.set_qwizard_data ('intro_text', intro_html);
      }
      var exit_html = qqc.parse_html_block (htm, ['[x]'], []);
      if (exit_html != 'NA') {
         exit_html = exit_html.replace (/\[x\]/, '');
         if (exit_html.search (/\[q[ \]]|<div class="qwizzled_question/) != -1) {
            errmsgs.push ('[x] ' + T ('(exit text) must be last') + '.  qwiz: ' + (i_qwiz + 1));
         } else {
            var i_pos_exit_opening = qqc. opening_tag_shortcode_pos ('[x]', htm);
            htm = htm.substr (0, i_pos_exit_opening);
         }
      } else {
         exit_html = '';
      }
      if (set_qwizard_data_b) {
         var qwizard_exit_html = qqc.shortcodes_to_video_elements (exit_html);
         qw.set_qwizard_data ('exit_text', qwizard_exit_html);
      }
      if (! use_dataset) {
         if (htm.search (/use_dataset_question\s*=\s*/) != -1) {
            use_dataset_questions_b = true;
            qwizdata[i_qwiz].use_dataset_questions_htm = htm;
         }
      }
      if (! use_dataset && (! use_dataset_questions_b || qwizard_process_dataset_questions_f)) {
         if (n_questions == 0) {
            qwizdata[i_qwiz].n_questions = 0;
            new_htm += '<div id="qwiz' + i_qwiz + '-q-1" class="qwizq">'
                       + '</div>';
         } else {
            qwizdata[i_qwiz].n_questions          = n_questions;
            qwizdata[i_qwiz].n_questions_for_done = n_questions;
            new_htm = q.process_questions (htm, new_htm, i_qwiz);
         }
      } else {
         if (qwizard_process_dataset_questions_f) {
            qwizard.questions_cards_dataset_b = false;
         }
         new_htm +=   '<div id="dataset_questions-qwiz' + i_qwiz + '">'
                    + '</div>';
      }
   }
   q.no_intro_b[i_qwiz] = no_intro_i_b;
   new_htm = create_qwiz_divs (i_qwiz, qwiz_tag, new_htm, exit_html);
   if (typeof q.qwizard_b != 'undefined') {
      qwizard.errmsgs = errmsgs;
   }
   return new_htm;
}
this.process_questions = function (htm, new_htm, i_qwiz, i_qwizard_question,
                                                               set_qwizard_f) {
   if (set_qwizard_f) {
      qw = qwizard;
      n_qwizzes = 1;
      set_qwizard_data_b = true;
      q.qwizard_b = true;
   }
   if (typeof (i_qwizard_question) != 'undefined') {
      number_first_question = i_qwizard_question;
   } else {
      number_first_question = 0;
   }
   if (! set_qwizard_data_b) {
      if (htm.indexOf ('[!') != -1) {
         htm = htm.replace (/\[!+\][^]*?\[\/!+\]/gm, '');
      }
   }
   var question_html = htm.match (/(\[q [^\]]*\]|<div class="qwizzled_question|\[q\])[^]*/m)[0];
   var question_shortcodes = question_html.match (/\[(<code><\/code>)*q([^\]]*)\]/gm);
   if (debug[4] || debug[11]) {
      console.log ('[process_questions] question_shortcodes: ', question_shortcodes);
   }
   n_questions = question_shortcodes.length;
   qwizdata[i_qwiz].question_topics = new Array (n_questions);
   if (q.qwizard_b) {
      qwizdata[i_qwiz].qwizard_multiple_choice_b = [];
   }
   qwizdata[i_qwiz].units  = [];
   qwizdata[i_qwiz].topics = [];
   var matches = htm.match (/(<[^\/][^>]*>\s*)*?(\[q[ \]]|\[<code><\/code>q)/gm);
   var q_opening_tags = [];
   var n_q_opening_tags = matches.length;
   for (var i_tag=0; i_tag<n_q_opening_tags; i_tag++) {
      var q_opening_tag = matches[i_tag];
      q_opening_tag = q_opening_tag.replace (/\[q[ \]]|\[<code><\/code>q/gm, '');
      q_opening_tag = q_opening_tag.replace (/[^]*<(img|input)[^>]+>/, '');
      q_opening_tags.push (q_opening_tag);
   }
   if (debug[0] || debug[11]) {
      console.log ('[process_questions] q_opening_tags: ', q_opening_tags);
      console.log ('[process_questions] question_html: ', question_html);
   }
   var first_q_qwizzled_b = question_html.substr (0, 2) != '[q';
   if (first_q_qwizzled_b) {
      question_html = question_html.replace (/<div class="qwizzled_question[^>]*>/, '');
   } else {
      var start = question_html.indexOf (']') + 1;
      question_html = question_html.substr (start);
   }
   var qwizzled_pieces = question_html.split (/<div class="qwizzled_question[^_][^>]*>/);
   if (debug[0] || debug[11]) {
      console.log ('[process_questions] qwizzled_pieces.length:', qwizzled_pieces.length);
   }
   var questions_html = [];
   if (qwizzled_pieces.length == 1) {
      var q_split = question_html.split (/\[q [^\]]*\]|\[<code><\/code>q [^\]]*\]|\[q\]|\[<code><\/code>q\]/);
      var i_qbeg = 0;
      if (first_q_qwizzled_b) {
         questions_html.push (q_split[0] + '[q]' + q_split[1]);
         i_qbeg = 2;
      }
      for (var i_q=i_qbeg; i_q<q_split.length; i_q++) {
         questions_html.push (q_split[i_q]);
      }
   } else if (qwizzled_pieces.length > 1) {
      if (first_q_qwizzled_b) {
         for (var i_qwizzled=0; i_qwizzled<qwizzled_pieces.length; i_qwizzled++) {
            var q_split = qwizzled_pieces[i_qwizzled].split (/\[q [^\]]*\]|\[<code><\/code>q [^\]]*\]|\[q\]|\[<code><\/code>q\]/);
            questions_html.push (q_split[0] + '[q]' + q_split[1]);
            for (var i_q=2; i_q<q_split.length; i_q++) {
               questions_html.push (q_split[i_q]);
            }
         }
      } else {
         var q_split =  qwizzled_pieces[0].split (/\[q [^\]]*\]|\[q\]/);
         for (var i_q=0; i_q<q_split.length; i_q++) {
            questions_html.push (q_split[i_q]);
         }
         for (var i_qwizzled=1; i_qwizzled<qwizzled_pieces.length; i_qwizzled++) {
            var q_split = qwizzled_pieces[i_qwizzled].split (/\[q [^\]]*\]|\[<code><\/code>q [^\]]*\]|\[q\]|\[<code><\/code>q\]/);
            questions_html.push (q_split[0] + '[q]' + q_split[1]);
            for (var i_q=2; i_q<q_split.length; i_q++) {
               questions_html.push (q_split[i_q]);
            }
         }
      }
   }
   if (set_qwizard_data_b && typeof (i_qwizard_question) == 'undefined') {
      qw.set_qwizard_data ('n_questions', n_questions);
   }
   if (! q.qwizard_b) {
      qwizdata[i_qwiz].n_questions_for_done = n_questions;
      qwizdata[i_qwiz].n_questions          = n_questions;
   }
   if (debug[0] || debug[11]) {
      console.log ('[process_questions] n_questions:', n_questions);
      console.log ('[process_questions] questions_html:', questions_html.join ('\n================================================\n'));
   }
   var question_divs = [];
   var question_div;
   var questions_w_topics_b = false;
   for (var ii=0; ii<n_questions; ii++) {
      var i_question = ii + number_first_question;
      var question_shortcode;
      if (typeof (i_qwizard_question) != 'undefined') {
         question_shortcode = question_shortcodes[0];
      } else {
         question_shortcode = question_shortcodes[i_question];
      }
      question_topic = process_question_attributes (i_qwiz, i_question, question_shortcode, i_qwizard_question);
      if (question_topic) {
         questions_w_topics_b = true;
      }
      if (questions_html[ii].indexOf ('[hangman') != -1
                  || questions_html[ii].indexOf ('hangman_img_wrapper') != -1) {
         question_div = process_hangman (i_qwiz, i_question,
                                         questions_html[ii],
                                         q_opening_tags[ii]);
      } else if (questions_html[ii].indexOf ('[textentry') != -1) {
         question_div = process_textentry (i_qwiz, i_question,
                                           questions_html[ii],
                                           q_opening_tags[ii]);
      } else if (questions_html[ii].search (/\[c\]|\[c\*\]/m) != -1) {
         question_div = process_multiple_choice (i_qwiz, i_question,
                                                 questions_html[ii],
                                                 q_opening_tags[ii]);
      } else if (questions_html[ii].search (/<div[^>]+class=".*?qwizzled_label/m) != -1) {
         qwizzled_b = true;
         qwizdata[i_qwiz].qwizzled_b = true;
         question_div = process_qwizzled (i_qwiz, i_question,
                                          questions_html[ii],
                                          q_opening_tags[ii],
                                          question_shortcodes[ii]);
         if (qwizdata[i_qwiz].correct_on_try1) {
            qwizdata[i_qwiz].correct_on_try1[i_question] = {};
         } else {
            qwizdata[i_qwiz].correct_on_try1 = [];
         }
      } else {
         var question_htm = questions_html[ii];
         var question_html_wo_tags_whitespace = question_htm.replace (/<[^>]+>|&nbsp;|\s/gm, '');
         if (! question_html_wo_tags_whitespace) {
            if (question_htm.indexOf ('img') != -1) {
               question_html_wo_tags_whitespace = true;
            }
         }
         if (! question_html_wo_tags_whitespace) {
            if (! qwizdata[i_qwiz].use_dataset_question_ids[i_question]) {
               errmsgs.push (T ('Question is completely blank') + '.  qwiz: ' + (i_qwiz + 1) + ', ' + T ('question') + ' ' + (i_question + 1));
            }
         } else {
            qwizdata[i_qwiz].information_question_b[i_question] = true;
            if (qwizdata[i_qwiz].qrecord_id) {
               var q_and_a_text;
               if (qqc.isInteger (qwizdata[i_qwiz].dataset_id[i_question])) {
                  q_and_a_text = qqc.remove_tags_eols (question_htm);
                  qwizdata[i_qwiz].q_and_a_text[i_question]  = qqc.q_and_a_hash (q_and_a_text);
                  qwizdata[i_qwiz].q_and_a_crc32[i_question] = qwiz_crc32 (questions_html[ii]);
               } else {
                  qwizdata[i_qwiz].q_and_a_text[i_question]  = qwizdata[i_qwiz].dataset_id[i_question];
                  qwizdata[i_qwiz].q_and_a_crc32[i_question] = 'dataset';
               }
            }
         }
         if (set_qwizard_data_b) {
            qw.questions_cards[i_question].type = 'information_only';
            question_htm = qqc.shortcodes_to_video_elements (question_htm);
            qw.questions_cards[i_question].question_text = q_opening_tags[ii] + question_htm;
         }
         var bg_img_style = create_bg_img_style (i_qwiz, i_question);
         question_div = '<div id="qwiz' + i_qwiz + '-q' + i_question + '" class="qwizq"' + bg_img_style + '>\n'
                    +      '<div class="qwiz-question qwiz_editable">'
                    +          q_opening_tags[ii] + question_htm
                    +      '</div>'
                    +   '</div>';
      }
      question_divs.push (question_div);
   }
   new_htm += question_divs.join ('\n');
   if (questions_w_topics_b) {
      if (debug[4]) {
         console.log ('[process_questions] topics: ' + qwizdata[i_qwiz].topics.join ('; '));
      }
   }
   if (set_qwizard_data_b) {
      qw.unit_names = qwizdata[i_qwiz].units;
   }
   if (qwizdata[i_qwiz].topics.length) {
      check_questions_have_topics (i_qwiz);
      if (set_qwizard_data_b) {
         qw.topic_names = qwizdata[i_qwiz].topics;
      }
   }
   if (debug[3] || debug[11]) {
      console.log ('[process_questions] new_htm: ', new_htm);
   }
   if (debug[12]) {
      console.log ('[process_questions] errmsgs: ', errmsgs.join ('\n'));
   }
   return new_htm;
}
function create_qwiz_divs (i_qwiz, qwiz_tag, htm, exit_html) {
   var m = qwiz_tag.match (/\[qwiz([^\]]*)\]/m);
   var attributes = m[1];
   if (debug[0]) {
      console.log ('[create_qwiz_divs] attributes: ', attributes);
   }
   attributes = qqc.replace_smart_quotes (attributes);
   var non_default_width_b = attributes.search (/[\s;"]width/m) != -1;
   var repeat_incorrect_value = get_attr (attributes, 'repeat_incorrect');
   qwizdata[i_qwiz].repeat_incorrect_b = repeat_incorrect_value != 'false';
   if (debug[0]) {
      console.log ('[create_qwiz_divs] repeat_incorrect_value:', repeat_incorrect_value, ', repeat_incorrect_b:', qwizdata[i_qwiz].repeat_incorrect_b);
   }
   var random = get_attr (attributes, 'random');
   qwizdata[i_qwiz].random_b = random == 'true';
   if (debug[0]) {
      console.log ('[create_qwiz_divs] random:', random, ', random_b:', qwizdata[i_qwiz].random_b);
   }
   var top_html = [];
   if (non_default_width_b) {
      var xattributes = attributes.replace (/(style\s*=\s*"[^"]*)/, '$1; position: absolute;');
      xattributes = xattributes.replace (/;\s*;/g, ';');
      top_html.push ('<div id="xqwiz' + i_qwiz + '" class="xqwiz" ' + xattributes + '></div>\n');
   }
   if (qwizdata[i_qwiz].align) {
      var align = qwizdata[i_qwiz].align;
      var style = '';
      if (align == 'center') {
         style = 'margin: auto;';
      } else if (align == 'right') {
         style = 'margin-left: auto;';
      } else if (align == 'tiled') {
         style = 'float: left;';
         if (qwizdata[i_qwiz].spacing) {
            var spacing = qwizdata[i_qwiz].spacing + 'px';
            style += ' margin-left: ' + spacing + '; margin-bottom: ' + spacing + ';';
         }
      }
      m = attributes.match (/style\s*=\s*"[^"]*/m);
      if (m) {
         attributes = attributes.replace (/(style\s*=\s*"[^"]*)/m, '$1' + '; ' + style);
         attributes = attributes.replace (/;\s*;/g, ';');
      } else {
         attributes += ' style="' + style + '"';
      }
      attributes = attributes.replace (/align\s*=\s*"[^"]*"/, '');
      if (debug[0]) {
         console.log ('[create_qwiz_divs] attributes: ', attributes);
      }
   }
   top_html.push ('<div id="qwiz' + i_qwiz + '" class="qwiz visibilityhidden" ' + attributes + '>');
   top_html.push (   '<div id="overlay-exit-mobile-qwiz' + i_qwiz + '" class="overlay-exit-mobile-qwiz" onclick="' + qname + '.close_panel_exit_mobile(this)">');
   top_html.push (      '<div id="panel-exit-mobile-qwiz' + i_qwiz + '" class="panel-exit-mobile-qwiz">');
   top_html.push (         '<button onclick="' + qname + '.exit_mobile (' + i_qwiz + ')">');
   top_html.push (            'Back to page view');
   top_html.push (         '</button>');
   top_html.push (         '<br />');
   top_html.push (         '<span>');
   top_html.push (            '(To return to this full-screen view, tap ');
   top_html.push (            '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAIAAADZF8uwAAAAk0lEQVR4nI3QMQ6EIBAF0BG2YLiGtjRcgt7EcBfDhShtbLwBHIgCJrPFbrGJqPvrl/k/MzAzPOUFAMYYRCSiaZpijGckAAARSynM3BVf1FpTSkkpQwiXaBzHnLNzbtu2Lhr+GS4exSUyxqzrCgDLssDnBefM87zv+3EcRHS3yVpba0XElFK/znsvhNBal1LuLv3mDbu1OYLB67+mAAAAAElFTkSuQmCC" />');
   top_html.push (            ')');
   top_html.push (         '</span>');
   top_html.push (         '<div class="panel-icon-exit-mobile-qwiz"></div>');
   top_html.push (      '</div>');
   top_html.push (   '</div>');
   top_html.push (   '<div id="icon-exit-mobile-qwiz' + i_qwiz + '" class="icon-exit-mobile-qwiz" onclick="' + qname + '.open_panel_exit_mobile (' + i_qwiz + ')"></div>');
   var style = '';
   if (header_html == '' || header_html == 'NA' || header_html.indexOf ('Enter header text') != -1) {
      style = ' style="display: none;"';
   }
   top_html.push ('<div class="header-qwiz' + i_qwiz + ' qwiz-header qwiz_editable"' + style + '>');
   top_html.push (    header_html);
   top_html.push ('</div>');
   top_html = top_html.join ('\n');
   /*
   var learn_mode_title = T ('Learn mode: questions repeat until answered correctly.');
   var test_mode_title  = T ('Test mode: incorrectly-answered questions do not repeat.');
   var mode;
   var title;
   if (qwizdata[i_qwiz].repeat_incorrect_b) {
      mode = T ('Learn');
      title = learn_mode_title + ' ' + test_mode_title;
   } else {
      mode = T ('Test');
      title = test_mode_title + ' ' + learn_mode_title;
   }
   */
   var progress_div_html = [];
   progress_div_html.push ('<div class="qwiz-progress-container qwiz' + i_qwiz + '">');
   progress_div_html.push (   '<div class="go-mobile-qwiz go-mobile-qwiz' + i_qwiz + '" onclick="' + qname + '.go_mobile (' + i_qwiz + ')" title="Full-screen view">');
   progress_div_html.push (   '</div>');
   progress_div_html.push (   '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAKwmlDQ1BJQ0MgUHJvZmlsZQAASImVlwdUU9kWhs+96Y2WEAEpofcuXUroAZReRSUkgYQSY0JQsSviCCqKiAiWoQxVwUEpMhbEgm1QbNgnyKCiPgcLNlTeBYYw89567633r3XW/e6+++y9z1nnrLUvABQ8WyTKgJUAyBRmiSMCvBlx8QkM3O8AAligChSAHpsjETHDwkIAounn3/XhDuKN6KblRKx///5fpczlSTgAQGEIJ3MlnEyEjyHjNUckzgIAVY3Y9ZdliSb4IsI0MVIgwg8nOHWKRyY4eZLR6EmfqAgfhNUAwJPZbHEqAGQDxM7I5qQicci+CNsIuQIhwsg78ODw2VyEkbzAIjNzyQTLEDZJ/kuc1L/FTJbHZLNT5Ty1lknhfQUSUQZ7xf+5Hf9bmRnS6RxGyCDzxYERyJOO7Nnd9CXBchYmzw+dZgF30n+S+dLA6GnmSHwSppnL9g2Wz82YHzLNKQJ/ljxOFitqmnkSv8hpFi+JkOdKEfswp5ktnskrTY+W2/k8ljx+Dj8qdpqzBTHzp1mSHhk84+Mjt4ulEfL6ecIA75m8/vK1Z0r+sl4BSz43ix8VKF87e6Z+npA5E1MSJ6+Ny/P1m/GJlvuLsrzluUQZYXJ/XkaA3C7JjpTPzUIO5MzcMPkeprGDwqYZhIAAwADRIANkATFgIxwIkJOaxVs+cUaBzxLRCrEglZ/FYCK3jMdgCTlWFgw7GzsbACbu7NSReHd38i5CdPyMTYJ4uG5DjKIZ20IaAMf4AChwZmxGRch1JAFwNoEjFWdP2SauE8AAIlAENKAOtIE+MAGWwA44AjfgBfxAEAgFUSAeLAIcwAeZSOXLwCqwHuSBArAD7AZl4CCoAnXgMGgB7eAEOAMugCvgOrgNHgAZGAIvwQj4AMYgCMJBFIgKqUM6kCFkDtlBzpAH5AeFQBFQPJQEpUJCSAqtgjZCBVARVAZVQPXQz9Bx6Ax0CeqD7kED0DD0FvoCo2AyTIO1YCPYGnaGmXAwHAUvhFPhpXAOnAtvh0vhSvgQ3Aafga/At2EZ/BIeRQEUCUVH6aIsUc4oH1QoKgGVghKj1qDyUSWoSlQTqhPVg7qJkqFeoT6jsWgqmoG2RLuhA9HRaA56KXoNeiu6DF2HbkOfQ99ED6BH0N8xFIwmxhzjimFh4jCpmGWYPEwJpgbTijmPuY0ZwnzAYrF0rDHWCRuIjcemYVdit2L3Y5uxXdg+7CB2FIfDqePMce64UBwbl4XLw+3FHcKdxt3ADeE+4Ul4Hbwd3h+fgBfiN+BL8A34U/gb+Gf4MYISwZDgSgglcAkrCIWEakIn4RphiDBGVCYaE92JUcQ04npiKbGJeJ74kPiORCLpkVxI4SQBaR2plHSEdJE0QPpMViGbkX3IiWQpeTu5ltxFvkd+R6FQjChelARKFmU7pZ5ylvKY8kmBqmClwFLgKqxVKFdoU7ih8FqRoGioyFRcpJijWKJ4VPGa4islgpKRko8SW2mNUrnScaV+pVFlqrKtcqhypvJW5QblS8rPVXAqRip+KlyVXJUqlbMqg1QUVZ/qQ+VQN1KrqeepQzQszZjGoqXRCmiHab20EVUV1TmqMarLVctVT6rK6Ci6EZ1Fz6AX0lvod+hfZmnNYs7izdoyq2nWjVkf1Wareanx1PLVmtVuq31RZ6j7qaer71RvV3+kgdYw0wjXWKZxQOO8xqvZtNluszmz82e3zL6vCWuaaUZortSs0ryqOaqlrRWgJdLaq3VW65U2XdtLO027WPuU9rAOVcdDR6BTrHNa5wVDlcFkZDBKGecYI7qauoG6Ut0K3V7dMT1jvWi9DXrNeo/0ifrO+in6xfrd+iMGOgbzDFYZNBrcNyQYOhvyDfcY9hh+NDI2ijXabNRu9NxYzZhlnGPcaPzQhGLiabLUpNLklinW1Nk03XS/6XUz2MzBjG9WbnbNHDZ3NBeY7zfvs8BYuFgILSot+i3JlkzLbMtGywErulWI1QardqvX1gbWCdY7rXusv9s42GTYVNs8sFWxDbLdYNtp+9bOzI5jV253y55i72+/1r7D/s0c8zm8OQfm3HWgOsxz2OzQ7fDN0clR7NjkOOxk4JTktM+p35nmHOa81fmiC8bF22WtywmXz66OrlmuLa5/uFm6pbs1uD2fazyXN7d67qC7njvbvcJd5sHwSPL40UPmqevJ9qz0fOKl78X1qvF6xjRlpjEPMV9723iLvVu9P/q4+qz26fJF+Qb45vv2+qn4RfuV+T321/NP9W/0HwlwCFgZ0BWICQwO3BnYz9JicVj1rJEgp6DVQeeCycGRwWXBT0LMQsQhnfPgeUHzds17ON9wvnB+eygIZYXuCn0UZhy2NOyXcGx4WHh5+NMI24hVET2R1MjFkQ2RH6K8owqjHkSbREuju2MUYxJj6mM+xvrGFsXK4qzjVsddideIF8R3JOASYhJqEkYX+C3YvWAo0SExL/HOQuOFyxdeWqSxKGPRycWKi9mLjyZhkmKTGpK+skPZlezRZFbyvuQRjg9nD+cl14tbzB3mufOKeM9S3FOKUp6nuqfuSh3me/JL+K8EPoIywZu0wLSDaR/TQ9Nr08czYjOaM/GZSZnHhSrCdOG5JdpLli/pE5mL8kSypa5Ldy8dEQeLaySQZKGkI4uGNEdXpSbSTdKBbI/s8uxPy2KWHV2uvFy4/OoKsxVbVjzL8c/5aSV6JWdl9yrdVetXDaxmrq5YA61JXtO9Vn9t7tqhdQHr6tYT16ev/3WDzYaiDe83xm7szNXKXZc7uClgU2OeQp44r3+z2+aDP6B/EPzQu8V+y94t3/O5+ZcLbApKCr5u5Wy9vM12W+m28e0p23sLHQsP7MDuEO64s9NzZ12RclFO0eCuebvaihnF+cXvdy/efalkTsnBPcQ90j2y0pDSjr0Ge3fs/VrGL7td7l3evE9z35Z9H/dz99844HWg6aDWwYKDX34U/Hi3IqCirdKosqQKW5Vd9bQ6prrnJ+ef6ms0agpqvtUKa2V1EXXn6p3q6xs0Gwob4UZp4/ChxEPXD/se7miybKpopjcXHAFHpEde/Jz0852W4Jbuo85Hm44ZHtvXSm3Nb4PaVrSNtPPbZR3xHX3Hg453d7p1tv5i9UvtCd0T5SdVTxaeIp7KPTV+Ouf0aJeo69WZ1DOD3Yu7H5yNO3vrXPi53vPB5y9e8L9wtofZc/qi+8UTl1wvHb/sfLn9iuOVtqsOV1t/dfi1tdext+2a07WO6y7XO/vm9p264XnjzE3fmxdusW5duT3/dt+d6Dt3+xP7ZXe5d5/fy7j35n72/bEH6x5iHuY/UnpU8ljzceVvpr81yxxlJwd8B64+iXzyYJAz+PJ3ye9fh3KfUp6WPNN5Vv/c7vmJYf/h6y8WvBh6KXo59irvH8r/2Pfa5PWxP7z+uDoSNzL0Rvxm/O3Wd+rvat/Ped89Gjb6+EPmh7GP+Z/UP9V9dv7c8yX2y7OxZV9xX0u/mX7r/B78/eF45vi4iC1mT7YCKGTAKSkAvK0FgBIPAPU6AMQFUz31pKCp/4BJAv+Jp/ruSTkCgIQC0V0ATLRoVX+2tIrIe5gXAFFeALa3l48/JUmxt5uKRWpHWpOS8fF3SP+IMwXgW//4+Fj7+Pi3GqTY+wB0fZjq5SekjfxXLMADqHzTrZYB8K/6J1bAD27htQDfAAAACXBIWXMAABYlAAAWJQFJUiTwAAACBGlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS40LjAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczpleGlmPSJodHRwOi8vbnMuYWRvYmUuY29tL2V4aWYvMS4wLyIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iPgogICAgICAgICA8ZXhpZjpQaXhlbFlEaW1lbnNpb24+NDY4PC9leGlmOlBpeGVsWURpbWVuc2lvbj4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjQ2NjwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDx0aWZmOk9yaWVudGF0aW9uPjE8L3RpZmY6T3JpZW50YXRpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgrdRrnkAAADWklEQVRIDZ1VW08TQRT+uvRqCBoI5ckCD3JRjEALCfFNMJHfIH9B0V+BkIj6omgiEilPBiFyT+TRAAX1wQj0JpBIFYQaa6Db7WWcc8rWBKl2nWR2Z3bOfOf2nbMmfzAk1tfWYLfbkclkABN4KIqCpJZEs7sZ5eVOpFIpmExHh1mRwp53+/uFlMw7P3xcFRkhxGEiIVQtaXiaHQ4HW+LxtCAS2UZxcTHS6TS2IxG8np/HhfP1SEhPFJNSmMXHpJRMhowHYrEfUkEEaiKBcDgMr3cEl9vaEFcTx64Y2yp6zK1WG5wVFUglk4wwNPQMgVAYp+y2bG6M4eakFY6+3MbVOHZ3dqCqKmpqazE5MQGPTHAw/ImVUNj+ZyhWq5XvlZWVoae3F9FoFAG/Hw0NF/EzFkODzIHuicy1cR29fX3MIN/yirwvxIvRUd47nRVCesJrm80mAqGQSMvzQ9UYm/D23Xux6FtmcKIhUXJ0bIyBJf/FpcZGXkvTxe7evkhLgYO4WjBdQVYRaDyh5awjbS/Hxhn4rMvF74ePBvg8kUyxbKE1gd/AGltFIaBvNMZfTTD4wOMnIiWt0FLpHDjJHar/9sRElhzPHLWMoqIiULsIh0JwVVbCYrEgKSlM36ltyLzI4oOsG43ljmPo+xMV0KF0gGXsVgtkWHhPCgncIWvj294+Dg8OUFXpoijkVZK3/qmx0ZShYHBaUy1Q4UWj39F98waqqyqxHgjCYbPyWbalZY2jKLCRhSaLYk55iHzdEde7ujg35CjN1XU/pYzZRXKESSMpKYdCFRA1aSwsLjHouZoa0djUlFOkKyEiEOb07Kz4HPlSuAK6ROwiWk9OzzBwSUmJqD0qRt0TovzTwUE+f7OwaFyB7v7k1DSDyPYu6urreX2lvV303OnlNSn0rawYU6B7odfJ1JEnBFZXV5cDdrvdvF5c8om8LJKXThzEJhkFyh06O69hZm6O5YLBIFwuF6qqq+W/JZa9K+vEsAK6SfVANKR/VUfHVXTfus001TQNmxsboLeuwXy0MvQiD8xmM4N6h5/jwf17aGltRTwex+kzZ1BaWoqtra0sJsXV6KQcEFuGvSO5uEu0P9bEoryt4m8ukQfUkwKBALY2N7kv8R9Pxlwf0gK4PR78Ak4cQYObEn/YAAAAAElFTkSuQmCC" class="go-mobile-qwiz go-mobile-qwiz' + i_qwiz + '" />');
   progress_div_html.push (   '<div class="exit-mobile-qwiz exit-mobile-qwiz' + i_qwiz + '" onclick="' + qname + '.exit_mobile (' + i_qwiz + ')" title="Exit full-screen view">');
   progress_div_html.push (   '</div>');
   progress_div_html.push (   '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAAlwSFlzAAALEwAACxMBAJqcGAAAAVlpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDUuNC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KTMInWQAAAoVJREFUSA2lVj2PEkEY3plZV5I9Q3KBqzFHQkFJaCx0sTCxo6EnsfMPWPjBctqoueqMNf/CwtODxo7ySCxMaAjhgiRGiXjsh8+zzl5WKoZ7k+GdnZ33ed6PeWdRtVrtxnQ6jWxbvrUs8SCO449cq1Qqst1uWxBVr9fFaDSKW62WouaikTiO0xVCxBy2bXdpXC6Xb5ZKpZzv+xKkIp/PH3JuBMzN9FyD/4Fea5IjvmMk1FLKExAfc24sMH6tCQgeYgSa5BXB4MAz/ewbg6cG8M4niAZPotDPZ1ozdZ10v5GuVqsODTIkJGIkkQZn6q5qYwSOzbJYLEYsaBAEvlLqpQYQWq+h07leMlPS87wI4IIFhX4B8zMNGkAnRTaD/H+37Pf7PO+Xw+FwzYLidQPjUm9jBCSiRP+U4S+bJ44twaOoc570w+YcNUIjmkuSXzbRcrl8DPOfGOzUbEPR8z0QfFitVp8xNxNGsG2Hpo1nxmC4e1sSOo0DZO90BHO53H2cuIfw7RfGZjqJeWt/331/cfHjG+bmwoJvHoLss1LyHS9Ipl+ZwyeX3x0AeLD9re15EEIM9KrqhGH4BIQSV7vMhqf3bqVSOxu702bkldNH6o5Yp16v57CJd42ggQjuAZCec5CIchvpsyeTyWmhUFCLxcK6LgHTklyW0OwfAeK74JDz+fzUdd2UGK8MBACdTFGvrnSs8apPvyddQl4nAg8FfR5F0SOmhZ4DL4kCOsRzA9fPHkmMBXjHOIonNEwbD2tdHRWjSL4hIHhjDM4OPTjIH8JDwTn/GPB7QiCQpF9GfqB8ru2SInV+/vX7YDCwx+OxaDabAU8LCzqbzT7BaxcRfEEvPGV0fwEIA/zW345reQAAAABJRU5ErkJggg==" class="exit-mobile-qwiz exit-mobile-qwiz' + i_qwiz + '" />');
   /*
   progress_div_html.push (   '<div id="mode-qwiz' + i_qwiz + '" class="qwiz-mode" title="' + title + '">');
   progress_div_html.push (      'Mode: ' + mode);
   progress_div_html.push (   '</div>');
   */
   var n_questions = qwizdata[i_qwiz].n_questions;
   if (   (   n_questions > 1
           || qwizdata[i_qwiz].use_dataset
           || qwizdata[i_qwiz].use_dataset_questions_htm)
       && (! q.preview || q.preview_i_qwiz_plus1)        ) {
      var style = '';
      if (qwizdata[i_qwiz].hide_forward_back_b) {
         style = ' style="visibility: hidden;"';
      }
      var title;
      if (qwizdata[i_qwiz].use_dataset && qwizdata[i_qwiz].dataset_intro_f) {
         title = 'Go to &ldquo;Choose questions&rdquo;';
      } else {
         title = 'Go to first question';
      }
      progress_div_html.push ('<span class="bbfe-wrapper bbfe-wrapper' + i_qwiz + '">');
      progress_div_html.push (   '<span class="bbfe bbfe-qwiz' + i_qwiz + ' bck-question-qwiz' + i_qwiz + '"' + style + ' onclick="' + qname + '.bck_question (' + i_qwiz + ', true )" title="' + title + '">');
      progress_div_html.push (      '<span class="bar-up">|</span>&#x25c0;&#xFE0E;');
      progress_div_html.push (   '</span>');
      progress_div_html.push (   '<span class="bbfe bbfe-qwiz' + i_qwiz + ' bck-question-qwiz' + i_qwiz + '"' + style + ' onclick="' + qname + '.bck_question (' + i_qwiz + ', false)" title="Go to previous question">');
      progress_div_html.push (      '&#x25c0;&#xFE0E;');
      progress_div_html.push (   '</span>');
      progress_div_html.push (   '<span class="question-number-qwiz question-number-qwiz' + i_qwiz + '"' + style + '>');
      progress_div_html.push (   '</span>');
      progress_div_html.push (   '<span class="bbfe bbfe-qwiz' + i_qwiz + ' fwd-question-qwiz' + i_qwiz + '"' + style + ' onclick="' + qname + '.fwd_question (' + i_qwiz + ', false)" title="Go to next question">');
      progress_div_html.push (      '&#x25b6;&#xFE0E;');
      progress_div_html.push (   '</span>');
      progress_div_html.push (   '<span class="bbfe bbfe-qwiz' + i_qwiz + ' fwd-question-qwiz' + i_qwiz + '"' + style + ' onclick="' + qname + '.fwd_question (' + i_qwiz + ', true )" title="Go to most-recent question">');
      progress_div_html.push (      '&#x25b6;&#xFE0E;<span class="bar-up">|</span>');
      progress_div_html.push (   '</span>');
      progress_div_html.push ('</span>');
   }
   if (qwizdata[i_qwiz].qrecord_id) {
      progress_div_html.push ('<span class="response_recorded_wrapper response_recorded_wrapper-qwiz' + i_qwiz + '">');
      progress_div_html.push (   '<span class="response_recorded response_recorded-qwiz' + i_qwiz + '">');
      progress_div_html.push (      '&#x2714;&#xFE0E;');
      progress_div_html.push (   '</span>');
      progress_div_html.push (   '<span class="response_recorded response_recorded_shadow response_recorded_shadow-qwiz' + i_qwiz + '">');
      progress_div_html.push (      '&#x2714;&#xFE0E;');
      progress_div_html.push (   '</span>');
      progress_div_html.push ('</span>');
      var plugin_url = qqc.get_qwiz_param ('url', './');
      progress_div_html.push ('<div class="qwiz_icon_and_menu_container  lock_unlock qwiz' + i_qwiz + '">');
      progress_div_html.push (   '<div id="locked-qwiz' + i_qwiz + '" class="qwiz-locked qwiz_menu_icon">');
      progress_div_html.push (      '<img src="' + plugin_url + '/images/icon_locked.png" />');
      progress_div_html.push (   '</div>');
      progress_div_html.push (   '<div id="unlocked-qwiz' + i_qwiz + '" class="qwiz-unlocked qwiz_menu_icon">');
      progress_div_html.push (      '<img src="' + plugin_url + '/images/icon_unlocked.png" />');
      progress_div_html.push (   '</div>');
      progress_div_html.push (   '<div class="qwiz_icon_trigger_and_menu qwiz-hover">');
      progress_div_html.push (      '<div class="qwiz_icon_trigger">');
      progress_div_html.push (      '</div>');
      progress_div_html.push (      '<div id="pay_unlock_menu-qwiz' + i_qwiz + '" class="qwiz-pay_unlock_menu qwiz_menu">');
      progress_div_html.push (      '</div>');
      progress_div_html.push (   '</div>');
      progress_div_html.push ('</div>');
      var addclass = '';
      if (q.no_intro_b[i_qwiz] || n_questions == 1) {
         addclass = ' qwiz-usermenu_icon_no_intro';
      }
      progress_div_html.push ('<div class="qwiz_icon_and_menu_container qwiz' + i_qwiz + '">');
      progress_div_html.push (   '<div class="qwiz-usermenu_icon qwiz_menu_icon' + addclass + '">');
      progress_div_html.push (      '&#x25bc;');
      progress_div_html.push (   '</div>');
      progress_div_html.push (   '<div class="qwiz_icon_trigger_and_menu qwiz-hover">');
      progress_div_html.push (      '<div class="qwiz_icon_trigger" style="left: -12px; top: -4px;">');
      progress_div_html.push (      '</div>');
      progress_div_html.push (      '<div id="usermenu-qwiz' + i_qwiz + '" class="qwiz-usermenu qwiz_menu">');
      progress_div_html.push (      '</div>');
      progress_div_html.push (   '</div>');
      progress_div_html.push ('</div>');
   }
   style = '';
   if (qwizdata[i_qwiz].hide_progress_b) {
      style = ' style="display: none;"';
   }
   progress_div_html.push (   '<div id="progress-qwiz' + i_qwiz + '" class="qwiz-progress"' + style + '>');
   progress_div_html.push (   '</div>');
   progress_div_html.push (   '<div style="clear: both;"></div>');
   progress_div_html.push ('</div>');
   progress_div_html = progress_div_html.join ('\n');
   var login_div = '';
   if (qwizdata[i_qwiz].qrecord_id || qwizdata[i_qwiz].use_dataset) {
      login_div =  '<div id="qwiz_login-qwiz' + i_qwiz + '" class="qwiz-login">\n'
                 + '</div>';
   }
   var bottom_html = [];
   if (   n_questions > 1
       || qwizdata[i_qwiz].use_dataset
       || qwizdata[i_qwiz].use_dataset_questions_htm || q.qwizard_b) {
      if (exit_html) {
         if (exit_html.indexOf ('[unpaid') != -1 && exit_html.indexOf ('[/unpaid]') != -1) {
            exit_html = exit_html.replace ('[unpaid]', '<span class="unpaid_msg">');
            exit_html = exit_html.replace ('[/unpaid]', '</span>');
         } else {
            exit_html += '<span class="unpaid_msg_payment_type unpaid_msg"></span>';
         }
      } else {
         exit_html += '<span class="unpaid_msg_payment_type unpaid_msg"></span>';
      }
      if (qwizdata[i_qwiz].use_dataset && ! q.preview) {
         if (exit_html.indexOf ('[restart') == -1) {
            exit_html += '<br />[restart]';
         }
      }
      if (exit_html) {
         exit_html = create_restart_button (i_qwiz, exit_html);
      }
      if (q.qwizard_b) {
         exit_html = qqc.shortcodes_to_video_elements (exit_html);
      }
      if (qwizdata[i_qwiz].summary_b) {
         bottom_html.push (create_summary_report_div (i_qwiz, exit_html));
      }
   } else {
      if (n_questions == 1 && exit_html) {
         exit_html = create_restart_button (i_qwiz, exit_html);
         bottom_html.push ('<div class="single-question_exit">');
         bottom_html.push (   exit_html);
         bottom_html.push ('</div>');
      }
      if (! qwizdata[i_qwiz].qwizzled_b && ! qwizdata[i_qwiz].qrecord_id && ! qqc.is_mobile () && ! q.qwizard_b) {
         progress_div_html = '';
      }
   }
   bottom_html.push ('<div class="next_button" id="next_button-qwiz' + i_qwiz + '">\n');
   bottom_html.push (   '<button class="qwiz_button" onclick="' + qname + '.next_question (' + i_qwiz + ')">');
   bottom_html.push (       '<span id="next_button_text-qwiz' + i_qwiz + '">');
   bottom_html.push (          T ('Start quiz'));
   bottom_html.push (       '</span>');
   bottom_html.push (   '</button>\n');
   bottom_html.push ('</div>\n');
   if (! qwizdata[i_qwiz].summary_b) {
      if (n_questions > 1 && exit_html) {
         bottom_html.push (create_summary_report_div (i_qwiz, exit_html));
      }
   }
   style = '';
   if (qqc.get_qwiz_param ('beta')) {
      style = 'style = "background: red;"';
   }
   if (! qwizdata[i_qwiz].hide_qwizcards_icon_b) {
      bottom_html.push ('<div class="icon_qwiz" id="icon_qwiz' + i_qwiz + '" ' + style + '>');
      var icon_qwiz = qqc.get_qwiz_param ('icon_qwiz');
      if (icon_qwiz != 'Not displayed') {
         if (icon_qwiz != 'Icon only') {
            bottom_html.push ('<a href="mailto:support@qwizcards.com" style="border: none; box-shadow: none;">');
         }
         var title = 'Questions, comments, suggestions? support@qwizcards.com';
         bottom_html.push ('<img class="icon_qwiz" style="border: none;" title="' + title + '" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAUCAIAAAALACogAAAABnRSTlMA/wD/AP83WBt9AAAACXBIWXMAAA7EAAAOxAGVKw4bAAABP0lEQVR4nGP8//8/AymAiSTV5GhgwSZ4rcRrxRooW3futlBnJDlGND/cXzXVccFLVP0oepiwqtZJyH2wrenBtogQBgYGhsv9q15j9cO1qTDVW8JEGRgYGBi0PJ0YGBgYrjzCpuH+qv1rGBgYGHQLoaoZGBgYlOTEGRgYGB68uY+h4fXuQy8ZGBgYnLSRvXjv0UsGBgYGBRFFdA1Prm+6x8DAwBBio4XsyO37GBgYGHTkEHaixYO4mszrWTl1CjmH7iMcKe5nhdAAi4cnL6/A3HbrHgMDw56pJ0QYIOHr5JgmgzASZoOFdggDAwPDy03HRCEhs6YJEne6c0uQHYkUcXt76pL3oTqQQbxqVjay8Sh+cC5pmuuEpkFMWQZNBCNpwMDrWTmT2+5hCCu54EqtomkVLjqYwgoiuGzACWifgQDhK2rq5bcX2gAAAABJRU5ErkJggg==" />');
         if (icon_qwiz != 'Icon only') {
            bottom_html.push ('</a>');
         }
      }
   }
   bottom_html.push ('</div>');
   htm = top_html + progress_div_html + login_div
         + htm + bottom_html.join ('')
         + '</div>\n';  // This qwiz closing div.
   return htm;
}
function create_summary_report_div (i_qwiz, exit_html) {
   bottom_html = [];
   bottom_html.push ('<div id="summary-qwiz' + i_qwiz + '" class="qwiz-summary">\n');
   bottom_html.push (   '<div id="summary_report-qwiz' + i_qwiz + '">');
   bottom_html.push (   '</div>\n');
   bottom_html.push (   '<div id="qwiz_exit-qwiz' + i_qwiz + '" class="qwiz-exit qwiz_editable">');
   bottom_html.push (       exit_html);
   bottom_html.push (   '</div>\n');
   bottom_html.push (   '<button class="summary_exit_mobile_qwiz" onclick="' + qname + '.exit_mobile (' + i_qwiz + ')">\n');
   bottom_html.push (      'Return to page view');
   bottom_html.push (   '</button>\n');
   bottom_html.push ('</div>\n');
   return bottom_html.join ('');
}
function get_login_html (i_qwiz, add_team_member_f, msg, proceed_to_pay_f) {
   add_team_member_f = add_team_member_f ? 1 : 0;
   proceed_to_pay_f  = proceed_to_pay_f  ? 1 : 0;
   var onfocus = 'onfocus="jQuery (\'#qwiz_login-qwiz' + i_qwiz + ' p.login_error\').hide ()"';
   var login_div_html = '<p>';
   if (msg) {
      login_div_html += '<strong>' + msg + '</strong>';
   } else if (add_team_member_f) {
      login_div_html += '<strong>' + T ('Add team member') + '</strong>';
   } else {
      login_div_html += '<strong>' + T ('Record score/credit?') + '</strong>';
   }
   login_div_html += '</p>';
   login_div_html +=
      '<form action="nada" onSubmit="return qwiz_.login (' + i_qwiz + ', ' + add_team_member_f + ', ' + proceed_to_pay_f + ')">\n'
     +   '<table border="0" align="center" width="100%">'
     +      '<tr>'
     +         '<td>'
     +            '<label for="qwiz_username-qwiz' + i_qwiz + '">'+ T ('Login name') + '</label>'
     +         '</td>'
     +         '<td>'
     +            '<input type="text" id="qwiz_username-qwiz' + i_qwiz + '" ' + onfocus + ' />'
     +         '</td>'
     +      '</tr>'
     +      '<tr>'
     +         '<td>'
     +            '<label for="qwiz_password-qwiz' + i_qwiz + '">'+ T ('Password') + '</label>'
     +         '</td>'
     +         '<td>'
     +            '<input type="password" id="qwiz_password-qwiz' + i_qwiz + '" />'
     +         '</td>'
     +      '</tr>'
     +      '<tr>'
     +         '<td style="text-align: right;">'
     +            '<span class="qwiz-remember" title="' + T ('Save preference (do not use on shared computer)') + '"><label><span><input type="checkbox" /></span>&nbsp;' + T ('Remember') + '</label></span>'
     +         '</td>'
     +         '<td>'
     +            '<button type="submit" class="qwiz_button">'
     +               T ('Student login')
     +            '</button>'
     +            '&ensp;';
   if (! add_team_member_f) {
      login_div_html +=
                  '<span class="qwiz_button" onclick="qwiz_qcards_common.create_register_taker_screen (\'' + qname + '\', ' + i_qwiz + ', ' + proceed_to_pay_f + ')">'
     +               T ('New student - register')
     +            '</span>'
     +            '&ensp;';
   }
   login_div_html +=
                  '<span class="qwiz_login_cancel_no_thanks qwiz_button" onclick="' + qname + '.no_login (' + i_qwiz + ',' + add_team_member_f + ')">';
   if (add_team_member_f) {
      login_div_html +=
                     T ('Cancel');
   } else {
      login_div_html +=
                    T ('No thanks');
   }
   login_div_html +=
                 '</span>'
     +         '</td>'
     +      '</tr>';
   if (! add_team_member_f) {
      login_div_html +=
            '<tr>'
     +         '<td>'
     +         '</td>'
     +         '<td class="qwiz-smaller">'
     +            '<a href="' + qqc.get_qwiz_param ('server_loc', 'http://qwizcards.com/admin') + '/password_reset_request" target="_blank">'
     +               T ('Forgot password?') + '</a>'
     +         '</td>'
     +      '</tr>'
   }
   var register_page = 'new_account';
   if (window.location.href.indexOf ('sciencemusicvideos.com') != -1) {
      register_page = 'new_account_smv';
   }
   login_div_html +=
             '<tr>'
     +          '<td colspan="2">'
     +             '<hr style="margin: 5px;">'
     +          '</td>'
     +       '</tr>'
     +       '<tr>'
     +          '<td colspan="2" class="qwiz-center">'
     +             '<b>Teachers: track your students&rsquo; progress on quizzes and flashcards.&nbsp; '
     +                '<a href="' + qqc.get_qwiz_param ('secure_server_loc', 'https://qwizcards.com/admin') + '/' + register_page + '" target="_blank">'
     +                'Create&nbsp;teacher&nbsp;administrative&nbsp;account</a></b>'
     +          '</td>'
     +       '</tr>'
     +    '</table>\n'
     + '</form>'
     + '<p class="login_error">'
     +     T ('Login incorrect. Please try again')
     + '</p>\n';
   return login_div_html;
}
this.qwiz_password_focus = function (el, i_qwiz) {
   el.qwiz_pw = '';
   el.value = '';
   $ ('#qwiz_login-qwiz' + i_qwiz + ' p.login_error').hide ();
}
function create_restart_button (i_qwiz, htm, feedback_f) {
   var restart = htm.match (/\[restart[^\]]*\]/);
   if (restart) {
      var label;
      if (feedback_f || qwizdata[i_qwiz].n_questions == 1) {
         label = T ('Do this question again');
      } else {
         if (qwizdata[i_qwiz].use_dataset && qwizdata[i_qwiz].dataset_intro_f) {
            label = T ('Practice more questions');
         } else {
            label = T ('Take this quiz again');
         }
      }
      var attr = qqc.replace_smart_quotes (restart[0]);
      var custom_label = get_attr (attr, 'label');
      if (custom_label) {
         label = custom_label;
      }
      var restart_redo = feedback_f ? 'redo_question' : 'restart_quiz' ;
      var restart_button_html =
                       '<button class="qwiz_button qwiz_restart" onclick="' + qname + '.' + restart_redo + ' (' + i_qwiz + ')">'
                     +    label
                     + '</button>';
      htm = htm.replace (restart, restart_button_html);
   }
   return htm;
}
function create_bg_img_style (i_qwiz, i_question) {
   var style = '';
   var bg_img = qwizdata[i_qwiz].bg_img[i_question];
   if (bg_img) {
      var top    = bg_img.top    ? bg_img.top    + 'px' : '0';
      var left   = bg_img.left   ? bg_img.left   + 'px' : '0';
      var width  = bg_img.width  ? bg_img.width  + 'px' : 'auto';
      var height = bg_img.height ? bg_img.height + 'px' : 'auto';
      var style = ' style="background: no-repeat ' + left + ' ' + top
                                       + ' / ' + width + ' ' + height
                                       + ' url(' + bg_img.src + ')"';
      if (debug[0]) {
         console.log ('[create_bg_img_style] style:', style);
      }
   }
   return style;
}
function process_question_attributes (i_qwiz, i_question, question_shortcode, i_qwizard_question) {
   if (set_qwizard_data_b) {
      if (typeof (i_qwizard_question) == 'undefined') {
         i_qwizard_question = i_question;
      }
      qw.questions_cards[i_qwizard_question] = {};
   }
   qwizdata[i_qwiz].dataset_id[i_question] = i_question;
   qwizdata[i_qwiz].unit[i_question] = qwizdata[i_qwiz].default_unit;
   var m = question_shortcode.match (/\[(<code><\/code>)*q\s*([^\]]*)\]/m);
   var attributes = m[2];
   if (attributes) {
      attributes = qqc.replace_smart_quotes (attributes);
      if (set_qwizard_data_b) {
         qw.questions_cards[i_qwizard_question].question_attributes = attributes;
      }
      if (q.qwizard_b) {
         qwizdata[i_qwiz].qwizard_multiple_choice_b[i_question] = get_attr (attributes, 'multiple_choice') == 'true';
      }
      var question_topics = get_attr (attributes, 'topic', true);
      if (! question_topics) {
         question_topics = get_attr (attributes, 'unit', true);
      }
      if (question_topics) {
         if (debug[4]) {
            console.log ('[process_question_attributes] question_topics: ', question_topics);
         }
         if (set_qwizard_data_b) {
            qw.questions_cards[i_qwizard_question].topic = question_topics;
         }
         question_topics = question_topics.split (/; */);
         for (var i=0; i<question_topics.length; i++) {
            question_topics[i] = question_topics[i].replace (/\s/g, '_');
            var topic = question_topics[i];
            if (qwizdata[i_qwiz].topics.indexOf (topic) == -1) {
               qwizdata[i_qwiz].topics.push (topic);
            }
         }
         qwizdata[i_qwiz].question_topics[i_question] = question_topics;
      }
      var dataset_id = get_attr (attributes, 'dataset_id');
      if (dataset_id) {
         qwizdata[i_qwiz].dataset_id[i_question] = dataset_id;
      }
      var unit = get_attr (attributes, 'unit');
      if (unit) {
         qwizdata[i_qwiz].unit[i_question] = unit;
         if (set_qwizard_data_b) {
            qw.questions_cards[i_qwizard_question].unit = unit;
         }
         if (qwizdata[i_qwiz].units.indexOf (unit) == -1) {
            qwizdata[i_qwiz].units.push (unit);
         }
      }
      var use_dataset_question_id = get_attr (attributes, 'use_dataset_question');
      if (use_dataset_question_id) {
         qwizdata[i_qwiz].use_dataset_question_ids[i_question] = use_dataset_question_id;
         qwizdata[i_qwiz].dataset_id[i_question]               = use_dataset_question_id;
         if (set_qwizard_data_b) {
            qw.questions_cards[i_qwizard_question].from_dataset_b = true;
         }
      }
      var bg_img_src = get_attr (attributes, 'bg_img_src');
      if (bg_img_src) {
         var bg_img = {};
         bg_img.src    = bg_img_src;
         bg_img.left   = get_attr (attributes, 'bg_img_left');
         bg_img.top    = get_attr (attributes, 'bg_img_top');
         bg_img.width  = get_attr (attributes, 'bg_img_width');
         bg_img.height = get_attr (attributes, 'bg_img_height');
         qwizdata[i_qwiz].bg_img[i_question] = bg_img;
         if (set_qwizard_data_b) {
            qw.questions_cards[i_qwizard_question].bg_img = bg_img;
         }
      }
   }
   return question_topics;
}
function check_questions_have_topics (i_qwiz) {
   var add_other_b = false;
   for (var i_question=0; i_question<qwizdata[i_qwiz].n_questions; i_question++) {
      if (! qwizdata[i_qwiz].information_question_b[i_question]) {
         if (! qwizdata[i_qwiz].question_topics[i_question]) {
            qwizdata[i_qwiz].question_topics[i_question] = ['Other'];
            add_other_b = true;
         }
      }
   }
   if (add_other_b) {
      if (qwizdata[i_qwiz].topics.indexOf ('Other') == -1) {
         qwizdata[i_qwiz].topics.push ('Other');
      }
   }
   if (debug[4]) {
      console.log ('[check_questions_have_topics] qwizdata[i_qwiz].question_topics:', qwizdata[i_qwiz].question_topics);
   }
   qwizdata[i_qwiz].topic_statistics = {};
   var n_topics = qwizdata[i_qwiz].topics.length;
   for (var i_topic=0; i_topic<n_topics; i_topic++) {
      var topic = qwizdata[i_qwiz].topics[i_topic];
      qwizdata[i_qwiz].topic_statistics[topic] = {};
      qwizdata[i_qwiz].topic_statistics[topic].n_correct = 0;
      qwizdata[i_qwiz].topic_statistics[topic].n_incorrect = 0;
   }
}
this.restart_quiz = function (i_qwiz) {
   var $summary = $ ('#summary-qwiz' + i_qwiz);
   $summary.hide ();
   $summary.find ('button.summary_exit_mobile_qwiz').hide ();
   $ ('#qwiz' + i_qwiz + ' div.show_answer_got_it_or_not').hide ();
   if (qwizdata[i_qwiz].n_questions == 1) {
      $( '#qwiz' + i_qwiz + ' div.single-question_exit').hide ();
   }
   qwizdata[i_qwiz].n_correct = 0;
   qwizdata[i_qwiz].n_incorrect = 0;
   if (qwizdata[i_qwiz].use_dataset) {
      qwizdata[i_qwiz].information_question_b = {};
      qwizdata[i_qwiz].hangman = {};
      qwizdata[i_qwiz].textentry = '';
   }
   q.display_progress (i_qwiz);
   for (var qwizzled_div_id in qwizdata[i_qwiz].$qwizzled) {
      $ ('div#' + qwizzled_div_id).replaceWith (qwizdata[i_qwiz].$qwizzled[qwizzled_div_id]);
      qwizdata[i_qwiz].$qwizzled[qwizzled_div_id] = $ ('div#' + qwizzled_div_id).clone (true);
   }
   if (qwizdata[i_qwiz].qwizzled_b) {
      qwizdata[i_qwiz].correct_on_try1 = [];
   }
   $ ('#qwiz' + i_qwiz).find ('div.qwizq').hide ();
   var n_questions = qwizdata[i_qwiz].n_questions;
   for (var i_question=0; i_question<n_questions; i_question++) {
      qwizdata[i_qwiz].answered_correctly[i_question] = 0;
      qwizdata[i_qwiz].questions[i_question] = {};
   }
   if (! qwizdata[i_qwiz].hide_forward_back_b) {
      $ ('.bbfe-qwiz' + i_qwiz).css ({visibility: 'visible', color: 'lightgray'}).removeClass ('hover');
      $ ('span.question-number-qwiz' + i_qwiz).css ({visibility: 'visible'}).html (1);
   }
   var n_topics = qwizdata[i_qwiz].topics.length;
   for (var i_topic=0; i_topic<n_topics; i_topic++) {
      var topic = qwizdata[i_qwiz].topics[i_topic];
      qwizdata[i_qwiz].topic_statistics[topic].n_correct = 0;
      qwizdata[i_qwiz].topic_statistics[topic].n_incorrect = 0;
   }
   qwizdata[i_qwiz].i_question           = -1;
   qwizdata[i_qwiz].i_user_question      = -1;
   qwizdata[i_qwiz].user_question_number = 0;
   if (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b) {
      qwizdata[i_qwiz].record_start_b = false;
      var data = {qrecord_id_ok: qwizdata[i_qwiz].qrecord_id_ok, type: 'start', confirm: 'js'};
      qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
   }
   q.next_question (i_qwiz, true);
};
this.redo_question = function (i_qwiz) {
   if (qwizdata[i_qwiz].n_questions == 1) {
      $( '#qwiz' + i_qwiz + ' div.single-question_exit').hide ();
   }
   qwizdata[i_qwiz].i_question = qwizdata[i_qwiz].i_question - 1;
   qwizdata[i_qwiz].answered_correctly[0] = 0;
   qwizdata[i_qwiz].n_correct = 0;
   q.next_question (i_qwiz, true);
}
this.next_question = function (i_qwiz, no_login_b, simple_go_f) {
   if (debug[0]) {
      console.log ('[next_question] qwizdata[i_qwiz].i_question:', qwizdata[i_qwiz].i_question);
   }
   simple_go_f = !! simple_go_f;
   if (qwizdata[i_qwiz].bck_f) {
      q.fwd_question (i_qwiz, false);
      return;
   }
   var i_question = qwizdata[i_qwiz].i_question;
   if (i_question == -1) {
      if (! qwizdata[i_qwiz].use_dataset || ! qwizdata[i_qwiz].dataset_intro_f) {
         $ ('.bbfe-qwiz' + i_qwiz).css ({visibility: 'visible', color: 'lightgray'}).removeClass ('hover');
      }
   }
   var n_questions = qwizdata[i_qwiz].n_questions;
   if (debug[0]) {
      console.log ('[next_question] i_question: ', i_question, ', n_questions: ', n_questions);
   }
   var qwiz_id = 'qwiz' + i_qwiz;
   var $qwiz = $ ('#' + qwiz_id);
   if (document_qwiz_mobile) {
      $qwiz.css ('width', '');
   } else {
      if (qwizdata[i_qwiz].width_reset) {
         if ($ ('#xqwiz' + i_qwiz).length) {
            $qwiz.css ({width: qwizdata[i_qwiz].initial_width + 'px', 'max-width': ''});
         } else {
            $qwiz.css ({width: '', 'max-width': ''});
         }
         $qwiz.css ({transform: ''});
         $qwiz[0].qscale_fac  = '';
         $qwiz[0].qstart_left = '';
         $qwiz[0].qstart_top  = '';
         qwizdata[i_qwiz].width_reset = false;
      }
   }
   var start_quiz_b = false;
   if (i_question == -1 && ! simple_go_f) {
      if (qwizdata[i_qwiz].use_dataset || qwizdata[i_qwiz].use_dataset_questions_htm || n_questions > 1 || q.qwizard_b) {
         var i_user_question = -1;
         if (! q.no_intro_b[i_qwiz] || qwizdata[i_qwiz].use_dataset_questions_htm) {
            start_quiz_b = true;
            if (! no_login_b && ! q.qwizard_b) {
               if (qwizdata[i_qwiz].qrecord_id) {
                  var user_logged_in_b
                     =    typeof (document_qwiz_user_logged_in_b) != 'undefined'
                                               && document_qwiz_user_logged_in_b
                       && typeof (document_qwiz_username) != 'undefined';
                  if (   user_logged_in_b
                      || (   typeof (document_qwiz_declined_login_b) != 'undefined'
                          && document_qwiz_declined_login_b)) {
                     if (user_logged_in_b) {
                        var check_team_b = true;
                        if (! $.cookie ('qwiz_current_login_lt_nmin_ago')) {
                           check_team_b = false;
                           var a_team = '';
                           if (document_qwiz_team_b) {
                              a_team = ' ' + T ('a team') + ':';
                           }
                           if (confirm (T ('You are logged in as') + a_team + ' ' + document_qwiz_username + '.\n' + T ('Do you want to continue?  (Click "Cancel" to sign out)'))) {
                              var login_timeout_min = qqc.get_qwiz_param ('login_timeout_min', 40);
                              var options = {path:    '/',
                                             expires: login_timeout_min/(24.0*60.0)};
                              $.cookie ('qwiz_current_login_lt_nmin_ago', 1, options);
                           } else {
                              qqc.sign_out ();
                              document_qwiz_user_logged_in_b = false;
                           }
                        }
                        if (check_team_b && document_qwiz_team_b) {
                           if (! confirm (T ('You are logged in as team') + ': ' + document_qwiz_username + '.\n' + T ('Do you want to continue as this team?'))) {
                              document_qwiz_session_id = document_qwiz_session_id.split (';')[0];
                              document_qwiz_username   = document_qwiz_username.split ('; ')[0];
                              document_qwiz_team_b     = false;
                              qqc.set_user_menus_and_icons ();
                              var msg = T ('OK.  Only %s is logged in now');
                              msg = msg.replace ('%s', document_qwiz_username);
                              alert (msg);
                           }
                        }
                        qwizdata[i_qwiz].record_start_b = false;
                        if (document_qwiz_user_logged_in_b) {
                           var data = {qrecord_id_ok: qwizdata[i_qwiz].qrecord_id_ok, type: 'start', confirm: 'js'};
                           qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
                        }
                     }
                  } else {
                     q.display_login (i_qwiz);
                     return false;
                  }
               }
            }
            if (qwizdata[i_qwiz].use_dataset || qwizdata[i_qwiz].use_dataset_questions_htm) {
               var dataset_intro_f = qwizdata[i_qwiz].dataset_intro_f;
               if (dataset_intro_f && dataset_intro_f != 'topics_only') {
                  q.display_login (i_qwiz, false, 'use_dataset_options');
               } else {
                  $ ('.intro-qwiz' + i_qwiz).hide ();
                  qqc.get_dataset_questions (qwizdata[i_qwiz].use_dataset, qname,
                                             i_qwiz, qwizdata[i_qwiz].qrecord_id,
                                             [], [], 10000,
                                             qwizdata[i_qwiz].dataset_questions_to_do,
                                             qwizdata[i_qwiz].random_b,
                                             qwizdata[i_qwiz].use_dataset_questions_htm);
                  i_user_question = 0;
               }
            }
         }
         q.next_question_from_intro (i_qwiz, i_user_question);
      } else {
         $ ('#mode-' + qwiz_id).css ('visibility', 'hidden');
         /* DKTMP
         if (q.qwizard_b && ! q.no_intro_b[i_qwiz]) {
            $ ('#intro-' + qwiz_id).hide ();
         }
         */
      }
   } else {
      var qwizq_id = qwiz_id + '-q' + i_question;
      $ ('#' + qwizq_id).hide ();
      if (document_qwiz_mobile) {
         $ ('#mobile_' + qwizq_id).hide ();
      }
      if (qwizdata[i_qwiz].pay_quiz_deck_id
            && (   qwizdata[i_qwiz].pay_quiz_ok == 'preview_questions'
                || qwizdata[i_qwiz].pay_quiz_ok == 'preview_period_expired'
                || qwizdata[i_qwiz].pay_quiz_ok == 'no_free_trial')) {
         if (qqc.preview_limit ('qwiz', qwizdata, i_qwiz)) {
            return;
         }
      }
   }
   if (n_questions == 0) {
      if (debug[0]) {
         console.log ('[next_question] n_questions:', n_questions);
      }
      return;
   }
   if (! next_button_active_b) {
      $ ('#next_button-' + qwiz_id).hide ();
      qwizdata[i_qwiz].next_button_show_b = false;
   }
   if (i_question != -1 || simple_go_f) {
      if (qwizdata[i_qwiz].information_question_b[i_question]) {
         $ ('#next_button_text-qwiz' + i_qwiz).html (T ('Next question'));
         if (! q.qwizard_b) {
            qwizdata[i_qwiz].answered_correctly[i_question] = 1;
            qwizdata[i_qwiz].n_correct++;
            q.display_progress (i_qwiz);
            if     (qwizdata[i_qwiz].user_question_number == 1
                && (q.no_intro_b[i_qwiz]
                                        || qwizdata[i_qwiz].n_questions == 1)) {
               $ ('div#icon_qwiz' + i_qwiz).hide ();
               alert_not_logged_in (i_qwiz);
            }
            if (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b) {
               var data = {q_and_a_text:  btoa (encodeURIComponent (qwizdata[i_qwiz].q_and_a_text[i_question])),
                           q_and_a_crc32: qwizdata[i_qwiz].q_and_a_crc32[i_question],
                           i_question:    qwizdata[i_qwiz].dataset_id[i_question],
                           unit:          qwizdata[i_qwiz].unit[i_question],
                           type:          'information_only',
                           response:      'continue',
                           correct_b:     1,
                           confirm:       'js'};
               qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
            }
         }
      }
   }
   if (! (qwizdata[i_qwiz].use_dataset && i_question == -1)
                                            || (simple_go_f && ! q.qwizard_b)) {
      var n_done = qwizdata[i_qwiz].n_correct;
      if (! qwizdata[i_qwiz].repeat_incorrect_b) {
         n_done += qwizdata[i_qwiz].n_incorrect;
      }
      var n_questions_for_done = qwizdata[i_qwiz].n_questions_for_done;
      if (debug[0]) {
         console.log ('[next_question] n_done:', n_done, ', n_questions:', n_questions, ', n_questions_for_done:', n_questions_for_done);
      }
      if (n_done == n_questions_for_done) {
         var i_user_question = qwizdata[i_qwiz].i_user_question;
         qwizdata[i_qwiz].questions[n_questions] = {i_user_prev_question: i_user_question, user_question_number: '--'};
         qwizdata[i_qwiz].questions[i_user_question].i_user_next_question = n_questions;
         qwizdata[i_qwiz].i_user_question  = n_questions;
         qwizdata[i_qwiz].saved_i_question = n_questions;
         $ ('span.question-number-qwiz' + i_qwiz).html ('--');
         q.display_summary_and_exit (i_qwiz);
      } else {
         if (! q.qwizard_b && qwizdata[i_qwiz].random_b) {
            i_question = Math.floor (Math.random () * n_questions);
         }
         while (true) {
            i_question++;
            if (i_question >= n_questions) {
               i_question = 0;
            }
            if (qwizdata[i_qwiz].repeat_incorrect_b) {
               if (qwizdata[i_qwiz].answered_correctly[i_question] != 1) {
                  break;
               }
            } else {
               if (typeof qwizdata[i_qwiz].answered_correctly[i_question] == 'undefined'
                      || qwizdata[i_qwiz].answered_correctly[i_question] == 0) {
                  break;
               }
            }
         }
         var i_prev_question = qwizdata[i_qwiz].i_user_question;
         qwizdata[i_qwiz].i_question = i_question;
         if (typeof (qwizdata[i_qwiz].questions[i_question]) == 'undefined') {
            qwizdata[i_qwiz].questions[i_question] = {};
         }
         var question = qwizdata[i_qwiz].questions[i_question];
         question.i_user_prev_question = i_prev_question;
         if (i_prev_question == -1) {
            qwizdata[i_qwiz].i_first_user_question = i_question;
            if (debug[0]) {
               console.log ('[next_question] i_first_user_question:', i_question);
            }
         } else {
            var prev_question = qwizdata[i_qwiz].questions[i_prev_question];
            if (prev_question) {
               prev_question.i_user_next_question = i_question;
            } else {
               console.log ('[next_question] prev_question for i_prev_question', i_prev_question, 'does not exist');
            }
            if (! q.qwizard_b) {
               $ ('.bck-question-qwiz' + i_qwiz).css ({color: 'gray'}).addClass ('hover');
            }
         }
         qwizdata[i_qwiz].i_user_question = i_question;
         if (typeof question.user_question_number == 'undefined') {
            qwizdata[i_qwiz].user_question_number++;
            question.user_question_number = qwizdata[i_qwiz].user_question_number;
         }
         if (! q.qwizard_b) {
            $ ('span.question-number-qwiz' + i_qwiz).html (question.user_question_number);
         }
         q.display_question (i_qwiz, i_question, start_quiz_b);
         if (q.qwizard_b && n_questions) {
            qwizard.set_qwizard_data ('i_question', i_question);
            qwizard.go_to_question2 ();
            q.display_progress (i_qwiz);
         }
      }
   }
};
this.next_question_from_intro = function (i_qwiz, i_user_question) {
   if (! qwizdata[i_qwiz].n_questions) {
      return;
   }
   $ ('.intro-qwiz' + i_qwiz).hide ();
   if (! q.no_intro_b[i_qwiz]) {
      $ ('#icon_qwiz' + i_qwiz).hide ();
   }
   $ ('.bbfe-qwiz' + i_qwiz).css ({visibility: 'visible', color: 'lightgray'}).removeClass ('hover');
   $ ('span.question-number-qwiz' + i_qwiz).html (1);
   $ ('#next_button-qwiz' + i_qwiz).css ('text-align', 'left');
   if (! (qwizdata[i_qwiz].use_dataset || qwizdata[i_qwiz].use_dataset_questions_htm)) {
      q.display_progress (i_qwiz, true);
   }
   $ ('#next_button_text-qwiz' + i_qwiz).html (T ('Next question'));
}
this.bck_question = function (i_qwiz, go_to_beg_f) {
   if (! $ ('.bck-question-qwiz' + i_qwiz).hasClass ('hover')) {
      return;
   }
   qwizdata[i_qwiz].bck_f = true;
   var i_user_question = qwizdata[i_qwiz].i_user_question;
   var i_current_user_question = i_user_question;
   if (go_to_beg_f) {
      if (qwizdata[i_qwiz].use_dataset && qwizdata[i_qwiz].dataset_intro_f) {
         qwizdata[i_qwiz].saved_i_question = qwizdata[i_qwiz].i_question;
         $ ('.bck-question-qwiz' + i_qwiz).css ({color: 'lightgray'}).removeClass ('hover');
         $ ('.fwd-question-qwiz' + i_qwiz).css ({color: 'gray'}).addClass ('hover');
         $ ('span.question-number-qwiz' + i_qwiz).html ('--');
         hide_current_question (i_qwiz, i_current_user_question);
         qwizdata[i_qwiz].i_question = -1;
         q.display_login (i_qwiz, false, 'use_dataset_options');
         return;
      } else {
         i_user_question = qwizdata[i_qwiz].i_first_user_question;
      }
   } else {
      i_user_question = qwizdata[i_qwiz].questions[i_user_question].i_user_prev_question;
      if (i_user_question == -1) {
         return;
      }
   }
   hide_current_question (i_qwiz, i_current_user_question);
   $ ('div#summary-qwiz' + i_qwiz).hide ();
   qwizdata[i_qwiz].i_user_question = i_user_question;
   var question = qwizdata[i_qwiz].questions[i_user_question];
   if (go_to_beg_f || question.i_user_prev_question == -1) {
      var $bck = $ ('.bck-question-qwiz' + i_qwiz);
      if (qwizdata[i_qwiz].use_dataset && qwizdata[i_qwiz].dataset_intro_f) {
         $bck = $bck.last ();
      }
      $bck.css ({color: 'lightgray'}).removeClass ('hover');
   }
   var user_question_number = question.user_question_number;
   $ ('span.question-number-qwiz' + i_qwiz).html (user_question_number);
   $ ('.fwd-question-qwiz' + i_qwiz).css ({color: 'gray'}).addClass ('hover');
   qwizq_id = 'qwiz' + i_qwiz + '-q' + i_user_question;
   $ ('#' + qwizq_id).show ();
   if (document_qwiz_mobile) {
      $ ('#mobile_' + qwizq_id).show ();
   }
}
this.fwd_question = function (i_qwiz, go_to_end_f) {
   if (! $ ('.fwd-question-qwiz' + i_qwiz).hasClass ('hover')) {
      return;
   }
   if (qwizdata[i_qwiz].i_question == -1) {
      $ ('#qwiz_login-qwiz' + i_qwiz).hide ();
   } else {
      var i_user_question = qwizdata[i_qwiz].i_user_question;
      var qwizq_id = 'qwiz' + i_qwiz + '-q' + i_user_question;
      $ ('#' + qwizq_id).hide ();
      if (document_qwiz_mobile) {
         $ ('#mobile_' + qwizq_id).hide ();
      }
   }
   if (go_to_end_f) {
      if (qwizdata[i_qwiz].i_question == -1) {
         qwizdata[i_qwiz].i_question = qwizdata[i_qwiz].saved_i_question;
      }
      i_user_question = qwizdata[i_qwiz].i_question;
   } else {
      if (qwizdata[i_qwiz].i_question == -1) {
         i_user_question = qwizdata[i_qwiz].i_first_user_question;
         qwizdata[i_qwiz].i_question = qwizdata[i_qwiz].saved_i_question;
      } else {
         i_user_question = qwizdata[i_qwiz].questions[i_user_question].i_user_next_question;
      }
   }
   qwizdata[i_qwiz].i_user_question = i_user_question;
   var question = qwizdata[i_qwiz].questions[i_user_question];
   if (i_user_question == qwizdata[i_qwiz].i_question) {
      qwizdata[i_qwiz].bck_f = false;
      $ ('.fwd-question-qwiz' + i_qwiz).css ({color: 'lightgray'}).removeClass ('hover');
   }
   var user_question_number = question.user_question_number;
   $ ('span.question-number-qwiz' + i_qwiz).html (user_question_number);
   if (i_user_question == qwizdata[i_qwiz].n_questions) {
      $ ('div#summary-qwiz' + i_qwiz).show ();
   } else {
      qwizq_id = 'qwiz' + i_qwiz + '-q' + i_user_question;
      $ ('#' + qwizq_id).show ();
      if (document_qwiz_mobile) {
         $ ('#mobile_' + qwizq_id).show ();
      }
      if (! qwizdata[i_qwiz].summary_b) {
         if (user_question_number == qwizdata[i_qwiz].n_questions) {
            $ ('div#summary-qwiz' + i_qwiz).show ();
         }
      }
   }
   var $bck = $ ('.bck-question-qwiz' + i_qwiz);
   if (question.i_user_prev_question == -1) {
      $bck = $bck.first ();
   }
   $bck.css ({color: 'gray'}).addClass ('hover');
}
function hide_current_question (i_qwiz, i_question) {
   var qwizq_id = 'qwiz' + i_qwiz + '-q' + i_question;
   $ ('#' + qwizq_id).hide ();
   if (document_qwiz_mobile) {
      $ ('#mobile_' + qwizq_id).hide ();
   }
}
this.display_question = function (i_qwiz, i_question, start_quiz_b) {
   var qwizq_id = 'qwiz' + i_qwiz + '-q' + i_question;
   var $qwizq = $ ('div#' + qwizq_id);
   if (debug[0]) {
      console.log ('[display_question] start_quiz_b:', start_quiz_b);
      console.log ('[display_question] $qwizq:', $qwizq);
   }
   $qwizq.find ('[id^=' + qwizq_id + '-a]').hide ();
   var $mobile_qwizq = $ ('div#mobile_' + qwizq_id);
   $mobile_qwizq.find ('[id^=mobile_' + qwizq_id + '-a]').hide ();
   var $qwiz_img = $qwizq.find ('input[name="qwiz_img"]');
   if ($qwiz_img.length) {
      $qwiz_img.changeElements ('img');
      $mobile_qwizq.find ('input[name="qwiz_img"]').changeElements ('img');
   }
   var qwizzled_b = $qwizq.hasClass ('qwizzled');
   if (qwizzled_b) {
      if (! qwizdata[i_qwiz].$qwizzled) {
         qwizdata[i_qwiz].$qwizzled = {};
      }
      if (typeof (qwizdata[i_qwiz].$qwizzled[qwizq_id]) == 'undefined') {
         if (q.qwizard_b) {
            q.init_qwizzled2 ($qwizq, i_qwiz, i_question);
         } else {
            q.init_qwizzled ($qwizq, i_qwiz, i_question);
         }
      }
      qwizdata[i_qwiz].n_labels_correct = 0;
      qwizdata[i_qwiz].n_label_attempts = 0;
      if (qwizdata[i_qwiz].answered_correctly[i_question] == -1) {
         $qwizq.replaceWith (qwizdata[i_qwiz].$qwizzled[qwizq_id]);
         var $qwizq = $ ('div#' + qwizq_id);
         qwizdata[i_qwiz].$qwizzled[qwizq_id] = $ ('div#' + qwizq_id).clone (true);
         var delay_init_drag_and_drop = function () {
            if (debug[8]) {
               console.log ('[display_question > delay_init_drag_and_drop] i_qwiz:', i_qwiz, ', i_question:', i_question);
            }
            q.init_drag_and_drop ($qwizq[0]);
         };
         if (! q.qwizard_b) {
            setTimeout (delay_init_drag_and_drop, 100);
         }
         var delay_place_labels = function () {
            place_labels (i_qwiz, i_question, qwizq_id);
         };
         setTimeout (delay_place_labels, 200);
      }
      var n_label_targets = 0;
      var target_count = {};
      $qwizq.find ('span.qwizzled_target').not ('.decoy').each (function () {
         var classes = $ (this).attr ('class');
         var m = classes.match (/qtarget_sib-[0-9]*/);
         if (m) {
            var qwizzled_target_assoc_id = m[0];
            target_count[qwizzled_target_assoc_id] = 1;
         } else {
            m = classes.match (/qwizzled_target-[0-9]*/);
            if (m) {
               var qwizzled_target_assoc_id = m[0];
               target_count[qwizzled_target_assoc_id] = 1;
            } else {
               n_label_targets++;
            }
         }
      });
      n_label_targets += $qwizq.find ('div.qwizzled_target').not ('.decoy').length;
      qwizdata[i_qwiz].n_label_targets = n_label_targets + Object.keys (target_count).length;
      display_qwizzled_progress (i_qwiz);
   }
   if (start_quiz_b && qqc.is_mobile ()) {
      q.go_mobile (i_qwiz);
   } else if (document_qwiz_mobile) {
      var $mobile_qwizq = $ ('#mobile_qwiz' + i_qwiz + '-q' + i_question);
      if ($mobile_qwizq.length) {
         $mobile_qwizq.show ();
      } else {
         $qwizq.show ();
      }
      window.scrollTo (0, 1);
   } else {
      if (debug[0]) {
         console.log ('[display_question] $qwizq:', $qwizq);
      }
      $qwizq.css ('display', 'block');
      if (q.qwizard_b) {
         var init_b = false;
         if (qwizdata[i_qwiz].use_dataset_question_ids[i_question]) {
            var $qwiz_editable = $qwizq.find ('.qwiz_editable');
            if ($qwiz_editable.length) {
               $qwiz_editable.removeClass ('qwiz_editable');
               init_b = true;
            }
         } else if ($qwizq.find ('div[contenteditable]').length == 0) {
            var hangman_labeled_diagram_f = $qwizq.find ('div.hangman_image').length;
            qwizard.init_tinymce ('div#' + qwizq_id + ' .qwiz_editable', false,
                                  hangman_labeled_diagram_f);
            init_b = true;
         }
         if (init_b) {
            var reset_i_question = i_question;
            if (debug[0]) {
               console.log ('[display_question] reset_i_question:', reset_i_question);
            }
            var delay_reset = function () {
               qwizard.reset_show_me_button_text (reset_i_question);
               if (typeof qwizdata[i_qwiz].parts_htm[reset_i_question] != 'undefined') {
                  var parts = qwizdata[i_qwiz].parts_htm[reset_i_question];
                  var n_parts = parts.length;
                  for (var i_part=1; i_part <= n_parts; i_part++) {
                     var part_htm = parts[i_part];
                     $ ('#qwiz' + i_qwiz + '-q' + reset_i_question + ' div.qwiz-part' + i_part).html (part_htm);
                     if (debug[12]) {
                        console.log ('part_htm:', part_htm);
                     }
                  }
               }
               q.init_textentry_autocomplete ($qwizq);
            }
            setTimeout (delay_reset, 300);
         }
      }
   }
   if (! document_qwiz_mobile && ! document_qwiz_force_mobile_f) {
      var selector = '#qwiz' + i_qwiz;
      if (q.preview) {
         selector += '-q' + i_question + '.qwizq_preview';
      }
      var $qwiz = $ (selector);
      if (qwizzled_b) {
         var table_width = 10 + $qwizq.find ('table.qwizzled_table').outerWidth ();
         if (debug[0]) {
            console.log ('[display_question] table_width:', table_width, ', initial_width:', qwizdata[i_qwiz].initial_width);
         }
         if (table_width > qwizdata[i_qwiz].initial_width) {
            $qwiz.css ({width: table_width + 'px', 'max-width': 'none'});
            qwizdata[i_qwiz].width_reset = true;
         }
         if (q.qwizard_b) {
            var $labels = $qwizq.find ('.qwizzled_highlight_label');
            /* DKTMP DEDRAG
            qwizard.create_label_tooltips ($labels);
            qwizard.disable_browser_context_menu ($labels);
            */
            $labels.addClass ('no_move');
         }
      } else {
         var $img = $qwizq.find ('img');
         if ($img.length) {
            var img_width = 10 + $img.outerWidth ();
            if (debug[0]) {
               console.log ('[display_question] img_width:', img_width, ', initial_width:', qwizdata[i_qwiz].initial_width);
            }
            if (img_width > qwizdata[i_qwiz].initial_width) {
               $qwiz.css ({width: img_width + 'px', 'max-width': 'none'});
               qwizdata[i_qwiz].width_reset = true;
            }
         }
      }
      var qwiz_width = $qwiz.outerWidth ();
      var $container = $qwiz.parents ('div');
      if ($container.length) {
         var container_width = $container.outerWidth ();
         if (container_width > 0) {
            if (qwiz_width > container_width) {
               var scale_fac = container_width / qwiz_width;
               var trans_pct = Math.round ((1.0 - scale_fac) * 50.0)
               $qwiz.css ({transform: 'translate(-' + trans_pct + '%, -' + trans_pct + '%) scale(' + scale_fac.toFixed (3) + ')'});
               qwizdata[i_qwiz].width_reset = true;
               $qwiz[0].qscale_fac = scale_fac;
            }
         }
      }
   }
   if (! qwizzled_b) {
      if (qwizdata[i_qwiz].textentry && qwizdata[i_qwiz].textentry[i_question]) {
         var $textentry = $ ('#textentry-qwiz' + i_qwiz + '-q' + i_question);
         if (! qwizdata[i_qwiz].textentry[i_question].textentry_suggest_b) {
            var $check_answer = $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question);
            $check_answer.find ('button.textentry_check_answer').removeClass ('qwiz_button_disabled');
            qwizdata[i_qwiz].check_answer_disabled_b = false;
            $check_answer.css ({display: 'inline-block'});
         } else if (! qwizdata[i_qwiz].textentry[i_question].single_char_b) {
            if (qwizdata[i_qwiz].terms) {
               if (! qwizdata[i_qwiz].textentry_terms_metaphones) {
                  qwizdata[i_qwiz].textentry_terms_metaphones = qqc.process_textentry_terms (qwizdata[i_qwiz].terms);
               }
            } else {
               if (! default_textentry_terms_metaphones) {
                  var plugin_url = qqc.get_qwiz_param ('url', './');
                  var terms_data = '';
                  if (content == 'body' && plugin_url == './') {
                     if (typeof (document_qwiz_terms) != 'undefined') {
                        terms_data = document_qwiz_terms;
                     }
                  } else {
                     terms_data = qqc.get_textentry_terms (plugin_url + 'terms.txt', qwizdata);
                  }
                  default_textentry_terms_metaphones = qqc.process_textentry_terms (terms_data);
               }
            }
            if (qwizdata[i_qwiz].add_terms) {
               if (! qwizdata[i_qwiz].add_textentry_terms_metaphones) {
                  qwizdata[i_qwiz].add_textentry_terms_metaphones = qqc.process_textentry_terms (qwizdata[i_qwiz].add_terms);
               }
            }
            qwizdata[i_qwiz].check_answer_disabled_b = true;
            qwizdata[i_qwiz].textentry_n_hints = 0;
            textentry_answers[i_qwiz] = qwizdata[i_qwiz].textentry[i_question].answers;
            textentry_answer_metaphones[i_qwiz]
               = textentry_answers[i_qwiz].map (function (answer) {
                                                   answer = answer.replace (/\s*(\S+)\s.*/, '\$1');
                                                   return qqc.metaphone (answer);
                                                });
            if (qwizdata[i_qwiz].textentry[i_question].use_terms_b) {
               var singular_plural;
               if (qwizdata[i_qwiz].textentry[i_question].textentry_plural_b) {
                  singular_plural = 'plural';
               } else {
                  singular_plural = 'singular';
               }
               if (qwizdata[i_qwiz].terms) {
                  current_question_textentry_terms_metaphones[i_qwiz]
                     = qwizdata[i_qwiz].textentry_terms_metaphones[singular_plural];
               } else {
                  current_question_textentry_terms_metaphones[i_qwiz]
                     = default_textentry_terms_metaphones[singular_plural];
               }
               if (qwizdata[i_qwiz].add_terms) {
                  current_question_textentry_terms_metaphones[i_qwiz]
                     = current_question_textentry_terms_metaphones[i_qwiz]
                          .concat (qwizdata[i_qwiz].add_textentry_terms_metaphones[singular_plural]);
               }
            } else {
               current_question_textentry_terms_metaphones[i_qwiz] = [];
            }
            var textentry_answers_metaphones
               = textentry_answers[i_qwiz].map (function (answer) {
                                           return [answer, qqc.metaphone (answer)];
                                        });
            if (debug[6]) {
               console.log ('[display_question] textentry_answers_metaphones: ', textentry_answers_metaphones);
            }
            current_question_textentry_terms_metaphones[i_qwiz]
                  = current_question_textentry_terms_metaphones[i_qwiz]
                                         .concat (textentry_answers_metaphones);
            current_question_textentry_terms_metaphones[i_qwiz]
               = qqc.sort_dedupe_terms_metaphones (current_question_textentry_terms_metaphones[i_qwiz]);
            if (debug[6]) {
               console.log ('[display_question] current_question_textentry_terms_metaphones[i_qwiz].length: ', current_question_textentry_terms_metaphones[i_qwiz].length);
               console.log ('[display_question] current_question_textentry_terms_metaphones[i_qwiz].slice (0, 10): ', current_question_textentry_terms_metaphones[i_qwiz].slice (0, 10));
               var i_start = current_question_textentry_terms_metaphones[i_qwiz].length - 10;
               if (i_start > 0) {
                  console.log ('[display_question] current_question_textentry_terms_metaphones[i_qwiz].slice (' + i_start + '): ', current_question_textentry_terms_metaphones[i_qwiz].slice (i_start));
               }
            }
            var question = qwizdata[i_qwiz].textentry[i_question];
            var minlength = question.textentry_minlength;
            var correct_answer_length = question.first_correct_answer.length;
            if (correct_answer_length < minlength) {
               minlength = correct_answer_length;
            }
            if (! $textentry.autocomplete ('instance')) {
               q.init_textentry_autocomplete ($qwizq);
            }
            $textentry.autocomplete ('option', 'minLength', minlength);
            var placeholder;
            var check_answer;
            if (minlength <= 1) {
               placeholder = T ('Type a letter/number');
               check_answer = T ('Type a letter');
            } else {
               minlength = Math.max (minlength, 3);
               placeholder = T ('Type %s+ letters/numbers, then select');
               placeholder = placeholder.replace ('%s', minlength);
               check_answer = T ('Type %s+ letters');
               check_answer = check_answer.replace ('%s', minlength);
            }
            $textentry.attr ('placeholder', placeholder);
            $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question + ' button.textentry_check_answer').html (check_answer);
            qwizdata[i_qwiz].check_answer = check_answer;
            question.textentry_minlength = minlength;
            var $check_answer = $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question);
            $check_answer.find ('button.textentry_check_answer').addClass ('qwiz_button_disabled');
            qwizdata[i_qwiz].check_answer_disabled_b = true;
            $check_answer.css ({display: 'inline-block'});
            if (i_question == 0 && (q.no_intro_b[i_qwiz]
                                           || qwizdata[i_qwiz].n_questions == 1)) {
               $ ('div#qwiz' + i_qwiz).attr ('onmouseenter', qname + '.start_hint_timeout (' + i_qwiz + ')');
            } else {
               q.start_hint_timeout (i_qwiz);
            }
         }
         if ($textentry.length) {
            $textentry.val ('').removeAttr ('disabled');
            if ((qwizdata[i_qwiz].n_correct + qwizdata[i_qwiz].n_incorrect) != 0
                                                    || ! q.no_intro_b[i_qwiz]) {
               panel_exit_mobile_just_closed_b = true;
               if (! q.preview) {
                  $textentry.focus ();
               }
            }
         }
      } else {
         if (qwizdata[i_qwiz].information_question_b[i_question]) {
            $ ('#next_button_text-qwiz' + i_qwiz).html (T ('Continue'));
            q.position_show_next_button (i_qwiz);
            qwizdata[i_qwiz].next_button_show_b = true;
         } else if (typeof qwizdata[i_qwiz].hangman[i_question] != 'undefined') {
            var hangman               = qwizdata[i_qwiz].hangman[i_question];
            var n_hangman             = hangman.n_hangman;
            hangman.n_hangman_done    = 0;
            hangman.n_hangman_correct = 0;
            var first_f               = true;
            var hangman_final_entry;
            for (var i_choice=0; i_choice<n_hangman; i_choice++) {
               $hangman = $qwizq.find ('span.qwiz_hangman.qwiz_hangman_c' + i_choice);
               hangman_final_entry = hangman.hangman_final_entry[i_choice]
               if (qwizdata[i_qwiz].answered_correctly[i_question] == -1) {
                  if (n_hangman > 1
                        && hangman.hangman_incorrect_chars[i_choice].length <= 3
                        && hangman.hangman_n_hints[i_choice] == 0) {
                     $hangman.find ('span.hangman_current_entry').html (hangman_final_entry);
                     hangman.n_hangman_done++;
                     hangman.n_hangman_correct++;
                     continue;
                  }
               }
               var hangman_current_entry = hangman_final_entry.replace (/>[a-z0-9]</gi, '>&ensp;<');
               hangman.hangman_current_entry[i_choice]
                          = hangman_current_entry.replace (/u>&ensp;</g, 'u>\t<');
               $hangman.find ('span.hangman_current_entry').html (hangman_current_entry);
               if (! q.qwizard_b || hangman.hangman_answer[i_choice] != 'placeholder') {
                  $hangman.find ('input').removeAttr ('disabled');
                  $ ('#hangman_hint-qwiz' + i_qwiz + '-q' + i_question + '-c' + i_choice)
                     .removeAttr ('disabled')
                     .removeClass ('qwiz_button_disabled')
               }
               hangman.hangman_incorrect_chars[i_choice] = '';
               hangman.hangman_incorrect_chars_before_hint[i_choice] = 3;
               hangman.hangman_n_hints[i_choice] = 0;
               $hangman[0].done_f = false;
               $hangman.find ('span.hangman_status').html ('');
               var msg;
               var hangman_answer = hangman.hangman_answer[i_choice];
               if (hangman_answer.search (/[a-z]/i) != -1) {
                  msg = T ('Type letters in the box');
               } else {
                  msg = T ('Type numbers in the box');
               }
               $hangman.find ('span.hangman_type_letters').html ('<span class="type_letters">' + msg + '</span>').show ();
               $qwizq.find ('div.qwiz_hangman_msg').hide ();
               if (   (   qwizdata[i_qwiz].user_question_number > 1
                       || ! q.no_intro_b[i_qwiz]
                       || qwizdata[i_qwiz].answered_correctly[i_question] == -1
                      )
                   && first_f && ! q.preview) {
                  first_f = false;
                  panel_exit_mobile_just_closed_b = true;
                  var $hangman_input = $qwizq.find ('span.qwiz_hangman.qwiz_hangman_c' + i_choice + ' input');
                  suppress_hangman_hint_b = true;
                  $hangman_input[0].focus ();
               }
            }
         } else {
            $ ('input[name=' + qwizq_id + ']').removeAttr ('disabled').prop ('checked', false);
            $ ('#mobile_' + qwizq_id + ' li.mobile_choice').show ();
            $qwizq.find ('button.show_the_answer').removeAttr ('disabled').show ();
            if (! q.qwizard_b) {
               $ ('.choices-' + qwizq_id).on ('mouseover', function () {
                  $ (this).css ({'cursor': 'pointer', 'color': '#045FB4'})
               }).on ('mouseout', function () {;
                  $ (this).css ({'cursor': 'text', 'color': 'black'})
               });
            }
         }
      }
   }
}
this.show_response_recorded = function (i_qwiz) {
   var i_question = qwizdata[i_qwiz].i_question;
   var question = qwizdata[i_qwiz].questions[i_question];
   var hhmmss = DateFormat.format.date (new Date ().getTime (), 'h:mm:ss');
   $ ('span.response_recorded_wrapper-qwiz' + i_qwiz).css ({display: 'inline-block'});
   var $response_recorded        = $ ('span.response_recorded-qwiz' + i_qwiz);
   var $response_recorded_shadow = $ ('span.response_recorded_shadow-qwiz' + i_qwiz);
   $response_recorded.addClass ('response_recorded_jump')
                      .attr ('title', 'Response to question ' + question.user_question_number + ' recorded ' + hhmmss)
   $response_recorded_shadow.css ({display: 'inline-block'});
   var delay_remove = function () {
      $response_recorded.removeClass ('response_recorded_jump');
      $response_recorded_shadow.hide ();
   }
   setTimeout (delay_remove, 500);
}
this.pay_lock_settings = function (do_i_qwiz_deck, i_login_qwiz, escaped_session_id,
                                   remember_f) {
   qqc.pay_lock_settings (qname, qwizdata, n_qwizzes, i_login_qwiz,
                          escaped_session_id, remember_f, do_i_qwiz_deck);
}
this.go_mobile = function (i_qwiz) {
   non_mobile_scrollLeft = window.scrollX;
   non_mobile_scrollTop  = window.scrollY;
   var $qwiz = $ ('#qwiz' + i_qwiz);
   qwizdata[i_qwiz].qwiz_style = $qwiz.attr ('style');
   $qwiz.removeAttr ('style').removeClass ('qwiz').addClass ('qwiz-mobile qwizard_qwiz_deck_div');
   $qwiz.after ('<div id="qwiz_div_placeholder"></div>');
   $qwiz.appendTo ('body');
   window.scrollTo (0, 0);
   $ ('body').css ({overflow: 'hidden'});
   $ ('#icon_qwiz' + i_qwiz).hide ();
   if (qqc.is_mobile () || ! document_qwiz_force_mobile_f) {
      $qwiz.find ('.qwizzled_label_head_standard').hide ();
      $qwiz.find ('.qwizzled_label_head_mobile').show ();
      var i_question = qwizdata[i_qwiz].i_question;
      if (i_question < qwizdata[i_qwiz].n_questions && ! qwizdata[i_qwiz].login_show_b) {
         var qwizq_id = 'qwiz' + i_qwiz + '-q' + i_question;
         var $qwizq = $ ('#' + qwizq_id);
         var $mobile_qwizq = $ ('#mobile_qwiz' + i_qwiz + '-q' + i_question);
         if ($mobile_qwizq.length) {
            $mobile_qwizq.show ();
            $qwizq.hide ();
         } else {
            $qwizq.show ();
         }
      }
      if (qwizdata[i_qwiz].$qwizzled && qwizdata[i_qwiz].$qwizzled[qwizq_id]) {
         reset_label_positions ($qwizq);
         $qwiz.css ('width', '');
      }
      $ ('.go-mobile-qwiz' + i_qwiz).hide ();
      if (! document_qwiz_force_mobile_f) {
         $ ('.exit-mobile-qwiz' + i_qwiz).show ();
         $ ('#mode-qwiz' + i_qwiz).hide ();
         $ ('#icon-exit-mobile-qwiz' + i_qwiz).show ();
         $ ('#summary-qwiz' + i_qwiz).find ('button.summary_exit_mobile_qwiz').show ();
      }
      document_qwiz_mobile = 'mobile_';
   }
}
this.open_panel_exit_mobile = function (i_qwiz) {
   $ ('#overlay-exit-mobile-qwiz' + i_qwiz)
      .show ()
      .animate ({top: '0px'}, 500);
   panel_exit_mobile_open_b = true;
   $ ('#icon-exit-mobile-qwiz' + i_qwiz).hide ();
}
this.close_panel_exit_mobile = function (overlay_el) {
   $ (overlay_el).animate ({top: '-100px'}, 500,
                           function () {
                              $ (this).hide ();
                              $ ('div.icon-exit-mobile-qwiz').show ();
                           });
   window.scrollTo ($ (window).scrollLeft (), 1);
   panel_exit_mobile_open_b = false;
   panel_exit_mobile_just_closed_b = true;
   return false;
}
this.exit_mobile = function (i_qwiz) {
   var $qwiz = $ ('#qwiz' + i_qwiz);
   $qwiz.attr ('style', qwizdata[i_qwiz].qwiz_style)
        .removeClass ('qwiz-mobile qwizard_qwiz_deck_div')
        .addClass ('qwiz');
   if ($ ('#xqwiz' + i_qwiz).length) {
      $ ('#qwiz' + i_qwiz).css ('width', qwizdata[i_qwiz].initial_width + 'px');
   }
   $ ('#qwiz_div_placeholder').replaceWith ($qwiz);
   $ ('body').css ({overflow: ''});
   window.scrollTo (non_mobile_scrollLeft, non_mobile_scrollTop);
   $ ('#overlay-exit-mobile-qwiz' + i_qwiz).css ({top: '-100px', display: 'none'});
   $ (window).off ('scroll');
   $qwiz.find ('.qwizzled_label_head_standard').show ();
   $qwiz.find ('.qwizzled_label_head_mobile').hide ();
   var i_question = qwizdata[i_qwiz].i_question;
   var qwizq_id = 'qwiz' + i_qwiz + '-q' + i_question;
   var $qwizq = $ ('#' + qwizq_id);
   if (i_question >= 0 && i_question < qwizdata[i_qwiz].n_questions
                                          && ! qwizdata[i_qwiz].login_show_b) {
      var $mobile_qwizq = $ ('#mobile_qwiz' + i_qwiz + '-q' + i_question);
      $mobile_qwizq.hide ();
      $qwizq.show ();
   }
   var $table_img;
   if (qwizdata[i_qwiz].$qwizzled && qwizdata[i_qwiz].$qwizzled[qwizq_id]) {
      $table_img = $qwizq.find ('table.qwizzled_table');
   } else {
      $table_img = $qwizq.find ('img');
   }
   if ($table_img.length) {
      var table_img_width = 10 + $table_img.outerWidth ();
      if (table_img_width > qwizdata[i_qwiz].initial_width) {
         var selector = '#qwiz' + i_qwiz;
         if (q.preview) {
            selector += '-q' + i_question + '.qwizq_preview';
         }
         $ (selector).css ({width: table_img_width + 'px', 'max-width': 'none'});
         qwizdata[i_qwiz].width_reset = true;
      }
      reset_label_positions ($qwizq);
   }
   $ ('div.icon-exit-mobile-qwiz, div.icon-panel-exit-mobile-qwiz').hide ();
   $ ('.exit-mobile-qwiz').hide ();
   $ ('button.summary_exit_mobile_qwiz').hide ();
   if (qqc.is_mobile ()) {
      $ ('.go-mobile-qwiz' + i_qwiz).show ();
      $ ('#mode-qwiz' + i_qwiz).show ();
   }
   document_qwiz_mobile = '';
   panel_exit_mobile_just_closed_b = false;
}
function reset_label_positions ($qwizq) {
   if (debug[8]) {
      console.log ('[reset_label_positions] $qwizq:', $qwizq);
   }
   $qwizq.find ('td.qwizzled_labels div.qwizzled_label').each (function () {
      var label_offset = $ (this).parents ('li').offset ();
      if (debug[8]) {
         console.log ('[reset_label_positions] label_offset:', label_offset);
      }
      $ (this).data ('label_x', label_offset.left).data ('label_y', label_offset.top);
   });
}
this.start_hint_timeout = function (i_qwiz) {
   $ ('div#qwiz' + i_qwiz).removeAttr ('onmouseenter');
   var i_question = qwizdata[i_qwiz].i_question;
   var $check_answer = $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question);
   if (debug[0]) {
      console.log ('[start_hint_timeout] $check_answer.length:', $check_answer.length);
   }
   var show_hint_button = function () {
      $check_answer.find ('button.qwiz_textentry_hint')
         .removeAttr ('disabled')
         .html ('Hint').css ({display: 'inline-block'});
   }
   $check_answer.find ('button.qwiz_textentry_hint').html ('Hint').hide ();
   if (hint_timeout_sec >= 0) {
      show_hint_timeout[i_qwiz] = setTimeout (show_hint_button, hint_timeout_sec*1000);
   }
}
function process_multiple_choice (i_qwiz, i_question, htm, opening_tags) {
   var desktop_htm;
   var remaining_htm;
   var choices_html = '';
   var span_pos = qqc.opening_tag_shortcode_pos ('([c]|[c*])', htm);
   if (span_pos == htm.length) {
      errmsgs.push (T ('Did not find choices ("[c]")') + '.  qwiz: ' + (i_qwiz + 1) + ', ' + T ('question') + ' ' + (i_question + 1));
      desktop_htm = '';
      remaining_htm = '';
   } else {
      var question_htm = htm.substr (0, span_pos);
      if (debug[0]) {
         console.log ('[process_multiple_choice] span_pos: ', span_pos);
         console.log ('[process_multiple_choice] question_htm: ', question_htm);
      }
      if (qwizdata[i_qwiz].qrecord_id) {
         var q_and_a_text;
         if (qqc.isInteger (qwizdata[i_qwiz].dataset_id[i_question])) {
            q_and_a_text = qqc.remove_tags_eols (question_htm);
            qwizdata[i_qwiz].q_and_a_text[i_question]  = qqc.q_and_a_hash (q_and_a_text);
            qwizdata[i_qwiz].q_and_a_crc32[i_question] = qwiz_crc32 (htm);
         } else {
            qwizdata[i_qwiz].q_and_a_text[i_question]  = qwizdata[i_qwiz].dataset_id[i_question];
            qwizdata[i_qwiz].q_and_a_crc32[i_question] = 'dataset';
         }
      }
      if (set_qwizard_data_b) {
         qw.questions_cards[i_question].type = 'multiple_choice';
         question_htm = qqc.shortcodes_to_video_elements (question_htm);
         qw.questions_cards[i_question].question_text = opening_tags + question_htm;
      }
      var bg_img_style = create_bg_img_style (i_qwiz, i_question);
      desktop_htm =   '<div id="qwiz' + i_qwiz + '-q' + i_question + '" class="qwizq"' + bg_img_style + '>\n'
                    +    '<div class="qwiz-question qwiz_editable">'
                    +       opening_tags + question_htm
                    +    '</div>';
      if (debug[1]) {
         console.log ('[process_multiple_choice] desktop_htm: ', desktop_htm);
      }
      remaining_htm = htm.substr (span_pos);
      choices_html = '</p>';
   }
   var choice_tags = htm.match (/\[c\*{0,1}\]/gm);
   var n_choices = 0;
   if (choice_tags) {
      n_choices = choice_tags.length;
   }
   if (debug[0]) {
      console.log ('[process_multiple_choice] n_choices: ', n_choices);
   }
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].n_choices = n_choices;
      qw.questions_cards[i_question].choices   = [];
      qw.questions_cards[i_question].feedbacks = [];
   }
   var n_correct = 0;
   var choice_start_tags = ['[c]', '[c*]'];
   var choice_next_tags  = ['[c]', '[c*]', '[x]'];
   var got_feedback_b = false;
   var i_fx = -1;
   var feedback_divs  = [];   // Feedback div for desktop.
   var feedback_items = [];   // Plain html for mobile.
   var choice_items   = [];   // "
   var i_choice_correct = -1;
   for (var i_choice=0; i_choice<n_choices; i_choice++) {
      var choice_html = qqc.parse_html_block (remaining_htm, choice_start_tags, choice_next_tags);
      remaining_htm = remaining_htm.substr (choice_html.length);
      if (q.wordpress_page_f) {
         choice_html = cvt_feedback (choice_html);
         if (choice_html.indexOf ('[c*]') != -1) {
            choice_tags[i_choice] = '[c*]';
         }
      }
      var r = process_feedback_item (choice_html, i_qwiz, i_question, i_choice);
      choice_html  = r.choice_html;
      if (r.feedback_div) {
         if (i_choice == n_choices-1 && ! got_feedback_b && n_choices != 1) {
            feedback_divs[0] = r.feedback_div;
            feedback_items[0] = r.feedback_item_html;
            var n_feedback_items = 1;
            if (r.fx_b) {
               i_fx = 0;
               n_feedback_items = 0;
            }
            for (var i_feedback=1; i_feedback<n_choices; i_feedback++) {
               var r = process_feedback_item (choice_html, i_qwiz, i_question,
                                              i_feedback);
               choice_html  = r.choice_html;
               if (! r.feedback_div) {
                  break;
               }
               feedback_divs[i_feedback] = r.feedback_div;
               feedback_items[i_feedback] = r.feedback_item_html;
               if (r.fx_b) {
                  if (i_fx == -1) {
                     i_fx = i_feedback;
                  } else {
                     errmsgs.push (T ('Got more than one [fx]') + '.  qwiz: ' + (i_qwiz + 1) + ', ' + T('question') + ' ' + (1 + i_question));
                  }
               } else {
                  n_feedback_items++;
               }
            }
            if (n_feedback_items == 1 || i_fx != -1) {
               feedback_divs[n_choices-1] = feedback_divs[0];
               feedback_divs[0] = '';
               feedback_items[n_choices-1] = feedback_items[0];
               feedback_items[0] = '';
               if (i_fx == 0) {
                  i_fx = n_choices - 1;
               }
            } else {
               if (n_feedback_items != n_choices) {
                  errmsgs.push (T ('Number of feedback items does not match number of choices') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T('question') + ' ' + (1 + i_question));
               } else {
                  feedback_divs[0] = feedback_divs[0].replace (/(qwiz[0-9]+-q[0-9]+-a)[0-9]+/, '\$10');
               }
            }
         } else {
            got_feedback_b = true;
            if (r.fx_b) {
               if (i_fx == -1) {
                  i_fx = feedback_divs.length;
               } else {
                  errmsgs.push (T ('Got more than one [fx]') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T('question') + ' ' + (1 + i_question));
               }
            }
            feedback_divs.push (r.feedback_div);
            feedback_items.push (r.feedback_item_html);
            var r = process_feedback_item (choice_html, i_qwiz, i_question,
                                           i_feedback);
            if (r.feedback_div) {
               errmsgs.push (T ('More than one feedback shortcode [f] or [fx] given with a choice') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question) + ', ' + T ('choice') + ' ' + (1 + i_choice));
            }
         }
      } else {
         feedback_divs.push ('');
         feedback_items.push ('');
      }
      if (debug[2]) {
         console.log ('[process_multiple_choice] feedback_divs:', feedback_divs);
      }
      var c = choice_html.match (/\[c\*{0,1}\]/m)[0];
      var without_c = choice_html.replace (/\[c\*{0,1}\]/m, '');
      choice_items.push (without_c);
      if (/^\s*<([ph]|br)/.test (without_c)) {
         choice_html = without_c.replace (/^\s*<([ph]|br)[^>]*>/, '$&' + c);
      } else {
         choice_html = c + without_c;
      }
      if (n_choices > 1
            || (q.qwizard_b
                   && qwizdata[i_qwiz].qwizard_multiple_choice_b[i_question])) {
         var r = create_radio_button_html (i_qwiz, i_question, i_choice,
                                           choice_tags[i_choice]);
         if (r.correct) {
            n_correct++;
            i_choice_correct = i_choice;
         }
         var qwiz_question = 'qwiz' + i_qwiz + '-q' + i_question;
         var qwiz_question_choice = qwiz_question + '-a' + i_choice;
         var onclick = 'onclick="' + qname + '.process_choice (event, \'' + qwiz_question_choice + '\')"';
         choice_html = choice_html.replace (/\[c\*{0,1}\]/m, r.htm + '<span class="qwiz-choice qwiz_editable" data-i_choice="' + i_choice + '" ' + onclick + '>');
         if (/^\s*<[ph]/.test (choice_html)) {
            var len = choice_html.length;
            if (choice_html.substr (len - 4, 3) != '</p'
                                  && choice_html.substr (len - 5, 3) != '</h') {
               var end_opening_tag_pos = choice_html.indexOf ('>');
               if (end_opening_tag_pos != -1) {
                  choices_html += choice_html.substr (0, end_opening_tag_pos + 1);
                  choice_html = choice_html.substr (end_opening_tag_pos + 1);
               }
               choice_html += '</span>';
            } else {
               choice_html = choice_html.replace (/<\/(p|h[1-6])>$/, '</span>$&');
            }
         } else {
            choice_html += '</span>';
         }
         if (set_qwizard_data_b) {
            var m = choice_html.match (/<span class="qwiz-choice[^>]+>/);
            var m = choice_html.match (/<span class="qwiz-choice[^>]+>([^]*?)<\/span>/);
            var choice = qqc.shortcodes_to_video_elements (m[1])
            qw.questions_cards[i_question].choices[i_choice] = choice;
         }
         var style = '';
         if (q.qwizard_b) {
            style = ' style="cursor: text;"';
         }
         choices_html += '<span class="choices-' + qwiz_question + ' choice-' + qwiz_question_choice + ' qwiz-choices" ' + onclick + ' data-i_choice="' + i_choice + '"' + style + '>\n'
                      +     '<span class="qwiz-choice" data-i_choice="' + i_choice + '">'
                      +        choice_html
                      +     '</span>'
                      + '</span>';
      } else {
         choice_html = choice_html.replace (/\[c\*{0,1}\]/m, '');
         i_choice_correct = 0;
         n_correct = 1;
         var onclick;
         if (qwizdata[i_qwiz].n_questions == 1) {
            onclick = qname + '.process_choice (event, \'qwiz' + i_qwiz + '-q' + i_question + '-a0\', true)';
         } else {
            onclick = qname + '.show_answer_got_it_or_not (' + i_qwiz + ', ' + i_question + ', this)';
         }
         var button_label = choice_html;
         if (button_label.indexOf ('[show_me_placeholder]') != -1) {
            button_label = 'Show me the answer';
         }
         choices_html += '<button class="qwiz_button show_the_answer qwiz-choice qwiz_editable" data-i_choice="0" style="margin-left: 20px;" onclick="' + onclick + '">';
         choices_html +=    button_label;
         choices_html += '</button>\n';
         if (set_qwizard_data_b) {
            qw.questions_cards[i_question].type = 'show_me';
            choice_html = qqc.shortcodes_to_video_elements (choice_html);
            qw.questions_cards[i_question].choices[i_choice] = choice_html;
         }
         if (! feedback_divs[0]) {
            errmsgs.push (T ('Feedback [f] is required for a one-choice question') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
         }
      }
   }
   if (set_qwizard_data_b && qw.questions_cards[i_question].type != 'show_me') {
      if (debug[12]) {
         console.log ('[process_multiple_choice] choices_html:', choices_html);
      }
      var pos_sub_span1 = choices_html.substr (31).indexOf ('<span');
      if (pos_sub_span1 != -1) {
         if (choices_html.substr (31 + pos_sub_span1).search (/<p|<h[1-6]|<br/) == -1) {
            qw.questions_cards[i_question].choices_inline = true;
         }
      }
   }
   desktop_htm += choices_html;
   desktop_htm += '<div style="clear: both;"></div>\n';
   if (debug[1]) {
      console.log ('[process_multiple_choice] desktop_htm: ', desktop_htm);
   }
   if (n_correct == 0) {
      if (! q.qwizard_b) {
         if (! qwizdata[i_qwiz].use_dataset
                   && ! qwizdata[i_qwiz].use_dataset_question_ids[i_question]) {
            errmsgs.push (T ('No choice was marked correct') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
         }
      }
   } else if (n_correct > 1) {
      errmsgs.push (T ('More than one choice was marked correct') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
   } else {
      if (set_qwizard_data_b) {
         qw.questions_cards[i_question].correct_choice = i_choice_correct;
      }
   }
   for (var i_choice=0; i_choice<n_choices; i_choice++) {
      if (! feedback_divs[i_choice]) {
         if (i_fx != -1 && i_choice != i_choice_correct) {
            feedback_divs[i_choice] = feedback_divs[i_fx].replace (/(qwiz[0-9]+-q[0-9]+-a)[0-9]+/, '\$1' + i_choice);
         } else {
            var response = q.canned_feedback (i_choice == i_choice_correct);
            feedback_divs[i_choice]
                               = create_feedback_div_html (i_qwiz, i_question,
                                                           i_choice, response);
            feedback_items[i_choice] = response;
         }
      }
   }
   feedback_divs = feedback_divs.join ('\n');
   desktop_htm += feedback_divs;
   if (set_qwizard_data_b) {
      for (var i_choice=0; i_choice<n_choices; i_choice++) {
         qw.questions_cards[i_question].feedbacks[i_choice]
                  = qqc.shortcodes_to_video_elements (feedback_items[i_choice]);
      }
   }
   if (n_choices == 1 && (qwizdata[i_qwiz].n_questions > 1 || qwizdata[i_qwiz].use_dataset)) {
      desktop_htm += create_got_it_or_not ('', i_qwiz, i_question);
   }
   if (debug[2]) {
      console.log ('[process_multiple_choice] desktop_htm: ', desktop_htm);
   }
   desktop_htm += '</div>\n';
   var qwiz_question = 'mobile_qwiz' + i_qwiz + '-q' + i_question;
   var mobile_htm = [];
   mobile_htm.push ('<div id="mobile_qwiz' + i_qwiz + '-q' + i_question + '" class="qwizq-mobile">\n');
   mobile_htm.push (   opening_tags + question_htm);
   mobile_htm.push (   '<ul class="mobile_choices">');
   for (var i_choice=0; i_choice<n_choices; i_choice++) {
      var qwiz_question_choice = qwiz_question + '-a' + i_choice;
      var data_correct = i_choice == i_choice_correct ? 'data-q="1"' : '';
      var onclick;
      if (n_choices > 1 || qwizdata[i_qwiz].n_questions == 1) {
         onclick = qname + '.process_choice (event, \'' + qwiz_question_choice + '\')';
      } else {
         onclick = qname + '.show_answer_got_it_or_not (' + i_qwiz + ', ' + i_question + ', this)';
      }
      mobile_htm.push (    '<li id="choice-' + qwiz_question_choice + '" class="mobile_choice" onclick="' + onclick  + '" ' + data_correct + '>');
      mobile_htm.push (       '<div class="mobile_choice">');
      mobile_htm.push (          choice_items[i_choice]);
      mobile_htm.push (       '</div>');
      mobile_htm.push (    '</li>');
   }
   mobile_htm.push (   '</ul>');
   mobile_htm.push (   '<div style="clear: both;"></div>');
   mobile_htm.push (feedback_divs.replace (/id="qwiz/gm, 'id="mobile_qwiz'));
   mobile_htm.push ('</div>');
   if (n_choices == 1 && (qwizdata[i_qwiz].n_questions > 1 || qwizdata[i_qwiz].use_dataset)) {
      mobile_htm.push (create_got_it_or_not ('mobile_', i_qwiz, i_question));
   }
   mobile_htm = mobile_htm.join ('\n');
   return desktop_htm + '\n' + mobile_htm;
}
function process_textentry (i_qwiz, i_question, htm, opening_tags) {
   if (! qwizdata[i_qwiz].textentry) {
      qwizdata[i_qwiz].textentry = {};
      textentry_b = true;
   }
   var question_text = '';
   var c_pos = qqc.opening_tag_shortcode_pos ('([c*]|[c])', htm);
   if (c_pos < htm.length) {
      question_text = htm.substr (0, c_pos);
   } else {
      errmsgs.push (T ('No answer-word given') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
   }
   if (qwizdata[i_qwiz].qrecord_id) {
      if (qqc.isInteger (qwizdata[i_qwiz].dataset_id[i_question])) {
         var q_and_a_text = qqc.remove_tags_eols (question_text);
         q_and_a_text     = q_and_a_text.replace (/\[textentry[^\]]*\]/, '_________');
         qwizdata[i_qwiz].q_and_a_text[i_question]  = qqc.q_and_a_hash (qqc.remove_tags_eols (q_and_a_text));
         qwizdata[i_qwiz].q_and_a_crc32[i_question] = qwiz_crc32 (htm);
      } else {
         qwizdata[i_qwiz].q_and_a_text[i_question]  = qwizdata[i_qwiz].dataset_id[i_question];
         qwizdata[i_qwiz].q_and_a_crc32[i_question] = 'dataset';
      }
   }
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].type = 'textentry';
      question_text = qqc.shortcodes_to_video_elements (question_text);
      qw.questions_cards[i_question].question_text = opening_tags + question_text;
      qw.questions_cards[i_question].choices = [];
      qw.questions_cards[i_question].correct_choice_fs = [];
      qw.questions_cards[i_question].feedbacks = [];
   }
   var textentry_plural_b = false;
   var textentry_suggest_b = true;
   var textentry_minlength = 3;
   var use_dict_b  = default_use_dict == 'true';
   var use_terms_b = default_use_terms == 'true';
   var single_char_b = false;
   var m = htm.match (/\[textentry([^\]]*)\]/m);
   var attributes = '';
   if (m) {
      attributes = m[1];
      if (attributes) {
         attributes = qqc.replace_smart_quotes (attributes);
         textentry_plural_b = get_attr (attributes, 'plural') == 'true';
         textentry_suggest_b = get_attr (attributes, 'suggest') != 'false';
         var attr_val = get_attr (attributes, 'minlength');
         if (attr_val != '') {
            textentry_minlength = attr_val;
         }
         var use_terms = get_attr (attributes, 'use_terms');
         if (use_terms) {
            use_terms_b = use_terms != 'false';
         }
         var use_dict = get_attr (attributes, 'use_dict');
         if (use_dict) {
            use_dict_b = use_dict != 'false';
         }
         single_char_b = get_attr (attributes, 'single_char') == 'true';
      }
   }
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].type = single_char_b ? 'one_letter_answer' : 'textentry';
      qw.questions_cards[i_question].textentry_attributes = attributes;
      qw.questions_cards[i_question].textentry_plural_b = textentry_plural_b;
   }
   var remaining_htm = htm.substr (c_pos);
   htm = htm.substr (0, c_pos);
   var classname;
   var style;
   if (single_char_b) {
      classname = 'qwiz_single_char_entry';
      style     = 'style="width: 2rem; padding: 2px;" ';
   } else if (textentry_suggest_b) {
      classname = 'qwiz_textentry';
      style     = 'style="width: 18em; padding: 2px;" ';
   } else {
      classname = 'qwiz_textentry_no_suggest';
      style     = 'style="width: 18em; padding: 2px;" ';
   }
   var input = '<input type="text" id="textentry-qwiz' + i_qwiz + '-q' + i_question + '" autocomplete="off" class="' + classname + '" ' + style + 'onfocus="' + qname + '.set_textentry_i_qwiz (event, this)" />';
   var new_htm;
   var re = new RegExp ('\\[textentry[^\\]]*\\]');
   if (q.qwizard_b) {
      new_htm = create_hangman_textentry_editable_divs (i_qwiz, i_question,
                                                        opening_tags, [input],
                                                        'textentry', re, htm);
   } else {
      new_htm =   '<div id="qwiz' + i_qwiz + '-q' + i_question + '" class="qwizq">\n'
                +    opening_tags + htm.replace (re, input);
   }
   new_htm +=  '<br />'
             + '<div id="textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question + '" class="textentry_check_answer_div">\n'
             +    '<button class="qwiz_button qwiz_button_disabled textentry_check_answer" onclick="' + qname + '.textentry_check_answer (' + i_qwiz + ')">'
             +        T ('Check answer')
             +    '</button>\n'
             +    '&emsp;\n'
             +    '<button class="qwiz_button qwiz_textentry_hint" style="display: none; font-size: 11px; padding: 2px 2px; border-radius: 5px;" onclick="' + qname + '.textentry_hint (' + i_qwiz + ')" disabled>'
             +        T ('Hint')
             +    '</button>\n'
             + '</div>\n';
   var n_correct = 0;
   var choice_start_tags = ['[c]', '[c*]'];
   var choice_next_tags  = ['[c]', '[c*]', '[x]'];
   var got_feedback_b = false;
   var feedback_divs = [];
   qwizdata[i_qwiz].textentry[i_question] = {};
   qwizdata[i_qwiz].textentry[i_question].choices = [];
   qwizdata[i_qwiz].textentry[i_question].textentry_plural_b = textentry_plural_b;
   qwizdata[i_qwiz].textentry[i_question].textentry_suggest_b = textentry_suggest_b;
   qwizdata[i_qwiz].textentry[i_question].textentry_minlength = textentry_minlength;
   qwizdata[i_qwiz].textentry[i_question].use_terms_b = use_terms_b;
   qwizdata[i_qwiz].textentry[i_question].use_dict_b = use_dict_b;
   qwizdata[i_qwiz].textentry[i_question].single_char_b = single_char_b;
   qwizdata[i_qwiz].textentry[i_question].choices_correct = [];
   qwizdata[i_qwiz].textentry[i_question].answers = [];
   qwizdata[i_qwiz].textentry[i_question].first_correct_answer = '';
   qwizdata[i_qwiz].check_answer_disabled_b = true;
   var i_choice = 0;
   var default_choice_given_b = false;
   while (true) {
      var choice_html = qqc.parse_html_block (remaining_htm, choice_start_tags,
                                              choice_next_tags);
      if (choice_html == 'NA') {
         break;
      }
      remaining_htm = remaining_htm.substr (choice_html.length);
      if (q.wordpress_page_f) {
         choice_html = cvt_feedback (choice_html);
      }
      var r = process_feedback_item (choice_html, i_qwiz, i_question, i_choice);
      choice_html  = r.choice_html;
      var feedback_item_html = '';
      if (r.feedback_div) {
         got_feedback_b = true;
         feedback_divs.push (r.feedback_div);
         feedback_item_html = r.feedback_item_html;
         var r = process_feedback_item (choice_html, i_qwiz, i_question,
                                        i_choice);
         if (r.feedback_div) {
            errmsgs.push (T ('More than one feedback shortcode [f] given with choice') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question) + ', ' + T ('choice') + ' ' + (1 + i_choice));
         }
      } else {
         feedback_divs.push ('');
      }
      if (set_qwizard_data_b) {
         qw.questions_cards[i_question].feedbacks[i_choice]
                        = qqc.shortcodes_to_video_elements (feedback_item_html);
      }
      var correct_b = choice_html.search (/\[c\*\]/) != -1;
      if (correct_b) {
         n_correct++;
      }
      choice_html = choice_html.replace (/[^]*\[c\*{0,1}\]/, '');
      if (! (q.qwizard_b && choice_html.indexOf ('placeholder') != -1)) {
         choice_html = choice_html.replace (/<[^>]+>|\n|&nbsp;/g, '');
      }
      if (choice_html.replace (';', '').search (/\S/) == -1) {
         errmsgs.push (T ('No word(s) given for [textentry] choice') + '.  qwiz: ' + (i_qwiz + 1) + ', ' + T ('question') + ' ' + (1 + i_question) + ', ' + T ('choice') + ' ' + (1 + i_choice));
      }
      var alts = choice_html.split (/\s*;\s*/);
      var nonblank_alts = [];
      for (var i=0; i<alts.length; i++) {
         if (alts[i].search (/\S/) != -1) {
            nonblank_alts.push (qqc.trim (alts[i]));
         }
      }
      if (nonblank_alts[0] == '*') {
         default_choice_given_b = true;
         if (correct_b) {
            errmsgs.push (T ('For [textentry] question, wildcard choice ("*", for any other user entry) cannot be marked correct "[c*]"') +  '.  qwiz: ' + (i_qwiz + 1) + ', ' + T ('question') + ' ' + (1 + i_question) + ', ' + T ('choice') + ' ' + (1 + i_choice));
         }
         /*
         if (feedback_divs[i_choice] == '') {
            errmsgs.push (T ('For [textentry] question, wildcard choice ("*", for any other user entry) must be accompanied by feedback "[f]"'));
         }
         */
      }
      qwizdata[i_qwiz].textentry[i_question].choices.push (nonblank_alts);
      qwizdata[i_qwiz].textentry[i_question].choices_correct.push (correct_b);
      if (set_qwizard_data_b) {
         qw.questions_cards[i_question].choices.push (nonblank_alts.join (';'));
         qw.questions_cards[i_question].correct_choice_fs.push (correct_b ? 1 : 0);
      }
      if (correct_b) {
         if (nonblank_alts.length && qwizdata[i_qwiz].textentry[i_question].first_correct_answer == '') {
            qwizdata[i_qwiz].textentry[i_question].first_correct_answer = nonblank_alts[0];
         }
      }
      var n_alts = nonblank_alts.length;
      for (var i=0; i<n_alts; i++) {
         if (qwizdata[i_qwiz].textentry[i_question].answers.indexOf (nonblank_alts[i]) != -1
                                           && nonblank_alts != 'Enter word') {
            errmsgs.push (T ('Answer given in more than one choice') + ': ' + nonblank_alts[i] + '.  qwiz: ' + (i_qwiz + 1) + ', ' + T ('question') + ' ' + (1 + i_question) + ', ' + T ('choice') + ' ' + (1 + i_choice));
         }
      }
      qwizdata[i_qwiz].textentry[i_question].answers
                 = qwizdata[i_qwiz].textentry[i_question].answers.concat (nonblank_alts);
      i_choice++;
   }
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].n_choices = i_choice;
   }
   if (! default_choice_given_b) {
      i_choice++;
      qwizdata[i_qwiz].textentry[i_question].choices.push (['*']);
      qwizdata[i_qwiz].textentry[i_question].choices_correct.push (false);
      if (set_qwizard_data_b) {
         qw.questions_cards[i_question].choices.push ('*');
         qw.questions_cards[i_question].correct_choice_fs.push (0);
         qw.questions_cards[i_question].n_choices = i_choice;
      }
   }
   var n_choices = i_choice;
   new_htm += '<div style="clear: both;"></div>\n';
   if (n_correct == 0) {
      errmsgs.push (T ('No choice was marked correct') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
   }
   for (var i_choice=0; i_choice<n_choices; i_choice++) {
      if (! feedback_divs[i_choice]) {
         var response = q.canned_feedback (qwizdata[i_qwiz].textentry[i_question].choices_correct[i_choice]);
         feedback_divs[i_choice] = create_feedback_div_html (i_qwiz, i_question,
                                                             i_choice, response);
         if (set_qwizard_data_b) {
            qw.questions_cards[i_question].feedbacks[i_choice] = response;
         }
      }
   }
   new_htm += feedback_divs.join ('\n');
   new_htm += '</div>\n';
   if (debug[2] || debug[12]) {
      console.log ('[process_textentry] new_htm: ', new_htm);
   }
   return new_htm;
}
function process_hangman (i_qwiz, i_question, htm, opening_tags) {
   var hangman_labeled_diagram_f = htm.indexOf ('hangman_img_wrapper') != -1;
   var c_pos = qqc.opening_tag_shortcode_pos ('([c*]|[c])', htm);
   if (c_pos < htm.length) {
      question_text = htm.substr (0, c_pos);
   } else {
      errmsgs.push (T ('Did not get [c] or [c*] (hangman answer) with [hangman]') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
      c_pos = 0;
   }
   if (qwizdata[i_qwiz].qrecord_id) {
      if (qqc.isInteger (qwizdata[i_qwiz].dataset_id[i_question])) {
         var q_and_a_text = qqc.remove_tags_eols (question_text);
         q_and_a_text = q_and_a_text.replace (/\[hangman[^\]]*\]/g, '_________');
         qwizdata[i_qwiz].q_and_a_text[i_question]  = qqc.q_and_a_hash (qqc.remove_tags_eols (q_and_a_text));
         qwizdata[i_qwiz].q_and_a_crc32[i_question] = qwiz_crc32 (htm);
      } else {
         qwizdata[i_qwiz].q_and_a_text[i_question]  = qwizdata[i_qwiz].dataset_id[i_question];
         qwizdata[i_qwiz].q_and_a_crc32[i_question] = 'dataset';
      }
   }
   var hangman_max_hints    = default_hangman_max_hints;
   var hangman_type_letters = true;
   var m = htm.match (/\[hangman([^\]]*)\]/m);
   var attributes = '';
   if (m) {
      attributes = m[1];
      if (attributes) {
         attributes = qqc.replace_smart_quotes (attributes);
         var hints = get_attr (attributes, 'hints');
         if (hints) {
            if (hints.search (/[^0-9]/) == -1) {
               hangman_max_hints = parseInt (hints, 10);
            } else {
               errmsgs.push (T ('"hints" for [hangman] should be a number') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
            }
         }
         hangman_type_letters = ! (get_attr (attributes, 'type_letters') == 'false');
      }
   }
   var type                         = 'hangman';
   var labeled_diagram_opening_tags = opening_tags;
   if (hangman_labeled_diagram_f) {
      type                         = 'hangman_labeled_diagram';
      labeled_diagram_opening_tags = '';
   }
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].type = type;
      qw.questions_cards[i_question].hangman_attributes = attributes;
      question_text = qqc.shortcodes_to_video_elements (question_text);
      qw.questions_cards[i_question].question_text = labeled_diagram_opening_tags + question_text;
      qw.questions_cards[i_question].choices = [];
      qw.questions_cards[i_question].correct_choice_fs = [];
      qw.questions_cards[i_question].feedbacks = [];
   }
   var question_htm = htm.substr (0, c_pos);
   var m = question_htm.match (/\[hangman/g);
   var n_hangman = m.length;
   var remaining_htm = htm.substr (c_pos);
   var choice_start_tags = ['[c]', '[c*]'];
   var choice_next_tags  = ['[c]', '[c*]', '[x]', '<div class="qwizzled_question_bottom_border_title"'];
   var got_feedback_b = false;
   var feedback_divs = [];
   var hangman_re = new RegExp ('\\[hangman[^\\]]*\\]');
   var hangman_spans = [];
   var i_choice = 0;
   while (true) {
      var hangman_answer = '';
      var hangman_answer_length = 0;
      var choice_html = qqc.parse_html_block (remaining_htm, choice_start_tags,
                                              choice_next_tags);
      if (choice_html == 'NA') {
         break;
      }
      remaining_htm = remaining_htm.substr (choice_html.length);
      if (q.wordpress_page_f) {
         choice_html = cvt_feedback (choice_html);
      }
      var r = process_feedback_item (choice_html, i_qwiz, i_question, i_choice);
      choice_html  = r.choice_html;
      if (r.feedback_div) {
         got_feedback_b = true;
         feedback_divs.push (r.feedback_div);
         if (set_qwizard_data_b) {
            qw.questions_cards[i_question].feedbacks[i_choice]
                      = qqc.shortcodes_to_video_elements (r.feedback_item_html);
         }
         var r = process_feedback_item (choice_html, i_qwiz, i_question,
                                        i_choice);
         if (r.feedback_div) {
            errmsgs.push (T ('More than one feedback shortcode [f] given with hangman answer') + '.  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question) + ', ' + T ('choice') + ' ' + (1 + i_choice));
         }
      } else {
         feedback_divs.push ('');
      }
      var hangman_match = choice_html.match (/\[c\*{0,1}\]([^\[]*)/m);
      if (hangman_match) {
         hangman_answer = hangman_match[1];
         hangman_answer = hangman_answer.replace (/<[^>]+>|\n|&nbsp;/g, '');
         hangman_answer = qqc.trim (hangman_answer);
         hangman_answer_length = hangman_answer.length;
         if (debug[0]) {
            console.log ('[process_hangman] hangman_answer:', hangman_answer);
         }
      }
      if (typeof qwizdata[i_qwiz].hangman[i_question] == 'undefined') {
         qwizdata[i_qwiz].hangman[i_question] = {};
      }
      if (! qwizdata[i_qwiz].hangman[i_question].hangman_answer) {
         qwizdata[i_qwiz].hangman[i_question].hangman_answer          = [];
         qwizdata[i_qwiz].hangman[i_question].hangman_final_entry     = [];
         qwizdata[i_qwiz].hangman[i_question].hangman_current_entry   = [];
         qwizdata[i_qwiz].hangman[i_question].hangman_incorrect_chars = [];
         qwizdata[i_qwiz].hangman[i_question].hangman_incorrect_chars_before_hint
                                                                      = [];
         qwizdata[i_qwiz].hangman[i_question].hangman_n_hints         = [];
      }
      if (i_choice == 0) {
         qwizdata[i_qwiz].hangman[i_question].n_hangman               = n_hangman;
         qwizdata[i_qwiz].hangman[i_question].hangman_max_hints       = hangman_max_hints;
         qwizdata[i_qwiz].hangman[i_question].hangman_type_letters    = hangman_type_letters;
         qwizdata[i_qwiz].hangman[i_question].n_hangman_done          = 0;
         qwizdata[i_qwiz].hangman[i_question].n_hangman_correct       = 0;
      }
      qwizdata[i_qwiz].hangman[i_question].hangman_answer[i_choice] = hangman_answer;
      if (set_qwizard_data_b) {
         qw.questions_cards[i_question].choices[i_choice] = hangman_answer;
         qw.questions_cards[i_question].correct_choice_fs[i_choice] = 1;
      }
      qwizdata[i_qwiz].hangman[i_question].hangman_n_hints[i_choice] = 0;
      var hangman_final_entry = qqc.create_hangman_entry (hangman_answer);
      qwizdata[i_qwiz].hangman[i_question].hangman_final_entry[i_choice] = hangman_final_entry;
      if (! feedback_divs[i_choice]) {
         var response = q.canned_feedback (true);
         feedback_divs[i_choice] = create_feedback_div_html (i_qwiz, i_question, i_choice, response);
         if (set_qwizard_data_b) {
            qw.questions_cards[i_question].feedbacks[i_choice] = response;
         }
      }
      var input_value = new Array (hangman_answer_length).join ('&ensp;');
      var input_focus = q.qwizard_b ? ' onfocus="qwizard.update_hangman_options_menu_choice (this, ' + i_choice + ')"' : '';
      var disabled        = '';
      var disabled_class  = '';
      if (q.qwizard_b && hangman_answer == 'placeholder') {
         disabled       = ' disabled';
         disabled_class = ' qwiz_button_disabled';
      }
      var hangman_span =  '<span class="qwiz_hangman qwiz_hangman_c' + i_choice + '" onkeyup="' + qname + '.hangman_show (this, true)" onmouseenter="' + qname + '.hangman_show (this)" onmouseleave="' + qname + '.hangman_hide (this)">'
                        +    '<span class="hangman_current_entry hangman_entry">'
                        +    '</span>'
                        +    '<input type="text" oninput="' + qname + '.hangman_keyup (this, event,\' ' + input_value + '\', ' + i_qwiz + ', ' + i_question + ', ' + i_choice + ')" onblur="' + qname + '.hangman_hide (this.parentElement)"' + input_focus + disabled + ' />';
      if (hangman_max_hints) {
         var title;
         if (hangman_max_hints < hangman_answer.replace (/[^a-z0-9]/i, '').length) {
            title = ' title="' + T ('You can get') + ' ' + qqc.number_to_word (hangman_max_hints) + ' ' + T ('hint') + ' ' + Tplural ('letter', 'letters', hangman_max_hints) + ', ' + T ('but your answer will count as incorrect') + '"';
         } else {
            title = ' title="' + T ('You can get hints, but your answer will count as incorrect') + '"';
         }
         hangman_span  +=     '<button id="hangman_hint-qwiz' + i_qwiz + '-q' + i_question + '-c' + i_choice + '" class="qwiz_button hangman_hint' + disabled_class + '" onmouseenter="' + qname + '.hangman_show (this.parentElement)" onclick="' + qname + '.hangman_hint (' + i_qwiz + ', ' + i_question + ', ' + i_choice + ')"' + title + disabled + '>'
                        +        T ('Hint')
                        +    '</button>\n';
      }
      if (hangman_type_letters && ! q.qwizard_b
                       && (! hangman_labeled_diagram_f
                             || (hangman_labeled_diagram_f && i_choice == 0))) {
         hangman_span  +=    '<span class="hangman_type_letters">'
                        +    '</span>';
      }
      hangman_span     +=    '<span class="hangman_status">'
                        +    '</span>'
                        + '</span>';
      if (q.qwizard_b && ! hangman_labeled_diagram_f) {
         hangman_spans.push (hangman_span);
      } else {
         question_htm = question_htm.replace (hangman_re, hangman_span);
      }
      i_choice++;
   }
   question_htm = question_htm.replace (/\[(<code><\/code>)*q[^\]]*\]/, '');
   if (i_choice != n_hangman) {
      errmsgs.push ('Number of [hangman] shortcodes does not match number of hangman words [c].  qwiz: ' + (1 + i_qwiz) + ', ' + T ('question') + ' ' + (1 + i_question));
   }
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].n_choices = n_hangman;
   }
   var new_htm;
   if (q.qwizard_b && ! hangman_labeled_diagram_f) {
      new_htm = create_hangman_textentry_editable_divs (i_qwiz, i_question,
                                                        labeled_diagram_opening_tags, hangman_spans,
                                                        'hangman', hangman_re,
                                                        question_htm);
   } else {
      if (q.qwizard_b) {
         var canvas_pos = question_htm.search (/<div[^>]+qwizzled_canvas/);
         if (canvas_pos != -1) {
            var hangman_fields_pos = question_htm.search (/<div[^>]+hangman_labeled_diagram_fields/);
            if (hangman_fields_pos != 1) {
               var hangman_fields = qqc.find_matching_block (question_htm.substr (hangman_fields_pos));
               question_htm = question_htm.replace (hangman_fields, '');
               question_htm = question_htm.substr (0, canvas_pos) + hangman_fields
                              + question_htm.substr (canvas_pos);
            }
         }
      }
      var bg_img_style = create_bg_img_style (i_qwiz, i_question);
      new_htm =   '<div id="qwiz' + i_qwiz + '-q' + i_question + '" class="qwizq"' + bg_img_style + '>\n'
                +    labeled_diagram_opening_tags + question_htm;
   }
   if (debug[0]) {
      console.log ('[process_hangman] new_htm:', new_htm);
   }
   new_htm += '<div style="clear: both;"></div>\n';
   new_htm += feedback_divs.join ('\n');
   new_htm += '<div class="qwiz_hangman_msg"></div>';
   new_htm += '</div>';
   return new_htm;
}
var first_decode_err_f = true;
function cvt_feedback (htm) {
   var c_pos = htm.indexOf ('[c]');
   if (c_pos == -1) {
      return htm;
   } else {
      var before_c = htm.substr (0, c_pos);
      var after_c  = htm.substr (c_pos + 3);
      var f_pos = after_c.indexOf ('[f]');
      if (f_pos == -1) {
         f_pos = after_c.indexOf ('[fx]');
      }
      var after_f;
      var f_cvt;
      var f = '';
      if (f_pos != -1) {
         after_f = after_c.substr (f_pos + 3);
         after_c = after_c.substr (0, f_pos);
         while (true) {
            f_pos = after_f.indexOf ('[f]');
            if (f_pos == -1) {
               f_cvt = after_f;
            } else {
               f_cvt = after_f.substr (0, f_pos);
               after_f = after_f.substr (f_pos + 3);
            }
            var r = feedback_decode (f_cvt);
            var more_f = r.c;
            if (more_f) {
               f += '[f]' + more_f;
            }
            if (f_pos == -1) {
               break;
            }
         }
      }
      var r = feedback_decode (after_c);
      var sc = '[c' + r.star + ']';
      var c = r.c;
      /*
      if (first_decode_err_f) {
         c = '[Sorry, missing question data. Please contact <a href="mailto: support@qwizcards.com">support@qwizcards.com</a>]';
         first_decode_err_f = false;
      }
      console.log ('[cvt_feedback] unable to convert:', after_c);
      */
   }
   var c_htm = before_c + sc + c + f;
   return c_htm;
}
function feedback_decode (s) {
   var tag_pos = s.indexOf ('[Qq]');
   var s_to_decode;
   var s_rest = '';
   if (tag_pos != -1) {
      s_to_decode = s.substr (0, tag_pos);
      s_rest      = s.substr (tag_pos + 4);
   } else {
      s_to_decode = s;
   }
   if (debug[2]) {
      console.log ('[feedback_decode] s_to_decode:', s_to_decode);
   }
   var star = '';
   var decoded_s = '';
   var fparts = s_to_decode.split (/<[^>]+>/);
   if (fparts.length > 1) {
      var splits = s_to_decode.match (/<[^>]+>/g)
      var decoded_parts = [];
      var n_splits = splits.length;
      var first_f = true;
      for (var i=0; i<n_splits; i++) {
         var fpart = fparts[i];
         if (fpart != '') {
            if (first_f) {
               first_f = false;
               if (/ /.test (fpart)) {
                  star = '*';
               }
            }
            try {
               decoded_parts.push (Base64.decode (fpart));
            } catch (e) {};
         }
         decoded_parts.push (splits[i]);
      }
      try {
         var fpart = fparts[n_splits];
         if (first_f) {
            if (/ /.test (fpart)) {
               star = '*';
            }
         }
         decoded_parts.push (Base64.decode (fpart));
      } catch (e) {};
      decoded_s = decoded_parts.join ('');
   } else {
      if (/ /.test (s_to_decode)) {
         star = '*';
      }
      try {
         decoded_s = Base64.decode (s_to_decode);
      } catch (e) {};
   }
   decoded_s += s_rest;
   if (debug[2]) {
      console.log ('[feedback_decode] decoded_s:', decoded_s);
   }
   return {c: decoded_s, star: star};
}
this.hangman_show = function (qwiz_hangman_el, keyup_f) {
   if (suppress_hangman_hint_b) {
      suppress_hangman_hint_b = false;
      return false;
   }
   var $qwiz_hangman = $ (qwiz_hangman_el);
   $qwiz_hangman.find ('span.hangman_type_letters').hide ();
   clearTimeout (qwiz_hangman_el.i_hint_timeout);
   if (keyup_f) {
      var hide_hint_button = function () {
         $qwiz_hangman.find ('button.hangman_hint, span.hangman_status').fadeOut (1000);
      }
      qwiz_hangman_el.i_hint_timeout = setTimeout (hide_hint_button, 750);
   }
   if (! qwiz_hangman_el.done_f) {
      $qwiz_hangman.find ('button.hangman_hint').show ();
      var $hangman_status =  $qwiz_hangman.find ('span.hangman_status')
      if ($hangman_status.html ()) {
         $hangman_status.show ();
      }
   }
}
this.hangman_hide = function (qwiz_hangman_el) {
   var $qwiz_hangman = $ (qwiz_hangman_el);
   var hide_hint_button = function () {
      $qwiz_hangman.find ('button.hangman_hint, span.hangman_status').fadeOut (500);
   }
   qwiz_hangman_el.i_hint_timeout = setTimeout (hide_hint_button, 100);
}
function create_hangman_textentry_editable_divs (i_qwiz, i_question,
                                                 opening_tags, span_inputs,
                                                 hangman_textentry, re, htm) {
   htm = qqc.shortcodes_to_video_elements (htm);
   var new_htm = [];
   var bg_img_style = create_bg_img_style (i_qwiz, i_question);
   new_htm.push ('<div id="qwiz' + i_qwiz + '-q' + i_question + '" class="qwizq qwizard_line_height"' + bg_img_style + '>\n');
   if (typeof qwizdata[i_qwiz].parts_htm[i_question] == 'undefined') {
      qwizdata[i_qwiz].parts_htm[i_question] = []
   }
   var t_pos = 0;
   var t_block;
   var i_part = 1;
   var htm_length = htm.length;
   while (true) {
      var remaining_htm = htm.substr (t_pos);
      var ii_pos = remaining_htm.search (re);
      if (ii_pos == -1) {
         break;
      }
      t_pos = t_pos + ii_pos;
      var part_htm = remaining_htm.substr (0, ii_pos);
      if (i_part == 1) {
         part_htm = opening_tags + part_htm;
      }
      part_htm = qqc.remove_unmatched_tag (part_htm, i_part == 1);
      if (part_htm.search (/\S/) == -1) {
         part_htm = '&nbsp;';
      }
      qwizdata[i_qwiz].parts_htm[i_question][i_part] = part_htm;
      var m = remaining_htm.match (re);
      if (debug[9]) {
         console.log ('[create_hangman_textentry_editable_divs] m[0]:', m[0]);
      }
      t_pos += m[0].length;
      new_htm.push ('<div class="qwiz-question qwiz-question-' + hangman_textentry + ' qwiz-parts qwiz-part' + i_part + ' qwiz-inline qwiz_editable" data-i_part="' + i_part + '">\n');
      new_htm.push (   part_htm);
      new_htm.push ('</div>');
      new_htm.push (span_inputs[i_part-1]);
      i_part++;
   }
   var part_htm = htm.substr (t_pos);
   part_htm = qqc.remove_unmatched_tag (part_htm, false, true);
   if (part_htm.search (/\S/) == -1) {
      part_htm = '&nbsp;';
   }
   qwizdata[i_qwiz].parts_htm[i_question][i_part] = part_htm;
   new_htm.push ('<div class="qwiz-question qwiz-question-' + hangman_textentry + ' qwiz-parts qwiz-part' + i_part + ' qwiz-inline qwiz_editable" data-i_part="' + i_part + '">\n');
   new_htm.push (   part_htm);
   new_htm.push ('</div>');
   return new_htm.join ('');
}
this.hangman_keyup = function (input_el, event, default_value, i_qwiz, i_question, i_choice) {
   if (qwizdata[i_qwiz].user_question_number == 1
               && (q.no_intro_b[i_qwiz] || qwizdata[i_qwiz].n_questions == 1)) {
      $ ('div#icon_qwiz' + i_qwiz).hide ();
      alert_not_logged_in (i_qwiz);
   }
   var value = input_el.value;
   input_el.value = default_value;
   if (debug[9]) {
      console.log ('[hangman_keyup] value.charCodeAt:', value.charCodeAt (0), value.charCodeAt (1), value.charCodeAt (2), value.charCodeAt (3));
   }
   var keychars = value.replace (/[^a-z0-9]/gi, '');
   if (keychars == '') {
      return false;
   }
   keychars = keychars.toLowerCase ();
   if (debug[9]) {
      console.log ('[hangman_keyup] keychars:', keychars);
   }
   var current_entry = qwizdata[i_qwiz].hangman[i_question].hangman_current_entry[i_choice];
   var final_entry   = qwizdata[i_qwiz].hangman[i_question].hangman_final_entry[i_choice];
   var done_f;
   var n_chars = keychars.length;
   for (var i=0; i<n_chars; i++) {
      var keychar = keychars[i];
      var done_f = update_hangman_input (keychar, current_entry, final_entry,
                                          i_qwiz, i_question, i_choice, input_el);
      if (done_f) {
         break;
      }
   }
   if (! done_f) {
      $ ('div#qwiz' + i_qwiz + '-q' + i_question + ' div.qwiz-feedback').hide ();
      $ (input_el).parents ('div.qwizq').find('div.qwiz_hangman_msg').hide ();
      if (qwizdata[i_qwiz].hangman[i_question].hangman_incorrect_chars[i_choice]) {
         var hangman_incorrect_chars_before_hint
                = qwizdata[i_qwiz].hangman[i_question].hangman_incorrect_chars_before_hint[i_choice];
         var hangman_incorrect_chars_display
            = qqc.create_hangman_incorrect_chars_display (qwizdata[i_qwiz].hangman[i_question].hangman_incorrect_chars[i_choice], hangman_incorrect_chars_before_hint, true);
         $ (input_el).parents ('span.qwiz_hangman.qwiz_hangman_c' + i_choice).find ('span.hangman_status').html (hangman_incorrect_chars_display).show ();
      }
   }
   return true;
}
function update_hangman_input (keychar, current_entry, final_entry,
                               i_qwiz, i_question, i_choice, input_el, hint_f) {
   var done_f = false;
   var good_char_b = false;
   var i_pos = -1;
   var re = new RegExp ('u>' + keychar + '</u', 'i');
   while (true) {
      var m = final_entry.substr (i_pos + 1).match (re);
      if (! m ) break;
      i_pos += m.index + 2;
      current_entry = qqc.setCharAt (current_entry, i_pos + 1, m[0][2]);
      good_char_b = true;
   }
   if (debug[9]) {
      console.log ('[update_hangman_input] keychar:', keychar, ', good_char_b:', good_char_b);
   }
   var hangman = qwizdata[i_qwiz].hangman[i_question];
   var hangman_incorrect_chars = hangman.hangman_incorrect_chars[i_choice];
   if (good_char_b) {
      hangman.hangman_current_entry[i_choice] = current_entry;
      var local_current_entry = current_entry.replace (/\t/g, '&ensp;');
      $ (input_el).parents ('span.qwiz_hangman.qwiz_hangman_c' + i_choice).find ('span.hangman_current_entry').html (local_current_entry);
      var done_f = current_entry.indexOf ('<u>\t</u>') == -1;
      var all_done_f;
      if (done_f) {
         var n_hangman = hangman.n_hangman;
         hangman.n_hangman_done++;
         all_done_f = hangman.n_hangman_done == n_hangman;
         $ (input_el).attr ('disabled', true);
         input_el.parentElement.done_f = true;
         var correct_b = hangman_incorrect_chars.length <= 3
                                      && hangman.hangman_n_hints[i_choice] == 0;
         if (correct_b) {
            hangman.n_hangman_correct++;
            if (n_hangman > 1) {
               $ ('div#qwiz' + i_qwiz + '-q' + i_question + ' div.qwiz-feedback').hide ();
               $ ('div#qwiz' + i_qwiz + '-q' + i_question + ' div.qwiz_hangman_msg').hide ();
            }
            $ ('#qwiz' + i_qwiz + '-q' + i_question + '-a' + i_choice).show ();
            if (all_done_f && hangman.n_hangman_correct == n_hangman ) {
               if (! q.qwizard_b) {
                  qwizdata[i_qwiz].n_correct++;
               }
            }
         } else {
            if (n_hangman > 1) {
               $ ('div#qwiz' + i_qwiz + '-q' + i_question + ' div.qwiz-feedback').hide ();
            }
            var msg;
            if (qwizdata[i_qwiz].repeat_incorrect_b) {
               msg = '<hr />' + T ('Now you\'ve got it, but remember your answer -- you\'ll see this word again soon.');
            } else {
               msg = '<hr />' + T ('Sorry, you entered more than three incorrect letters.');
            }
            $ ('div#qwiz' + i_qwiz + '-q' + i_question + ' div.qwiz_hangman_msg').html (msg).show ();
            if (all_done_f && hangman.n_hangman_correct != n_hangman ) {
               if (! q.qwizard_b) {
                  qwizdata[i_qwiz].n_incorrect++;
               }
            }
         }
         if (all_done_f) {
            if (! q.qwizard_b) {
               qwizdata[i_qwiz].answered_correctly[i_question]
                              = n_hangman == hangman.n_hangman_correct ? 1 : -1;
            }
            input_el.blur ();
            if (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b) {
               var hangman_answer = hangman.hangman_answer[i_choice];
               var data = {q_and_a_text:  btoa (encodeURIComponent (qwizdata[i_qwiz].q_and_a_text[i_question])),
                           q_and_a_crc32: qwizdata[i_qwiz].q_and_a_crc32[i_question],
                           i_question:    qwizdata[i_qwiz].dataset_id[i_question],
                           unit:          qwizdata[i_qwiz].unit[i_question],
                           type:          'hangman',
                           response:      hangman_answer,
                           correct_b:     correct_b ? 1 : '',
                           confirm:       'js'};
               qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
            }
            update_topic_statistics (i_qwiz, i_question, correct_b);
            update_progress_show_next (i_qwiz);
         } else {
            var first_f = true;
            for (var i_choice=0; i_choice<n_hangman; i_choice++) {
               if (hangman.hangman_current_entry[i_choice]
                                     != hangman.hangman_final_entry[i_choice]) {
                  var $qwiz_hangman = $ ('div#qwiz' + i_qwiz + '-q' + i_question + ' span.qwiz_hangman.qwiz_hangman_c' + i_choice);
                  if (first_f) {
                     $qwiz_hangman.find ('input').focus ();
                     first_f = false;
                  } else {
                     $qwiz_hangman.find ('span.hangman_type_letters').show ();
                  }
               }
            }
         }
         done_f = true;
      }
   } else {
      keychar = keychar.toLowerCase ();
      if (hangman_incorrect_chars.indexOf (keychar) == -1) {
         hangman_incorrect_chars += keychar;
      }
      hangman.hangman_incorrect_chars[i_choice] = hangman_incorrect_chars;
      if (debug[9]) {
         console.log ('[hangman_keyup] hangman_incorrect_chars:', hangman_incorrect_chars);
      }
   }
   return done_f;
}
this.hangman_hint = function (i_qwiz, i_question, i_choice) {
   qwizdata[i_qwiz].hangman[i_question].hangman_n_hints[i_choice]++;
   if (qwizdata[i_qwiz].hangman[i_question].hangman_n_hints[i_choice] > qwizdata[i_qwiz].hangman[i_question].hangman_max_hints) {
      return false;
   } else if (qwizdata[i_qwiz].hangman[i_question].hangman_n_hints[i_choice] == qwizdata[i_qwiz].hangman[i_question].hangman_max_hints) {
      $ ('#hangman_hint-qwiz' + i_qwiz + '-q' + i_question + '-c' + i_choice)
         .attr ('disabled', true)
         .addClass ('qwiz_button_disabled');
   } else {
      qwizdata[i_qwiz].hangman[i_question].hangman_incorrect_chars_before_hint[i_choice]
                  = qwizdata[i_qwiz].hangman[i_question].hangman_incorrect_chars[i_choice].length;
   }
   var hangman_answer = qwizdata[i_qwiz].hangman[i_question].hangman_answer[i_choice];
   var current_entry = qwizdata[i_qwiz].hangman[i_question].hangman_current_entry[i_choice];
   var matches = current_entry.match (/<u>.<\/u>/g);
   var i_pos = matches.indexOf ('<u>\t</u>');
   if (debug[9]) {
      console.log ('[hangman_hint] matches:', matches);
      console.log ('[hangman_hint] i_pos:', i_pos);
   }
   if (i_pos != -1) {
      var final_entry = qwizdata[i_qwiz].hangman[i_question].hangman_final_entry[i_choice];
      matches = final_entry.match (/<u>.<\/u>/g);
      var hint_char = matches[i_pos][3];
      var qwizq_id = 'qwiz' + i_qwiz + '-q' + i_question;
      var $qwizq = $ ('div#' + qwizq_id);
      var $hangman_input = $qwizq.find ('span.qwiz_hangman.qwiz_hangman_c' + i_choice + ' input');
      var input_el = $hangman_input[0];
      update_hangman_input (hint_char, current_entry, final_entry,
                            i_qwiz, i_question, i_choice, input_el, true);
      $hangman_input.focus ();
   }
}
function single_char_textentry_keyup (e) {
   var input_el = e.target;
   if (debug[6]) {
      console.log ('[single_char_textentry_keyup] input_el:', input_el);
   }
   var value = input_el.value;
   if (value.search (/[a-z0-9]/i) == -1) {
      input_el.value = '';
      return false;
   }
   var id = input_el.id;
   var i_qwiz = id.match (/qwiz([0-9]+)/)[1];
   if (debug[6]) {
      console.log ('[single_char_textentry_keyup] i_qwiz:', i_qwiz);
   }
   if (qwizdata[i_qwiz].user_question_number == 1) {
      $ ('div#icon_qwiz' + i_qwiz).hide ();
      alert_not_logged_in (i_qwiz);
   }
   q.textentry_check_answer (i_qwiz, true);
}
function process_feedback_item (choice_html, i_qwiz, i_question, i_choice) {
   var feedback_start_tags = ['[f]', '[fx]'];
   var feedback_next_tags  = ['[f]', '[fx]', '[x]'];
   if (debug[2]) {
      console.log ('[process_feedback_item] choice_html: ', choice_html);
   }
   var feedback_item_html = qqc.parse_html_block (choice_html, feedback_start_tags,
                                                  feedback_next_tags);
   var feedback_div = '';
   var fx_b;
   if (feedback_item_html != 'NA') {
      choice_html = choice_html.replace (feedback_item_html, '');
      if (debug[2]) {
         console.log ('[process_feedback_item] feedback_item_html: ', feedback_item_html);
      }
      fx_b = feedback_item_html.indexOf ('[fx]') != -1;
      feedback_item_html = feedback_item_html.replace (/\[fx{0,1}\]/, '');
      feedback_div = create_feedback_div_html (i_qwiz, i_question, i_choice,
                                               feedback_item_html);
   } else {
      feedback_item_html = '';
   }
   if (debug[2]) {
      console.log ('[process_feedback_item] feedback_div:', feedback_div);
      console.log ('[process_feedback_item] choice_html: ', choice_html);
      console.log ('[process_feedback_item] fx_b:        ', fx_b);
   }
   return {'feedback_div':       feedback_div,
           'choice_html':        choice_html,
           'feedback_item_html': feedback_item_html,
           'fx_b':               fx_b};
}
function process_qwizzled (i_qwiz, i_question, question_htm, opening_tags,
                           question_shortcode) {
   if (debug[0]) {
      console.log ('[process_qwizzled] question_htm: ', question_htm);
   }
   var labels_position = '';
   var m = question_shortcode.match (/\[(<code><\/code>)*q([^\]]*)\]/m);
   if (m) {
      var attributes = m[2];
      if (attributes) {
         attributes = qqc.replace_smart_quotes (attributes);
         labels_position = get_attr (attributes, 'labels');
         labels_position = labels_position.toLowerCase ();
         if (debug[0]) {
            console.log ('[process_qwizzled] labels_position:', labels_position);
         }
      }
   }
   var new_htm  = '<div id="qwiz' + i_qwiz + '-q' + i_question + '" '
   if (q.qwizard_b) {
      question_htm = qqc.shortcodes_to_video_elements (question_htm);
   } else {
      new_htm     +=   'onmouseover="' + qname + '.init_drag_and_drop (this)" ';
   }
   new_htm +=          'class="qwizq qwizzled">'
             +    '<table class="qwizzled_table">'
             +    '<tr class="qwizzled_table">' + question_htm;
   if (debug[0]) {
      console.log ('[process_qwizzled] new_htm: ', new_htm);
   }
   var canvas_div_pos = new_htm.indexOf ('<div class="qwizzled_canvas');
   if (canvas_div_pos == -1) {
      errmsgs.push (T ('Did not find target "drop-zones" for labels.  Please check that all labels and target "drop zones" were correctly processed and saved during the edit of this page') + '.  qwiz: ' + (i_qwiz + 1) + ', ' + T ('question') + ' ' + (i_question + 1));
      return '';
   }
   var div_html = qqc.find_matching_block (new_htm.substr (canvas_div_pos));
   if (! div_html) {
      errmsgs.push ('Did not find end of image area.  Please check that all labels and target "drop zones" were correctly processed and saved during the edit of this page.');
      return '';
   }
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].type = 'labeled_diagram';
      qw.questions_cards[i_question].question_text = div_html;
      qw.questions_cards[i_question].labels = [];
      qw.questions_cards[i_question].feedback_corrects = [];
      qw.questions_cards[i_question].feedback_incorrects = [];
   }
   var remaining_htm = new_htm.substr (canvas_div_pos + div_html.length);
   new_htm = new_htm.substr (0, canvas_div_pos + div_html.length);
   var qwizq_id = 'qwizzled_canvas-qwiz' + i_qwiz + '-q' + i_question;
   var td_canvas = '<td class="qwizzled_table"><div id="' + qwizq_id + '"' + div_html.substring (4) + '</td>';
   td_canvas = td_canvas.replace ('class="', 'class="' + qwizq_id + ' ');
   if (qwizdata[i_qwiz].qrecord_id) {
      if (qqc.isInteger (qwizdata[i_qwiz].dataset_id[i_question])) {
         var question_htm_wo_tags = qqc.remove_tags_eols (question_htm);
         qwizdata[i_qwiz].q_and_a_text[i_question]  = qqc.q_and_a_hash (question_htm_wo_tags);
         qwizdata[i_qwiz].q_and_a_crc32[i_question] = qwiz_crc32 (question_htm);
         if (debug[0]) {
            console.log ('[process_qwizzled] qwizdata[i_qwiz].q_and_a_text[i_question]:', qwizdata[i_qwiz].q_and_a_text[i_question]);
            console.log ('[process_qwizzled] qwizdata[i_qwiz].q_and_a_crc32[i_question]:', qwizdata[i_qwiz].q_and_a_crc32[i_question]);
         }
      } else {
         qwizdata[i_qwiz].q_and_a_text[i_question]  = qwizdata[i_qwiz].dataset_id[i_question];
         qwizdata[i_qwiz].q_and_a_crc32[i_question] = 'dataset';
      }
   }
   var td_labels_style = '';
   if (labels_position == 'left') {
      td_labels_style = ' style="padding-right: 5px;"'
   }
   var td_labels_add_class = '';
   if (labels_position != 'top' && labels_position != 'bottom') {
      td_labels_add_class = ' qwizzled_labels_left_right';
   }
   var td_labels   = '<td class="qwizzled_table qwizzled_labels' + td_labels_add_class + '"' + td_labels_style + '>'
                   +    '<div class="qwizzled_labels_border">'
                   +        'Q-LABELS-Q'
                   +        '<div style="clear: both;"></div>\n'
                   +    '</div>'
                   + '</td>';
   var td_feedback = '<td class="qwizzled_table qwizzled_feedback" colspan="2">QWIZZLED-FEEDBACK-Q</td>';
   var table_html;
   if (labels_position == "top") {
      table_html =            td_labels + '</tr>'
                   + '<tr class="qwizzled_table">' + td_canvas + '</tr>';
   } else if (labels_position == "bottom") {
      table_html =            td_canvas + '</tr>'
                   + '<tr class="qwizzled_table">' + td_labels + '</tr>';
   } else if (labels_position == "left") {
      table_html =            td_labels + td_canvas + '</tr>';
                   + '<tr class="qwizzled_table">' + td_canvas + '</tr>';
   } else {
      table_html =            td_canvas + td_labels + '</tr>';
                   + '<tr class="qwizzled_table">' + td_canvas + '</tr>';
   }
   if (debug[0]) {
      console.log ('[process_qwizzled] table_html.substr (0, 100):', table_html.substr (0, 100));
   }
   table_html +=      '<tr class="qwizzled_table">' + td_feedback + '</tr>'
                 + '</table>';
   new_htm = new_htm.replace (div_html, table_html);
   new_htm = new_htm.replace (/\[(<code><\/code>)*q[^\]]*\]/, '');
   if (debug[0]) {
      console.log ('[process_qwizzled] new_htm:', new_htm);
      console.log ('[process_qwizzled] remaining_htm:', remaining_htm);
   }
   remaining_htm = remaining_htm.replace (/\[(<code><\/code>)*l\]/gm, '');
   var label_divs = [];
   var i_label = 0;
   while (true) {
      var label_div_pos = remaining_htm.search (/<div[^>]+class=".*?qwizzled_label/m);
      if (label_div_pos == -1) {
         break;
      }
      var label_div = qqc.find_matching_block (remaining_htm.substr (label_div_pos));
      if (debug[0]) {
         console.log ('[process_qwizzled] label_div:', label_div);
      }
      var new_label_div = '<li><div id="label-qwiz' + i_qwiz + '-q' + i_question + '-a' + i_label + '"'
                          + label_div.substr (4) + '</li>';
      label_divs.push (new_label_div);
      if (set_qwizard_data_b) {
         qw.questions_cards[i_question].labels.push (label_div);
      }
      remaining_htm = remaining_htm.replace (label_div, '');
      i_label++;
   }
   if (debug[0]) {
      console.log ('[process_qwizzled] label_divs:', label_divs);
   }
   var label_head =   '<p class="qwizzled_label_head qwizzled_label_head_standard">Move each item to its correct <span class="qwizzled_target_border">place</span></p>\n'
                    + '<p class="qwizzled_label_head qwizzled_label_head_mobile">Click an item, then its correct <span class="qwizzled_target_border">place</span></p>\n'
                    + '<p class="qwizzled_label_head qwizzled_label_head_label_clicked">Click the correct <span class="qwizzled_target_border">place</span> for the label</p>';
   var ul;
   if (labels_position == "top" || labels_position == "bottom") {
      ul = '<ul class="qwizzled_labels qwizzled_labels_inline">';
   } else {
      ul = '<ul class="qwizzled_labels qwizzled_labels_std">';
   }
   new_htm = new_htm.replace ('Q-LABELS-Q', label_head + ul + label_divs.join ('\n') + '</ul>');
   var feedback_html = remaining_htm;
   var feedback_divs = [];
   var feedback_start_tags = ['[f*]', '[fx]'];
   var feedback_next_tags =  ['[f*]', '[fx]', '[x]', '<div class="qwizzled_question_bottom_border_title"'];
   var i_item = 0;
   while (true) {
      var feedback_item_html
                       = qqc.parse_html_block (feedback_html, feedback_start_tags,
                                               feedback_next_tags);
      if (feedback_item_html == 'NA') {
         break;
      }
      feedback_html = feedback_html.replace (feedback_item_html, '');
      var c_x;
      if (feedback_item_html.search (/\[f\*\]/) != -1) {
         c_x = 'c';
      } else {
         c_x = 'x';
      }
      feedback_item_html = feedback_item_html.replace (/\[f[\*x]\]/, '');
      if (debug[2]) {
         console.log ('[process_qwizzled] feedback_item_html: ', feedback_item_html);
      }
      feedback_divs.push (
            create_feedback_div_html (i_qwiz, i_question, parseInt (i_item/2, 10),
                                      feedback_item_html, c_x)
      );
      if (set_qwizard_data_b) {
         if (c_x == 'c') {
            qw.questions_cards[i_question].feedback_corrects.push (feedback_item_html);
         } else {
            qw.questions_cards[i_question].feedback_incorrects.push (feedback_item_html);
         }
      }
      i_item++;
   }
   if (debug[0]) {
      console.log ('[process_qwizzled] feedback_divs:', feedback_divs);
      console.log ('[process_qwizzled] feedback_html:', feedback_html);
   }
   var n_labels = label_divs.length;
   var n_feedback_items = feedback_divs.length;
   if (n_labels*2 != n_feedback_items) {
      errmsgs.push (T ('Number of feedback items') + ' (' + n_feedback_items + ') ' + T ('does not match number of labels') + ' (' + n_labels + ').  qwiz: ' + (1 + i_qwiz) + ', question ' + (1 + i_question) + ' labeled diagram' + '\n'
                    + '(' + T ('There should be two feedback items -- correct and incorrect -- for each label') + ')');
   }
   var htm = '<div class="qwiz-feedback" id="qwiz' + i_qwiz + '-q' + i_question + '-ff"></div>\n';
   feedback_divs.push (htm);
   new_htm = new_htm.replace ('QWIZZLED-FEEDBACK-Q', feedback_divs.join (''));
   new_htm += '</div>\n';
   if (set_qwizard_data_b) {
      qw.questions_cards[i_question].n_labels = n_labels;
   }
   if (debug[2]) {
      console.log ('[process_qwizzled] new_htm: ', new_htm);
   }
   return new_htm;
}
this.init_drag_and_drop = function (qwizq_elm) {
   if (debug[0]) {
      console.log ('[init_drag_and_drop] qwizq_elm:', qwizq_elm);
   }
   var $qwizq = $ (qwizq_elm);
   $qwizq.removeAttr ('onmouseover');
   $qwizq.find ('td.qwizzled_labels div.qwizzled_label').each (function () {
      if (debug[0] || debug[8]) {
         console.log ('[init_drag_and_drop] $ (this):', $ (this));
         console.log ('[init_drag_and_drop] \'td.qwizzled_labels div.qwizzled_label\':', $ (this)[0]);
      }
      var label_offset = $ (this).offset ();
      if (debug[8]) {
         console.log ('[init_drag_and_drop] label_offset:', label_offset);
      }
      $ (this).data ('label_x', label_offset.left).data ('label_y', label_offset.top);
      $ (this).draggable ({
         containment:   $ (this).parents ('table.qwizzled_table'),
         start:         function (e, ui) {
                           var $qwiz = $ (this).parents ('div.qwiz');
                           if ($qwiz.length) {
                              var qwiz_el = $qwiz[0];
                              if (qwiz_el.qscale_fac) {
                                 qwiz_el.qstart_left = ui.position.left;
                                 if (ui.position.top) {
                                    qwiz_el.qstart_top = ui.position.top;
                                 } else {
                                    qwiz_el.qstart_top = 0.1;
                                 }
                              }
                           }
                           q.label_dragstart ($ (this));
                        },
         drag:          function (e, ui) {
                           var $qwiz = $ (this).parents ('div.qwiz');
                           if ($qwiz.length) {
                              var qwiz_el = $qwiz[0];
                              if (qwiz_el.qscale_fac) {
                                 if (qwiz_el.qstart_top) {
                                    var scale_fac = qwiz_el.qscale_fac;
                                    ui.position.left = (ui.position.left - qwiz_el.qstart_left)/scale_fac + qwiz_el.qstart_left;
                                    ui.position.top  = (ui.position.top  - qwiz_el.qstart_top )/scale_fac + qwiz_el.qstart_top;
                                 }
                              }
                           }
                        }
      }).addClass ('qwizzled_label_unplaced');
   });
   $qwizq.find ('.qwizzled_target').droppable ({
      accept:           '.qwizzled_label',
      hoverClass:       'qwizzled_target_hover',
      drop:             function (event, ui) {
                           q.label_dropped ($ (this), ui.draggable);
                        },
      tolerance:        'pointer',
   });
}
function process_header (htm, i_qwiz, i_question, intro_b) {
   var qtags = ['[h]'];
   var qnext_tags = ['[q]', '[q ', '<div class="qwizzled_question'];
   if (intro_b != undefined) {
      qnext_tags.push ('[i]');
   }
   header_html = qqc.parse_html_block (htm.substr (0, 1000), qtags, qnext_tags, true);
   if (header_html != 'NA' && header_html != '') {
      var htmx = htm.substr (0, 200);
      htmx = qqc.trim (htmx);
      var i_pos = qqc.opening_tag_shortcode_pos ('[h]', htmx);
      htmx = htmx.substr (i_pos, 5);
      var header_htmlx = header_html.replace (/<br[^>]*>/g, '');
      header_htmlx = qqc.trim (header_htmlx).substr (0, 5);
      if (htmx != header_htmlx) {
         errmsgs.push (T ('Text before header') + ' [h].  qwiz: ' + (i_qwiz + 1));
      }
      htm = htm.replace (header_html, '');
      header_html = header_html.replace (/\[h\]/ig, '');
      header_html = qqc.balance_closing_tag (header_html);
      header_html = header_html.replace (/<(p|h[1-6])[^>]*><\/(p|h[1-6])>/g, '');
      header_html = qqc.decode_image_tags (header_html);
   }
   return htm;
}
this.display_summary_and_exit = function (i_qwiz) {
   if (! q.qwizard_b) {
      $ ('.bck-question-qwiz' + i_qwiz).css ({color: 'gray'}).addClass ('hover');
   }
   if (qwizdata[i_qwiz].summary_b) {
      var report_html = [];
      var n_questions = qwizdata[i_qwiz].n_questions_for_done;
      var n_correct   = qwizdata[i_qwiz].n_correct;
      var n_incorrect = qwizdata[i_qwiz].n_incorrect;
      if (qwizdata[i_qwiz].repeat_incorrect_b) {
         var quiz_set = qwizdata[i_qwiz].use_dataset && qwizdata[i_qwiz].dataset_intro_f ? 'set' : 'quiz';
         report_html.push ('<p><b>' + T ('Congratulations, you\'re done!') + '</b></p>');
         if (n_incorrect == 0) {
            report_html.push ('<p>' + T ('In this') + ' ' + qqc.number_to_word (n_questions) + '-' + T ('question') + ' ' + T (quiz_set) + ', ' + T ('you answered every question correctly on the first try!') + '</p>');
         } else {
            report_html.push ('<p>' + T('In finishing this') + ' ' + qqc.number_to_word (n_questions) + '-' + T ('question') + ' ' + T (quiz_set) + ', ' + T ('you entered') + ' ' + qqc.number_to_word (n_incorrect) + ' ' + T ('incorrect') + ' ' + Tplural ('answer', 'answers', n_incorrect) + '.</p>');
         }
      } else {
         if (n_incorrect == 0) {
            report_html.push ('<p>' + T ('Congratulations, you answered all questions correctly') + '.</p>');
         } else {
            report_html.push ('<p>' + T ('Your score is') + ' ' + qqc.number_to_word (n_correct) + ' ' + T ('out of') + ' ' + qqc.number_to_word (n_questions) + ' ' + T ('questions') + '.</p>');
         }
      }
      var n_topics = 0;
      if (qwizdata[i_qwiz].topics) {
         n_topics = qwizdata[i_qwiz].topics.length;
      }
      if (n_topics == 1) {
         var topic = qwizdata[i_qwiz].topics[0];
         if (topic != 'Other') {
            var all_both_n;
            if (n_questions == 1) {
               report_html.push ('<p>The question was about topic &ldquo;' + topic + '.&rdquo;</p>');
            } else {
               if (n_questions == 2) {
                  all_both_n = T ('Both');
               } else {
                  all_both_n = T ('All') + ' '+ qqc.number_to_word (n_questions);
               }
               report_html.push ('<p>' + all_both_n + ' ' + Tplural ('question', 'questions', n_questions) + ' were about topic &ldquo;' + topic + '.&rdquo;</p>');
            }
         }
      } else if (n_topics > 1 && n_incorrect > 0) {
         var incorrect_topics = [];
         for (var i_topic=0; i_topic<n_topics; i_topic++) {
            var topic = qwizdata[i_qwiz].topics[i_topic];
            var n_topic_correct = qwizdata[i_qwiz].topic_statistics[topic].n_correct;
            var n_topic_incorrect = qwizdata[i_qwiz].topic_statistics[topic].n_incorrect;
            var n_topic_items = n_topic_correct + n_topic_incorrect;
            if (n_topic_incorrect > 0) {
               topic = topic.replace (/_/g, ' ');
               var topic_text = '<strong>' + topic + '</strong>: ' + qqc.number_to_word (n_topic_incorrect) + ' ' + T ('incorrect');
               incorrect_topics.push (topic_text);
            }
         }
         var n_incorrect_topics = incorrect_topics.length;
         var topics_html = [];
         if (n_incorrect_topics > 1) {
            topics_html.push (T ('These are the topics of questions that you answered incorrectly'));
            topics_html.push ('<ul style="padding-left: 0;">');
            for (var i=0; i<n_incorrect_topics; i++) {
               topics_html.push ('<li>');
               topics_html.push (   incorrect_topics[i]);
               topics_html.push ('</li>');
            }
            topics_html.push ('</ul>');
         } else {
            topics_html.push (T ('The topic of the only question you answered incorrectly is' + '<br />'));
            topics_html.push (incorrect_topics[0]);
         }
         report_html.push (topics_html.join ('\n'));
      }
      $ ('#summary_report-qwiz' + i_qwiz).html (report_html.join ('\n'));
   }
   var $summary = $ ('#summary-qwiz' + i_qwiz);
   if (q.qwizard_b) {
      if ($summary.find ('div[contenteditable]').length == 0) {
         qwizard.init_tinymce ('div#qwiz_exit-qwiz' + i_qwiz + '.qwiz_editable');
      }
   }
   var $qwiz_img = $summary.find ('input[name="qwiz_img"]');
   if ($qwiz_img.length) {
      $qwiz_img.changeElements ('img');
   }
   if (qwizdata[i_qwiz].cv_index) {
      $summary.find ('button.qwiz_restart').remove ();
   }
   $summary.show ();
   qwizdata[i_qwiz].i_question = qwizdata[i_qwiz].n_questions;
}
function check_qwiz_tag_pairs (htm) {
   var new_htm = '';
   var matches = htm.match (/\[qwiz|\[\/qwiz\]/gm);
   if (matches) {
      var n_tags = matches.length;
      var error_b = false;
      if (n_tags % 2 != 0) {
         error_b = true;
      } else {
         for (var i=0; i<n_tags; i++) {
            if (i % 2 == 0) {
               if (matches[i] != '[qwiz') {
                  error_b = true;
                  break;
               }
            } else {
               if (matches[i] != '[/qwiz]') {
                  error_b = true;
                  break;
               }
            }
         }
      }
      if (error_b) {
         if (htm.indexOf ('more-link') != -1
                               || htm.indexOf ('search-entry-readmore') != -1) {
            var pos_qwiz = htm.indexOf ('[qwiz');
            var pos_more = htm.search (/<[pa] class="more-link/);
            if (pos_more != -1) {
               new_htm = htm.substring (0, pos_qwiz) + htm.substr (pos_more);
            } else {
               new_htm = htm;
            }
         } else {
            errmsgs.push (T ('Unmatched [qwiz] - [/qwiz] pairs.'));
         }
      }
   }
   return new_htm;
}
function create_radio_button_html (i_qwiz, i_question, i_choice, choice_tag) {
   var htm = '';
   var data_correct = '';
   var correct = 0;
   if (choice_tag == '[c*]') {
      data_correct = 'data-q="1" ';
      correct = 1;
   }
   var title   = '';
   var onclick = ' onclick="' + qname + '.process_choice (event, \'qwiz' + i_qwiz + '-q' + i_question + '-a' + i_choice + '\')"';
   if (q.qwizard_b) {
      title = ' title="Click to see feedback for this answer choice"';
   }
   htm += '<input type="radio" id="radio-qwiz' + i_qwiz + '-q' + i_question + '-a' + i_choice + '" name="qwiz' + i_qwiz + '-q' + i_question + '" ' + data_correct + ' style="border: none;"' + onclick + title + ' />\n';
   if (debug[1]) {
      console.log ('[create_radio_button_html] htm: ', htm);
   }
   return {'correct': correct,
           'htm':     htm};
}
this.process_choice = function (e, feedback_id, correct_b, got_it_more_practice_f) {
   if (e) {
      e.stopPropagation ();
   }
   var got_it_more_practice_f = got_it_more_practice_f ? 1 : 0;
   var matches = feedback_id.match (/(.*)-/);
   var qwizq_id = matches[1];
   var qwiz_id = feedback_id.match (/(qwiz.*?)-/)[1];
   i_qwiz = parseInt (qwiz_id.substr (4), 10);
   var i_question = feedback_id.match (/-q([0-9]+)-/)[1];
   if (debug[0]) {
      console.log ('[process_choice] feedback_id: ', feedback_id, ', qwizq_id: ', qwizq_id, ', i_qwiz: ', i_qwiz);
   }
   var record_start_delay = 10;
   if (qwizdata[i_qwiz].record_start_b && document_qwiz_user_logged_in_b) {
      record_start_delay = 500;
      qwizdata[i_qwiz].record_start_b = false;
      var data = {qrecord_id_ok: qwizdata[i_qwiz].qrecord_id_ok, type: 'start', confirm: 'js'};
      qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
   }
   if (q.qwizard_b) {
      var i_choice = feedback_id.match(/-a([0-9]+)$/)[1];
      var selector = 'span.choice-qwiz' + i_qwiz + '-q' + i_question + '-a' + i_choice;
      var $choice_text = $ (selector + ' span.qwiz-choice.qwiz_editable');
      var choice_el = $choice_text[0];
      qw.answer_choice_focus (choice_el);
   }
   var secure_f = qwizdata[i_qwiz].qrecord_id
                   && qwizdata[i_qwiz].use_dataset
                   && ! qqc.isInteger (qwizdata[i_qwiz].dataset_id[i_question]);
   var disabled;
   var mobile = feedback_id.substr (0, 7) == 'mobile_' ? 'mobile_' : '';
   if (mobile) {
      $ ('#' + qwizq_id + ' li.mobile_choice').hide ();
      $ ('#' + qwizq_id + ' .qwiz-feedback').hide ();
      if (! secure_f) {
         $ ('#' + feedback_id).show ();
      }
      $choice = $ ('#choice-' + feedback_id);
      $choice.show ();
      disabled = '';
   } else {
      disabled = $ ('input[name=' + qwizq_id + ']').attr ('disabled');
   }
   if (disabled == 'disabled') {
      return;
   }
   if (mobile) {
      feedback_id = feedback_id.substr (7);
      qwizq_id    = qwizq_id.substr (7);
   }
   $ ('#' + qwizq_id + ' .qwiz-feedback').hide ();
   process_choice_disable (feedback_id, qwizq_id);
   if (! secure_f) {
      $ ('#' + feedback_id).show ();
   }
   var elm = document.getElementById ('radio-' + feedback_id);
   if (elm) {
      correct_b = !! $ ('#radio-' + feedback_id).data ('q');
   }
   if (qwizdata[i_qwiz].user_question_number == 1
               && (q.no_intro_b[i_qwiz] || qwizdata[i_qwiz].n_questions == 1)) {
      $ ('div#icon_qwiz' + i_qwiz).hide ();
      alert_not_logged_in (i_qwiz);
   }
   if (secure_f || (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b)) {
      var i_choice = feedback_id.match(/-a([0-9]+)$/)[1];
      var choice_text = $ ('#' + qwizq_id + ' .choice-' + feedback_id).html ();
      if (choice_text) {
         choice_text = qqc.remove_tags_eols (choice_text);
      } else {
         choice_text = qqc.remove_tags_eols ($ ('#' + qwizq_id + ' button.qwiz_button').html ())
                       + '\t'
                       + qqc.remove_tags_eols ($ ('#' + feedback_id).html ());
      }
      var delay_jjax = function () {
         var data = {q_and_a_text:           btoa (encodeURIComponent (qwizdata[i_qwiz].q_and_a_text[i_question])),
                     q_and_a_crc32:          qwizdata[i_qwiz].q_and_a_crc32[i_question],
                     i_question:             qwizdata[i_qwiz].dataset_id[i_question],
                     unit:                   qwizdata[i_qwiz].unit[i_question],
                     type:                   'multiple_choice',
                     response:               choice_text,
                     i_choice:               i_choice,
                     correct_b:              correct_b ? 1 : '',
                     feedback_id:            mobile + feedback_id,
                     got_it_more_practice_f: got_it_more_practice_f,
                     confirm:                'js'};
         if (secure_f) {
            data.secure_f = 1;
         }
         if (q.preview_i_qwiz_plus1) {
            data.preview_no_record = 1;
         }
         if (qwizdata[i_qwiz].cv_index) {
            data.cv_index = qwizdata[i_qwiz].cv_index;
         }
         qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
      }
      setTimeout (delay_jjax, record_start_delay);
   }
   if (! secure_f) {
      q.process_choice2 (mobile + feedback_id, got_it_more_practice_f, correct_b, -1);
   }
};
function process_choice_disable (feedback_id, qwizq_id) {
   var mobile_b = feedback_id.substr (0, 6) == 'mobile';
   if (mobile_b) {
      feedback_id = feedback_id.substr (7);
      qwizq_id    = qwizq_id.substr (7);
   }
   var elm = document.getElementById ('radio-' + feedback_id);
   if (elm) {
      elm.checked = true;
      if (! q.qwizard_b && ! q.preview) {
         $ ('input[name=' + qwizq_id + ']').attr ('disabled', true);
      }
      $ ('.choices-' + qwizq_id).on('mouseover', function () {
         $ (this).css ({'cursor': 'text', 'color': 'black'});
      });
   }
}
this.process_choice2 = function (mobile_feedback_id, got_it_more_practice_f,
                                                     correct_b, feedback_html) {
   var i_question = mobile_feedback_id.match (/-q([0-9]+)-/)[1];
   if (debug[0]) {
      console.log ('[process_choice2] correct_b:', correct_b, ', feedback_html:', feedback_html);
      console.log ('[process_choice2] i_question:', i_question);
   }
   var mobile_b = mobile_feedback_id.substr (0, 6) == 'mobile';
   if (feedback_html != -1) {
      var matches = mobile_feedback_id.match (/(.*)-/);
      var qwizq_id = matches[1];
      if (preview_mode != 'show_answers') {
         $ ('#' + mobile_feedback_id).html (feedback_html).show ();
      }
      if (mobile_b) {
         $ ('#' + mobile_feedback_id.substr (7)).html (feedback_html).show ();
      }
   }
   if (! q.qwizard_b && ! q.preview) {
      qwizdata[i_qwiz].answered_correctly[i_question] = correct_b ? 1 : -1;
      if (correct_b) {
         qwizdata[i_qwiz].n_correct++;
      } else {
         qwizdata[i_qwiz].n_incorrect++;
      }
      update_topic_statistics (i_qwiz, i_question, correct_b);
   }
   if (got_it_more_practice_f == 1) {
      $ ('#' + document_qwiz_mobile + 'show_answer_got_it_or_not-qwiz' + i_qwiz + '-q' + i_question).hide ();
      $ ('#qwiz' + i_qwiz + '-q' + i_question).find ('button.show_the_answer').show ();
      if (! q.qwizard_b) {
         q.display_progress (i_qwiz);
      }
      q.next_question (i_qwiz);
   } else {
      update_progress_show_next (i_qwiz);
   }
};
this.show_answer_got_it_or_not = function (i_qwiz, i_question, show_me_button_el) {
   if (qwizdata[i_qwiz].user_question_number == 1) {
      $ ('div#icon_qwiz' + i_qwiz).hide ();
      alert_not_logged_in (i_qwiz);
   }
   if (! q.qwizard_b) {
      show_me_button_el.style.display = 'none';
   }
   $ ('#' + document_qwiz_mobile + 'qwiz' + i_qwiz + '-q' + i_question + '-a0').show ();
   if (! q.preview) {
      $ ('#' + document_qwiz_mobile + 'show_answer_got_it_or_not-qwiz' + i_qwiz + '-q' + i_question).show ();
   }
}
function create_got_it_or_not (mobile_, i_qwiz, i_question) {
   var htm = '';
   htm += '<div id="' + mobile_ + 'show_answer_got_it_or_not-qwiz' + i_qwiz + '-q' + i_question + '" class="show_answer_got_it_or_not">\n';
   htm +=    '<button class="qwiz_button" onclick="' + qname + '.process_choice (event, \'qwiz' + i_qwiz + '-q' + i_question + '-a0\', false, 1)">\n';
   htm +=       T ('Need more practice');
   htm +=    '</button>\n';
   htm +=    '&emsp;';
   htm +=    '<button class="qwiz_button" onclick="' + qname + '.process_choice (event, \'qwiz' + i_qwiz + '-q' + i_question + '-a0\', true, 1)">\n';
   htm +=       T ('Got it!');
   htm +=    '</button>\n';
   htm += '</div>\n';
   return htm;
}
function update_topic_statistics (i_qwiz, i_question, correct_b) {
   var question_topics = qwizdata[i_qwiz].question_topics[i_question];
   if (question_topics) {
      for (var ii=0; ii<question_topics.length; ii++) {
         var topic = question_topics[ii];
         if (correct_b) {
            qwizdata[i_qwiz].topic_statistics[topic].n_correct++;
         } else {
            qwizdata[i_qwiz].topic_statistics[topic].n_incorrect++;
         }
      }
   }
}
function update_progress_show_next (i_qwiz) {
   if (qwizdata[i_qwiz].n_questions > 1 || qwizdata[i_qwiz].use_dataset) {
      q.display_progress (i_qwiz);
      var n_done = qwizdata[i_qwiz].n_correct;
      if (! qwizdata[i_qwiz].repeat_incorrect_b) {
         n_done += qwizdata[i_qwiz].n_incorrect;
      }
      if (n_done == qwizdata[i_qwiz].n_questions_for_done) {
         if (qwizdata[i_qwiz].summary_b) {
            $ ('#next_button_text-qwiz' + i_qwiz).html (T ('View summary report'));
         } else {
            $ ('#summary-qwiz' + i_qwiz).show ();
         }
         if (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b) {
            if (   qwizdata[i_qwiz].use_dataset
                || qwizdata[i_qwiz].repeat_incorrect_b
                || qwizdata[i_qwiz].n_incorrect == 0  ) {
               var wait_completed = function () {
                  var data = {type: 'completed', confirm: 'js'};
                  qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
               }
               setTimeout (wait_completed, 2000);
            }
         }
      }
      if (n_done < qwizdata[i_qwiz].n_questions_for_done || qwizdata[i_qwiz].summary_b) {
         q.position_show_next_button (i_qwiz);
      }
      qwizdata[i_qwiz].next_button_show_b = true;
   }
   if (qwizdata[i_qwiz].n_questions == 1) {
      $( '#qwiz' + i_qwiz + ' div.single-question_exit').show ();
   }
}
this.display_progress = function (i_qwiz, came_from_start_b) {
   if (debug[0]) {
      console.log ('[display_progress] qwizdata[i_qwiz].i_question:', qwizdata[i_qwiz].i_question, ', came_from_start_b:', came_from_start_b);
   }
   var progress_html;
   var n_attempts = qwizdata[i_qwiz].n_correct + qwizdata[i_qwiz].n_incorrect;
   var n_done = qwizdata[i_qwiz].n_correct;
   if (! qwizdata[i_qwiz].repeat_incorrect_b) {
      n_done += qwizdata[i_qwiz].n_incorrect;
   }
   if (q.qwizard_b) {
      if (qwizdata[i_qwiz].i_question == -1 && ! came_from_start_b) {
         progress_html = T ('Questions in this quiz:') + ' ' + qwizdata[i_qwiz].n_questions;
      } else {
         var display_i_question = qwizdata[i_qwiz].i_question + 1;
         if (came_from_start_b) {
            display_i_question++;
         }
         progress_html = 'Question ' + display_i_question + ' of ' + qwizdata[i_qwiz].n_questions;
      }
   } else {
      var n_questions = qwizdata[i_qwiz].n_questions_for_done;
      var n_to_go = n_questions - n_done;
      if (n_attempts == 0) {
         progress_html = T ('Questions in this quiz:') + ' ' + n_to_go;
      } else {
         var questions;
         var incorrect;
         if (document_qwiz_mobile) {
            questions = T ('Qs');
            incorrect = T ('not');
         } else {
            questions = T ('questions');
            incorrect = T ('incorrect');
         }
         progress_html = n_questions + ' ' + questions + ', ' + n_attempts + ' ' + Tplural ('response', 'responses', n_attempts) + ', ' + qwizdata[i_qwiz].n_correct + ' ' + T ('correct') + ', ' + qwizdata[i_qwiz].n_incorrect + ' ' + incorrect + ', ' + n_to_go + ' ' + T ('to go');
      }
   }
   $ ('#progress-qwiz' + i_qwiz).html (progress_html).show ();
}
function display_qwizzled_progress (i_qwiz) {
   var i_question  = qwizdata[i_qwiz].i_question + 1;
   var n_questions = qwizdata[i_qwiz].n_questions;
   var progress_html = '';
   if (n_questions > 1 && ! qwizdata[i_qwiz].random_b) {
      progress_html = 'Q #' + i_question + '/' + n_questions + '; ';
   }
   progress_html += 'Correctly labeled ' + qwizdata[i_qwiz].n_labels_correct + ' out of ' + qwizdata[i_qwiz].n_label_targets + ' items';
   $ ('#progress-qwiz' + i_qwiz).html (progress_html).show ();
}
function create_feedback_div_html (i_qwiz, i_question, i_item, item_html, c_x) {
   var local_c_x = '';
   if (c_x != undefined) {
      local_c_x = c_x;
   }
   var htm = '<div id="qwiz' + i_qwiz + '-q' + i_question + '-a' + i_item + local_c_x + '" class="qwiz-feedback">\n';
   if (! local_c_x) {
      htm += '<hr style="margin: 0px;" />\n';
   }
   var classname = '';
   if (local_c_x) {
      if (local_c_x == 'c') {
         classname = 'qwizzled-correct_feedback';
      } else {
         classname = 'qwizzled-incorrect_feedback';
      }
      htm += '<div class="' + classname + ' qwiz_editable" data-i_choice="' + i_item + '">' + item_html + '</div>';
   } else {
      if (q.qwizard_b) {
         item_html = qqc.shortcodes_to_video_elements (item_html);
      }
      item_html = create_restart_button (i_qwiz, item_html, true);
      htm += '<span class="qwiz-feedback-span qwiz_editable" data-i_choice="' + i_item + '">' + item_html + '</span>';
   }
   htm += '<div style="clear: both;"></div>\n';
   htm += '</div>\n';
   if (debug[2]) {
      console.log ('[create_feedback_div_html] htm: ', htm);
   }
   return htm;
}
this.canned_feedback = function (correct_b) {
   var response;
   if (correct_b) {
      var i = Math.floor (Math.random () * correct.length);
      response = correct[i];
   } else {
      var i = Math.floor (Math.random () * incorrect.length);
      response = incorrect[i];
   }
   response = '<p><strong>' + response + '</strong></p>';
   if (debug[0]) {
      console.log ('[canned_feedback] response:', response);
   }
   return response;
}
var find_matching_terms = function (request, response) {
   if (qwizdata[textentry_i_qwiz].user_question_number == 1) {
      $ ('div#icon_qwiz' + textentry_i_qwiz).hide ();
      alert_not_logged_in (textentry_i_qwiz);
   }
   var entry = request.term.toLowerCase ();
   var entry_metaphone = qqc.metaphone (entry);
   if (debug[6]) {
      console.log ('[find_matching_terms] entry_metaphone; ', entry_metaphone);
   }
   var required_entry_length = 100;
   var required_metaphone_length = 100;
   var i_question = qwizdata[textentry_i_qwiz].i_question;
   var minlength = qwizdata[textentry_i_qwiz].textentry[i_question].textentry_minlength;
   for (var i=0; i<textentry_answer_metaphones[textentry_i_qwiz].length; i++) {
      if (entry[0] == textentry_answers[textentry_i_qwiz][i][0].toLowerCase ()) {
         required_entry_length = Math.min (required_entry_length, textentry_answers[textentry_i_qwiz][i].length);
         if (debug[6]) {
            console.log ('[find_matching_terms] entry[0]:', entry[0], ', textentry_answers[textentry_i_qwiz][i][0]:', textentry_answers[textentry_i_qwiz][i][0]);
         }
      }
      if (entry_metaphone[0] == textentry_answer_metaphones[textentry_i_qwiz][i][0]) {
         required_metaphone_length = Math.min (required_metaphone_length, textentry_answer_metaphones[textentry_i_qwiz][i].length);
         if (debug[6]) {
            console.log ('[find_matching_terms] textentry_answer_metaphones[textentry_i_qwiz][i]:', textentry_answer_metaphones[textentry_i_qwiz][i], ', required_metaphone_length:', required_metaphone_length);
         }
      }
   }
   if (required_entry_length == 100) {
      required_entry_length = minlength;
   } else {
      required_entry_length -= 2;
      required_entry_length = Math.min (minlength, required_entry_length);
   }
   if (required_metaphone_length != 100) {
      required_metaphone_length--;
      if (required_metaphone_length < 2) {
         required_metaphone_length = 2;
      } else if (required_metaphone_length > 4) {
         required_metaphone_length = 4;
      }
   }
   if (debug[6]) {
      console.log ('[find_matching_terms] required_entry_length:', required_entry_length, ', required_metaphone_length:', required_metaphone_length);
   }
   var deduped_entry = entry.replace (/(.)\1{2,}/gi, '\$1');
   if (deduped_entry.length < required_entry_length && entry_metaphone.length < required_metaphone_length) {
      textentry_matches[textentry_i_qwiz] = [];
      lc_textentry_matches[textentry_i_qwiz] = [];
      find_matching_terms2 (response, deduped_entry);
   } else {
      if (debug[6]) {
         console.log ('[find_matching_terms] request.term:', request.term,', entry_metaphone:', entry_metaphone, ', entry_metaphone.length:', entry_metaphone.length);
      }
      textentry_matches[textentry_i_qwiz]
            = $.map (current_question_textentry_terms_metaphones[textentry_i_qwiz],
                     function (term_i) {
         var ok_f;
         if (entry_metaphone == '') {
            ok_f = term_i[1] == ''
                             || term_i[0].toLowerCase ().indexOf (entry) === 0;
         } else {
            ok_f = term_i[1].indexOf (entry_metaphone) === 0
                             || term_i[0].toLowerCase ().indexOf (entry) === 0;
         }
         if (ok_f) {
            if (debug[6]) {
               console.log ('[find_matching_terms] term_i:', term_i);
            }
            return term_i[0];
         }
      });
      if (debug[6]) {
         console.log ('[find_matching_terms] textentry_matches[textentry_i_qwiz]:', textentry_matches[textentry_i_qwiz]);
      }
      if (qwizdata[textentry_i_qwiz].textentry[i_question].use_dict_b) {
         var plural_f = qwizdata[textentry_i_qwiz].textentry[i_question].textentry_plural_b ? 1 : 0;
         var data = {action:          'textentry_suggestions',
                     entry:           encodeURIComponent (entry),
                     entry_metaphone: encodeURIComponent (entry_metaphone),
                     n_hints:         qwizdata[textentry_i_qwiz].textentry_n_hints,
                     terms:           btoa (encodeURIComponent (JSON.stringify (textentry_matches[textentry_i_qwiz]))),
                     plural_f:        plural_f};
         var url;
         if (qwizcards_page_f) {
            url = qqc.get_qwiz_param ('server_loc', 'http://qwizcards.com/admin') + '/qwizard_textentry_suggestions.php';
         } else {
            url = qqc.get_qwiz_param ('ajaxurl', '');
         }
         $.ajax ({
            type:       'POST',
            url:        url,
            data:       data,
            dataType:   'json',
            error:      function (xhr, desc) {
                           if (debug[0]) {
                              console.log ('[find_matching_terms] error desc:', desc);
                           }
                        },
            success:    function (data) {
                           textentry_matches[textentry_i_qwiz] = data;
                           find_matching_terms2 (response, deduped_entry);
                        }
         });
      } else {
         find_matching_terms2 (response, deduped_entry);
      }
   }
}
function find_matching_terms2 (response, deduped_entry) {
   if (textentry_matches[textentry_i_qwiz].length) {
      lc_textentry_matches[textentry_i_qwiz]
         = textentry_matches[textentry_i_qwiz].map (function (item) {
                                                       return item.toLowerCase ();
                                                    });
      if (debug[6]) {
         console.log ('[find_matching_terms2] textentry_matches[textentry_i_qwiz]:', textentry_matches[textentry_i_qwiz]);
      }
   }
   if (debug[6]) {
      console.log ('[find_matching_terms2] deduped_entry.length: ', deduped_entry.length, ', textentry_matches[textentry_i_qwiz].length: ', textentry_matches[textentry_i_qwiz].length, ', qwizdata[textentry_i_qwiz].textentry_n_hints: ', qwizdata[textentry_i_qwiz].textentry_n_hints);
   }
   var i_question = qwizdata[textentry_i_qwiz].i_question;
   var minlength = qwizdata[textentry_i_qwiz].textentry[i_question].textentry_minlength;
   if (deduped_entry.length >= minlength && qwizdata[textentry_i_qwiz].textentry_n_hints < 5) {
      var lc_first_correct_answer = qwizdata[textentry_i_qwiz].textentry[i_question].first_correct_answer.toLowerCase ();
      if (typeof (lc_textentry_matches[textentry_i_qwiz]) == 'undefined'
            || lc_textentry_matches[textentry_i_qwiz].indexOf (lc_first_correct_answer) == -1) {
         $ ('#textentry_check_answer_div-qwiz' + textentry_i_qwiz + '-q' + i_question + ' button.qwiz_textentry_hint')
            .removeAttr ('disabled')
            .removeClass ('qwiz_button_disabled')
            .css ({display: 'inline-block'});
         if (q.qwizard_b) {
            $ ('#qwiz' + textentry_i_qwiz + '-q' + i_question + ' .qwiz-feedback').hide ();
         }
      }
   }
   response (textentry_matches[textentry_i_qwiz]);
}
function menu_closed (e) {
   var lc_entry = e.target.value.toLowerCase ();
   var i_question = qwizdata[textentry_i_qwiz].i_question;
   var n_hints = qwizdata[textentry_i_qwiz].textentry_n_hints;
   if (lc_entry.length < n_hints) {
      var textentry_hint_val = qwizdata[textentry_i_qwiz].textentry[i_question].first_correct_answer.substr (0, n_hints);
      e.target.value = textentry_hint_val;
   }
   if (! qwizdata[textentry_i_qwiz].check_answer_disabled_b) {
      if (debug[6]) {
         console.log ('[menu_closed] textentry_matches[textentry_i_qwiz]: ', textentry_matches[textentry_i_qwiz]);
      }
      if (typeof (lc_textentry_matches[textentry_i_qwiz]) == 'undefined'
           || lc_textentry_matches[textentry_i_qwiz].indexOf (lc_entry) == -1) {
         $ ('#textentry_check_answer_div-qwiz' + textentry_i_qwiz + '-q' + i_question + ' button.textentry_check_answer')
            .addClass ('qwiz_button_disabled')
            .html (qwizdata[textentry_i_qwiz].check_answer);
         qwizdata[textentry_i_qwiz].check_answer_disabled_b = true;
         if (q.qwizard_b) {
            $ ('#qwiz' + textentry_i_qwiz + '-q' + i_question + ' .qwiz-feedback').hide ();
         }
      }
   }
   if (show_hint_timeout[textentry_i_qwiz]) {
      var $textentry = $ ('#textentry-qwiz' + textentry_i_qwiz + '-q' + i_question);
      var n_chars = $textentry.val ().length;
      var minlength = qwizdata[textentry_i_qwiz].textentry[i_question].textentry_minlength;
      if (n_chars >= minlength) {
         clearTimeout (show_hint_timeout[textentry_i_qwiz]);
         show_hint_timeout[textentry_i_qwiz] = 0;
      }
   }
}
function menu_shown (e) {
   if (qwizdata[textentry_i_qwiz].record_start_b && document_qwiz_user_logged_in_b) {
      qwizdata[textentry_i_qwiz].record_start_b = false;
      var data = {qrecord_id_ok: qwizdata[textentry_i_qwiz].qrecord_id_ok, type: 'start', confirm: 'js'};
      qqc.jjax (qname, textentry_i_qwiz, qwizdata[textentry_i_qwiz].qrecord_id, 'record_response_v3', data);
   }
   var lc_entry = e.target.value.toLowerCase ();
   var i_question = qwizdata[textentry_i_qwiz].i_question;
   var lc_first_correct_answer = qwizdata[textentry_i_qwiz].textentry[i_question].first_correct_answer.toLowerCase ();
   if (lc_textentry_matches[textentry_i_qwiz].indexOf (lc_first_correct_answer) != -1) {
      $ ('#textentry_check_answer_div-qwiz' + textentry_i_qwiz + '-q' + i_question + ' button.qwiz_textentry_hint')
         .attr ('disabled', true)
         .addClass ('qwiz_button_disabled');
   }
   if (lc_textentry_matches[textentry_i_qwiz].indexOf (lc_entry) != -1) {
      $ ('#textentry_check_answer_div-qwiz' + textentry_i_qwiz + '-q' + i_question + ' button.textentry_check_answer')
         .removeClass ('qwiz_button_disabled')
         .html (T ('Check answer'));
      qwizdata[textentry_i_qwiz].check_answer_disabled_b = false;
   } else {
      $ ('#textentry_check_answer_div-qwiz' + textentry_i_qwiz + '-q' + i_question + ' button.textentry_check_answer')
         .addClass ('qwiz_button_disabled')
         .html (qwizdata[textentry_i_qwiz].check_answer);
      qwizdata[textentry_i_qwiz].check_answer_disabled_b = true;
   }
}
this.textentry_check_answer = function (i_qwiz, single_char_b, qwizard_i_choice) {
   var i_question = qwizdata[i_qwiz].i_question;
   var $textentry = $ ('#textentry-qwiz' + i_qwiz + '-q' + i_question);
   if (debug[6]) {
      entry = $textentry.val ();
      console.log ('[textentry_check_answer] $textentry.val ():', $textentry.val ());
   }
   if (! qwizdata[i_qwiz].textentry[i_question].textentry_suggest_b) {
      if (qwizdata[i_qwiz].record_start_b && document_qwiz_user_logged_in_b) {
         qwizdata[i_qwiz].record_start_b = false;
         var data = {qrecord_id_ok: qwizdata[i_qwiz].qrecord_id_ok, type: 'start', confirm: 'js'};
         qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
      }
      var entry = $textentry.val ();
      if (entry == '') {
         return false;
      }
   }
   if (! single_char_b) {
      if (! q.qwizard_b && ! q.preview) {
         if (qwizdata[i_qwiz].check_answer_disabled_b) {
            alert (Tcheck_answer_message);
            $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question + ' button.qwiz_textentry_hint')
               .removeAttr ('disabled')
               .removeClass ('qwiz_button_disabled')
               .css ({display: 'inline-block'});
            return;
         }
         $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question).hide ();
      }
   }
   $textentry.blur ();
   if (! q.qwizard_b && ! q.preview) {
      $textentry.attr ('disabled', true);
   }
   var entry = $textentry.val ().toLowerCase ();
   var i_choice = -1;
   var correct_b = false;
   var n_choices = qwizdata[i_qwiz].textentry[i_question].choices.length;
   var i_default_choice;
   for (var i=0; i<n_choices; i++) {
      var alts = qwizdata[i_qwiz].textentry[i_question].choices[i];
      if (alts[0] == '*') {
         i_default_choice = i;
      } else {
         var ok_f = false;
         if (q.qwizard_b && typeof (qwizard_i_choice) != 'undefined') {
            if (alts == 'Enter word' && i == qwizard_i_choice) {
               ok_f = true;
            }
         }
         if (! ok_f) {
            var lc_alts = alts.map (function (item) {
                                       return item.toLowerCase ();
                                    });
            ok_f = lc_alts.indexOf (entry) != -1;
         }
         if (ok_f) {
            correct_b = qwizdata[i_qwiz].textentry[i_question].choices_correct[i];
            i_choice = i;
            break;
         }
      }
   }
   if (i_choice == -1) {
      i_choice = i_default_choice;
   }
   if (preview_mode == 'questions_active') {
      $ ('#qwiz' + i_qwiz + '-q' + i_question + ' .qwiz-feedback').hide ();
   }
   $ ('#qwiz' + i_qwiz + '-q' + i_question + '-a' + i_choice).show ();
   if (! q.qwizard_b) {
      qwizdata[i_qwiz].answered_correctly[i_question] = correct_b ? 1 : -1;
      if (correct_b) {
         qwizdata[i_qwiz].n_correct++;
      } else {
         qwizdata[i_qwiz].n_incorrect++;
      }
      if (qwizdata[i_qwiz].qrecord_id && document_qwiz_user_logged_in_b) {
         var data = {q_and_a_text:  btoa (encodeURIComponent (qwizdata[i_qwiz].q_and_a_text[i_question])),
                     q_and_a_crc32: qwizdata[i_qwiz].q_and_a_crc32[i_question],
                     i_question:    qwizdata[i_qwiz].dataset_id[i_question],
                     unit:          qwizdata[i_qwiz].unit[i_question],
                     type:          'textentry',
                     response:      entry,
                     i_choice:      -1,
                     correct_b:     correct_b ? 1 : '',
                     confirm:       'js'};
         qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
      }
      update_topic_statistics (i_qwiz, i_question, correct_b);
   }
   update_progress_show_next (i_qwiz);
}
this.textentry_hint = function (i_qwiz) {
   clearTimeout (show_hint_timeout[i_qwiz]);
   show_hint_timeout[i_qwiz] = 0;
   qwizdata[i_qwiz].textentry_n_hints++;
   var i_question = qwizdata[i_qwiz].i_question;
   var textentry_hint_val = qwizdata[i_qwiz].textentry[i_question].first_correct_answer.substr (0, qwizdata[i_qwiz].textentry_n_hints);
   $ ('#textentry-qwiz' + i_qwiz + '-q' + i_question).val (textentry_hint_val).focus ().trigger ('keydown');
   $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question + ' button.qwiz_textentry_hint').attr ('disabled', true)
      .addClass ('qwiz_button_disabled')
      .html ('Another hint');
   var $check_answer = $ ('#textentry_check_answer_div-qwiz' + i_qwiz+ '-q' + i_question );
   var show_hint_button = function () {
      $check_answer.find ('button.qwiz_textentry_hint')
         .removeAttr ('disabled')
         .removeClass ('qwiz_button_disabled');
   }
   if (hint_timeout_sec >= 0) {
      show_hint_timeout[i_qwiz] = setTimeout (show_hint_button, hint_timeout_sec*1000);
   }
}
this.set_textentry_i_qwiz = function (e, input_el) {
   var id = input_el.id;
   textentry_i_qwiz = id.match (/[0-9]+/)[0];
   if (debug[6]) {
      console.log ('[set_textentry_i_qwiz] textentry_i_qwiz: ', textentry_i_qwiz);
   }
   e.stopPropagation ();
   if (q.qwizard_b) {
      var qwizq = id.match (/qwiz.*/)[0];
      $ ('#' + qwizq + ' .qwiz-feedback').hide ();
   }
}
this.item_selected = function () {
   var i_question = qwizdata[textentry_i_qwiz].i_question;
   $ ('#textentry_check_answer_div-qwiz' + textentry_i_qwiz + '-q' + i_question + ' button.textentry_check_answer')
      .removeClass ('qwiz_button_disabled')
      .html (T ('Check answer'));
   qwizdata[textentry_i_qwiz].check_answer_disabled_b = false;
}
this.keep_next_button_active = function () {
   next_button_active_b = true;
   $ ('.next_button').show ();
}
this.position_show_next_button = function (i_qwiz) {
   var $next_button = $ ('#next_button-qwiz' + i_qwiz);
   $next_button.show ();
}
this.hide_menu_and_display_login = function (i_qwiz, add_team_member_f,
                                            login_alt, msg, proceed_to_pay_f,
                                            pay_now_sign_up) {
   var $container = $ ('div.qwiz_icon_and_menu_container.qwiz' + i_qwiz + ' div.qwiz_icon_trigger_and_menu');
   $container.removeClass ('qwiz-hover');
   q.display_login (i_qwiz, add_team_member_f, login_alt, msg, proceed_to_pay_f,
                    pay_now_sign_up);
   var delay_reset = function () {
      $container.addClass ('qwiz-hover');
   }
   setTimeout (delay_reset, 500);
}
this.display_login = function (i_qwiz, add_team_member_f, login_alt, msg,
                               proceed_to_pay_f, pay_now_sign_up) {
   if (! login_alt) {
      login_alt = '';
   }
   if (! add_team_member_f && ! login_alt) {
      $ ('div.qwiz-usermenu_icon').removeClass ('qwiz-icon-bounce');
   }
   var i_question = qwizdata[i_qwiz].i_question;
   if (i_question == -1) {
      $ ('.intro-qwiz' + i_qwiz).hide ();
      if (! q.no_intro_b[i_qwiz]) {
         $ ('div#icon_qwiz' + i_qwiz).hide ();
      }
   } else if (i_question >= qwizdata[i_qwiz].n_questions) {
      $ ('#summary-qwiz' + i_qwiz).hide ();
   } else {
      $textentry_check_answer_div = $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_question);
      if ($textentry_check_answer_div.is (':visible')) {
         $textentry_check_answer_div.hide ();
         qwizdata[i_qwiz].textentry_check_answer_show_b = true;
      } else {
         qwizdata[i_qwiz].textentry_check_answer_show_b = false;
      }
      hide_current_question (i_qwiz, qwizdata[i_qwiz].i_user_question);
      $ ('.bbfe-qwiz' + i_qwiz).css ({visibility: 'hidden'});
      $ ('span.question-number-qwiz' + i_qwiz).css ({visibility: 'hidden'});
   }
   $ ('#next_button-qwiz' + i_qwiz).hide ();
   if (login_alt == 'progress_bars' || login_alt == 'leaderboard') {
      if (qwizdata[i_qwiz].display_pay_screen == 'register') {
         $ ('.intro-qwiz' + i_qwiz).show ();
      } else {
         qqc.create_progress_bars (qname, qwizdata, i_qwiz, login_alt);
      }
   } else if (login_alt == 'feedback') {
      qqc.create_provide_feedback_screen (qname, i_qwiz, qwizdata[i_qwiz].i_question);
   } else if (login_alt == 'use_dataset_options') {
      qqc.create_use_dataset_options (qname, qwizdata, i_qwiz);
   } else if (login_alt == 'pay') {
      qqc.create_pay_screen (qname, qwizdata, i_qwiz, msg, pay_now_sign_up);
   } else if (login_alt == 'maker_pay') {
      qqc.create_maker_pay_screen (qname, qwizdata, i_qwiz, msg);
   } else if (login_alt == 'enroll') {
      qqc.create_enroll_screen (qname, i_qwiz);
   } else if (login_alt == 'register') {
      qqc.create_register_taker_screen (qname, i_qwiz);
   } else {
      $ ('#qwiz_login-qwiz' + i_qwiz).html (get_login_html (i_qwiz, add_team_member_f, msg, proceed_to_pay_f)).show ();
      qqc.init_hide_show_password ('#qwiz_password-qwiz' + i_qwiz);
      if (! qwizdata[i_qwiz].display_pay_screen) {
         $ ('#qwiz_username-qwiz' + i_qwiz).focus ();
      }
   }
   qwizdata[i_qwiz].login_show_b = true;
}
this.login = function (i_qwiz, add_team_member_f, proceed_to_pay_f) {
   add_team_member_f = add_team_member_f ? 1 : 0;
   proceed_to_pay_f  = proceed_to_pay_f ? 1 : 0;
   $.removeCookie ('qwiz_declined_login', {path: '/'});
   document_qwiz_declined_login_b = false;
   var $username = $ ('#qwiz_username-qwiz' + i_qwiz);
   var username = $username.val ();
   if (! username ) {
      alert (T ('Please enter Login name'));
      $username.focus ();
      return false;
   }
   if (add_team_member_f) {
      var usernames = document_qwiz_username.split ('; ');
      if (usernames.indexOf (username) != -1) {
         alert ('User ' + username + ' is already on your team.');
         return false;
      }
   }
   var $password = $ ('#qwiz_password-qwiz' + i_qwiz);
   var password = $password.val ();
   if (! password) {
      alert (T ('Please enter Password'));
      $password.focus ();
      return false;
   }
   $password.blur ();
   var sha3_password = CryptoJS.SHA3 (password).toString ();
   var remember_f;
   if (add_team_member_f) {
      remember_f = document_qwiz_remember_f;
   } else {
      remember_f = $ ('#qwiz_login-qwiz' + i_qwiz + ' input[type="checkbox"]').prop('checked') ? 1 : 0;
      document_qwiz_remember_f = remember_f;
   }
   var data = {username: username, sha3_password: sha3_password, remember_f: remember_f, add_team_member_f: add_team_member_f};
   if (add_team_member_f) {
      data.previous_username = document_qwiz_username;
   }
   if (proceed_to_pay_f) {
      data.proceed_to_pay_f = '1';
   }
   qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'login', data);
   return false;
}
this.login_ok = function (i_qwiz, session_id, remember_f, proceed_to_pay_f) {
   var options = {path: '/'};
   if (remember_f == 1) {
      options.expires = 1;
   }
   $.cookie ('qwiz_session_id', document_qwiz_session_id, options);
   options.expires = 30;
   $.cookie ('qwiz_user_login', 1, options);
   document_qwiz_user_logged_in_b = true;
   var login_timeout_min = qqc.get_qwiz_param ('login_timeout_min', 40);
   options.expires = login_timeout_min/(24.0*60.0);
   $.cookie ('qwiz_current_login_lt_nmin_ago', 1, options);
   qqc.set_user_menus_and_icons ();
   if (qwiz_ && qwiz_.any_pay_quiz_f) {
      qwiz_.pay_lock_settings ();
   }
   if (qcard_ && qcard_.any_pay_deck_f) {
      qcard_.pay_lock_settings ();
   }
   $ ('#qwiz_login-qwiz' + i_qwiz).hide ();
   qwizdata[i_qwiz].login_show_b = false;
   if (q.qrecord_b) {
      for (var ii_qwiz=0; ii_qwiz<n_qwizzes; ii_qwiz++) {
         if (qwizdata[ii_qwiz].qrecord_id) {
            qwizdata[ii_qwiz].record_start_b = true;
         }
         if (qwizdata[ii_qwiz].qrecord_id) {
            qwizdata[ii_qwiz].qrecord_id_ok = 'check credit';
         }
      }
   }
   if (proceed_to_pay_f) {
      if (qwizdata[i_qwiz].pay_quiz_ok == 'paid') {
         proceed_to_pay_f = false;
      }
   }
   if (proceed_to_pay_f) {
      q.display_login (i_qwiz, false, 'pay');
   } else {
      if (qwizdata[i_qwiz].display_pay_screen) {
         $ ('.intro-qwiz' + i_qwiz).show ();
      } else {
         q.login_ok_start_quiz (i_qwiz);
      }
   }
   return false;
}
this.login_ok_start_quiz = function (i_qwiz) {
   var i_user_question = qwizdata[i_qwiz].i_user_question;
   if (i_user_question == -1) {
      q.next_question (i_qwiz);
   } else {
      redisplay_current_question (i_qwiz, i_user_question);
      qwizdata[i_qwiz].record_start_b = false;
      var data = {qrecord_id_ok: qwizdata[i_qwiz].qrecord_id_ok, type: 'start', confirm: 'js'};
      qqc.jjax (qname, i_qwiz, qwizdata[i_qwiz].qrecord_id, 'record_response_v3', data);
      if (qwizdata[i_qwiz].next_button_show_b) {
         q.position_show_next_button (i_qwiz);
      }
      if (qwizdata[i_qwiz].textentry_check_answer_show_b) {
         $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_user_question).css ({display: 'inline-block'});
      }
   }
}
this.login_not_ok = function (i_qwiz) {
   $ ('#qwiz_login-qwiz' + i_qwiz + ' p.login_error').show ();
   if (debug[0]) {
      console.log ('[login_not_ok] $ (\'#qwiz_login-qwiz' + i_qwiz + ' p.login_error\'):', $ ('#qwiz_login-qwiz' + i_qwiz + ' p.login_error'));
   }
}
this.no_login = function (i_qwiz, add_team_member_f, progress_bars_f) {
   if (! (add_team_member_f || progress_bars_f)) {
      if ($ ('#qwiz_login-qwiz' + i_qwiz + ' input[type="checkbox"]').prop('checked')) {
         $.cookie ('qwiz_declined_login', 1, {path: '/'});
         document_qwiz_declined_login_b = true;
      }
      $ ('div.qwiz-usermenu_icon').removeClass ('qwiz-icon-bounce');
   }
   $ ('#qwiz_login-qwiz' + i_qwiz).hide ();
   qwizdata[i_qwiz].login_show_b = false;
   var i_user_question = qwizdata[i_qwiz].i_user_question;
   if (i_user_question == -1) {
      if (progress_bars_f) {
         $ ('.intro-qwiz' + i_qwiz).show ();
         $ ('#next_button-qwiz' + i_qwiz).show ();
      } else {
         q.next_question (i_qwiz, true);
      }
   } else {
      redisplay_current_question (i_qwiz, i_user_question);
      if (qwizdata[i_qwiz].next_button_show_b) {
         q.position_show_next_button (i_qwiz);
      }
      if (qwizdata[i_qwiz].textentry_check_answer_show_b) {
         $ ('#textentry_check_answer_div-qwiz' + i_qwiz + '-q' + i_user_question).show ();
      }
   }
   return false;
}
function alert_not_logged_in (i_qwiz) {
   if (q.no_intro_b[i_qwiz] && qwizdata[i_qwiz].qrecord_id && $.cookie ('qwiz_user_login')) {
      var user_logged_in_b
         =    typeof (document_qwiz_user_logged_in_b) != 'undefined'
                                   && document_qwiz_user_logged_in_b
           && typeof (document_qwiz_username) != 'undefined';
      if (! user_logged_in_b) {
         if (! document_qwiz_declined_login_b
                   && ! document_qwiz_not_logged_in_alerted && ! q.qwizard_b ) {
            alert ('Note: you are not logged in. You must be logged in to receive credit.');
            document_qwiz_not_logged_in_alerted = true;
         }
      }
   }
}
this.icon_no_login = function (i_qwiz, add_team_member_f) {
   $ ('div.qwiz-usermenu_icon').removeClass ('qwiz-icon-bounce');
   if (! add_team_member_f) {
      if ($ ('#usermenu-qwiz' + i_qwiz + ' input[type="checkbox"]').prop('checked')) {
         $.cookie ('qwiz_declined_login', 1, {path: '/'});
         document_qwiz_declined_login_b = true;
      }
   }
}
function redisplay_current_question (i_qwiz, i_question) {
   if (i_question < qwizdata[i_qwiz].n_questions) {
      if (document_qwiz_mobile) {
         var $mobile_qwizq = $ ('#mobile_qwiz' + i_qwiz + '-q' + i_question);
         if ($mobile_qwizq.length) {
            $mobile_qwizq.show ();
         } else {
            $ ('#qwiz' + i_qwiz + '-q' + i_question).show ();
         }
      } else {
         $ ('#qwiz' + i_qwiz + '-q' + i_question).show ();
      }
   } else {
      $ ('#summary-qwiz' + i_qwiz).show ();
   }
   $ ('.bbfe-qwiz' + i_qwiz).css ({visibility: 'visible'});
   $ ('span.question-number-qwiz' + i_qwiz).css ({visibility: 'visible'});
}
function get_attr (htm, attr_name, plural_ok_b) {
   var attr_value = qqc.get_attr (htm, attr_name);
   if (plural_ok_b && ! attr_value) {
      attr_value = qqc.get_attr (htm, attr_name + 's');
   }
   return attr_value;
}
this.get_qwizdata = function (i_qwiz, variable) {
   return qwizdata[i_qwiz][variable];
}
this.set_qwizdata = function (i_qwiz, variable, value) {
   if (i_qwiz == -1) {
      var s = variable + ' = ' + value;
      eval (s);
   } else {
      qwizdata[i_qwiz][variable] = value;
   }
}
function T (string) {
   return qqc.T (string);
}
function Tplural (word, plural_word, n) {
   return qqc.Tplural (word, plural_word, n);
}
function inarray0 (array_of_arrays, query) {
   var len = array_of_arrays.length;
   for (var i=0; i<len; i++) {
      if (array_of_arrays[i][0] == query) {
         return true;
      }
   }
   return false;
}
};
qwizf.call (qwiz_);
