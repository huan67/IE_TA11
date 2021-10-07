<?php
/**
 * The template for displaying search forms in Creatures Lite
 *
 * @package Creatures Lite
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'creatures-lite' ); ?>" value="<?php echo get_search_query() ; ?>" name="s">
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'creatures-lite' ); ?>">
</form>
