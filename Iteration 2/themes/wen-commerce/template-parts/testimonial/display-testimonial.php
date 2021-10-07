<?php
/**
 * The template for displaying testimonial items
 *
 * @package WEN_Commerce
 */

$enable            = get_theme_mod( 'wen_commerce_testimonial_option', 'disabled' );

if ( ! wen_commerce_check_section( $enable ) ) {
	// Bail if featured content is disabled
	return;
}

$wen_commerce_title       = get_theme_mod( 'wen_commerce_testimonial_title' );
$wen_commerce_description = get_theme_mod( 'wen_commerce_testimonial_description' );

$classes[] = 'section testimonial-content-section';

if ( ! $wen_commerce_title && ! $wen_commerce_description ) {
	$classes[] = 'no-section-heading';
}
?>

<div id="testimonial-content-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<div class="full-content-wrap full-width">

			<?php if ( $wen_commerce_title || $wen_commerce_description ) : ?>
				<div class="section-heading-wrapper">

					<?php if ( $wen_commerce_title ) : ?>
						<div class="section-title-wrapper">
							<h2 class="section-title"wen_commerce_><?php echo wp_kses_post( $wen_commerce_title ); ?></h2>
						</div><!-- .page-title-wrapper -->
					<?php endif; ?>

					<?php if ( $wen_commerce_description ) : ?>
						<div class="section-description">
							<p>
								<?php
									echo wp_kses_post( $wen_commerce_description );
								?>
							</p>
						</div><!-- .section-description-wrapper -->
					<?php endif; ?>
				</div><!-- .section-heading-wrapper -->
			<?php endif; ?>

			<?php 
			
			$content_classes = 'section-content-wrapper testimonial-content-wrapper';

			$content_classes .= ' testimonial-slider owl-carousel';

			if ( get_theme_mod( 'wen_commerce_testimonial_dots', 1 ) ) {
				$content_classes .= ' owl-dots-enabled';
			} 
			?>

			<div class="<?php echo esc_attr( $content_classes ); ?>">
				<?php
				get_template_part( 'template-parts/testimonial/post-types-testimonial' );
				?>
			</div><!-- .section-content-wrapper -->
		</div><!-- .full-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- .testimonial-content-section -->
