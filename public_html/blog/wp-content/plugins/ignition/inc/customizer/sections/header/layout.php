<?php
/**
 * Customizer section options: Header - Layout
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'header_layout', array(
	'title'    => esc_html_x( 'Layout', 'customizer section title', 'ignition' ),
	'panel'    => 'header',
	'priority' => 10,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['header_layout_type'];
if ( count( $args['control_args']['choices'] ) > 1 ) {
	$wp_customize->add_setting( 'header_layout_type', $args['setting_args'] );
	$wp_customize->add_control( 'header_layout_type', $args['control_args'] );
}

$args = $customizer_options['header_layout_is_full_width'];
if ( ! $args['disabled'] && $args['output_args']['control'] ) {
	$wp_customize->add_setting( 'header_layout_is_full_width', $args['setting_args'] );
	$wp_customize->add_control( 'header_layout_is_full_width', $args['control_args'] );
}

$args = $customizer_options['header_layout_menu_type'];
if ( count( $args['control_args']['choices'] ) > 1 ) {
	$wp_customize->add_setting( 'header_layout_menu_type', $args['setting_args'] );
	$wp_customize->add_control( 'header_layout_menu_type', $args['control_args'] );
}

$args = $customizer_options['header_layout_menu_sticky_type'];
$wp_customize->add_setting( 'header_layout_menu_sticky_type', $args['setting_args'] );
$wp_customize->add_control( 'header_layout_menu_sticky_type', $args['control_args'] );

if ( current_theme_supports( 'ignition-side-header' ) ) {
	$args = $customizer_options['side_mode_header_layout_is_sticky'];
	$wp_customize->add_setting( 'side_mode_header_layout_is_sticky', $args['setting_args'] );
	$wp_customize->add_control( 'side_mode_header_layout_is_sticky', $args['control_args'] );
}

$args = $customizer_options['header_layout_menu_mobile_slide_right'];
$wp_customize->add_setting( 'header_layout_menu_mobile_slide_right', $args['setting_args'] );
$wp_customize->add_control( 'header_layout_menu_mobile_slide_right', $args['control_args'] );


$args = $customizer_options['header_layout_menu_mobile_breakpoint'];
$wp_customize->add_setting( 'header_layout_menu_mobile_breakpoint', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Range_Control( $wp_customize, 'header_layout_menu_mobile_breakpoint', $args['control_args'] ) );
