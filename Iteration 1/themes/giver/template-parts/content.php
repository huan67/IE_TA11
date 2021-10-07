<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giver
 */
if ( defined('FW') ) {
  $giver_post_meta = fw_get_db_customizer_option('giver_post_meta');
  $giver_readmore_text = fw_get_db_customizer_option('giver_readmore_text');
}
$giver_post_meta = isset( $giver_post_meta ) ? $giver_post_meta : '';
$giver_readmore_text = isset( $giver_readmore_text ) ? $giver_readmore_text : esc_html__( 'Read More', 'giver' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-thumbnail">
		<?php giver_post_thumbnail(); ?>
	</div>
	<div class="entry-meta-wrapper">
		<?php
		if ( !$giver_post_meta && 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				giver_posted_on();
				giver_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</div><!-- .entry-header -->

	<?php 
		if ( !is_single() && 'post' == get_post_type() ) { ?>
		<div class="entry-title">
			<h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3>
		</div>
	<?php	}
	?>

	<div class="entry-content">
		<?php
		if ( is_single() && 'post' == get_post_type() ) {
				the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'giver' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
		} else { ?>
			<p><?php echo wp_trim_words( get_the_content(), 50); ?></p>
		<?php }
	
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'giver' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<div class="entry-footer">
		<?php 
		if ( is_single() && 'post' == get_post_type() ) {
			giver_entry_footer(); 
		} ?>
	</div><!-- .entry-footer -->

	<?php 
		if ( !is_single() && 'post' == get_post_type() ) { ?>
	<div class="entry-more">
		 <a href="<?php echo esc_url( get_permalink() ); ?>" class="reammore-btn">
		  	<?php echo esc_html( $giver_readmore_text ); ?>	
		  </a> 
	</div><!-- .entry-more -->
	<?php	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->
