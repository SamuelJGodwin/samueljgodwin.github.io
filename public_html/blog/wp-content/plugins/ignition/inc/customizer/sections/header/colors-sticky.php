<?php
/**
 * Customizer section options: Header - Sticky colors
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'header_sticky_colors', array(
	'title'    => esc_html_x( 'Sticky Colors', 'customizer section title', 'ignition' ),
	'panel'    => 'header',
	'priority' => 35,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['header_sticky_colors_background'];
$wp_customize->add_setting( 'header_sticky_colors_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_background', $args['control_args'] ) );

$args = $customizer_options['header_sticky_colors_background_image'];
$wp_customize->add_setting( 'header_sticky_colors_background_image', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Image_BG_Control( $wp_customize, 'header_sticky_colors_background_image', $args['control_args'] ) );


$args = $customizer_options['header_sticky_colors_overlay'];
$wp_customize->add_setting( 'header_sticky_colors_overlay', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_overlay', $args['control_args'] ) );

$args = $customizer_options['header_sticky_colors_text'];
$wp_customize->add_setting( 'header_sticky_colors_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_text', $args['control_args'] ) );

$args = $customizer_options['header_sticky_colors_border'];
$wp_customize->add_setting( 'header_sticky_colors_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_border', $args['control_args'] ) );

$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'header_sticky_colors_submenu_sub', array(
	'section'  => 'header_sticky_colors',
	'settings' => array(),
	'label'    => esc_html__( '▸ Submenu colors', 'ignition' ),
) ) );

$args = $customizer_options['header_sticky_colors_submenu_background'];
$wp_customize->add_setting( 'header_sticky_colors_submenu_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_submenu_background', $args['control_args'] ) );

$args = $customizer_options['header_sticky_colors_submenu_background_hover'];
$wp_customize->add_setting( 'header_sticky_colors_submenu_background_hover', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_submenu_background_hover', $args['control_args'] ) );

$args = $customizer_options['header_sticky_colors_submenu_text'];
$wp_customize->add_setting( 'header_sticky_colors_submenu_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_submenu_text', $args['control_args'] ) );

$args = $customizer_options['header_sticky_colors_submenu_text_hover'];
$wp_customize->add_setting( 'header_sticky_colors_submenu_text_hover', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'header_sticky_colors_submenu_text_hover', $args['control_args'] ) );
