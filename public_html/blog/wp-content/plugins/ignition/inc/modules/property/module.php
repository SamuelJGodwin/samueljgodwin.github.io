<?php
/**
 * Property module hooks and functions
 *
 * @since 2.2.0
 */

add_action( 'init', 'ignition_module_property_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_property_create_cpt' );
/**
 * Registers the Property post type and taxonomy.
 *
 * @since 2.2.0
 */
function ignition_module_property_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Properties', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Property', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Properties', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Property', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'property', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Property', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Property', 'ignition' ),
		'new_item'           => esc_html__( 'New Property', 'ignition' ),
		'view_item'          => esc_html__( 'View Property', 'ignition' ),
		'search_items'       => esc_html__( 'Search Properties', 'ignition' ),
		'not_found'          => esc_html__( 'No Properties found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Properties found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Property:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Property', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'property', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-admin-home',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-property', $args );

	$labels = array(
		'name'              => _x( 'Property Types', 'taxonomy general name', 'ignition' ),
		'singular_name'     => _x( 'Property Type', 'taxonomy singular name', 'ignition' ),
		'search_items'      => __( 'Search Types', 'ignition' ),
		'all_items'         => __( 'All Types', 'ignition' ),
		'parent_item'       => __( 'Parent Type', 'ignition' ),
		'parent_item_colon' => __( 'Parent Type:', 'ignition' ),
		'edit_item'         => __( 'Edit Type', 'ignition' ),
		'update_item'       => __( 'Update Type', 'ignition' ),
		'add_new_item'      => __( 'Add New Type', 'ignition' ),
		'new_item_name'     => __( 'New Type Name', 'ignition' ),
		'menu_name'         => __( 'Types', 'ignition' ),
		'view_item'         => __( 'View Type', 'ignition' ),
		'popular_items'     => __( 'Popular Types', 'ignition' ),
	);
	register_taxonomy( 'ignition_property_type', array( 'ignition-property' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => _x( 'property-type', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );

	$labels = array(
		'name'              => _x( 'Property Statuses', 'taxonomy general name', 'ignition' ),
		'singular_name'     => _x( 'Property Status', 'taxonomy singular name', 'ignition' ),
		'search_items'      => __( 'Search Statuses', 'ignition' ),
		'all_items'         => __( 'All Statuses', 'ignition' ),
		'parent_item'       => __( 'Parent Status', 'ignition' ),
		'parent_item_colon' => __( 'Parent Status:', 'ignition' ),
		'edit_item'         => __( 'Edit Status', 'ignition' ),
		'update_item'       => __( 'Update Status', 'ignition' ),
		'add_new_item'      => __( 'Add New Status', 'ignition' ),
		'new_item_name'     => __( 'New Status Name', 'ignition' ),
		'menu_name'         => __( 'Statuses', 'ignition' ),
		'view_item'         => __( 'View Status', 'ignition' ),
		'popular_items'     => __( 'Popular Statuses', 'ignition' ),
	);
	register_taxonomy( 'ignition_property_status', array( 'ignition-property' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => _x( 'property-status', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );

	$labels = array(
		'name'              => _x( 'Property Amenities', 'taxonomy general name', 'ignition' ),
		'singular_name'     => _x( 'Property Amenity', 'taxonomy singular name', 'ignition' ),
		'search_items'      => __( 'Search Amenities', 'ignition' ),
		'all_items'         => __( 'All Amenities', 'ignition' ),
		'parent_item'       => __( 'Parent Amenity', 'ignition' ),
		'parent_item_colon' => __( 'Parent Amenity:', 'ignition' ),
		'edit_item'         => __( 'Edit Amenity', 'ignition' ),
		'update_item'       => __( 'Update Amenity', 'ignition' ),
		'add_new_item'      => __( 'Add New Amenity', 'ignition' ),
		'new_item_name'     => __( 'New Amenity Name', 'ignition' ),
		'menu_name'         => __( 'Amenities', 'ignition' ),
		'view_item'         => __( 'View Amenity', 'ignition' ),
		'popular_items'     => __( 'Popular Amenities', 'ignition' ),
	);
	register_taxonomy( 'ignition_property_amenity', array( 'ignition-property' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => _x( 'property-amenity', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );

	$labels = array(
		'name'              => _x( 'Property Locations', 'taxonomy general name', 'ignition' ),
		'singular_name'     => _x( 'Property Location', 'taxonomy singular name', 'ignition' ),
		'search_items'      => __( 'Search Locations', 'ignition' ),
		'all_items'         => __( 'All Locations', 'ignition' ),
		'parent_item'       => __( 'Parent Location', 'ignition' ),
		'parent_item_colon' => __( 'Parent Location:', 'ignition' ),
		'edit_item'         => __( 'Edit Location', 'ignition' ),
		'update_item'       => __( 'Update Location', 'ignition' ),
		'add_new_item'      => __( 'Add New Location', 'ignition' ),
		'new_item_name'     => __( 'New Location Name', 'ignition' ),
		'menu_name'         => __( 'Locations', 'ignition' ),
		'view_item'         => __( 'View Location', 'ignition' ),
		'popular_items'     => __( 'Popular Locations', 'ignition' ),
	);
	register_taxonomy( 'ignition_property_location', array( 'ignition-property' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => _x( 'property-location', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );

	$labels = array(
		'name'              => _x( 'Extra Attributes', 'taxonomy general name', 'ignition' ),
		'singular_name'     => _x( 'Extra Attribute', 'taxonomy singular name', 'ignition' ),
		'search_items'      => __( 'Search Attributes', 'ignition' ),
		'all_items'         => __( 'All Attributes', 'ignition' ),
		'parent_item'       => __( 'Parent Attribute', 'ignition' ),
		'parent_item_colon' => __( 'Parent Attribute:', 'ignition' ),
		'edit_item'         => __( 'Edit Attribute', 'ignition' ),
		'update_item'       => __( 'Update Attribute', 'ignition' ),
		'add_new_item'      => __( 'Add New Attribute', 'ignition' ),
		'new_item_name'     => __( 'New Attribute Name', 'ignition' ),
		'menu_name'         => __( 'Attributes', 'ignition' ),
		'view_item'         => __( 'View Attribute', 'ignition' ),
		'popular_items'     => __( 'Popular Attributes', 'ignition' ),
	);
	register_taxonomy( 'ignition_property_attribute', array( 'ignition-property' ), array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'show_admin_column' => false,
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'meta_box_cb'       => false,
		'rewrite'           => array( 'slug' => _x( 'property-attribute', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => false,
	) );

	$labels = array(
		'name'              => _x( 'Extra Useful Data', 'taxonomy general name', 'ignition' ),
		'singular_name'     => _x( 'Extra Useful Data', 'taxonomy singular name', 'ignition' ),
		'search_items'      => __( 'Search Useful Data', 'ignition' ),
		'all_items'         => __( 'All Useful Data', 'ignition' ),
		'parent_item'       => __( 'Parent Useful Data', 'ignition' ),
		'parent_item_colon' => __( 'Parent Useful Data:', 'ignition' ),
		'edit_item'         => __( 'Edit Useful Data', 'ignition' ),
		'update_item'       => __( 'Update Useful Data', 'ignition' ),
		'add_new_item'      => __( 'Add New Useful Data', 'ignition' ),
		'new_item_name'     => __( 'New Useful Data Name', 'ignition' ),
		'menu_name'         => __( 'Useful Data', 'ignition' ),
		'view_item'         => __( 'View Useful Data', 'ignition' ),
		'popular_items'     => __( 'Popular Useful Data', 'ignition' ),
	);
	register_taxonomy( 'ignition_property_useful_data', array( 'ignition-property' ), array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'show_admin_column' => false,
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'meta_box_cb'       => false,
		'rewrite'           => array( 'slug' => _x( 'property-useful-data', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => false,
	) );
}

add_action( 'ignition_deactivated', 'ignition_module_property_unregister_cpt' );
/**
 * Unregisters the Property post type and taxonomy.
 *
 * @since 2.2.0
 */
function ignition_module_property_unregister_cpt() {
	unregister_post_type( 'ignition-property' );
	unregister_taxonomy( 'ignition_property_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_property_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_property_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_property_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_property_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_property_add_tax_to_array' );

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
function ignition_module_property_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-property' ) );
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
function ignition_module_property_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_property_category' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_property_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 2.2.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_property_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'property' => array(
			'name'          => esc_html__( 'Properties', 'ignition' ),
			'id'            => 'property',
			'description'   => esc_html__( 'Widgets added here will appear on property pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_property_current_sidebar_id' );
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
function ignition_module_property_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-property';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'property';
	}

	return $sidebar_id;
}

/**
 * Returns a list of valid extra property field types and their labels.
 *
 * @since 2.2.0
 *
 * @return array
 */
function ignition_get_property_extra_field_types() {
	return array(
		''         => esc_html__( 'Text', 'ignition' ),
		'html'     => esc_html__( 'HTML', 'ignition' ),
		'int'      => esc_html__( 'Number (integer)', 'ignition' ),
		'float'    => esc_html__( 'Number (decimal)', 'ignition' ),
		'percent'  => esc_html__( 'Percentage (0-100%)', 'ignition' ),
		'progress' => esc_html__( 'Progress bar (0-100%)', 'ignition' ),
	);
}

/**
 * Sanitizes an extra property field type value.
 *
 * @since 2.2.0
 *
 * @return string
 */
function ignition_sanitize_property_extra_field_type( $value ) {
	$choices = ignition_get_property_extra_field_types();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return '';
}

/**
 * Outputs the an extra field's markup.
 *
 * @since 2.2.0
 *
 * @param string  $meta_key
 * @param WP_Term $term
 * @param string  $field_type
 */
function ignition_property_extra_field_form_input( $meta_key, $term, $field_type ) {
	switch ( $field_type ) {
		case 'html':
			ignition_main_metabox_textarea( $meta_key, array(
				'title' => $term->name,
			) );
			break;
		case 'int':
			ignition_main_metabox_input( $meta_key, array(
				'title'       => $term->name,
				'description' => __( 'Integer, e.g. <em>5</em>', 'ignition' ),
				'type'        => 'number',
			) );
			break;
		case 'float':
			ignition_main_metabox_input( $meta_key, array(
				'title'       => $term->name,
				'description' => __( 'Decimal, e.g. <em>5.67</em> - up to two decimal places.', 'ignition' ),
				'type'        => 'number',
				'input_attrs' => array(
					'step' => '0.01',
				),
			) );
			break;
		case 'percent':
			ignition_main_metabox_input( $meta_key, array(
				'title'       => $term->name,
				'description' => __( 'Progress 0-100, e.g. <em>52</em>', 'ignition' ),
				'type'        => 'number',
				'input_attrs' => array(
					'min' => '0',
					'max' => '100',
				),
			) );
			break;
		case 'progress':
			ignition_main_metabox_input( $meta_key, array(
				'title'       => $term->name,
				'description' => __( 'Percentage 0-100, e.g. <em>52</em>', 'ignition' ),
				'type'        => 'number',
				'input_attrs' => array(
					'min' => '0',
					'max' => '100',
				),
			) );
			break;
		case '':
		default:
			ignition_main_metabox_input( $meta_key, array(
				'title' => $term->name,
			) );
	}
}

/**
 * Sanitizes an extra property field's value, depending on the type of the field.
 *
 * @since 2.2.0
 *
 * @param mixed  $value
 * @param string $field_type
 *
 * @return string
 */
function ignition_sanitize_property_extra_field( $value, $field_type ) {
	$original_value = $value;

	switch ( $field_type ) {
		case 'html':
			$value = wp_kses_post( $value );
			break;
		case 'int':
			$value = ignition_sanitize_intval_or_empty( $value );
			break;
		case 'float':
			$value = ignition_sanitize_floatval_or_empty( $value );
			break;
		case 'percent':
		case 'progress':
			$value = str_replace( '%', '', $value );
			$value = ignition_sanitize_int_percentage_or_empty( $value );
			if ( '' !== $value ) {
				$value = $value < 0 ? 0 : $value;
				$value = $value > 100 ? 100 : $value;
			}
			break;
		case '':
		default:
			$value = sanitize_text_field( $value );
	}

	return apply_filters( 'ignition_sanitize_property_extra_field', $value, $field_type, $original_value );
}

/**
 * Outputs the appropriate front-end markup for an extra field.
 *
 * @since 2.2.0
 *
 * @param int     $post_id
 * @param WP_Term $term
 * @param string  $taxonomy
 */
function ignition_property_print_extra_field( $post_id, $term, $taxonomy ) {
	$meta_key   = "{$taxonomy}_{$term->slug}";
	$field_type = get_term_meta( $term->term_id, 'field_type', true );
	$value      = get_post_meta( $post_id, $meta_key, true );

	if ( ! $value ) {
		return;
	}

	?>
	<tr>
		<th><?php
			/* translators: %s is a property's field name, e.g. Garages or Crime Rate */
			echo esc_html( sprintf( _x( '%s:', 'property field name', 'ignition' ), $term->name ) );
		?></th>
		<td>
			<?php
				switch ( $field_type ) {
					case 'html':
						echo wp_kses_post( $value );
						break;
					case 'percent':
						echo esc_html( $value . '%' );
						break;
					case 'progress':
						?>
						<span class="ignition-percentage">
							<span class="ignition-percentage-bar" style="width: <?php echo esc_attr( $value . '%' ); ?>;"></span>
						</span>
						<?php
						break;
					case '':
					case 'int':
					case 'float':
					default:
						echo esc_html( $value );
				}
			?>
		</td>
	</tr>
	<?php
}

require_once untrailingslashit( __DIR__ ) . '/custom-fields.php';
require_once untrailingslashit( __DIR__ ) . '/term-meta.php';
