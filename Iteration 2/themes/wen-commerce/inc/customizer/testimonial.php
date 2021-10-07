<?php
/**
 * Add Testimonial Settings in Customizer
 *
 * @package WEN_Commerce
*/

/**
 * Add testimonial options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_testimonial_options( $wp_customize ) {
	$wp_customize->add_section( 'wen_commerce_testimonials', array(
			'panel'    => 'wen_commerce_theme_options',
			'title'    => esc_html__( 'Testimonials', 'wen-commerce' ),
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_testimonial_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'wen_commerce_testimonials',
			'type'              => 'select',
			'priority'          => 1,
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_testimonial_title',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_testimonial_active',
			'label'             => esc_html__( 'Title', 'wen-commerce' ),
			'section'           => 'wen_commerce_testimonials',
			'type'              => 'text',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_testimonial_description',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_testimonial_active',
			'label'             => esc_html__( 'Description', 'wen-commerce' ),
			'section'           => 'wen_commerce_testimonials',
			'type'              => 'textarea',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_testimonial_number',
			'default'           => '3',
			'sanitize_callback' => 'wen_commerce_sanitize_number_range',
			'active_callback'   => 'wen_commerce_is_testimonial_active',
			'label'             => esc_html__( 'Number of items', 'wen-commerce' ),
			'section'           => 'wen_commerce_testimonials',
			'type'              => 'number',
			'input_attrs'       => array(
				'style'             => 'width: 100px;',
				'min'               => 0,
			),
		)
	);

	$number = get_theme_mod( 'wen_commerce_testimonial_number', 3 );

	for ( $i = 1; $i <= $number ; $i++ ) {
		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_testimonial_page_' . $i,
				'sanitize_callback' => 'wen_commerce_sanitize_post',
				'active_callback'   => 'wen_commerce_is_testimonial_active',
				'label'             => esc_html__( 'Page', 'wen-commerce' ) . ' ' . $i ,
				'section'           => 'wen_commerce_testimonials',
				'type'              => 'dropdown-pages',
			)
		);

		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_testimonial_title_' . $i,
				'sanitize_callback' => 'sanitize_text_field',
				'active_callback'   => 'wen_commerce_is_testimonial_active',
				'label'             => esc_html__( 'Name', 'wen-commerce' ),
				'section'           => 'wen_commerce_testimonials',
				'type'              => 'text',
			)
		);

		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_testimonial_position_' . $i,
				'sanitize_callback' => 'sanitize_text_field',
				'active_callback'   => 'wen_commerce_is_testimonial_active',
				'label'             => esc_html__( 'Position', 'wen-commerce' ),
				'section'           => 'wen_commerce_testimonials',
				'type'              => 'text',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'wen_commerce_testimonial_options' );

/**
 * Active Callback Functions
 */
if ( ! function_exists( 'wen_commerce_is_testimonial_active' ) ) :
	/**
	* Return true if testimonial is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_testimonial_active( $control ) {
		$enable = $control->manager->get_setting( 'wen_commerce_testimonial_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return wen_commerce_check_section( $enable );
	}
endif;

if ( ! function_exists( 'wen_commerce_is_post_testimonial_active' ) ) :
	/**
	* Return true if page content is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_post_testimonial_active( $control ) {
		$type = $control->manager->get_setting( 'wen_commerce_testimonial_type' )->value();

		//return true only if previwed page on customizer matches the type of option selected
		return ( wen_commerce_is_testimonial_active( $control ) && 'post' === $type );
	}
endif;

if ( ! function_exists( 'wen_commerce_is_jetpack_testimonial_active' ) ) :
	/**
	* Return true if jetpack testimonial is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_jetpack_testimonial_active( $control ) {
		$type = $control->manager->get_setting( 'wen_commerce_testimonial_type' )->value();

		//return true only if previwed page on customizer matches the type of option selected
		return ( wen_commerce_is_testimonial_active( $control ) && 'jetpack-testimonial' === $type );
	}
endif;

if ( ! function_exists( 'wen_commerce_is_page_testimonial_active' ) ) :
	/**
	* Return true if page content is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_page_testimonial_active( $control ) {
		$type = $control->manager->get_setting( 'wen_commerce_testimonial_type' )->value();

		//return true only if previwed page on customizer matches the type of option selected
		return ( wen_commerce_is_testimonial_active( $control ) && 'page' === $type );
	}
endif;
