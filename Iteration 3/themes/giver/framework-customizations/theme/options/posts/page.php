<?php

if ( ! defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
    'giver_general_settings' => array(
        'title'   => esc_html__( 'General', 'giver' ),
        'type'    => 'tab',
        'options' => array(
            'general-box' => array(
                'title'   => esc_html__( 'General Settings', 'giver' ),
                'type'    => 'box',
                'options' => array(
                    'giver_hide_title'    => array(
                        'label' => esc_html__( 'Hide Title bar', 'giver' ),
                        'desc'  => esc_html__( 'Please turn on if you dont want to show title bar', 'giver' ),
                        'type'  => 'switch',
                        'value' => 'title_bar',
                    ),
                    'giver_header_styles'    => array(
                        'label' => esc_html__( 'Select Header Style', 'giver' ),
                        'desc'  => esc_html__( 'Pleas select header options here', 'giver' ),
                        'type'  => 'image-picker',
                        'value' => 'image-2',
                        'choices' => array(
                            'hs_1' => get_template_directory_uri() .'/assets/images/hs-1.png',
                            'hs_2' => get_template_directory_uri() .'/assets/images/hs-2.png',
                        ),
                    ),
                    'giver_sidebar_style'    => array(
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
                    'giver_header_banner_image' => array(
                        'label' => esc_html__( 'Banner Image', 'giver' ),
                        'desc'  => esc_html__( 'Please Upload a image for specific page header image', 'giver' ),
                        'type'  => 'upload'
                    ),
                    'giver_custom_padding' => array(
                        'label' => esc_html__( 'Cuatom Padding', 'giver' ),
                        'desc'  => esc_html__( 'Please select custom padding for page', 'giver' ),
                        'type'  => 'range-slider',
                        'value' => array(
                          'from' => 50,
                          'to'   => 100,
                        ),
                        'properties' => array(
                            'min' => 20,
                            'max' => 200,
                        ),
                    ),
                    'giver_hide_footer'    => array(
                        'label' => esc_html__( 'Hide Footer', 'giver' ),
                        'desc'  => esc_html__( 'Please turn on if you dont want to show footer', 'giver' ),
                        'type'  => 'switch',
                        'value' => 'footer',
                    ),
                )
            ),
        )
    )
);