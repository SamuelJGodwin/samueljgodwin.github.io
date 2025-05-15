<?php
/**
 * Customizer section options: Site - Typography
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'site_typo', array(
	'title'    => esc_html_x( 'Typography', 'customizer section title', 'ignition' ),
	'panel'    => 'site',
	'priority' => 30,
) );


// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['site_typo_disable_google_fonts'];
$wp_customize->add_setting( 'site_typo_disable_google_fonts', $args['setting_args'] );
$wp_customize->add_control( 'site_typo_disable_google_fonts', $args['control_args'] );

$args = $customizer_options['site_typo_primary'];
$wp_customize->add_setting( 'site_typo_primary', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_primary', $args['control_args'] ) );

$args = $customizer_options['site_typo_secondary'];
$wp_customize->add_setting( 'site_typo_secondary', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_secondary', $args['control_args'] ) );

if ( get_theme_support( 'ignition-typography-navigation' ) ) {
	$args = $customizer_options['site_typo_navigation'];
	$wp_customize->add_setting( 'site_typo_navigation', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_navigation', $args['control_args'] ) );
}

if ( get_theme_support( 'ignition-typography-page-title' ) ) {
	$args = $customizer_options['site_typo_page_title'];
	$wp_customize->add_setting( 'site_typo_page_title', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_page_title', $args['control_args'] ) );
}

$args = $customizer_options['site_typo_h1'];
$wp_customize->add_setting( 'site_typo_h1', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_h1', $args['control_args'] ) );

$args = $customizer_options['site_typo_h2'];
$wp_customize->add_setting( 'site_typo_h2', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_h2', $args['control_args'] ) );

$args = $customizer_options['site_typo_h3'];
$wp_customize->add_setting( 'site_typo_h3', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_h3', $args['control_args'] ) );

$args = $customizer_options['site_typo_h4'];
$wp_customize->add_setting( 'site_typo_h4', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_h4', $args['control_args'] ) );

$args = $customizer_options['site_typo_h5'];
$wp_customize->add_setting( 'site_typo_h5', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_h5', $args['control_args'] ) );

$args = $customizer_options['site_typo_h6'];
$wp_customize->add_setting( 'site_typo_h6', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_h6', $args['control_args'] ) );

$args = $customizer_options['site_typo_widget_title'];
$wp_customize->add_setting( 'site_typo_widget_title', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_widget_title', $args['control_args'] ) );

$args = $customizer_options['site_typo_widget_text'];
$wp_customize->add_setting( 'site_typo_widget_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_widget_text', $args['control_args'] ) );

if ( get_theme_support( 'ignition-typography-button' ) ) {
	$args = $customizer_options['site_typo_button'];
	$wp_customize->add_setting( 'site_typo_button', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Typography_Control( $wp_customize, 'site_typo_button', $args['control_args'] ) );
}
