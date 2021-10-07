<?php

/*
 * Check if admin
 */
if( is_admin() ){
	add_action( 'admin_init', 'checklistinpost_register_backend_options' );
	add_action( 'admin_menu', 'checklistinpost_add_backend_menu' );
	//TODO Cookies into js file

	/*
 * Add backend menu
 */
	function checklistinpost_add_backend_menu(){
		//create new top-level menu
		add_menu_page('Checklist in Post Options', 'Checklist in Post', 'administrator', __FILE__, 'checklistinpost_options_page' , 'dashicons-yes' );

		//call register settings function
		add_action( 'admin_init', 'checklistinpost_register_backend_options' );
	}

	/*
	 * Register settings
	 */
	function checklistinpost_register_backend_options() {
		register_setting( 'checklistinpost_options', 'cookies', array('boolean','','','','') );
	}

	function checklistinpost_options_page(){
		?>
		<div class="wrap">
			<h1><?php echo __('Checklist in Post Options','checklistinpost'); ?></h1>

			<hr/>
			<form method="post" action="options.php">
				<?php
				settings_fields('checklistinpost_options');
				do_settings_sections('checklistinpost_options');


				?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php echo __('Cookies','checklistinpost'); ?></th>
						<td><input type="checkbox" name="cookies" value="1"  <?php checked( '1', get_option( 'cookies' ) ); ?> />
							<p class="description"><?php echo __('Use cookies to remember users selection','checklistinpost'); ?></p>
						</td>
					</tr>
				</table>
				<?php
				submit_button();
				?>



			</form>

		</div>
	<?php } //end of options page

} else {
	// non-admin enqueues, actions, and filters
}



