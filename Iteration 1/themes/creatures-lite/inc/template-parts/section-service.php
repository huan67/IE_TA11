<?php
  /**
  * Second Section
  **/

  $creaturehideser = get_theme_mod('creature_ser_hide','1');

  if( $creaturehideser == '' ){
?>
    <section class="services">
      <div class="aligner">
        <?php
          $showserttl = esc_html(get_theme_mod('creature_ser_ttl'));
          $dispserttl = '';
            if( !empty( $showserttl ) ){
              $dispserttl = '<div class="section_head"><h2 class="section_title"><span>'.$showserttl.'</span></h2><div class="sec-separator"><span class="secicon"><i class="fa fa-paw" aria-hidden="true"></i></span></div></div>';
            }

            echo $dispserttl;
        ?>
        <div class="flex">
            <?php
              for( $ser = 1; $ser<=3; $ser++ ){
                if( get_theme_mod( 'creature_service'.$ser,true ) !='' ){
                  $serquery = new WP_Query(array('page_id' => get_theme_mod( 'creature_service'.$ser )));
                  while( $serquery->have_posts() ) : $serquery->the_post();
            ?>        
                    <div class="column-third">
                      <div class="service-col">
						<?php if( has_post_thumbnail() ){
							echo '<div class="service-thumb">'.get_the_post_thumbnail().'</div>';
						}?>
						<div class="service-data">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo creatures_excerpt(14); ?></p>
						</div>
                      </div><!-- service col -->
                    </div><!-- col -->
            <?php
                  endwhile;
                }
              }
            ?>
        </div><!-- row -->
      </div><!-- aligner -->
    </section>
      
<?php
  }
?>