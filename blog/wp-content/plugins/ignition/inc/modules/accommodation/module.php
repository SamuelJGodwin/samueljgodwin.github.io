<?php
/**
 * Accommodation module hooks and functions
 *
 * @since 1.0.0
 */

add_action( 'init', 'ignition_module_accommodation_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_accommodation_create_cpt' );
/**
 * Registers the Accommodation post type and taxonomy.
 *
 * @since 1.0.0
 */
function ignition_module_accommodation_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Accommodation', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Accommodation', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Accommodation', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Accommodation', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'accommodation', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Accommodation', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Accommodation', 'ignition' ),
		'new_item'           => esc_html__( 'New Accommodation', 'ignition' ),
		'view_item'          => esc_html__( 'View Accommodation', 'ignition' ),
		'search_items'       => esc_html__( 'Search Accommodation', 'ignition' ),
		'not_found'          => esc_html__( 'No Accommodation found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Accommodation found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Accommodation:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Accommodation', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'accommodation', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-admin-home',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-accommodati', $args );

	$labels = array(
		'name'              => esc_html_x( 'Accommodation Categories', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Accommodation Category', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Accommodation Categories', 'ignition' ),
		'all_items'         => esc_html__( 'All Accommodation Categories', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Accommodation Category', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Accommodation Category:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Accommodation Category', 'ignition' ),
		'update_item'       => esc_html__( 'Update Accommodation Category', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Accommodation Category', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Accommodation Category Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Categories', 'ignition' ),
		'view_item'         => esc_html__( 'View Accommodation Category', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Accommodation Categories', 'ignition' ),
	);
	register_taxonomy( 'ignition_accommodation_category', array( 'ignition-accommodati' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'accommodation-category', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );
}

add_action( 'ignition_deactivated', 'ignition_module_accommodation_unregister_cpt' );
/**
 * Unregisters the Accommodation post type and taxonomy.
 *
 * @since 1.0.0
 */
function ignition_module_accommodation_unregister_cpt() {
	unregister_post_type( 'ignition-accommodati' );
	unregister_taxonomy( 'ignition_accommodation_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_accommodation_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_accommodation_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_accommodation_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_accommodation_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_accommodation_add_tax_to_array' );

/**
 * Helper function that merges the post type name into a list of post types.
 *
 * Used to easily add the post type in a list of post types via filters.
 *
 * @since 1.0.0
 *
 * @param string[] $post_types
 *
 * @return array
 */
function ignition_module_accommodation_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-accommodati' ) );
}

/**
 * Helper function that merges the taxonomy name into a list of taxonomies.
 *
 * Used to easily add the taxonomy in a list of taxonomies via filters.
 *
 * @since 1.0.0
 *
 * @param string[] $taxonomies
 *
 * @return array
 */
function ignition_module_accommodation_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_accommodation_category' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_accommodation_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 1.0.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_accommodation_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'accommodation' => array(
			'name'          => esc_html__( 'Accommodation', 'ignition' ),
			'id'            => 'accommodation',
			'description'   => esc_html__( 'Widgets added here will appear on accommodation pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_accommodation_current_sidebar_id' );
/**
 * Filters the sidebar id that should be used for the module's pages.
 *
 * @see ignition_get_current_sidebar_id()
 *
 * @since 1.0.0
 *
 * @param string $sidebar_id
 *
 * @return string
 */
function ignition_module_accommodation_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-accommodati';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'accommodation';
	}

	return $sidebar_id;
}
