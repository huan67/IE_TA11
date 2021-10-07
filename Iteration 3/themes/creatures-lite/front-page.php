<?php
/**
 *
 * @package Creatures Lite
 */

get_header();

if ( !is_home() && is_front_page() ) {
	get_template_part( 'inc/template-parts/theme','slider');
}

get_template_part('inc/template-parts/section','service');

get_template_part('inc/template-parts/section','about');

?>

<div class="main-aligner">

  <div class="content-area">

    <div class="middle-align content_sidebar">

      <div class="site-main" id="sitemain">

        <?php
          if ( have_posts() ) :

            // Start the Loop.
            while ( have_posts() ) : the_post();

              /*
              * Include the post format-specific template for the content. If you want to
              * use this in a child theme, then include a file called called content-___.php
              * (where ___ is the post format) and that will be used instead.
              */
              get_template_part( 'content-page', get_post_format() );

            endwhile;
            // Previous/next post navigation.
            the_posts_pagination();
            wp_reset_postdata();

            else :
            // If no content, include the "No posts found" template.
            get_template_part( 'no-results' );

          endif;
        ?>

      </div><!-- sitemain -->

      <?php get_sidebar();?><div class="clear"></div>

    </div><!-- content sidebar -->

  </div><!-- content area -->

<?php get_footer(); ?>