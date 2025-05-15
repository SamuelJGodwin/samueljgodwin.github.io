<?php
/**
 * Variations' setup and related functions
 *
 * @since 1.3.0
 */

$variation = ignition_public_opinion_get_theme_variation();
if ( ! empty( $variation ) ) {
	require_once get_theme_file_path( "/theme-variations/{$variation}/variation.php" );
}

/**
 * Returns the available theme variations.
 *
 * @since 1.3.0
 *
 * @return array
 */
function ignition_public_opinion_get_theme_variations() {
	/**
	 * Filters the available theme variations.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args The list of available theme variations.
	 */
	return apply_filters( 'ignition_public_opinion_theme_variations', array(
		''         => array(
			'title'       => __( 'Public Opinion', 'ignition-public-opinion' ),
			'description' => __( 'Magazine variation #1', 'ignition-public-opinion' ),
		),
		'noozbeat' => array(
			'title'       => __( 'Noozbeat', 'ignition-public-opinion' ),
			'description' => __( 'Magazine variation #2', 'ignition-public-opinion' ),
		),
	) );
}

/**
 * Returns the currently active theme variation.
 *
 * @since 1.3.0
 *
 * @return string Theme variation slug, or empty string if no variation is active.
 */
function ignition_public_opinion_get_theme_variation() {
	$variation = get_theme_mod( 'theme_variation', '' );

	if ( array_key_exists( $variation, ignition_public_opinion_get_theme_variations() ) ) {
		return $variation;
	}

	return '';
}

add_action( 'init', 'ignition_public_opinion_initialize_theme_variations' );
/**
 * Initializes the currently active theme variation.
 *
 * @since 1.3.0
 */
function ignition_public_opinion_initialize_theme_variations() {
	$variation = ignition_public_opinion_get_theme_variation();

	if ( ! empty( $variation ) ) {
		require_once get_theme_file_path( "/theme-variations/{$variation}/variation.php" );
	}
}

add_filter( 'body_class', 'ignition_public_opinion_variation_body_class', 10, 2 );
/**
 * Adds theme variation classes on the body tag.
 *
 * @since 1.3.0
 *
 * @param string[] $classes An array of body class names.
 * @param string[] $class   An array of additional class names added to the body.
 *
 * @return array
 */
function ignition_public_opinion_variation_body_class( $classes, $class ) {
	$variation = ignition_public_opinion_get_theme_variation();

	if ( $variation ) {
		$classes[] = 'theme-variation';
		$classes[] = "theme-variation-{$variation}";
	}

	return $classes;
}

/**
 * Retrieves the path of a theme variation file.
 *
 * Searches in the stylesheet directory before the template directory so themes
 * which inherit from a parent theme can just override one file.
 *
 * @since 1.3.0
 *
 * @param string $file            File to search for in the stylesheet directory.
 * @param string|false $variation Optional. The theme variation's file to search for. If empty, the currently active
 *                                theme variation is used.
 *
 * @return string The path of the file.
 */
function ignition_public_opinion_get_theme_variation_file_path( $file, $variation = false ) {
	$file = ltrim( $file, '/' );

	$path = '';

	if ( false === $variation ) {
		$variation = ignition_public_opinion_get_theme_variation();
	}

	if ( $variation ) {
		$path = "/theme-variations/{$variation}";
	}

	return get_theme_file_path( $path . '/' . $file );
}

/**
 * Retrieves the URL of a theme variation file.
 *
 * Searches in the stylesheet directory before the template directory so themes
 * which inherit from a parent theme can just override one file.
 *
 * @since 1.3.0
 *
 * @param string $file            File to search for in the stylesheet directory.
 * @param string|false $variation Optional. The theme variation's file to search for. If empty, the currently active
 *                                theme variation is used.
 *
 * @return string The URL of the file.
 */
function ignition_public_opinion_get_theme_variation_file_uri( $file, $variation = false ) {
	$file = ltrim( $file, '/' );

	$path = '';

	if ( false === $variation ) {
		$variation = ignition_public_opinion_get_theme_variation();
	}

	if ( $variation ) {
		$path = "/theme-variations/{$variation}";
	}

	return get_theme_file_uri( $path . '/' . $file );
}

add_filter( 'ignition_locate_template_variation_path', 'ignition_public_opinion_ignition_locate_template_variation_path' );
/**
 * Filters the plugin's main widget areas.
 *
 * @since 1.3.0
 *
 * @param string $variation_path The variation's path inside the theme, without leading or trailing slashes.
 *                               E.g. 'theme-variations/varisample'.
 */
function ignition_public_opinion_ignition_locate_template_variation_path( $variation_path ) {
	$variation = ignition_public_opinion_get_theme_variation();

	if ( $variation ) {
		$variation_path = "theme-variations/{$variation}";
	}

	return $variation_path;
}
