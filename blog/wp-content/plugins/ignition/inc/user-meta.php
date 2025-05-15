<?php
/**
 * User meta fields
 *
 * @since 1.3.0
 */

add_action( 'admin_init', 'ignition_register_user_meta' );
/**
 * Registers user meta callbacks.
 *
 * @since 1.3.0
 */
function ignition_register_user_meta() {
	if ( current_theme_supports( 'ignition-user-social-icons' ) ) {
		add_action( 'show_user_profile', 'ignition_user_meta_social_icons_show_fields' );
		add_action( 'edit_user_profile', 'ignition_user_meta_social_icons_show_fields' );

		add_action( 'personal_options_update', 'ignition_user_meta_social_icons_save_fields' );
		add_action( 'edit_user_profile_update', 'ignition_user_meta_social_icons_save_fields' );
	}
}

/**
 * Outputs the user Social Icons meta markup.
 *
 * @since 1.3.0
 *
 * @param WP_User $user
 */
function ignition_user_meta_social_icons_show_fields( $user ) {
	wp_nonce_field( 'ignition_user_meta_social_icons_fields_nonce', '_ignition_user_meta_social_icons_fields_nonce' );

	$networks = ignition_get_social_networks();

	?>
	<h3><?php esc_html_e( 'Social Networks', 'ignition' ); ?></h3>

	<table class="form-table">
		<?php foreach ( $networks as $network ) : ?>
			<?php $fieldname = "ignition_social_{$network['name']}"; ?>
			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $fieldname ); ?>">
						<?php echo esc_html( sprintf(
							// translators: %s is a social network's name, e.g. Facebook
							_x( '%s URL', 'social network url', 'ignition' ),
							$network['label']
						) ); ?>
					</label>
				</th>
				<td>
					<input
						type="url"
						class="regular-text code"
						id="<?php echo esc_attr( $fieldname ); ?>"
						name="<?php echo esc_attr( $fieldname ); ?>"
						value="<?php echo esc_attr( ignition_get_user_meta( $user->ID, $fieldname, '' ) ); ?>"
					/>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

/**
 * Stores the user Social Icons meta.
 *
 * @since 1.3.0
 *
 * @param int $user_id
 */
function ignition_user_meta_social_icons_save_fields( $user_id ) {

	if ( isset( $_POST['_ignition_user_meta_social_icons_fields_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_ignition_user_meta_social_icons_fields_nonce'] ) ), 'ignition_user_meta_social_icons_fields_nonce' ) ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		$networks = ignition_get_social_networks();

		foreach ( $networks as $network ) {
			$fieldname = "ignition_social_{$network['name']}";

			if ( isset( $_POST[ $fieldname ] ) ) {
				update_user_meta( $user_id, $fieldname, esc_url_raw( $_POST[ $fieldname ] ) );
			}
		}
	}
}
