<?php
/**
 * Event module hooks and functions
 *
 * @since 1.0.0
 */

add_action( 'init', 'ignition_module_event_create_cpt' );
add_action( 'ignition_activated', 'ignition_module_event_create_cpt' );
/**
 * Registers the Event post type and taxonomy.
 *
 * @since 1.0.0
 */
function ignition_module_event_create_cpt() {
	$labels = array(
		'name'               => esc_html_x( 'Events', 'post type general name', 'ignition' ),
		'singular_name'      => esc_html_x( 'Event', 'post type singular name', 'ignition' ),
		'menu_name'          => esc_html_x( 'Events', 'admin menu', 'ignition' ),
		'name_admin_bar'     => esc_html_x( 'Event', 'add new on admin bar', 'ignition' ),
		'add_new'            => esc_html_x( 'Add New', 'event', 'ignition' ),
		'add_new_item'       => esc_html__( 'Add New Event', 'ignition' ),
		'edit_item'          => esc_html__( 'Edit Event', 'ignition' ),
		'new_item'           => esc_html__( 'New Event', 'ignition' ),
		'view_item'          => esc_html__( 'View Event', 'ignition' ),
		'search_items'       => esc_html__( 'Search Events', 'ignition' ),
		'not_found'          => esc_html__( 'No Events found', 'ignition' ),
		'not_found_in_trash' => esc_html__( 'No Events found in the trash', 'ignition' ),
		'parent_item_colon'  => esc_html__( 'Parent Event:', 'ignition' ),
	);

	$args = array(
		'labels'          => $labels,
		'singular_label'  => esc_html_x( 'Event', 'post type singular name', 'ignition' ),
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => false,
		'rewrite'         => array( 'slug' => esc_html_x( 'event', 'post type slug', 'ignition' ) ),
		'menu_position'   => 10,
		'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'       => 'dashicons-location-alt',
		'show_in_rest'    => true,
	);

	register_post_type( 'ignition-event', $args );

	$labels = array(
		'name'              => esc_html_x( 'Event Categories', 'taxonomy general name', 'ignition' ),
		'singular_name'     => esc_html_x( 'Event Category', 'taxonomy singular name', 'ignition' ),
		'search_items'      => esc_html__( 'Search Event Categories', 'ignition' ),
		'all_items'         => esc_html__( 'All Event Categories', 'ignition' ),
		'parent_item'       => esc_html__( 'Parent Event Category', 'ignition' ),
		'parent_item_colon' => esc_html__( 'Parent Event Category:', 'ignition' ),
		'edit_item'         => esc_html__( 'Edit Event Category', 'ignition' ),
		'update_item'       => esc_html__( 'Update Event Category', 'ignition' ),
		'add_new_item'      => esc_html__( 'Add New Event Category', 'ignition' ),
		'new_item_name'     => esc_html__( 'New Event Category Name', 'ignition' ),
		'menu_name'         => esc_html__( 'Categories', 'ignition' ),
		'view_item'         => esc_html__( 'View Event Category', 'ignition' ),
		'popular_items'     => esc_html__( 'Popular Event Categories', 'ignition' ),
	);
	register_taxonomy( 'ignition_event_category', array( 'ignition-event' ), array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => esc_html_x( 'event-category', 'taxonomy slug', 'ignition' ) ),
		'show_in_rest'      => true,
	) );
}

add_action( 'ignition_deactivated', 'ignition_module_event_unregister_cpt' );
/**
 * Unregisters the Event post type and taxonomy.
 *
 * @since 1.0.0
 */
function ignition_module_event_unregister_cpt() {
	unregister_post_type( 'ignition-event' );
	unregister_taxonomy( 'ignition_event_category' );
}

// Add Page Title Options for post type.
add_filter( 'ignition_single_page_title_post_types', 'ignition_module_event_add_cpt_to_array' );
// Add Featured Image Visibility Options for post type.
add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_module_event_add_cpt_to_array' );
// Add Remove Main Margin option for post type.
add_filter( 'ignition_single_remove_main_padding_post_types', 'ignition_module_event_add_cpt_to_array' );
// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_module_event_add_cpt_to_array' );
// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_module_event_add_tax_to_array' );

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
function ignition_module_event_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'ignition-event' ) );
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
function ignition_module_event_add_tax_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'ignition_event_category' ) );
}

add_filter( 'ignition_main_widget_areas', 'ignition_module_event_add_widget_area' );
/**
 * Registers module-specific sidebars.
 *
 * @since 1.0.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_module_event_add_widget_area( $main_sidebars ) {
	$main_sidebars = array_merge( $main_sidebars, array(
		'event' => array(
			'name'          => esc_html__( 'Events', 'ignition' ),
			'id'            => 'event',
			'description'   => esc_html__( 'Widgets added here will appear on event pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	return $main_sidebars;
}

add_filter( 'ignition_current_sidebar_id', 'ignition_module_event_current_sidebar_id' );
/**
 * Filters the sidebar id that should be used for the module's pages.
 *
 * @see ignition_get_current_sidebar_id()
 *
 * @param string $sidebar_id
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_module_event_current_sidebar_id( $sidebar_id ) {
	$post_type  = 'ignition-event';
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	if ( is_singular( $post_type ) || is_tax( $taxonomies ) ) {
		$sidebar_id = 'event';
	}

	return $sidebar_id;
}

add_action( 'admin_init', 'ignition_setup_event_settings_metabox' );
/**
 * Registers the Event Settings metabox.
 *
 * @since 1.0.0
 */
function ignition_setup_event_settings_metabox() {
	add_meta_box( 'ignition-event-settings', esc_html__( 'Event Settings', 'ignition' ), 'ignition_event_settings_metabox', 'ignition-event', 'side', 'default' );

	add_action( 'save_post', 'ignition_event_settings_save_post' );
}

/**
 * Displays the "Event Settings" metabox contents.
 *
 * @since 1.0.0
 *
 * @param WP_Post $object
 * @param array $box
 */
function ignition_event_settings_metabox( $object, $box ) {
	// Nonce generated inside ignition_prepare_metabox()
	ignition_prepare_metabox( $object->post_type );

	ignition_side_metabox_checkbox( 'ignition_event_is_recurring', array(
		'title'         => __( 'Event is recurring', 'ignition' ),
		'checked_value' => 1,
		'default'       => '',
	) );

	$description = '';
	if ( current_theme_supports( 'ignition-event', 'date-fragments' ) ) {
		/* translators: %s is a multipart recurrence string example. */
		$description = sprintf( __( 'The theme displays the event date with a special format. You can wrap part of the text in the event\'s recurrence with a &lt;span&gt; in order to display it in a similar fashion. E.g.: <code>%s</code>', 'ignition' ),
			esc_html_x( '<span>Every Other</span> Tuesday', 'multipart recurrent event example', 'ignition' )
		);
	}

	?><div class="ignition-side-setting-wrap ignition-event-meta-fields-recurring"><?php
		ignition_side_metabox_input( 'ignition_event_recurrence', array(
			'title'       => __( 'Event recurrence', 'ignition' ),
			'description' => $description,
		) );
	?></div><?php

	?><div class="ignition-side-setting-wrap ignition-event-meta-fields-dated"><?php
		ignition_side_metabox_input( 'ignition_event_date', array(
			'title'       => __( 'Event Date', 'ignition' ),
			'description' => __( 'Make sure the date follows the <em>yyyy-mm-dd</em> format. It will be automatically re-formatted according to your locale settings.', 'ignition' ),
			'input_class' => 'ignition-datepicker',
		) );
	?></div><?php

		ignition_side_metabox_input( 'ignition_event_time', array(
			'title' => __( 'Event Time', 'ignition' ),
		) );

	ignition_side_metabox_input( 'ignition_event_location', array(
		'title' => __( 'Event Location', 'ignition' ),
	) );
}

/**
 * Stores the "Event Settings" post meta.
 *
 * @since 1.0.0
 *
 * @param int $post_id
 */
function ignition_event_settings_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	$is_recurring = isset( $_POST['ignition_event_is_recurring'] );
	update_post_meta( $post_id, 'ignition_event_is_recurring', $is_recurring );

	if ( $is_recurring ) {
		// Since it's a recurring event, we need to delete date information, so that it won't interfere with custom queries.
		delete_post_meta( $post_id, 'ignition_event_date' );

		if ( isset( $_POST['ignition_event_recurrence'] ) ) {
			update_post_meta( $post_id, 'ignition_event_recurrence', wp_kses( $_POST['ignition_event_recurrence'], array(
				'span' => array( 'class' => true ),
			) ) );
		}
	} else {
		delete_post_meta( $post_id, 'ignition_event_recurrence' );

		if ( isset( $_POST['ignition_event_date'] ) ) {
			update_post_meta( $post_id, 'ignition_event_date', sanitize_text_field( $_POST['ignition_event_date'] ) );
		}
	}

	if ( isset( $_POST['ignition_event_time'] ) ) {
		update_post_meta( $post_id, 'ignition_event_time', sanitize_text_field( $_POST['ignition_event_time'] ) );
	}

	if ( isset( $_POST['ignition_event_location'] ) ) {
		update_post_meta( $post_id, 'ignition_event_location', sanitize_text_field( $_POST['ignition_event_location'] ) );
	}

	// phpcs:enable
}

add_action( 'pre_get_posts', 'ignition_taxonomy_pre_get_posts_order_events' );
/**
 * Orders event taxonomy archives by the event date.
 *
 * @since 1.0.0
 *
 * @param WP_Query $query
 */
function ignition_taxonomy_pre_get_posts_order_events( $query ) {
	// We don't want to mess other post types.
	if ( 'ignition-event' !== $query->get( 'post_type' ) && ! $query->is_tax( 'ignition_event_category' ) ) {
		return;
	}

	// Don't affect the admin listing screen.
	if ( is_admin() && get_current_screen() && 'edit' === get_current_screen()->base && 'ignition-event' === get_current_screen()->post_type ) {
		return;
	}

	// Don't affect the main singular query.
	if ( $query->is_singular() && $query->is_main_query() ) {
		return;
	}

	$order = $query->get( 'order' );
	if ( empty( $order ) ) {
		$order = 'DESC';
	}

	// We should only affect queries without any explicit orderby parameters.
	$orderby = $query->get( 'orderby' );

	$ignition_event_query = $query->get( 'ignition_event_query' );

	if ( ! empty( $ignition_event_query ) ) {
		if ( 'recurring' === $ignition_event_query ) {
			$query->set( 'meta_query', array(
				array(
					'key'     => 'ignition_event_is_recurring',
					'value'   => 1,
					'compare' => '=',
				),
			) );

			if ( empty( $orderby ) ) {
				$query->set( 'orderby', '' );
				$query->set( 'order', $order );
			}
		} elseif ( 'future' === $ignition_event_query ) {
			$query->set( 'meta_query', array(
				'relation'    => 'AND',
				'date_clause' => array(
					'key'     => 'ignition_event_date',
					'value'   => date_i18n( 'Y-m-d' ),
					'compare' => '>=',
					'type'    => 'DATE',
				),
				'time_clause' => array(
					'key'     => 'ignition_event_time',
					'compare' => 'EXISTS',
					'type'    => 'TIME',
				),
			) );

			if ( empty( $orderby ) ) {
				$query->set( 'orderby', array(
					'date_clause' => $order,
					'time_clause' => $order,
				) );
			}
		} elseif ( 'past' === $ignition_event_query ) {
			$query->set( 'meta_query', array(
				'relation'    => 'AND',
				'date_clause' => array(
					'key'     => 'ignition_event_date',
					'value'   => date_i18n( 'Y-m-d' ),
					'compare' => '<',
					'type'    => 'DATE',
				),
				'time_clause' => array(
					'key'     => 'ignition_event_time',
					'compare' => 'EXISTS',
					'type'    => 'TIME',
				),
			) );

			if ( empty( $orderby ) ) {
				$query->set( 'orderby', array(
					'date_clause' => $order,
					'time_clause' => $order,
				) );
			}
		}
	} else {
		$query->set( 'meta_query', array(
			'relation'          => 'OR',
			'date_order_clause' => array(
				'key'     => 'ignition_event_date',
				'compare' => 'EXISTS',
				'type'    => 'DATE',
			),
			'time_order_clause' => array(
				'key'     => 'ignition_event_time',
				'compare' => 'EXISTS',
				'type'    => 'TIME',
			),
			'recurring_clause'  => array(
				'key'     => 'ignition_event_is_recurring',
				'compare' => 'EXISTS',
				'type'    => 'NUMERIC',
			),
		) );

		if ( empty( $orderby ) ) {
			$query->set( 'orderby', array(
				'recurring_clause'    => 'DESC',
				'date_order_clause'   => $order,
				'ignition_event_date' => $order,
				'time_order_clause'   => $order,
			) );
		}
	}
}
