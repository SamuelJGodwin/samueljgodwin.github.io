<?php
/**
 * Elementor related hooks and functions
 *
 * @since 1.6.1
 */

if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
	return;
}

add_filter( 'ignition_header_classes', 'ignition_elementor_editor_header_fix' );
/**
 * Replaces the transparent header class with the normal one when the Elementor editor is active.
 *
 * @since 1.6.1
 *
 * @param array $classes
 *
 * @return array
 */
function ignition_elementor_editor_header_fix( $classes ) {
	$elementor_preview_active = \Elementor\Plugin::$instance->preview->is_preview_mode();

	if ( $elementor_preview_active ) {
		$classes = array_replace( $classes,
			array_fill_keys(
				array_keys( $classes, 'header-fixed', true ),
				'header-normal'
			)
		);
	}

	return $classes;
}
