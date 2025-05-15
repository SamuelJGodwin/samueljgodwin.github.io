/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview changes asynchronously.
 *
 * https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#using-postmessage-for-improved-setting-previewing
 */

(function ( $ ) {
	//
	// Normal Colors
	//
	wp.customize( 'top_bar_transparent_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'top_bar_transparent_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'top_bar_transparent_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	//
	// Transparent Colors
	//
	wp.customize( 'top_bar_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'top_bar_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'top_bar_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );
})( jQuery );
