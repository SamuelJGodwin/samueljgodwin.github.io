jQuery( function ( $ ) {
	$( '.wsrw-close-modal, .wsrw-modal-overlay' ).on(
		'click',
		function () {
			const $body = $( 'body' );
			// Let's disable closing the modal if a specific class is used.
			if ( $body.hasClass( 'wsrw-no-close' ) ) {
				return;
			}
			$body.removeClass( 'wsrw-show-modal' );

			$(document).trigger( 'wsrw-modal-closed' );
		}
	);
	$( '.wsrw-search-replace-history .wsrw-close-modal,.wsrw-search-replace-history .wsrw-modal-overlay, .wsrw-search-replace-unused_media_backups .wsrw-close-modal,.wsrw-search-replace-unused_media_backups .wsrw-modal-overlay' ).on(
		'click',
		function () {

			$( '.wsrw-modal' ).hide();
			$( '.wsrw-modal-overlay' ).hide();
			$( '.wsrw-undo-operation' ).prop( 'disabled', false );
			$( '#wsrw-results-table' ).find('tr:not(:first-child)').remove();
		}
	);
} );
