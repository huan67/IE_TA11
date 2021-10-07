<?php
/**
 * Services options
 *
 * @package WEN_Commerce
 */

/**
 * Add service content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_service_options( $wp_customize ) {
    $wp_customize->add_section( 'wen_commerce_service', array(
			'title' => esc_html__( 'Services', 'wen-commerce' ),
			'panel' => 'wen_commerce_theme_options',
		)
	);

	// Add color scheme setting and control.
	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_service_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'wen_commerce_service',
			'type'              => 'select',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_service_title',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_service_active',
			'label'             => esc_html__( 'Title', 'wen-commerce' ),
			'section'           => 'wen_commerce_service',
			'type'              => 'text',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_service_description',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_service_active',
			'label'             => esc_html__( 'Description', 'wen-commerce' ),
			'section'           => 'wen_commerce_service',
			'type'              => 'textarea',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_service_number',
			'default'           => 3,
			'sanitize_callback' => 'wen_commerce_sanitize_number_range',
			'active_callback'   => 'wen_commerce_is_service_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Services is changed (Max no of Services is 20)', 'wen-commerce' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of items', 'wen-commerce' ),
			'section'           => 'wen_commerce_service',
			'type'              => 'number',
			'transport'         => 'postMessage',
		)
	);

	$number = get_theme_mod( 'wen_commerce_service_number', 3 );

	//loop for service post content
	for ( $i = 1; $i <= $number ; $i++ ) {
		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_service_page_' . $i,
				'sanitize_callback' => 'wen_commerce_sanitize_post',
				'active_callback'   => 'wen_commerce_is_service_active',
				'label'             => esc_html__( 'Services Page', 'wen-commerce' ) . ' ' . $i ,
				'section'           => 'wen_commerce_service',
				'type'              => 'dropdown-pages',
				'allow_addition'    => true,
			)
		);
	} // End for().
}
add_action( 'customize_register', 'wen_commerce_service_options', 10 );

/** Active Callback Functions **/
if ( ! function_exists( 'wen_commerce_is_service_active' ) ) :
	/**
	* Return true if service content is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_service_active( $control ) {
		$enable = $control->manager->get_setting( 'wen_commerce_service_option' )->value();

		//return true only if previewed page on customizer matches the type of content option selected
		return wen_commerce_check_section( $enable );
	}
endif;
