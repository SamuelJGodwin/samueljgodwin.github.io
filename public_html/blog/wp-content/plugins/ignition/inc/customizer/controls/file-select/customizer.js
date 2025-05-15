(function ( $ ) {
	'use strict';

	wp.customize.controlConstructor[ 'ignition-file-select' ] = wp.customize.Control.extend( {
		ready: function () {
			var control = this;
			var frame;

			// File selection
			control.container.on( 'click keydown', '.upload-button', function () {
				if ( frame ) {
					frame.open();
					return;
				}

				frame = wp.media( {
					title: customizerFileSelectStrings.selectFile,
					multiple: false,
					date: false,
					library: {
						type: control.params.file_type
					},
				} );

				frame.on( 'select', function () {
					var attachment = frame.state().get( 'selection' ).first().toJSON();

					if ( attachment && attachment.type !== control.params.file_type ) {
						return;
					}

					control.setting( attachment.url )
				} );

				frame.open();
			} );
		},
	} );
}( jQuery ));
