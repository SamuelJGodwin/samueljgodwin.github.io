<?php
/**
 * Term meta fields
 *
 * @since 1.0.0
 */

add_action( 'admin_init', 'ignition_public_opinion_register_term_meta_fields' );
/**
 * Registers term meta callbacks.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_register_term_meta_fields() {
	add_action( 'category_add_form_fields', 'ignition_public_opinion_term_meta_layout_add_fields', 10 );
	add_action( 'category_edit_form_fields', 'ignition_public_opinion_term_meta_layout_edit_fields', 10, 2 );
}

/**
 * Outputs the Layout meta markup for new terms.
 *
 * @since 1.0.0
 *
 * @param string  $taxonomy Current taxonomy slug.
 */
function ignition_public_opinion_term_meta_layout_add_fields( $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<div class="form-field">
		<?php
			ignition_side_metabox_input( 'accent_color', array(
				'title'       => __( 'Accent color', 'ignition-public-opinion' ),
				'description' => __( 'The accent color is used throughout the theme to color specific parts of the posts belonging to this category.', 'ignition-public-opinion' ),
				'input_class' => 'ignition-color-picker',
			) );
		?>
	</div>

	<div class="form-field">
		<?php
			ignition_side_metabox_dropdown( 'featured_layout', array(
				'title'   => __( 'Featured Articles Layout', 'ignition-public-opinion' ),
				'choices' => array_merge(
					array( '' => '' ),
					ignition_public_opinion_featured_articles_get_layout_choices()
				),
			) );
		?>
	</div>

	<div class="form-field">
		<?php
			ignition_side_metabox_checkbox( 'hide_featured_posts', array(
				'title'       => __( 'Hide featured articles from the listing', 'ignition-public-opinion' ),
				'description' => __( "Featured articles will only appear on the category's featured section, hiding them from the normal date-based flow. This only affects the specific category's archive page.", 'ignition-public-opinion' ),
			) );
		?>
	</div>
	<?php
}

/**
 * Outputs the Layout meta markup for existing terms.
 *
 * @since 1.0.0
 *
 * @param WP_Term $term     Current taxonomy term object.
 * @param string  $taxonomy Current taxonomy slug.
 */
function ignition_public_opinion_term_meta_layout_edit_fields( $term, $taxonomy ) {
	wp_nonce_field( 'ignition_term_meta_category_fields_nonce', '_ignition_fields_nonce' );

	?>
	<tr class="form-field">
		<th scope="row"><label for="accent_color"><?php esc_html_e( 'Accent color', 'ignition-public-opinion' ); ?></label></th>
		<td>
			<?php
				ignition_side_metabox_input( 'accent_color', array(
					'value'       => ignition_get_term_meta( $term->term_id, 'accent_color', '' ),
					'title'       => '',
					'description' => __( 'The accent color is used throughout the theme to color specific parts of the posts belonging to this category.', 'ignition-public-opinion' ),
					'input_class' => 'ignition-color-picker',
				) );
			?>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="featured_layout"><?php esc_html_e( 'Featured Articles Layout', 'ignition-public-opinion' ); ?></label></th>
		<td>
			<?php
				ignition_side_metabox_dropdown( 'featured_layout', array(
					'value'   => ignition_get_term_meta( $term->term_id, 'featured_layout', '' ),
					'title'   => '',
					'choices' => array_merge(
						array( '' => '' ),
						ignition_public_opinion_featured_articles_get_layout_choices()
					),
				) );
			?>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row"><label for="hide_featured_posts"><?php esc_html_e( 'Hide featured articles from the listing', 'ignition-public-opinion' ); ?></label></th>
		<td>
			<?php
				ignition_side_metabox_checkbox( 'hide_featured_posts', array(
					'value'       => ignition_get_term_meta( $term->term_id, 'hide_featured_posts', '' ),
					'title'       => __( 'Hide featured articles from the listing', 'ignition-public-opinion' ),
					'description' => __( "Featured articles will only appear on the category's featured section, hiding them from the normal date-based flow. This only affects the specific category's archive page.", 'ignition-public-opinion' ),
				) );
			?>
		</td>
	</tr>
	<?php
}

add_action( 'create_term', 'ignition_public_opinion_term_meta_layout_save_fields', 10, 3 );
add_action( 'edit_term', 'ignition_public_opinion_term_meta_layout_save_fields', 10, 3 );
/**
 * Stores the Layout term meta.
 *
 * @since 1.0.0
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function ignition_public_opinion_term_meta_layout_save_fields( $term_id, $tt_id, $taxonomy ) {
	$taxonomies = array(
		'category',
	);

	if ( ! in_array( $taxonomy, $taxonomies, true ) ||
		! isset( $_POST['_ignition_fields_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_ignition_fields_nonce'] ) ), 'ignition_term_meta_category_fields_nonce' )
	) {
		return;
	}

	if ( isset( $_POST['accent_color'] ) ) {
		update_term_meta( $term_id, 'accent_color', ignition_sanitize_rgba_color( $_POST['accent_color'] ) );
	}

	if ( isset( $_POST['featured_layout'] ) ) {
		update_term_meta( $term_id, 'featured_layout', ignition_public_opinion_featured_articles_sanitize_layout( $_POST['featured_layout'] ) );
	}

	update_term_meta( $term_id, 'hide_featured_posts', isset( $_POST['hide_featured_posts'] ) );
}
