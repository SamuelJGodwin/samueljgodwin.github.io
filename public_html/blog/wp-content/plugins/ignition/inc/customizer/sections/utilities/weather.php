<?php
/**
 * Customizer section options: Utilities - Weather
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'utilities_weather', array(
	'title'       => esc_html_x( 'Weather', 'customizer section title', 'ignition' ),
	'description' => wp_kses(
		sprintf(
			/* translators: %1$s and %2$s are code fragments, e.g.: <code>[shortcode]</code> */
			__( 'The following settings set the defaults that the weather shortcode will use, but they may be overridden later. You can display the current weather conditions with the shortcode %1$s, and optionally provide a different location ID, e.g. %2$s', 'ignition' ),
			'<code>[ignition-current-weather]</code>',
			'<code>[ignition-current-weather id="734880"]</code>'
		),
		ignition_get_allowed_tags( 'guide' )
	),
	'panel'       => 'utilities',
	'priority'    => 10,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['utilities_openweathermap_api_key'];
$wp_customize->add_setting( 'utilities_openweathermap_api_key', $args['setting_args'] );
$wp_customize->add_control( 'utilities_openweathermap_api_key', $args['control_args'] );

$args = $customizer_options['utilities_openweathermap_location_id'];
$wp_customize->add_setting( 'utilities_openweathermap_location_id', $args['setting_args'] );
$wp_customize->add_control( 'utilities_openweathermap_location_id', $args['control_args'] );

$args = $customizer_options['utilities_openweathermap_units'];
$wp_customize->add_setting( 'utilities_openweathermap_units', $args['setting_args'] );
$wp_customize->add_control( 'utilities_openweathermap_units', $args['control_args'] );
