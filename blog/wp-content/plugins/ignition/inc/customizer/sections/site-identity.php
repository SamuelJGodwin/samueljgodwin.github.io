<?php
/**
 * Customizer section options: Site Identity
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['site_identity_custom_logo_alt'];
$wp_customize->add_setting( 'site_identity_custom_logo_alt', $args['setting_args'] );
$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'site_identity_custom_logo_alt', $args['control_args'] ) );
$wp_customize->selective_refresh->get_partial( 'site_branding' )->settings[] = 'site_identity_custom_logo_alt';


$args = $customizer_options['site_identity_title_is_visible'];
$wp_customize->add_setting( 'site_identity_title_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'site_identity_title_is_visible', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'site_branding' )->settings[] = 'site_identity_title_is_visible';

$args = $customizer_options['site_identity_description_is_visible'];
$wp_customize->add_setting( 'site_identity_description_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'site_identity_description_is_visible', $args['control_args'] );
$wp_customize->selective_refresh->get_partial( 'site_branding' )->settings[] = 'site_identity_description_is_visible';
