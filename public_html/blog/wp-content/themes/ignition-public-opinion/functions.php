<?php
/**
 * Theme functions and definitions
 *
 * @since 1.0.0
 */

if ( ! defined( 'IGNITION_PUBLIC_OPINION_NAME' ) ) {
	/**
	 * The theme's slug.
	 */
	define( 'IGNITION_PUBLIC_OPINION_NAME', 'ignition-public-opinion' );
}

add_action( 'after_setup_theme', 'ignition_public_opinion_setup' );
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ignition_public_opinion_setup() {
	// Default content width.
	$GLOBALS['content_width'] = 998;

	// Make theme available for translation.
	load_theme_textdomain( 'ignition-public-opinion', get_template_directory() . '/languages' );

	// Image sizes
	set_post_thumbnail_size( 998, 665, true );
	add_image_size( 'ignition_item', 670, 446, true );
	add_image_size( 'ignition_item_lg', 1340, 894, true );
	add_image_size( 'ignition_article_media', 510, 510, true );
	add_image_size( 'ignition_minicart_item', 160, 160, true );

	add_theme_support( 'editor-styles' );

	$suffix = ignition_public_opinion_ignition_scripts_styles_suffix();
	add_editor_style( "inc/assets/css/admin/editor-styles{$suffix}.css" );

	// Post types/modules.
	add_theme_support( 'ignition-gsection', array(
		'locations' => array(
			'header',
			'sidebar',
			'footer',
		),
	) );
	// Colors.
	add_theme_support( 'ignition-site-colors-secondary' );
	// User meta.
	add_theme_support( 'ignition-user-social-icons' );
	// Typography.
	add_theme_support( 'ignition-typography-navigation' );
	add_theme_support( 'ignition-typography-button' );

	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Primary color', 'ignition-public-opinion' ),
			'slug'  => 'theme-primary',
			'color' => '#000000',
		),
		array(
			'name'  => __( 'Dark gray', 'ignition-public-opinion' ),
			'slug'  => 'theme-dark-gray',
			'color' => '#191919',
		),
		array(
			'name'  => __( 'Medium gray', 'ignition-public-opinion' ),
			'slug'  => 'theme-medium-gray',
			'color' => '#808080',
		),
		array(
			'name'  => __( 'Light gray', 'ignition-public-opinion' ),
			'slug'  => 'theme-light-gray',
			'color' => '#ebebeb',
		),
		array(
			'name'  => __( 'White', 'ignition-public-opinion' ),
			'slug'  => 'white',
			'color' => '#ffffff',
		),
	) );

	// Make sure WooCommerce detects theme support properly. Do not remove this line.
	// add_theme_support( 'woocommerce' );

	// Opt-out features.
	remove_theme_support( 'ignition-header-transparent' );
	remove_theme_support( 'ignition-page-title-with-background' );

	unregister_nav_menu( 'menu-2' );
}

function current_year_shortcode_function() {
    $year = date('Y');
    return $year;
}
add_shortcode('year', 'current_year_shortcode_function');

/**
 * Theme includes.
 */
require_once get_theme_file_path( '/inc/inc.php' );
