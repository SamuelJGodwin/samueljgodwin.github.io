<?php
/**
 * Customizer partial callbacks
 *
 * @since 1.0.0
 */

/**
 * Renders the header section for customizer partials preview.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Partial $_this
 * @param array $container_context
 */
function ignition_customize_preview_header( $_this, $container_context ) {
	global $wp_filter;

	$before = isset( $wp_filter['ignition_before_header'] ) ? $wp_filter['ignition_before_header'] : null;
	$after  = isset( $wp_filter['ignition_after_header'] ) ? $wp_filter['ignition_after_header'] : null;

	if ( $before ) {
		unset( $wp_filter['ignition_before_header'] );
	}

	if ( $after ) {
		unset( $wp_filter['ignition_after_header'] );
	}

	ignition_header();

	if ( $before ) {
		$wp_filter['ignition_before_header'] = $before;
	}

	if ( $after ) {
		$wp_filter['ignition_after_header'] = $after;
	}
}

/**
 * Renders the footer section for customizer partials preview.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Partial $_this
 * @param array $container_context
 */
function ignition_customize_preview_footer( $_this, $container_context ) {
	/**
	 * Hook: ignition_footer.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_footer' );
}
