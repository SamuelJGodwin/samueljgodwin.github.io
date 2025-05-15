<?php
/**
 * Customizer section options: Utilities - Back to Top
 *
 * @since 2.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'utilities_button_top', array(
	'title'    => esc_html_x( 'Back to Top button', 'customizer section title', 'ignition' ),
	'panel'    => 'utilities',
	'priority' => 50,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['utilities_button_top_is_enabled'];
$wp_customize->add_setting( 'utilities_button_top_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'utilities_button_top_is_enabled', $args['control_args'] );
