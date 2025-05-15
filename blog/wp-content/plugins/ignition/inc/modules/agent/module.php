<?php
/**
 * Agent module hooks and functions
 *
 * @since 2.2.0
 */

add_action( 'init', 'ignition_module_agent_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_agent_create_cpt' );
/**
 * Registers the Agent post type and taxonomy.
 *
 * @since 2.2.0
 */
function ignition_module_agent_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Agents', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Agent', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Agents', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Agent', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'agent', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Agent', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Agent', 'ignition' ),
		'new_item'           => esc_html__( 'New Agent', 'ignition' ),
		'view_item'          => esc_html__( 'View Agent', 'ignition' ),
		'search_items'       => esc_html__( 'Search Agents', 'ignition' ),
		'not_found'          => esc_html__( 'No Agents found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Agents found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Agent:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Agent', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'agent', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-admin-users',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-agent', $args );

	$labels = array(
		'name'              => esc_html_x( 'Agent Categories', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Agent Category', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Agent Categories', 'ignition' ),
		'all_items'         => esc_html__( 'All Agent Categories', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Agent Category', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Agent Category:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Agent Category', 'ignition' ),
		'update_item'       => esc_html__( 'Update Agent Category', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Agent Category', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Agent Category Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Categories', 'ignition' ),
		'view_item'         => esc_html__( 'View Agent Category', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Agent Categories', 'ignition' ),
	);
	register_taxonomy( 'ignition_agent_category', array( 'ignition-agent' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'agent-category', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );

	if ( post_type_exists( 'ignition-property' ) ) {
		$labels = array(
			'name'              => esc_html_x( 'Agents', 'taxonomy general name', 'ignition' ),
			'singular_name'     => esc_html_x( 'Agent', 'taxonomy singular name', 'ignition' ),
			'search_items'      => esc_html__( 'Search Agents', 'ignition' ),
			'all_items'         => esc_html__( 'All Agents', 'ignition' ),
			'parent_item'       => esc_html__( 'Parent Agent', 'ignition' ),
			'parent_item_colon' => esc_html__( 'Parent Agent:', 'ignition' ),
			'edit_item'         => esc_html__( 'Edit Agent', 'ignition' ),
			'update_item'       => esc_html__( 'Update Agent', 'ignition' ),
			'add_new_item'      => esc_html__( 'Add New Agent', 'ignition' ),
			'new_item_name'     => esc_html__( 'New Agent Name', 'ignition' ),
			'menu_name'         => esc_html__( 'Agents', 'ignition' ),
			'view_item'         => esc_html__( 'View Agent', 'ignition' ),
			'popular_items'     => esc_html__( 'Popular Agents', 'ignition' ),
		);

		register_taxonomy( 'ignition_property_agent', array( 'ignition-property' ), array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => esc_html_x( 'property-agent', 'taxonomy slug', 'ignition' ) ),
			'show_in_rest'      => true,
		) );
	}
}

add_action( 'ignition_deactivated', 'ignition_module_agent_unregister_cpt' );
/**
 * Unregisters the Agent post type and taxonomy.
 *
 * @since 2.2.0
 */
function ignition_module_agent_unregister_cpt() {
	unregister_post_type( 'ignition-agent' );
	unregister_taxonomy( 'ignition_agent_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_agent_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_agent_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_agent_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_agent_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_agent_add_tax_to_array' );

/**
 * Helper function that merges the post type name into a list of post types.
 *
 * Used to easily add the post type in a list of post types via filters.
 *
 * @since 2.2.0
 *
 * @param string[] $post_types
 *
 * @return array
 */
function ignition_module_agent_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-agent' ) );
}

/**
 * Helper function that merges the taxonomy name into a list of taxonomies.
 *
 * Used to easily add the taxonomy in a list of taxonomies via filters.
 *
 * @since 2.2.0
 *
 * @param string[] $taxonomies
 *
 * @return array
 */
function ignition_module_agent_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_agent_category' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_agent_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 2.2.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_agent_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'agent' => array(
			'name'          => esc_html__( 'Agents', 'ignition' ),
			'id'            => 'agent',
			'description'   => esc_html__( 'Widgets added here will appear on agent pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_agent_current_sidebar_id' );
/**
 * Filters the sidebar id that should be used for the module's pages.
 *
 * @see ignition_get_current_sidebar_id()
 *
 * @since 2.2.0
 *
 * @param string $sidebar_id
 *
 * @return string
 */
function ignition_module_agent_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-agent';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'agent';
	}

	return $sidebar_id;
}

add_action( 'admin_init', 'ignition_module_agent_setup_single_agent_metabox' );
/**
 * Registers the agent metabox.
 *
 * @since 2.2.0
 */
function ignition_module_agent_setup_single_agent_metabox() {
	if ( current_theme_supports( 'ignition-agent', 'metadata' ) ) {
		add_meta_box( 'ignition-single-agent', esc_html__( 'Agent Info', 'ignition' ), 'ignition_single_agent_info_metabox', 'ignition-agent', 'side', 'default' );

		add_action( 'save_post', 'ignition_single_agent_info_save_post' );
	}
}

/**
 * Displays the "Agent Info" metabox contents.
 *
 * @since 2.2.0
 *
 * @param WP_Post $object
 * @param array $box
 */
function ignition_single_agent_info_metabox( $object, $box ) {
	// Nonce generated inside ignition_prepare_metabox()
	ignition_prepare_metabox( $object->post_type );

	ignition_side_metabox_input( 'ignition_agent_contact_shortcode', array(
		'title' => __( 'Contact Shortcode', 'ignition' ),
	) );
}

/**
 * Stores the "Agent Info" post meta.
 *
 * @since 2.2.0
 *
 * @param int $post_id
 */
function ignition_single_agent_info_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	if ( isset( $_POST['ignition_agent_contact_shortcode'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_contact_shortcode', sanitize_text_field( $_POST['ignition_agent_contact_shortcode'] ) );
	}

	// phpcs:enable
}

add_action( 'save_post', 'ignition_module_agent_auto_create_agent_term', 10, 3 );
/**
 * Automatically creates an Agent term for Properties when new agent is added.
 *
 * @since 1.0.0
 *
 * @param int     $post_ID Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function ignition_module_agent_auto_create_agent_term( $post_ID, $post, $update ) {
	if ( ! taxonomy_exists( 'ignition_property_agent' ) ) {
		return false;
	}

	if ( is_null( $post ) ) {
		return false;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	if ( isset( $_POST['post_view'] ) && 'list' === $_POST['post_view'] ) {
		return false;
	}

	if ( wp_is_post_autosave( $post ) || wp_is_post_revision( $post ) || 'publish' !== $post->post_status ) {
		return false;
	}

	if ( 'ignition-agent' !== $post->post_type ) {
		return false;
	}

	if ( ! $update ) {
		ignition_module_agent_create_agent_term_from_post( $post );
	} else {
		$term = get_term_by( 'slug', $post->post_name, 'ignition_property_agent' );
		if ( $term && ! is_wp_error( $term ) ) {
			wp_update_term( $term->term_id, $term->taxonomy, array( 'name' => $post->post_title ) );
		} else {
			ignition_module_agent_create_agent_term_from_post( $post );
		}
	}
}

add_action( 'delete_post', 'ignition_module_agent_auto_delete_agent_term', 10, 2 );
/**
 * Fires immediately before a post is deleted from the database.
 *
 * @since 1.0.0
 *
 * @param int     $post_ID Post ID.
 * @param WP_Post $post   Post object.
 */
function ignition_module_agent_auto_delete_agent_term( $post_ID, $post ) {
	if ( ! taxonomy_exists( 'ignition_property_agent' ) ) {
		return false;
	}

	if ( 'ignition-agent' !== $post->post_type ) {
		return false;
	}

	$slug  = $post->post_name;
	$found = strpos( $slug, '__trashed' );
	if ( false !== $found ) {
		$slug = substr( $slug, 0, $found );
	}

	$term = get_term_by( 'slug', $slug, 'ignition_property_agent' );
	if ( $term && ! is_wp_error( $term ) ) {
		wp_delete_term( $term->term_id, $term->taxonomy );
	}
}

/**
 * Creates a Agent term from a passed Agent post object, and associates the post with it.
 *
 * @since 1.0.0
 *
 * @param WP_Post $post Post object.
 */
function ignition_module_agent_create_agent_term_from_post( $post ) {
	$new_term = wp_insert_term( $post->post_title, 'ignition_property_agent', array( 'slug' => $post->post_name ) );
	if ( is_array( $new_term ) && isset( $new_term['term_id'] ) ) {
		wp_set_object_terms( $post->ID, $new_term['term_id'], 'ignition_property_agent', true );
	}
}

require_once untrailingslashit( __DIR__ ) . '/custom-fields.php';
