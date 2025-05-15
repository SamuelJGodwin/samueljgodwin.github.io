<?php
/**
 * Template part for displaying the sidebar image and artist metadata of team items
 *
 * @since 1.1.0
 */

ignition_the_post_thumbnail( 'ignition_sidebar_tall' );

$_post_type    = get_post_type();
$metadata_type = false;

if ( current_theme_supports( 'ignition-team', 'metadata' ) ) {
	$metadata_type = get_theme_support( 'ignition-team' )[0]['metadata'];
}

ignition_get_template_part( "template-parts/single/sidebar-image-meta/meta-{$_post_type}", $metadata_type );
