<?php
/**
 * TGM implementation.
 *
 * @package WEN_Commerce
 */

/**
 * Load TGMPA
 */
require get_parent_theme_file_path( '/inc/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'wen_commerce_register_recommended_plugins' );

/**
 * Register recommended plugins.
 *
 * @since 1.0.0
 */
function wen_commerce_register_recommended_plugins() {

	$plugins = array(
		array(
			'name'     => esc_html__( 'WooCommerce', 'wen-commerce' ),
			'slug'     => 'woocommerce',
		),
		array(
			'name' => esc_html__( 'Contact Form 7', 'wen-commerce' ),
			'slug' => 'contact-form-7',
		),
		array(
			'name' => esc_html__( 'WEN Featured Image', 'wen-commerce' ),
			'slug' => 'wen-featured-image',
		),
		array(
			'name' => esc_html__( 'Catch Themes Demo Import', 'wen-commerce' ),
			'slug' => 'catch-themes-demo-import',
		),
	);

	// TGM configurations.
	$config = array(
	);

	// Register now.
	tgmpa( $plugins, $config );

}
