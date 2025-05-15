<?php
/**
 * Customizer-based generated CSS - Top Bar
 *
 * @since 1.0.0
 */

$css = Ignition_Customizer_CSS_Generator::get_instance();

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );
$defaults           = ignition_customizer_defaults( 'all' );

//
// Normal top bar colors
//

$value = get_theme_mod( 'top_bar_colors_background', $defaults['top_bar_colors_background'] );
$args  = $customizer_options['top_bar_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'top_bar_colors_text', $defaults['top_bar_colors_text'] );
$args  = $customizer_options['top_bar_colors_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'top_bar_colors_border', $defaults['top_bar_colors_border'] );
$args  = $customizer_options['top_bar_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );


//
// Transparent top bar colors
//

$value = get_theme_mod( 'top_bar_transparent_colors_background', $defaults['top_bar_transparent_colors_background'] );
$args  = $customizer_options['top_bar_transparent_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'top_bar_transparent_colors_text', $defaults['top_bar_transparent_colors_text'] );
$args  = $customizer_options['top_bar_transparent_colors_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'top_bar_transparent_colors_border', $defaults['top_bar_transparent_colors_border'] );
$args  = $customizer_options['top_bar_transparent_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );

