<?php
/**
 * Podcast module hooks and functions
 *
 * @since 1.8.0
 */

add_action( 'init', 'ignition_module_podcast_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_podcast_create_cpt' );
/**
 * Registers the Podcast post type and taxonomy.
 *
 * @since 1.8.0
 */
function ignition_module_podcast_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Podcasts', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Podcast', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Podcasts', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Podcast', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'podcast', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Podcast', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Podcast', 'ignition' ),
		'new_item'           => esc_html__( 'New Podcast', 'ignition' ),
		'view_item'          => esc_html__( 'View Podcast', 'ignition' ),
		'search_items'       => esc_html__( 'Search Podcasts', 'ignition' ),
		'not_found'          => esc_html__( 'No Podcasts found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Podcasts found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Podcast:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Podcast', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'podcast', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-microphone',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-podcast', $args );

	$labels = array(
		'name'              => esc_html_x( 'Podcast Categories', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Podcast Category', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Podcast Categories', 'ignition' ),
		'all_items'         => esc_html__( 'All Podcast Categories', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Podcast Category', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Podcast Category:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Podcast Category', 'ignition' ),
		'update_item'       => esc_html__( 'Update Podcast Category', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Podcast Category', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Podcast Category Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Categories', 'ignition' ),
		'view_item'         => esc_html__( 'View Podcast Category', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Podcast Categories', 'ignition' ),
	);
	register_taxonomy( 'ignition_podcast_category', array( 'ignition-podcast' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'podcast-category', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );
}

add_action( 'ignition_deactivated', 'ignition_module_podcast_unregister_cpt' );
/**
 * Unregisters the Podcast post type and taxonomy.
 *
 * @since 1.8.0
 */
function ignition_module_podcast_unregister_cpt() {
	unregister_post_type( 'ignition-podcast' );
	unregister_taxonomy( 'ignition_podcast_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_podcast_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_podcast_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_podcast_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_podcast_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_podcast_add_tax_to_array' );

/**
 * Helper function that merges the post type name into a list of post types.
 *
 * Used to easily add the post type in a list of post types via filters.
 *
 * @since 1.8.0
 *
 * @param string[] $post_types
 *
 * @return array
 */
function ignition_module_podcast_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-podcast' ) );
}

/**
 * Helper function that merges the taxonomy name into a list of taxonomies.
 *
 * Used to easily add the taxonomy in a list of taxonomies via filters.
 *
 * @since 1.8.0
 *
 * @param string[] $taxonomies
 *
 * @return array
 */
function ignition_module_podcast_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_podcast_category' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_podcast_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 1.8.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_podcast_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'podcast' => array(
			'name'          => esc_html__( 'Podcasts', 'ignition' ),
			'id'            => 'podcast',
			'description'   => esc_html__( 'Widgets added here will appear on podcast pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_podcast_current_sidebar_id' );
/**
 * Filters the sidebar id that should be used for the module's pages.
 *
 * @see ignition_get_current_sidebar_id()
 *
 * @since 1.8.0
 *
 * @param string $sidebar_id
 *
 * @return string
 */
function ignition_module_podcast_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-podcast';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'podcast';
	}

	return $sidebar_id;
}
