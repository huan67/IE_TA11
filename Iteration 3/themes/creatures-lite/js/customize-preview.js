/**
 * Live-update creatures-lited settings in real time in the Customizer preview.
 */

( function( $ ) {
		api = wp.customize;

	// Site title.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-name-desc a' ).text( to );
		} );
	} );

	// Site tagline.
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-name-desc p' ).text( to );
		} );
	} );

	// Site tagline.
	api( 'creatures-call-txt', function( value ) {
		value.bind( function( to ) {
			$( '.header-info' ).text( to );
		} );
	} );

} )( jQuery );
