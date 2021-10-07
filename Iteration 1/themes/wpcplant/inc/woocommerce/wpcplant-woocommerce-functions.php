<?php
/**
 * WPCplant WooCommerce functions.
 *
 * @package wpcplant
 */

/**
 * Checks if the current page is a product archive
 *
 * @return boolean
 */
function wpcplant_is_product_archive() {
	if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
		return true;
	} else {
		return false;
	}
}

