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
	wp.customize( 'page_title_with_background_is_visible', function ( value ) {
		var initialValue = $body.hasClass( 'ignition-page-title-bg-on' );

		var $hero       = $( '.page-hero' );
		var $page_title = $( '.page-title-wrap' );

		var toggleElements = function ( option_value ) {
			if ( option_value ) {
				$hero.show();
				$page_title.hide();
				$body
					.removeClass('ignition-page-title-bg-off')
					.addClass('ignition-page-title-bg-on');
			} else {
				$hero.hide();
				$page_title.show();
				$body
					.removeClass('ignition-page-title-bg-on')
					.addClass('ignition-page-title-bg-off');
			}
		};

		toggleElements( initialValue );

		value.bind( toggleElements );
	} );

	wp.customize( 'normal_page_title_title_is_visible', function ( value ) {
		var initialValue = $body.hasClass( 'ignition-page-title-normal-on' );

		var $title = $( '.page-title' );
		var $subtitle = $( '.page-subtitle' );
		var $page_title = $( '.page-title-wrap' );

		var toggleElements = function ( option_value ) {
			var initialHeroBackgroundValue = $body.hasClass( 'ignition-page-title-bg-on' );

			if ( option_value ) {
				$title.show();

				if ( !initialHeroBackgroundValue ) {
					$page_title.show();
				}

				$body
					.removeClass('ignition-page-title-normal-off')
					.addClass('ignition-page-title-normal-on');
			} else {
				$title.hide();

				if ( !$subtitle.is(':visible') ) {
					$page_title.hide();
				}

				$body
					.removeClass('ignition-page-title-normal-on')
					.addClass('ignition-page-title-normal-off');
			}
		};

		toggleElements( initialValue );

		value.bind( toggleElements );
	} );

	wp.customize( 'normal_page_title_subtitle_is_visible', function ( value ) {
		var initialValue = $body.hasClass( 'ignition-page-title-subtitle-on' );

		var $title = $( '.page-title' );
		var $subtitle = $( '.page-subtitle' );
		var $page_title = $( '.page-title-wrap' );

		var toggleElements = function ( option_value ) {
			var initialHeroBackgroundValue = $body.hasClass( 'ignition-page-title-bg-on' );

			if ( option_value ) {
				$subtitle.show();

				if ( !initialHeroBackgroundValue ) {
					$page_title.show();
				}

				$body
					.removeClass('ignition-page-title-subtitle-off')
					.addClass('ignition-page-title-subtitle-on');
			} else {
				$subtitle.hide();

				if ( !$title.is(':visible') ) {
					$page_title.hide();
				}

				$body
					.removeClass('ignition-page-title-subtitle-on')
					.addClass('ignition-page-title-subtitle-off');
			}
		};

		toggleElements( initialValue );

		value.bind( toggleElements );
	} );

	wp.customize( 'breadcrumb_is_visible', function ( value ) {
		var initialValue = $body.hasClass( 'ignition-page-breadcrumb-on' );

		var $breadcrumb = $( '.page-breadcrumb' );

		var toggleElements = function ( option_value ) {
			if ( option_value ) {
				$breadcrumb.show();
			} else {
				$breadcrumb.hide();
			}
		};

		toggleElements( initialValue );

		value.bind( toggleElements );
	} );

	wp.customize( 'page_title_with_background_height', function ( value ) {
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

	wp.customize( 'page_title_with_background_text_align_horizontal', function ( value ) {
		value.bind( function ( to ) {
			var $hero   = $( '.page-hero' );
			var $pageTitle = $( '.page-title-wrap' );
			var classes = [
				'page-hero-align-left',
				'page-hero-align-center',
				'page-hero-align-right',
				'page-title-align-left',
				'page-title-align-center',
				'page-title-align-right',
			];
			classes.forEach( function ( className ) {
				$hero.removeClass( className );
				$pageTitle.removeClass( className );
			} );

			$hero.addClass( 'page-hero-align-' + to );
			$pageTitle.addClass( 'page-title-align-' + to );
		} );
	} );


	//
	// Colors
	//
	wp.customize( 'page_title_colors_background', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'page_title_colors_background_image', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_image_background_by_id(
				to,
				args.image_size,
				args.css
			) );
		} );
	} );

	wp.customize( 'page_title_colors_overlay', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'page_title_colors_primary_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

	wp.customize( 'page_title_colors_secondary_text', function ( value ) {
		value.bind( function ( to ) {
			var args = IGNITION_OPTIONS[this.id].render_args;
			IGNITION_PREVIEW_SCRIPTS.injectStylesheet( this.id, IGNITION_PREVIEW_SCRIPTS.add_variable(
				args.css_var,
				to
			) );
		} );
	} );

})( jQuery );
