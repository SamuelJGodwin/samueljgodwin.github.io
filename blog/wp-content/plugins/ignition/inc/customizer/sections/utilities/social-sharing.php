<?php
/**
 * Customizer section options: Utilities - Social Sharing
 *
 * @since 1.9.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'utilities_social_sharing', array(
	'title'    => esc_html_x( 'Social Sharing', 'customizer section title', 'ignition' ),
	'panel'    => 'utilities',
	'priority' => 40,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['utilities_social_sharing_single_post_is_enabled'];
$wp_customize->add_setting( 'utilities_social_sharing_single_post_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'utilities_social_sharing_single_post_is_enabled', $args['control_args'] );

$args = $customizer_options['utilities_social_sharing_single_product_is_enabled'];
$wp_customize->add_setting( 'utilities_social_sharing_single_product_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'utilities_social_sharing_single_product_is_enabled', $args['control_args'] );

$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'utilities_social_sharing_networks_sub', array(
	'section'  => 'utilities_social_sharing',
	'settings' => array(),
	'label'    => esc_html__( '▸ Icons', 'ignition' ),
) ) );

$args = $customizer_options['utilities_social_sharing_facebook_is_enabled'];
$wp_customize->add_setting( 'utilities_social_sharing_facebook_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'utilities_social_sharing_facebook_is_enabled', $args['control_args'] );

$args = $customizer_options['utilities_social_sharing_twitter_is_enabled'];
$wp_customize->add_setting( 'utilities_social_sharing_twitter_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'utilities_social_sharing_twitter_is_enabled', $args['control_args'] );

$args = $customizer_options['utilities_social_sharing_pinterest_is_enabled'];
$wp_customize->add_setting( 'utilities_social_sharing_pinterest_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'utilities_social_sharing_pinterest_is_enabled', $args['control_args'] );

//$args = $customizer_options['utilities_social_sharing_copy_url_is_enabled'];
//$wp_customize->add_setting( 'utilities_social_sharing_copy_url_is_enabled', $args['setting_args'] );
//$wp_customize->add_control( 'utilities_social_sharing_copy_url_is_enabled', $args['control_args'] );
