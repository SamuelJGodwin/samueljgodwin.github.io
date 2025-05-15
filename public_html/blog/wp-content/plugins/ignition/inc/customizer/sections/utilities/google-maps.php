<?php
/**
 * Customizer section options: Utilities - Weather
 *
 * @since 2.2.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'utilities_google_maps', array(
	'title'    => esc_html_x( 'Google Maps', 'customizer section title', 'ignition' ),
	'panel'    => 'utilities',
	'priority' => 70,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

if ( current_theme_supports( 'ignition-google-maps' ) ) {
	$args = $customizer_options['utilities_google_maps_api_key'];
	$wp_customize->add_setting( 'utilities_google_maps_api_key', $args['setting_args'] );
	$wp_customize->add_control( 'utilities_google_maps_api_key', $args['control_args'] );
}
