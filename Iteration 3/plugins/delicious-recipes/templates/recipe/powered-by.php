<?php
/**
 * Powered by and notes block
 * 
 * @package Delicious_Recipes
 */

// Get global toggles.
$global_toggles = delicious_recipes_get_global_toggles_and_labels();

    /**
     * Hook to fire before the recipe card powered by section.
     */
    do_action( 'delicious_recipes_before_recipe_card_powered_by' );

    if( $global_toggles['enable_poweredby'] ) : ?>
    
        <div class="dr-poweredby">
            <span><?php esc_html_e( 'Recipe Card powered by', 'delicious-recipes' ); ?></span>
            <a href="https://wpdelicious.com/" target="_blank" rel="nofollow noopener noreferrer" ><img src="<?php echo esc_url( plugin_dir_url( DELICIOUS_RECIPES_PLUGIN_FILE ) ); ?>/assets/images/Delicious-Recipes.png" alt="Delicious Recipes"></a>
        </div>

    <?php endif;
    
    /**
     * Hook to fire before the recipe card powered by section.
     */
    do_action( 'delicious_recipes_before_recipe_card_powered_by' );
