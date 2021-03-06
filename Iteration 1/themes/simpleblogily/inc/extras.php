<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package simpleblogily
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function simpleblogily_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'simpleblogily_body_classes' );

/**
 * Custom excerpt more
 */
function simpleblogily_custom_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}
	return '&hellip; ';
}
add_filter( 'excerpt_more', 'simpleblogily_custom_excerpt_more' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function simpleblogily_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', bloginfo( 'pingback_url' ), '">';
	}
}
add_action( 'wp_head', 'simpleblogily_pingback_header' );

function simpleblogily_light_get_image_src( $image_id, $size = 'full' ) {
	$img_attr = wp_get_attachment_image_src( intval( $image_id ), $size );
	if ( ! empty( $img_attr[0] ) ) {
		return $img_attr[0];
	}
}
function simpleblogily_excerpt_length( $length ) {
	return 100;
}
add_filter( 'excerpt_length', 'simpleblogily_excerpt_length', 1 );