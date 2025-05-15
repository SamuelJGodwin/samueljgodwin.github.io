(function ( $ ) {
	'use strict';

	var $controls = $( '.ignition-side-image-control' );

	$controls.each( function () {
		// Control initialization
		var $control   = $( this );
		var attachment = $control.data( 'attachment' );

		renderAttachmentView( $control, attachment );

		// Control functionality
		var frame;

		// Image selection
		$control.on( 'click keydown', '.upload-button, .ignition-side-image-control-thumbnail, .ignition-side-image-control-placeholder', function () {
			if ( frame ) {
				frame.open();
				return;
			}

			frame = wp.media( {
				title: sideImageBgStrings.selectImage,
				multiple: false,
				date: false,
			} );

			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();

				if ( attachment && attachment.type !== 'image' ) {
					return;
				}

				saveValue( $control, {
					image_id: attachment && attachment.id,
					image_url: attachment && attachment.url
				} );

				renderAttachmentView( $control, attachment );
			} );

			frame.open();
		} );

		// Image removal
		$control.on( 'click keydown', '.remove-button', function () {
			saveValue( $control, {
				image_id: '',
				image_url: ''
			} );
			renderAttachmentView( $control, false );
		} );

		// Select controls (repeat, position, etc)
		$control.on( 'change', 'select, input[type="number"]', function () {
			var $this   = $( this );
			var setting = {};

			setting[ $this.data( 'property' ) ] = $this.val();

			saveValue( $control, setting );
		} );

		// Checkboxes
		$control.on( 'change', 'input[type="checkbox"]', function () {
			var $this   = $( this );
			var setting = {};

			setting[ $this.data( 'property' ) ] = $this.prop( 'checked' );

			saveValue( $control, setting );
		} );
	} );

	/**
	 * Determines the attachment DOM elements' visibility.
	 *
	 * @param {jquery} $control - The control's jQuery object.
	 * @param {Object} attachment - The attachment.
	 */
	function renderAttachmentView( $control, attachment ) {
		var $attachmentPlaceholder = $control.find( '.ignition-side-image-control-attachment-placeholder' );
		var $attachmentPreview     = $control.find( '.ignition-side-image-control-attachment-preview' );

		if ( attachment ) {
			$attachmentPlaceholder.hide();
			$attachmentPreview.show();

			var src = (attachment.sizes.medium && attachment.sizes.medium.url) || attachment.url;
			$attachmentPreview.find( '.ignition-side-image-control-attachment-thumb' ).attr( 'src', src );
		} else {
			$attachmentPlaceholder.show();
			$attachmentPreview.hide();
		}
	}

	/**
	 * Saves the value.
	 *
	 * @param {jquery} $control - The control's jQuery object.
	 * @param {Object} values - The values.
	 */
	function saveValue( $control, values ) {
		var $input       = $control.find( '.ignition-side-image-control-hidden-value' );
		var currentValue = JSON.parse( $input.val() );
		var value        = _.extend( {}, currentValue, values );

		$input.val( JSON.stringify( value ) )
	}
}( jQuery ));
