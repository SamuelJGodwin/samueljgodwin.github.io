<?php
/**
 * Team module hooks and functions
 *
 * @since 1.0.0
 */

add_action( 'init', 'ignition_module_team_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_team_create_cpt' );
/**
 * Registers the Team post type and taxonomy.
 *
 * @since 1.0.0
 */
function ignition_module_team_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Team', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Team Member', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Team', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Team Member', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'team', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Team Member', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Team Member', 'ignition' ),
		'new_item'           => esc_html__( 'New Team Member', 'ignition' ),
		'view_item'          => esc_html__( 'View Team Member', 'ignition' ),
		'search_items'       => esc_html__( 'Search Team Members', 'ignition' ),
		'not_found'          => esc_html__( 'No Team Members found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Team Members found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Team Member:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Team Member', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'team', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-admin-users',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-team', $args );

	$labels = array(
		'name'              => esc_html_x( 'Team Categories', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Team Category', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Team Categories', 'ignition' ),
		'all_items'         => esc_html__( 'All Team Categories', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Team Category', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Team Category:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Team Category', 'ignition' ),
		'update_item'       => esc_html__( 'Update Team Category', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Team Category', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Team Category Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Categories', 'ignition' ),
		'view_item'         => esc_html__( 'View Team Category', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Team Categories', 'ignition' ),
	);
	register_taxonomy( 'ignition_team_category', array( 'ignition-team' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'team-category', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );
}

add_action( 'ignition_deactivated', 'ignition_module_team_unregister_cpt' );
/**
 * Unregisters the Team post type and taxonomy.
 *
 * @since 1.0.0
 */
function ignition_module_team_unregister_cpt() {
	unregister_post_type( 'ignition-team' );
	unregister_taxonomy( 'ignition_team_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_team_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_team_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_team_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_team_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_team_add_tax_to_array' );

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
function ignition_module_team_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-team' ) );
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
function ignition_module_team_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_team_category' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_team_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 1.0.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_team_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'team' => array(
			'name'          => esc_html__( 'Team', 'ignition' ),
			'id'            => 'team',
			'description'   => esc_html__( 'Widgets added here will appear on team pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_team_current_sidebar_id' );
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
function ignition_module_team_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-team';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'team';
	}

	return $sidebar_id;
}

add_action( 'admin_init', 'ignition_module_team_setup_single_team_metabox' );
/**
 * Registers the team metabox.
 *
 * @since 1.1.0
 */
function ignition_module_team_setup_single_team_metabox() {
	if ( current_theme_supports( 'ignition-team', 'metadata' ) ) {
		add_meta_box( 'ignition-single-team', esc_html__( 'Team Member Info', 'ignition' ), 'ignition_single_team_info_metabox', 'ignition-team', 'side', 'default' );

		add_action( 'save_post', 'ignition_single_team_info_save_post' );
	}
}

/**
 * Displays the "Team Member Info" metabox contents.
 *
 * @since 1.1.0
 *
 * @param WP_Post $object
 * @param array $box
 */
function ignition_single_team_info_metabox( $object, $box ) {
	// Nonce generated inside ignition_prepare_metabox()
	ignition_prepare_metabox( $object->post_type );

	ignition_side_metabox_input( 'ignition_team_location', array(
		'title' => __( 'Location', 'ignition' ),
	) );

	ignition_side_metabox_input( 'ignition_team_genre', array(
		'title' => __( 'Genre', 'ignition' ),
	) );

	ignition_side_metabox_input( 'ignition_team_booking_notice', array(
		'title' => __( 'Booking Notice', 'ignition' ),
	) );
}

/**
 * Stores the "Team Member Info" post meta.
 *
 * @since 1.1.0
 *
 * @param int $post_id
 */
function ignition_single_team_info_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	if ( isset( $_POST['ignition_team_location'] ) ) {
		update_post_meta( $post_id, 'ignition_team_location', sanitize_text_field( $_POST['ignition_team_location'] ) );
	}

	if ( isset( $_POST['ignition_team_genre'] ) ) {
		update_post_meta( $post_id, 'ignition_team_genre', sanitize_text_field( $_POST['ignition_team_genre'] ) );
	}

	if ( isset( $_POST['ignition_team_booking_notice'] ) ) {
		update_post_meta( $post_id, 'ignition_team_booking_notice', sanitize_text_field( $_POST['ignition_team_booking_notice'] ) );
	}

	// phpcs:enable
}
