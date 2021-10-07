<?php if (!defined( 'FW' )) die('Forbidden');

$options = array(
    'setting_panel' => array(
        'title' => esc_html__('Giver Panel', 'giver'),
        'options' => array(

            'giver_settings' => array(
                'title' => esc_html__('General Setting', 'giver'),
                'options' => array(

                    'giver_preloader' => array(
                        'type'  => 'switch',
                        'label' => esc_html__('Giver Preloader', 'giver'),
                        'desc' => esc_html__('Turn off if you dont want to show preloader', 'giver'),
                    ),

                ),
            ),
            // Blog Settings
            'giver_blog_settings' => array(
                'title' => esc_html__('Blog Setting', 'giver'),
                'options' => array(
                    'giver_blog_sidebar_style'    => array(
                        'label' => esc_html__( 'Select Sidebar Style', 'giver' ),
                        'desc'  => esc_html__( 'Pleas select sidebar options here', 'giver' ),
                        'type'  => 'image-picker',
                        'value' => 'image-2',
                        'choices' => array(
                            'sidebar_full' => get_template_directory_uri() .'/assets/images/page-1.png',
                            'sidebar_left' => get_template_directory_uri() .'/assets/images/page-2.png',
                            'sidebar_right' => get_template_directory_uri() .'/assets/images/page-3.png',
                        ),
                    ),
                    'giver_post_meta' => array(
                        'type'  => 'switch',
                        'value' => false,
                        'label' => esc_html__('Hide Post Meta', 'giver'),
                        'desc' => esc_html__('Turn On if you dont want to show post meta', 'giver'),
                    ),
                    'giver_readmore_text' => array(
                        'type'  => 'text',
                        'value' => 'Read More',
                        'label' => esc_html__('Change Read More Text', 'giver'),
                        'desc' => esc_html__('Change Read More Post text. Just Type that you want.', 'giver'),
                    ),

                ),
            ),
            // Footer Settings
            'giver_footer_settings' => array(
                'title' => esc_html__('Footer Setting', 'giver'),
                'options' => array(
                    'giver_footer_widget_block' => array(
                        'type'  => 'switch',
                        'label' => esc_html__('Footer widget', 'giver'),
                        'desc' => esc_html__('Turn off if you dont want to show Footer widget', 'giver'),
                    ),
                    'giver_footer_widget_layout'    => array(
                        'label' => esc_html__( 'Select Footer widget', 'giver' ),
                        'desc'  => esc_html__( 'Pleas select Footer widget options here', 'giver' ),
                        'type'  => 'image-picker',
                        'value' => 'footer-2',
                        'choices' => array(
                            '1' => get_template_directory_uri() .'/assets/images/footer-1.png',
                            '2' => get_template_directory_uri() .'/assets/images/footer-2.png',
                            '3' => get_template_directory_uri() .'/assets/images/footer-3.png',
                            '4' => get_template_directory_uri() .'/assets/images/footer-4.png',
                        ),
                    ),
                ),
            ),

            // Typography Settings
            'giver_typography_settings' => array(
                'title' => esc_html__('Typography Setting', 'giver'),
                'options' => array(
                    'giver_body_typography' => array(
                      'type'  => 'typography',
                      'value' => array(
                          'family' => 'segoe-ui',
                      ),
                     'components' => array(
                          'family' => true,
                          'size'   => false,
                          'color'  => false
                      ),
                      'label' => esc_html__('Body Typography', 'giver'),
                  ),
                    'giver_heading_typography' => array(
                      'type'  => 'typography',
                      'value' => array(
                          'family' => 'Josefin Sans',
                      ),
                     'components' => array(
                          'family' => true,
                          'size'   => false,
                          'color'  => false
                      ),
                     'label' => esc_html__('Title Typography', 'giver'),
                  )

                ),
            ),

        ),
        'wp-customizer-args' => array(
            'priority' => 3,
        ),
    ),

    // Color Panel
    'giver_color_panel' => array(
        'title' => esc_html__('Giver Colors', 'giver'),
        'options' => array(

            'giver_menu_color_section' => array(
                'title' => esc_html__('Menu Color', 'giver'),
                'options' => array(

                    'giver_menu_color' => array(
                        'type' => 'color-picker',
                        'value' => '#274054',
                        'label' => esc_html__('Menu Color', 'giver'),
                        'desc' => esc_html__('Set Color for menu text', 'giver'),
                    ),
                    'giver_menu_bg_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => 'rgba(255,255,255,0.9)',
                        'label' => esc_html__('Menu Background Color', 'giver'),
                        'desc' => esc_html__('Set Color for menu background', 'giver'),
                    ),

                    'giver_menu_logo_color' => array(
                        'type' => 'color-picker',
                        'value' => '#02a95c',
                        'label' => esc_html__('Logo Text Color', 'giver'),
                        'desc' => esc_html__('Set Color for text logo', 'giver'),
                    ),

                ),
            ),

            'giver_titlebar_color_section' => array(
                'title' => esc_html__('Titlbar Color', 'giver'),
                'options' => array(

                    'giver_title_text_color' => array(
                        'type' => 'color-picker',
                        'value' => '#ffffff',
                        'label' => esc_html__('Title Text Color', 'giver'),
                        'desc' => esc_html__('Set Color for titlebar text', 'giver'),
                    ),
                    'giver_title_bg_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => 'rgba(2, 169, 72,0.7)',
                        'label' => esc_html__('Title Bar Background', 'giver'),
                        'desc' => esc_html__('Set Color for titlebar Background', 'giver'),
                    ),

                ),
            ),
            'giver_footer_color_section' => array(
                'title' => esc_html__('Footer Color', 'giver'),
                'options' => array(

                    'giver_footer_copywrite_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => '#ffffff',
                        'label' => esc_html__('Copywrite Text', 'giver'),
                        'desc' => esc_html__('Set Color for Footer Copywrite text', 'giver'),
                    ),
                    'giver_footer_copywrite_bg_color' => array(
                        'type' => 'rgba-color-picker',
                        'value' => '#2b2e3a',
                        'label' => esc_html__('Copywrite Background', 'giver'),
                        'desc' => esc_html__('Set Color for Footer Copywrite Background', 'giver'),
                    ),

                ),
            ),

        ),
        'wp-customizer-args' => array(
            'priority' => 4,
        ),
    ),
    // Color Panel End
);

