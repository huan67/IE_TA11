<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Creatures Lite
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php if(is_singular() && pings_open()) { ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' )); ?>">
<?php } ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
	//wp_body_open hook from WordPress 5.2
	if ( function_exists( 'wp_body_open' ) ) {
	    wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
?>
<a class="skip-link screen-reader-text" href="#sitemain">
	<?php _e( 'Skip to content', 'creatures-lite' ); ?>
</a>

<?php 
	get_template_part('inc/template-parts/header','top');
?>
<div id="header">
	<div class="aligner">
		<div class="flex ac">
			<?php 
				get_template_part('inc/template-parts/site','title');
				get_template_part('inc/template-parts/header','info');
			?>
		</div><!-- flex -->
	</div><!-- aligner --><div class="clear"></div>
	<?php get_template_part('inc/template-parts/navigation'); ?>
</div><!-- #header -->