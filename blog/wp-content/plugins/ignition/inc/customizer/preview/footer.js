/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview changes asynchronously.
 *
 * https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#using-postmessage-for-improved-setting-previewing
 */

(function ( $ ) {
	var $body = $( 'body' );

	//
	// Layout
	//
	wp.customize( 'footer_is_visible', function ( value ) {
		var initialValue = $body.hasClass( 'ignition-footer-visible-on' );

		var $element = $( '.footer' );

		var toggleElements = function ( option_value ) {
			if ( option_value ) {
				$element.show();
			} else {
				$element.hide();
			}
		};

		toggleElements( initialValue );

		value.bind( toggleElements );
	} );

	//
	// Colors
	//
	wp.customize( 'footer_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'footer_colors_background_image', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_image_background_by_id(
				to,
				args.image_size,
				args.css
			) );
		} );
	} );

	wp.customize( 'footer_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'footer_colors_title', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'footer_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	//
	// Credits Colors
	//
	wp.customize( 'footer_credits_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'footer_credits_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'footer_credits_colors_link', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'footer_credits_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

})( jQuery );
