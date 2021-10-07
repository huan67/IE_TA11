<?php

add_shortcode('flashcard_set', 'flashcard_set');

function flashcard_set($atts) {
    $a = shortcode_atts(array('id' => '',), $atts);
    $obj = new Wp_Flashcard_Process();
    $data = array();
    if (!empty($a['id'])) {
        $data = $obj->get_flashcard_terms($a['id']);
    }

    ob_start();
    include_once WP_FLASHCARD_PATH . 'includes/shortcodes/views/flashcard-shortcode-html.php';
    return ob_get_clean();
}
