<?php
/**
 * Adding support for WooCommerce Products Showcase Option
 */

/**
 * Add WooCommerce Product Showcase Options to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wen_commerce_woo_products_showcase( $wp_customize ) {
   $wp_customize->add_section( 'wen_commerce_woo_products_showcase', array(
        'title' => esc_html__( 'WooCommerce Products Showcase', 'wen-commerce' ),
        'panel' => 'wen_commerce_theme_options',
    ) );

    wen_commerce_register_option( $wp_customize, array(
            'name'              => 'wen_commerce_woo_products_showcase_option',
            'default'           => 'disabled',
            'sanitize_callback' => 'wen_commerce_sanitize_select',
            'choices'           => wen_commerce_section_visibility_options(),
            'label'             => esc_html__( 'Enable on', 'wen-commerce' ),
            'section'           => 'wen_commerce_woo_products_showcase',
            'type'              => 'select',
        )
    );

    wen_commerce_register_option( $wp_customize, array(
            'name'              => 'wen_commerce_woo_products_showcase_title',
            'sanitize_callback' => 'wp_kses_post',
            'active_callback'   => 'wen_commerce_is_woo_products_showcase_active',
            'label'             => esc_html__( 'Title', 'wen-commerce' ),
            'section'           => 'wen_commerce_woo_products_showcase',
            'type'              => 'text',
        )
    );

    wen_commerce_register_option( $wp_customize, array(
            'name'              => 'wen_commerce_woo_products_showcase_description',
            'sanitize_callback' => 'wp_kses_post',
            'active_callback'   => 'wen_commerce_is_woo_products_showcase_active',
            'label'             => esc_html__( 'Description', 'wen-commerce' ),
            'section'           => 'wen_commerce_woo_products_showcase',
            'type'              => 'textarea',
        )
    );

    wen_commerce_register_option( $wp_customize, array(
            'name'              => 'wen_commerce_woo_products_showcase_number',
            'default'           => 5,
            'sanitize_callback' => 'wen_commerce_sanitize_number_range',
            'active_callback'   => 'wen_commerce_is_woo_products_showcase_active',
            'description'       => esc_html__( 'Save and refresh the page if No. of Products is changed. Set -1 to display all', 'wen-commerce' ),
            'input_attrs'       => array(
                'style' => 'width: 50px;',
                'min'   => -1,
            ),
            'label'             => esc_html__( 'No of Products', 'wen-commerce' ),
            'section'           => 'wen_commerce_woo_products_showcase',
            'type'              => 'number',
        )
    );
}
add_action( 'customize_register', 'wen_commerce_woo_products_showcase', 10 );

/** Active Callback Functions **/
if ( ! function_exists( 'wen_commerce_is_woo_products_showcase_active' ) ) :
    /**
    * Return true if featured content is active
    *
    * @since WEN Commerce 1.0
    */
    function wen_commerce_is_woo_products_showcase_active( $control ) {
        $enable = $control->manager->get_setting( 'wen_commerce_woo_products_showcase_option' )->value();

        return wen_commerce_check_section( $enable );
    }
endif;
