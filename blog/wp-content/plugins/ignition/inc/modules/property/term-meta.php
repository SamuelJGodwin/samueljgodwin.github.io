<?php
/**
 * Property-related term meta fields
 *
 * @since 2.2.0
 */

add_action( 'admin_init', 'ignition_module_property_register_term_meta_fields' );
/**
 * Registers term meta callbacks.
 *
 * @since 2.2.0
 */
function ignition_module_property_register_term_meta_fields() {
	$field_type_taxonomies = ignition_module_property_get_propery_field_type_taxonomies();
	if ( $field_type_taxonomies ) {
		foreach ( $field_type_taxonomies as $taxonomy ) {
			add_action( "{$taxonomy}_add_form_fields", 'ignition_module_property_term_meta_field_type_add_fields', 10 );
			add_action( "{$taxonomy}_edit_form_fields", 'ignition_module_property_term_meta_field_type_edit_fields', 10, 2 );
		}

		add_action( 'create_term', 'ignition_module_property_term_meta_field_type_save_fields', 10, 3 );
		add_action( 'edit_term', 'ignition_module_property_term_meta_field_type_save_fields', 10, 3 );
	}
}

//
// Extra Property Attributes / Extra Property Useful Data
//

/**
 * Returns the taxonomies whose terms have the Field Type option.
 *
 * @since 2.2.0
 *
 * @return array
 */
function ignition_module_property_get_propery_field_type_taxonomies() {
	$taxonomies = array(
		'ignition_property_attribute',
		'ignition_property_useful_data',
	);

	return $taxonomies;
}

/**
 * Outputs the Field Type meta markup for new terms.
 *
 * @since 2.2.0
 *
 * @param string $taxonomy Current taxonomy slug.
 */
function ignition_module_property_term_meta_field_type_add_fields( $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<div class="form-field">
		<label for="field_type"><?php esc_html_e( 'Field type', 'ignition' ); ?></label>
		<?php
			ignition_side_metabox_dropdown( 'field_type', array(
				'choices' => ignition_get_property_extra_field_types(),
			) );
		?>
	</div>
	<?php
}

/**
 * Outputs the Field Type meta markup for existing terms.
 *
 * @since 2.2.0
 *
 * @param WP_Term $term     Current taxonomy term object.
 * @param string  $taxonomy Current taxonomy slug.
 */
function ignition_module_property_term_meta_field_type_edit_fields( $term, $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<tr class="form-field">
		<th scope="row"><label for="field_type"><?php esc_html_e( 'Field type', 'ignition' ); ?></label></th>
		<td>
			<?php
				ignition_side_metabox_dropdown( 'field_type', array(
					'choices' => ignition_get_property_extra_field_types(),
					'value'   => ignition_get_term_meta( $term->term_id, 'field_type', '' ),
				) );
			?>
		</td>
	</tr>
	<?php
}

/**
 * Stores the Field Type term meta.
 *
 * @since 2.2.0
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function ignition_module_property_term_meta_field_type_save_fields( $term_id, $tt_id, $taxonomy ) {
	$taxonomies = ignition_module_property_get_propery_field_type_taxonomies();

	if ( ! in_array( $taxonomy, $taxonomies, true ) ||
		! isset( $_POST['_ignition_fields_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_ignition_fields_nonce'] ) ), 'ignition_term_meta_category_fields_nonce' )
	) {
		return;
	}

	if ( isset( $_POST['field_type'] ) ) {
		update_term_meta( $term_id, 'field_type', ignition_sanitize_property_extra_field_type( $_POST['field_type'] ) );
	}
}
