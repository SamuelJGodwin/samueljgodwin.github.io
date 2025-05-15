<?php
/**
 * Customizer section options: Site - Colors
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'site_colors', array(
	'title'    => esc_html_x( 'Colors', 'customizer section title', 'ignition' ),
	'panel'    => 'site',
	'priority' => 20,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['site_colors_body_background'];
if ( ! $args['disabled'] && $args['output_args']['control'] ) {
	$wp_customize->add_setting( 'site_colors_body_background', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_body_background', $args['control_args'] ) );
}

$args = $customizer_options['site_colors_body_background_image'];
if ( ! $args['disabled'] && $args['output_args']['control'] ) {
	$wp_customize->add_setting( 'site_colors_body_background_image', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Image_BG_Control( $wp_customize, 'site_colors_body_background_image', $args['control_args'] ) );
}

$args = $customizer_options['site_colors_primary'];
$wp_customize->add_setting( 'site_colors_primary', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_primary', $args['control_args'] ) );

if ( get_theme_support( 'ignition-site-colors-secondary' ) ) {
	$args = $customizer_options['site_colors_secondary'];
	$wp_customize->add_setting( 'site_colors_secondary', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_secondary', $args['control_args'] ) );
}

$args = $customizer_options['site_colors_text'];
$wp_customize->add_setting( 'site_colors_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_text', $args['control_args'] ) );

$args = $customizer_options['site_colors_secondary_text'];
$wp_customize->add_setting( 'site_colors_secondary_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_secondary_text', $args['control_args'] ) );

$args = $customizer_options['site_colors_heading'];
$wp_customize->add_setting( 'site_colors_heading', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_heading', $args['control_args'] ) );

$args = $customizer_options['site_colors_border'];
$wp_customize->add_setting( 'site_colors_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_border', $args['control_args'] ) );


$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'site_colors_forms_sub', array(
	'section'  => 'site_colors',
	'settings' => array(),
	'label'    => esc_html__( '▸ Forms', 'ignition' ),
) ) );

$args = $customizer_options['site_colors_forms_background'];
$wp_customize->add_setting( 'site_colors_forms_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_forms_background', $args['control_args'] ) );

$args = $customizer_options['site_colors_forms_border'];
$wp_customize->add_setting( 'site_colors_forms_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_forms_border', $args['control_args'] ) );

$args = $customizer_options['site_colors_forms_text'];
$wp_customize->add_setting( 'site_colors_forms_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_forms_text', $args['control_args'] ) );



$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'site_colors_buttons_sub', array(
	'section'  => 'site_colors',
	'settings' => array(),
	'label'    => esc_html__( '▸ Buttons', 'ignition' ),
) ) );

$args = $customizer_options['site_colors_buttons_background'];
$wp_customize->add_setting( 'site_colors_buttons_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_buttons_background', $args['control_args'] ) );

$args = $customizer_options['site_colors_buttons_text'];
$wp_customize->add_setting( 'site_colors_buttons_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_buttons_text', $args['control_args'] ) );

$args = $customizer_options['site_colors_buttons_border'];
$wp_customize->add_setting( 'site_colors_buttons_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'site_colors_buttons_border', $args['control_args'] ) );
