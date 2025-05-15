<?php
/**
 * Displays a textarea field for use in single post metaboxes.
 *
 * @since 2.2.1
 *
 * @param string $fieldname   The input's name attribute.
 * @param array  $params      {
 *      Array of dropdown parameters.
 *
 *      @type string $title          The label text.
 *      @type string $description    Additional description text.
 *      @type string $default        The default value of the control. Applies on new posts, when there are no existing post meta.
 *      @type array  $textarea_class Optional. Classes for the textarea field. Default empty.
 *      @type string $placeholder    Optional. Placeholder text.
 *      @type int    $rows           Optional. Number of rows visible. Default 4.
 * }
 */
function ignition_side_metabox_textarea( $fieldname, $params = array() ) {
	$params = (array) $params;

	$defaults = array(
		'title'          => '',
		'description'    => '',
		'default'        => '',
		'textarea_class' => '',
		'placeholder'    => '',
		'rows'           => 4,
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
			<textarea
				id="<?php echo esc_attr( $fieldname ); ?>"
				name="<?php echo esc_attr( $fieldname ); ?>"
				class="<?php echo esc_attr( $params['textarea_class'] ); ?>"
				placeholder="<?php echo esc_attr( $params['placeholder'] ); ?>"
				rows="<?php echo esc_attr( $params['rows'] ); ?>"
			><?php echo esc_textarea( $value ); ?></textarea>
		</div>
	</div>
	<?php
}
