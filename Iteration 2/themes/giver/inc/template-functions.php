<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package giver
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function giver_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'giver_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function giver_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'giver_pingback_header' );




/* Compress CSS */
if ( ! function_exists( 'giver_compress_css_lines' ) ) {
  function giver_compress_css_lines( $css ) {
    $css  = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
    $css  = str_replace( ': ', ':', $css );
    $css  = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
    return $css;
  }
}

/* Inline Style */
global $giver_all_inline_styles;
$giver_all_inline_styles = array();
if( ! function_exists( 'giver_add_inline_style' ) ) {
  function giver_add_inline_style( $style ) {
    global $giver_all_inline_styles;
    array_push( $giver_all_inline_styles, $style );
  }
}

/* Enqueue Inline Styles */
if ( ! function_exists( 'giver_enqueue_inline_styles' ) ) {
  function giver_enqueue_inline_styles() {

    global $giver_all_inline_styles;

    if ( ! empty( array_filter($giver_all_inline_styles) ) ) {
      echo '<style id="giver-inline-style" type="text/css">'. giver_compress_css_lines( join( '', $giver_all_inline_styles ) ) .'</style>';
    }

  }
  add_action( 'wp_footer', 'giver_enqueue_inline_styles' );
}



// footer widget

if ( ! function_exists( 'giver_widget_init' ) ) {
  function giver_widget_init() {
    if ( function_exists( 'register_sidebar' ) ) {

      // Footer Widgets
      if ( defined('FW') ) {
        $footer_widgets = fw_get_db_customizer_option('giver_footer_widget_layout');
      }
      $footer_widgets = isset( $footer_widgets ) ? $footer_widgets : '';
      
      if( $footer_widgets ) {

        switch ( $footer_widgets ) {
          case 3:
            $length = 3;
          break;

          case 4:
            $length = 4;
          break;

          default:
            $length = $footer_widgets;
          break;
        }

        for( $i = 0; $i < $length; $i++ ) {

          $num = ( $i+1 );

          register_sidebar( array(
            'id'            => 'footer-' . $num,
            'name'          => esc_html__( 'Footer Widget ', 'giver' ). $num,
            'description'   => esc_html__( 'Appears on footer section.', 'giver' ),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '<div class="clear"></div></div> <!-- end widget -->',
            'before_title'  => '<div class="widget-title"><h3>',
            'after_title'   => '</h3></div>'
          ) );

        }

      }
      // Footer Widgets


    }
  }
  add_action( 'widgets_init', 'giver_widget_init' );
}


/* Widget Layouts */
if ( ! function_exists( 'giver_footer_widgets' ) ) {
  function giver_footer_widgets() {

    $output = '';
    if ( defined('FW') ) {
      $giver_footer_widget_layout = fw_get_db_customizer_option('giver_footer_widget_layout');
    }
    $giver_footer_widget_layout = isset( $giver_footer_widget_layout ) ? $giver_footer_widget_layout : '';

    if( $giver_footer_widget_layout ) {

      switch ( $giver_footer_widget_layout ) {
        case 1: $widget = array('piece' => 1, 'class' => 'col col-lg-12'); break;
        case 2: $widget = array('piece' => 2, 'class' => 'col col-lg-6'); break;
        case 3: $widget = array('piece' => 3, 'class' => 'col col-lg-4'); break;
        case 4: $widget = array('piece' => 4, 'class' => 'col col-lg-3 col-md-3 col-sm-6'); break;
        default : $widget = array('piece' => 4, 'class' => 'col-lg-3'); break;
      }

      for( $i = 1; $i < $widget["piece"]+1; $i++ ) {

        $widget_class = ( isset( $widget["queue"] ) && $widget["queue"] == $i ) ? $widget["layout"] : $widget["class"];

        if (is_active_sidebar('footer-'. $i)) {
          $output .= '<div class="'. $widget_class .'">';
          ob_start();
            dynamic_sidebar( 'footer-'. $i );
          $output .= ob_get_clean();
          $output .= '</div>';
        }

      }
    }

    return $output;

  }
}