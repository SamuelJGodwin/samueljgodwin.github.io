<?php
/**
 * Customizer section options: Utilities - Lightbox
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'utilities_lightbox', array(
	'title'    => esc_html_x( 'Lightbox', 'customizer section title', 'ignition' ),
	'panel'    => 'utilities',
	'priority' => 20,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['utilities_lightbox_is_enabled'];
$wp_customize->add_setting( 'utilities_lightbox_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'utilities_lightbox_is_enabled', $args['control_args'] );
