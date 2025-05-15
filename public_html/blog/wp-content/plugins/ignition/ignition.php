<?php
/*
Plugin Name: Ignition Framework
Description: CSSIgniter themes' core functions.
Plugin URI: https://www.cssigniter.com/
Version: 2.2.2
Author: CSSIgniter
Author URI: https://www.cssigniter.com/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ignition
*/

if ( ! defined( 'IGNITION_DIR' ) ) {
	/**
	 * Ignition plugin directory path.
	 *
	 * @since 1.0.0
	 */
	define( 'IGNITION_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'IGNITION_DIR_URL' ) ) {
	/**
	 * Ignition plugin directory URL.
	 *
	 * @since 1.0.0
	 */
	define( 'IGNITION_DIR_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'IGNITION_PLUGIN_FILE_PATH' ) ) {
	/**
	 * Ignition plugin file path.
	 *
	 * @since 1.0.0
	 */
	define( 'IGNITION_PLUGIN_FILE_PATH', __FILE__ );
}

if ( ! defined( 'IGNITION_VERSION' ) ) {
	if ( ! function_exists( 'get_plugin_data' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugin_data = get_plugin_data( IGNITION_PLUGIN_FILE_PATH );
	/**
	 * Current Ignition plugin version.
	 *
	 * @since 1.0.0
	 */
	define( 'IGNITION_VERSION', $plugin_data['Version'] );
}

/**
 * Declares features that all Ignition-based themes support.
 *
 * @since 1.0.0
 */
function ignition_setup() {
	load_plugin_textdomain( 'ignition', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	new Ignition_Updater( 'ignition', IGNITION_VERSION, IGNITION_PLUGIN_FILE_PATH );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'gallery',
		'caption',
		'search-form',
		'script',
		'style',
		'navigation-widgets',
	) );

	// Add theme support for custom logos.
	add_theme_support( 'custom-logo',
		/**
		 * Filters the custom logo parameters.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The list of custom logo parameters.
		 */
		apply_filters( 'ignition_add_theme_support_custom_logo', array() )
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	// Trick editor into loading inline styles. See ignition_pre_http_request_block_editor_styles()
	add_editor_style( 'https://ignition-block-editor-styles' );

	add_theme_support( 'ignition-header-transparent' );
	add_theme_support( 'ignition-page-title-with-background' );
	add_theme_support( 'ignition-top-bar' );

	add_post_type_support( 'post', 'ignition-authorbox' );
	add_post_type_support( 'post', 'ignition-related' );
	add_post_type_support( 'page', 'excerpt' );

	if ( get_theme_mod( 'utilities_block_editor_dark_mode_is_enabled', ignition_customizer_defaults( 'utilities_block_editor_dark_mode_is_enabled' ) ) ) {
		add_theme_support( 'dark-editor-style' );
	}

	if ( ! get_theme_mod( 'utilities_block_widgets_is_enabled', ignition_customizer_defaults( 'utilities_block_widgets_is_enabled' ) ) ) {
		remove_theme_support( 'widgets-block-editor' );
	}
}
add_action( 'after_setup_theme', 'ignition_setup' );

/**
 * Handles plugin activation.
 *
 * @since 1.0.0
 */
function ignition_activated() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	// Make sure supported modules are initialized, otherwise CPTs and taxonomies will not get registered.
	ignition_modules_init();

	/**
	 * Hook: ignition_activated.
	 *
	 * Fires when the plugin is activated, and before permalinks are flushed.
	 * Custom post types and Taxonomies are registered at this point.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_activated' );

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ignition_activated' );

/**
 * Handles plugin deactivation.
 *
 * @since 1.0.0
 */
function ignition_deactivated() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	/**
	 * Hook: ignition_deactivated.
	 *
	 * Fires when the plugin is deactivated, and before permalinks are flushed.
	 * Custom post types and Taxonomies are registered at this point.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_deactivated' );

	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ignition_deactivated' );

/**
 * Plugin includes.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/inc.php';
