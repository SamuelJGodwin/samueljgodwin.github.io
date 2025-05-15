(function ( $ ) {
	'use strict';

	wp.customize.controlConstructor[ 'ignition-range' ] = wp.customize.Control.extend( {
		ready: function () {
			var control = this;

			control.container.on( 'change keyup', 'input[type="number"], input[type="range"]', function () {
				var $wrapper   = $( this ).parents( '.ignition-control-range' );
				var value      = $( this ).val();
				var breakpoint = $wrapper.data( 'breakpoint' );

				// Sync the controls
				if ( $( this ).attr( 'type' ) === 'range' ) {
					$wrapper.find( 'input[type="number"]' ).val( value );
				} else {
					$wrapper.find( 'input[type="range"]' ).val( value );
				}

				control.saveValue( breakpoint, value );
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
			var sections = control.container.find( '.ignition-control-range' );
			var $button  = $buttons.filter( '[data-device="' + device + '"]' );
			var index    = $button.index();

			$buttons.removeClass( 'active' );
			$button.addClass( 'active' );
			sections.hide().eq( index ).css( 'display', 'flex' );
		},

		/**
		 * Saves the value.
		 */
		saveValue: function ( breakpoint, value ) {
			var control = this;
			var $input  = control.container.find( '.ignition-range-control-hidden-value' );
			var val     = control.setting.get();

			val[ breakpoint ] = value;

			$( $input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );
			control.setting.set( val );
		}

	} );
}( jQuery ));
