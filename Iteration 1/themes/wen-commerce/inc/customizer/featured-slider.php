<?php
/**
 * Featured Slider Options
 *
 * @package WEN_Commerce
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_slider_options( $wp_customize ) {
	$wp_customize->add_section( 'wen_commerce_featured_slider', array(
			'panel' => 'wen_commerce_theme_options',
			'title' => esc_html__( 'Featured Slider', 'wen-commerce' ),
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_slider_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'wen_commerce_featured_slider',
			'type'              => 'select',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_slider_number',
			'default'           => '4',
			'sanitize_callback' => 'wen_commerce_sanitize_number_range',

			'active_callback'   => 'wen_commerce_is_slider_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Slides is changed (Max no of slides is 20)', 'wen-commerce' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
				'max'   => 20,
				'step'  => 1,
			),
			'label'             => esc_html__( 'No of Slides', 'wen-commerce' ),
			'section'           => 'wen_commerce_featured_slider',
			'type'              => 'number',
		)
	);

	$slider_number = get_theme_mod( 'wen_commerce_slider_number', 4 );

	for ( $i = 1; $i <= $slider_number ; $i++ ) {
		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_slider_note_' . $i,
				'sanitize_callback' => 'sanitize_text_field',
				'custom_control'    => 'WEN_Commerce_Note_Control',
				'active_callback'   => 'wen_commerce_is_slider_active',
				'label'             => esc_html__( 'Slide #', 'wen-commerce' ) . $i,
				'section'           => 'wen_commerce_featured_slider',
				'type'              => 'description',
			)
		);

		// Page Sliders
		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_slider_page_' . $i,
				'sanitize_callback' => 'wen_commerce_sanitize_post',
				'active_callback'   => 'wen_commerce_is_slider_active',
				'label'             => esc_html__( 'Page', 'wen-commerce' ) . ' # ' . $i,
				'section'           => 'wen_commerce_featured_slider',
				'type'              => 'dropdown-pages',
			)
		);

		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_slider_description_' . $i,
				'sanitize_callback' => 'wp_kses_post',
				'active_callback'   => 'wen_commerce_is_slider_active',
				'label'             => esc_html__( 'Description', 'wen-commerce' ) . '&nbsp;' . $i,
				'section'           => 'wen_commerce_featured_slider',
				'type'              => 'textarea',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'wen_commerce_slider_options' );

/** Active Callback Functions */

if ( ! function_exists( 'wen_commerce_is_slider_active' ) ) :
	/**
	* Return true if slider is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_slider_active( $control ) {
		$enable = $control->manager->get_setting( 'wen_commerce_slider_option' )->value();

		//return true only if previwed page on customizer matches the type option selected
		return wen_commerce_check_section( $enable );
	}
endif;
