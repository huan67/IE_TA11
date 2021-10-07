<?php
/**
 * The template for displaying featured posts on the front page
 *
 * @package WEN_Commerce
 */
$wen_commerce_type = 'page';
$number        = get_theme_mod( 'wen_commerce_featured_content_number', 3 );
$post_list     = array();
$no_of_post    = 0;

$args = array(
	'post_type'           => 'post',
	'ignore_sticky_posts' => 1, // ignore sticky posts.
);

// Get valid number of posts.
	$args['post_type'] = 'page';

	for ( $i = 1; $i <= $number; $i++ ) {
		$wen_commerce_post_id = '';

		$wen_commerce_post_id = get_theme_mod( 'wen_commerce_featured_content_page_' . $i );

		if ( $wen_commerce_post_id && '' !== $wen_commerce_post_id ) {
			$post_list = array_merge( $post_list, array( $wen_commerce_post_id ) );

			$no_of_post++;
		}
	}

	$args['post__in'] = $post_list;
	$args['orderby']  = 'post__in';

if ( ! $no_of_post ) {
	return;
}

$args['posts_per_page'] = $no_of_post;

$loop = new WP_Query( $args );

while ( $loop->have_posts() ) :
	
	$loop->the_post();

	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="hentry-inner">
			<?php
			$thumbnail = 'wen-commerce-content';

			wen_commerce_post_thumbnail( $thumbnail );
			?>

			<div class="entry-container">
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h2>' ); ?>
				</header>
				
				<div class="entry-summary"><?php the_excerpt(); ?></div><!-- .entry-summary -->
			</div><!-- .entry-container -->
		</div><!-- .hentry-inner -->
	</article>
<?php
endwhile;

wp_reset_postdata();
