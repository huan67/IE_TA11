<?php
/**
 * The template for displaying service content
 *
 * @package WEN_Commerce
 */
?>

<?php
$enable_content    = get_theme_mod( 'wen_commerce_service_option', 'disabled' ); 

if ( ! wen_commerce_check_section( $enable_content ) ) {
	// Bail if service content is disabled.
	return;
}

$wen_commerce_title       = get_theme_mod( 'wen_commerce_service_title' );
$wen_commerce_description = get_theme_mod( 'wen_commerce_service_description' );

$classes[] = 'service-section';
$classes[] = 'section';
$classes[] = 'text-align-left';

if ( ! $wen_commerce_title && ! $wen_commerce_description ) {
	$classes[] = 'no-section-heading';
}
?>

<div id="service-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
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

		$wrapper_classes[] = 'section-content-wrapper service-content-wrapper';

		$wrapper_classes[] = 'layout-three';
		?>

		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<?php
			get_template_part( 'template-parts/service/content-service' );
			?>
		</div><!-- .service-wrapper -->
	</div><!-- .wrapper -->
</div><!-- #service-section -->
