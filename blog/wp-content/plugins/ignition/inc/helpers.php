<?php
/**
 * Helper functions
 *
 * @since 1.0.0
 */

/**
 * Returns a set of related posts, or the arguments needed for such a query.
 *
 * @since 1.0.0
 *
 * @param int   $post_id       A post ID to get related posts for.
 * @param int   $related_count The number of related posts to return.
 * @param array $args          Array of arguments to change the default behavior.
 *
 * @return object|array A WP_Query object with the results, or an array with the query arguments.
 */
function ignition_get_related_posts( $post_id, $related_count, $args = array() ) {
	$args = wp_parse_args( (array) $args, array(
		'orderby' => 'rand',
		'return'  => 'query', // Valid values are: 'query' (WP_Query object), 'array' (the arguments array)
	) );

	$post_type = get_post_type( $post_id );
	$post      = get_post( $post_id );

	$tax_query  = array();
	$taxonomies = get_object_taxonomies( $post, 'names' );

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			$term_list = wp_list_pluck( $terms, 'slug' );
			$term_list = array_values( $term_list );
			if ( ! empty( $term_list ) ) {
				$tax_query['tax_query'][] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $term_list,
				);
			}
		}
	}

	if ( count( $taxonomies ) > 1 ) {
		$tax_query['tax_query']['relation'] = 'OR';
	}

	$query_args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $related_count,
		'post_status'    => 'publish',
		'post__not_in'   => array( $post_id ),
		'orderby'        => $args['orderby'],
	);

	if ( 'query' === $args['return'] ) {
		return new WP_Query( array_merge( $query_args, $tax_query ) );
	} else {
		return array_merge( $query_args, $tax_query );
	}
}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.
 *
 * @see https://gist.github.com/stephenharris/5532899
 *
 * @since 1.0.0
 *
 * @param string       $color   Hexadecimal color value. May be 3 or 6 digits, with an optional leading # sign.
 * @param float        $percent Decimal (0.2 = lighten by 20%, -0.4 = darken by 40%)
 * @param false|string $default Value to return if calculation fails for any reason (e.g. invalid color).
 *
 * @return string Lightened/Darkened colour as hexadecimal (with hash)
 */
function ignition_color_luminance( $color, $percent, $default = false ) {
	if ( empty( $color ) ) {
		return $default;
	}

	// Remove # if provided
	$color = ltrim( $color, '#' );

	// Validate hex string.
	$hex     = preg_replace( '/[^0-9a-f]/i', '', $color );
	$new_hex = '#';

	$percent = floatval( $percent );

	$hex_len = strlen( $hex );
	if ( $hex_len < 6 && $hex_len >= 3 ) {
		$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
	} elseif ( 6 !== $hex_len ) {
		return $default;
	}

	// Convert to decimal and change luminosity.
	for ( $i = 0; $i < 3; $i ++ ) {
		$dec = hexdec( substr( $hex, $i * 2, 2 ) );
		$dec = min( max( 0, $dec + $dec * $percent ), 255 );

		$new_hex .= str_pad( dechex( $dec ), 2, 0, STR_PAD_LEFT );
	}

	return $new_hex;
}

/**
 * Determines if we're currently in a blog-related page, e.g. date archives, category listings, etc.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function ignition_is_blog_listing() {
	$is_blog = false;
	if ( is_home() || is_tag() || is_category() || is_date() || is_author() || is_search() ) {
		$is_blog = true;
	}

	/**
	 * Filters whether the current page belongs to the blog.
	 *
	 * Blog pages are considered post-related pages, such as category/tag listings, date archives, etc.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_blog Whether the current page belongs to the blog.
	 */
	return apply_filters( 'ignition_is_blog_listing', $is_blog );
}

/**
 * Determines if the blog section of the site is configured as a page.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function ignition_blog_is_page() {
	$show_on_front  = get_option( 'show_on_front' );
	$page_for_posts = get_option( 'page_for_posts' );

	if ( 'page' === $show_on_front && $page_for_posts ) {
		return true;
	}

	return false;
}

/**
 * Returns the page ID that's configured as the blog page.
 *
 * @since 1.0.0
 *
 * @return int|false
 */
function ignition_get_blog_page_id() {
	$show_on_front  = get_option( 'show_on_front' );
	$page_for_posts = get_option( 'page_for_posts' );

	if ( 'page' === $show_on_front && $page_for_posts ) {
		return intval( $page_for_posts );
	}

	return false;
}

/**
 * Converts a hexadecimal color value to rgb(a) format.
 *
 * @since 1.0.0
 *
 * @param string      $color   Hexadecimal color value. May be 3 or 6 digits, with an optional leading # sign.
 * @param false|float $opacity Opacity level 0-1 (decimal) or false to disable.
 *
 * @return string
 */
function ignition_hex2rgba( $color, $opacity = false ) {

	$default = 'rgb(0,0,0)';

	// Return default if no color provided
	if ( empty( $color ) ) {
		return $default;
	}

	// Remove # if provided
	$color = ltrim( $color, '#' );

	// Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) === 6 ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) === 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	$rgb = array_map( 'hexdec', $hex );

	if ( false !== $opacity ) {
		$opacity = abs( floatval( $opacity ) );
		if ( $opacity > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	return $output;
}


/**
 * Conditionally returns a Javascript/CSS asset's version number.
 *
 * When the site is in debug mode, the normal asset's version is returned.
 * When it's not in debug mode, the plugin's version is returned, so that caches can be invalidated on plugin updates.
 *
 * @since 1.0.0
 *
 * @param bool $version The version string of the asset.
 *
 * @return string Plugin version if SCRIPT_DEBUG or WP_DEBUG are enabled. Otherwise, $version is returned.
 */
function ignition_asset_version( $version = false ) {
	if ( $version ) {
		if ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ||
			( defined( 'WP_DEBUG' ) && WP_DEBUG )
		) {
			return $version;
		}
	}

	return IGNITION_VERSION;
}

/**
 * Retrieves a theme_mod value, or it's overridden value by post/term meta.
 * Assumes that an empty string value means we want to fall back to the customizer value.
 *
 * @since 1.0.0
 *
 * @param string      $name    Option name
 * @param false|mixed $default Optional. Default value to pass to get_theme_mod()
 *
 * @return mixed
 */
function ignition_get_mod( $name, $default = false ) {
	$object_id = false;

	if ( false === $object_id ) {
		if ( is_singular() ) {
			$object_id = get_the_ID();
		} elseif ( is_home() && ignition_blog_is_page() ) {
			$object_id = ignition_get_blog_page_id();
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$object_id = get_queried_object_id();
		} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
			$object_id = wc_get_page_id( 'shop' );
		}
	}

	$value      = $default;
	$customizer = get_theme_mod( $name, $default );
	$meta_keys  = array();

	if ( is_singular() || ( is_home() && ignition_blog_is_page() ) ) {
		$meta_keys = get_post_custom_keys( $object_id );
		if ( is_null( $meta_keys ) ) {
			$meta_keys = array();
		}
	}

	$case = false;
	if ( is_singular() && in_array( $name, $meta_keys, true ) ) {
		$case = 'singular';
	} elseif ( is_home() && ignition_blog_is_page() && in_array( $name, $meta_keys, true ) ) {
		$case = 'blog_page';
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$case = 'term';
	} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
		$case = 'shop';
	} else {
		$case = false;
	}

	switch ( $case ) {
		case 'singular':
		case 'blog_page':
		case 'shop':
			$value = get_post_meta( $object_id, $name, true );
			if ( false === $value || '' === $value ) {
				$value = $customizer;
			} elseif ( is_array( $value ) && is_array( $default ) ) {
				// Make sure the arrays contain identical keys, otherwise the comparison is not meaningful.
				$value = shortcode_atts( $default, $value );

				ksort( $value );
				ksort( $default );
				if ( $value === $default ) {
					$value = $customizer;
				}
			}
			break;
		case 'term':
			$value = get_term_meta( $object_id, $name, true );
			if ( false === $value || '' === $value ) {
				$value = $customizer;
			}
			break;
		default:
			$value = $customizer;
	}

	/**
	 * Filters the theme_mod/meta value.
	 *
	 * The dynamic portion of the name, `$name`, refers to the mod/meta option name.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value   The mod/meta value.
	 * @param string $name   The mod/meta option name.
	 * @param mixed $default The default value return if the option doesn't exist.
	 * @param int $object_id Object ID of the current request, that the option value is retrieved from.
	 */
	return apply_filters( "ignition_mod_{$name}", $value, $name, $default, $object_id );
}

/**
 * Generic fallback callback for the main menu.
 *
 * Displays a few useful links, in contrast to the default wp_page_menu() which may flood the menu area.
 *
 * @since 1.0.0
 *
 * @param array $args
 *
 * @return void|string
 */
function ignition_main_menu_fallback( $args ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return false;
	}

	$id    = ! empty( $args['menu_id'] ) ? $args['menu_id'] : 'menu-' . $args['theme_location'];
	$class = $args['menu_class'];

	/**
	 * Filters the main menu fallback items.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items Array of 'url' => 'label' elements.
	 */
	$items = apply_filters( 'ignition_main_menu_fallback_items', array(
		home_url( '/' )              => __( 'Home', 'ignition' ),
		admin_url( 'nav-menus.php' ) => __( 'Set primary menu', 'ignition' ),
	) );

	$items_html = '';
	if ( $items ) {
		foreach ( $items as $item_url => $item_text ) {
			$items_html .= sprintf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $item_url ), esc_html( $item_text ) );
		}
	}

	if ( empty( $items_html ) ) {
		return false;
	}

	$menu_html = sprintf( $args['items_wrap'], esc_attr( $id ), esc_attr( $class ), $items_html );

	$container_id    = $args['container_id'] ? ' id="' . esc_attr( $args['container_id'] ) . '"' : '';
	$container_class = $args['container_class'] ? ' class="' . esc_attr( $args['container_class'] ) . '"' : '';

	if ( $args['container'] ) {
		$menu_html = '<' . $args['container'] . $container_id . $container_class . '>' . $menu_html . '</' . $args['container'] . '>';
	}

	if ( $args['echo'] ) {
		echo wp_kses_post( $menu_html );
	} else {
		return $menu_html;
	}
}

/**
 * Generic fallback callback for secondary menus.
 *
 * Displays a useful link, in contrast to the default wp_page_menu() which may flood the menu area.
 *
 * @since 1.0.0
 *
 * @param array $args
 *
 * @return void|string
 */
function ignition_secondary_menu_fallback( $args ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return false;
	}

	$id    = ! empty( $args['menu_id'] ) ? $args['menu_id'] : 'menu-' . $args['theme_location'];
	$class = $args['menu_class'];

	/**
	 * Filters the secondary menu fallback items.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items Array of 'url' => 'label' elements.
	 */
	$items = apply_filters( 'ignition_secondary_menu_fallback_items', array(
		admin_url( 'nav-menus.php' ) => __( 'Set secondary menu', 'ignition' ),
	) );

	$items_html = '';
	if ( $items ) {
		foreach ( $items as $item_url => $item_text ) {
			$items_html .= sprintf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $item_url ), esc_html( $item_text ) );
		}
	}

	if ( empty( $items_html ) ) {
		return false;
	}

	$menu_html = sprintf( $args['items_wrap'], esc_attr( $id ), esc_attr( $class ), $items_html );

	$container_id    = $args['container_id'] ? ' id="' . esc_attr( $args['container_id'] ) . '"' : '';
	$container_class = $args['container_class'] ? ' class="' . esc_attr( $args['container_class'] ) . '"' : '';

	if ( $args['container'] ) {
		$menu_html = '<' . $args['container'] . $container_id . $container_class . '>' . $menu_html . '</' . $args['container'] . '>';
	}

	if ( $args['echo'] ) {
		echo wp_kses_post( $menu_html );
	} else {
		return $menu_html;
	}
}

/**
 * Retrieves a post meta field for the given post ID, returning a default value if the field doesn't exist.
 *
 * @since 1.0.0
 *
 * @param int    $post_id   Post ID.
 * @param string $fieldname The meta key to retrieve.
 * @param mixed  $default   Optional. Value to return if no metadata exist with this key. Default false.
 *
 * @return mixed
 */
function ignition_get_post_meta( $post_id, $fieldname, $default = false ) {
	if ( metadata_exists( 'post', $post_id, $fieldname ) ) {
		$value = get_post_meta( $post_id, $fieldname, true );
	} else {
		$value = $default;
	}

	return $value;
}

/**
 * Retrieves a term meta field for the given term ID, returning a default value if the field doesn't exist.
 *
 * @since 1.0.0
 *
 * @param int    $term_id   Term ID.
 * @param string $fieldname The meta key to retrieve.
 * @param mixed  $default   Optional. Value to return if no metadata exist with this key. Default false.
 *
 * @return mixed
 */
function ignition_get_term_meta( $term_id, $fieldname, $default = false ) {
	if ( metadata_exists( 'term', $term_id, $fieldname ) ) {
		$value = get_term_meta( $term_id, $fieldname, true );
	} else {
		$value = $default;
	}

	return $value;
}

/**
 * Retrieves a user meta field (global on multisite) for the given user ID, returning a default value if the field doesn't exist.
 *
 * @since 1.3.0
 *
 * @param int    $user_id   User ID.
 * @param string $fieldname The meta key to retrieve.
 * @param mixed  $default   Optional. Value to return if no metadata exist with this key. Default false.
 *
 * @return mixed
 */
function ignition_get_user_meta( $user_id, $fieldname, $default = false ) {
	if ( metadata_exists( 'user', $user_id, $fieldname ) ) {
		$value = get_user_meta( $user_id, $fieldname, true );
	} else {
		$value = $default;
	}

	return $value;
}

/**
 * Retrieves a user meta field (blog-specific on multisite) for the given user ID, returning a default value if the field doesn't exist.
 *
 * @since 1.3.0
 *
 * @param int    $user_id   User ID.
 * @param string $fieldname The meta key to retrieve.
 * @param mixed  $default   Optional. Value to return if no metadata exist with this key. Default false.
 *
 * @return mixed
 */
function ignition_get_user_option( $user_id, $fieldname, $default = false ) {
	global $wpdb;
	$prefix = $wpdb->get_blog_prefix();

	if ( metadata_exists( 'user', $user_id, $prefix . $fieldname ) ) {
		$value = get_user_meta( $user_id, $prefix . $fieldname, true );
	} else {
		$value = $default;
	}

	return $value;
}

/**
 * Accepts a value and returns "on" or "off" depending
 * on whether the value is truthy or falsy.
 *
 * @since 1.0.0
 *
 * @param int|string $value
 *
 * @return string
 */
function ignition_to_on_off( $value ) {
	if ( $value ) {
		return 'on';
	}
	return 'off';
}

/**
 * Returns the caption of an image, to be used in a lightbox.
 *
 * @since 1.0.0
 *
 * @uses get_post_thumbnail_id()
 * @uses wp_prepare_attachment_for_js()
 *
 * @param int|false $image_id Optional. The image's attachment ID. When false, the current post's featured image will be used.
 *                            Default false.
 *
 * @return string
 */
function ignition_get_image_lightbox_caption( $image_id = false ) {
	if ( false === $image_id ) {
		$image_id = get_post_thumbnail_id();
	}

	$lightbox_caption = '';

	$attachment = wp_prepare_attachment_for_js( $image_id );

	if ( is_array( $attachment ) ) {
		$field = apply_filters( 'ignition_image_lightbox_caption_field', 'caption', $image_id, $attachment );

		if ( array_key_exists( $field, $attachment ) ) {
			$lightbox_caption = $attachment[ $field ];
		}
	}

	return $lightbox_caption;
}

/**
 * Returns the caption of an image.
 *
 * @since 1.9.0
 *
 * @uses get_post_thumbnail_id()
 * @uses wp_prepare_attachment_for_js()
 *
 * @param int|false $image_id Optional. The image's attachment ID. When false, the current post's featured image will be used.
 *                            Default false.
 *
 * @return string
 */
function ignition_get_image_caption( $image_id = false ) {
	if ( false === $image_id ) {
		$image_id = get_post_thumbnail_id();
	}

	$caption = '';

	$attachment = wp_prepare_attachment_for_js( $image_id );

	if ( is_array( $attachment ) && ! empty( $attachment['caption'] ) ) {
		$caption = $attachment['caption'];
	}

	return $caption;
}

/**
 * Returns an array of the supported social networks and their properties.
 *
 * @since 1.3.0
 *
 * @return array
 */
function ignition_get_social_networks() {
	/**
	 * Filters the list of supported social networks.
	 *
	 * @since 1.3.0
	 *
	 * @param array $networks
	 */
	return apply_filters( 'ignition_social_networks', array(
		'500px'       => array(
			'name'  => '500px',
			'label' => _x( '500px', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-500px',
		),
		'bandcamp'    => array(
			'name'  => 'bandcamp',
			'label' => _x( 'Bandcamp', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-bandcamp',
		),
		'behance'     => array(
			'name'  => 'behance',
			'label' => _x( 'Behance', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-behance-square',
		),
		'codepen'     => array(
			'name'  => 'codepen',
			'label' => _x( 'CodePen', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-codepen',
		),
		'deviantart'  => array(
			'name'  => 'deviantart',
			'label' => _x( 'DeviantArt', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-deviantart',
		),
		'dribbble'    => array(
			'name'  => 'dribbble',
			'label' => _x( 'Dribbble', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-dribbble',
		),
		'etsy'        => array(
			'name'  => 'etsy',
			'label' => _x( 'Etsy', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-etsy',
		),
		'facebook'    => array(
			'name'  => 'facebook',
			'label' => _x( 'Facebook', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-facebook-square',
		),
		'flickr'      => array(
			'name'  => 'flickr',
			'label' => _x( 'Flickr', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-flickr',
		),
		'foursquare'  => array(
			'name'  => 'foursquare',
			'label' => _x( 'Foursquare', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-foursquare',
		),
		'github'      => array(
			'name'  => 'github',
			'label' => _x( 'GitHub', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-github-square',
		),
		'goodreads'   => array(
			'name'  => 'goodreads',
			'label' => _x( 'Goodreads', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-goodreads',
		),
		'hackernews'  => array(
			'name'  => 'hackernews',
			'label' => _x( 'Hacker News', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-hacker-news',
		),
		'instagram'   => array(
			'name'  => 'instagram',
			'label' => _x( 'Instagram', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-instagram-square',
		),
		'lastfm'      => array(
			'name'  => 'lastfm',
			'label' => _x( 'Last.fm', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-lastfm-square',
		),
		'linkedin'    => array(
			'name'  => 'linkedin',
			'label' => _x( 'LinkedIn', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-linkedin',
		),
		'medium'      => array(
			'name'  => 'medium',
			'label' => _x( 'Medium', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-medium',
		),
		'mixcloud'    => array(
			'name'  => 'mixcloud',
			'label' => _x( 'Mixcloud', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-mixcloud',
		),
		'pinterest'   => array(
			'name'  => 'pinterest',
			'label' => _x( 'Pinterest', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-pinterest-square',
		),
		'reddit'      => array(
			'name'  => 'reddit',
			'label' => _x( 'Reddit', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-reddit',
		),
		'snapchat'    => array(
			'name'  => 'snapchat',
			'label' => _x( 'Snapchat', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-snapchat',
		),
		'soundcloud'  => array(
			'name'  => 'soundcloud',
			'label' => _x( 'SoundCloud', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-soundcloud',
		),
		'spotify'     => array(
			'name'  => 'spotify',
			'label' => _x( 'Spotify', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-spotify',
		),
		'steam'       => array(
			'name'  => 'steam',
			'label' => _x( 'Steam', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-steam-square',
		),
		'stumbleupon' => array(
			'name'  => 'stumbleupon',
			'label' => _x( 'StumbleUpon', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-stumbleupon',
		),
		'tripadvisor' => array(
			'name'  => 'tripadvisor',
			'label' => _x( 'TripAdvisor', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-tripadvisor',
		),
		'tumblr'      => array(
			'name'  => 'tumblr',
			'label' => _x( 'Tumblr', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-tumblr-square',
		),
		'twitch'      => array(
			'name'  => 'twitch',
			'label' => _x( 'Twitch', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-twitch',
		),
		'twitter'     => array(
			'name'  => 'twitter',
			'label' => _x( 'Twitter', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-twitter-square',
		),
		'vimeo'       => array(
			'name'  => 'vimeo',
			'label' => _x( 'Vimeo', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-vimeo',
		),
		'yelp'        => array(
			'name'  => 'yelp',
			'label' => _x( 'Yelp', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-yelp',
		),
		'youtube'     => array(
			'name'  => 'youtube',
			'label' => _x( 'YouTube', 'social network name', 'ignition' ),
			'icon'  => 'ignition-icons ignition-icons-youtube-square',
		),
	) );
}

/**
 * Outputs the current author's social icons HTML.
 *
 * @since 1.3.0
 */
function ignition_the_user_social_icons() {
	$networks      = ignition_get_social_networks();
	$used_networks = array();

	// Set the target attribute for social icons.
	$add_target_blank = true;

	foreach ( $networks as $slug => $network ) {
		$fieldname = "ignition_social_{$network['name']}";
		$url       = get_the_author_meta( $fieldname );
		if ( $url ) {
			$used_networks[ $slug ] = array_merge( $network, array(
				'url' => $url,
			) );
		}
	}

	/**
	 * Filters the list of the current author's used social networks.
	 *
	 * @since 1.3.0
	 *
	 * @param array $used_networks
	 */
	$used_networks = apply_filters( 'ignition_user_social_icons', $used_networks );

	$has_social_icons = ! empty( $used_networks );

	/**
	 * Hook: ignition_before_the_user_social_icons.
	 *
	 * @since 1.3.0
	 *
	 * @param bool $has_social_icons
	 */
	do_action( 'ignition_before_the_user_social_icons', $has_social_icons );

	if ( $has_social_icons ) {
		?>
		<ul class="list-social-icons user-social-icons">
			<?php
				$template = '<li><a href="%1$s" class="social-icon"><span class="%2$s"></span></a></li>';

				foreach ( $used_networks as $network ) {
					$html = sprintf( $template,
						esc_url( $network['url'] ),
						esc_attr( $network['icon'] )
					);

					if ( $add_target_blank ) {
						$html = links_add_target( $html, '_blank' );
					}

					echo wp_kses( $html, ignition_get_allowed_tags() );
				}
			?>
		</ul>
		<?php
	}

	/**
	 * Hook: ignition_after_the_user_social_icons.
	 *
	 * @since 1.3.0
	 *
	 * @param bool $has_social_icons
	 */
	do_action( 'ignition_after_the_user_social_icons', $has_social_icons );
}

/**
 * Executes functions hooked on a specific action hook, and triggers before and after hooks.
 *
 * @since 1.3.0
 *
 * @param string $tag    The name of the action to be executed.
 * @param mixed  ...$arg Optional. Additional arguments which are passed on to do_action() and the
 *                       functions hooked to the action. Default empty.
 */
function ignition_do_action( $tag, ...$arg ) {
	if ( has_action( $tag ) ) {
		do_action( "before_action_{$tag}", $tag, ...$arg );
		do_action( $tag, ...$arg );
		do_action( "after_action_{$tag}", $tag, ...$arg );
	}
}

/**
 * Returns information about a video URL.
 *
 * Information regarding the passed URL includes provider (youtube/vimeo/self-hosted), the video ID and start time.
 * Not all information applies to all providers.
 *
 * @since 1.9.0
 *
 * @param string $url
 *
 * @return array
 */
function ignition_get_video_url_info( $url ) {
	$is_vimeo   = preg_match( '#(?:https?://)?(?:www\.)?vimeo\.com/([A-Za-z0-9\-_]+)#', $url, $vimeo_id );
	$is_youtube = preg_match( '~
		# Match non-linked youtube URL in the wild. (Rev:20111012)
		https?://         # Required scheme. Either http or https.
		(?:[0-9A-Z-]+\.)? # Optional subdomain.
		(?:               # Group host alternatives.
		  youtu\.be/      # Either youtu.be,
		| youtube\.com    # or youtube.com followed by
		  \S*             # Allow anything up to VIDEO_ID,
		  [^\w\-\s]       # but char before ID is non-ID char.
		)                 # End host alternatives.
		([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
		(?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
		(?!               # Assert URL is not pre-linked.
		  [?=&+%\w]*      # Allow URL (query) remainder.
		  (?:             # Group pre-linked alternatives.
			[\'"][^<>]*>  # Either inside a start tag,
		  | </a>          # or inside <a> element text contents.
		  )               # End recognized pre-linked alts.
		)                 # End negative lookahead assertion.
		[?=&+%\w-]*        # Consume any URL (query) remainder.
		~ix',
	$url, $youtube_id );

	$info = array(
		'supported'  => false,
		'provider'   => '',
		'video_id'   => '',
		'start_time' => '',
	);

	if ( empty( $url ) ) {
		return $info;
	}

	$supported_extensions = apply_filters( 'ignition_video_url_info_supported_self_hosted_extensions', array(
		'mp4',
		'webm',
		'ogv',
	) );

	if ( $is_youtube ) {
		$time_param = '';
		$url_parts  = wp_parse_url( $url );
		if ( ! empty( $url_parts['query'] ) ) {
			$query = wp_parse_args( $url_parts['query'] );
			if ( ! empty( $query['t'] ) ) {
				$time_param = $query['t'];
			}
		}

		$info['supported']  = true;
		$info['provider']   = 'youtube';
		$info['video_id']   = $youtube_id[1];
		$info['start_time'] = $time_param;
	} elseif ( $is_vimeo ) {
		$time_param = '';
		$url_parts  = wp_parse_url( $url );

		if ( ! empty( $url_parts['fragment'] ) ) {
			$found = preg_match( '/t=(\d+)s/', $url_parts['fragment'], $matches );
			if ( $found ) {
				$time_param = $matches[1];
			}
		}

		$info['supported']  = true;
		$info['provider']   = 'vimeo';
		$info['video_id']   = $vimeo_id[1];
		$info['start_time'] = $time_param;
	} else {
		$url_parts = wp_parse_url( $url );

		$path = ! empty( $url_parts['path'] ) ? $url_parts['path'] : '';
		$path = untrailingslashit( $path );

		// This regex looks like: /\.(mp4|webm)$/
		$pattern = '/\.(' . implode( '|', $supported_extensions ) . ')$/';

		if ( preg_match( $pattern, $path ) ) {
			$info['supported'] = true;
			$info['provider']  = 'self';
			$info['video_id']  = $url;
		}
	}

	return apply_filters( 'ignition_video_url_info', $info, $url );
}

/**
 * Returns the appropriate page(d) query variable to use in custom loops (needed for pagination).
 *
 * @uses get_query_var()
 *
 * @since 2.0.0
 *
 * @param int $default_return The default page number to return, if no query vars are set.
 *
 * @return int The appropriate paged value if found, 0 otherwise.
 */
function ignition_get_page_var( $default_return = 0 ) {
	$paged = get_query_var( 'paged', false );
	$page  = get_query_var( 'page', false );

	if ( false === $paged && false === $page ) {
		return $default_return;
	}

	return max( $paged, $page );
}

/**
 * Retrieve or display list of posts as a dropdown (select list).
 *
 * This function is a modified copy of wp_dropdown_pages() as of WordPress v5.7.2
 *
 * @since 2.0.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @param string $name Optional. Name of the select box.
 * @return string HTML content, if not displaying.
 */
function ignition_dropdown_posts( $args = '', $name = 'post_id' ) {
	$defaults = array(
		'depth'                 => 0,
		'child_of'              => 0,
		'selected'              => 0,
		'echo'                  => 1,
		//'name'                  => 'page_id', // With this line, get_posts() doesn't work properly.
		'id'                    => '',
		'class'                 => '',
		'show_option_none'      => '',
		'show_option_no_change' => '',
		'option_none_value'     => '',
		'value_field'           => 'ID',
		'post_type'             => 'post',
		'post_status'           => 'publish',
		'suppress_filters'      => false,
		'numberposts'           => -1,
		'hide_empty'            => false, // If no posts are found, an empty <select> will be returned/echoed.
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$hierarchical_post_types = get_post_types( array( 'hierarchical' => true ) );
	if ( in_array( $parsed_args['post_type'], $hierarchical_post_types, true ) ) {
		$pages = get_pages( $parsed_args );
	} else {
		$pages = get_posts( $parsed_args );
	}

	$output = '';
	// Back-compat with old system where both id and name were based on $name argument.
	if ( empty( $parsed_args['id'] ) ) {
		$parsed_args['id'] = $name;
	}

	if ( ! empty( $pages ) || false === (bool) $parsed_args['hide_empty'] ) {
		$class = '';
		if ( ! empty( $parsed_args['class'] ) ) {
			$class = " class='" . esc_attr( $parsed_args['class'] ) . "'";
		}

		$output = "<select name='" . esc_attr( $name ) . "'" . $class . " id='" . esc_attr( $parsed_args['id'] ) . "'>\n";
		if ( $parsed_args['show_option_no_change'] ) {
			$output .= "\t<option value=\"-1\">" . $parsed_args['show_option_no_change'] . "</option>\n";
		}
		if ( $parsed_args['show_option_none'] ) {
			$output .= "\t<option value=\"" . esc_attr( $parsed_args['option_none_value'] ) . '">' . $parsed_args['show_option_none'] . "</option>\n";
		}
		if ( ! empty( $pages ) ) {
			$output .= walk_page_dropdown_tree( $pages, $parsed_args['depth'], $parsed_args );
		}
		$output .= "</select>\n";
	}

	/**
	 * Filters the HTML output of a list of pages as a drop down.
	 *
	 * @since 2.0.0
	 *
	 * @param string    $output      HTML output for drop down list of pages.
	 * @param string    $name        Select's name attribute.
	 * @param array     $parsed_args The parsed arguments array. See wp_dropdown_pages()
	 *                               for information on accepted arguments.
	 * @param WP_Post[] $pages       Array of the page objects.
	 */
	$html = apply_filters( 'ignition_dropdown_posts', $output, $name, $parsed_args, $pages );

	if ( $parsed_args['echo'] ) {
		echo wp_kses( $html, array(
			'select'   => array(
				'id'    => true,
				'class' => true,
				'name'  => true,
			),
			'option'   => array(
				'value'    => true,
				'class'    => true,
				'selected' => true,
			),
			'optgroup' => array(
				'label'    => true,
				'disabled' => true,
			),
		) );
	}

	return $html;
}
