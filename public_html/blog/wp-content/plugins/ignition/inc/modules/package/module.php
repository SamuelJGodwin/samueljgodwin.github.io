<?php
/**
 * Package module hooks and functions
 *
 * @since 2.0.0
 */

add_action( 'init', 'ignition_module_package_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_package_create_cpt' );
/**
 * Registers the Package post type and taxonomy.
 *
 * @since 2.0.0
 */
function ignition_module_package_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Packages', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Package', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Packages', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Package', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'package', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Package', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Package', 'ignition' ),
		'new_item'           => esc_html__( 'New Package', 'ignition' ),
		'view_item'          => esc_html__( 'View Package', 'ignition' ),
		'search_items'       => esc_html__( 'Search Packages', 'ignition' ),
		'not_found'          => esc_html__( 'No Packages found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Packages found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Package:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Package', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'package', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-feedback',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-package', $args );

	$labels = array(
		'name'              => esc_html_x( 'Destinations', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Destination', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Destinations', 'ignition' ),
		'all_items'         => esc_html__( 'All Destinations', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Destination', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Destination:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Destination', 'ignition' ),
		'update_item'       => esc_html__( 'Update Destination', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Destination', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Destination Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Destinations', 'ignition' ),
		'view_item'         => esc_html__( 'View Destination', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Destinations', 'ignition' ),
	);
	register_taxonomy( 'ignition_package_destination', array( 'ignition-package' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'destination', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );

	$labels = array(
		'name'              => esc_html_x( 'Package Categories', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Package Category', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Package Categories', 'ignition' ),
		'all_items'         => esc_html__( 'All Package Categories', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Package Category', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Package Category:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Package Category', 'ignition' ),
		'update_item'       => esc_html__( 'Update Package Category', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Package Category', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Package Category Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Categories', 'ignition' ),
		'view_item'         => esc_html__( 'View Package Category', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Package Categories', 'ignition' ),
	);
	register_taxonomy( 'ignition_package_category', array( 'ignition-package' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'package-category', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );
}

add_action( 'ignition_deactivated', 'ignition_module_package_unregister_cpt' );
/**
 * Unregisters the Package post type and taxonomy.
 *
 * @since 2.0.0
 */
function ignition_module_package_unregister_cpt() {
	unregister_post_type( 'ignition-package' );
	unregister_taxonomy( 'ignition_package_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_package_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_package_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_package_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_package_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_package_add_tax_to_array' );
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_package_add_destination_tax_to_array' );
// Add Cover Image Option for Destinations taxonomy.
add_filter( 'ignition_cover_image_taxonomies', 'ignition_module_package_add_destination_tax_to_array' );

/**
 * Helper function that merges the post type name into a list of post types.
 *
 * Used to easily add the post type in a list of post types via filters.
 *
 * @since 2.0.0
 *
 * @param string[] $post_types
 *
 * @return array
 */
function ignition_module_package_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-package' ) );
}

/**
 * Helper function that merges the taxonomy name into a list of taxonomies.
 *
 * Used to easily add the taxonomy in a list of taxonomies via filters.
 *
 * @since 2.0.0
 *
 * @param string[] $taxonomies
 *
 * @return array
 */
function ignition_module_package_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_package_category' ) );
}

/**
 * Helper function that merges the Destination taxonomy name into a list of taxonomies.
 *
 * Used to easily add the taxonomy in a list of taxonomies via filters.
 *
 * @since 2.0.0
 *
 * @param string[] $taxonomies
 *
 * @return array
 */
function ignition_module_package_add_destination_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_package_destination' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_package_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 2.0.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_package_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'package' => array(
			'name'          => esc_html__( 'Packages', 'ignition' ),
			'id'            => 'package',
			'description'   => esc_html__( 'Widgets added here will appear on package pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_package_current_sidebar_id' );
/**
 * Filters the sidebar id that should be used for the module's pages.
 *
 * @see ignition_get_current_sidebar_id()
 *
 * @param string $sidebar_id
 *
 * @since 2.0.0
 *
 * @return string
 */
function ignition_module_package_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-package';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'package';
	}

	return $sidebar_id;
}
