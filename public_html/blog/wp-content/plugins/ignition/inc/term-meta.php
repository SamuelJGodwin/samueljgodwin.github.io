<?php
/**
 * Term meta fields
 *
 * @since 1.0.0
 */

add_action( 'admin_init', 'ignition_register_term_meta_fields' );
/**
 * Registers term meta callbacks.
 *
 * @since 1.0.0
 */
function ignition_register_term_meta_fields() {
	$page_title_taxonomies = ignition_get_page_title_image_taxonomies();
	if ( $page_title_taxonomies ) {
		foreach ( $page_title_taxonomies as $taxonomy ) {
			add_action( "{$taxonomy}_add_form_fields", 'ignition_term_meta_page_title_image_add_fields', 10 );
			add_action( "{$taxonomy}_edit_form_fields", 'ignition_term_meta_page_title_image_edit_fields', 10, 2 );
		}

		add_action( 'create_term', 'ignition_term_meta_page_title_image_save_fields', 10, 3 );
		add_action( 'edit_term', 'ignition_term_meta_page_title_image_save_fields', 10, 3 );
	}

	$cover_image_taxonomies = ignition_get_cover_image_taxonomies();
	if ( $cover_image_taxonomies ) {
		foreach ( $cover_image_taxonomies as $taxonomy ) {
			add_action( "{$taxonomy}_add_form_fields", 'ignition_term_meta_cover_image_add_fields', 10 );
			add_action( "{$taxonomy}_edit_form_fields", 'ignition_term_meta_cover_image_edit_fields', 10, 2 );
		}

		add_action( 'create_term', 'ignition_term_meta_cover_image_save_fields', 10, 3 );
		add_action( 'edit_term', 'ignition_term_meta_cover_image_save_fields', 10, 3 );
	}
}

//
// Page Title Image
//

/**
 * Returns the taxonomies whose terms have the Page Title Image option.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_page_title_image_taxonomies() {
	$taxonomies = array(
		'category',
		'post_tag',
	);

	/**
	 * Filters the taxonomies where the Page Title Image meta are available.
	 *
	 * @since 1.0.0
	 *
	 * @param array $taxonomies
	 *
	 * @hooked ignition_woocommerce_add_taxonomies_to_array - 10
	 * @hooked ignition_module_accommodation_add_tax_to_array - 10
	 * @hooked ignition_module_discography_add_tax_to_array - 10
	 * @hooked ignition_module_event_add_tax_to_array - 10
	 * @hooked ignition_module_package_add_tax_to_array - 10
	 * @hooked ignition_module_package_add_destination_tax_to_array - 10
	 * @hooked ignition_module_podcast_add_tax_to_array - 10
	 * @hooked ignition_module_portfolio_add_tax_to_array - 10
	 * @hooked ignition_module_service_add_tax_to_array - 10
	 * @hooked ignition_module_team_add_tax_to_array - 10
	 * @hooked ignition_module_property_add_tax_to_array - 10
	 */
	$taxonomies = apply_filters( 'ignition_page_title_image_taxonomies', $taxonomies );

	if ( ! current_theme_supports( 'ignition-page-title-with-background' ) ) {
		$taxonomies = array();
	}

	return $taxonomies;
}

/**
 * Outputs the Page Title Image meta markup for new terms.
 *
 * @since 1.0.0
 *
 * @param string $taxonomy Current taxonomy slug.
 */
function ignition_term_meta_page_title_image_add_fields( $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<div class="form-field">
		<label for="page_title_colors_background_image"><?php esc_html_e( 'Page Title Image', 'ignition' ); ?></label>
		<?php
			ignition_side_metabox_image( 'page_title_colors_background_image', array(
				'value'  => ignition_image_bg_control_defaults(),
				'labels' => array(
					'no_image'     => __( 'Set page title image', 'ignition' ),
					'remove_image' => __( 'Remove page title image', 'ignition' ),
				),
			) );
		?>
	</div>
	<div class="form-field">
		<label for="page_title_colors_background_video"><?php esc_html_e( 'Page Title Video', 'ignition' ); ?></label>
		<?php
			ignition_side_metabox_file_select( 'page_title_colors_background_video', array(
				'file_type' => 'video',
			) );

			ignition_side_metabox_checkbox( 'page_title_colors_background_video_disabled', array(
				'title' => __( 'Disable background video', 'ignition' ),
			) );
		?>
	</div>
	<?php
}

/**
 * Outputs the Page Title Image meta markup for existing terms.
 *
 * @since 1.0.0
 *
 * @param WP_Term $term     Current taxonomy term object.
 * @param string  $taxonomy Current taxonomy slug.
 */
function ignition_term_meta_page_title_image_edit_fields( $term, $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<tr class="form-field">
		<th scope="row"><label for="page_title_colors_background_image"><?php esc_html_e( 'Page Title Image', 'ignition' ); ?></label></th>
		<td>
			<?php
				$value = ignition_get_term_meta( $term->term_id, 'page_title_colors_background_image', ignition_image_bg_control_defaults() );
				// $value may be an empty string, due to inheritance reasons.
				$value = ! empty( $value ) ? $value : ignition_image_bg_control_defaults();

				ignition_side_metabox_image( 'page_title_colors_background_image', array(
					'value'  => $value,
					'labels' => array(
						'no_image'     => __( 'Set page title image', 'ignition' ),
						'remove_image' => __( 'Remove page title image', 'ignition' ),
					),
				) );
			?>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="page_title_colors_background_video"><?php esc_html_e( 'Page Title Video', 'ignition' ); ?></label></th>
		<td>
			<?php
				ignition_side_metabox_file_select( 'page_title_colors_background_video', array(
					'value'     => ignition_get_term_meta( $term->term_id, 'page_title_colors_background_video', '' ),
					'file_type' => 'video',
				) );

				ignition_side_metabox_checkbox( 'page_title_colors_background_video_disabled', array(
					'title' => __( 'Disable background video', 'ignition' ),
					'value' => ignition_get_term_meta( $term->term_id, 'page_title_colors_background_video_disabled', '' ),
				) );
			?>
		</td>
	</tr>
	<?php
}

/**
 * Stores the Page Title Image term meta.
 *
 * @since 1.0.0
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function ignition_term_meta_page_title_image_save_fields( $term_id, $tt_id, $taxonomy ) {
	$taxonomies = ignition_get_page_title_image_taxonomies();

	if ( ! in_array( $taxonomy, $taxonomies, true ) ||
		! isset( $_POST['_ignition_fields_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_ignition_fields_nonce'] ) ), 'ignition_term_meta_category_fields_nonce' )
	) {
		return;
	}

	if ( isset( $_POST['page_title_colors_background_image'] ) ) {
		// Make sure we only store useful values, or empty string to flag that it should be inherited.
		$image = ignition_sanitize_image_bg_control( stripslashes_deep( $_POST['page_title_colors_background_image'] ) );
		$image = ! ignition_are_image_bg_arrays_equal( $image, ignition_image_bg_control_defaults() ) ? $image : '';
		update_term_meta( $term_id, 'page_title_colors_background_image', $image );
	}

	if ( isset( $_POST['page_title_colors_background_video'] ) ) {
		update_term_meta( $term_id, 'page_title_colors_background_video', esc_url_raw( $_POST['page_title_colors_background_video'] ) );
	}
	update_term_meta( $term_id, 'page_title_colors_background_video_disabled', isset( $_POST['page_title_colors_background_video_disabled'] ) );
}

//
// Cover Image
//

/**
 * Returns the taxonomies whose terms have the Featured Image option.
 *
 * @since 2.0.0
 *
 * @return array
 */
function ignition_get_cover_image_taxonomies() {
	$taxonomies = array(
		'category',
		'post_tag',
	);

	/**
	 * Filters the taxonomies where the Cover Image meta are available.
	 *
	 * @since 2.0.0
	 *
	 * @param array $taxonomies
	 *
	 * @hooked ignition_module_package_add_destination_tax_to_array - 10
	 */
	$taxonomies = apply_filters( 'ignition_cover_image_taxonomies', $taxonomies );

	return $taxonomies;
}

/**
 * Outputs the Cover Image meta markup for new terms.
 *
 * @since 2.0.0
 *
 * @param string $taxonomy Current taxonomy slug.
 */
function ignition_term_meta_cover_image_add_fields( $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<div class="form-field">
		<label for="cover_image"><?php esc_html_e( 'Cover Image', 'ignition' ); ?></label>
		<?php
			ignition_side_metabox_image( 'cover_image', array(
				'value'  => ignition_image_bg_control_defaults(),
				'labels' => array(
					'no_image'     => __( 'Set cover image', 'ignition' ),
					'remove_image' => __( 'Remove cover image', 'ignition' ),
				),
			) );
		?>
	</div>
	<?php
}

/**
 * Outputs the Cover Image meta markup for existing terms.
 *
 * @since 2.0.0
 *
 * @param WP_Term $term     Current taxonomy term object.
 * @param string  $taxonomy Current taxonomy slug.
 */
function ignition_term_meta_cover_image_edit_fields( $term, $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<tr class="form-field">
		<th scope="row"><label for="cover_image"><?php esc_html_e( 'Cover Image', 'ignition' ); ?></label></th>
		<td>
			<?php
				$value = ignition_get_term_meta( $term->term_id, 'cover_image', ignition_image_bg_control_defaults() );
				// $value may be an empty string, due to inheritance reasons.
				$value = ! empty( $value ) ? $value : ignition_image_bg_control_defaults();

				ignition_side_metabox_image( 'cover_image', array(
					'value'  => $value,
					'labels' => array(
						'no_image'     => __( 'Set cover image', 'ignition' ),
						'remove_image' => __( 'Remove cover image', 'ignition' ),
					),
				) );
			?>
		</td>
	</tr>
	<?php
}

/**
 * Stores the Cover Image term meta.
 *
 * @since 2.0.0
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function ignition_term_meta_cover_image_save_fields( $term_id, $tt_id, $taxonomy ) {
	$taxonomies = ignition_get_cover_image_taxonomies();

	if ( ! in_array( $taxonomy, $taxonomies, true ) ||
		! isset( $_POST['_ignition_fields_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_ignition_fields_nonce'] ) ), 'ignition_term_meta_category_fields_nonce' )
	) {
		return;
	}

	if ( isset( $_POST['cover_image'] ) ) {
		// Make sure we only store useful values, or empty string to flag that it should be inherited.
		$image = ignition_sanitize_image_bg_control( stripslashes_deep( $_POST['cover_image'] ) );
		$image = ! ignition_are_image_bg_arrays_equal( $image, ignition_image_bg_control_defaults() ) ? $image : '';
		update_term_meta( $term_id, 'cover_image', $image );
	}
}
