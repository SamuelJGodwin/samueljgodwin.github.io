<?php
/**
 * Defines Ignition blocks
 *
 * @since 2.0.0
 */

global $wp_version;

// TODO: Remove the 'block_categories' filter when WordPress reaches 5.9
if ( version_compare( $wp_version, '5.8', '<' ) ) {
	add_filter( 'block_categories', 'ignition_register_block_category', 10, 2 );
} else {
	add_filter( 'block_categories_all', 'ignition_register_block_category', 10, 2 );
}

/**
 * Registers the Ignition blocks category.
 *
 * @since 2.0.0
 */
function ignition_register_block_category( $categories, $post ) {
	$categories = array_merge( $categories, array(
		array(
			'slug'  => 'ignition',
			'title' => __( 'Ignition', 'ignition' ),
		),
	) );

	return $categories;
}

require_once untrailingslashit( IGNITION_DIR ) . '/inc/blocks/src/blocks/taxonomy-terms/block.php';
