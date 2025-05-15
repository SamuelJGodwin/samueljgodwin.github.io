<?php
/**
 * Default template part for GutenBee's horizontal "media" template (1 column)
 *
 * @since 1.0.0
 */

/** @var array $args */
$args = isset( $args ) ? $args : array();

ignition_get_template_part( 'template-parts/article-media', get_post_type(), $args );
