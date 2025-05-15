<?php
/**
 * Customizer section options: Site - Layout
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'site_layout', array(
	'title'    => esc_html_x( 'Layout', 'customizer section title', 'ignition' ),
	'panel'    => 'site',
	'priority' => 10,
) );


// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

if ( current_theme_supports( 'ignition-side-header' ) ) {
	$args = $customizer_options['side_mode_site_layout_type'];
	$wp_customize->add_setting( 'side_mode_site_layout_type', $args['setting_args'] );
	$wp_customize->add_control( 'side_mode_site_layout_type', $args['control_args'] );
}

$args = $customizer_options['site_layout_type'];
$wp_customize->add_setting( 'site_layout_type', $args['setting_args'] );
$wp_customize->add_control( 'site_layout_type', $args['control_args'] );

if ( count( ignition_get_blog_layout_types() ) > 1 ) {
	$args = $customizer_options['blog_archive_layout_type'];
	$wp_customize->add_setting( 'blog_archive_layout_type', $args['setting_args'] );
	$wp_customize->add_control( 'blog_archive_layout_type', $args['control_args'] );

	$args = $customizer_options['blog_single_layout_type'];
	$wp_customize->add_setting( 'blog_single_layout_type', $args['setting_args'] );
	$wp_customize->add_control( 'blog_single_layout_type', $args['control_args'] );
}

if ( current_theme_supports( 'ignition-side-header' ) ) {
	$args = $customizer_options['side_mode_site_layout_container_width'];
	$wp_customize->add_setting( 'side_mode_site_layout_container_width', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Range_Control( $wp_customize, 'side_mode_site_layout_container_width', $args['control_args'] ) );
}

$args = $customizer_options['site_layout_container_width'];
$wp_customize->add_setting( 'site_layout_container_width', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Range_Control( $wp_customize, 'site_layout_container_width', $args['control_args'] ) );

$args = $customizer_options['site_layout_content_width'];
$wp_customize->add_setting( 'site_layout_content_width', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Range_Control( $wp_customize, 'site_layout_content_width', $args['control_args'] ) );

$args = $customizer_options['site_layout_sidebar_width'];
$wp_customize->add_setting( 'site_layout_sidebar_width', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Range_Control( $wp_customize, 'site_layout_sidebar_width', $args['control_args'] ) );
