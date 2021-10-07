<?php
/**
 * Brands options
 *
 * @package WEN_Commerce
 */

/**
 * Add brands options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_brands_options( $wp_customize ) {
	$wp_customize->add_section( 'wen_commerce_brands', array(
			'title' => esc_html__( 'Brands', 'wen-commerce' ),
			'panel' => 'wen_commerce_theme_options',
		)
	);

	// Add color scheme setting and control.
	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_brands_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
			'section'           => 'wen_commerce_brands',
			'type'              => 'select',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_brands_archive_title',
			'default'           => esc_html__( 'Brands', 'wen-commerce' ),
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_brands_active',
			'label'             => esc_html__( 'Title', 'wen-commerce' ),
			'section'           => 'wen_commerce_brands',
			'type'              => 'text',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_brands_description',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'wen_commerce_is_brands_active',
			'label'             => esc_html__( 'Description', 'wen-commerce' ),
			'section'           => 'wen_commerce_brands',
			'type'              => 'textarea',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_brands_number',
			'default'           => 2,
			'sanitize_callback' => 'wen_commerce_sanitize_number_range',
			'active_callback'   => 'wen_commerce_is_brands_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of items is changed (Max no of items: 20)', 'wen-commerce' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of Items', 'wen-commerce' ),
			'section'           => 'wen_commerce_brands',
			'type'              => 'number',
		)
	);

	$number = get_theme_mod( 'wen_commerce_brands_number', 2 );

	for ( $i = 1; $i <= $number ; $i++ ) {
		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_brands_page_' . $i,
				'sanitize_callback' => 'wen_commerce_sanitize_post',
				'active_callback'   => 'wen_commerce_is_brands_active',
				'label'             => esc_html__( 'Page', 'wen-commerce' ) . ' ' . $i ,
				'section'           => 'wen_commerce_brands',
				'type'              => 'dropdown-pages',
			)
		);

		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_brands_description_' . $i,
				'sanitize_callback' => 'wp_kses_post',
				'active_callback'   => 'wen_commerce_is_brands_active',
				'label'             => esc_html__( 'Description', 'wen-commerce' ) . ' ' . $i,
				'section'           => 'wen_commerce_brands',
				'type'              => 'textarea',
			)
		);

		wen_commerce_register_option( $wp_customize, array(
				'name'              => 'wen_commerce_brands_button_text_' . $i,
				'sanitize_callback' => 'wp_kses_post',
				'active_callback'   => 'wen_commerce_is_brands_active',
				'label'             => esc_html__( 'Button Text', 'wen-commerce' ) . ' ' . $i,
				'section'           => 'wen_commerce_brands',
				'type'              => 'text',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'wen_commerce_brands_options', 10 );

/** Active Callback Functions **/
if( ! function_exists( 'wen_commerce_is_brands_active' ) ) :
	/**
	* Return true if brands is active
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_brands_active( $control ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

		// Front page display in Reading Settings
		$page_for_posts = get_option('page_for_posts');

		$enable = $control->manager->get_setting( 'wen_commerce_brands_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return ( 'entire-site' == $enable || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'homepage' == $enable )
	);
	}
endif;
