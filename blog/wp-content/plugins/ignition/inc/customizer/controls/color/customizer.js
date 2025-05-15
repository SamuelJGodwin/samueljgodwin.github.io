jQuery( window ).on( 'load', function () {
	jQuery( 'html' ).addClass( 'colorpicker-ready' );
} );

(function ( $ ) {
	wp.customize.controlConstructor[ 'ignition-color' ] = wp.customize.Control.extend( {
		ready: function () {
			var control = this;

			this.container.find( '.ignition-color-picker-alpha' ).wpColorPicker( {
				palettes: false,
				/**
				 * @param {Event} event - standard jQuery event, produced by whichever
				 * control was changed.
				 * @param {Object} ui - standard jQuery UI object, with a color member
				 * containing a Color.js object.
				 */
				change: function ( event, ui ) {
					var element = event.target;
					var color   = ui.color.toString();

					if ( $( 'html' ).hasClass( 'colorpicker-ready' ) ) {
						control.setting.set( color );
					}
				},

				/**
				 * @param {Event} event - standard jQuery event, produced by "Clear"
				 * button.
				 */
				clear: function ( event ) {
					var element = $( event.target )
						.closest( '.wp-picker-input-wrap' )
						.find( '.wp-color-picker' )[ 0 ];
					var color   = '';

					if ( element ) {
						control.setting.set( color );
					}
				}
			} );
		}
	} );
}( jQuery ));
