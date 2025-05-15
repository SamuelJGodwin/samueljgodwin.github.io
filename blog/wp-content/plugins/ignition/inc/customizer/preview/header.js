/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview changes asynchronously.
 *
 * https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#using-postmessage-for-improved-setting-previewing
 */

(function ( $ ) {
	//
	// Layout
	//
	wp.customize( 'header_layout_type', function ( value ) {
		value.bind( function ( to ) {
			var $header = $( '.header' );
			var $hero   = $( '.page-hero' );
			if ( to === 'normal' ) {
				$header.addClass( 'header-normal' );
				$header.removeClass( 'header-fixed' );
			} else if ( to === 'transparent' ) {
				$header.addClass( 'header-fixed' );
				$header.removeClass( 'header-normal' );
				$hero.show();
			}
		} );
	} );

	// Keeping this callback separate from the above, as it's actually about the alternative logo,
	// rather than header_layout_type.
	wp.customize( 'header_layout_type', function ( value ) {
		value.bind( function ( to ) {
			var $logo        = $( '.custom-logo' );
			var logo_url     = $logo.data( 'logo' );
			var logo_alt_url = $logo.data( 'logo-alt' );

			if ( to === 'normal' && logo_url ) {
				$logo.attr( 'src', logo_url );
			} else if ( to === 'transparent' && logo_alt_url ) {
				$logo.attr( 'src', logo_alt_url );
			}
		} );
	} );

	wp.customize( 'header_layout_is_full_width', function ( value ) {
		value.bind( function ( to ) {
			if ( to ) {
				$( '.header' ).addClass( 'header-fullwidth' );
			} else {
				$( '.header' ).removeClass( 'header-fullwidth' );
			}
		} );
	} );

	wp.customize( 'header_layout_menu_mobile_slide_right', function ( value ) {
		value.bind( function ( to ) {
			if ( to ) {
				$( 'body' ).addClass( 'ignition-mobile-nav-slide-right-on' );
			} else {
				$( 'body' ).removeClass( 'ignition-mobile-nav-slide-right-on' );
			}
		} );
	} );

	wp.customize( 'header_layout_menu_mobile_breakpoint', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_value(
				args.breakpoint,
				to.desktop,
				args.css,
				args.breakpoint_limit
			) );
		} );
	} );

	//
	// Colors
	//
	wp.customize( 'header_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_colors_background_image', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_image_background_by_id(
				to,
				args.image_size,
				args.css
			) );
		} );
	} );

	wp.customize( 'header_colors_overlay', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_colors_submenu_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_colors_submenu_background_hover', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_colors_submenu_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_colors_submenu_text_hover', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );


	//
	// Transparent header colors
	//
	wp.customize( 'header_transparent_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_background_image', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_image_background_by_id(
				to,
				args.image_size,
				args.css
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_overlay', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_submenu_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_submenu_background_hover', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_submenu_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_transparent_colors_submenu_text_hover', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	//
	// Sticky header colors
	//
	wp.customize( 'header_sticky_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_background_image', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_image_background_by_id(
				to,
				args.image_size,
				args.css
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_overlay', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_submenu_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_submenu_background_hover', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_submenu_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_sticky_colors_submenu_text_hover', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	//
	// Mobile nav colors
	//
	wp.customize( 'header_mobile_nav_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_mobile_nav_colors_link', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'header_mobile_nav_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

})( jQuery );
