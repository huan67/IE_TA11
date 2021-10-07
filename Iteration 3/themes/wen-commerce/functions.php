<?php
/**
 * WEN Commerce Pro functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WEN_Commerce
 */

if ( ! function_exists( 'wen_commerce_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wen_commerce_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on WEN Commerce Pro, use a find and replace
		 * to change 'wen-commerce' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wen-commerce', get_parent_theme_file_path( '/languages' ) );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Used in Blog Post, Featured Content/Why Choose Us Sections
		set_post_thumbnail_size( 568, 426, true ); // Ratio 4:3

		// Used in featured slider
		add_image_size( 'wen-commerce-slider', 1920, 656, true ); 

		// Used in inner pages as header image
		add_image_size( 'wen-commerce-header-inner', 1920, 400, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1'        => esc_html__( 'Primary', 'wen-commerce' ),
			'social-footer' => esc_html__( 'Footer Social Menu', 'wen-commerce' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		/**
		 * Add support for essential widget image.
		 *
		 */
		add_theme_support( 'ew-newsletter-image' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => esc_html__( 'Small', 'wen-commerce' ),
					'shortName' => esc_html__( 'S', 'wen-commerce' ),
					'size'      => 13,
					'slug'      => 'small',
				),
				array(
					'name'      => esc_html__( 'Normal', 'wen-commerce' ),
					'shortName' => esc_html__( 'M', 'wen-commerce' ),
					'size'      => 18,
					'slug'      => 'normal',
				),
				array(
					'name'      => esc_html__( 'Large', 'wen-commerce' ),
					'shortName' => esc_html__( 'L', 'wen-commerce' ),
					'size'      => 42,
					'slug'      => 'large',
				),
				array(
					'name'      => esc_html__( 'Extra Large', 'wen-commerce' ),
					'shortName' => esc_html__( 'XL', 'wen-commerce' ),
					'size'      => 56,
					'slug'      => 'extra-large',
				),
				array(
					'name'      => esc_html__( 'Huge', 'wen-commerce' ),
					'shortName' => esc_html__( 'XLL', 'wen-commerce' ),
					'size'      => 90,
					'slug'      => 'huge',
				),
			)
		);

		// Add support for custom color scheme.
		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'White', 'wen-commerce' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
			array(
				'name'  => esc_html__( 'Black', 'wen-commerce' ),
				'slug'  => 'black',
				'color' => '#000000',
			),
			array(
				'name'  => esc_html__( 'Eighty Black', 'wen-commerce' ),
				'slug'  => 'eighty-black',
				'color' => '#333',
			),

			array(
				'name'  => esc_html__( 'Gray', 'wen-commerce' ),
				'slug'  => 'gray',
				'color' => '#777',
			),
			array(
				'name'  => esc_html__( 'Light Gray', 'wen-commerce' ),
				'slug'  => 'light-gray',
				'color' => '#fafafa',
			),
			array(
				'name'  => esc_html__( 'Orange', 'wen-commerce' ),
				'slug'  => 'orange',
				'color' => '#ec7a5b',
			),

		) );

		add_editor_style( array( 'css/editor-style.css', wen_commerce_fonts_url() ) );
	}
endif;
add_action( 'after_setup_theme', 'wen_commerce_setup' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 *
 */
function wen_commerce_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) ) {
		$count++;
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$count++;
	}

	if ( is_active_sidebar( 'sidebar-4' ) ) {
		$count++;
	}

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
		case '4':
			$class = 'four';
			break;
	}

	if ( $class ) {
		echo 'class="widget-area footer-widget-area ' . esc_attr( $class ) . '"';
	}
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wen_commerce_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wen_commerce_content_width', 920 );
}
add_action( 'after_setup_theme', 'wen_commerce_content_width', 0 );

if ( ! function_exists( 'wen_commerce_template_redirect' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet for different value other than the default one
	 *
	 * @global int $content_width
	 */
	function wen_commerce_template_redirect() {
		$layout = wen_commerce_get_theme_layout();

		if ( 'no-sidebar-full-width' === $layout ) {
			$GLOBALS['content_width'] = 1510;
		}
	}
endif;
add_action( 'template_redirect', 'wen_commerce_template_redirect' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wen_commerce_widgets_init() {
	$args = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s"> <div class="widget-wrap">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);

	register_sidebar( array(
		'name'        => esc_html__( 'Sidebar', 'wen-commerce' ),
		'id'          => 'sidebar-1',
		'description' => esc_html__( 'Add widgets here.', 'wen-commerce' ),
		) + $args
	);

	register_sidebar( array(
		'name'        => esc_html__( 'Footer 1', 'wen-commerce' ),
		'id'          => 'sidebar-2',
		'description' => esc_html__( 'Add widgets here to appear in your footer.', 'wen-commerce' ),
		) + $args
	);

	register_sidebar( array(
		'name'        => esc_html__( 'Footer 2', 'wen-commerce' ),
		'id'          => 'sidebar-3',
		'description' => esc_html__( 'Add widgets here to appear in your footer.', 'wen-commerce' ),
		) + $args
	);

	register_sidebar( array(
		'name'        => esc_html__( 'Footer 3', 'wen-commerce' ),
		'id'          => 'sidebar-4',
		'description' => esc_html__( 'Add widgets here to appear in your footer.', 'wen-commerce' ),
		) + $args
	);

	if ( class_exists( 'WooCommerce' ) ) {
		//Optional Primary Sidebar for Shop
		register_sidebar( array(
			'name'        => esc_html__( 'WooCommerce Sidebar', 'wen-commerce' ),
			'id'          => 'sidebar-woo',
			'description' => esc_html__( 'This is Optional Sidebar for WooCommerce Pages', 'wen-commerce' ),
			) + $args
		);
	}

	// Registering 404 Error Page Content
	register_sidebar( array(
		'name'          => esc_html__( '404 Page Not Found Content', 'wen-commerce' ),
		'id'            => 'sidebar-notfound',
		'description'   => esc_html__( 'Replaces the default 404 Page Not Found Content', 'wen-commerce' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"> <div class="widget-wrap">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wen_commerce_widgets_init' );

if ( ! function_exists( 'wen_commerce_fonts_url' ) ) :
	/**
	 * Register Google fonts for WEN Commerce Pro
	 *
	 * Create your own wen_commerce_fonts_url() function to override in a child theme.
	 *
	 * @since WEN Commerce 1.0
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function wen_commerce_fonts_url() {
		/* Translators: If there are characters in your language that are not
		* supported by Poppins, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$poppins = _x( 'on', 'Poppins: on or off', 'wen-commerce' );

		if ( 'on' === $poppins ) {
			return esc_url( '//fonts.googleapis.com/css?family=Poppins::300,400,500,600,700,400italic,700italic' );
		}
	}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since WEN Commerce 1.0
 */
function wen_commerce_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'wen_commerce_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 */
function wen_commerce_scripts() {
	$min  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'wen-commerce-fonts', wen_commerce_fonts_url(), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'wen-commerce-style', get_stylesheet_uri(), null, date( 'Ymd-Gis', filemtime( get_template_directory() . '/style.css' ) ) );

	// Theme block stylesheet.
	wp_enqueue_style( 'wen-commerce-block-style', get_theme_file_uri( 'css/blocks.css' ), array( 'wen-commerce-style' ), date( 'Ymd-Gis', filemtime( get_template_directory() . '/css/blocks.css' ) ) );

	// Load the html5 shiv.
	wp_enqueue_script( 'wen-commerce-html5',  get_theme_file_uri() . 'js/html5' . $min . '.js', array(), '3.7.3' );

	wp_script_add_data( 'wen-commerce-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'wen-commerce-skip-link-focus-fix', trailingslashit( esc_url ( get_template_directory_uri() ) ) . '/js/skip-link-focus-fix' . $min . '.js', array(), '201800703', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$deps[] = 'jquery';

	//Slider Scripts
	$enable_slider      = wen_commerce_check_section( get_theme_mod( 'wen_commerce_slider_option', 'disabled' ) );

	if ( $enable_slider ) {
		// Enqueue owl carousel css. Must load CSS before JS.
		wp_enqueue_style( 'owl-carousel-core', get_theme_file_uri( 'css/owl-carousel/owl.carousel.min.css' ), null, '2.3.4' );
		wp_enqueue_style( 'owl-carousel-default', get_theme_file_uri( 'css/owl-carousel/owl.theme.default.min.css' ), null, '2.3.4' );
 
		// Enqueue script
		wp_enqueue_script( 'owl-carousel', get_theme_file_uri( '/js/owl.carousel' . $min . '.js'), array( 'jquery' ), '2.3.4', true );

		$deps[] = 'owl-carousel';

	}

	// Add masonry to dependent scripts of main script.
	$deps[] = 'jquery-masonry';

	wp_enqueue_script( 'wen-commerce-script', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/functions' . $min . '.js', $deps, date( 'Ymd-Gis', filemtime( get_template_directory() . '/' . 'js/functions' . $min . '.js' ) ), true );

	wp_localize_script( 'wen-commerce-script', 'wenCommerceOptions', array(
		'screenReaderText' => array(
			'expand'   => esc_html__( 'expand child menu', 'wen-commerce' ),
			'collapse' => esc_html__( 'collapse child menu', 'wen-commerce' ),
			'icon'     => wen_commerce_get_svg( array(
					'icon'     => 'angle-down',
					'fallback' => true,
				)
			),
		),
		'iconNavPrev'     => wen_commerce_get_svg( array(
				'icon'     => 'angle-left',
				'fallback' => true,
			)
		),
		'iconNavNext'     => wen_commerce_get_svg( array(
				'icon'     => 'angle-right',
				'fallback' => true,
			)
		),
		'rtl' => is_rtl(),
	) );

	// Remove Media CSS, we have ouw own CSS for this.
	wp_deregister_style('wp-mediaelement');
}
add_action( 'wp_enqueue_scripts', 'wen_commerce_scripts' );

/**
 * Enqueue editor styles for Gutenberg
 */
function wen_commerce_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'wen-commerce-block-editor-style', get_theme_file_uri( 'css/editor-blocks.css' ) );

	// Add custom fonts.
	wp_enqueue_style( 'wen-commerce-fonts', wen_commerce_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'wen_commerce_block_editor_styles' );

if ( ! function_exists( 'wen_commerce_excerpt_length' ) ) :
	/**
	 * Sets the post excerpt length to n words.
	 *
	 * function tied to the excerpt_length filter hook.
	 * @uses filter excerpt_length
	 *
	 * @since WEN Commerce 1.0
	 */
	function wen_commerce_excerpt_length( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		// Getting data from Customizer Options
		$length	= get_theme_mod( 'wen_commerce_excerpt_length', 20 );

		return absint( $length );
	}
endif; //wen_commerce_excerpt_length
add_filter( 'excerpt_length', 'wen_commerce_excerpt_length', 999 );

if ( ! function_exists( 'wen_commerce_excerpt_more' ) ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a option from customizer
	 *
	 * @return string option from customizer prepended with an ellipsis.
	 */
	function wen_commerce_excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}

		$more_tag_text = get_theme_mod( 'wen_commerce_excerpt_more_text',  esc_html__( 'Continue reading', 'wen-commerce' ) );

		$link = sprintf( '<span class="view-more"><a href="%1$s" class="more-link">%2$s</a></span>',
			esc_url( get_permalink() ),
			/* translators: %s: Name of current post */
			wp_kses_data( $more_tag_text ). '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>'
			);

		return $link;
	}
endif;
add_filter( 'excerpt_more', 'wen_commerce_excerpt_more' );

if ( ! function_exists( 'wen_commerce_custom_excerpt' ) ) :
	/**
	 * Adds Continue reading link to more tag excerpts.
	 *
	 * function tied to the get_the_excerpt filter hook.
	 *
	 * @since WEN Commerce 1.0
	 */
	function wen_commerce_custom_excerpt( $output ) {
		if ( has_excerpt() && ! is_attachment() ) {
			$more_tag_text = get_theme_mod( 'wen_commerce_excerpt_more_text', esc_html__( 'Continue reading', 'wen-commerce' ) );

			$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
				esc_url( get_permalink() ),
				/* translators: %s: Name of current post */
				wp_kses_data( $more_tag_text ). '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>'
			);

			$output .= $link;
		}

		return $output;
	}
endif; //wen_commerce_custom_excerpt
add_filter( 'get_the_excerpt', 'wen_commerce_custom_excerpt' );

if ( ! function_exists( 'wen_commerce_more_link' ) ) :
	/**
	 * Replacing Continue reading link to the_content more.
	 *
	 * function tied to the the_content_more_link filter hook.
	 *
	 * @since WEN Commerce 1.0
	 */
	function wen_commerce_more_link( $more_link, $more_link_text ) {
		$more_tag_text = get_theme_mod( 'wen_commerce_excerpt_more_text', esc_html__( 'Continue reading', 'wen-commerce' ) );

		return ' &hellip; ' . str_replace( $more_link_text, wp_kses_data( $more_tag_text ), $more_link );
	}
endif; //wen_commerce_more_link
add_filter( 'the_content_more_link', 'wen_commerce_more_link', 10, 2 );

/**
 * Checks if there are options already present from free version and adds it to the Pro theme options
 *
 * @since WEN Commerce 1.0
 * @hook after_theme_switch
 */
function wen_commerce_setup_options( $old_theme_name ) {
	if ( $old_theme_name ) {
		$old_theme_slug = sanitize_title( $old_theme_name );
		$free_version_slug = array(
			'wen_commerce',
		);

		$pro_version_slug  = 'wen-commerce-pro';

		$free_options = get_option( 'theme_mods_' . $old_theme_slug );

		// Perform action only if theme_mods_wen_commerce free version exists.
		if ( in_array( $old_theme_slug, $free_version_slug ) && $free_options && '1' !== get_theme_mod( 'free_pro_migration' ) ) {
			$new_options = wp_parse_args( get_theme_mods(), $free_options );

			if ( update_option( 'theme_mods_' . $pro_version_slug, $free_options ) ) {
				// Set Migration Parameter to true so that this script does not run multiple times.
				set_theme_mod( 'free_pro_migration', '1' );
			}
		}
	}
}
add_action( 'after_switch_theme', 'wen_commerce_setup_options' );

 /**
 * Query WooCommerce activation
 */
if ( ! function_exists( 'wen_commerce_is_woocommerce_activated' ) ) {
    function wen_commerce_is_woocommerce_activated() {
        return class_exists( 'WooCommerce' ) ? true : false;
    }
}

/**
 * Load TGMPA
 */
require_once get_parent_theme_file_path( '/inc/tgm.php' );

/**
 * SVG icons functions and filters
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );

/**
 * Implement the Custom Header feature
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Functions which enhance the theme by hooking into WordPress
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

/**
 * Customizer additions
 */
require get_parent_theme_file_path( '/inc/customizer/customizer.php' );

/**
 * Color Scheme additions
 */
require get_parent_theme_file_path( '/inc/color-scheme.php' );

/**
 * Load Jetpack compatibility file
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_parent_theme_file_path( '/inc/jetpack.php' );
}


/**
 * Load Theme About Page
 */
require get_parent_theme_file_path( '/inc/admin/admin.php' );
