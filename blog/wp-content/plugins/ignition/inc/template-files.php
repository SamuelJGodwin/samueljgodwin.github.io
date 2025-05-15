<?php
/**
 * Template hooks that allow the plugin to fall-back onto its own template files
 *
 * @since 1.0.0
 */

add_filter( 'theme_templates', 'ignition_register_page_templates', 10, 4 );
/**
 * Adds the plugin-provided page templates with the rest of the theme's page templates.
 *
 * @since 1.0.0
 *
 * @param $post_templates
 * @param $wp_theme
 * @param $post
 * @param $post_type
 *
 * @return array
 */
function ignition_register_page_templates( $post_templates, $wp_theme, $post, $post_type ) {
	$plugin_templates = ignition_get_plugin_page_templates();

	if ( ! empty( $plugin_templates[ $post_type ] ) ) {
		$post_templates = array_merge( $plugin_templates[ $post_type ], $post_templates );
	}

	if ( ! current_theme_supports( 'ignition-template-sidebar-image-meta' ) || ! current_theme_supports( 'ignition-template-sidebar-image-meta', $post_type ) ) {
		unset( $post_templates['templates/template-sidebar-image-meta.php'] );
	}

	return $post_templates;
}

add_filter( 'page_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'single_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'singular_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'index_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( '404_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'archive_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'author_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'category_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'tag_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'taxonomy_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'date_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'home_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'frontpage_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'privacypolicy_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'search_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'embed_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );
add_filter( 'attachment_template', 'ignition_maybe_locate_plugin_template_file', 10, 3 );

/**
 * Locates a template using Ignition's hierarchy rules, falling back to the passed template.
 *
 * Hooked to "{$type}_template" of get_query_template()
 *
 * @since 1.0.0
 *
 * @param string $template  Path to the template. See locate_template().
 * @param string $type      Sanitized filename without extension.
 * @param array  $templates A list of template candidates, in descending order of priority.
 *
 * @return string
 */
function ignition_maybe_locate_plugin_template_file( $template, $type, $templates ) {
	$theme_template = $template;

	foreach ( $templates as $t ) {
		$template = ignition_locate_template( $t );
		if ( $template ) {
			break;
		}
	}

	if ( ! $template ) {
		$template = $theme_template;
	}

	return $template;
}

/**
 * Returns an array of page templates and their titles, per post type.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_plugin_page_templates() {
	$templates      = ignition_get_page_templates_slugs();
	$post_templates = array();

	foreach ( $templates as $file ) {
		$full_path = untrailingslashit( IGNITION_DIR ) . '/template-files/' . $file;

		if ( ! preg_match( '|Template Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
			continue;
		}

		$types = array( 'page' );
		if ( preg_match( '|Template Post Type:(.*)$|mi', file_get_contents( $full_path ), $type ) ) {
			$types = explode( ',', _cleanup_header_comment( $type[1] ) );
		}

		foreach ( $types as $type ) {
			$type = sanitize_key( $type );
			if ( ! isset( $post_templates[ $type ] ) ) {
				$post_templates[ $type ] = array();
			}

			$post_templates[ $type ][ $file ] = _cleanup_header_comment( $header[1] );
		}
	}

	return $post_templates;
}

/**
 * Loads a sidebar template.
 *
 * Slightly modified copy of get_sidebar().
 *
 * @see get_sidebar()
 *
 * @since 1.0.0
 *
 * @param null|string $name The name of the specialised sidebar. Default null.
 */
function ignition_get_sidebar( $name = null ) {
	/**
	 * Fires before the sidebar template file is loaded.
	 *
	 * @since WP 2.2.0
	 * @since WP 2.8.0 $name parameter added.
	 *
	 * @param string|null $name Name of the specific sidebar file to use. null for the default sidebar.
	 */
	do_action( 'get_sidebar', $name );

	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "sidebar-{$name}.php";
	}

	$templates[] = 'sidebar.php';

	ignition_locate_template( $templates, true );
}

/**
 * Loads a template part into a template.
 *
 * Slightly modified copy of get_template_part().
 *
 * @see get_template_part()
 *
 * @since 1.0.0
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args Additional arguments passed to the template.
 */
function ignition_get_template_part( $slug, $name = '', $args = array() ) {
	/**
	 * Fires before the specified template part file is loaded.
	 *
	 * The dynamic portion of the hook name, `$slug`, refers to the slug name
	 * for the generic template part.
	 *
	 * @since WP 3.0.0
	 *
	 * @param string      $slug The slug name for the generic template.
	 * @param string|null $name The name of the specialized template.
	 */
	do_action( "get_template_part_{$slug}", $slug, $name );

	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	/**
	 * Fires before a template part is loaded.
	 *
	 * @since WP 5.2.0
	 *
	 * @param string   $slug      The slug name for the generic template.
	 * @param string   $name      The name of the specialized template.
	 * @param string[] $templates Array of template files to search for, in order.
	 */
	do_action( 'get_template_part', $slug, $name, $templates );

	ignition_locate_template( $templates, true, false, $args );
}

/**
 * Retrieves the name of the highest priority template file that exists.
 *
 * Slightly modified copy of locate_template().
 *
 * @see locate_template()
 *
 * @since 1.0.0
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool         $load           If true the template file will be loaded if it is found.
 * @param bool         $require_once   Whether to require_once or require. Default true. Has no effect if $load is false.
 * @param array        $args           Additional arguments passed to the template.
 *
 * @return string The template filename if one is located.
 */
function ignition_locate_template( $template_names, $load = false, $require_once = true, $args = array() ) {
	$plugin_path = trailingslashit( IGNITION_DIR ) . 'template-files';

	/**
	 * Filters the plugin's main widget areas.
	 *
	 * @since 2.2.2
	 *
	 * @param string $variation_path The variation's path inside the theme, without leading or trailing slashes.
	 *                               E.g. 'theme-variations/varisample'.
	 */
	$variation_path = apply_filters( 'ignition_locate_template_variation_path', '' );
	if ( $variation_path ) {
		$variation_path = trailingslashit( $variation_path );
	}

	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name ) {
			continue;
		}

		if ( file_exists( STYLESHEETPATH . '/' . $variation_path . $template_name ) ) {
			$located = STYLESHEETPATH . '/' . $variation_path . $template_name;
			break;
		} elseif ( file_exists( TEMPLATEPATH . '/' . $variation_path . $template_name ) ) {
			$located = TEMPLATEPATH . '/' . $variation_path . $template_name;
			break;
		} elseif ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {
			$located = STYLESHEETPATH . '/' . $template_name;
			break;
		} elseif ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {
			$located = TEMPLATEPATH . '/' . $template_name;
			break;
		} elseif ( file_exists( $plugin_path . '/' . $template_name ) ) {
			$located = $plugin_path . '/' . $template_name;
			break;
		} elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/' . $template_name ) ) {
			$located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
			break;
		}
	}

	if ( $load && '' != $located ) {
		load_template( $located, $require_once, $args );
	}

	return $located;
}

/**
 * Loads a plugin-provided template part into a template.
 *
 * Slightly modified copy of get_template_part().
 *
 * @see get_template_part()
 *
 * @since 1.2.1
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args Additional arguments passed to the template.
 */
function ignition_get_plugin_template_part( $slug, $name = '', $args = array() ) {
	/**
	 * Fires before the specified template part file is loaded.
	 *
	 * The dynamic portion of the hook name, `$slug`, refers to the slug name
	 * for the generic template part.
	 *
	 * @since 1.2.1
	 *
	 * @param string      $slug The slug name for the generic template.
	 * @param string|null $name The name of the specialized template.
	 */
	do_action( "ignition_get_plugin_template_part_{$slug}", $slug, $name );

	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	/**
	 * Fires before a template part is loaded.
	 *
	 * @since 1.2.1
	 *
	 * @param string   $slug      The slug name for the generic template.
	 * @param string   $name      The name of the specialized template.
	 * @param string[] $templates Array of template files to search for, in order.
	 */
	do_action( 'ignition_get_plugin_template_part', $slug, $name, $templates );

	ignition_locate_plugin_template( $templates, true, false, $args );
}


/**
 * Retrieves the name of the highest priority template file that exists in the plugin.
 *
 * Slightly modified copy of locate_template().
 *
 * @see locate_template()
 *
 * @since 1.2.1
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool         $load           If true the template file will be loaded if it is found.
 * @param bool         $require_once   Whether to require_once or require. Default true. Has no effect if $load is false.
 * @param array        $args           Additional arguments passed to the template.
 *
 * @return string The template filename if one is located.
 */
function ignition_locate_plugin_template( $template_names, $load = false, $require_once = true, $args = array() ) {
	$plugin_path = trailingslashit( IGNITION_DIR ) . 'template-files';

	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name ) {
			continue;
		}
		if ( file_exists( $plugin_path . '/' . $template_name ) ) {
			$located = $plugin_path . '/' . $template_name;
			break;
		}
	}

	if ( $load && '' != $located ) {
		load_template( $located, $require_once, $args );
	}

	return $located;
}
