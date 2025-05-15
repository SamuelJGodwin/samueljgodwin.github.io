<?php
/**
 * Global Section module hooks and functions
 *
 * @since 1.2.0
 */

add_action( 'init', 'ignition_module_gsection_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_gsection_create_cpt' );
/**
 * Registers the Global Section post type.
 *
 * @since 1.2.0
 */
function ignition_module_gsection_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Global Sections', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Global Section', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Global Sections', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Global Section', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'global section', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Global Section', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Global Section', 'ignition' ),
		'new_item'           => esc_html__( 'New Global Section', 'ignition' ),
		'view_item'          => esc_html__( 'View Global Section', 'ignition' ),
		'search_items'       => esc_html__( 'Search Global Sections', 'ignition' ),
		'not_found'          => esc_html__( 'No Global Sections found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Global Sections found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Global Section:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Global Section', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'gsection', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-editor-ul',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-gsection', $args );
}

add_action( 'ignition_deactivated', 'ignition_module_gsection_unregister_cpt' );
/**
 * Unregisters the Global Section post type.
 *
 * @since 1.2.0
 */
function ignition_module_gsection_unregister_cpt() {
	unregister_post_type( 'ignition-gsection' );
}

// Remove from widgets' lists of post types.
add_filter( 'ignition_widget_post_types_dropdown_excluded', 'ignition_module_gsection_add_cpt_to_array' );

/**
 * Helper function that merges the post type name into a list of post types.
 *
 * Used to easily add the post type in a list of post types via filters.
 *
 * @since 1.2.0
 *
 * @param string[] $post_types
 *
 * @return array
 */
function ignition_module_gsection_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-gsection' ) );
}

add_action( 'before_action_ignition_main_container_before', 'ignition_module_gsection_wrap_main_container_action_open' );
add_action( 'before_action_ignition_main_container_after', 'ignition_module_gsection_wrap_main_container_action_open' );
/**
 * Opens container elements before the ignition_main_container_* actions.
 *
 * @since 1.3.0
 *
 * @param string $tag Action name.
 */
function ignition_module_gsection_wrap_main_container_action_open( $tag ) {
	$container_class = str_replace( '_', '-', "container-{$tag}" );
	$wrapper_class   = str_replace( '_', '-', $tag );
	?>
	<div class="row <?php echo esc_attr( $container_class ); ?>">
		<div class="col-12">
			<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
}

add_action( 'after_action_ignition_main_container_before', 'ignition_module_gsection_wrap_main_container_action_close' );
add_action( 'after_action_ignition_main_container_after', 'ignition_module_gsection_wrap_main_container_action_close' );
/**
 * Closes container elements after the ignition_main_container_* actions.
 *
 * @since 1.3.0
 *
 * @param string $tag Action name.
 */
function ignition_module_gsection_wrap_main_container_action_close( $tag ) {
	?>
			</div>
		</div>
	</div>
	<?php
}

require_once untrailingslashit( __DIR__ ) . '/functions.php';
require_once untrailingslashit( __DIR__ ) . '/custom-fields.php';
require_once untrailingslashit( __DIR__ ) . '/shortcodes.php';
