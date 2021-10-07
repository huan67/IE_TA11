<?php
/**
 * Displays Footer Navigation
 *
 * @package WEN_Commerce
 */
?>

<?php if ( has_nav_menu( 'social-footer' ) ) : ?>
	<div id="footer-menu-section" class="site-footer-menu">
		<div class="wrapper">

			<?php if ( has_nav_menu( 'social-footer' ) ) : ?>
				<nav id="social-footer-navigation" class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'wen-commerce' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'social-footer',
							'menu_class'     => 'social-links-menu',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'       => '</span>' . wen_commerce_get_svg( array( 'icon' => 'chain' ) ),

						) );
					?>
				</nav><!-- .social-navigation -->
			<?php endif; ?>
		</div><!-- .wrapper -->
	</div><!-- #footer-menu-section -->
<?php endif;
