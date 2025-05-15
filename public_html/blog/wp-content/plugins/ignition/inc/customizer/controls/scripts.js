/**
 * Customizer Controls enhancements for a better user experience.
 */

// https://make.xwp.co/2016/07/24/dependently-contextual-customizer-controls/
// https://gist.github.com/westonruter/2c1e87e381ca0c9a3dcb1e3a61a9eb4d
( function ( api ) {
	'use strict';

	//
	// WooCommerce
	//

	// // Set preview pane to cart page.
	// api.section( 'theme_woocommerce_cart', function( section ) {
	// 	section.expanded.bind( function( isExpanded ) {
	// 		if ( isExpanded ) {
	// 			wp.customize.previewer.previewUrl.set( ThemeCustomizerControls.cart_url );
	// 		}
	// 	} );
	// } );

	// When on Side Header mode, content/sidebar widths (columns) don't make very much sense in the 'boxed' layout as
	// the space is limited and/or predetermined. Therefore, only show the controls on the rest of the layouts, as they
	// are still applicable, INCLUDING the 'boxed' layout. It's just a UX enhancement, rather than a functional one.
	api( 'side_mode_site_layout_type', function( setting ) {
		var isNotLayoutBoxed, linkSettingValueToControlActiveState;

		// Determine whether the dependent control should be displayed.
		isNotLayoutBoxed = function() {
			var boxedLayouts = [
				'boxed',
			];
			return ! boxedLayouts.includes( setting.get() );
		};

		linkSettingValueToControlActiveState = function( control ) {
			var setActiveState = function() {
				control.active.set( isNotLayoutBoxed() );
			};

			control.active.validate = isNotLayoutBoxed;

			setActiveState();

			setting.bind( setActiveState );
		};

		api.control( 'site_layout_content_width', linkSettingValueToControlActiveState );
		api.control( 'site_layout_sidebar_width', linkSettingValueToControlActiveState );
	} );

	// When on Side Header mode with Fullwidth content selected 'full_fullwidth', the Site Width (px) options doesn't
	// make very much sense as it doesn't apply at all.
	api( 'side_mode_site_layout_type', function( setting ) {
		var isNotContentLayoutFull, linkSettingValueToControlActiveState;

		// Determine whether the dependent control should be displayed.
		isNotContentLayoutFull = function() {
			var fullLayouts = [
				'full_fullwidth',
			];
			return ! fullLayouts.includes( setting.get() );
		};

		linkSettingValueToControlActiveState = function( control ) {
			var setActiveState = function() {
				control.active.set( isNotContentLayoutFull() );
			};

			control.active.validate = isNotContentLayoutFull;

			setActiveState();

			setting.bind( setActiveState );
		};

		api.control( 'site_layout_container_width', linkSettingValueToControlActiveState );
	} );

}( wp.customize ) );
