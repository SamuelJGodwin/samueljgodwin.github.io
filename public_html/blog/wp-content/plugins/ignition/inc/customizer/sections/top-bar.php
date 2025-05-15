<?php
/**
 * Customizer section options: Top Bar
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'top_bar', array(
	'title'    => esc_html_x( 'Top Bar', 'customizer section title', 'ignition' ),
	'priority' => 2,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['top_bar_layout_is_visible'];
$wp_customize->add_setting( 'top_bar_layout_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'top_bar_layout_is_visible', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'header_layout' )->settings[] = 'top_bar_layout_is_visible';

$args = $customizer_options['top_bar_layout_visibility'];
$wp_customize->add_setting( 'top_bar_layout_visibility', $args['setting_args'] );
$wp_customize->add_control( 'top_bar_layout_visibility', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'header_layout' )->settings[] = 'top_bar_layout_visibility';



$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'top_bar_content_sub', array(
	'section'  => 'top_bar',
	'settings' => array(),
	'label'    => esc_html__( '▸ Content', 'ignition' ),
) ) );

$args = $customizer_options['top_bar_content_area1'];
$wp_customize->add_setting( 'top_bar_content_area1', $args['setting_args'] );
$wp_customize->add_control( 'top_bar_content_area1', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'header_layout' )->settings[] = 'top_bar_content_area1';

$args = $customizer_options['top_bar_content_area2'];
$wp_customize->add_setting( 'top_bar_content_area2', $args['setting_args'] );
$wp_customize->add_control( 'top_bar_content_area2', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'header_layout' )->settings[] = 'top_bar_content_area2';

$args = $customizer_options['top_bar_content_area3'];
$wp_customize->add_setting( 'top_bar_content_area3', $args['setting_args'] );
$wp_customize->add_control( 'top_bar_content_area3', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'header_layout' )->settings[] = 'top_bar_content_area3';



$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'top_bar_color_sub', array(
	'section'     => 'top_bar',
	'settings'    => array(),
	'label'       => esc_html__( '▸ Normal Colors', 'ignition' ),
	'description' => wp_kses( sprintf(
		/* translators: %1$s is the URL to the Header Layout section. */
		__( 'Colors that will be applied on the top bar when the <a href="%1$s">header is <strong>normal</strong></a>.', 'ignition' ),
		admin_url( '/customize.php?autofocus[section]=header_layout' )
	), ignition_get_allowed_tags() ),
) ) );

$args = $customizer_options['top_bar_colors_background'];
$wp_customize->add_setting( 'top_bar_colors_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'top_bar_colors_background', $args['control_args'] ) );

$args = $customizer_options['top_bar_colors_text'];
$wp_customize->add_setting( 'top_bar_colors_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'top_bar_colors_text', $args['control_args'] ) );

$args = $customizer_options['top_bar_colors_border'];
$wp_customize->add_setting( 'top_bar_colors_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'top_bar_colors_border', $args['control_args'] ) );



$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'top_bar_transparent_color_sub', array(
	'section'     => 'top_bar',
	'settings'    => array(),
	'label'       => esc_html__( '▸ Transparent Colors', 'ignition' ),
	'description' => wp_kses( sprintf(
		/* translators: %1$s is the URL to the Header Layout section. */
		__( 'Colors that will be applied on the top bar when the <a href="%1$s">header is <strong>transparent</strong></a>.', 'ignition' ),
		admin_url( '/customize.php?autofocus[section]=header_layout' )
	), ignition_get_allowed_tags() ),
) ) );

$args = $customizer_options['top_bar_transparent_colors_background'];
$wp_customize->add_setting( 'top_bar_transparent_colors_background', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'top_bar_transparent_colors_background', $args['control_args'] ) );

$args = $customizer_options['top_bar_transparent_colors_text'];
$wp_customize->add_setting( 'top_bar_transparent_colors_text', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'top_bar_transparent_colors_text', $args['control_args'] ) );

$args = $customizer_options['top_bar_transparent_colors_border'];
$wp_customize->add_setting( 'top_bar_transparent_colors_border', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Color_Control( $wp_customize, 'top_bar_transparent_colors_border', $args['control_args'] ) );
