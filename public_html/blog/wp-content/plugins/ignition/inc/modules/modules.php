<?php
/**
 * Defines Ignition modules
 *
 * Modules are big units of functionality that can be selectively supported by themes.
 *
 * E.g. the Accommodation module may include an 'accommodation' post type, shortcode, block-editor blocks, etc
 * that will all be enabled if a theme declares the necessary support.
 *
 * @since 1.0.0
 */


add_action( 'after_setup_theme', 'ignition_modules_init', 100 );
/**
 * Initializes theme-supported modules.
 *
 * @since 1.0.0
 */
function ignition_modules_init() {
	$supported_modules = ignition_get_theme_supported_plugin_modules();

	foreach ( $supported_modules as $module => $module_info ) {
		$default_module_path = untrailingslashit( IGNITION_DIR ) . "/modules/{$module}/module.php";

		if ( ! empty( $module_info['path'] ) ) {
			require_once $module_info['path'];
		} elseif ( file_exists( $default_module_path ) && is_readable( $default_module_path ) ) {
			require_once $default_module_path;
		}
	}
}

/**
 * Returns all modules provided by the plugin.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_all_plugin_modules() {
	/**
	 * Filters the list of all modules supported by Ignition.
	 *
	 * @param array $modules {
	 *
	 *     @type array $module_name {
	 *         @type string $path Absolute path to main module file.
	 *     }
	 * }
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'ignition_all_plugin_modules', array(
		'accommodation' => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/accommodation/module.php',
		),
		'discography'   => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/discography/module.php',
		),
		'event'         => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/event/module.php',
		),
		'gsection'      => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/gsection/module.php',
		),
		'podcast'       => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/podcast/module.php',
		),
		'portfolio'     => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/portfolio/module.php',
		),
		'service'       => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/service/module.php',
		),
		'team'          => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/team/module.php',
		),
		'package'       => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/package/module.php',
		),
		'property'      => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/property/module.php',
		),
		'agent'         => array(
			'path' => untrailingslashit( IGNITION_DIR ) . '/inc/modules/agent/module.php',
		),
	) );
}

/**
 * Returns a list of theme-supported modules.
 *
 * @return array
 */
function ignition_get_theme_supported_plugin_modules() {
	$all_modules = ignition_get_all_plugin_modules();

	$supported_modules = array();

	foreach ( $all_modules as $module => $module_info ) {
		if ( get_theme_support( "ignition-{$module}" ) ) {
			$supported_modules[ $module ] = $module_info;
		}
	}

	/**
	 * Filters the list of Ignition modules supported by the current theme.
	 *
	 * @see ignition_get_all_plugin_modules()
	 *
	 * @param array $supported_modules Theme-supported modules.
	 * @param array $all_modules All Ignition-supported modules.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'ignition_theme_supported_plugin_modules', $supported_modules, $all_modules );
}
