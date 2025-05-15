<?php
/**
 * Customizer-based generated CSS - Page Title
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
$value = get_theme_mod( 'page_title_with_background_height', $defaults['page_title_with_background_height'] );
$args  = $customizer_options['page_title_with_background_height']['render_args'];
$css->add_responsive( $value, $args['breakpoints_css'], $args['breakpoint_limit'], $args['edge_cases'] );


//
// Colors
//
$value = get_theme_mod( 'page_title_colors_background', $defaults['page_title_colors_background'] );
$args  = $customizer_options['page_title_colors_background']['render_args'];
$css->add_variable( $args['css_var'], $value );

$data  = ignition_page_title_get_data();
$value = $data['background_image'];
$args  = $customizer_options['page_title_colors_background_image']['render_args'];
$css->add_image_background_by_id( $value, $args['image_size'], $args['css'] );

$value = get_theme_mod( 'page_title_colors_overlay', $defaults['page_title_colors_overlay'] );
$args  = $customizer_options['page_title_colors_overlay']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'page_title_colors_primary_text', $defaults['page_title_colors_primary_text'] );
$args  = $customizer_options['page_title_colors_primary_text']['render_args'];
$css->add_variable( $args['css_var'], $value );

$value = get_theme_mod( 'page_title_colors_secondary_text', $defaults['page_title_colors_secondary_text'] );
$args  = $customizer_options['page_title_colors_secondary_text']['render_args'];
$css->add_variable( $args['css_var'], $value );
