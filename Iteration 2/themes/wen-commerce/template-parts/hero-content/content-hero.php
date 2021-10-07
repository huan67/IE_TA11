<?php
/**
 * The template used for displaying hero content
 *
 * @package WEN_Commerce
 */

$enable_section = get_theme_mod( 'wen_commerce_hero_content_visibility', 'disabled' );

if ( ! wen_commerce_check_section( $enable_section ) ) {
	// Bail if hero content is not enabled
	return;
}

get_template_part( 'template-parts/hero-content/post-type-hero' );
