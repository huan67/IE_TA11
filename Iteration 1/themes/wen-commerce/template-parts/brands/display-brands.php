<?php
/**
 * The template for displaying brands
 *
 * @package WEN_Commerce
 */
?>

<?php
$enable_content = get_theme_mod( 'wen_commerce_brands_option', 'disabled' );
$classes = array();

if ( ! wen_commerce_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$wen_commerce_title       = get_theme_mod( 'wen_commerce_brands_archive_title', esc_html__( 'Brands', 'wen-commerce' ) );
$description = get_theme_mod( 'wen_commerce_brands_description' );

$classes[] = 'section';

$classes[] = 'section-type-page';

if ( ! $wen_commerce_title && ! $description ) {
	$classes[] = 'no-section-heading';
}

?>

<div id="brands-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<?php if ( $wen_commerce_title || $description ) : ?>
			<div class="section-heading-wrapper featured-section-headline">

				<?php if ( $wen_commerce_title ) : ?>
					<div class="section-title-wrapper">
						<h2 class="section-title"><?php echo wp_kses_post( $wen_commerce_title ); ?></h2>
					</div><!-- .page-title-wrapper -->
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<div class="section-description">
						<p>
							<?php
								echo wp_kses_post( $description );
							?>
						</p>
					</div><!-- .taxonomy-description-wrapper -->
				<?php endif; ?>
			</div><!-- .section-heading-wrapper -->
		<?php endif; ?>

		<div class="section-content-wrapper brands-wrapper layout-two">

			<?php
			get_template_part( 'template-parts/brands/post-types-brands' );
			?>
		</div><!-- .brands-wrapper -->
	</div><!-- .wrapper -->
</div><!-- #brands-section -->
