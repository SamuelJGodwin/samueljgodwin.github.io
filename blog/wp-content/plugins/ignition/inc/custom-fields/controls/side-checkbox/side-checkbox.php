<?php
/**
 * Displays a checkbox field for use in single post metaboxes.
 *
 * @since 1.0.0
 *
 * @param string $fieldname   The checkbox's name attribute.
 * @param array  $params      {
 *      Array of dropdown parameters.
 *
 *      @type string  $title         The label text.
 *      @type string  $description   Additional description text.
 *      @type string  $checked_value Form value when the checkbox is checked. Default: 1
 *      @type string  $default       The default value of the control. Applies on new posts, when there are no existing post meta.
 * }
 */
function ignition_side_metabox_checkbox( $fieldname, $params = array() ) {
	$defaults = array(
		'title'         => '',
		'description'   => '',
		'checked_value' => 1,
		'default'       => '',
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

	?>
	<div class="ignition-side-setting-wrap">
		<div class="ignition-side-setting-control-checkbox">
			<?php if ( $params['title'] ) : ?>
				<label class="ignition-side-setting-checkbox-label" for="<?php echo esc_attr( $fieldname ); ?>">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $fieldname ); ?>"
						name="<?php echo esc_attr( $fieldname ); ?>"
						<?php checked( $value, $params['checked_value'] ); ?>
					/>
					<span class="ignition-side-setting-label"><?php echo esc_html( $params['title'] ); ?></span>
				</label>
			<?php else : ?>
				<input
					type="checkbox"
					id="<?php echo esc_attr( $fieldname ); ?>"
					name="<?php echo esc_attr( $fieldname ); ?>"
					<?php checked( $value, $params['checked_value'] ); ?>
				/>
			<?php endif; ?>

			<?php if ( $params['description'] ) : ?>
				<span class="ignition-side-setting-description"><?php echo wp_kses( $params['description'], ignition_get_allowed_tags() ); ?></span>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
