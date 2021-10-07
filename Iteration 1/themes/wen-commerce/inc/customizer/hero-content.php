<?php
/**
 * Hero Content Options
 *
 * @package WEN_Commerce
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_hero_content_options( $wp_customize ) {
	$wp_customize->add_section( 'wen_commerce_hero_content_options', array(
			'title' => esc_html__( 'Hero Content', 'wen-commerce' ),
			'panel' => 'wen_commerce_theme_options',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_hero_content_visibility',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'wen_commerce_hero_content_options',
			'type'              => 'select',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_hero_content',
			'default'           => '0',
			'sanitize_callback' => 'wen_commerce_sanitize_post',
			'active_callback'   => 'wen_commerce_is_hero_content_active',
			'label'             => esc_html__( 'Page', 'wen-commerce' ),
			'section'           => 'wen_commerce_hero_content_options',
			'type'              => 'dropdown-pages',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_hero_content_description',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_hero_content_active',
			'label'             => esc_html__( 'Description', 'wen-commerce' ),
			'section'           => 'wen_commerce_hero_content_options',
			'type'              => 'textarea',
		)
	);
}
add_action( 'customize_register', 'wen_commerce_hero_content_options' );

/** Active Callback Functions **/
if ( ! function_exists( 'wen_commerce_is_hero_content_active' ) ) :
	/**
	* Return true if hero content is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_hero_content_active( $control ) {
		$enable = $control->manager->get_setting( 'wen_commerce_hero_content_visibility' )->value();

		return wen_commerce_check_section( $enable );
	}
endif;
