<?php
/**
 * Template part for the Back to Top button
 *
 * @since 1.0.0
 */

if ( ! get_theme_mod( 'utilities_button_top_is_enabled', ignition_customizer_defaults( 'utilities_button_top_is_enabled' ) ) ) {
	return;
}
?>

<button class="btn-to-top">
	<span class="ignition-icons ignition-icons-arrow-up"></span>
</button>
