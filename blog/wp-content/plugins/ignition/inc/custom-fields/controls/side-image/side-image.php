<?php
/**
 * Displays an image selection/upload field for use in single post metaboxes.
 *
 * @since 1.0.0
 *
 * @param string $fieldname   The image field's name attribute.
 * @param array  $params      {
 *      Array of image control parameters.
 *
 *      @type string  $title       The label text.
 *      @type string  $description Additional description text.
 *      @type string  $default     The default value of the control. Applies on new posts, when there are no existing post meta.
 *      @type array   $labels      Value => Label array of the control's strings.
 * }
 */
function ignition_side_metabox_image( $fieldname, $params = array() ) {
	$params = (array) $params;

	$defaults = array(
		'title'       => '',
		'description' => '',
		'default'     => '',
		'labels'      => array(
			'set_image'     => __( 'Set image', 'ignition' ),
			'no_image'      => __( 'No image selected', 'ignition' ),
			'replace_image' => __( 'Replace Image', 'ignition' ),
			'remove_image'  => __( 'Remove Image', 'ignition' ),
		),
	);

	$params = wp_parse_args( $params, $defaults );

	$params['labels'] = wp_parse_args( $params['labels'], $defaults['labels'] );

	if ( array_key_exists( 'value', $params ) ) {
		$value = $params['value'];
	} else {
		$custom_keys = get_post_custom_keys( get_the_ID() );

		if ( is_array( $custom_keys ) && in_array( $fieldname, $custom_keys, true ) ) {
			$value = get_post_meta( get_the_ID(), $fieldname, true );
			if ( ! is_array( $value ) ) {
				$value = $params['default'];
			}
		} else {
			$value = $params['default'];
		}
	}

	$value = wp_parse_args( $value, $params['default'] );

	$attachment = wp_prepare_attachment_for_js( $value['image_id'] );
	$can_upload = current_user_can( 'upload_files' );

	$select_button_id = 'ignition-side-image-customize-control-button-' . $fieldname;
	?>
	<div class="ignition-side-setting-wrap">
		<?php if ( $params['title'] || $params['description'] ) : ?>
			<label class="ignition-side-setting-labels" for="<?php echo esc_attr( $fieldname ); ?>">
				<?php if ( $params['title'] ) : ?>
					<span class="ignition-side-setting-label"><?php echo esc_html( $params['title'] ); ?></span>
				<?php endif; ?>
				<?php if ( $params['description'] ) : ?>
					<span class="ignition-side-setting-description"><?php echo wp_kses( $params['description'], ignition_get_allowed_tags() ); ?></span>
				<?php endif; ?>
			</label>
		<?php endif; ?>

		<div class="ignition-side-setting-control ignition-side-image-control" data-attachment="<?php echo esc_attr( wp_json_encode( $attachment ) ); ?>">

			<div class="ignition-side-image-control-wrap">
				<div class="ignition-side-image-control-group">

					<div class="ignition-side-image-attachment-view ignition-side-image-control-attachment-preview">
						<div class="ignition-side-image-control-thumbnail">
							<img class="ignition-side-image-control-attachment-thumb" alt="" src="">
						</div>

						<div class="actions">
							<?php if ( $can_upload ) : ?>
								<button type="button" class="upload-button control-focus" id="<?php echo esc_attr( $select_button_id ); ?>"><?php echo esc_html( $params['labels']['replace_image'] ); ?></button>
								<button type="button" class="remove-button"><?php echo esc_html( $params['labels']['remove_image'] ); ?></button>
							<?php endif; ?>
						</div>
					</div>

					<div class="ignition-side-image-attachment-view ignition-side-image-control-attachment-placeholder">
						<div class="ignition-side-image-control-placeholder">
							<?php echo esc_html( $params['labels']['no_image'] ); ?>
						</div>
						<div class="actions">
							<?php if ( $can_upload ) : ?>
								<button type="button" class="button upload-button screen-reader-text" id="<?php echo esc_attr( $select_button_id ); ?>"><?php echo esc_html( $params['labels']['set_image'] ); ?></button>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<input class="ignition-side-image-control-hidden-value" type="hidden" name="<?php echo esc_attr( $fieldname ); ?>" value="<?php echo esc_attr( wp_json_encode( $value ) ); ?>">
			</div>
		</div>
	</div>
	<?php
}
