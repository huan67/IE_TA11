<?php
function qwiz_update_datasets ($html, $post_id, $prev_text, $qwizard_page) {
   global $debug;
   $html = preg_replace ('/\[<code><\/code>([ql])/', '[$1', $html, -1, $count);
   if ($debug[5]) {
      error_log ('[qwiz_update_datasets] $count: ' . $count);
   }
   list ($qwiz_qdeck_poss, $qwiz_qdeck_lens, $qdata,
         $any_dataset_id_f, $any_no_dataset_id_f, $maker_session_id)
                               = qwiz_parse_dataset_questions ($html, $post_id);
   if (! $qdata || ! $qdata['htmls']) {
      if ($debug[5]) {
         error_log ('[qwiz_update_datasets] html:' . $html);
      }
      return $html;
   } else {
      if ($any_dataset_id_f) {
         if ($post_id) {
            $prev_html = qwiz_get_previous_version ($post_id);
         } else {
            $prev_html = $prev_text;
         }
         if ($debug[5]) {
            error_log ('[qwiz_update_datasets] prev_html: ' . $prev_html);
         }
         list ($u, $u, $prev_qdata, $prev_any_dataset_id_f, $u, $u)
                                 = qwiz_parse_dataset_questions ($prev_html, 0);
         list ($qdata, $any_renumber_or_no_json_f)
                      = qwiz_compare_dataset_questions ($qdata, $prev_qdata,
                                                        $prev_any_dataset_id_f);
      }
      if ($post_id) {
         $permalink = get_permalink ($post_id);
      } else {
         $permalink = $qwizard_page;
      }
      $dataset_ids_to_blank
                  = qwiz_find_deleted_dataset_questions ($permalink,
                                                         $qdata['dataset_ids']);
      if (count ($dataset_ids_to_blank)) {
         qwiz_blank_deleted_dataset_questions ($dataset_ids_to_blank, $qdata);
      }
      if ($any_no_dataset_id_f || $any_renumber_or_no_json_f) {
         qwiz_update_dataset_ids ($qwiz_qdeck_poss, $qwiz_qdeck_lens, $qdata,
                                  $html);
      }
      if ($debug[0]) {
         error_log ('[qwiz_update_datasets] $qdata[\'new_modified\']: ' . print_r ($qdata['new_modified'], true));
      }
      if ($qdata['new_modified']) {
         if ($maker_session_id) {
            qwiz_dataset_questions_to_db ($qdata, $post_id, $maker_session_id,
                                          $permalink);
         } else {
            $update_msg = 'Warning: dataset questions/cards not added to/updated in Qwizcards database.  '
                          . 'You must log in to do so.  Click the "Q" icon. ';
            if ($post_id) {
               qwiz_save_dataset_update_msg ($post_id, $update_msg);
            }
         }
      }
   }
   return $html;
}
function qwiz_parse_dataset_questions ($html, $post_id) {
   global $debug;
   if ($post_id) {
      list ($maker_session_id, $qwizzes_questions, $qdecks_cards)
                                             = qwiz_get_dataset_json ($post_id);
   } else {
      $qwizzes_questions = '';
      $qdecks_cards      = '';
      $maker_session_id  = '';
   }
   $any_dataset_id_f    = false;
   $any_no_dataset_id_f = false;
   $qwiz_qdeck_poss = array ();
   $qwiz_qdeck_lens = array ();
   $qdata = array ('i_qwiz_qdecks'          => array (),
                   'i_qwiz_qdeck_questions' => array (),
                   'htmls'                  => array (),
                   'jsons'                  => array (),
                   'qwiz_qdecks'            => array (),
                   'datasets'               => array (),
                   'dataset_ids'            => array (),
                   'question_numbers'       => array (),
                   'units'                  => array (),
                   'topics'                 => array (),
                   'difficulties'           => array (),
                   'new_modified'           => array ()
                  );
   $i_qwiz_qdeck   = 0;
   $i_qwiz         = 0;
   $i_deck         = 0;
   $remaining_html = $html;
   $n_before_remaining = 0;
   while (preg_match ('/(\[qwiz|\[qdeck)[^\]]*?\sdataset="([^"]+)/',
                      $remaining_html, $matches, PREG_OFFSET_CAPTURE)) {
      if ($debug[6]) {
         error_log ('[qwiz_parse_dataset_questions] $matches: ' . print_r ($matches, true));
      }
      $i_qwiz_qdeck_question = 0;
      $remaining_html_qwiz_deck_pos = $matches[0][1];
      $qwiz_qdeck_pos               = $n_before_remaining + $remaining_html_qwiz_deck_pos;
      $qwiz_qdeck                   = $matches[1][0];
      $dataset                      = $matches[2][0];
      $remaining_html = substr ($remaining_html, $remaining_html_qwiz_deck_pos);
      $n_before_remaining += $remaining_html_qwiz_deck_pos;
      $questions_cards_pos = strpos ($remaining_html, ']') + 1;
      $remaining_html = substr ($remaining_html, $questions_cards_pos);
      $n_before_remaining += $questions_cards_pos;
      $end_match_f = preg_match ('/(\[\/qwiz\]|\[\/qdeck\])/', $remaining_html,
                                 $end_matches, PREG_OFFSET_CAPTURE);
      $end_questions_cards_pos = $end_matches[0][1];
      $qwiz_qdeck_end = $n_before_remaining + $end_questions_cards_pos;
      $qwiz_qdeck_html = substr ($remaining_html, 0, $end_questions_cards_pos);
      if ($debug[6]) {
         error_log ('[qwiz_parse_dataset_questions] $end_matches: ' . print_r ($end_matches, true));
         error_log ('                               $qwiz_qdeck_html: ' . $qwiz_qdeck_html);
      }
      $remaining_html = substr ($remaining_html, $end_questions_cards_pos);
      $n_before_remaining += $end_questions_cards_pos;
      $qwiz_qdeck = substr ($qwiz_qdeck, 1);
      $qwiz_qdeck_len = $qwiz_qdeck_end - $qwiz_qdeck_pos;
      if ($debug[6]) {
         error_log ('[qwiz_parse_dataset_questions] $qwiz_qdeck_len: ' . $qwiz_qdeck_len);
         error_log ('                               strlen ($qwiz_qdeck_html): ' . strlen ($qwiz_qdeck_html));
      }
      $qwiz_qdeck_poss[] = $qwiz_qdeck_pos;
      $qwiz_qdeck_lens[] = $qwiz_qdeck_len;
      $pieces = preg_split ('/((<[^\/][^>]*>\s*)*?)(\[q\]|\[q [^\]]+\]|\[x\])/',
                            $qwiz_qdeck_html, NULL,
                            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
      if ($debug[6]) {
         error_log ('[qwiz_parse_dataset_questions] $pieces: ' . print_r ($pieces, true));
      }
      $piece = $pieces[0];
      $default_unit       = qwiz_get_attr ($piece, 'unit');
      $default_topic      = qwiz_get_attr ($piece, 'topic');
      $default_difficulty = qwiz_get_attr ($piece, 'difficulty');
      if ($debug[6]) {
         error_log ('[qwiz_parse_dataset_questions] $piece: ' . $piece);
         error_log ('[qwiz_parse_dataset_questions] $default_unit: ' . $default_unit);
      }
      $n_pieces = count ($pieces);
      $i_piece = 1;
      $question_html = '';
      while ($i_piece < $n_pieces) {
         $piece = $pieces[$i_piece];
         $piece_wo_tags_whitespace = preg_replace ('/<[^>]+>*|\s*/', '', $piece);
         if ($piece_wo_tags_whitespace == '') {
            $question_html .= $piece;
            $i_piece += 2;
         }
         if ($i_piece == $n_pieces - 2 && $pieces[$i_piece] == '[x]') {
            break;
         }
         $question_shortcode = $pieces[$i_piece];
         $question_html .= $question_shortcode;
         $dataset_id = qwiz_get_attr ($question_shortcode, 'dataset_id');
         if ($dataset_id) {
            preg_match ('/[^|]+/', $dataset_id, $dmatches);
            $id_dataset_name = $dmatches[0];
            if ($id_dataset_name != $dataset) {
               $dataset_id = '';
               $any_no_dataset_id_f = true;
            } else {
               $any_dataset_id_f = true;
            }
         } else {
            $any_no_dataset_id_f = true;
         }
         $question_number = qwiz_get_attr ($question_shortcode, 'question_number');
         $unit            = qwiz_get_attr ($question_shortcode, 'unit');
         if (! $unit) {
            $unit = $default_unit;
         }
         $topic           = qwiz_get_attr ($question_shortcode, 'topic');
         if (! $topic) {
            $topic = $default_topic;
         }
         $difficulty      = qwiz_get_attr ($question_shortcode, 'difficulty');
         if (! $difficulty) {
            $difficulty = $default_difficulty;
         }
         $i_piece++;
         $question_content = $pieces[$i_piece];
         if ($debug[6]) {
            error_log ('[qwiz_parse_dataset_questions] $question_content: ' . $question_content);
         }
         if ($i_piece == $n_pieces - 1) {
            if (substr ($question_content, -7) == '[/qwiz]') {
               $i_remove = 7;
            } else if (substr ($question_content, -8) == '[/qcard]') {
               $i_remove = 8;
            } else {
               $i_remove = 0;
            }
            if ($i_remove) {
               $question_content = substr ($question_content, 0, -$i_remove);
            }
         }
         $question_html .= $question_content;
         if ($i_piece + 1 < $n_pieces) {
            $piece = $pieces[$i_piece+1];
            $img_input_match_f = preg_match ('/.*<(img|input)[^>]+>/', $piece,
                                             $img_input_matches);
            if ($img_input_match_f) {
               $question_html .= $img_input_matches[0];
               $pieces[$i_piece+1] = str_replace ($img_input_matches[0], '', $piece);
               if ($debug[6]) {
                  error_log ('[qwiz_parse_dataset_questions] $img_input_matches[0]: ' . $img_input_matches[0]);
               }
            }
         }
         if ($post_id != 0) {
            $question_html = preg_replace ('/(\[q\s[^\]]*?)(\s*json=.true.\s*)*([^\]]*\])/', '$1json="true" $3', $question_html);
         }
         $qdata['i_qwiz_qdecks'][]          = $i_qwiz_qdeck;
         $qdata['i_qwiz_qdeck_questions'][] = $i_qwiz_qdeck_question;
         $qdata['htmls'][]                  = $question_html;
         $qdata['qwiz_qdecks'][]            = $qwiz_qdeck;
         $qdata['datasets'][]               = $dataset;
         $qdata['dataset_ids'][]            = $dataset_id;
         $qdata['question_numbers'][]       = $question_number;
         $qdata['units'][]                  = $unit;
         $qdata['topics'][]                 = $topic;
         $qdata['difficulties'][]           = $difficulty;
         if ($qwiz_qdeck == 'qwiz') {
            if ($debug[5]) {
               error_log ("[qwiz_parse_dataset_questions] i_qwiz: $i_qwiz, i_qwiz_qdeck_question: $i_qwiz_qdeck_question");
            }
            if ($qwizzes_questions) {
               $qdata['jsons'][]            = $qwizzes_questions[$i_qwiz][$i_qwiz_qdeck_question];
            } else {
               $qdata['jsons'][]            = '';
            }
         } else {
            if ($debug[5]) {
               error_log ("[qwiz_parse_dataset_questions] i_deck: $i_deck, i_deck: $i_qwiz_qdeck_question");
            }
            if ($qdecks_cards) {
               $qdata['jsons'][]            = $qdecks_cards[$i_deck][$i_qwiz_qdeck_question];
            } else {
               $qdata['jsons'][]            = '';
            }
         }
         $i_qwiz_qdeck_question++;
         $question_html = '';
         $i_piece++;
      }
      $i_qwiz_qdeck++;
      if ($qwiz_qdeck == 'qwiz') {
         $i_qwiz++;
      } else {
         $i_deck++;
      }
   }
   if ($debug[5]) {
      error_log ('[qwiz_parse_dataset_questions] $qwiz_qdeck_poss: ' . print_r ($qwiz_qdeck_poss, true));
      error_log ('[qwiz_parse_dataset_questions] $qdata: ' . print_r ($qdata, true));
      error_log ('[qwiz_parse_dataset_questions] $any_dataset_id_f: ' . $any_dataset_id_f);
      error_log ('[qwiz_parse_dataset_questions] $any_no_dataset_id_f: ' . $any_no_dataset_id_f);
   }
   return array ($qwiz_qdeck_poss, $qwiz_qdeck_lens, $qdata,
                 $any_dataset_id_f, $any_no_dataset_id_f, $maker_session_id);
}
function qwiz_compare_dataset_questions ($qdata, $prev_qdata, $prev_any_dataset_id_f) {
   global $debug;
   if ($debug[5]) {
      error_log ('[qwiz_compare_dataset_questions] $qdata: ' . print_r ($qdata, true));
      error_log ('[qwiz_compare_dataset_questions] $prev_qdata: ' . print_r ($prev_qdata, true));
   }
   $any_renumber_or_no_json_f = false;
   $n_questions = count ($qdata['dataset_ids']);
   for ($i_question=0; $i_question<$n_questions; $i_question++) {
      $dataset_id = $qdata['dataset_ids'][$i_question];
      if ($dataset_id) {
         $i_prev_question = false;
         if ($prev_any_dataset_id_f) {
            $i_prev_question = array_search ($dataset_id, $prev_qdata['dataset_ids']);
         }
         $question_number_change_f = false;
         $no_json_f                = false;
         if ($i_prev_question !== false) {
            $question_number      = $qdata['question_numbers'][$i_question];
            $prev_question_number = $prev_qdata['question_numbers'][$i_prev_question];
            if (! $question_number || $question_number != $prev_question_number) {
               $qdata['new_modified'][$i_question] = true;
               $question_number_change_f = true;
               $qdata['question_numbers'][$i_question] = $i_question + 1;
            }
            $n = preg_match ('/json=.true./', $prev_qdata['htmls'][$i_prev_question]);
            if (! $n) {
               $no_json_f = true;
            }
            if ($question_number_change_f || $no_json_f) {
               $any_renumber_or_no_json_f = true;
            } else {
               $html      = $qdata['htmls'][$i_question];
               $prev_html = $prev_qdata['htmls'][$i_prev_question];
               if ($debug[5] && $i_question == 0) {
                  error_log ('[qwiz_compare_dataset_questions] $html: ' . $html);
                  error_log ('[qwiz_compare_dataset_questions] $prev_html: ' . $prev_html);
               }
               if ($html != $prev_html) {
                  $qdata['new_modified'][$i_question] = true;
               }
            }
         } else {
            $qdata['new_modified'][$i_question] = true;
            $any_renumber_or_no_json_f = true;
            if ($debug[5]) {
               error_log ('[qwiz_compare_dataset_questions] not there - $i_question: ' .$i_question);
            }
         }
      }
   }
   if ($debug[5]) {
      error_log ('[qwiz_compare_dataset_questions] $qdata[\'new_modified\']: ' . print_r ($qdata['new_modified'], true));
      error_log ('[qwiz_compare_dataset_questions] $any_renumber_or_no_json_f: ' . $any_renumber_or_no_json_f);
   }
   return array ($qdata, $any_renumber_or_no_json_f);
}
function qwiz_update_dataset_ids ($qwiz_qdeck_poss, $qwiz_qdeck_lens,
                                                              &$qdata, &$html) {
   global $debug;
   if ($debug[5]) {
      error_log ('[qwiz_update_dataset_ids] $qwiz_qdeck_poss: ' . print_r ($qwiz_qdeck_poss, true));
      error_log ('[qwiz_update_dataset_ids] $qdata: ' . print_r ($qdata, true));
   }
   $n_questions = count ($qdata['dataset_ids']);
   $prev_qwiz_qdeck = -1;
   for ($i_question=$n_questions-1; $i_question>=0; $i_question--) {
      $i_qwiz_qdeck = $qdata['i_qwiz_qdecks'][$i_question];
      if ($i_qwiz_qdeck == -1) {
         continue;
      }
      if ($i_qwiz_qdeck != $prev_qwiz_qdeck) {
         if ($debug[5]) {
            error_log ('[qwiz_update_dataset_ids] $i_qwiz_qdeck: ' . $i_qwiz_qdeck);
         }
         if ($prev_qwiz_qdeck != -1) {
            $html = $before_qwiz_qdeck . $qwiz_qdeck_html . $after_qwiz_qdeck;
         }
         $qwiz_qdeck_pos = $qwiz_qdeck_poss[$i_qwiz_qdeck];
         $qwiz_qdeck_len = $qwiz_qdeck_lens[$i_qwiz_qdeck];
         $before_qwiz_qdeck = substr ($html, 0, $qwiz_qdeck_pos);
         $qwiz_qdeck_html   = substr ($html, $qwiz_qdeck_pos, $qwiz_qdeck_len);
         $after_qwiz_qdeck  = substr ($html, $qwiz_qdeck_pos + $qwiz_qdeck_len);
         $prev_qwiz_qdeck = $i_qwiz_qdeck;
         $n_matches = preg_match_all ('/\[q\]|\[q [^\]]+\]/', $qwiz_qdeck_html,
                                      $matches, PREG_OFFSET_CAPTURE, PREG_SET_ORDER);
         if ($debug[5]) {
            error_log ('[qwiz_update_dataset_ids] $qwiz_qdeck_html: ' . substr ($qwiz_qdeck_html, 0, 200) . "\n ...\n " . substr ($qwiz_qdeck_html, -200));
            error_log ('[qwiz_update_dataset_ids] $matches: ' . print_r ($matches, true));
         }
      }
      $i_qwiz_qdeck_question = $qdata['i_qwiz_qdeck_questions'][$i_question];
      $shortcode = $matches[0][$i_qwiz_qdeck_question][0];
      $pos       = $matches[0][$i_qwiz_qdeck_question][1];
      $len = strlen ($shortcode);
      $before = substr ($qwiz_qdeck_html, 0, $pos);
      $after  = substr ($qwiz_qdeck_html, $pos + $len);
      $jsons_i = $qdata['jsons'][$i_question];
      if (isset ($jsons_i['question_attributes'])) {
         $json_question_attributes = $jsons_i['question_attributes'];
      } else {
         $json_question_attributes = '';
      }
      if (! $qdata['dataset_ids'][$i_question]) {
         $dataset_id = qwiz_create_dataset_id ($qdata['datasets'][$i_question]);
         $qdata['dataset_ids'][$i_question] = $dataset_id;
         $qdata['new_modified'][$i_question] = true;
         $new_dataset_attr = 'dataset_id="' . $dataset_id . '"';
         if (strpos ($shortcode, 'dataset_id=') !== false) {
            $shortcode                = preg_replace ('/dataset_id="[^"]+"/', $new_dataset_attr, $shortcode);
            $json_question_attributes = preg_replace ('/dataset_id="[^"]+"/', $new_dataset_attr, $json_question_attributes);
         } else {
            $shortcode                = substr ($shortcode,                0, -1) . ' ' . $new_dataset_attr . ']';
            $json_question_attributes = substr ($json_question_attributes, 0, -1) . ' ' . $new_dataset_attr;
         }
      }
      $i_question_number = $qdata['i_qwiz_qdeck_questions'][$i_question] + 1;
      $new_question_number_attr = 'question_number="' . $i_question_number . '"';
      if (strpos ($shortcode, 'question_number=') !== false) {
         $old_shortcode = $shortcode;
         $shortcode                = preg_replace ('/question_number="[^"]+"/', $new_question_number_attr, $shortcode);
         $json_question_attributes = preg_replace ('/question_number="[^"]+"/', $new_question_number_attr, $json_question_attributes);
         if ($shortcode != $old_shortcode) {
            $qdata['new_modified'][$i_question] = true;
         }
      } else {
         $shortcode                = substr ($shortcode               , 0, -1) . ' ' . $new_question_number_attr . ']';
         $json_question_attributes = substr ($json_question_attributes, 0, -1) . ' ' . $new_question_number_attr;
         $qdata['new_modified'][$i_question] = true;
      }
      $qdata['question_numbers'][$i_question] = $i_question_number;
      $shortcode = preg_replace ('/(\[q\s[^\]]*?)(\s*json=.true.\s*)*([^\]]*\])/', '$1json="true" $3', $shortcode);
      $qwiz_qdeck_html = $before . $shortcode . $after;
      $qdata['htmls'][$i_question] = preg_replace ('/\[q\]|\[q [^\]]+\]/', $shortcode,
                                                   $qdata['htmls'][$i_question]);
      $qdata['jsons'][$i_question]['question_attributes'] = $json_question_attributes;
   }
   $html = $before_qwiz_qdeck . $qwiz_qdeck_html . $after_qwiz_qdeck;
   if ($debug[6]) {
      error_log ('[qwiz_update_dataset_ids] $html: ' . $html);
   }
   if ($debug[5]) {
      error_log ('[qwiz_update_dataset_ids] $qdata[\'dataset_ids\']: ' . print_r ($qdata['dataset_ids'], true));
      error_log ('[qwiz_update_dataset_ids] $qdata[\'new_modified\']: ' . print_r ($qdata['new_modified'], true));
   }
}
function qwiz_create_dataset_id ($dataset) {
   $dataset_name = preg_replace ('/["|]/', '', $dataset);
   $string = microtime ();
   $string = substr ($string, 2, 6) . substr ($string, 11);
   $int_microtime = $string + 0;
   $dataset_id = $dataset_name . '|' . sprintf ('%x', $int_microtime);
   return $dataset_id;
}
function qwiz_blank_deleted_dataset_questions ($dataset_ids_to_blank, &$qdata) {
   foreach ($dataset_ids_to_blank as $dataset_id) {
      $i_pos = strpos ($dataset_id, '|');
      $dataset = substr ($dataset_id, 0, $i_pos);
      $i_question = count ($qdata['datasets']);
      $qdata['new_modified'][$i_question] = true;
      $qdata['i_qwiz_qdecks'][]          = -1;
      $qdata['i_qwiz_qdeck_questions'][] = '';
      $qdata['htmls'][]                  = '';
      $qdata['jsons'][]                  = '';
      $qdata['qwiz_qdecks'][]            = '';
      $qdata['datasets'][]               = $dataset;
      $qdata['dataset_ids'][]            = $dataset_id;
      $qdata['question_numbers'][]       = '';
      $qdata['units'][]                  = '';
      $qdata['topics'][]                 = '';
      $qdata['difficulties'][]           = '';
   }
}
function qwiz_parse_qrecord_ids ($html) {
   global $debug;
   $n_matches = preg_match_all ('/(\[qwiz|\[qdeck)[^\]]*?\sqrecord_id\s*=\s*"[^"]+[\s\S]*?(\[\/qwiz\]|\[\/qdeck\])/',
                                $html, $matches, PREG_SET_ORDER);
   if ($debug[6]) {
      error_log ('[qwiz_parse_qrecord_ids] $matches: ' . print_r ($matches, true));
   }
   if (! $n_matches) {
      return array ('', '', '');
   }
   $use_dataset_qrecord_ids = array ();
   $use_dataset_datasets    = array ();
   $qrecord_id_n_questions  = array ();
   for ($i_qwiz_qdeck=0; $i_qwiz_qdeck<$n_matches; $i_qwiz_qdeck++) {
      $match = $matches[$i_qwiz_qdeck][0];
      $shortcode_end_pos = strpos ($match, ']');
      if ($shortcode_end_pos === false) {
         $shortcode = '';
      } else {
         $shortcode = substr ($match, 0, $shortcode_end_pos);
      }
      $qrecord_id  = qwiz_get_attr ($shortcode, 'qrecord_id');
      $use_dataset = qwiz_get_attr ($shortcode, 'use_dataset');
      if ($use_dataset) {
         $use_dataset_qrecord_ids[] = $qrecord_id;
         $use_dataset_datasets[]    = $use_dataset;
      } else {
         $qrecord_id_n_questions[$qrecord_id] = array ();
         $default_unit = qwiz_get_attr ($shortcode, 'unit');
         if ($debug[5]) {
            error_log ("[qwiz_parse_qrecord_ids] qrecord_id: $qrecord_id, default_unit: $default_unit");
         }
         $n_q_matches = preg_match_all ('/\[q\]|\[q\s[^\]]*/', $match, $q_matches);
         if ($debug[5]) {
            error_log ('[qwiz_parse_qrecord_ids] q_matches: ' . print_r ($q_matches, true));
         }
         for ($i_question=0; $i_question<$n_q_matches; $i_question++) {
            $unit = qwiz_get_attr ($q_matches[0][$i_question], 'unit');
            if (! $unit) {
               $unit = $default_unit;
            }
            if (! isset ($qrecord_id_n_questions[$qrecord_id][$unit])) {
               $qrecord_id_n_questions[$qrecord_id][$unit] = 0;
            }
            $qrecord_id_n_questions[$qrecord_id][$unit]++;
         }
         if (count ($qrecord_id_n_questions[$qrecord_id]) == 1) {
            $keys = array_keys ($qrecord_id_n_questions[$qrecord_id]);
            $unit = $keys[0];
            if ($unit == '') {
               unset ($qrecord_id_n_questions[$qrecord_id]);
            }
         }
      }
   }
   if ($debug[5]) {
      error_log ('[qwiz_parse_qrecord_ids] $qrecord_id_n_questions: ' . print_r ($qrecord_id_n_questions, true));
   }
   return array ($use_dataset_qrecord_ids,
                 $use_dataset_datasets,
                 $qrecord_id_n_questions);
}
