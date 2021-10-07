<div class="site-name">
	<?php if ( has_custom_logo() ) { ?>
		<div class="custom-logo">
			<?php creatures_lite_the_custom_logo(); ?>
		</div><!-- cutom logo -->
	<?php } ?>
	<div class="site-name-desc">
		<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php esc_html(bloginfo( 'name' )); ?></a></h1>
		<?php
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) :
				echo '<p>'.esc_html($description).'</p>';
			endif;
		?>
	</div><!-- site-name-desc -->
</div><!-- .logo -->