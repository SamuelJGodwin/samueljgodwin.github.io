<?php
/**
 * Displays a checkbox field for use in single post metaboxes.
 *
 * @since 1.2.0
 *
 * @param array  $params    {
 *      Array of dropdown parameters.
 *
 *      @type string  $title       The label text.
 *      @type string  $description Additional description text.
 * }
 */
function ignition_metabox_separator( $params = array() ) {
	$params = (array) $params;

	$defaults = array(
		'title'       => '',
		'description' => '',
	);

	$params = wp_parse_args( $params, $defaults );
	?>
	<h5 class="ignition-setting-separator">
		<?php echo esc_html( $params['title'] ); ?>

		<?php if ( $params['description'] ) : ?>
			<span class="ignition-setting-separator-description">
				<?php echo wp_kses( $params['description'], ignition_get_allowed_tags() ); ?>
			</span>
		<?php endif; ?>
	</h5>
	<?php
}
