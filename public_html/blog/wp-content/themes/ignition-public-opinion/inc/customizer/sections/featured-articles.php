<?php
/**
 * Customizer section options: Featured Articles
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'theme_featured_articles', array(
	'title' => esc_html_x( 'Featured Articles', 'customizer section title', 'ignition-public-opinion' ),
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['theme_featured_articles_tag'];
$wp_customize->add_setting( 'theme_featured_articles_tag', $args['setting_args'] );
$wp_customize->add_control( 'theme_featured_articles_tag', $args['control_args'] );
