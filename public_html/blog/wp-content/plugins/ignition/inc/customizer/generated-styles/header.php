<?php
/**
 * Customizer-based generated CSS - Header
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

$value = (array) get_theme_mod( 'header_layout_menu_mobile_breakpoint', $defaults['header_layout_menu_mobile_breakpoint'] );
$args  = $customizer_options['header_layout_menu_mobile_breakpoint']['render_args'];
$css->add_value( $args['breakpoint'], $value['desktop'], $args['css'], $args['breakpoint_limit'] );


//
// Normal header colors
//

$value = get_theme_mod( 'header_colors_background', $defaults['header_colors_background'] );
$args  = $customizer_options['header_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_colors_background_image', $defaults['header_colors_background_image'] );
$args  = $customizer_options['header_colors_background_image']['render_args'];
$css->add_image_background_by_id( $value, $args['image_size'], $args['css'] );

$value = get_theme_mod( 'header_colors_overlay', $defaults['header_colors_overlay'] );
$args  = $customizer_options['header_colors_overlay']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_colors_text', $defaults['header_colors_text'] );
$args  = $customizer_options['header_colors_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_colors_border', $defaults['header_colors_border'] );
$args  = $customizer_options['header_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_colors_submenu_background', $defaults['header_colors_submenu_background'] );
$args  = $customizer_options['header_colors_submenu_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_colors_submenu_background_hover', $defaults['header_colors_submenu_background_hover'] );
$args  = $customizer_options['header_colors_submenu_background_hover']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_colors_submenu_text', $defaults['header_colors_submenu_text'] );
$args  = $customizer_options['header_colors_submenu_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_colors_submenu_text_hover', $defaults['header_colors_submenu_text_hover'] );
$args  = $customizer_options['header_colors_submenu_text_hover']['render_args'];
$css->add_variable( $args['css_var'], $value );

//
// Transparent header colors
//
if ( current_theme_supports( 'ignition-header-transparent' ) ) {
	$value = get_theme_mod( 'header_transparent_colors_background', $defaults['header_transparent_colors_background'] );
	$args  = $customizer_options['header_transparent_colors_background']['render_args'];
	$css->add_variable( $args['css_var'], $value );

	$value = get_theme_mod( 'header_transparent_colors_background_image', $defaults['header_transparent_colors_background_image'] );
	$args  = $customizer_options['header_transparent_colors_background_image']['render_args'];
	$css->add_image_background_by_id( $value, $args['image_size'], $args['css'] );

	$value = get_theme_mod( 'header_transparent_colors_overlay', $defaults['header_transparent_colors_overlay'] );
	$args  = $customizer_options['header_transparent_colors_overlay']['render_args'];
	$css->add_variable( $args['css_var'], $value );

	$value = get_theme_mod( 'header_transparent_colors_text', $defaults['header_transparent_colors_text'] );
	$args  = $customizer_options['header_transparent_colors_text']['render_args'];
	$css->add_variable( $args['css_var'], $value );

	$value = get_theme_mod( 'header_transparent_colors_border', $defaults['header_transparent_colors_border'] );
	$args  = $customizer_options['header_transparent_colors_border']['render_args'];
	$css->add_variable( $args['css_var'], $value );

	$value = get_theme_mod( 'header_transparent_colors_submenu_background', $defaults['header_transparent_colors_submenu_background'] );
	$args  = $customizer_options['header_transparent_colors_submenu_background']['render_args'];
	$css->add_variable( $args['css_var'], $value );

	$value = get_theme_mod( 'header_transparent_colors_submenu_background_hover', $defaults['header_transparent_colors_submenu_background_hover'] );
	$args  = $customizer_options['header_transparent_colors_submenu_background_hover']['render_args'];
	$css->add_variable( $args['css_var'], $value );

	$value = get_theme_mod( 'header_transparent_colors_submenu_text', $defaults['header_transparent_colors_submenu_text'] );
	$args  = $customizer_options['header_transparent_colors_submenu_text']['render_args'];
	$css->add_variable( $args['css_var'], $value );

	$value = get_theme_mod( 'header_transparent_colors_submenu_text_hover', $defaults['header_transparent_colors_submenu_text_hover'] );
	$args  = $customizer_options['header_transparent_colors_submenu_text_hover']['render_args'];
	$css->add_variable( $args['css_var'], $value );
}

//
// Sticky header colors
//

$value = get_theme_mod( 'header_sticky_colors_background', $defaults['header_sticky_colors_background'] );
$args  = $customizer_options['header_sticky_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_sticky_colors_background_image', $defaults['header_sticky_colors_background_image'] );
$args  = $customizer_options['header_sticky_colors_background_image']['render_args'];
$css->add_image_background_by_id( $value, $args['image_size'], $args['css'] );

$value = get_theme_mod( 'header_sticky_colors_overlay', $defaults['header_sticky_colors_overlay'] );
$args  = $customizer_options['header_sticky_colors_overlay']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_sticky_colors_text', $defaults['header_sticky_colors_text'] );
$args  = $customizer_options['header_sticky_colors_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_sticky_colors_border', $defaults['header_sticky_colors_border'] );
$args  = $customizer_options['header_sticky_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_sticky_colors_submenu_background', $defaults['header_sticky_colors_submenu_background'] );
$args  = $customizer_options['header_sticky_colors_submenu_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_sticky_colors_submenu_background_hover', $defaults['header_sticky_colors_submenu_background_hover'] );
$args  = $customizer_options['header_sticky_colors_submenu_background_hover']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_sticky_colors_submenu_text', $defaults['header_sticky_colors_submenu_text'] );
$args  = $customizer_options['header_sticky_colors_submenu_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_sticky_colors_submenu_text_hover', $defaults['header_sticky_colors_submenu_text_hover'] );
$args  = $customizer_options['header_sticky_colors_submenu_text_hover']['render_args'];
$css->add_variable( $args['css_var'], $value );


//
// Mobile nav colors
//
$value = get_theme_mod( 'header_mobile_nav_colors_background', $defaults['header_mobile_nav_colors_background'] );
$args  = $customizer_options['header_mobile_nav_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_mobile_nav_colors_link', $defaults['header_mobile_nav_colors_link'] );
$args  = $customizer_options['header_mobile_nav_colors_link']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'header_mobile_nav_colors_border', $defaults['header_mobile_nav_colors_border'] );
$args  = $customizer_options['header_mobile_nav_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );
