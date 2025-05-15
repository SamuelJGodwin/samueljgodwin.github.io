<?php
/**
 * Customizer section options: Footer
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'footer', array(
	'title'    => esc_html_x( 'Footer', 'customizer section title', 'ignition' ),
	'priority' => 6,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['footer_is_visible'];
$wp_customize->add_setting( 'footer_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'footer_is_visible', $args['control_args'] );


$args = $customizer_options['footer_widgets_layout_type'];
if ( count( $args['control_args']['choices'] ) > 1 ) {
	$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'footer_layout_sub', array(
		'section'  => 'footer',
		'settings' => array(),
		'label'    => esc_html__( '▸ Layout', 'ignition' ),
	) ) );

	$wp_customize->add_setting( 'footer_widgets_layout_type', $args['setting_args'] );
	$wp_customize->add_control( 'footer_widgets_layout_type', $args['control_args'] );
	$wp_customize->selective_refresh->get_partial( 'footer_layout' )->settings[] = 'footer_widgets_layout_type';
}

$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'footer_color_sub', array(
	'section'  => 'footer',
	'settings' => array(),
	'label'    => esc_html__( '▸ Color', 'ignition' ),
) ) );

$args = $customizer_options['footer_colors_background'];
$wp_customize->add_setting( 'footer_colors_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_colors_background', $args['control_args'] ) );

$args = $customizer_options['footer_colors_background_image'];
$wp_customize->add_setting( 'footer_colors_background_image', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Image_BG_Control( $wp_customize, 'footer_colors_background_image', $args['control_args'] ) );

$args = $customizer_options['footer_colors_border'];
$wp_customize->add_setting( 'footer_colors_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_colors_border', $args['control_args'] ) );

$args = $customizer_options['footer_colors_title'];
$wp_customize->add_setting( 'footer_colors_title', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_colors_title', $args['control_args'] ) );

$args = $customizer_options['footer_colors_text'];
$wp_customize->add_setting( 'footer_colors_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_colors_text', $args['control_args'] ) );



$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'footer_credits_content_sub', array(
	'section'  => 'footer',
	'settings' => array(),
	'label'    => esc_html__( '▸ Credits content', 'ignition' ),
) ) );

$args = $customizer_options['footer_content_area1'];
$wp_customize->add_setting( 'footer_content_area1', $args['setting_args'] );
$wp_customize->add_control( 'footer_content_area1', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'footer_layout' )->settings[] = 'footer_content_area1';

$args = $customizer_options['footer_content_area2'];
$wp_customize->add_setting( 'footer_content_area2', $args['setting_args'] );
$wp_customize->add_control( 'footer_content_area2', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'footer_layout' )->settings[] = 'footer_content_area2';


$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'footer_credits_colors_sub', array(
	'section'  => 'footer',
	'settings' => array(),
	'label'    => esc_html__( '▸ Credits color', 'ignition' ),
) ) );

$args = $customizer_options['footer_credits_colors_background'];
$wp_customize->add_setting( 'footer_credits_colors_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_credits_colors_background', $args['control_args'] ) );

$args = $customizer_options['footer_credits_colors_text'];
$wp_customize->add_setting( 'footer_credits_colors_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_credits_colors_text', $args['control_args'] ) );

$args = $customizer_options['footer_credits_colors_link'];
$wp_customize->add_setting( 'footer_credits_colors_link', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_credits_colors_link', $args['control_args'] ) );

$args = $customizer_options['footer_credits_colors_border'];
$wp_customize->add_setting( 'footer_credits_colors_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'footer_credits_colors_border', $args['control_args'] ) );

