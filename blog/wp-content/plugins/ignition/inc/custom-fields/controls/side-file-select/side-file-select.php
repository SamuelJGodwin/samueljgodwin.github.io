<?php
/**
 * Displays a file selection field for use in single post metaboxes.
 *
 * @since 1.9.0
 *
 * @param string $fieldname   The field's name attribute.
 * @param array  $params      {
 *      Array of the field's parameters.
 *
 *      @type string  $title       The label text.
 *      @type string  $description Additional description text.
 *      @type string  $type        The type of file. Accepts 'image', 'video', 'audio', etc. Default 'image'.
 *      @type string  $default     The default value of the control. Applies on new posts, when there are no existing post meta.
 *      @type array   $placeholder Optional. Placeholder text. Default 'https://.
 * }
 */
function ignition_side_metabox_file_select( $fieldname, $params = array() ) {
	$params = (array) $params;

	$defaults = array(
		'title'       => '',
		'description' => '',
		'file_type'   => 'image', // Valid types: image, audio, video
		'default'     => '',
		'placeholder' => 'https://',
	);

	$params = wp_parse_args( $params, $defaults );

	if ( array_key_exists( 'value', $params ) ) {
		$value = $params['value'];
	} else {
		$custom_keys = get_post_custom_keys( get_the_ID() );

		if ( is_array( $custom_keys ) && in_array( $fieldname, $custom_keys, true ) ) {
			$value = get_post_meta( get_the_ID(), $fieldname, true );
		} else {
			$value = $params['default'];
		}
	}

	$can_upload = current_user_can( 'upload_files' );

	$select_button_id = 'ignition-file-select-customize-control-button-' . $fieldname;
	?>
	<div class="ignition-side-setting-wrap">
		<?php if ( $params['title'] || $params['description'] ) : ?>
			<label class="ignition-side-setting-labels" for="<?php echo esc_attr( $fieldname ); ?>">
				<?php if ( $params['title'] ) : ?>
					<span class="ignition-side-setting-label"><?php echo esc_html( $params['title'] ); ?></span>
				<?php endif; ?>
				<?php if ( $params['description'] ) : ?>
					<span class="ignition-side-setting-description"><?php echo wp_kses( $params['description'], ci_theme_plugin_get_allowed_tags() ); ?></span>
				<?php endif; ?>
			</label>
		<?php endif; ?>

		<div class="ignition-side-setting-control ignition-side-control-file-select">

			<input
				type="url"
				name="<?php echo esc_attr( $fieldname ); ?>"
				id="<?php echo esc_attr( $fieldname ); ?>"
				class="ignition-side-control-file-select-input"
				value="<?php echo esc_attr( $value ); ?>"
				placeholder="<?php echo esc_attr( $params['placeholder'] ); ?>"
			>

			<div class="actions">
				<?php if ( $can_upload ) : ?>
					<button
						type="button"
						class="button upload-button control-focus"
						id="<?php echo esc_attr( $select_button_id ); ?>"
						data-type="<?php echo esc_attr( $params['file_type'] ); ?>"
					>
						<?php esc_html_e( 'Select file', 'ignition' ); ?>
					</button>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}
