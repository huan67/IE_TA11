<?php
/**
 * Customizer functionality
 *
 * @package WEN_Commerce
 */

/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since 1.0
 *
 * @see wen_commerce_header_style()
 */
function wen_commerce_custom_header_and_bg() {
	/**
	 * Filter the arguments used when adding 'custom-background' support in Zubin.
	 *
	 * @since 1.0
	 *
	 * @param array $args {
	 *     An array of custom-background support arguments.
	 *
	 *     @type string $default-color Default color of the background.
	 * }
	 */
	add_theme_support( 'custom-background', apply_filters( 'wen_commerce_custom_bg_args', array(
		'default-color' => '#ffffff',
	) ) );

	/**
	 * Filter the arguments used when adding 'custom-header' support in Zubin.
	 *
	 * @since 1.0
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default-text-color Default color of the header text.
	 *     @type int      $width            Width in pixels of the custom header image. Default 1200.
	 *     @type int      $height           Height in pixels of the custom header image. Default 280.
	 *     @type bool     $flex-height      Whether to allow flexible-height header images. Default true.
	 *     @type callable $wp-head-callback Callback function used to style the header image and text
	 *                                      displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'wen_commerce_custom_header_args', array(
		'default-image'      => get_parent_theme_file_uri( '/images/header-image.jpg' ),
		'default-text-color' => '#111111',
		'width'              => 1920,
		'height'             => 660,
		'flex-height'        => true,
		'flex-height'        => true,
		'wp-head-callback'   => 'wen_commerce_header_style',
		'video'              => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/images/header-image.jpg',
			'thumbnail_url' => '%s/images/header-image-275x155.jpg',
			'description'   => esc_html__( 'Default Header Image', 'wen-commerce' ),
		)
	) );
}
add_action( 'after_setup_theme', 'wen_commerce_custom_header_and_bg' );
