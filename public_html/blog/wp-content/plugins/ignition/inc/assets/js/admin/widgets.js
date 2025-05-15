jQuery( document ).ready( function ( $ ) {
	"use strict";

	var ignition_initialize_widget = function ( widget_el ) {
		ignition_repeating_sortable_init( widget_el );
	};

	ignition_initialize_widget();

	function ignition_on_customizer_widget_form_update( e, widget_el ) {
		ignition_initialize_widget( widget_el );
	}

	// Widget init doesn't occur for some reason, when added through the customizer. Therefore the event handler below is needed.
	// https://make.wordpress.org/core/2014/04/17/live-widget-previews-widget-management-in-the-customizer-in-wordpress-3-9/
	// 'widget-added' is complemented by 'widget-updated'. However, alpha-color-picker shows multiple alpha channel
	// pickers if called on 'widget-updated'
	$( document ).on( 'widget-updated', ignition_on_customizer_widget_form_update );
	$( document ).on( 'widget-added', ignition_on_customizer_widget_form_update );
} );
