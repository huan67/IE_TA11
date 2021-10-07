<?php
/**
 * The template for displaying Woo Products Showcase
 *
 * @package WEN_Commerce
 */

if ( ! class_exists( 'WooCommerce' ) ) {
    // Bail if WooCommerce is not installed
    return;
}

$enable_content = get_theme_mod( 'wen_commerce_woo_products_showcase_option', 'disabled' );

if ( ! wen_commerce_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$number                = get_theme_mod( 'wen_commerce_woo_products_showcase_number', 5 );
$columns               = 5;
$paginate              = 'false';
$wen_commerce_orderby  = 'title';
$wen_commerce_order    = 'ASC';

$shortcode = '[products';

if ( $number ) {
	$shortcode .= ' limit="' . esc_attr( $number ) . '"';
}

$shortcode .= ' columns="' . absint( $columns ) . '"';

$shortcode .= ' paginate="' . esc_attr( $paginate ) . '"';

$shortcode .= ' orderby="' . esc_attr( $wen_commerce_orderby ) . '"';

$shortcode .= ' order="' . esc_attr( $wen_commerce_order ) . '"';

$shortcode .= ']';

$wen_commerce_title       = get_theme_mod( 'wen_commerce_woo_products_showcase_title' );
$wen_commerce_description = get_theme_mod( 'wen_commerce_woo_products_showcase_description' );
$classes[] = 'section';

if ( ! $wen_commerce_title && ! $wen_commerce_description ) {
	$classes[] = 'no-section-heading';
}
?>

<div id="product-content-section" class="product-content-section <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
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

		<div class="section-content-wrapper product-content-wrapper">
			<?php echo do_shortcode( $shortcode ); ?>
		</div><!-- .section-content-wrapper -->
	</div><!-- .wrapper -->
</div><!-- .sectionr -->
