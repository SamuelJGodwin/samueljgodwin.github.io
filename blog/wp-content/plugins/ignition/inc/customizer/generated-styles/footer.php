<?php
/**
 * Customizer-based generated CSS - Footer
 *
 * @since 1.0.0
 */

$css = Ignition_Customizer_CSS_Generator::get_instance();

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );
$defaults           = ignition_customizer_defaults( 'all' );

//
// Colors
//

$value = get_theme_mod( 'footer_colors_background', $defaults['footer_colors_background'] );
$args  = $customizer_options['footer_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'footer_colors_background_image', $defaults['footer_colors_background_image'] );
$args  = $customizer_options['footer_colors_background_image']['render_args'];
$css->add_image_background_by_id( $value, $args['image_size'], $args['css'] );

$value = get_theme_mod( 'footer_colors_border', $defaults['footer_colors_border'] );
$args  = $customizer_options['footer_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'footer_colors_title', $defaults['footer_colors_title'] );
$args  = $customizer_options['footer_colors_title']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'footer_colors_text', $defaults['footer_colors_text'] );
$args  = $customizer_options['footer_colors_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

//
// Credits Colors
//

$value = get_theme_mod( 'footer_credits_colors_background', $defaults['footer_credits_colors_background'] );
$args  = $customizer_options['footer_credits_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'footer_credits_colors_text', $defaults['footer_credits_colors_text'] );
$args  = $customizer_options['footer_credits_colors_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'footer_credits_colors_link', $defaults['footer_credits_colors_link'] );
$args  = $customizer_options['footer_credits_colors_link']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'footer_credits_colors_border', $defaults['footer_credits_colors_border'] );
$args  = $customizer_options['footer_credits_colors_border']['render_args'];
$css->add_variable( $args['css_var'], $value );
