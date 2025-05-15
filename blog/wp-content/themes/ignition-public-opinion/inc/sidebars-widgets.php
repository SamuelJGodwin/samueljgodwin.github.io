<?php
/**
 * Sidebars and widgets related functions
 *
 * @since 1.0.0
 */

add_action( 'widgets_init', 'ignition_public_opinion_register_widgets' );
/**
 * Registers widgets.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_register_widgets() {
	require_once get_theme_file_path( '/inc/widgets/latest-posts.php' );
	register_widget( 'Ignition_Public_Opinion_Widget_Latest_Posts' );
}
