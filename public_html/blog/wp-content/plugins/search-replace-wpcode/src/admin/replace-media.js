// Let's listen for the submit event on #wsrw-media-replace-form and then send the form including the file to the api endpoint we have for replacing the image.
// When the image is selected we should attempt to show a preview of the image.
jQuery( document ).ready( function ( $ ) {

	jconfirm.defaults = {
		closeIcon: true,
		closeIconClass: 'close-icon-svg',
		backgroundDismiss: false,
		escapeKey: true,
		animationBounce: 1,
		useBootstrap: false,
		theme: 'modern',
		boxWidth: '400px',
		type: 'blue',
		animateFromElement: false,
	};

	const app = {
		init() {
			app.find_elements();
			app.placeholder_height();
			app.init_form();
			app.placeholder_click();
			app.drag_and_drop(); // Added this line
			app.clear_click();
		},
		find_elements() {
			app.form = $( '#wsrw-media-replace-form' );
			app.file_input = $( '#wsrw-media-file' );
			app.preview = $( '#wsrw-media-preview' );
			app.media_id = $( '#wsrw-media-id' );
			app.placeholder = $( '#wsrw-media-preview-placeholder' );
			app.input_placeholder = $('.wsrw-file-field');
			app.current_image = $( '#wsrw-media-current-image img' );
			app.replace_buton = $( '#wsrw-start-replace' );
			app.clear_button = $( '#wsrw-clear-form' );
			app.results = $( '#wsrw-media-results' );
		},
		placeholder_height() {
			app.placeholder.height( app.current_image.height() );
		},
		placeholder_click() {
			app.placeholder.on(
				'click',
				function () {
					app.file_input.click();
				}
			);
		},
		clear_click() {
			app.clear_button.on(
				'click',
				function () {
					app.reset_form();
				}
			);
		},
		reset_form() {
			app.file_input.val( '' );
			app.show_preview();
		},
		init_form() {
			app.form.on(
				'submit',
				function ( e ) {
					e.preventDefault();
					$.confirm( {
						title: wsrwjs.are_you_sure,
						content: wsrwjs.replace_media_confirm,
						type: 'blue',
						icon: 'fa fa-exclamation-circle',
						animateFromElement: false,
						buttons: {
							confirm: {
								text: wsrwjs.yes,
								btnClass: 'btn-confirm',
								keys: ['enter'],
							},
							cancel: {
								text: wsrwjs.no,
								btnClass: 'btn-cancel',
								keys: ['esc'],
							},
						},
						onAction: function ( action ) {
							if ( action === 'confirm' ) {
								app.start_media_replace();
							}
						},
					} );
				}
			);
			app.file_input.on(
				'change',
				function ( e ) {
					app.show_preview();
				}
			);
		},
		start_media_replace() {
			const data = new FormData();
			data.append( '_wpnonce', wsrwjs.rest_nonce );
			data.append( 'file', app.file_input[0].files[0] );
			data.append( 'media_id', app.media_id.val() );

			$.ajax(
				{
					url: wsrwjs.upload_url,
					type: 'POST',
					data: data,
					contentType: false,
					processData: false,
					beforeSend: function () {
						WSRSpinner.show_button_spinner( app.replace_buton );
					},
					success: function ( response ) {
						WSRSpinner.hide_button_spinner( app.replace_buton );
						app.show_results( response );
					},
					error: function ( response ) {
						WSRSpinner.show_button_spinner( app.replace_buton );
						app.show_results( response );
					}
				}
			);
		},
		show_preview() {
			const file = app.file_input[0].files[0];
			if ( file ) {
				const reader = new FileReader();
				// Is the file an image or something else?
				const isimage = file.type.match( 'image' );
				reader.onload = function ( e ) {
					if ( isimage ) {
						app.image_preview( e.target.result );
					} else {
						app.file_preview( file.name );
					}
				};
				app.input_placeholder.text(file.name);
				reader.readAsDataURL( file );
				app.replace_buton.prop( 'disabled', false );
				app.clear_button.prop( 'disabled', false );
			} else {
				app.placeholder.show();
				app.replace_buton.prop( 'disabled', true );
				app.clear_button.prop( 'disabled', true );
				app.preview.html( '' );
			}
		},
		file_preview( filename ) {
			app.placeholder.hide();
			const file_html = '<div class="wsrw-media-placeholder"><img src="https://find-replace.site/wp-includes/images/media/document.png" alt="stock-photo-3"><span class="wsrw-media-placeholder-text">' + filename + '</span></div>';
			app.preview.html( file_html );
		},
		image_preview( img_url ) {
			const new_image = $( '<img>' );
			new_image.attr( 'src', img_url );
			app.placeholder.hide();
			app.preview.html( new_image );
		},
		show_results( response ) {

			$.confirm(
				{
					title: wsrwjs.image_replace_complete,
					content: response.message,
					animateFromElement: false,
					buttons: {
						confirm: {
							text: wsrwjs.ok,
							btnClass: 'btn-confirm',
							keys: ['enter'],
						},
					}
				}
			);

			if ( response.success ) {
				if( response.image_url ) {
					app.current_image.attr( 'src', response.image_url );
					app.current_image.removeAttr( 'srcset' );
					app.current_image.on( 'load', app.placeholder_height );
				} else {

				}
				app.reset_form();
			}
		},
		drag_and_drop() {
			const dropZone = app.placeholder.add(app.preview);

			dropZone.on('dragover', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$(this).addClass('dragover');
				e.originalEvent.dataTransfer.dropEffect = 'copy';
			});

			dropZone.on('dragleave', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$(this).removeClass('dragover');
			});

			dropZone.on('drop', function(e) {
				e.preventDefault();
				e.stopPropagation();
				$(this).removeClass('dragover');

				const files = e.originalEvent.dataTransfer.files;
				if ( files.length > 0 ) {
					const dt = new DataTransfer();
					dt.items.add( files[0] ); // Only take the first file
					app.file_input[0].files = dt.files;
					app.show_preview();

					app.replace_buton.prop( 'disabled', false );
					app.clear_button.prop( 'disabled', false );
				}
			});
		}
	};

	app.init();
} );