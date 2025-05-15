<?php
/**
 * Customizer section options: Page Title
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'page_title', array(
	'title'    => esc_html_x( 'Page Title', 'customizer section title', 'ignition' ),
	'priority' => 4,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

if ( current_theme_supports( 'ignition-page-title-with-background' ) ) {
	$args = $customizer_options['page_title_with_background_is_visible'];
	$wp_customize->add_setting( 'page_title_with_background_is_visible', $args['setting_args'] );
	$wp_customize->add_control( 'page_title_with_background_is_visible', $args['control_args'] );


	$args = $customizer_options['page_title_with_background_height'];
	$wp_customize->add_setting( 'page_title_with_background_height', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Range_Control( $wp_customize, 'page_title_with_background_height', $args['control_args'] ) );

	$args = $customizer_options['page_title_with_background_text_align_horizontal'];
	$wp_customize->add_setting( 'page_title_with_background_text_align_horizontal', $args['setting_args'] );
	$wp_customize->add_control( 'page_title_with_background_text_align_horizontal', $args['control_args'] );
}

$args = $customizer_options['normal_page_title_title_is_visible'];
$wp_customize->add_setting( 'normal_page_title_title_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'normal_page_title_title_is_visible', $args['control_args'] );

$args = $customizer_options['normal_page_title_subtitle_is_visible'];
$wp_customize->add_setting( 'normal_page_title_subtitle_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'normal_page_title_subtitle_is_visible', $args['control_args'] );

$args = $customizer_options['breadcrumb_is_visible'];
$wp_customize->add_setting( 'breadcrumb_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'breadcrumb_is_visible', $args['control_args'] );

if ( current_theme_supports( 'ignition-page-title-with-background' ) ) {
	//
	// Colors
	//
	$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'page_title_colors_sub', array(
		'section'  => 'page_title',
		'settings' => array(),
		'label'    => esc_html__( '▸ Colors', 'ignition' ),
	) ) );

	$args = $customizer_options['page_title_colors_background'];
	$wp_customize->add_setting( 'page_title_colors_background', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'page_title_colors_background', $args['control_args'] ) );

	$args = $customizer_options['page_title_colors_background_image'];
	$wp_customize->add_setting( 'page_title_colors_background_image', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Image_BG_Control( $wp_customize, 'page_title_colors_background_image', $args['control_args'] ) );

	$args = $customizer_options['page_title_colors_background_video'];
	$wp_customize->add_setting( 'page_title_colors_background_video', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_File_Select( $wp_customize, 'page_title_colors_background_video', $args['control_args'] ) );

	$args = $customizer_options['page_title_colors_background_video_disabled'];
	$wp_customize->add_setting( 'page_title_colors_background_video_disabled', $args['setting_args'] );
	$wp_customize->add_control( 'page_title_colors_background_video_disabled', $args['control_args'] );

	$args = $customizer_options['page_title_colors_overlay'];
	$wp_customize->add_setting( 'page_title_colors_overlay', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Color_Gradient_Control( $wp_customize, 'page_title_colors_overlay', $args['control_args'] ) );

	$args = $customizer_options['page_title_colors_primary_text'];
	$wp_customize->add_setting( 'page_title_colors_primary_text', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'page_title_colors_primary_text', $args['control_args'] ) );

	$args = $customizer_options['page_title_colors_secondary_text'];
	$wp_customize->add_setting( 'page_title_colors_secondary_text', $args['setting_args'] );
	$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'page_title_colors_secondary_text', $args['control_args'] ) );
}
