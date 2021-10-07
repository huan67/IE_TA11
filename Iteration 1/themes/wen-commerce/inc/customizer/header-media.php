<?php
/**
 * Header Media Options
 *
 * @package WEN_Commerce
 */

/**
 * Add Header Media options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_header_media_options( $wp_customize ) {
	$wp_customize->get_section( 'header_image' )->description = esc_html__( 'If you add video, it will only show up on Homepage/FrontPage. Other Pages will use Header/Post/Page Image depending on your selection of option. Header Image will be used as a fallback while the video loads ', 'wen-commerce' );

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_header_media_option',
			'default'           => 'homepage',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => array(
				'homepage'               => esc_html__( 'Homepage / Frontpage', 'wen-commerce' ),
				'entire-site'            => esc_html__( 'Entire Site', 'wen-commerce' ),
				'disable'                => esc_html__( 'Disabled', 'wen-commerce' ),
			),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'header_image',
			'type'              => 'select',
			'priority'          => 1,
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_header_media_title',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Title', 'wen-commerce' ),
			'section'           => 'header_image',
			'type'              => 'text',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_header_media_sub_title',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Subtitle', 'wen-commerce' ),
			'section'           => 'header_image',
			'type'              => 'text',
		)
	);

    wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_header_media_text',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Description', 'wen-commerce' ),
			'section'           => 'header_image',
			'type'              => 'textarea',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_header_media_url',
			'sanitize_callback' => 'esc_url_raw',
			'label'             => esc_html__( 'Header Media Url', 'wen-commerce' ),
			'section'           => 'header_image',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_header_media_url_text',
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Header Media Url Text', 'wen-commerce' ),
			'section'           => 'header_image',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_header_url_target',
			'sanitize_callback' => 'wen_commerce_sanitize_checkbox',
			'label'             => esc_html__( 'Open Link in New Window/Tab', 'wen-commerce' ),
			'section'           => 'header_image',
			'type'   	 		=> 'checkbox',
		)
	);
}
add_action( 'customize_register', 'wen_commerce_header_media_options' );

/** Active Callback Functions */

if ( ! function_exists( 'wen_commerce_is_header_media_logo_active' ) ) :
	/**
	* Return true if header logo is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_header_media_logo_active( $control ) {
		$logo = $control->manager->get_setting( 'wen_commerce_header_media_logo' )->value();
		if ( '' != $logo ) {
			return true;
		} else {
			return false;
		}
	}
endif;
