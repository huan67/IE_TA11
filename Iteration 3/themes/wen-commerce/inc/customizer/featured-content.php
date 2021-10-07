<?php
/**
 * Featured Content options
 *
 * @package WEN_Commerce
 */

/**
 * Add featured content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_featured_content_options( $wp_customize ) {
    $wp_customize->add_section( 'wen_commerce_featured_content', array(
			'title' => esc_html__( 'Featured Content', 'wen-commerce' ),
			'panel' => 'wen_commerce_theme_options',
		)
	);

	// Add color scheme setting and control.
	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_featured_content_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'wen_commerce_featured_content',
			'type'              => 'select',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_featured_content_title',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_featured_content_active',
			'label'             => esc_html__( 'Title', 'wen-commerce' ),
			'section'           => 'wen_commerce_featured_content',
			'type'              => 'text',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_featured_content_description',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_featured_content_active',
			'label'             => esc_html__( 'Description', 'wen-commerce' ),
			'section'           => 'wen_commerce_featured_content',
			'type'              => 'textarea',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_featured_content_number',
			'default'           => 3,
			'sanitize_callback' => 'wen_commerce_sanitize_number_range',
			'active_callback'   => 'wen_commerce_is_featured_content_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Featured Content is changed (Max no of Featured Content is 20)', 'wen-commerce' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of Featured Content', 'wen-commerce' ),
			'section'           => 'wen_commerce_featured_content',
			'type'              => 'number',
			'transport'         => 'postMessage',
		)
	);

	$number = get_theme_mod( 'wen_commerce_featured_content_number', 3 );

	//loop for featured post content
	for ( $i = 1; $i <= $number ; $i++ ) {
		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_featured_content_page_' . $i,
				'sanitize_callback' => 'wen_commerce_sanitize_post',
				'active_callback'   => 'wen_commerce_is_featured_content_active',
				'label'             => esc_html__( 'Featured Page', 'wen-commerce' ) . ' ' . $i ,
				'section'           => 'wen_commerce_featured_content',
				'type'              => 'dropdown-pages',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'wen_commerce_featured_content_options', 10 );

/** Active Callback Functions **/
if ( ! function_exists( 'wen_commerce_is_featured_content_active' ) ) :
	/**
	* Return true if featured content is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_featured_content_active( $control ) {
		$enable = $control->manager->get_setting( 'wen_commerce_featured_content_option' )->value();

		return wen_commerce_check_section( $enable );
	}
endif;
