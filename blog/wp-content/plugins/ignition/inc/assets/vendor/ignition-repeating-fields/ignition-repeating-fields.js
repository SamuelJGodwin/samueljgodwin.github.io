var triggerFieldChange = function ( $field ) {
	var $wrapper = $field.closest('.widget-content');
	$wrapper.find('input').trigger('change');
};

var ignition_repeating_sortable_init = function ( selector ) {
	if ( typeof selector === 'undefined' ) {
		jQuery( '.ignition-repeating-fields .inner' ).sortable( {
			placeholder: 'ui-state-highlight',
			stop: function(event, ui) {
				triggerFieldChange(jQuery(ui.item));
			}
		} );
	} else {
		jQuery( '.ignition-repeating-fields .inner', selector ).sortable( {
			placeholder: 'ui-state-highlight',
			stop: function(event, ui) {
				triggerFieldChange(jQuery(ui.item));
			}
		} );
	}
};

jQuery( document ).ready( function ( $ ) {
	"use strict";
	var $body = $( 'body' );

	// Repeating fields
	ignition_repeating_sortable_init();

	$body.on( 'click', '.ignition-repeating-add-field', function ( e ) {
		var repeatable_area = $( this ).siblings( '.inner' );
		var prototype       = repeatable_area.find( '.field-prototype' );
		var fields          = prototype
			.clone()
			.removeClass( 'field-prototype' )
			.removeAttr( 'style' );

		fields.find( 'input, select, textarea' ).attr( 'disabled', false );
		fields.insertBefore( prototype );

		ignition_repeating_sortable_init();
		e.preventDefault();
	} );

	$body.on( 'click', '.ignition-repeating-remove-field', function ( e ) {
		var $field = $( this ).parents( '.post-field' );
		triggerFieldChange( $field );
		$field.remove();

		e.preventDefault();
	} );
} );
