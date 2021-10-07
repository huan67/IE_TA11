<?php
/**
 * Theme Options
 *
 * @package WEN_Commerce
 */

/**
 * Add theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_theme_options( $wp_customize ) {
	$wp_customize->add_panel( 'wen_commerce_theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'wen-commerce' ),
		'priority' => 130,
	) );

	// Footer Editor Options.
	$wp_customize->add_section( 'wen_commerce_footer_editor_options', array(
		'title'       => esc_html__( 'Footer Editor Options', 'wen-commerce' ),
		'description' => esc_html__( 'You can either add html or plain text or custom shortcodes, which will be automatically inserted into your theme. Some shorcodes: [the-year], [site-link] and [privacy-policy-link] for current year, site link and privacy policy link respectively.', 'wen-commerce' ),
		'panel'       => 'wen_commerce_theme_options',
	) );

	$theme_data = wp_get_theme();

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_footer_copyright_text',
			'default'           => sprintf( _x( 'Copyright &copy; %1$s %2$s %3$s', '1: Year, 2: Site Title with home URL, 3: Privacy Policy Link', 'wen-commerce' ), '[the-year]', '[site-link]', '[privacy-policy-link]' ) . '<span class="sep"> | </span>',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Copyright Text', 'wen-commerce' ),
			'section'           => 'wen_commerce_footer_editor_options',
			'type'              => 'textarea',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_reset_footer_content',
			'sanitize_callback' => 'wen_commerce_sanitize_checkbox',
			'label'             => esc_html__( 'Reset Footer Content', 'wen-commerce' ),
			'description'       => esc_html__( 'Refresh the page after save to view full effects.', 'wen-commerce' ),
			'section'           => 'wen_commerce_footer_editor_options',
			'transport'         => 'postMessage',
			'type'              => 'checkbox',
		)
	);


	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_latest_posts_title',
			'default'           => esc_html__( 'News', 'wen-commerce' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Latest Posts Title', 'wen-commerce' ),
			'section'           => 'wen_commerce_theme_options',
		)
	);

	// Layout Options
	$wp_customize->add_section( 'wen_commerce_layout_options', array(
		'title' => esc_html__( 'Layout Options', 'wen-commerce' ),
		'panel' => 'wen_commerce_theme_options',
		)
	);

	/* Default Layout */
	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_default_layout',
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'label'             => esc_html__( 'Default Layout', 'wen-commerce' ),
			'section'           => 'wen_commerce_layout_options',
			'type'              => 'radio',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'wen-commerce' ),
				'no-sidebar'            => esc_html__( 'No Sidebar', 'wen-commerce' ),
			),
		)
	);

	/* Homepage/Archive Layout */
	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_homepage_archive_layout',
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'label'             => esc_html__( 'Homepage/Archive Layout', 'wen-commerce' ),
			'section'           => 'wen_commerce_layout_options',
			'type'              => 'radio',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'wen-commerce' ),
				'no-sidebar'            => esc_html__( 'No Sidebar', 'wen-commerce' ),
			),
		)
	);

	/* Single Page/Post Image */
	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_single_layout',
			'default'           => 'disabled',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'label'             => esc_html__( 'Single Page/Post Image', 'wen-commerce' ),
			'section'           => 'wen_commerce_layout_options',
			'type'              => 'radio',
			'choices'           => array(
				'disabled'              => esc_html__( 'Disabled', 'wen-commerce' ),
				'post-thumbnail'        => esc_html__( 'Post Thumbnail', 'wen-commerce' ),
			),
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'wen_commerce_excerpt_options', array(
		'panel'     => 'wen_commerce_theme_options',
		'title'     => esc_html__( 'Excerpt Options', 'wen-commerce' ),
	) );

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_excerpt_length',
			'default'           => '20',
			'sanitize_callback' => 'absint',
			'input_attrs' => array(
				'min'   => 10,
				'max'   => 200,
				'step'  => 5,
				'style' => 'width: 60px;',
			),
			'label'    => esc_html__( 'Excerpt Length (words)', 'wen-commerce' ),
			'section'  => 'wen_commerce_excerpt_options',
			'type'     => 'number',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_excerpt_more_text',
			'default'           => esc_html__( 'Continue reading...', 'wen-commerce' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Read More Text', 'wen-commerce' ),
			'section'           => 'wen_commerce_excerpt_options',
			'type'              => 'text',
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'wen_commerce_search_options', array(
		'panel'     => 'wen_commerce_theme_options',
		'title'     => esc_html__( 'Search Options', 'wen-commerce' ),
	) );

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_search_text',
			'default'           => esc_html__( 'Search', 'wen-commerce' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Search Text', 'wen-commerce' ),
			'section'           => 'wen_commerce_search_options',
			'type'              => 'text',
		)
	);

	// Homepage / Frontpage Options.
	$wp_customize->add_section( 'wen_commerce_homepage_options', array(
		'description' => esc_html__( 'Only posts that belong to the categories selected here will be displayed on the front page', 'wen-commerce' ),
		'panel'       => 'wen_commerce_theme_options',
		'title'       => esc_html__( 'Homepage / Frontpage Options', 'wen-commerce' ),
	) );

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_recent_posts_heading',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => esc_html__( 'News', 'wen-commerce' ),
			'label'             => esc_html__( 'Recent Posts Heading', 'wen-commerce' ),
			'section'           => 'wen_commerce_homepage_options',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_static_page_heading',
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'	=> 'wen_commerce_is_static_page_enabled',
			'default'           => esc_html__( 'Archives', 'wen-commerce' ),
			'label'             => esc_html__( 'Posts Page Header Text', 'wen-commerce' ),
			'section'           => 'wen_commerce_homepage_options',
		)
	);

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_front_page_category',
			'sanitize_callback' => 'wen_commerce_sanitize_category_list',
			'custom_control'    => 'WEN_Commerce_Multi_Cat',
			'label'             => esc_html__( 'Categories', 'wen-commerce' ),
			'section'           => 'wen_commerce_homepage_options',
			'type'              => 'dropdown-categories',
		)
	);

	// Pagination Options.
	$pagination_type = get_theme_mod( 'wen_commerce_pagination_type', 'default' );

	$nav_desc = '';

	/**
	* Check if navigation type is Jetpack Infinite Scroll and if it is enabled
	*/
	$nav_desc = sprintf(
		wp_kses(
			__( 'For infinite scrolling, use %1$sJetPack Plugin%2$s with Infinite Scroll module Enabled.', 'wen-commerce' ),
			array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br'=> array()
			)
		),
		'<a target="_blank" href="https://wordpress.org/plugins/jetpack/">',
		'</a>'
	);

	$wp_customize->add_section( 'wen_commerce_pagination_options', array(
		'description'     => $nav_desc,
		'panel'           => 'wen_commerce_theme_options',
		'title'           => esc_html__( 'Pagination Options', 'wen-commerce' ),
		'active_callback' => 'wen_commerce_scroll_plugins_inactive'
	) );

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_pagination_type',
			'default'           => 'default',
			'sanitize_callback' => 'wen_commerce_sanitize_select',
			'choices'           => wen_commerce_get_pagination_types(),
			'label'             => esc_html__( 'Pagination type', 'wen-commerce' ),
			'section'           => 'wen_commerce_pagination_options',
			'type'              => 'select',
		)
	);

	// For WooCommerce layout: wen_commerce_woocommerce_layout, check woocommerce-options.php.
	/* Scrollup Options */
	$wp_customize->add_section( 'wen_commerce_scrollup', array(
		'panel'    => 'wen_commerce_theme_options',
		'title'    => esc_html__( 'Scrollup Options', 'wen-commerce' ),
	) );

	wen_commerce_register_option( $wp_customize, array(
			'name'              => 'wen_commerce_disable_scrollup',
			'default'			=> 1,
			'sanitize_callback' => 'wen_commerce_sanitize_checkbox',
			'label'             => esc_html__( 'Scroll Up', 'wen-commerce' ),
			'section'           => 'wen_commerce_scrollup',
			'type'   	 		=> 'checkbox',
		)
	);
}
add_action( 'customize_register', 'wen_commerce_theme_options' );

/** Active Callback Functions */

if ( ! function_exists( 'wen_commerce_is_homepage_posts_enabled' ) ) :
	/**
	* Return true if hommepage posts/content is enabled
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_homepage_posts_enabled( $control ) {
		return ( $control->manager->get_setting( 'wen_commerce_display_homepage_posts' )->value() ? true : false );
	}
endif;

if ( ! function_exists( 'wen_commerce_scroll_plugins_inactive' ) ) :
	/**
	* Return true if infinite scroll functionality exists
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_scroll_plugins_inactive( $control ) {
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) {
			// Support infinite scroll plugins.
			return false;
		}

		return true;
	}
endif;

if ( ! function_exists( 'wen_commerce_is_static_page_enabled' ) ) :
	/**
	* Return true if A Static Page is enabled
	*
	* @since WEN Commerce 1.0
	*/
	function wen_commerce_is_static_page_enabled( $control ) {
		$enable = $control->manager->get_setting( 'show_on_front' )->value();
		if ( 'page' === $enable ) {
			return true;
		}
		return false;
	}
endif;
