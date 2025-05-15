<?php
/**
 * Customizer Sections and Settings, and Ignition overrides
 *
 * @since 1.0.0
 */

add_action( 'customize_register', 'ignition_public_opinion_customize_register' );
/**
 * Registers Customizer panels, sections, and controls.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Reference to the customizer's manager object.
 */
function ignition_public_opinion_customize_register( $wp_customize ) {
	//
	// Featured Articles
	//
	require_once get_theme_file_path( '/inc/customizer/sections/featured-articles.php' );

	//
	// News Ticker
	//
	require_once get_theme_file_path( '/inc/customizer/sections/utilities/news-ticker.php' );
}

add_action( 'customize_controls_enqueue_scripts', 'ignition_public_opinion_customize_controls_js' );
/**
 * Registers/Enqueues styles and scripts for customizer controls.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_customize_controls_js() {
	$suffix = ignition_public_opinion_ignition_scripts_styles_suffix();

	wp_enqueue_script( 'ignition-public-opinion-customizer-controls', get_theme_file_uri( "/inc/customizer/controls/scripts{$suffix}.js" ), array(
		'jquery',
		'suggest',
	), ignition_public_opinion_asset_version(), true );
}

add_filter( 'ignition_customize_text_field_shortcodes_description', 'ignition_public_opinion_ignition_customize_text_field_shortcodes_description' );
/**
 * Filters the default text for customizer fields that accept text, HTML, and shortcodes.
 *
 * @since 1.0.0
 *
 * @param string $description
 *
 * @return string
 */
function ignition_public_opinion_ignition_customize_text_field_shortcodes_description( $description ) {
	$description = sprintf(
		/* translators: %s is a URL */
		__( 'You can add text, HTML, and shortcodes.<br/><a href="%s" target="_blank">Theme Shortcodes</a>', 'ignition-public-opinion' ),
		esc_url( ignition_public_opinion_get_theme_link_url( 'theme_shortcodes', 'utm_source=customizer&utm_medium=description-link&utm_campaign=ignition-public-opinion' ) )
	);

	return $description;
}

/**
 * Customizer defaults.
 */
require_once get_theme_file_path( '/inc/customizer/defaults.php' );

/**
 * Customizer options.
 */
require_once get_theme_file_path( '/inc/customizer/options.php' );
