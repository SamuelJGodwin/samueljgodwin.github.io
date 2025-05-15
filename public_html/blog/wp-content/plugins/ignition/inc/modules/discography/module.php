<?php
/**
 * Discography module hooks and functions
 *
 * @since 1.1.0
 */

add_action( 'init', 'ignition_module_discography_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_discography_create_cpt' );
/**
 * Registers the Discography post type and taxonomy.
 *
 * @since 1.1.0
 */
function ignition_module_discography_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Discography', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Discography Item', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Discography', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Discography', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'discography', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Discography Item', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Discography Item', 'ignition' ),
		'new_item'           => esc_html__( 'New Discography Item', 'ignition' ),
		'view_item'          => esc_html__( 'View Discography Item', 'ignition' ),
		'search_items'       => esc_html__( 'Search Discography Items', 'ignition' ),
		'not_found'          => esc_html__( 'No Discography Items found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Discography Items found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Discography Item:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Discography Item', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'discography', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-format-audio',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-discography', $args );

	$labels = array(
		'name'              => esc_html_x( 'Discography Categories', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Discography Category', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Discography Categories', 'ignition' ),
		'all_items'         => esc_html__( 'All Discography Categories', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Discography Category', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Discography Category:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Discography Category', 'ignition' ),
		'update_item'       => esc_html__( 'Update Discography Category', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Discography Category', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Discography Category Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Categories', 'ignition' ),
		'view_item'         => esc_html__( 'View Discography Category', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Discography Categories', 'ignition' ),
	);
	register_taxonomy( 'ignition_discography_category', array( 'ignition-discography' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'discography-category', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );
}

add_action( 'ignition_deactivated', 'ignition_module_discography_unregister_cpt' );
/**
 * Unregisters the Discography post type and taxonomy.
 *
 * @since 1.1.0
 */
function ignition_module_discography_unregister_cpt() {
	unregister_post_type( 'ignition-discography' );
	unregister_taxonomy( 'ignition_discography_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_discography_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_discography_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_discography_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_discography_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_discography_add_tax_to_array' );

/**
 * Helper function that merges the post type name into a list of post types.
 *
 * Used to easily add the post type in a list of post types via filters.
 *
 * @since 1.1.0
 *
 * @param string[] $post_types
 *
 * @return array
 */
function ignition_module_discography_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-discography' ) );
}

/**
 * Helper function that merges the taxonomy name into a list of taxonomies.
 *
 * Used to easily add the taxonomy in a list of taxonomies via filters.
 *
 * @since 1.1.0
 *
 * @param string[] $taxonomies
 *
 * @return array
 */
function ignition_module_discography_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_discography_category' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_discography_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 1.1.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_discography_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'discography' => array(
			'name'          => esc_html__( 'Discography', 'ignition' ),
			'id'            => 'discography',
			'description'   => esc_html__( 'Widgets added here will appear on discography pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_discography_current_sidebar_id' );
/**
 * Filters the sidebar id that should be used for the module's pages.
 *
 * @see ignition_get_current_sidebar_id()
 *
 * @since 1.1.0
 *
 * @param string $sidebar_id
 *
 * @return string
 */
function ignition_module_discography_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-discography';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'discography';
	}

	return $sidebar_id;
}

add_action( 'admin_init', 'ignition_module_discography_setup_single_discography_metabox' );
/**
 * Registers the discography metabox.
 *
 * @since 1.1.0
 */
function ignition_module_discography_setup_single_discography_metabox() {
	add_meta_box( 'ignition-single-discography', esc_html__( 'Discography Info', 'ignition' ), 'ignition_single_discography_info_metabox', 'ignition-discography', 'side', 'default' );

	add_action( 'save_post', 'ignition_single_discography_info_save_post' );
}

/**
 * Displays the "Discography Info" metabox contents.
 *
 * @since 1.1.0
 *
 * @param WP_Post $object
 * @param array $box
 */
function ignition_single_discography_info_metabox( $object, $box ) {
	// Nonce generated inside ignition_prepare_metabox()
	ignition_prepare_metabox( $object->post_type );

	ignition_side_metabox_input( 'ignition_discography_date', array(
		'title'       => __( 'Release Date', 'ignition' ),
		'description' => __( 'Make sure the date follows the <em>yyyy-mm-dd</em> format. It will be automatically re-formatted according to your locale settings.', 'ignition' ),
		'input_class' => 'ignition-datepicker',
	) );

	ignition_side_metabox_input( 'ignition_discography_catalog_no', array(
		'title' => __( 'Catalog Number', 'ignition' ),
	) );

	ignition_side_metabox_input( 'ignition_discography_label', array(
		'title' => __( 'Label', 'ignition' ),
	) );

	ignition_side_metabox_input( 'ignition_discography_producers', array(
		'title' => __( 'Producers', 'ignition' ),
	) );
}

/**
 * Stores the "Discography Info" post meta.
 *
 * @since 1.1.0
 *
 * @param int $post_id
 */
function ignition_single_discography_info_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	if ( isset( $_POST['ignition_discography_date'] ) ) {
		update_post_meta( $post_id, 'ignition_discography_date', sanitize_text_field( $_POST['ignition_discography_date'] ) );
	}

	if ( isset( $_POST['ignition_discography_catalog_no'] ) ) {
		update_post_meta( $post_id, 'ignition_discography_catalog_no', sanitize_text_field( $_POST['ignition_discography_catalog_no'] ) );
	}

	if ( isset( $_POST['ignition_discography_label'] ) ) {
		update_post_meta( $post_id, 'ignition_discography_label', sanitize_text_field( $_POST['ignition_discography_label'] ) );
	}

	if ( isset( $_POST['ignition_discography_producers'] ) ) {
		update_post_meta( $post_id, 'ignition_discography_producers', sanitize_text_field( $_POST['ignition_discography_producers'] ) );
	}

	// phpcs:enable
}
