<?php
/**
 * Actions and filters that affect Ignition functionality
 *
 * @since 1.0.0
 */

add_filter( 'ignition_single_featured_image_visibility_post_types', 'ignition_public_opinion_single_featured_image_visibility_post_types' );
/**
 * Adds additional post types that have Feature Image Visibility options.
 *
 * @since 1.0.0
 *
 * @param string[] $post_types
 *
 * @return string[]
 */
function ignition_public_opinion_single_featured_image_visibility_post_types( $post_types ) {
	return array_merge( $post_types, array( 'post' ) );
}

add_filter( 'ignition_the_post_tags_args', 'ignition_public_opinion_filter_ignition_the_post_tags_args' );
/**
 * Removes any before, after, and separator texts from the ignition_the_post_tags() tags output.
 *
 * @since 1.0.0
 *
 * @param array $args
 *
 * @return array
 */
function ignition_public_opinion_filter_ignition_the_post_tags_args( $args ) {
	return array(
		'before' => '',
		'sep'    => '',
		'after'  => '',
	);
}
