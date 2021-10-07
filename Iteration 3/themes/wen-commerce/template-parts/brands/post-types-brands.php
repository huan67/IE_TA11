<?php
/**
 * The template for displaying brands items
 *
 * @package WEN_Commerce
 */	

$number       = get_theme_mod( 'wen_commerce_brands_number', 2 );
$post_list    = array();
$no_of_post   = 0;

$args = array(
	'post_type'           => 'post',
	'ignore_sticky_posts' => 1, // ignore sticky posts.
);

$args['post_type'] = 'page';

for ( $i = 1; $i <= $number; $i++ ) {
	$wen_commerce_post_id = '';

	$wen_commerce_post_id = get_theme_mod( 'wen_commerce_brands_page_' . $i );

	if ( $wen_commerce_post_id && '' !== $wen_commerce_post_id ) {
		$post_list = array_merge( $post_list, array( $wen_commerce_post_id ) );

		$no_of_post++;
	}
}

$args['post__in'] = $post_list;
$args['orderby']  = 'post__in';

if ( 0 === $no_of_post ) {
	return;
}

$args['posts_per_page'] = $no_of_post;

$loop = new WP_Query( $args );


if ( $loop -> have_posts() ) :
	while ( $loop -> have_posts() ) :
		$loop -> the_post();

		$description  = get_theme_mod('wen_commerce_brands_description_' . absint( $loop->current_post + 1 ) );
		$button_text  = get_theme_mod('wen_commerce_brands_button_text_' . absint( $loop->current_post + 1 ) );

		$post_class = 'hentry';

		$thumbnail = array( 840, 560 );

		if ( has_post_thumbnail() ) {
			$thumb_url = get_the_post_thumbnail_url( get_the_ID(), $thumbnail );
		} else {
			$thumb_url = trailingslashit( esc_url ( get_template_directory_uri() ) ) . '/images/no-thumb-840x560.jpg';
		}
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
			<div class="hentry-inner">
				<div class="post-thumbnail" style="background-image: url( '<?php echo esc_url( $thumb_url ); ?>' )">
					<a class="cover-link" href="<?php the_permalink(); ?>">
					</a>
				</div>

				<?php 
				$wen_commerce_title = 'entry-title';
				?>
				<div class="entry-container product-container">
					<header class="entry-header">
						<?php the_title( '<h2 class="' . esc_attr( $wen_commerce_title ) . '"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h2>' ); ?>
					</header>

					<?php if( $description ) : ?>
						<div class="entry-content">
							<?php echo esc_html( $description ); ?>
						</div>
					<?php endif; ?>

					<?php if( $button_text ) : ?>
						<p class="view-more">
							<a class="button" target="_blank" href="<?php the_permalink(); ?>"><?php echo esc_html( $button_text ); ?></a>
						</p>
					<?php endif; ?>
				</div><!-- .entry-container -->
			</div><!-- .hentry-inner -->
		</article>
<?php

	endwhile;
	wp_reset_postdata();
endif;
