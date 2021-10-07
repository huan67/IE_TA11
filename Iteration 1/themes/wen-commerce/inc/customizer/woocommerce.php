<?php
/**
 * Adding support for WooCommerce Plugin
 */

if ( ! class_exists( 'WooCommerce' ) ) {
    // Bail if WooCommerce is not installed
    return;
}

if ( ! function_exists( 'wen_commerce_woocommerce_setup' ) ) :
    /**
     * Sets up support for various WooCommerce features.
     */
    function wen_commerce_woocommerce_setup() {
        add_theme_support( 'woocommerce', array(
            'thumbnail_image_width'         => 480,
            'single_image_width'            => 580,
            'gallery_thumbnail_image_width' => 120,
        ) );

        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    
    }
endif; //wen_commerce_woocommerce_setup
add_action( 'after_setup_theme', 'wen_commerce_woocommerce_setup' );

/**
 * Add WooCommerce Options to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_woocommerce_options( $wp_customize ) {

    wen_commerce_register_option( $wp_customize, array(
            'name'              => 'wen_commerce_woocommerce_layout',
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'wen_commerce_sanitize_select',
            'description'       => esc_html__( 'Layout for WooCommerce Pages', 'wen-commerce' ),
            'label'             => esc_html__( 'WooCommerce Layout', 'wen-commerce' ),
            'section'           => 'wen_commerce_layout_options',
            'type'              => 'radio',
            'choices'           => array(
                'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'wen-commerce' ),
                'no-sidebar'            => esc_html__( 'No Sidebar', 'wen-commerce' ),
            ),
        )
    );
}
add_action( 'customize_register', 'wen_commerce_woocommerce_options' );

/**
 * uses remove_action to remove the WooCommerce Wrapper and add_action to add Main Wrapper
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'wen_commerce_woocommerce_start' ) ) :
    function wen_commerce_woocommerce_start() {
    	echo '<div id="primary" class="content-area"><main role="main" class="site-main woocommerce" id="main"><div class="woocommerce-posts-wrapper">';
    }
endif; //wen_commerce_woocommerce_start
add_action( 'woocommerce_before_main_content', 'wen_commerce_woocommerce_start', 15 );

if ( ! function_exists( 'wen_commerce_woocommerce_end' ) ) :
    function wen_commerce_woocommerce_end() {
    	echo '</div><!-- .woocommerce-posts-wrapper --></main><!-- #main --></div><!-- #primary -->';
    }
endif; //wen_commerce_woocommerce_end
add_action( 'woocommerce_after_main_content', 'wen_commerce_woocommerce_end', 15 );

function wen_commerce_woocommerce_shorting_start() {
	echo '<div class="woocommerce-shorting-wrapper">';
}
add_action( 'woocommerce_before_shop_loop', 'wen_commerce_woocommerce_shorting_start', 10 );

function wen_commerce_woocommerce_shorting_end() {
	echo '</div><!-- .woocommerce-shorting-wrapper -->';
}
add_action( 'woocommerce_before_shop_loop', 'wen_commerce_woocommerce_shorting_end', 40 );

function wen_commerce_woocommerce_product_container_start() {
	echo '<div class="product-container">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'wen_commerce_woocommerce_product_container_start', 20 );

function wen_commerce_woocommerce_product_container_end() {
	echo '</div><!-- .product-container -->';
}
add_action( 'woocommerce_after_shop_loop_item', 'wen_commerce_woocommerce_product_container_end', 20 );

if ( ! function_exists( 'wen_commerce_header_cart' ) ) {
    /**
     * Display Header Cart
     *
     * @since  1.0.0
     * @uses  wen_commerce_is_woocommerce_activated() check if WooCommerce is activated
     * @return void
     */
    function wen_commerce_header_cart( $outer_class = '' ) {
        if ( is_cart() ) {
            $class = 'cart-icon current-menu-item';
        } else {
            $class = 'cart-icon';
        }

        //account class
        if ( is_account_page() ) {
            $accountclass = 'menu-inline current-menu-item';
        } else {
            $accountclass = 'menu-inline';
        }
        ?>
        <div id="site-header-cart-wrapper" class="menu-wrapper <?php echo esc_attr( $outer_class ); ?>">
            <ul id="site-header-cart" class="site-header-cart menu">

                <li class="<?php echo esc_attr( $accountclass ); ?>">
                    <?php wen_commerce_my_account_icon_link( esc_html__( 'My Account', 'wen-commerce' ) ); ?>
                </li>

                <li class="<?php echo esc_attr( $class ); ?>">
                    <?php wen_commerce_cart_link(); ?>

                    <div class="cart-wrapper">
                        <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
                    </div>
                </li>
            </ul>
        </div>
        <?php
    }
}

if ( ! function_exists( 'wen_commerce_cart_link' ) ) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function wen_commerce_cart_link() {
        ?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'wen-commerce' ); ?>"><?php echo wen_commerce_get_svg( array( 'icon' => 'shopping-bag', 'title' => esc_html__( 'View your shopping cart', 'wen-commerce' ) ) ); ?><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?><span class="count"><?php echo absint( WC()->cart->get_cart_contents_count() ); ?></span></a>
        <?php
    }
}

if ( ! function_exists( 'wen_commerce_my_account_icon_link' ) ) {
    /**
     * The account callback function
     */
    function wen_commerce_my_account_icon_link( $label ) {

        $label_html = '';

        $label_title = esc_html__( 'My Account', 'wen-commerce' );

        if ( $label ) {
            $label_html = '<span class="my-account-label">' . esc_html( $label ) . '</span>';
            $label_title = $label;
        }
        echo '<a class="my-account" href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" title="' . esc_attr( $label_title ) . '">' . wen_commerce_get_svg( array( 'icon' => 'user' ) ) . $label_html . '</a>';
    }
}

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function wen_commerce_woocommerce_active_body_class( $classes ) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter( 'body_class', 'wen_commerce_woocommerce_active_body_class' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function wen_commerce_woocommerce_scripts() {
    $font_path   = WC()->plugin_url() . '/fonts/';
    $inline_font = '@font-face {
            font-family: "star";
            src: url("' . $font_path . 'star.eot");
            src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
                url("' . $font_path . 'star.woff") format("woff"),
                url("' . $font_path . 'star.ttf") format("truetype"),
                url("' . $font_path . 'star.svg#star") format("svg");
            font-weight: normal;
            font-style: normal;
        }';

    wp_add_inline_style( 'wen-commerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'wen_commerce_woocommerce_scripts' );

if ( ! function_exists( 'wen_commerce_woocommerce_product_columns_wrapper' ) ) {
    /**
     * Product columns wrapper.
     *
     * @return  void
     */
    function wen_commerce_woocommerce_product_columns_wrapper() {
        // Get option from Customizer=> WooCommerce=> Product Catlog=> Products per row.
        echo '<div class="wocommerce-section-content-wrapper columns-' . absint( get_option( 'woocommerce_catalog_columns', 4 ) ) . '">';
    }
}
add_action( 'woocommerce_before_shop_loop', 'wen_commerce_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'wen_commerce_woocommerce_product_columns_wrapper_close' ) ) {
    /**
     * Product columns wrapper close.
     *
     * @return  void
     */
    function wen_commerce_woocommerce_product_columns_wrapper_close() {
        echo '</div>';
    }
}
add_action( 'woocommerce_after_shop_loop', 'wen_commerce_woocommerce_product_columns_wrapper_close', 40 );

/**
* woo_hide_page_title
*
* Removes the "shop" title on the main shop page
*
* @access      public
* @since       1.0
* @return      void
*/
 
function wen_commerce_woocommerce_hide_page_title() { 
    if ( is_shop() && wen_commerce_has_header_media_text() ) {
        return false;
    }

    return true;  
}
add_filter( 'woocommerce_show_page_title', 'wen_commerce_woocommerce_hide_page_title' ); 

/**
 * Remove breadcrumb from default position
 * Check template-parts/header/breadcrumb.php
 */
function wen_commerce_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}
add_action( 'init', 'wen_commerce_remove_wc_breadcrumbs' );

/**
 * Include Woo Products Showcase
 */
require get_parent_theme_file_path( 'inc/customizer/woo-products-showcase.php' );
