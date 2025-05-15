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
	wp.customize( 'side_mode_site_layout_container_width', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_responsive(
				to,
				args.breakpoints_css,
				args.breakpoint_limit,
				args.edge_cases
			) );
		} );
	} );

	wp.customize( 'site_layout_container_width', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_responsive(
				to,
				args.breakpoints_css,
				args.breakpoint_limit,
				args.edge_cases
			) );
		} );
	} );

	wp.customize( 'site_layout_content_width', function ( value ) {
		value.bind( function ( to ) {
			$( '.main > .container > .row > div:first-child' )
				.attr( 'class', 'col-lg-' + to.desktop + ' col-12' );
		} );
	} );

	wp.customize( 'site_layout_sidebar_width', function ( value ) {
		value.bind( function ( to ) {
			$( '.main > .container > .row > div:last-child' )
				.attr( 'class', 'col-lg-' + to.desktop + ' col-12' );
		} );
	} );


	//
	// Colors
	//

	// Backgrounds
	if ( ! IGNITION_OPTIONS['site_colors_body_background'].disabled && IGNITION_OPTIONS['site_colors_body_background'].output_args.live_preview ) {
		wp.customize( 'site_colors_body_background', function ( value ) {
			value.bind( function ( to ) {
				var args = IGNITION_OPTIONS[ this.id ].render_args;
				IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
					args.css_var,
					to
				) );
			} );
		} );
	}

	if ( ! IGNITION_OPTIONS['site_colors_body_background_image'].disabled && IGNITION_OPTIONS['site_colors_body_background_image'].output_args.live_preview ) {
		wp.customize( 'site_colors_body_background_image', function ( value ) {
			value.bind( function ( to ) {
				var args = IGNITION_OPTIONS[ this.id ].render_args;
				IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_image_background_by_id(
					to,
					args.image_size,
					args.css
				) );
			} );
		} );
	}

	// Global
	wp.customize( 'site_colors_primary', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'site_colors_secondary', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'site_colors_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, [
				IGNITION_PREVIEW_SCRIPTS.add_variable(
					args.css_var,
					to
				),
			].join( ' ' ) );
		} );
	} );

	wp.customize( 'site_colors_secondary_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'site_colors_heading', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'site_colors_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	// Forms
	wp.customize( 'site_colors_forms_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'site_colors_forms_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'site_colors_forms_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );


	// Buttons
	wp.customize( 'site_colors_buttons_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, [
				IGNITION_PREVIEW_SCRIPTS.add_variable(
					args.css_var,
					to
				),
			].join( ' ' ) );
		} );
	} );

	wp.customize( 'site_colors_buttons_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'site_colors_buttons_border', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );



	//
	// Typography
	//
	wp.customize( 'site_typo_primary', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS['site_base_font_size'].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( 'site_base_font_size', IGNITION_PREVIEW_SCRIPTS.add_typography(
				IGNITION_PREVIEW_SCRIPTS.typography_control_extract_properties( to, ['size'] ),
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );

			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_secondary', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_navigation', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_page_title', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_h1', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_h2', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_h3', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_h4', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_h5', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_h6', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_widget_title', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_widget_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

	wp.customize( 'site_typo_button', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_typography(
				to,
				args.fallback_stack,
				args.breakpoints_css,
				args.breakpoint_limit
			) );
		} );
	} );

})( jQuery );
