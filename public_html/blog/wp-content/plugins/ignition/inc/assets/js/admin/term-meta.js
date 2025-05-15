jQuery( document ).ready( function ( $ ) {
	var $form = $( 'form.ignition-taxonomy-meta' );
	$form.on( 'submit', function ( e ) {
		e.preventDefault();

		$.post( ajaxurl, $form.serialize(), function ( response ) {
			$form.find( '.taxonomy-meta-saved' ).show().delay( 3000 ).fadeOut();
		}, 'json' );
	} );
} );
