<?php

if (!defined('ABSPATH')) {

    exit;
}


/**
 * main class
 */
if (!class_exists('Wp_Flashcard_Process')) {

    class Wp_Flashcard_Process {

        /**
         * construct
         */
        function __construct() {
            //enqueue scripts
            add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts_callback'));
            // hide add new menu item
//            add_action('admin_menu', array($this, 'disable_new_posts_in_admin_menu'));
            // hide add new menu item button
//            add_action('admin_init', array($this, 'disable_new_posts_in_edit_page'));
        }

        /**
         * enqueue scripts
         */
        function wp_enqueue_scripts_callback() {
            wp_enqueue_style('owl-carousel-style', WP_FLASHCARD_URL . '/assets/css/owl.carousel.min.css');
            wp_enqueue_style('owl-carousel-theme-style', WP_FLASHCARD_URL . '/assets/css/owl.theme.default.min.css');
            wp_enqueue_style('animate-style', WP_FLASHCARD_URL . '/assets/css/animate.min.css');
            wp_enqueue_style('scrollbar-style', WP_FLASHCARD_URL . '/assets/css/jquery.scrollbar.css');
            wp_enqueue_style('flashcard-style', WP_FLASHCARD_URL . '/assets/css/flashcard.css');
            wp_enqueue_script('owl-carousel-script', WP_FLASHCARD_URL . 'assets/js/owl.carousel.min.js', array('jquery'), '', true);
            wp_enqueue_script('flip-script', WP_FLASHCARD_URL . 'assets/js/jquery.flip.min.js', array('jquery'), '', true);
            wp_enqueue_script('scrollbar-script', WP_FLASHCARD_URL . 'assets/js/jquery.scrollbar.min.js', array('jquery'), '', true);
            wp_enqueue_script('flashcard-script', WP_FLASHCARD_URL . 'assets/js/flashcard.js', array('jquery'), '', true);
            wp_localize_script('flashcard-script', 'obj', array('ajax_url' => admin_url('admin-ajax.php')));
        }

        /**
         * get flashcard terms from repeater by card id
         * @param type $flashcard_id
         * @return type
         */
        function get_flashcard_terms($flashcard_id) {
            $data = array();
            if (!empty(get_post_meta($flashcard_id, 'falshcard_slides', true))) {
                $data = get_post_meta($flashcard_id, 'falshcard_slides', true);
            }
            return $data;
        }

        function disable_new_posts_in_edit_page() {
            global $pagenow;
            if ($pagenow == 'edit.php') {
                if (isset($_GET['post_type']) && sanitize_text_field($_GET['post_type']) == 'flashcard_set') {
                    $posts = get_posts(array('post_type' => 'flashcard_set'));
                    if (count($posts) > 0) {
                        echo '<style type="text/css">
        .page-title-action { display:none; }
        </style>';
                    }
                }
            }
        }

        function disable_new_posts_in_admin_menu() {
            global $submenu;
            $posts = get_posts(array('post_type' => 'flashcard_set'));
            if (count($posts) > 0) {
                unset($submenu['edit.php?post_type=flashcard_set'][10]);
                if (isset($_GET['post_type']) && $_GET['post_type'] == 'flashcard_set') {
                    echo '<style type="text/css">
        #favorite-actions, .add-new-h2, .tablenav { display:none; }
        </style>';
                }
            }
        }

    }

    $instance = new Wp_Flashcard_Process();
}