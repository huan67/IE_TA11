<?php
/**
 * Primary Menu Template
 *
 * @package WEN_Commerce
 */
?>
<div id="site-header-menu" class="site-header-menu">
	<div id="primary-menu-wrapper" class="menu-wrapper show-in-desktop">
		<div class="menu-toggle-wrapper">
			<button id="menu-toggle" class="menu-toggle" aria-controls="top-menu" aria-expanded="false"><span class="menu-label"><?php echo esc_html_e( 'Menu', 'wen-commerce' ); ?></span></button>
		</div><!-- .menu-toggle-wrapper -->

		<div class="menu-inside-wrapper">
			<?php
    		if ( function_exists( 'wen_commerce_header_cart' ) ) {
				wen_commerce_header_cart();
			}
			?>

			<?php if ( has_nav_menu( 'menu-1' ) ) : ?>

				<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'wen-commerce' ); ?>">
					<?php
						wp_nav_menu( array(
								'container'      => '',
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'menu nav-menu',
							)
						);
					?>

			<?php else : ?>

				<nav id="site-navigation" class="main-navigation default-page-menu" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'wen-commerce' ); ?>">
					<?php wp_page_menu(
						array(
							'menu_class' => 'primary-menu-container',
							'before'     => '<ul id="menu-primary-items" class="menu nav-menu">',
							'after'      => '</ul>',
						)
					); ?>

			<?php endif; ?>

				</nav><!-- .main-navigation -->

			<div class="mobile-social-search">

				<?php if ( get_theme_mod( 'wen_commerce_primary_search_enable', 1 ) ) : ?>
				<div class="search-container">
					<?php get_search_form(); ?>
				</div>
				<?php endif; ?>
			</div><!-- .mobile-social-search -->
		</div><!-- .menu-inside-wrapper -->
	</div><!-- #primary-menu-wrapper.menu-wrapper -->

	<div id="primary-search-wrapper" class="menu-wrapper search-wrapper show-in-desktop">
		<div class="menu-toggle-wrapper">
			<button id="social-search-toggle" class="menu-toggle search-toggle">
				<?php echo wen_commerce_get_svg( array( 'icon' => 'search' ) ); echo wen_commerce_get_svg( array( 'icon' => 'close' ) ); ?>
				<span class="menu-label screen-reader-text"><?php echo esc_html_e( 'Search', 'wen-commerce' ); ?></span>
			</button>
		</div><!-- .menu-toggle-wrapper -->

		<div class="menu-inside-wrapper">
			<div class="search-container">
				<?php get_search_form(); ?>
			</div>
		</div><!-- .menu-inside-wrapper -->
	</div><!-- #social-search-wrapper.menu-wrapper -->

	<?php
	if ( function_exists( 'wen_commerce_header_cart' ) ) {
		wen_commerce_header_cart( 'show-in-desktop' );
	}
	?>

	<?php
	if ( function_exists( 'wen_commerce_header_cart' ) ) {
		wen_commerce_header_cart( 'show-in-mobile' );
	}
	?>

	<div id="primary-search-wrapper" class="menu-wrapper search-wrapper show-in-mobile">
		<div class="menu-toggle-wrapper">
			<button id="social-search-toggle" class="menu-toggle search-toggle">
				<?php echo wen_commerce_get_svg( array( 'icon' => 'search' ) ); echo wen_commerce_get_svg( array( 'icon' => 'close' ) ); ?>
				<span class="menu-label screen-reader-text"><?php echo esc_html_e( 'Search', 'wen-commerce' ); ?></span>
			</button>
		</div><!-- .menu-toggle-wrapper -->

		<div class="menu-inside-wrapper">
			<div class="search-container">
				<?php get_search_form(); ?>
			</div>
		</div><!-- .menu-inside-wrapper -->
	</div><!-- #social-search-wrapper.menu-wrapper -->

	<div id="primary-menu-wrapper" class="menu-wrapper show-in-mobile">
		<div class="menu-toggle-wrapper">
			<button id="menu-toggle-mobile" class="menu-toggle" aria-controls="top-menu" aria-expanded="false">
				<?php echo wen_commerce_get_svg( array( 'icon' => 'bars' ) ); echo wen_commerce_get_svg( array( 'icon' => 'close' ) ); ?><span class="menu-label"><?php echo esc_html_e( 'Menu', 'wen-commerce' ); ?></span></button>
		</div><!-- .menu-toggle-wrapper -->

		<div class="menu-inside-wrapper">
			<?php
    		if ( function_exists( 'wen_commerce_header_cart' ) ) {
				wen_commerce_header_cart();
			}
			?>

			<?php if ( has_nav_menu( 'menu-1' ) ) : ?>

				<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'wen-commerce' ); ?>">
					<?php
						wp_nav_menu( array(
								'container'      => '',
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'menu nav-menu',
							)
						);
					?>

			<?php else : ?>

				<nav id="site-navigation" class="main-navigation default-page-menu" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'wen-commerce' ); ?>">
					<?php wp_page_menu(
						array(
							'menu_class' => 'primary-menu-container',
							'before'     => '<ul id="menu-primary-items" class="menu nav-menu">',
							'after'      => '</ul>',
						)
					); ?>

			<?php endif; ?>

				</nav><!-- .main-navigation -->

			<div class="mobile-social-search">

				<?php if ( get_theme_mod( 'wen_commerce_primary_search_enable', 1 ) ) : ?>
				<div class="search-container">
					<?php get_search_form(); ?>
				</div>
				<?php endif; ?>
				
			</div><!-- .mobile-social-search -->
		</div><!-- .menu-inside-wrapper -->
	</div><!-- #primary-menu-wrapper.menu-wrapper -->
</div><!-- .site-header-menu -->

