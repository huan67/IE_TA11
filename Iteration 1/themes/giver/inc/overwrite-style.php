<?php
/*
 *  Custom Style
 */

/* All Dynamic CSS Styles */
if ( ! function_exists( 'giver_dynamic_styles' ) ) {
  function giver_dynamic_styles() {


  ob_start();
  global $post;

  if ( defined('FW') ) {
    $giver_menu_color = fw_get_db_customizer_option('giver_menu_color');
    $giver_menu_bg_color = fw_get_db_customizer_option('giver_menu_bg_color');
    $giver_menu_logo_color = fw_get_db_customizer_option('giver_menu_logo_color');
    $giver_title_text_color = fw_get_db_customizer_option('giver_title_text_color');
    $giver_title_bg_color = fw_get_db_customizer_option('giver_title_bg_color');
    $giver_footer_copywrite_color = fw_get_db_customizer_option('giver_footer_copywrite_color');
    $giver_footer_copywrite_bg_color = fw_get_db_customizer_option('giver_footer_copywrite_bg_color');
    $giver_body_typography = fw_get_db_customizer_option('giver_body_typography');
    $giver_heading_typography = fw_get_db_customizer_option('giver_heading_typography');
  }
  $giver_menu_color = isset( $giver_menu_color ) ? $giver_menu_color : '';
  $giver_menu_bg_color = isset( $giver_menu_bg_color ) ? $giver_menu_bg_color : '';
  $giver_menu_logo_color = isset( $giver_menu_logo_color ) ? $giver_menu_logo_color : '';
  $giver_title_text_color = isset( $giver_title_text_color ) ? $giver_title_text_color : '';
  $giver_title_bg_color = isset( $giver_title_bg_color ) ? $giver_title_bg_color : '';
  $giver_footer_copywrite_color = isset( $giver_footer_copywrite_color ) ? $giver_footer_copywrite_color : '';
  $giver_footer_copywrite_bg_color = isset( $giver_footer_copywrite_bg_color ) ? $giver_footer_copywrite_bg_color : '';
  $giver_body_typography = isset( $giver_body_typography['family'] ) ? $giver_body_typography['family'] : '';
  $giver_heading_typography = isset( $giver_heading_typography['family'] ) ? $giver_heading_typography['family'] : '';

  if ( $giver_menu_color ) {?>
    .site-header #navbar>ul>li>a {
      color: <?php echo esc_attr( $giver_menu_color ); ?>;
    }
  <?php
  }

  if ( $giver_menu_bg_color ) {?>
    .site-header .navigation {
      background-color: <?php echo esc_attr( $giver_menu_bg_color ); ?>;
    }
  <?php
  }
 
  if ( $giver_menu_logo_color ) {?>
    .site-header .navbar-header .site-branding h2 a, .site-header .navbar-header .site-branding p a {
      color: <?php echo esc_attr( $giver_menu_logo_color ); ?>;
    }
  <?php
  }

  if ( $giver_title_text_color ) {?>
    .entry-header h2 {
      color: <?php echo esc_attr( $giver_title_text_color ); ?>;
    }
  <?php
  }

  if ( $giver_title_bg_color ) {?>
    .entry-header:before {
      background-color: <?php echo esc_attr( $giver_title_bg_color ); ?>;
    }
  <?php
  }

  if ( $giver_footer_copywrite_color ) {?>
    .site-footer .site-info, .site-footer .site-info a {
      color: <?php echo esc_attr( $giver_footer_copywrite_color ); ?>;
    }
  <?php
  }

  if ( $giver_footer_copywrite_bg_color ) {?>
    .site-footer {
      background-color: <?php echo esc_attr( $giver_footer_copywrite_bg_color ); ?>;
    }
  <?php
  }

  if ( $giver_body_typography ) {?>
     body {
      font-family: <?php echo esc_attr( $giver_body_typography ); ?>;
    }
  <?php
  }
  if ( $giver_heading_typography ) {?>
     h1, h2, h3, h4, h5, h6 {
      font-family: <?php echo esc_attr( $giver_heading_typography ); ?>;
    }
  <?php
  }

  $output = ob_get_clean();
  return $output;

  }

}

/* Custom Styles */
if( ! function_exists( 'giver_custom_css' ) ) {
  function giver_custom_css() {
    wp_enqueue_style('giver-default-style', get_template_directory_uri() . '/style.css');
    $output = giver_dynamic_styles();
    $custom_css = giver_compress_css_lines( $output );

    wp_add_inline_style( 'giver-default-style', $custom_css );
  }
  add_action( 'wp_enqueue_scripts', 'giver_custom_css' );
}