<?php
/**
 * Customizer-based generated CSS - Site
 *
 * @since 1.0.0
 */

$css = Ignition_Customizer_CSS_Generator::get_instance();

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );
$defaults           = ignition_customizer_defaults( 'all' );

//
// Layout
//
if ( current_theme_supports( 'ignition-side-header' ) ) {
	$value = get_theme_mod( 'side_mode_site_layout_container_width', $defaults['side_mode_site_layout_container_width'] );
	$args  = $customizer_options['side_mode_site_layout_container_width']['render_args'];
	$css->add_responsive( $value, $args['breakpoints_css'], $args['breakpoint_limit'], $args['edge_cases'] );
}

$value = get_theme_mod( 'site_layout_container_width', $defaults['site_layout_container_width'] );
$args  = $customizer_options['site_layout_container_width']['render_args'];
$css->add_responsive( $value, $args['breakpoints_css'], $args['breakpoint_limit'], $args['edge_cases'] );



//
// Colors
//

// Background color & image
if ( ! $customizer_options['site_colors_body_background']['disabled'] && $customizer_options['site_colors_body_background']['output_args']['generated_styles'] ) {
	$value = get_theme_mod( 'site_colors_body_background', $defaults['site_colors_body_background'] );
	$args  = $customizer_options['site_colors_body_background']['render_args'];
	$css->add_variable( $args['css_var'], $value );
}

if ( ! $customizer_options['site_colors_body_background_image']['disabled'] && $customizer_options['site_colors_body_background_image']['output_args']['generated_styles'] ) {
	$value = get_theme_mod( 'site_colors_body_background_image', $defaults['site_colors_body_background_image'] );
	$args  = $customizer_options['site_colors_body_background_image']['render_args'];
	$css->add_image_background_by_id( $value, $args['image_size'], $args['css'] );
}

// Global
$value = get_theme_mod( 'site_colors_primary', $defaults['site_colors_primary'] );
$args  = $customizer_options['site_colors_primary']['render_args'];
$css->add_variable( $args['css_var'], $value );

if ( get_theme_support( 'ignition-site-colors-secondary' ) ) {
	$value = get_theme_mod( 'site_colors_secondary', $defaults['site_colors_secondary'] );
	$args  = $customizer_options['site_colors_secondary']['render_args'];
	$css->add_variable( $args['css_var'], $value );
}

$value = get_theme_mod( 'site_colors_text', $defaults['site_colors_text'] );
$args  = $customizer_options['site_colors_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'site_colors_secondary_text', $defaults['site_colors_secondary_text'] );
$args  = $customizer_options['site_colors_secondary_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'site_colors_heading', $defaults['site_colors_heading'] );
$args  = $customizer_options['site_colors_heading']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'site_colors_border', $defaults['site_colors_border'] );
$args  = $customizer_options['site_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );

// Forms
$value = get_theme_mod( 'site_colors_forms_background', $defaults['site_colors_forms_background'] );
$args  = $customizer_options['site_colors_forms_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'site_colors_forms_border', $defaults['site_colors_forms_border'] );
$args  = $customizer_options['site_colors_forms_border']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'site_colors_forms_text', $defaults['site_colors_forms_text'] );
$args  = $customizer_options['site_colors_forms_text']['render_args'];
$css->add_variable( $args['css_var'], $value );


// Buttons
$value = get_theme_mod( 'site_colors_buttons_background', $defaults['site_colors_buttons_background'] );
$args  = $customizer_options['site_colors_buttons_background']['render_args'];
$css->add_variable( $args['css_var'], $value );


$value = get_theme_mod( 'site_colors_buttons_text', $defaults['site_colors_buttons_text'] );
$args  = $customizer_options['site_colors_buttons_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'site_colors_buttons_border', $defaults['site_colors_buttons_border'] );
$args  = $customizer_options['site_colors_buttons_border']['render_args'];
$css->add_variable( $args['css_var'], $value );


//
// Typography
//
// Use the primary typography's size to set the base font size.
$value = get_theme_mod( 'site_typo_primary', $defaults['site_typo_primary'] );
$args  = $customizer_options['site_base_font_size']['render_args'];
$css->add_typography( ignition_typography_control_extract_properties( $value, array( 'size' ) ), $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_primary', $defaults['site_typo_primary'] );
$args  = $customizer_options['site_typo_primary']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );
$font_family = ! empty( $value['desktop']['family'] ) ? $value['desktop']['family'] : $defaults['site_typo_primary']['desktop']['family'];
$css->add_variable( $args['css_var'], $font_family );

$value = get_theme_mod( 'site_typo_secondary', $defaults['site_typo_secondary'] );
$args  = $customizer_options['site_typo_secondary']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );
$font_family = ! empty( $value['desktop']['family'] ) ? $value['desktop']['family'] : $defaults['site_typo_secondary']['desktop']['family'];
$css->add_variable( $args['css_var'], $font_family );

if ( get_theme_support( 'ignition-typography-navigation' ) ) {
	$value = get_theme_mod( 'site_typo_navigation', $defaults['site_typo_navigation'] );
	$args  = $customizer_options['site_typo_navigation']['render_args'];
	$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );
}

if ( get_theme_support( 'ignition-typography-page-title' ) ) {
	$value = get_theme_mod( 'site_typo_page_title', $defaults['site_typo_page_title'] );
	$args  = $customizer_options['site_typo_page_title']['render_args'];
	$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );
}

$value = get_theme_mod( 'site_typo_h1', $defaults['site_typo_h1'] );
$args  = $customizer_options['site_typo_h1']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_h2', $defaults['site_typo_h2'] );
$args  = $customizer_options['site_typo_h2']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_h3', $defaults['site_typo_h3'] );
$args  = $customizer_options['site_typo_h3']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_h4', $defaults['site_typo_h4'] );
$args  = $customizer_options['site_typo_h4']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_h5', $defaults['site_typo_h5'] );
$args  = $customizer_options['site_typo_h5']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_h6', $defaults['site_typo_h6'] );
$args  = $customizer_options['site_typo_h6']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_widget_title', $defaults['site_typo_widget_title'] );
$args  = $customizer_options['site_typo_widget_title']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

$value = get_theme_mod( 'site_typo_widget_text', $defaults['site_typo_widget_text'] );
$args  = $customizer_options['site_typo_widget_text']['render_args'];
$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );

if ( get_theme_support( 'ignition-typography-button' ) ) {
	$value = get_theme_mod( 'site_typo_button', $defaults['site_typo_button'] );
	$args  = $customizer_options['site_typo_button']['render_args'];
	$css->add_typography( $value, $args['fallback_stack'], $args['breakpoints_css'], $args['breakpoint_limit'] );
}
