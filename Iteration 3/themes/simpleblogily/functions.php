<?php
/**
 * simpleblogily functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package simpleblogily
 */

if ( ! function_exists( 'simpleblogily_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function simpleblogily_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on simpleblogily, use a find and replace
	 * to change 'simpleblogily' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'simpleblogily', get_template_directory() . '/languages' );

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
	add_image_size( 'simpleblogily-full-thumb', 768, 0, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'simpleblogily' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'simpleblogily_custom_background_args', array(
		'default-color' => 'eeeeee',
		'default-image' => '',
	) ) );

}
endif;
add_action( 'after_setup_theme', 'simpleblogily_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function simpleblogily_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'simpleblogily_content_width', 640 );
}
add_action( 'after_setup_theme', 'simpleblogily_content_width', 0 );

/**
 *
 * Add Custom editor styles
 *
 */
function simpleblogily_add_editor_styles() {
    add_editor_style( 'css/custom-editor-style.css' );
}
add_action( 'admin_init', 'simpleblogily_add_editor_styles' );

/**
 *
 * Load Google Fonts
 *
 */
function simpleblogily_google_fonts_url(){

    $fonts_url  = '';
    $Lato = _x( 'on', 'Lato font: on or off', 'simpleblogily' );
    $Montserrat      = _x( 'on', 'Montserrat font: on or off', 'simpleblogily' );
 
    if ( 'off' !== $Lato || 'off' !== $Montserrat ){

        $font_families = array();
 
        if ( 'off' !== $Lato ) {

            $font_families[] = 'Lato:300,400,400i,700';

        }
 
        if ( 'off' !== $Montserrat ) {

            $font_families[] = 'Montserrat:400,400i,500,600,700';

        }
        
 
        $query_args = array(

            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

    }
 
    return esc_url_raw( $fonts_url );
}

function simpleblogily_enqueue_googlefonts() {

    wp_enqueue_style( 'simpleblogily-googlefonts', simpleblogily_google_fonts_url(), array(), null );
}

add_action( 'wp_enqueue_scripts', 'simpleblogily_enqueue_googlefonts' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function simpleblogily_widgets_init() {

	register_sidebar( array(
		'name' => esc_html__('Footer Widget One', 'simpleblogily'),
		'id' => 'footer_widget_left',
		'before_widget' => '<div class="footer-widgets">',
		'description'   => esc_html__( 'Widgets here will appear in the footer', 'simpleblogily' ),
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		) );

		register_sidebar( array(
		'name' => esc_html__('Footer Widget Two', 'simpleblogily'),
		'id' => 'footer_widget_middle',
		'description'   => esc_html__( 'Widgets here will appear in the footer', 'simpleblogily' ),
		'before_widget' => '<div class="footer-widgets">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		) );

		register_sidebar( array(
		'name' => esc_html__('Footer Widget Three', 'simpleblogily'),
		'id' => 'footer_widget_right',
		'before_widget' => '<div class="footer-widgets">',
		'after_widget' => '</div>',
		'description'   => esc_html__( 'Widgets here will appear in the footer', 'simpleblogily' ),
		'before_title' => '<h3>',
		'after_title' => '</h3>',
		) );


}
add_action( 'widgets_init', 'simpleblogily_widgets_init' );

/**
 * Filter the except length to 75 words.
*
 * REMOVE THIS PART TO STOP AUTO EXCERPT LENGTH
 *
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function simpleblogily_custom_excerpt_length( $length ) {
    return 75;
}
add_filter( 'excerpt_length', 'simpleblogily_custom_excerpt_length', 999 );


/**
 * Enqueue scripts and styles.
 */
function simpleblogily_scripts() {
	wp_enqueue_style( 'simpleblogily-style', get_stylesheet_uri() );
	wp_enqueue_style( 'simpleblogily-font-awesome-css', get_template_directory_uri() . '/css/font-awesome.min.css');

	wp_enqueue_script( 'simpleblogily-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'simpleblogily-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script( 'simpleblogily-script', get_template_directory_uri() . '/js/simpleblogily.js', array('jquery'), false, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'simpleblogily_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



/**
 * Copyright and License for Upsell button by Justin Tadlock - 2016 © Justin Tadlock. customizer button https://github.com/justintadlock/trt-customizer-pro
 */
require_once( trailingslashit( get_template_directory() ) . 'justinadlock-customizer-button/class-customize.php' );


/**
 * Compare page CSS
 */

function simpleblogily_comparepage_css($hook) {
	if ( 'appearance_page_simpleblogily-info' != $hook ) {
		return;
	}
	wp_enqueue_style( 'simpleblogily-custom-style', get_template_directory_uri() . '/css/compare.css' );
}
add_action( 'admin_enqueue_scripts', 'simpleblogily_comparepage_css' );

/**
 * Compare page content
 */

add_action('admin_menu', 'simpleblogily_themepage');
function simpleblogily_themepage(){
	$theme_info = add_theme_page( __('Simple Blogily','simpleblogily'), __('Simple Blogily','simpleblogily'), 'manage_options', 'simpleblogily-info.php', 'simpleblogily_info_page' );
}

function simpleblogily_info_page() {
	$user = wp_get_current_user();
	?>
	<div class="wrap about-wrap simpleblogily-add-css">
		<div>
			<h1>
				<?php echo __('Welcome to Simple Blogily!','simpleblogily'); ?>
			</h1>

			<div class="feature-section three-col">
				<div class="col">
					<div class="widgets-holder-wrap">
						<h3><?php echo __("Contact Support", "simpleblogily"); ?></h3>
						<p><?php echo __("Getting started with a new theme can be difficult, if you have issues with Simple Blogily then throw us an email.", "simpleblogily"); ?></p>
						<p><a target="blank" href="<?php echo esc_url('https://superbthemes.com/help-contact/', 'simpleblogily'); ?>" class="button button-primary">
							<?php echo __("Contact Support", "simpleblogily"); ?>
						</a></p>
					</div>
				</div>
				<div class="col">
					<div class="widgets-holder-wrap">
						<h3><?php echo __("Review Simple Blogily", "simpleblogily"); ?></h3>
						<p><?php echo __("Nothing motivates us more than feedback, are you are enjoying Simple Blogily? We would love to hear what you think!", "simpleblogily"); ?></p>
						<p><a target="blank" href="<?php echo esc_url('https://wordpress.org/support/theme/simpleblogily/reviews/', 'simpleblogily'); ?>" class="button button-primary">
							<?php echo __("Submit A Review", "simpleblogily"); ?>
						</a></p>
					</div>
				</div>
				<div class="col">
					<div class="widgets-holder-wrap">
						<h3><?php echo __("Premium Edition", "simpleblogily"); ?></h3>
						<p><?php echo __("If you enjoy Simple Blogily and want to take your website to the next step, then check out our premium edition here.", "simpleblogily"); ?></p>
						<p><a target="blank" href="<?php echo esc_url('https://superbthemes.com/simpleblogily/', 'simpleblogily'); ?>" class="button button-primary">
							<?php echo __("Read More", "simpleblogily"); ?>
						</a></p>
					</div>
				</div>
			</div>
		</div>
		<hr>

		<h2><?php echo __("Free Vs Premium","simpleblogily"); ?></h2>
		<div class="simpleblogily-button-container">
			<a target="blank" href="<?php echo esc_url('https://superbthemes.com/simpleblogily/', 'simpleblogily'); ?>" class="button button-primary">
				<?php echo __("Read Full Description", "simpleblogily"); ?>
			</a>
			<a target="blank" href="<?php echo esc_url('https://superbthemes.com/demo/simpleblogily/', 'simpleblogily'); ?>" class="button button-primary">
				<?php echo __("View Theme Demo", "simpleblogily"); ?>
			</a>
		</div>


		<table class="wp-list-table widefat">
			<thead>
				<tr>
					<th><strong><?php echo __("Theme Feature", "simpleblogily"); ?></strong></th>
					<th><strong><?php echo __("Basic Version", "simpleblogily"); ?></strong></th>
					<th><strong><?php echo __("Premium Version", "simpleblogily"); ?></strong></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><?php echo __("Background Color/Image	", "simpleblogily"); ?></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>

				<tr>
					<td><?php echo __("Premium Support", "simpleblogily"); ?></td>
					<td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>
				<tr>
					<td><?php echo __("Custom Footer Copyright Text", "simpleblogily"); ?></td>
					<td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>
				<tr>
					<td><?php echo __("Custom Navigation Colors", "simpleblogily"); ?></td>
					<td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>
				<tr>
					<td><?php echo __("Custom Blog Page Colors", "simpleblogily"); ?></td>
					<td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>
				<tr>
					<td><?php echo __("Custom Posts Colors", "simpleblogily"); ?></td>
					<td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>
				<tr>
					<td><?php echo __("Custom Page Colors", "simpleblogily"); ?></td>
					<td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>
				<tr>
					<td><?php echo __("Custom Footer Colors", "simpleblogily"); ?></td>
					<td><span class="cross"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/cross.png' ); ?>" alt="<?php echo __("No", "simpleblogily"); ?>" /></span></td>
					<td><span class="checkmark"><img src="<?php echo esc_url( get_template_directory_uri() . '/icons/check.png' ); ?>" alt="<?php echo __("Yes", "simpleblogily"); ?>" /></span></td>
				</tr>
			</tbody>
		</table>

	</div>
	<?php
}




/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme Simpleblogily for publication on WordPress.org
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/tgm/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/tgm/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/tgm/class-tgm-plugin-activation.php';
 */
require_once get_template_directory() . '/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'simpleblogily_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function simpleblogily_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

			array(
			'name'      => 'Superb Helper',
			'slug'      => 'superb-helper',
			'required'  => false,
		),


	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'simpleblogily',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

	
	);

	tgmpa( $plugins, $config );
}

