<?php
/**
 * The template for displaying featured content
 *
 * @package WEN_Commerce
 */
?>

<?php
$enable_content = get_theme_mod( 'wen_commerce_featured_content_option', 'disabled' );

if ( ! wen_commerce_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$wen_commerce_title       = get_theme_mod( 'wen_commerce_featured_content_title' );
$wen_commerce_description = get_theme_mod( 'wen_commerce_featured_content_description' ); 

$classes[] = 'layout-three';
$classes[] = 'page';
$classes[] = 'section';

if ( ! $wen_commerce_title && ! $wen_commerce_description ) {
	$classes[] = 'no-section-heading';
}
?>

<div id="featured-content-section" class="featured-content-section <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<?php if ( $wen_commerce_title || $wen_commerce_description ) : ?>
			<div class="section-heading-wrapper">
				<?php if ( $wen_commerce_title ) : ?>
					<div class="section-title-wrapper">
						<h2 class="section-title"><?php echo wp_kses_post( $wen_commerce_title ); ?></h2>
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

		<div class="section-content-wrapper featured-content-wrapper layout-three">
			<?php
			get_template_part( 'template-parts/featured-content/content-featured' );
			?>
		</div><!-- .section-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- #featured-content-section -->
