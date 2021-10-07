<?php
  /**
  * First Section
  **/

  $creature_hide_fsec = get_theme_mod('creature_hide_aboutsec', '1' );

  if( $creature_hide_fsec == '' ){
    if( get_theme_mod( 'creature_about_sec',true ) != '' ) {
?>
	<section class="about">
		<div class="flex ac">
			<?php
				$fsec_query = new WP_Query( array( 'page_id' => get_theme_mod( 'creature_about_sec' ) ) );
				while( $fsec_query->have_posts() ) : $fsec_query->the_post();
				if( get_theme_mod( 'creature_about_sbttl',true ) != '' ) {
				  $abtsbttl .= '<h5>'.esc_html(get_theme_mod( 'creature_about_sbttl' )).'</h5>';
				}
				if( get_theme_mod( 'creature_about_more',true ) != '' ){
				  $displaybtn .= '<a href="'.get_the_permalink().'" class="about-more-btn">'.esc_html(get_theme_mod( 'creature_about_more',true )).'</a>';
				}

				if( has_post_thumbnail() ) {
				  $src = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'full' );
				  $url = $src[0];
				  echo '<div class="column-half"><img src="'.$url.'"></div><!-- col -->';
				}
				
				echo '<div class="column-half"><div class="inner-about-content">';
				  echo $abtsbttl;
				  the_title('<h3>','</h3>');
				  the_excerpt();
				  echo $displaybtn;
				echo '</div></div><!-- col -->';

				endwhile; wp_reset_postdata();
			?>
		</div><!-- row -->
	</section><!-- first section -->
<?php 
    }
  }
?>