<?php
/**
 * The template used for displaying slider
 *
 * @package WEN_Commerce
 */
?>
<?php
$enable_slider = get_theme_mod( 'wen_commerce_slider_option', 'disabled' );

if ( ! wen_commerce_check_section( $enable_slider ) ) {
	return;
}
?>

<div id="feature-slider-section" class="feature-slider-section section">
	<div class="wrapper section-content-wrapper feature-slider-wrapper">
		<div class="main-slider owl-carousel">
			<?php
			get_template_part( 'template-parts/slider/post-type-slider' );
			?>
		</div><!-- .main-slider -->
	</div><!-- .wrapper -->
</div><!-- #feature-slider -->

