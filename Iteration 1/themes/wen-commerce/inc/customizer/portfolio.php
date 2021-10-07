<?php
/**
 * Add Portfolio Settings in Customizer
 *
 * @package WEN_Commerce
 */

/**
 * Add portfolio options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_portfolio_options( $wp_customize ) {
	$wp_customize->add_section( 'wen_commerce_portfolio', array(
			'panel'    => 'wen_commerce_theme_options',
			'title'    => esc_html__( 'Portfolio', 'wen-commerce' ),
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_portfolio_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'wen_commerce_portfolio',
			'type'              => 'select',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_portfolio_title',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_portfolio_active',
			'label'             => esc_html__( 'Title', 'wen-commerce' ),
			'section'           => 'wen_commerce_portfolio',
			'type'              => 'text',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_portfolio_description',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_portfolio_active',
			'label'             => esc_html__( 'Description', 'wen-commerce' ),
			'section'           => 'wen_commerce_portfolio',
			'type'              => 'textarea',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_portfolio_number',
			'default'           => 6,
			'sanitize_callback' => 'wen_commerce_sanitize_number_range',
			'active_callback'   => 'wen_commerce_is_portfolio_active',
			'label'             => esc_html__( 'Number of items to show', 'wen-commerce' ),
			'section'           => 'wen_commerce_portfolio',
			'type'              => 'number',
			'input_attrs'       => array(
				'style'             => 'width: 100px;',
				'min'               => 0,
			),
		)
	);

	$number = get_theme_mod( 'wen_commerce_portfolio_number', 6 );

	for ( $i = 1; $i <= $number ; $i++ ) {
		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_portfolio_page_' . $i,
				'sanitize_callback' => 'wen_commerce_sanitize_post',
				'active_callback'   => 'wen_commerce_is_portfolio_active',
				'label'             => esc_html__( 'Page', 'wen-commerce' ) . ' ' . $i ,
				'section'           => 'wen_commerce_portfolio',
				'type'              => 'dropdown-pages',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'wen_commerce_portfolio_options' );

/**
 * Active Callback Functions
 */
if ( ! function_exists( 'wen_commerce_is_portfolio_active' ) ) :
	/**
	* Return true if portfolio is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_portfolio_active( $control ) {
		$enable = $control->manager->get_setting( 'wen_commerce_portfolio_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return wen_commerce_check_section( $enable );
	}
endif;
