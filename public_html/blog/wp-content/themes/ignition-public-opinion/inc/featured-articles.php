<?php
/**
 * Featured Articles related functions and definitions
 *
 * @since 1.0.0
 */

add_action( 'ignition_main_before', 'ignition_public_opinion_category_featured_articles' );
function ignition_public_opinion_category_featured_articles() {
	if ( ! is_category() ) {
		return;
	}

	$layout  = ignition_get_term_meta( get_queried_object_id(), 'featured_layout', '' );
	$layouts = ignition_public_opinion_featured_articles_get_layouts();

	if ( empty( $layout ) || ! array_key_exists( $layout, $layouts ) ) {
		return;
	}

	$layout_info = $layouts[ $layout ];

	$q = ignition_public_opinion_featured_articles_get_category_layout_articles( $layout, get_queried_object_id() );

	if ( false === $q ) {
		return;
	}

	$columns = $layout_info['columns'];

	$template_part = 'template-parts/gutenbee/post-types/article-default';
	if ( 1 === $columns ) {
		$template_part = 'template-parts/gutenbee/post-types/article-media';
	}

	$layout_class      = "entry-category-featured-layout is-style-{$layout_info['class']}";
	$row_columns_class = "row-columns-{$columns}";
	$columns_classes   = ignition_get_columns_classes( $columns );

	if ( $q->have_posts() ) {
		?>
		<div class="container">
			<div class="<?php echo esc_attr( $layout_class ); ?>">
				<div class="row row-items no-gutters <?php echo esc_attr( $row_columns_class ); ?>">
					<?php while ( $q->have_posts() ) : ?>
						<div class="<?php echo esc_attr( $columns_classes ); ?>">
							<?php $q->the_post(); ?>

							<?php ignition_get_template_part( $template_part, get_post_type() ); ?>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * Returns a list of available Featured Articles layouts, and their associated attributes (label, post count, etc).
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_public_opinion_featured_articles_get_layouts() {
	/* translators: %d is a number of columns. */
	$nooped = _nx_noop( 'Slideshow - %d Column', 'Slideshow - %d Columns', 'featured posts layout', 'ignition-public-opinion' );

	$layouts = array(
		'hero-1'                 => array(
			'title'   => _x( '1 Left / 2 Right', 'featured posts layout', 'ignition-public-opinion' ),
			'class'   => 'ignition-public-opinion-layout-hero-1',
			'posts'   => 3,
			'columns' => 2,
		),
		'hero-2'                 => array(
			'title'   => _x( '2 Left / 1 Right', 'featured posts layout', 'ignition-public-opinion' ),
			'class'   => 'ignition-public-opinion-layout-hero-2',
			'posts'   => 3,
			'columns' => 2,
		),
		'hero-3'                 => array(
			'title'   => _x( '1 Left / 4 Right', 'featured posts layout', 'ignition-public-opinion' ),
			'class'   => 'ignition-public-opinion-layout-hero-3',
			'posts'   => 5,
			'columns' => 2,
		),
		'hero-4'                 => array(
			'title'   => _x( '4 Left / 1 Right', 'featured posts layout', 'ignition-public-opinion' ),
			'class'   => 'ignition-public-opinion-layout-hero-4',
			'posts'   => 5,
			'columns' => 2,
		),
		'overlay-slideshow-1col' => array(
			'title'   => sprintf( translate_nooped_plural( $nooped, 1, 'ignition-public-opinion' ), 1 ),
			'class'   => 'ignition-public-opinion-layout-overlay-slideshow',
			'posts'   => 12,
			'columns' => 1,
		),
		'overlay-slideshow-2col' => array(
			'title'   => sprintf( translate_nooped_plural( $nooped, 2, 'ignition-public-opinion' ), 2 ),
			'class'   => 'ignition-public-opinion-layout-overlay-slideshow',
			'posts'   => 12,
			'columns' => 2,
		),
		'overlay-slideshow-3col' => array(
			'title'   => sprintf( translate_nooped_plural( $nooped, 3, 'ignition-public-opinion' ), 3 ),
			'class'   => 'ignition-public-opinion-layout-overlay-slideshow',
			'posts'   => 12,
			'columns' => 3,
		),
		'overlay-slideshow-4col' => array(
			'title'   => sprintf( translate_nooped_plural( $nooped, 4, 'ignition-public-opinion' ), 4 ),
			'class'   => 'ignition-public-opinion-layout-overlay-slideshow',
			'posts'   => 12,
			'columns' => 4,
		),
	);

	/**
	 * Filters the available Featured Articles layouts.
	 *
	 * @since 1.0.0
	 *
	 * @param array $layouts
	 */
	return apply_filters( 'ignition_public_opinion_featured_articles_layouts', $layouts );
}

/**
 * Returns a list of valid Featured Articles layout names and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_public_opinion_featured_articles_get_layout_choices() {
	$choices = array();

	foreach ( ignition_public_opinion_featured_articles_get_layouts() as $key => $choice ) {
		$choices[ $key ] = $choice['title'];
	}

	/**
	 * Filters the available Featured Articles choices.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_public_opinion_featured_articles_layout_choices', $choices );
}

/**
 * Sanitizes a Featured Articles layout value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_public_opinion_featured_articles_sanitize_layout( $value ) {
	$choices = ignition_public_opinion_featured_articles_get_layout_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return '';
}

add_action( 'wp_ajax_ignition-public-opinion-ajax-term-search', 'ignition_public_opinion_ajax_term_search' );
/**
 * Ajax handler for term search.
 *
 * This is a copy of wp_ajax_ajax_tag_search() but modified to return the tag slugs instead.
 *
 * @since 1.0.0
 * @since WP 3.1.0
 */
function ignition_public_opinion_ajax_term_search() {
	if ( ! isset( $_GET['tax'] ) ) {
		wp_die( 0 );
	}

	$taxonomy = sanitize_key( $_GET['tax'] );
	$tax      = get_taxonomy( $taxonomy );

	if ( ! $tax ) {
		wp_die( 0 );
	}

	if ( ! current_user_can( $tax->cap->assign_terms ) ) {
		wp_die( -1 );
	}

	$s = wp_unslash( $_GET['q'] );

	$comma = _x( ',', 'tag delimiter', 'ignition-public-opinion' );
	if ( ',' !== $comma ) {
		$s = str_replace( $comma, ',', $s );
	}

	if ( false !== strpos( $s, ',' ) ) {
		$s = explode( ',', $s );
		$s = $s[ count( $s ) - 1 ];
	}

	$s = trim( $s );

	/**
	 * Filters the minimum number of characters required to fire a tag search via Ajax.
	 *
	 * @since 4.0.0
	 *
	 * @param int         $characters The minimum number of characters required. Default 2.
	 * @param WP_Taxonomy $tax        The taxonomy object.
	 * @param string      $s          The search term.
	 */
	$term_search_min_chars = (int) apply_filters( 'term_search_min_chars', 2, $tax, $s );

	/*
	 * Require $term_search_min_chars chars for matching (default: 2)
	 * ensure it's a non-negative, non-zero integer.
	 */
	if ( ( 0 === $term_search_min_chars ) || ( strlen( $s ) < $term_search_min_chars ) ) {
		wp_die();
	}

	$results = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'name__like' => $s,
			'fields'     => 'id=>slug',
			'hide_empty' => false,
		)
	);

	echo implode( "\n", $results );
	wp_die();
}

add_filter( 'get_terms', 'ignition_public_opinion_get_terms_hide_featured_tag', 10, 4 );
/**
 * Filters out the selected featured tag from get_terms(), for front-end requests.
 *
 * @since 1.0.0
 *
 * @param array         $terms      Array of found terms.
 * @param array         $taxonomies An array of taxonomies.
 * @param array         $args       An array of get_terms() arguments.
 * @param WP_Term_Query $term_query The WP_Term_Query object.
 *
 * @return array
 */
function ignition_public_opinion_get_terms_hide_featured_tag( $terms, $taxonomies, $args, $term_query ) {
	if ( is_admin() || wp_is_json_request() ) {
		return $terms;
	}

	if ( ! in_array( 'post_tag', $taxonomies, true ) ) {
		return $terms;
	}

	if ( empty( $terms ) ) {
		return $terms;
	}

	if ( 'all' !== $args['fields'] ) {
		return $terms;
	}

	$featured_tag = get_theme_mod( 'theme_featured_articles_tag', ignition_public_opinion_ignition_customizer_defaults( 'theme_featured_articles_tag' ) );

	if ( $featured_tag ) {
		foreach ( $terms as $key => $term ) {
			if ( is_object( $term ) && $featured_tag === $term->slug ) {
				unset( $terms[ $key ] );
			}
		}
	}

	return $terms;
}

add_filter( 'get_the_terms', 'ignition_public_opinion_get_the_terms_hide_featured_tag', 10, 3 );
/**
 * Filters out the selected featured tag from get_the_terms(), for front-end requests.
 *
 * @since 1.0.0
 *
 * @param WP_Term[]|WP_Error $terms    Array of attached terms, or WP_Error on failure.
 * @param int                $post_id  Post ID.
 * @param string             $taxonomy Name of the taxonomy.
 *
 * @return array
 */
function ignition_public_opinion_get_the_terms_hide_featured_tag( $terms, $post_id, $taxonomy ) {
	if ( is_admin() || wp_is_json_request() ) {
		return $terms;
	}

	if ( 'post_tag' !== $taxonomy ) {
		return $terms;
	}

	if ( empty( $terms ) ) {
		return $terms;
	}

	$featured_tag = get_theme_mod( 'theme_featured_articles_tag', ignition_public_opinion_ignition_customizer_defaults( 'theme_featured_articles_tag' ) );

	if ( $featured_tag ) {
		foreach ( $terms as $key => $term ) {
			if ( $featured_tag === $term->slug ) {
				unset( $terms[ $key ] );
			}
		}
	}

	return $terms;
}

add_action( 'pre_get_posts', 'ignition_public_opinion_featured_articles_exclude_from_category_archives' );
/**
 * Modifies the main query in category archives, to exclude featured posts (if applicable).
 *
 * @since 1.0.0
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function ignition_public_opinion_featured_articles_exclude_from_category_archives( $query ) {
	if ( is_admin() ) {
		return;
	}

	if ( ! $query->is_category() || ! $query->is_main_query() ) {
		return;
	}

	$hide = get_term_meta( get_queried_object_id(), 'hide_featured_posts', true );
	if ( ! $hide ) {
		return;
	}

	$layout = ignition_get_term_meta( get_queried_object_id(), 'featured_layout', '' );
	if ( empty( $layout ) ) {
		return;
	}

	$ids = ignition_public_opinion_featured_articles_get_ids( $layout, get_queried_object_id() );

	if ( ! empty( $ids ) ) {
		$query->set( 'post__not_in', array_merge( (array) $query->get( 'post__not_int' ), $ids ) );
	}
}

/**
 * Returns the post IDs to display for a specific layout, optionally filtered by a specific category.
 *
 * @param string $layout      The layout slug. See ignition_public_opinion_featured_articles_get_layouts()
 * @param bool   $category_id Optional. Category ID to return featured post IDs from.
 * @param array  $query_args  Optional. Additional query arguments for WP_Query().
 *
 * @return array
 */
function ignition_public_opinion_featured_articles_get_ids( $layout, $category_id = false, $query_args = array() ) {
	$layouts = ignition_public_opinion_featured_articles_get_layouts();
	if ( ! array_key_exists( $layout, $layouts ) ) {
		return array();
	}

	$featured_tag = get_theme_mod( 'theme_featured_articles_tag', ignition_public_opinion_ignition_customizer_defaults( 'theme_featured_articles_tag' ) );

	if ( empty( $featured_tag ) ) {
		return array();
	}

	$posts_per_page = $layouts[ $layout ]['posts'];

	$q_args = array(
		'posts_per_page'      => $posts_per_page,
		'tag'                 => $featured_tag,
		'ignore_sticky_posts' => true,
		'fields'              => 'ids',
	);

	if ( intval( $category_id ) > 0 ) {
		$q_args['cat'] = intval( $category_id );
	}

	$q_args = array_merge( $q_args, $query_args );

	$q = new WP_Query( $q_args );

	return $q->posts;
}

/**
 * Returns a WP_Query object that contains the posts to be displayed in a speicific featured articles category template.
 *
 * @param string $layout      The layout slug. See ignition_public_opinion_featured_articles_get_layouts()
 * @param bool   $category_id Optional. Category ID to return featured post IDs from.
 * @param array  $query_args  Optional. Additional query arguments for WP_Query(), to be passed to ignition_public_opinion_featured_articles_get_ids().
 *
 * @return WP_Query|false WP_Query if there are post to display, false otherwise.
 */
function ignition_public_opinion_featured_articles_get_category_layout_articles( $layout, $category_id = false, $query_args = array() ) {
	$ids = ignition_public_opinion_featured_articles_get_ids( $layout, $category_id, $query_args );

	if ( empty( $ids ) ) {
		return false;
	}

	$q_args = apply_filters( 'ignition_public_opinion_featured_articles_category_layout_articles', array(
		'posts_per_page'      => - 1,
		'post__in'            => $ids,
		'ignore_sticky_posts' => true,
		'orderby'             => 'post__in',
	), $layout, $category_id );

	return new WP_Query( $q_args );
}
