<?php
if (!defined('ABSPATH')) {

    exit;
}


/**
 * main class
 */
if (!class_exists('Wp_Flashcard_Settings')) {

    class Wp_Flashcard_Settings {

        /**
         * construct
         */
        function __construct() {
            //create custom post type
            add_action('init', array($this, 'create_custom_post_types'));
            //add admin scripts
            add_action('admin_enqueue_scripts', array($this, 'flashcard_admin_scripts'));
            // manage columns in flashcard post type
            add_filter('manage_edit-flashcard_set_columns', array($this, 'list_flashcard_table'));
            add_action('manage_posts_custom_column', array($this, 'list_flashcard_table_values'), 10, 2);
            //save flashcard details 
            add_action("save_post", array($this, "save_flashcard_posts_details"));
            add_action('admin_menu', array($this, 'create_custom_menu_page'), 10);
            // add flashcard details
            add_action('admin_init', array($this, 'admin_init_fn_callback'));
        }

        /**
         * create custom post type
         */
        function create_custom_post_types() {
            $labels = array(
                'name' => _x('Flash Card Sets', 'Post type general name', 'wp-flashcard'),
                'singular_name' => _x('Flash Card Set', 'Post type singular name', 'wp-flashcard'),
                'menu_name' => _x('Flash Cards', 'Admin Menu text', 'wp-flashcard'),
                'name_admin_bar' => _x('Flash Cards', 'Add New on Toolbar', 'wp-flashcard'),
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => null,
                'menu_icon' => 'dashicons-admin-page',
                'supports' => array('title'),
            );

            $flashcard_sets = get_posts(array('post_type' => 'flashcard_set'));
            if (count($flashcard_sets) > 0) {
                $args['map_meta_cap'] = true;
                $args['capabilities'] = array('create_posts' => false);
            }

            register_post_type('flashcard_set', $args);
        }

        /**
         * add flashcard Details
         */
        public function flashcard_details() {
            add_meta_box("falshcard-details", __('Flash Cards', 'wp-flashcard'), array($this, "flashcard_details_html"), "flashcard_set", "normal");
        }

        /**
         * add flashcard admin scripts
         * @global type $post_type
         * @param type $hook
         */
        public function flashcard_admin_scripts($hook) {
            global $post_type;
            if (!empty($post_type) && $post_type == 'flashcard_set') {
                wp_enqueue_media();
                wp_enqueue_style('style-keyboard', WP_FLASHCARD_URL . 'assets/css/backend/keyboard.css');
                wp_enqueue_style('style-colorbox', WP_FLASHCARD_URL . 'assets/css/backend/colorbox.css');

                wp_enqueue_script('script-colorbox', WP_FLASHCARD_URL . 'assets/js/backend/jquery.colorbox-min.js');
                wp_enqueue_script('script-falshcard', WP_FLASHCARD_URL . 'assets/js/backend/flashcard-admin.js');
                wp_enqueue_script('script-keyboard', WP_FLASHCARD_URL . 'assets/js/backend/keyboard.js');
            }
            wp_enqueue_style('style-importer', WP_FLASHCARD_URL . 'assets/css/backend/flashcard-importer.css');
        }

        /**
         * flashcard details html
         * @global type $post
         */
        public function flashcard_details_html() {
            global $post;

            $tableHtml = '<tr style="cursor: pointer;">
                        <td style="width: 47%;">
                            <input required="" placeholder="Definition" class="keyboardInput" style="width: 95%;" type="text" name="flashcard_foreground_word_flashcard_index" value=""/>
                        </td>
                        <td style="width: 47%;">
                            <input required="" placeholder="Word" class="source_word" style="width: 95%;" type="text" name="flashcard_background_word_flashcard_index" value=""/>
                        </td>
                        <td style="width: 6%;">
                            <a href="javascript:void(0)" class="add_flashcard"><span class="dashicons dashicons-insert"></span></a>
                                <a href="javascript:void(0)" class="remove_flashcard"><span class="dashicons dashicons-remove"></span></a>  
                                    
                        </td>
                    </tr>';
            ?>
            <div class="flashcard_sets">        

                <?php
                $falshcard_slides = get_post_meta($post->ID, 'falshcard_slides', true);
                if (!$falshcard_slides)
                    $falshcard_slides = array();
                ?>
                <table class="widefat flashcard_table">
                    <thead>
                        <tr>                    
                            <th><?php esc_html_e('Card Front', 'wp-flashcard'); ?></th>
                            <th><?php esc_html_e('Card Back', 'wp-flashcard'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="flashcard_table_sortable">    
                        <?php
                        $index = 0;
                        if ($falshcard_slides):
                            foreach ($falshcard_slides as $falshcard_slide):
                                ?>
                                <tr style="cursor: pointer;">                            
                                    <td style="width: 47%;">
                                        <input required=""  class="keyboardInput" style="width: 95%;" type="text" name="flashcard_foreground_word_<?php echo $index; ?>" value="<?php echo $falshcard_slide['foreground']; ?>"/>
                                    </td>
                                    <td style="width: 47%;">
                                        <input required="" class="source_word" style="width: 95%;" type="text" name="flashcard_background_word_<?php echo $index; ?>" value="<?php echo $falshcard_slide['background']; ?>"/>
                                        </div>
                                    </td>
                                    <td style="width: 6%;">
                                        <a href="javascript:void(0)" class="add_flashcard"><span class="dashicons dashicons-insert"></span></a>
                                        <a href="javascript:void(0)" class="remove_flashcard"><span class="dashicons dashicons-remove"></span></a>                                
                                    </td>
                                </tr>
                                <?php
                                $index++;
                            endforeach;
                        else:
                            ?>
                            <tr style="cursor: pointer;">                        
                                <td style="width: 47%;">
                                    <input required=""  class="keyboardInput" style="width: 95%;" type="text" name="flashcard_foreground_word_0" value=""/>

                                </td>
                                <td style="width: 47%;">
                                    <input required=""  class="source_word" style="width: 95%;" type="text" name="flashcard_background_word_0" value=""/>

                                </td>
                                <td style="width: 6%;">
                                    <a href="javascript:void(0)" class="add_flashcard"><span class="dashicons dashicons-insert"></span></a>
                                    <a href="javascript:void(0)" class="remove_flashcard"><span class="dashicons dashicons-remove"></span></a>                                
                                </td>
                            </tr>
                        <?php
                        endif;
                        ?>
                    </tbody>
                </table>            
            </div>
            <?php
            echo '<input type="hidden" name="noncename" value="' . wp_create_nonce(__FILE__) . '" />';
        }

        /**
         * manage columns in flashcard post type
         * @param array $column
         * @return type
         */
        public function list_flashcard_table($column) {
            $column['flashcard_short_code'] = __('ShortCode', 'wp-flashcard');
            return $column;
        }

        /**
         * manage columns in flashcard post type
         * @param type $column_name
         * @param type $post_id
         */
        public function list_flashcard_table_values($column_name, $post_id) {
            switch ($column_name) {
                case 'flashcard_short_code' :
                    echo!empty(get_post_meta($post_id, 'flashcard_short_code', true)) ? get_post_meta($post_id, 'flashcard_short_code', true) : '-';
                    break;
                default:
                    break;
            }
        }

        /**
         * save flashcard details
         * @global type $post
         * @param type $post_id
         */
        public function save_flashcard_posts_details($post_id) {
            global $post;
            if (!empty($post->post_type) && $post->post_type == "flashcard_set") {
                $flashcard_data_array = array();
                for ($i = 0; $i < WP_FLASHCARD_ROWS_NO; $i++) {
                    if (!empty($_POST["flashcard_foreground_word_$i"]) && !empty($_POST["flashcard_background_word_$i"])) {
                        $flashcardData = array();
                        $flashcard_foreground_word = sanitize_text_field($_POST["flashcard_foreground_word_$i"]);
                        $flashcard_background_word = sanitize_text_field($_POST["flashcard_background_word_$i"]);

                        $forground = preg_replace('/\t+/', '', htmlspecialchars($flashcard_foreground_word, ENT_QUOTES));
                        $background = preg_replace('/\t+/', '', htmlspecialchars($flashcard_background_word, ENT_QUOTES));
                        $flashcardData['foreground'] = $forground;
                        $flashcardData['background'] = $background;
                        $flashcard_data_array[$forground] = $flashcardData;
                    }
                }
                update_post_meta($post_id, 'falshcard_slides', $flashcard_data_array);
                //add flashcard short code
                update_post_meta($post_id, 'flashcard_short_code', "[flashcard_set id='" . $post_id . "']");
            }
        }

        public function create_custom_menu_page() {
            add_submenu_page('edit.php?post_type=flashcard_set', __('Flash Card Settings', 'wp-flashcard'), __('Flash Card Settings', 'wp-flashcard'), 'manage_options', 'wp-flashcard-setting-page', array($this, 'wp_flashcard_setting_page_callback'));
        }

        public function admin_init_fn_callback() {
            add_meta_box("falshcard-details", __('Flash Cards', 'wp-flashcard'), array($this, "flashcard_details_html"), "flashcard_set", "normal");
            register_setting('wp-flashcard-general-settings', 'wp_flashcard_width_setting');
            register_setting('wp-flashcard-general-settings', 'wp_flashcard_height_setting');
        }

        public function wp_flashcard_setting_page_callback() {
            ?>
            <div class="wrap">
                <div id="icon-themes" class="icon32"></div>  
                <h2><?php echo esc_html__('WP Flashcard Settings', 'wp-flashcard'); ?></h2>  
                <?php settings_errors(); ?>  
                <form method="POST" action="options.php">  
                    <?php
                    settings_fields('wp-flashcard-general-settings');
                    do_settings_sections('wp-flashcard-general-settings');
                    ?>       
                    <table class="widefat" style="margin-top: 20px;">
                        <tr>
                        <h2><?php echo esc_html__('Card Size Settings', 'wp-flashcard'); ?></h2>  
                        </tr>
                        <tr>
                            <th style="width: 30%;"><?php esc_html_e('Card Width', 'wp-flashcard'); ?></th>
                            <td style="width: 70%"><input type="number" id="wp_flashcard_width_setting" name="wp_flashcard_width_setting" value="<?php echo esc_attr(get_option('wp_flashcard_width_setting')); ?>"> px</td>
                        </tr>
                        <tr>
                            <th style="width: 30%;"><?php esc_html_e('Card Height', 'wp-flashcard'); ?></th>
                            <td style="width: 70%"><input type="number" id="wp_flashcard_height_setting" name="wp_flashcard_height_setting" value="<?php echo esc_attr(get_option('wp_flashcard_height_setting')); ?>"> px</td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>  
                </form> 
            </div>
            <?php
        }

    }

    $instance = new Wp_Flashcard_Settings();
}