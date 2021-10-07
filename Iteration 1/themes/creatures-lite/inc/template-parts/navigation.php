<div class="navigation">
	<div class="aligner">
		<div class="toggle">
			<a class="toggleMenu" href="#"><?php esc_html_e('Menu','creatures-lite'); ?></a>
		</div><!-- toggle -->
		<div class="flex ac">
			<nav id="main-navigation" class="site-navigation primary-navigation sitenav" role="navigation">
				<?php 
					wp_nav_menu( 
						array( 
							'theme_location' => 'primary'
						)
					);
				?>
			</nav>
			<?php if( !empty( get_theme_mod( 'creature-cta-btn' ) ) ){ ?>
			<div class="cta-button">
				<a href="mailto:<?php echo esc_url( get_theme_mod( 'creature-cta-btn' ) ); ?>"><?php echo esc_url( get_theme_mod( 'creature-cta-btn' ) ); ?></a>
			</div>
			<?php } ?>
		</div><!-- flex -->
		
	</div><!-- aligner -->
</div><!-- navigation -->