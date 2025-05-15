<?php
/**
 * Actions and filters that affect core WordPress functionality
 *
 * @since 1.0.0
 */

add_action( 'wp_head', 'ignition_pingback_header' );
/**
 * Adds a pingback url auto-discovery header for singularly identifiable articles.
 *
 * @since 1.0.0
 */
function ignition_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}

add_filter( 'body_class', 'ignition_body_class', 10, 2 );
/**
 * Adds classes on the body tag.
 *
 * @since 1.0.0
 *
 * @param string[] $classes An array of body class names.
 * @param string[] $class   An array of additional class names added to the body.
 *
 * @return array
 */
function ignition_body_class( $classes, $class ) {
	if ( is_customize_preview() ) {
		$classes[] = 'is-customize-preview';
	}

	$defaults = ignition_customizer_defaults( 'all' );

	$data      = ignition_page_title_get_data();
	$classes[] = "ignition-header-type-{$data['layout_type']}";
	$classes[] = sprintf( 'ignition-page-title-bg-%s', ignition_to_on_off( $data['with_background'] ) );
	$classes[] = sprintf( 'ignition-page-title-normal-%s', ignition_to_on_off( $data['normal_title'] ) );
	$classes[] = sprintf( 'ignition-page-title-subtitle-%s', ignition_to_on_off( $data['normal_subtitle'] ) );
	$classes[] = sprintf( 'ignition-page-breadcrumb-%s', ignition_to_on_off( $data['breadcrumb'] ) );

	$value     = get_theme_mod( 'site_layout_type', $defaults['site_layout_type'] );
	$classes[] = "ignition-site-layout-{$value}";

	$value     = ignition_to_on_off( get_theme_mod( 'top_bar_layout_is_visible', $defaults['top_bar_layout_is_visible'] ) );
	$classes[] = "ignition-top-bar-visible-{$value}";

	$value     = get_theme_mod( 'header_layout_menu_type', $defaults['header_layout_menu_type'] );
	$classes[] = "ignition-header-menu-layout-{$value}";

	$value     = ignition_to_on_off( get_theme_mod( 'header_layout_is_full_width', $defaults['header_layout_is_full_width'] ) );
	$classes[] = "ignition-header-fullwidth-{$value}";

	$value     = get_theme_mod( 'header_layout_menu_sticky_type', $defaults['header_layout_menu_sticky_type'] );
	$is_sticky = ignition_to_on_off( 'off' !== ignition_sanitize_sticky_menu_type( $value ) ? true : false );
	$classes[] = "ignition-header-sticky-{$is_sticky}";
	$classes[] = "ignition-header-sticky-type-{$value}";

	$value     = ignition_to_on_off( get_theme_mod( 'header_layout_menu_mobile_slide_right', $defaults['header_layout_menu_mobile_slide_right'] ) );
	$classes[] = "ignition-mobile-nav-slide-right-{$value}";

	$value     = get_theme_mod( 'page_title_with_background_text_align_horizontal', $defaults['page_title_with_background_text_align_horizontal'] );
	$classes[] = "ignition-page-title-horz-align-{$value}";

	$value     = get_theme_mod( 'blog_archive_layout_type', $defaults['blog_archive_layout_type'] );
	$classes[] = "ignition-blog-layout-{$value}";

	$value     = get_theme_mod( 'blog_single_layout_type', $defaults['blog_single_layout_type'] );
	$classes[] = "ignition-blog-single-layout-{$value}";

	$value     = get_theme_mod( 'blog_archive_posts_layout_type', $defaults['blog_archive_posts_layout_type'] );
	$classes[] = "ignition-blog-posts-layout-{$value}";

	$value     = ignition_to_on_off( get_theme_mod( 'footer_is_visible', $defaults['footer_is_visible'] ) );
	$classes[] = "ignition-footer-visible-{$value}";

	$value     = ignition_to_on_off( get_theme_mod( 'site_identity_title_is_visible', $defaults['site_identity_title_is_visible'] ) );
	$classes[] = "ignition-site-title-{$value}";

	$value     = ignition_to_on_off( get_theme_mod( 'site_identity_description_is_visible', $defaults['site_identity_description_is_visible'] ) );
	$classes[] = "ignition-site-description-{$value}";

	if ( is_singular() ) {
		$value     = ignition_to_on_off( ignition_get_post_meta( get_queried_object_id(), 'single_remove_main_padding' ) );
		$classes[] = "ignition-no-main-padding-{$value}";
	}

	// Remove empty entries.
	$classes = array_filter( $classes );

	return $classes;
}

add_filter( 'excerpt_length', 'ignition_excerpt_length' );
/**
 * Modifies the excerpt length according to the user option.
 *
 * @since 1.0.0
 *
 * @param int $length The maximum number of words. Default 55.
 *
 * @return int
 */
function ignition_excerpt_length( $length ) {
	return (int) get_theme_mod( 'blog_archive_excerpt_length', ignition_customizer_defaults( 'blog_archive_excerpt_length' ) );
}

add_filter( 'wp_link_pages_args', 'ignition_wp_link_pages_default_args' );
/**
 * Adds default wp_link_pages() arguments.
 *
 * @since 1.0.0
 *
 * @param array $args An array of arguments for page links for paginated posts.
 *
 * @return array
 */
function ignition_wp_link_pages_default_args( $args ) {
	$new_args = array(
		'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'ignition' ),
		'after'       => '</div>',
		'link_before' => '<span class="page-number">',
		'link_after'  => '</span>',
	);

	$args = wp_parse_args( $new_args, $args );

	return $args;
}

add_filter( 'embed_oembed_html', 'ignition_oembed_responsive_wrapper', 10, 4 );
/**
 * Wraps oEmbed markup into a responsive wrapper.
 *
 * @since 1.0.0
 *
 * @param string       $url     The attempted embed URL.
 * @param array        $attr    An array of shortcode attributes.
 * @param int          $post_ID Post ID.
 * @param string|false $cache   The cached HTML result, stored in post meta.
 *
 * @return string
 */
function ignition_oembed_responsive_wrapper( $cache, $url, $attr, $post_ID ) {
	if ( empty( $cache ) ) {
		return $cache;
	}

	$url_patterns = array(
		'youtube.com',
		'youtu.be',
		'youtube-nocookie.com', // This doesn't seem to embed anything.
		'vimeo.com',
		'dailymotion.com',
		'dai.ly', // This doesn't seem to embed anything.
		'hulu.com',
		'wordpress.tv',
		'slideshare.net',
	);

	$match = false;

	foreach ( $url_patterns as $url_pattern ) {
		$pattern = 'https?://.*?' . preg_quote( $url_pattern, '#' );
		if ( preg_match( '#' . $pattern . '#', $url ) ) {
			$match = true;
			break;
		}
	}

	if ( $match ) {
		$cache = '<div class="ignition-responsive-embed">' . $cache . '</div>';
	}

	return $cache;
}

add_action( 'wp_body_open', 'ignition_skip_link', 5 );
/**
 * Include a skip to content link at the top of the page so that users can bypass the menu.
 *
 * @since 1.0.0
 */
function ignition_skip_link() {
	?><div><a class="skip-link sr-only sr-only-focusable" href="#site-content"><?php esc_html_e( 'Skip to the content', 'ignition' ); ?></a></div><?php
}

add_filter( 'get_post_metadata', 'ignition_hide_single_featured_get_post_metadata', 10, 4 );
/**
 * Filters the post thumbnail metadata, conditionally disabling it based on a user option.
 *
 * @since 1.0.0
 *
 * @param mixed  $value     The value to return, either a single metadata value or an array
 *                          of values depending on the value of `$single`. Default null.
 * @param int    $post_id   ID of the object metadata is for.
 * @param string $meta_key  Metadata key.
 * @param bool   $single    Whether to return only the first value of the specified `$meta_key`.

 * @return false
 */
function ignition_hide_single_featured_get_post_metadata( $value, $post_id, $meta_key, $single ) {
	if ( '_thumbnail_id' !== $meta_key ) {
		return $value;
	}

	// DO NOT uncomment the following. It's only left here as an example of what NOT to do.
	// It affects post types that don't declare support for thumbnails, yet use them. e.g. WooCommerce Variations,
	/*
	if ( ! post_type_supports( get_post_type( $post_id ), 'thumbnail' ) ) {
		return false;
	}
	*/

	if ( ( is_single( $post_id ) || is_page( $post_id ) ) && in_array( get_post_type( $post_id ), ignition_get_single_featured_image_visibility_post_types(), true ) ) {
		$is_hidden = ignition_get_post_meta( $post_id, 'single_featured_image_is_hidden', false );

		// Only affect the result if we need to explicitly disable the thumbnail.
		if ( $is_hidden ) {
			return false;
		}
	}

	return $value;
}

add_filter( 'get_custom_logo', 'ignition_get_custom_logo_filter', 10, 2 );
/**
 * Filters and returns the correct logo (normal/alternative) depending on header type.
 *
 * Also handles case where an alternative logo is set but a normal one isn't.
 *
 * @since 1.0.0
 *
 * @param string $html    Custom logo HTML output.
 * @param int    $blog_id ID of the blog to get the custom logo for.
 *
 * @return string
 */
function ignition_get_custom_logo_filter( $html, $blog_id ) {
	$logo     = get_theme_mod( 'custom_logo' );
	$logo_alt = get_theme_mod( 'site_identity_custom_logo_alt' );

	if ( empty( $logo ) && empty( $logo_alt ) ) {
		return $html;
	}

	// $html contains display:none when a logo is not set but we're previewing in customizer.
	// Both cases mean that $logo is not set.
	if ( empty( $html ) || false !== strpos( $html, 'display:none' ) ) {
		$custom_logo_id = $logo_alt;

		// The following code is identical to get_custom_logo() as of WP 5.0.3
		$custom_logo_attr = array(
			'class'    => 'custom-logo custom-logo-alt',
			'itemprop' => 'logo',
		);

		/*
		 * If the logo alt attribute is empty, get the site title and explicitly
		 * pass it to the attributes used by wp_get_attachment_image().
		 */
		$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
		if ( empty( $image_alt ) ) {
			$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
		}

		/*
		 * If the alt attribute is not empty, there's no need to explicitly pass
		 * it because wp_get_attachment_image() already adds the alt attribute.
		 */
		$html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
			esc_url( home_url( '/' ) ),
			wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr )
		);
	} elseif ( $logo && $logo_alt ) {
		// We have both a logo and logo_alt. Show the correct one depending on header type.
		if ( 'transparent' === ignition_get_mod( 'header_layout_type', ignition_customizer_defaults( 'header_layout_type' ) ) ) {
			$alt_url = wp_get_attachment_image_url( $logo_alt, 'full' );

			$html = preg_replace( '/src="(.*?)"/', 'src="' . esc_url( $alt_url ) . '"', $html );
		}

		$data_attributes = sprintf( 'data-logo="%s" data-logo-alt="%s" ',
			esc_url( wp_get_attachment_image_url( $logo, 'full' ) ),
			esc_url( wp_get_attachment_image_url( $logo_alt, 'full' ) )
		);

		$html = str_replace( '<img ', '<img ' . $data_attributes, $html );
	}

	// Strip srcset and sizes attributes.
	$html = preg_replace( '/srcset="(.*?)"/', '', $html );
	$html = preg_replace( '/sizes="(.*?)"/', '', $html );

	return $html;
}

add_filter( 'the_content', 'ignition_lightbox_add_rel', 12 );
add_filter( 'get_comment_text', 'ignition_lightbox_add_rel' );
/**
 * Enable lightbox in content and comments, if applicable.
 *
 * @since 1.0.0
 *
 * @param string $content
 *
 * @return string
 */
function ignition_lightbox_add_rel( $content ) {
	if ( get_theme_mod( 'utilities_lightbox_is_enabled', ignition_customizer_defaults( 'utilities_lightbox_is_enabled' ) ) ) {
		global $post;
		$pattern     = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
		$replacement = '<a$1href=$2$3.$4$5 data-lightbox="gal[' . $post->ID . ']"$6>$7</a>';
		$content     = preg_replace( $pattern, $replacement, $content );
	}

	return $content;
}

add_filter( 'wp_get_attachment_link', 'ignition_wp_get_attachment_link_lightbox_caption', 10, 6 );
/**
 * Enables lightbox captions, if applicable.
 *
 * @since 1.0.0
 *
 * @param string       $html      The page link HTML output.
 * @param int          $id        Post ID.
 * @param string|array $size      Size of the image. Image size or array of width and height values (in that order).
 * @param bool         $permalink Whether to add permalink to image. Default false.
 * @param bool         $icon      Whether to include an icon. Default false.
 * @param string|bool  $text      If string, will be link text. Default false.
 *
 * @return string
 */
function ignition_wp_get_attachment_link_lightbox_caption( $html, $id, $size, $permalink, $icon, $text ) {
	if ( get_theme_mod( 'utilities_lightbox_is_enabled', ignition_customizer_defaults( 'utilities_lightbox_is_enabled' ) ) && false === $permalink ) {
		$found = preg_match( '#(<a.*?>)<img.*?></a>#', $html, $matches );
		if ( $found ) {
			$found_title = preg_match( '#title=([\'"])(.*?)\1#', $matches[1], $title_matches );

			// Only continue if title attribute doesn't exist.
			if ( 0 === $found_title ) {
				$caption = ignition_get_image_lightbox_caption( $id );

				if ( $caption ) {
					$new_a = $matches[1];
					$new_a = rtrim( $new_a, '>' );
					$new_a = $new_a . ' title="' . esc_attr( $caption ) . '">';

					$html = str_replace( $matches[1], $new_a, $html );
				}
			}
		}
	}

	return $html;
}

add_filter( 'current_theme_supports-ignition-template-sidebar-image-meta', 'ignition_handle_current_theme_supports_array', 10, 3 );
add_filter( 'current_theme_supports-ignition-event', 'ignition_handle_current_theme_supports_array', 10, 3 );
/**
 * Handles whether the current theme supports a specific feature.
 * Requires that the theme support has additional arguments declared as an array, e.g.:
 *   add_theme_support( 'theme-feature', array(
 *     'arg1',
 *     'arg2',
 *   ) );
 *
 * @since 1.1.0
 *
 * @param bool  $supports     Whether the current theme supports the given feature. Default true.
 * @param array $check_args   Array of arguments to check.
 * @param array $feature_args Array of arguments the theme supports.
 *
 * @return bool
 */
function ignition_handle_current_theme_supports_array( $supports, $check_args, $feature_args ) {
	if ( ! is_array( $feature_args ) ) {
		return false;
	}

	return in_array( $check_args[0], $feature_args[0], true );
}

add_filter( 'current_theme_supports-ignition-team', 'ignition_handle_current_theme_supports_key_value_array', 10, 3 );
/**
 * Handles whether the current theme supports a specific feature.
 * Requires that the theme support has additional arguments declared as a key-value array, e.g.:
 *   add_theme_support( 'theme-feature', array(
 *     'key1' => 'value1',
 *     'key2' => 'value2',
 *   ) );
 *
 * @since 1.1.0
 *
 * @param bool  $supports     Whether the current theme supports the given feature. Default true.
 * @param array $check_args   Array of arguments to check.
 * @param array $feature_args Array of arguments the theme supports.
 *
 * @return bool
 */
function ignition_handle_current_theme_supports_key_value_array( $supports, $check_args, $feature_args ) {
	if ( ! is_array( $feature_args ) ) {
		return false;
	}

	$type = $check_args[0];

	$return = false;
	if ( array_key_exists( $type, $feature_args[0] ) ) {
		$return = true;
	}

	if ( true === $return && isset( $check_args[1] ) ) {
		if ( $feature_args[0][ $type ] !== $check_args[1] ) {
			$return = false;
		}
	}

	return $return;
}

add_filter( 'get_post_metadata', 'ignition_revert_non_applicable_post_metadata', 10, 4 );
/**
 * Modifies the metadata values returned, to be compatible with the current theme's Ignition options support.
 *
 * For example, if a page was created with a transparent header, but this theme doesn't support transparent headers,
 * we need to return the default value declared by the theme (or some other value that actually makes sense
 * in the current context).
 *
 * @since 1.4.0
 *
 * @param mixed  $value     The value to return, either a single metadata value or an array
 *                          of values depending on the value of `$single`. Default null.
 * @param int    $object_id ID of the object metadata is for.
 * @param string $meta_key  Metadata key.
 * @param bool   $single    Whether to return only the first value of the specified `$meta_key`.
 */
function ignition_revert_non_applicable_post_metadata( $value, $object_id, $meta_key, $single ) {
	$keys = array(
		'header_layout_type',
		'page_title_with_background_is_visible',
	);

	if ( ! in_array( $meta_key, $keys, true ) ) {
		return $value;
	}

	remove_filter( 'get_post_metadata', 'ignition_revert_non_applicable_post_metadata', 10 );
	$meta_value = get_post_meta( $object_id, $meta_key, $single );
	add_filter( 'get_post_metadata', 'ignition_revert_non_applicable_post_metadata', 10, 4 );

	if ( 'header_layout_type' === $meta_key && is_scalar( $meta_value ) && ! array_key_exists( $meta_value, ignition_header_layout_type_choices() ) ) {
		$value = ''; // Respect customizer setting.
	}

	if ( 'page_title_with_background_is_visible' === $meta_key && ! current_theme_supports( 'ignition-page-title-with-background' ) ) {
		$value = ''; // Respect customizer setting.
	}

	return $value;
}

add_filter( 'extra_theme_headers', 'ignition_add_ignition_theme_headers' );
/**
 * Read Ignition headers when reading theme and plugin headers.
 *
 * @since 1.6.3
 *
 * @param array $headers Headers.
 *
 * @return array
 */
function ignition_add_ignition_theme_headers( $headers ) {
	$headers[] = 'RequiresIgnition';

	return $headers;
}
