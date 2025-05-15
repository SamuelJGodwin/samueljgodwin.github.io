(function ( $ ) {
	'use strict';

	wp.customize.controlConstructor[ 'ignition-image-bg' ] = wp.customize.Control.extend( {
		ready: function () {
			var control = this;
			var frame;

			// Initialize
			control.renderAttachmentView( control.params.attachment );

			// Image selection
			control.container.on( 'click keydown', '.upload-button, .ignition-image-bg-control-thumbnail, .ignition-image-bg-control-placeholder', function () {
				if ( frame ) {
					frame.open();
					return;
				}

				frame = wp.media( {
					title: customizerImageBgStrings.selectImage,
					multiple: false,
					date: false,
				} );

				frame.on( 'select', function () {
					var attachment = frame.state().get( 'selection' ).first().toJSON();

					if ( attachment && attachment.type !== 'image' ) {
						return;
					}

					control.saveValue( {
						image_id: attachment && attachment.id,
						image_url: attachment && attachment.url
					} );

					control.renderAttachmentView( attachment );
				} );

				frame.open();
			} );

			// Image removal
			control.container.on( 'click keydown', '.remove-button', function () {
				control.saveValue( {
					image_id: '',
					image_url: ''
				} );
				control.renderAttachmentView( false );
			} );

			// Select controls (repeat, position, etc)
			control.container.on( 'change', 'select, input[type="number"]', function () {
				var $this                           = $( this );
				var setting                         = {};
				setting[ $this.data( 'property' ) ] = $this.val();

				control.saveValue( setting );
			} );

			// Checkboxes
			control.container.on( 'change', 'input[type="checkbox"]', function () {
				var $this                           = $( this );
				var setting                         = {};
				setting[ $this.data( 'property' ) ] = $this.prop( 'checked' );

				control.saveValue( setting );
			} );
		},

		/**
		 * Determines the attachment DOM elements' visibility.
		 *
		 * @param {Object} attachment - The attachment.
		 */
		renderAttachmentView: function ( attachment ) {
			var control                = this;
			var $attachmentPlaceholder = control.container.find( '.ignition-image-bg-control-attachment-placeholder' );
			var $attachmentPreview     = control.container.find( '.ignition-image-bg-control-attachment-preview' );

			if ( attachment ) {
				$attachmentPlaceholder.hide();
				$attachmentPreview.show();

				var src = (attachment.sizes.medium && attachment.sizes.medium.url) || attachment.url;
				$attachmentPreview.find( '.ignition-image-bg-control-attachment-thumb' ).attr( 'src', src );
			} else {
				$attachmentPlaceholder.show();
				$attachmentPreview.hide();
			}
		},

		/**
		 * Saves the value.
		 */
		saveValue: function ( values ) {
			var control = this;
			var val     = _.extend( {}, control.setting.get(), values );

			control.setting( val );
		}
	} );
}( jQuery ));
