<?php
/**
 * Theme Customizer
 *
 * @package WEN_Commerce
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport              = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport       = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'header_video' )->transport          = 'refresh';
	$wp_customize->get_setting( 'external_header_video' )->transport = 'refresh';
	$wp_customize->get_setting( 'header_image' )->transport 		 = 'refresh';
	
	$wp_customize->register_section_type( 'WEN_Commerce_Customize_Section_Upsell' );

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title a',
			'container_inclusive' => false,
			'render_callback' => 'wen_commerce_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
			'container_inclusive' => false,
			'render_callback' => 'wen_commerce_customize_partial_blogdescription',
		) );
	}

	// Reset all settings to default.
	$wp_customize->add_section( 'wen_commerce_reset_all', array(
		'description'   => esc_html__( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'wen-commerce' ),
		'title'         => esc_html__( 'Reset all settings', 'wen-commerce' ),
		'priority'      => 998,
	) );

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_reset_all_settings',
			'sanitize_callback' => 'wen_commerce_sanitize_checkbox',
			'label'             => esc_html__( 'Check to reset all settings to default', 'wen-commerce' ),
			'section'           => 'wen_commerce_reset_all',
			'transport'         => 'postMessage',
			'type'              => 'checkbox',
		)
	);
	// Reset all settings to default end.

	// Important Links.
	$wp_customize->add_section( 'wen_commerce_important_links', array(
		'priority'      => 999,
		'title'         => esc_html__( 'Important Links', 'wen-commerce' ),
	) );

	// Has dummy Sanitizaition function as it contains no value to be sanitized.
	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_important_links',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'WEN_Commerce_Important_Links_Control',
			'label'             => esc_html__( 'Important Links', 'wen-commerce' ),
			'section'           => 'wen_commerce_important_links',
			'type'              => 'wen_commerce_important_links',
		)
	);
	// Important Links End.

	// Register sections.
	$wp_customize->add_section(
		new WEN_Commerce_Customize_Section_Upsell(
			$wp_customize,
			'upsell',
			array(
				'title'    => esc_html__( 'WEN Commerce Pro', 'wen-commerce' ),
				'pro_text' => esc_html__( 'Buy Pro', 'wen-commerce' ),
				'pro_url'  => 'https://themepalace.com/downloads/wen-commerce-pro/',
				'priority'  => 1,
			)
		)
	);
}
add_action( 'customize_register', 'wen_commerce_customize_register', 999 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wen_commerce_customize_preview_js() {
	$min  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'wen-commerce-customize-preview', trailingslashit( esc_url ( get_template_directory_uri() ) ) . '/js/customize-preview' . $min . '.js', array( 'customize-preview' ), '2020', true );
}
add_action( 'customize_preview_init', 'wen_commerce_customize_preview_js' );

/**
 * Register customizer controls scripts.
 *
 * @since 0.1
 */
function wen_commerce_customize_controls_register_scripts() {
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	wp_enqueue_script( 'wen-commerce-customize-controls', get_template_directory_uri() . '/js/customize-controls' . $min . '.js', array( 'jquery', 'customize-controls', 'jquery-ui-core', 'jquery-ui-sortable' ), '1.0.0', true );

	wp_enqueue_style( 'wen-commerce-customize-controls', get_template_directory_uri() . '/css/customize-controls' . $min . '.css', array(), '1.0.0' );
}

add_action( 'customize_controls_enqueue_scripts', 'wen_commerce_customize_controls_register_scripts', 0 );

/**
 * Include Custom Controls
 */
require get_parent_theme_file_path( 'inc/customizer/custom-controls.php' );

/**
 * Include Header Media Options
 */
require get_parent_theme_file_path( 'inc/customizer/header-media.php' );

/**
 * Include Theme Options
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options.php' );

/**
 * Include Hero Content
 */
require get_parent_theme_file_path( 'inc/customizer/hero-content.php' );

/**
 * Include Featured Slider
 */
require get_parent_theme_file_path( 'inc/customizer/featured-slider.php' );

/**
 * Include Featured Content
 */
require get_parent_theme_file_path( 'inc/customizer/featured-content.php' );

/**
 * Include Testimonial
 */
require get_parent_theme_file_path( 'inc/customizer/testimonial.php' );

/**
 * Include Portfolio
 */
require get_parent_theme_file_path( 'inc/customizer/portfolio.php' );

/**
 * Include WooCommerce Support
 */
require get_parent_theme_file_path( 'inc/customizer/woocommerce.php' );

/**
 * Include Customizer Helper Functions
 */
require get_parent_theme_file_path( 'inc/customizer/helpers.php' );

/**
 * Include Sanitization functions
 */
require get_parent_theme_file_path( 'inc/customizer/sanitize-functions.php' );

/**
 * Include Service
 */
require get_parent_theme_file_path( 'inc/customizer/service.php' );

/**
 * Include Brands
 */
require get_parent_theme_file_path( 'inc/customizer/brands.php' );
