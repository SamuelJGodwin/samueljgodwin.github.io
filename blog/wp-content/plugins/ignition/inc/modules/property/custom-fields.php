<?php
/**
 * Agent custom fields functions and definitions
 *
 * @since 2.2.0
 */

add_action( 'admin_init', 'ignition_module_property_setup_metabox' );
/**
 * Registers the Property metabox.
 *
 * @since 2.2.0
 */
function ignition_module_property_setup_metabox() {
	add_meta_box( 'ignition-single-property', esc_html__( 'Property Settings', 'ignition' ), 'ignition_module_property_metabox', 'ignition-property', 'normal', 'default' );

	add_action( 'save_post', 'ignition_module_property_save_post' );
}

/**
 * Displays the "Property Settings" metabox contents.
 *
 * @since 2.2.0
 *
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_module_property_metabox( $object, $box ) {
	ignition_prepare_metabox( 'ignition-property' );

	ignition_metabox_create_tabs( array(
		'details' => array(
			'title' => _x( 'Details', 'metabox tab title', 'ignition' ),
			'icon'  => 'dashicons dashicons-media-document',
			'tabs'  => array(
				'attributes'  => _x( 'Attributes', 'metabox tab title', 'ignition' ),
				'address'     => _x( 'Address', 'metabox tab title', 'ignition' ),
				'pricing'     => _x( 'Pricing', 'metabox tab title', 'ignition' ),
				'useful_data' => _x( 'Useful Data', 'metabox tab title', 'ignition' ),
			),
		),
	), 'property', $object, $box );
}


/**
 * Stores the "Property Settings" post meta.
 *
 * @since 2.2.0
 *
 * @param int $post_id
 */
function ignition_module_property_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	// Attributes
	if ( isset( $_POST['ignition_property_reference_id'] ) ) {
		update_post_meta( $post_id, 'ignition_property_reference_id', sanitize_text_field( $_POST['ignition_property_reference_id'] ) );
	}

	if ( isset( $_POST['ignition_property_area'] ) ) {
		update_post_meta( $post_id, 'ignition_property_area', ignition_sanitize_floatval_or_empty( $_POST['ignition_property_area'] ) );
	}

	if ( isset( $_POST['ignition_property_area_unit'] ) ) {
		update_post_meta( $post_id, 'ignition_property_area_unit', sanitize_text_field( $_POST['ignition_property_area_unit'] ) );
	}

	if ( isset( $_POST['ignition_property_bedrooms'] ) ) {
		update_post_meta( $post_id, 'ignition_property_bedrooms', ignition_sanitize_intval_or_empty( $_POST['ignition_property_bedrooms'] ) );
	}

	if ( isset( $_POST['ignition_property_bathrooms'] ) ) {
		update_post_meta( $post_id, 'ignition_property_bathrooms', ignition_sanitize_intval_or_empty( $_POST['ignition_property_bathrooms'] ) );
	}

	if ( isset( $_POST['ignition_property_garages'] ) ) {
		update_post_meta( $post_id, 'ignition_property_garages', ignition_sanitize_intval_or_empty( $_POST['ignition_property_garages'] ) );
	}

	$extra_attributes = get_terms( array(
		'taxonomy'   => 'ignition_property_attribute',
		'hide_empty' => false,
	) );
	foreach ( $extra_attributes as $attribute ) {
		$field_key = "ignition_property_attribute_{$attribute->slug}";
		if ( isset( $_POST[ $field_key ] ) ) {
			$field_type = get_term_meta( $attribute->term_id, 'field_type', true );
			update_post_meta( $post_id, $field_key, ignition_sanitize_property_extra_field( $_POST[ $field_key ], $field_type ) );
		}
	}

	// Address
	if ( isset( $_POST['ignition_property_open_house'] ) ) {
		update_post_meta( $post_id, 'ignition_property_open_house', sanitize_text_field( $_POST['ignition_property_open_house'] ) );
	}

	if ( isset( $_POST['ignition_property_address'] ) ) {
		update_post_meta( $post_id, 'ignition_property_address', sanitize_text_field( $_POST['ignition_property_address'] ) );
	}

	if ( isset( $_POST['ignition_property_postcode'] ) ) {
		update_post_meta( $post_id, 'ignition_property_postcode', sanitize_text_field( $_POST['ignition_property_postcode'] ) );
	}

	if ( isset( $_POST['ignition_property_map_lat'] ) ) {
		update_post_meta( $post_id, 'ignition_property_map_lat', sanitize_text_field( $_POST['ignition_property_map_lat'] ) );
	}

	if ( isset( $_POST['ignition_property_map_lon'] ) ) {
		update_post_meta( $post_id, 'ignition_property_map_lon', sanitize_text_field( $_POST['ignition_property_map_lon'] ) );
	}

	if ( isset( $_POST['ignition_property_map_zoom'] ) ) {
		update_post_meta( $post_id, 'ignition_property_map_zoom', intval( $_POST['ignition_property_map_zoom'] ) );
	}

	update_post_meta( $post_id, 'ignition_property_map_approximate', isset( $_POST['ignition_property_map_approximate'] ) );

	// Pricing
	$price      = '';
	$price_prev = '';

	if ( isset( $_POST['ignition_property_price_prefix'] ) ) {
		update_post_meta( $post_id, 'ignition_property_price_prefix', sanitize_text_field( $_POST['ignition_property_price_prefix'] ) );
	}

	if ( isset( $_POST['ignition_property_price'] ) ) {
		$price = ignition_sanitize_floatval_or_empty( $_POST['ignition_property_price'] );
		update_post_meta( $post_id, 'ignition_property_price', $price );
	}

	if ( isset( $_POST['ignition_property_price_suffix'] ) ) {
		update_post_meta( $post_id, 'ignition_property_price_suffix', sanitize_text_field( $_POST['ignition_property_price_suffix'] ) );
	}

	if ( isset( $_POST['ignition_property_price_previous'] ) ) {
		$price_prev = ignition_sanitize_floatval_or_empty( $_POST['ignition_property_price_previous'] );
		update_post_meta( $post_id, 'ignition_property_price_previous', $price_prev );
	}

	if ( isset( $_POST['ignition_property_price_freetext'] ) ) {
		update_post_meta( $post_id, 'ignition_property_price_freetext', sanitize_text_field( $_POST['ignition_property_price_freetext'] ) );
	}

	$price_diff = '';
	if ( ! empty( $price ) && ! empty( $price_prev ) ) {
		$price_diff = $price - $price_prev;
	}
	update_post_meta( $post_id, 'ignition_property_price_difference', $price_diff );

	// Useful data
	if ( isset( $_POST['ignition_property_crime_rate'] ) ) {
		update_post_meta( $post_id, 'ignition_property_crime_rate', ignition_sanitize_int_percentage_or_empty( $_POST['ignition_property_crime_rate'] ) );
	}

	if ( isset( $_POST['ignition_property_noise'] ) ) {
		update_post_meta( $post_id, 'ignition_property_noise', ignition_sanitize_int_percentage_or_empty( $_POST['ignition_property_noise'] ) );
	}

	if ( isset( $_POST['ignition_property_pollution'] ) ) {
		update_post_meta( $post_id, 'ignition_property_pollution', ignition_sanitize_int_percentage_or_empty( $_POST['ignition_property_pollution'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_city_center'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_city_center', sanitize_text_field( $_POST['ignition_property_distance_city_center'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_hospital'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_hospital', sanitize_text_field( $_POST['ignition_property_distance_hospital'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_shops'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_shops', sanitize_text_field( $_POST['ignition_property_distance_shops'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_police'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_police', sanitize_text_field( $_POST['ignition_property_distance_police'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_schools'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_schools', sanitize_text_field( $_POST['ignition_property_distance_schools'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_bus'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_bus', sanitize_text_field( $_POST['ignition_property_distance_bus'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_train'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_train', sanitize_text_field( $_POST['ignition_property_distance_train'] ) );
	}

	if ( isset( $_POST['ignition_property_distance_airport'] ) ) {
		update_post_meta( $post_id, 'ignition_property_distance_airport', sanitize_text_field( $_POST['ignition_property_distance_airport'] ) );
	}

	$extra_attributes = get_terms( array(
		'taxonomy'   => 'ignition_property_useful_data',
		'hide_empty' => false,
	) );
	foreach ( $extra_attributes as $attribute ) {
		$field_key = "ignition_property_useful_data_{$attribute->slug}";
		if ( isset( $_POST[ $field_key ] ) ) {
			$field_type = get_term_meta( $attribute->term_id, 'field_type', true );
			update_post_meta( $post_id, $field_key, ignition_sanitize_property_extra_field( $_POST[ $field_key ], $field_type ) );
		}
	}

	// phpcs:enable
}

/**
 * Produces the "Attributes" tab contents of the "Property Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 2.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_property_details_attributes( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {
	ignition_main_metabox_input( 'ignition_property_reference_id', array(
		'title' => __( 'Property ID', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_area', array(
		'title'       => __( 'Area', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => '0',
			'step' => '0.01',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_area_unit', array(
		'title'   => __( 'Area Unit', 'ignition' ),
		'default' => apply_filters( 'ignition_property_area_unit_default', 'm2' ),
	) );

	ignition_main_metabox_input( 'ignition_property_bedrooms', array(
		'title'       => __( 'Bedrooms', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min' => '0',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_bathrooms', array(
		'title'       => __( 'Bathrooms', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min' => '0',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_garages', array(
		'title'       => __( 'Garages', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min' => '0',
		),
	) );

	$taxonomy         = 'ignition_property_attribute';
	$extra_attributes = get_terms( array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => false,
	) );
	foreach ( $extra_attributes as $attribute ) {
		$meta_key   = "{$taxonomy}_{$attribute->slug}";
		$field_type = get_term_meta( $attribute->term_id, 'field_type', true );
		ignition_property_extra_field_form_input( $meta_key, $attribute, $field_type );
	}
}

/**
 * Produces the "Address" tab contents of the "Property Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 2.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_property_details_address( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {
	ignition_main_metabox_input( 'ignition_property_open_house', array(
		'title' => __( 'Open House', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_address', array(
		'title'       => __( 'Address', 'ignition' ),
		'description' => __( 'Publicly viewable', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_postcode', array(
		'title'       => __( 'ZIP / Post code', 'ignition' ),
		'description' => __( 'Publicly viewable', 'ignition' ),
	) );

	ignition_metabox_separator( array(
		'title'       => __( 'Map', 'ignition' ),
		'description' => __( 'Enter a place or address and press <em>Search place/address</em> to automatically create a point on the map. Alternatively, you can drag the marker to the desired position, or double click on the map to set a new location.', 'ignition' ),
	) );

	?>
	<fieldset class="gllpLatlonPicker">
		<div class="gllpSearch">
			<input type="text" class="gllpSearchField">
			<input type="button" class="button gllpSearchButton" value="<?php esc_attr_e( 'Search place/address', 'ignition' ); ?>" />
		</div>
		<div class="gllpMap"><?php esc_html_e( 'Google Maps', 'ignition' ); ?></div>
		<?php
			ignition_main_metabox_input( 'ignition_property_map_zoom', array(
				'type'        => 'hidden',
				'default'     => '8',
				'input_class' => 'gllpZoom',
			) );

			ignition_main_metabox_input( 'ignition_property_map_lat', array(
				'title'       => __( 'Location Latitude', 'ignition' ),
				'default'     => '36',
				'input_class' => 'widefat gllpLatitude',
			) );

			ignition_main_metabox_input( 'ignition_property_map_lon', array(
				'title'       => __( 'Location Longitude', 'ignition' ),
				'default'     => '-120',
				'input_class' => 'widefat gllpLongitude',
			) );
		?>
		<p><input type="button" class="button gllpUpdateButton" value="<?php esc_attr_e( 'Update map from coordinates', 'ignition' ); ?>" /></p>
	</fieldset>
	<?php

	ignition_main_metabox_checkbox( 'ignition_property_map_approximate', array(
		'title'       => __( 'Hide exact location', 'ignition' ),
		'description' => __( "You can conceal the exact location of the property by checking this box. Instead of a marker, a circular area (radius 200m) will be highlighted with the property placed unmarked someplace randomly within that area. For optimal results, the map's zoom level is set automatically.", 'ignition' ),
	) );
}

/**
 * Produces the "Pricing" tab contents of the "Property Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 2.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_property_details_pricing( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {
	ignition_main_metabox_input( 'ignition_property_price_prefix', array(
		'title'       => __( 'Price Prefix', 'ignition' ),
		'description' => __( 'Optional. Text to display before the price.', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_price', array(
		'title'       => __( 'Price', 'ignition' ),
		'description' => __( 'Required. Do not enter any currency symbols or thousand separators. You may use decimal numbers with a dot <code>.</code> as the decimal point. Price will be formatted properly according to your locale.', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => '0',
			'step' => '0.01',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_price_suffix', array(
		'title'       => __( 'Price Suffix', 'ignition' ),
		'description' => __( 'Optional. Text to display after the price.', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_price_previous', array(
		'title'       => __( 'Previous Price', 'ignition' ),
		'description' => __( 'Optional. Entering both the current and the previous price, allows for calculating special labels and percentages such as <strong>Reduced</strong>, <strong>Increased</strong>, etc.', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => '0',
			'step' => '0.01',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_price_freetext', array(
		'title'       => __( 'Free text', 'ignition' ),
		'description' => __( 'Optional. You may provide a one-liner (e.g. <code>Call for price</code>) to be displayed instead of the specially formatted price-related texts above. Overrides all price-related texts.', 'ignition' ),
	) );
}

/**
 * Produces the "Useful Data" tab contents of the "Property Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 2.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_property_details_useful_data( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {
	ignition_main_metabox_input( 'ignition_property_crime_rate', array(
		'title'       => __( 'Crime rate', 'ignition' ),
		'description' => __( '0% - 100%', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min' => '0',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_noise', array(
		'title'       => __( 'Noise', 'ignition' ),
		'description' => __( '0% - 100%', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min' => '0',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_pollution', array(
		'title'       => __( 'Pollution', 'ignition' ),
		'description' => __( '0% - 100%', 'ignition' ),
		'type'        => 'number',
		'input_attrs' => array(
			'min' => '0',
		),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_city_center', array(
		'title' => __( 'Distance from city center', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_hospital', array(
		'title' => __( 'Distance from hospital', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_shops', array(
		'title' => __( 'Distance from shops', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_police', array(
		'title' => __( 'Distance from police station', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_schools', array(
		'title' => __( 'Distance from schools', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_bus', array(
		'title' => __( 'Distance from bus station', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_train', array(
		'title' => __( 'Distance from train station', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_property_distance_airport', array(
		'title' => __( 'Distance from airport', 'ignition' ),
	) );

	$taxonomy         = 'ignition_property_useful_data';
	$extra_attributes = get_terms( array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => false,
	) );
	foreach ( $extra_attributes as $attribute ) {
		$meta_key   = "{$taxonomy}_{$attribute->slug}";
		$field_type = get_term_meta( $attribute->term_id, 'field_type', true );
		ignition_property_extra_field_form_input( $meta_key, $attribute, $field_type );
	}
}
