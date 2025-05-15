<?php
/**
 * Customizer section options: Header - Content
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'header_content', array(
	'title'    => esc_html_x( 'Content', 'customizer section title', 'ignition' ),
	'panel'    => 'header',
	'priority' => 20,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['header_content_area'];
$wp_customize->add_setting( 'header_content_area', $args['setting_args'] );
$wp_customize->add_control( 'header_content_area', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'header_layout' )->settings[] = 'header_content_area';

if ( current_theme_supports( 'ignition-side-header' ) ) {
	$args = $customizer_options['side_mode_header_mobile_content_area'];
	$wp_customize->add_setting( 'side_mode_header_mobile_content_area', $args['setting_args'] );
	$wp_customize->add_control( 'side_mode_header_mobile_content_area', $args['control_args'] );
	$wp_customize->selective_refresh->get_partial( 'header_layout' )->settings[] = 'side_mode_header_mobile_content_area';
}
