(function ( $ ) {
	'use strict';

	var $controls = $( '.ignition-side-control-file-select' );

	$controls.each( function () {
		// Control initialization
		var $control = $( this );
		var $input   = $control.find( 'input[type="url"]' );

		// Control functionality
		var frame;

		// File selection
		$control.on( 'click keydown', '.upload-button', function () {
			var file_type = $( this ).data( 'type' );

			if ( frame ) {
				frame.open();
				return;
			}

			frame = wp.media( {
				title: fileSelectStrings.selectFile,
				multiple: false,
				date: false,
				library: {
					type: file_type
				},
			} );

			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();

				if ( attachment && attachment.type !== file_type ) {
					return;
				}

				$input.val( attachment.url );
			} );

			frame.open();
		} );
	} );
}( jQuery ));
