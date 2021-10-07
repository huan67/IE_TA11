<?php

/**
 * Plugin Name: WP Flashcard LITE
 * Description: A WordPress Flashcard Plugin
 * Version: 1.0.4
 * Author: WP Flashcard
 * Author URI: https://wpflashcard.com/
 * Text Domain: wp-flashcard
 */
if (!defined('ABSPATH')) {

    exit;
}
define('WP_FLASHCARD_URL', plugin_dir_url(__FILE__));
define('WP_FLASHCARD_PATH', plugin_dir_path(__FILE__));
define('WP_FLASHCARD_FILE', __FILE__);
define('WP_FLASHCARD_ROWS_NO', 10);


/**
 * main class
 */
if (!class_exists('Wp_Flashcard')) {

    class Wp_Flashcard {

        /**
         * construct
         */
        function __construct() {
            $this->includes();
        }

        /**
         * includes
         */
        public function includes() {
            include_once WP_FLASHCARD_PATH . 'includes/admin/wp-flashcard-settings.php';

            include_once WP_FLASHCARD_PATH . 'includes/front/wp-flashcard-process.php';

            include_once WP_FLASHCARD_PATH . 'includes/shortcodes/flashcard-shortcode.php';
        }

    }

    $instance = new Wp_Flashcard();
}