<?php
/**
 * Helper functions
 *
 * @since 1.0.0
 */

/**
 * Conditionally returns a Javascript/CSS asset's version number.
 *
 * When the site is in debug mode, the normal asset's version is returned.
 * When it's not in debug mode, the theme's version is returned, so that caches can be invalidated on theme updates.
 *
 * @since 1.0.0
 *
 * @param bool $version The version string of the asset.
 *
 * @return false|string Theme version if SCRIPT_DEBUG or WP_DEBUG are enabled. Otherwise, $version is returned.
 */
function ignition_public_opinion_asset_version( $version = false ) {
	static $theme_version = false;

	if ( ! $theme_version ) {
		$theme = wp_get_theme();

		if ( is_child_theme() ) {
			$theme_version = $theme->parent()->get( 'Version' ) . '-' . $theme->get( 'Version' );
		} else {
			$theme_version = $theme->get( 'Version' );
		}
	}

	if ( $version ) {
		if ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ||
			( defined( 'WP_DEBUG' ) && WP_DEBUG )
		) {
			return $version;
		}
	}

	return $theme_version;
}

/**
 * Returns a list of allowed tags and attributes for a given context.
 *
 * @see wp_kses()
 *
 * @since 1.0.0
 *
 * @param string $context Optional. The context for which to retrieve tags.
 *                        Currently available contexts: 'guide'.
 *
 * @return array List of allowed tags and their allowed attributes.
 */
function ignition_public_opinion_get_allowed_tags( $context = '' ) {
	$allowed = array(
		'a'       => array(
			'href'   => true,
			'title'  => true,
			'class'  => true,
			'target' => true,
			'rel'    => true,
		),
		'abbr'    => array( 'title' => true ),
		'acronym' => array( 'title' => true ),
		'b'       => array( 'class' => true ),
		'br'      => array(),
		'code'    => array( 'class' => true ),
		'em'      => array( 'class' => true ),
		'i'       => array( 'class' => true ),
		'img'     => array(
			'alt'    => true,
			'class'  => true,
			'src'    => true,
			'width'  => true,
			'height' => true,
		),
		'li'      => array( 'class' => true ),
		'ol'      => array( 'class' => true ),
		'p'       => array( 'class' => true ),
		'pre'     => array( 'class' => true ),
		'span'    => array( 'class' => true ),
		'strong'  => array( 'class' => true ),
		'ul'      => array( 'class' => true ),
	);

	switch ( $context ) {
		case 'guide':
			unset( $allowed['p'] );
			break;
		default:
			break;
	}

	/**
	 * Filters the list of allowed tags.
	 *
	 * @since 1.0.0
	 *
	 * @param array $allowed  Array of allowed elements, structured for use with wp_kses()
	 * @param string $context Optional. Usage context.
	 */
	return apply_filters( 'ignition_public_opinion_get_allowed_tags', $allowed, $context );
}

/**
 * Returns a post's primary term from a specific taxonomy.
 *
 * By default, it returns the first available term from the given taxonomy.
 * The definition of a post's primary term, however, might be different depending on the use-case,
 * in which case, the returned term can be changed via the 'ignition_public_opinion_primary_post_term' filter.
 *
 * @param int|WP_Post   $post
 * @param string $taxonomy
 *
 * @return WP_Term|false
 */
function ignition_public_opinion_get_primary_post_term( $post = null, $taxonomy = 'category' ) {
	if ( empty( $post ) ) {
		$post = get_post();
	}

	$term  = false;
	$terms = get_the_terms( $post, $taxonomy );

	if ( ! empty( $terms ) ) {
		$term = current( $terms );
	}

	/**
	 * Filters the post's primary term.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Term|false            $term
	 * @param int|WP_Post              $post
	 * @param string                   $taxonomy
	 * @param WP_Term[]|false|WP_Error $terms
	 */
	$term = apply_filters( 'ignition_public_opinion_primary_post_term', $term, $post, $taxonomy, $terms );

	return $term;
}

/**
 * Returns the post's primary primary category's accent color.
 *
 * @param int|WP_Post $post
 *
 * @return string
 */
function ignition_public_opinion_get_the_post_category_color( $post = null ) {
	if ( empty( $post ) ) {
		$post = get_post();
	}

	$term    = ignition_public_opinion_get_primary_post_term( $post );
	$term_id = false;
	$color   = '';

	if ( ! empty( $term ) ) {
		$color   = get_term_meta( $term->term_id, 'accent_color', true );
		$term_id = $term->term_id;
	}

	/**
	 * Filters the post's primary category's accent color.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $color
	 * @param int|WP_Post $post
	 * @param int         $term_id
	 */
	return apply_filters( 'ignition_public_opinion_get_the_post_category_color', $color, $post, $term_id );
}
