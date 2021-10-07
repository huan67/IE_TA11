<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package giver
 */

if ( defined('FW') ) {
	// global $post;
  $hide_footer = fw_get_db_post_option( get_the_ID(), 'giver_hide_footer', '');
  $giver_footer_widget_block = fw_get_db_customizer_option('giver_footer_widget_block');
}

$hide_footer = isset( $hide_footer ) ? $hide_footer : '';
$giver_footer_widget_block = isset( $giver_footer_widget_block ) ? $giver_footer_widget_block : '';

if ( !$hide_footer ) { ?>
	<footer id="giver-footer" class="site-footer">
		<div class="container">
			<div class="row">
					<?php
					if ( $giver_footer_widget_block ) {
			      get_template_part( 'template-parts/footer', 'widgets' );
			    }
				 ?>
				<div class="site-info text-center">
					&copy; 
					<?php 
						echo get_bloginfo( 'name');
					?>
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'giver' ) ); ?>">
						<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( '| Proudly powered by %s', 'giver' ), 'WordPress' );
						?>
					</a>
					<span class="sep"> | </span>
						<?php
						/* translators: 1: Theme name, 2: Theme author. */
						printf( esc_html__( 'Theme: %1$s by %2$s.', 'giver' ), 'giver', '<a href="http://annurtheme.com/">AnnurTheme</a>' );
						?>
				</div><!-- .site-info -->
			</div>	
		</div>	
	</footer><!-- #giver-footer -->
<?php }
?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
