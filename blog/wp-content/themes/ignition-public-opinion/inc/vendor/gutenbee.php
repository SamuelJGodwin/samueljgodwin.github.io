<?php
/**
 * GutenBee related hooks and functions
 *
 * @since 1.0.0
 */

add_action( 'after_setup_theme', 'ignition_public_opinion_gutenbee_setup' );
/**
 * Sets up GutenBee support.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_gutenbee_setup() {
	// Override Ignition's default Post Types block support. Disable filters. Enable images.
	add_theme_support( 'block/gutenbee/post-types', array(
		'gridEffect'      => false,
		'masonry'         => false,
		'categoryFilters' => false,
		'selectImageSize' => array( 'post' ),
	) );
}
