<?php
/**
 * Layout-related functions and definitions
 *
 * @since 1.0.0
 */

add_filter( 'ignition_header_layout_menu_types', 'ignition_public_opinion_ignition_header_layout_menu_types' );
/**
 * Modifies the list of valid header menu types for this theme.
 *
 * @param array $menu_types
 *
 * @return array
 */
function ignition_public_opinion_ignition_header_layout_menu_types( $menu_types ) {
	unset( $menu_types['full_left'] );
	unset( $menu_types['full_center'] );
	unset( $menu_types['full_right'] );
	unset( $menu_types['split'] );

	// Example of how to add menu types.
	$menu_types['theme'] = array(
		'title'         => __( 'Theme Default', 'ignition-public-opinion' ),
		// Template file suffix, e.g. 'minimal' will map to template-parts/header/header-minimal.php
		// Make sure this path/file exists in the theme.
		'template_file' => 'theme',
		// Extra menu-specific classes.
		'classes'       => array(
			'header-theme',
		),
	);

	return $menu_types;
}

/**
 * Filters the classes returned, for a given number of columns.
 *
 * @since 1.0.0
 *
 * @param string $classes
 * @param int    $columns
 * @param string $context
 */
add_filter( 'ignition_get_columns_classes', 'ignition_public_opinion_ignition_get_columns_classes_related', 10, 3 );
function ignition_public_opinion_ignition_get_columns_classes_related( $classes, $columns, $context ) {
	if ( 'related' === $context && 3 === (int) $columns ) {
		$classes = 'col-lg-4 col-md-6 col-12';
	}

	return $classes;
}
