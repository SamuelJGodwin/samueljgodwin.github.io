<?php
/**
 * Default template part for GutenBee's "item" template (>=2 columns)
 *
 * @since 1.0.0
 */

/** @var array $args */
$args = isset( $args ) ? $args : array();

ignition_get_template_part( 'template-parts/item', get_post_type(), $args );
