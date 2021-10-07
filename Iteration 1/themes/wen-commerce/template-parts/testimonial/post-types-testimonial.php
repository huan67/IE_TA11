<?php
/**
 * The template for displaying testimonial items
 *
 * @package WEN_Commerce
 */
?>

<?php
$number = get_theme_mod( 'wen_commerce_testimonial_number', 3 );

if ( ! $number ) {
	// If number is 0, then this section is disabled
	return;
}

$args = array(
	'ignore_sticky_posts' => 1 // ignore sticky posts
);

$post_list  = array();// list of valid post/page ids

$args['post_type'] = 'page';

for ( $i = 1; $i <= $number; $i++ ) {
	$wen_commerce_post_id = '';

	$wen_commerce_post_id = get_theme_mod( 'wen_commerce_testimonial_page_' . $i );

	if ( $wen_commerce_post_id && '' !== $wen_commerce_post_id ) {
		// Polylang Support.
		if ( class_exists( 'Polylang' ) ) {
			$wen_commerce_post_id = pll_get_post( $wen_commerce_post_id, pll_current_language() );
		}

		$post_list = array_merge( $post_list, array( $wen_commerce_post_id ) );

	}
}

$args['post__in'] = $post_list;
$args['orderby'] = 'post__in';

$args['posts_per_page'] = $number;
$loop = new WP_Query( $args );

if ( $loop -> have_posts() ) :
	while ( $loop -> have_posts() ) :
		$loop -> the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="hentry-inner">
				<div class="entry-container">
					<div class="entry-content">
						<div class="entry-title">
							<?php the_title(); ?>
						</div>
						<?php the_excerpt(); ?>
					</div>
					<?php 
					$position = get_theme_mod( 'wen_commerce_testimonial_position_' . absint( $loop->current_post + 1 ) );
					$name = get_theme_mod( 'wen_commerce_testimonial_title_' . absint( $loop->current_post + 1 ) );
					?>
					
					<div class="author-thumb">
						<?php wen_commerce_post_thumbnail( 'wen-commerce-stats', 'html', true ); ?>
					
						<?php if ( $name || $position ) : ?>
						<header class="entry-header">
							<?php if ( $name ): ?>
								<h2 class="entry-title"><a target="_blank" href="<?php the_permalink(); ?>">
									<?php echo wp_kses_post( $name ); ?></a></h2>
							<?php endif; ?>

							<?php if ( $position ): ?>
								<div class="entry-meta"><span class="position"><?php echo esc_html( $position ); ?></span></div>
							<?php endif; ?>
						</header>
				<?php endif; ?>
					</div> <!-- .author-thumb -->				
				</div><!-- .entry-container -->	
			</div><!-- .hentry-inner -->
		</article>
	<?php
	endwhile;
	wp_reset_postdata();
endif;
