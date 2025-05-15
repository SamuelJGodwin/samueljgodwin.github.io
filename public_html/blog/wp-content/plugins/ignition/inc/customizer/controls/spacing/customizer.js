(function ( $ ) {
	'use strict';

	wp.customize.controlConstructor[ 'ignition-spacing' ] = wp.customize.Control.extend( {
		ready: function () {
			var control = this;

			control.container.find( '.button-link-spacing' ).on( 'click', function () {
				var breakpoint = $( this ).data( 'breakpoint' );
				var $button    = control.container.find( '.button-link-spacing' );

				$button.toggleClass( 'button-primary' );
				control.saveValue( breakpoint, 'linked', $button.hasClass( 'button-primary' ) );
			} );

			control.container.on( 'change keyup mouseup', 'input[type="number"]', function () {
				var $wrapper = $( this ).parents( '.ignition-spacing-control-group-wrap' );
				if ( $wrapper.find( '.button-link-spacing' ).hasClass( 'button-primary' ) ) {
					var value = $( this ).val();

					$wrapper.find( 'input:visible' ).val( value );
				}

				$wrapper.find( 'input' ).each( function () {
					var breakpoint = $( this ).data( 'breakpoint' );
					var property   = $( this ).data( 'property' );

					control.saveValue( breakpoint, property, $( this ).val() );
				} );
			} );

			//
			// Responsive Controls
			//
			control.container.on( 'click', '.button-group-devices button', function () {
				var index  = $( this ).index();
				var device = $( this ).data( 'device' );

				control.displayDeviceControls( device );

				// Trigger the Customizer's responsive controls
				$( '.wp-full-overlay-footer .devices button' ).eq( index ).trigger( 'click' );
				$( 'body' ).trigger( 'on-responsive-mode-change', [ device, control ] );
			} );

			$( 'body' ).on( 'on-responsive-mode-change', function ( event, device, ref ) {
				if ( control !== ref ) {
					control.displayDeviceControls( device );
				}
			} );
		},

		displayDeviceControls: function ( device ) {
			var control  = this;
			var $buttons = control.container.find( '.button-group-devices button' );
			var sections = control.container.find( '.ignition-spacing-control-group-wrap' );
			var $button  = $buttons.filter( '[data-device="' + device + '"]' );
			var index    = $button.index();

			$buttons.removeClass( 'active' );
			$button.addClass( 'active' );
			sections.hide().eq( index ).css( 'display', 'flex' );
		},

		/**
		 * Saves the value.
		 */
		saveValue: function ( breakpoint, property, value ) {
			var control = this;
			var $input  = control.container.find( '.ignition-spacing-control-hidden-value' );
			var val     = control.setting.get();

			val[ breakpoint ][ property ] = value;

			$input.val( JSON.stringify( val ) ).trigger( 'change' );
			control.setting.set( val );
		}

	} );
}( jQuery ));
