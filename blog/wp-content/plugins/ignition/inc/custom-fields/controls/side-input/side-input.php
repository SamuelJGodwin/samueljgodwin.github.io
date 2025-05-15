<?php
/**
 * Displays an input field for use in single post metaboxes.
 *
 * @since 1.0.0
 * @since 2.0.0 $params['placeholder']
 *
 * @param string $fieldname   The input's name attribute.
 * @param array  $params      {
 *      Array of dropdown parameters.
 *
 *      @type string  $title       The label text.
 *      @type string  $description Additional description text.
 *      @type string  $default     The default value of the control. Applies on new posts, when there are no existing post meta.
 *      @type string  $type        The type property of the input field. Accepts 'text', 'number', 'hidden', etc. Default 'text'.
 *      @type array   $input_class Optional. Classes for the input field. Default empty.
 * }
 */
function ignition_side_metabox_input( $fieldname, $params = array() ) {
	$params = (array) $params;

	$defaults = array(
		'title'       => '',
		'description' => '',
		'default'     => '',
		'type'        => 'text',
		'input_class' => '',
		'placeholder' => '',
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

	if ( 'url' === $params['type'] ) {
		$value_escaped  = esc_url( $value );
		$params['type'] = 'text';
	} else {
		$value_escaped = esc_attr( $value );
	}

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

		<div class="ignition-side-setting-control">
			<input
				type="<?php echo esc_attr( $params['type'] ); ?>"
				id="<?php echo esc_attr( $fieldname ); ?>"
				name="<?php echo esc_attr( $fieldname ); ?>"
				value="<?php echo $value_escaped; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
				class="<?php echo esc_attr( $params['input_class'] ); ?>"
				placeholder="<?php echo esc_attr( $params['placeholder'] ); ?>"
			/>
		</div>
	</div>
	<?php
}
