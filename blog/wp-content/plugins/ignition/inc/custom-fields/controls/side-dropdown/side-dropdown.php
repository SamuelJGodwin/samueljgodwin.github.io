<?php
/**
 * Displays a dropdown field for use in single post metaboxes.
 *
 * @since 1.0.0
 *
 * @param string $fieldname   The dropdown's name attribute.
 * @param array  $params      {
 *      Array of dropdown parameters.
 *
 *      @type string  $title       The label text.
 *      @type string  $description Additional description text.
 *      @type string  $default     The default value of the control. Applies on new posts, when there are no existing post meta.
 *      @type array   $choices     Value => Label array of the dropdown's choices.
 * }
 */
function ignition_side_metabox_dropdown( $fieldname, $params = array() ) {
	$params = (array) $params;

	$defaults = array(
		'title'       => '',
		'description' => '',
		'default'     => '',
		'choices'     => array(),
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
			<select id="<?php echo esc_attr( $fieldname ); ?>" name="<?php echo esc_attr( $fieldname ); ?>">
				<?php foreach ( $params['choices'] as $opt_value => $opt_title ) : ?>
					<option value="<?php echo esc_attr( $opt_value ); ?>" <?php selected( $value, $opt_value ); ?>><?php echo wp_kses( $opt_title, 'strip' ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<?php
}
