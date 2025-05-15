<?php
/**
 * Customizer section options: Header - Mobile navigation colors
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'header_mobile_nav_colors', array(
	'title'    => esc_html_x( 'Mobile Nav Colors', 'customizer section title', 'ignition' ),
	'panel'    => 'header',
	'priority' => 35,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

//
// Mobile nav colors
//
$args = $customizer_options['header_mobile_nav_colors_background'];
$wp_customize->add_setting( 'header_mobile_nav_colors_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_mobile_nav_colors_background', $args['control_args'] ) );

$args = $customizer_options['header_mobile_nav_colors_link'];
$wp_customize->add_setting( 'header_mobile_nav_colors_link', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_mobile_nav_colors_link', $args['control_args'] ) );

$args = $customizer_options['header_mobile_nav_colors_border'];
$wp_customize->add_setting( 'header_mobile_nav_colors_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_mobile_nav_colors_border', $args['control_args'] ) );
