<?php
/**
 * GutenBee related hooks and functions
 *
 * @since 1.0.0
 */

add_action( 'after_setup_theme', 'ignition_gutenbee_setup' );
/**
 * Sets up GutenBee support.
 *
 * @since 1.0.0
 */
function ignition_gutenbee_setup() {
	add_theme_support( 'block/gutenbee/container', array(
		'themeGrid' => true,
	) );

	add_theme_support( 'block/gutenbee/post-types', array(
		'gridEffect'      => false,
		'masonry'         => false,
		'categoryFilters' => true,
		'selectImageSize' => array(),
	) );
}

add_filter( 'gutenbee_locate_template_theme_path', 'ignition_gutenbee_locate_template_theme_path', 10, 2 );
/**
 * Filters the path that GutenBee templates can be found under.
 *
 * @since 1.0.0
 *
 * @param string $path
 * @param string $block
 *
 * @return string
 */
function ignition_gutenbee_locate_template_theme_path( $path, $block ) {
	return "template-parts/gutenbee/{$block}";
}

add_filter( 'gutenbee_locate_template', 'ignition_gutenbee_locate_template', 10, 6 );
/**
 * Filters the name of the highest priority template file that exists.
 *
 * @since 1.0.0
 *
 * @param $located
 * @param $block
 * @param $theme_templates
 * @param $templates
 * @param $theme_path
 * @param $default_path
 *
 * @return string
 */
function ignition_gutenbee_locate_template( $located, $block, $theme_templates, $templates, $theme_path, $default_path ) {
	// Intercept GutenBee's gutenbee_locate_template() so that we can try getting Ignition's files first.
	$ignition_template = ignition_locate_template( $theme_templates );
	return $ignition_template;
}

add_filter( 'gutenbee_post_types_container_classes', 'ignition_gutenbee_post_types_container_classes', 10, 2 );
/**
 * Rewrites GutenBee classes.
 *
 * @since 1.0.0
 *
 * @param $container_classes
 * @param $attributes
 *
 * @return array
 */
function ignition_gutenbee_post_types_container_classes( $container_classes, $attributes ) {
	$new_classes = array();
	foreach ( $container_classes as $class ) {
		$new_classes[] = str_replace( 'gutenbee-', '', $class );
	}

	return $new_classes;
}

add_filter( 'gutenbee_get_columns_classes', 'ignition_gutenbee_get_columns_classes', 10, 2 );
/**
 * Filters GutenBee's column classes to be compatible with the Ignition.
 *
 * @param $classes
 * @param $columns
 *
 * @return string
 */
function ignition_gutenbee_get_columns_classes( $classes, $columns ) {
	return ignition_get_columns_classes( $columns );
}

add_filter( 'gutenbee_block_post_types_post_types_columns', 'ignition_gutenbee_block_post_types_post_types_columns', 10 );
/**
 * Filters GutenBee's Post Types block's min-max columns for each post type.
 *
 * @param array $pt_cols
 *
 * @return array
 */
function ignition_gutenbee_block_post_types_post_types_columns( $pt_cols ) {
	if ( isset( $pt_cols['ignition-portfolio'] ) ) {
		$pt_cols['ignition-portfolio']['min'] = 2;
	}

	if ( isset( $pt_cols['ignition-team'] ) ) {
		$pt_cols['ignition-team']['min'] = 2;
	}

	if ( isset( $pt_cols['product'] ) ) {
		$pt_cols['product']['min'] = 2;
	}

	return $pt_cols;
}

