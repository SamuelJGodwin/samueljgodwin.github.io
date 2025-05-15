<?php
/**
 * Layout-related functions and definitions
 *
 * @since 1.0.0
 */


add_action( 'template_redirect', 'ignition_content_width', 0 );
/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Hooked with priority 0 to make it available to lower priority callbacks.
 *
 * @since 1.0.0
 *
 * @global int $content_width
 */
function ignition_content_width() {
	$content_width = $GLOBALS['content_width'];

	$info          = ignition_get_layout_info();
	$content_width = $info['content_width'];

	/**
	 * Filters the global $content_width variable.
	 *
	 * @since 1.0.0
	 *
	 * @param int $content_width
	 */
	$GLOBALS['content_width'] = apply_filters( 'ignition_content_width', $content_width );
}

/**
 * Returns the HTML classes needed depending on the number of columns passed.
 *
 * @since 1.0.0
 * @since 1.3.0 $context
 *
 * @param int    $columns A number of columns.
 * @param string $context Optional. A context, used to differentiate between different usages.
 *
 * @return string
 */
function ignition_get_columns_classes( $columns, $context = '' ) {
	$columns = intval( $columns );

	switch ( $columns ) {
		case 1:
			$classes = 'col-12';
			break;
		case 2:
			$classes = 'col-md-6 col-12';
			break;
		case 3:
			$classes = 'col-md-4 col-12';
			break;
		case 4:
		default:
			$classes = 'col-xl-3 col-md-6 col-12';
			break;
	}

	if ( 3 === $columns && 'footer-widgets' === $context ) {
		$classes = 'col-lg-4 col-12';
	}

	/**
	 * Filters the classes returned, for a given number of columns.
	 *
	 * @since 1.0.0
	 * @since 1.3.0 $context
	 *
	 * @param string $classes
	 * @param int    $columns
	 * @param string $context
	 */
	return apply_filters( 'ignition_get_columns_classes', $classes, $columns, $context );
}

/**
 * Returns the HTML classes needed for blog items, depending on the number of columns passed.
 *
 * @since 1.0.0
 *
 * @param int $columns A number of columns.
 *
 * @return string
 */
function ignition_get_blog_columns_classes( $columns ) {
	switch ( intval( $columns ) ) {
		case 1:
			$classes = 'col-12';
			break;
		case 2:
			$classes = 'col-xl-6 col-12';
			break;
		case 3:
			$classes = 'col-xl-4 col-lg-6 col-12';
			break;
		case 4:
		default:
			$classes = 'col-xl-3 col-lg-6 col-12';
			break;
	}

	/**
	 * Filters the classes returned, for a given number of columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classes
	 * @param int    $columns
	 */
	return apply_filters( 'ignition_get_blog_columns_classes', $classes, $columns );
}

/**
 * Returns a list of plugin-provided page templates and their layout information.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_page_templates_info() {
	/**
	 * Filters the page templates made available by Ignition.
	 *
	 * @since 1.0.0
	 *
	 * @param array $templates
	 */
	return apply_filters( 'ignition_page_templates_info', array(
		'templates/template-content-sidebar.php'    => array(
			'slug'   => 'templates/template-content-sidebar.php',
			'layout' => 'content_sidebar',
		),
		'templates/template-sidebar-content.php'    => array(
			'slug'   => 'templates/template-sidebar-content.php',
			'layout' => 'sidebar_content',
		),
		'templates/template-canvas.php'             => array(
			'slug'   => 'templates/template-canvas.php',
			'layout' => 'fullwidth',
		),
		'templates/template-fullwidth.php'          => array(
			'slug'   => 'templates/template-fullwidth.php',
			'layout' => 'fullwidth',
		),
		'templates/template-fullwidth-boxed.php'    => array(
			'slug'   => 'templates/template-fullwidth-boxed.php',
			'layout' => 'fullwidth_boxed',
		),
		'templates/template-fullwidth-narrow.php'   => array(
			'slug'   => 'templates/template-fullwidth-narrow.php',
			'layout' => 'fullwidth_narrow',
		),
		'templates/template-sidebar-image-meta.php' => array(
			'slug'   => 'templates/template-sidebar-image-meta.php',
			'layout' => 'sidebar_content',
		),
	) );
}

/**
 * Returns the slugs of plugin-provided page templates.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_page_templates_slugs() {
	return array_keys( ignition_get_page_templates_info() );
}

/**
 * Determines the correct sidebar to be displayed.
 *
 * @since 1.0.0
 *
 * @return false|string A sidebar ID. False when there is no sidebar applicable.
 */
function ignition_get_current_sidebar_id() {
	$sidebar_id = false;

	if ( ignition_is_woocommerce_with_sidebar() ) {
		$sidebar_id = 'shop';
	} elseif ( ignition_is_woocommerce_without_sidebar() ) {
		$sidebar_id = false;
	} elseif ( ! is_page() ) {
		$sidebar_id = 'sidebar-1';
	} elseif ( is_page() ) {
		$sidebar_id = 'sidebar-2';
	}

	/**
	 * Filters the correct sidebar ID for the current request.
	 *
	 * @since 1.0.0
	 *
	 * @param string $sidebar_id
	 */
	return apply_filters( 'ignition_current_sidebar_id', $sidebar_id );
}

/**
 * Determines if a sidebar is being displayed.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function ignition_has_sidebar() {
	$sidebar_id  = ignition_get_current_sidebar_id();
	$has_sidebar = false;

	if ( $sidebar_id ) {
		$has_sidebar = is_active_sidebar( $sidebar_id );
	}

	if ( is_page_template( 'templates/template-sidebar-image-meta.php' ) ) {
		$has_sidebar = true;
	}

	if ( function_exists( 'ignition_gsection_can_do_location' ) && ignition_gsection_can_do_location( 'sidebar' ) ) {
		$has_sidebar = true;
	}

	/**
	 * Filters whether the current page has an active sidebar.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $has_sidebar
	 */
	return apply_filters( 'ignition_has_sidebar', $has_sidebar );
}

/**
 * Returns the layout name for the current page.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_get_current_page_layout() {
	$layout = '';

	if ( is_search() && 'product' === get_query_var( 'post_type' ) || ignition_is_woocommerce_with_sidebar() ) {
		$layout = get_theme_mod( 'woocommerce_shop_layout', ignition_customizer_defaults( 'woocommerce_shop_layout' ) );
	} elseif ( is_page_template( ignition_get_page_templates_slugs() ) ) {
		$template = ignition_get_page_templates_info()[ get_page_template_slug( get_queried_object_id() ) ];
		$layout   = $template['layout'];
	} elseif ( is_singular( 'post' ) ) {
		// Single posts may have a page template assigned, so this case needs to be after is_page_template().
		$layout = get_theme_mod( 'blog_single_layout_type', ignition_customizer_defaults( 'blog_single_layout_type' ) );
	} elseif ( ignition_is_blog_listing() ) {
		$layout = get_theme_mod( 'blog_archive_layout_type', ignition_customizer_defaults( 'blog_archive_layout_type' ) );
	} elseif ( ignition_is_woocommerce_without_sidebar() ) {
		$layout = 'fullwidth_boxed';
	} elseif ( is_tax() ) {
		$layout = 'fullwidth_boxed';
	} else {
		$layout = get_theme_mod( 'site_layout_type', ignition_customizer_defaults( 'site_layout_type' ) );
	}

	/**
	 * Filters the current page's layout name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout
	 */
	return apply_filters( 'ignition_current_page_layout', $layout );
}

/**
 * Returns information about a specific layout.
 *
 * @since 1.0.0
 *
 * @param false|string $layout The layout name to get information for. False to auto-detect. Default false.
 *
 * @return array
 */
function ignition_get_layout_info( $layout = false ) {
	$sidebar_id  = ignition_get_current_sidebar_id();
	$has_sidebar = ignition_has_sidebar();

	$default_site_width         = ignition_customizer_defaults( 'site_layout_container_width' );
	$default_content_width_cols = ignition_customizer_defaults( 'site_layout_content_width' );
	$default_sidebar_width_cols = ignition_customizer_defaults( 'site_layout_sidebar_width' );

	// Assume a default layout of "Content / Sidebar", and then handle all other cases.

	$site_width         = (array) get_theme_mod( 'site_layout_container_width', $default_site_width );
	$site_width         = ! empty( $site_width['desktop'] ) ? $site_width['desktop'] : $default_site_width['desktop'];
	$content_width_cols = (array) get_theme_mod( 'site_layout_content_width', $default_content_width_cols );
	$content_width_cols = ! empty( $content_width_cols['desktop'] ) ? $content_width_cols['desktop'] : $default_content_width_cols['desktop'];
	$sidebar_width_cols = (array) get_theme_mod( 'site_layout_sidebar_width', $default_sidebar_width_cols );
	$sidebar_width_cols = ! empty( $sidebar_width_cols['desktop'] ) ? $sidebar_width_cols['desktop'] : $default_sidebar_width_cols['desktop'];

	$content_width = (int) floor( ( $site_width / 12 ) * $content_width_cols );

	$info = array(
		'layout'                 => $layout ? $layout : ignition_get_current_page_layout(),
		'container_classes'      => "col-lg-{$content_width_cols} col-12",
		'sidebar_classes'        => $has_sidebar ? "col-lg-{$sidebar_width_cols} col-12" : '',
		'content_width'          => $content_width,
		'has_sidebar'            => $has_sidebar,
		'sidebar_id'             => $sidebar_id,
		'row_classes'            => $has_sidebar ? 'has-sidebar' : 'justify-content-center',
		'main_width_classes'     => $has_sidebar ? 'col-12' : "col-lg-{$content_width_cols} col-12",
		'main_width_row_classes' => $has_sidebar ? '' : 'justify-content-center',
		'featured_size'          => 'post-thumbnail',
	);

	switch ( $info['layout'] ) {
		case 'sidebar_content':
			// If there's no sidebar, keep the row .justify-content-center and main_width .col-12 classes.

			if ( $has_sidebar ) {
				$info['row_classes'] = 'has-sidebar layout-reverse';

				if ( is_page_template( 'templates/template-sidebar-image-meta.php' ) ) {
					$info['row_classes'] .= ' layout-reverse-mobile';
				}
			}
			break;
		case 'fullwidth':
			$info = array(
				'layout'                 => 'fullwidth',
				'container_classes'      => '',
				'sidebar_classes'        => '',
				'content_width'          => $site_width,
				'has_sidebar'            => false,
				'sidebar_id'             => false,
				'row_classes'            => '',
				'main_width_classes'     => 'col-12',
				'main_width_row_classes' => '',
				'featured_size'          => 'ignition_item_lg',
			);
			break;
		case 'fullwidth_boxed':
			$info = array(
				'layout'                 => 'fullwidth_boxed',
				'container_classes'      => 'col-12',
				'sidebar_classes'        => '',
				'content_width'          => $site_width,
				'has_sidebar'            => false,
				'sidebar_id'             => false,
				'row_classes'            => '',
				'main_width_classes'     => 'col-12',
				'main_width_row_classes' => '',
				'featured_size'          => 'ignition_item_lg',
			);
			break;
		case 'fullwidth_narrow':
			$info['sidebar_classes']        = '';
			$info['has_sidebar']            = false;
			$info['sidebar_id']             = false;
			$info['row_classes']            = 'justify-content-center';
			$info['main_width_classes']     = $info['container_classes'];
			$info['main_width_row_classes'] = 'justify-content-center';
			break;
	}

	/**
	 * Filters the layout information for a given layout name.
	 *
	 * The current layout name can be accessed from `$info['layout']`
	 *
	 * @since 1.0.0
	 *
	 * @param array $info        Array of layout information.
	 * @param bool  $has_sidebar Whether the current page has an active sidebar.
	 *
	 * @hooked ignition_side_mode_layout_info - 10
	 */
	return apply_filters( 'ignition_layout_info', $info, $has_sidebar );
}

/**
 * Determines if the current page is one that the plugin considers to be a shop page with a sidebar.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function ignition_is_woocommerce_with_sidebar() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	$return = false;

	if ( is_shop() || is_product_taxonomy() ) {
		$return = true;
	}

	/**
	 * Filters whether the current WooCommerce page should show a sidebar.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $return
	 */
	return (bool) apply_filters( 'ignition_is_woocommerce_with_sidebar', $return );
}

/**
 * Determines if the current page is one that the plugin considers to be a shop page without a sidebar.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function ignition_is_woocommerce_without_sidebar() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	$return = false;

	if ( is_product() || is_cart() || is_checkout() || is_account_page() ) {
		$return = true;
	}

	/**
	 * Filters whether the current WooCommerce page should not show a sidebar.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $return
	 */
	return (bool) apply_filters( 'ignition_is_woocommerce_without_sidebar', $return );
}

/**
 * Generates the page title section's data for the current page, or a specific page if passed a post ID.
 *
 * Also takes care of inheritance of the values, falling back to more generic ones if necessary.
 * E.g.: If a post explicitly sets a page title type, use this. If not, use the blog page's options. If those are empty as well, use the customizer ones.
 *
 * @since 1.0.0
 *
 * @param false|int $post_id Optional. Post ID to get the page title data from. False uses the current post. Default false.
 *
 * @return array
 */
function ignition_page_title_get_data( $post_id = false ) {
	$generic_data = array(
		'layout_type'               => '',
		'background_image'          => '',
		'background_video'          => '',
		'background_video_disabled' => '',
		'with_background'           => '',
		'normal_title'              => '',
		'normal_subtitle'           => '',
		'breadcrumb'                => '',
	);

	// $global_data is highest in the inheritance tree.
	$global_data = ignition_page_title_get_global_data();

	// These may contain empty string values, that should be inherited from the Customizer.
	$blog_data = ignition_page_title_inherit_data( ignition_page_title_get_blog_data(), $global_data );
	$shop_data = ignition_page_title_inherit_data( ignition_page_title_get_shop_data(), $global_data );

	if ( is_home() || is_author() || is_date() ) {
		$generic_data = $blog_data;
	} elseif ( is_category() || is_tag() ) {
		$term_data    = ignition_page_title_get_term_data( get_queried_object_id() );
		$term_data    = ignition_page_title_inherit_data( $term_data, $blog_data );
		$generic_data = $term_data;
	} elseif ( is_search() || is_404() ) {
		$generic_data = $global_data;
	} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
		$generic_data = $shop_data;
	} elseif ( class_exists( 'WooCommerce' ) && is_product_taxonomy() ) {
		$term_data = ignition_page_title_get_term_data( get_queried_object_id() );
		$term_data = ignition_page_title_inherit_data( $term_data, $shop_data );

		if ( get_theme_mod( 'woocommerce_force_show_title_subtitle_is_enabled', ignition_customizer_defaults( 'woocommerce_force_show_title_subtitle_is_enabled' ) ) ) {
			$term_data = array_merge( $term_data, array(
				'normal_title'    => 1, // Force enable 'normal_page_title_title_is_visible'.
				'normal_subtitle' => 1, // Force enable 'normal_page_title_subtitle_is_visible'.
			) );
		}

		$generic_data = $term_data;
	} elseif ( is_tax() ) {
		$term_data    = ignition_page_title_get_term_data( get_queried_object_id() );
		$term_data    = ignition_page_title_inherit_data( $term_data, $global_data );
		$generic_data = $term_data;
	} else {
		$generic_data = $global_data;
	}

	if ( is_singular() && false === $post_id ) {
		$post_id = get_the_ID();
	}

	$data = $generic_data;

	$single_data = array();

	if ( false !== $post_id ) {
		$single_data = ignition_page_title_get_singular_data( $post_id );

		if ( is_singular( 'post' ) ) {
			$single_data = ignition_page_title_inherit_data( $single_data, $blog_data );
		} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
			$single_data = ignition_page_title_inherit_data( $single_data, $shop_data );
		} else {
			$single_data = ignition_page_title_inherit_data( $single_data, $global_data );
		}

		$data = $single_data;
	}

	/**
	 * Filters the current page's page title data, optionally for a specific Post ID.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data
	 * @param array $generic_data
	 * @param array $post_id
	 * @param array $single_data
	 */
	return apply_filters( 'ignition_page_title_data', $data, $generic_data, $post_id, $single_data );
}

/**
 * Merges page title data with a parent array.
 *
 * Replaces empty string values of $data with values from $parent_data.
 *
 * @since 1.0.0
 *
 * @param array $data        The array that contains empty string values.
 * @param array $parent_data The array to inherit values from.
 *
 * @return array
 */
function ignition_page_title_inherit_data( $data, $parent_data ) {
	// Don't inherit the value of these keys.
	$skip_keys = array( 'background_video_disabled' );

	foreach ( $data as $key => $value ) {
		if ( in_array( $key, $skip_keys, true ) ) {
			continue;
		}

		// Empty string means "inherit".
		if ( '' === $value && array_key_exists( $key, $parent_data ) ) {
			$data[ $key ] = $parent_data[ $key ];
		}
	}

	return $data;
}


/**
 * Returns an array of the page title related customizer values.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_page_title_get_global_data() {
	return array(
		'layout_type'               => get_theme_mod( 'header_layout_type', ignition_customizer_defaults( 'header_layout_type' ) ),
		'background_image'          => get_theme_mod( 'page_title_colors_background_image', ignition_customizer_defaults( 'page_title_colors_background_image' ) ),
		'background_video'          => get_theme_mod( 'page_title_colors_background_video', ignition_customizer_defaults( 'page_title_colors_background_video' ) ),
		'background_video_disabled' => get_theme_mod( 'page_title_colors_background_video_disabled', ignition_customizer_defaults( 'page_title_colors_background_video_disabled' ) ),
		'with_background'           => get_theme_mod( 'page_title_with_background_is_visible', ignition_customizer_defaults( 'page_title_with_background_is_visible' ) ),
		'normal_title'              => get_theme_mod( 'normal_page_title_title_is_visible', ignition_customizer_defaults( 'normal_page_title_title_is_visible' ) ),
		'normal_subtitle'           => get_theme_mod( 'normal_page_title_subtitle_is_visible', ignition_customizer_defaults( 'normal_page_title_subtitle_is_visible' ) ),
		'breadcrumb'                => get_theme_mod( 'breadcrumb_is_visible', ignition_customizer_defaults( 'breadcrumb_is_visible' ) ),
	);
}

/**
 * Returns an array of the page title related post meta values.
 *
 * @since 1.0.0
 *
 * @param $post_id int A post ID to get the values from.
 *
 * @return array
 */
function ignition_page_title_get_singular_data( $post_id ) {
	// Post meta are returned as strings, so some values need to be converted to int or empty to maintain consistency with the rest of the functions.
	return array(
		'layout_type'               => ignition_get_post_meta( $post_id, 'header_layout_type', '' ),
		'background_image'          => ignition_get_post_meta( $post_id, 'page_title_colors_background_image', '' ),
		'background_video'          => ignition_get_post_meta( $post_id, 'page_title_colors_background_video', '' ),
		'background_video_disabled' => ignition_get_post_meta( $post_id, 'page_title_colors_background_video_disabled', '' ),
		'with_background'           => ignition_sanitize_intval_or_empty( ignition_get_post_meta( $post_id, 'page_title_with_background_is_visible', '' ) ),
		'normal_title'              => ignition_sanitize_intval_or_empty( ignition_get_post_meta( $post_id, 'normal_page_title_title_is_visible', '' ) ),
		'normal_subtitle'           => ignition_sanitize_intval_or_empty( ignition_get_post_meta( $post_id, 'normal_page_title_subtitle_is_visible', '' ) ),
		'breadcrumb'                => ignition_sanitize_intval_or_empty( ignition_get_post_meta( $post_id, 'breadcrumb_is_visible', '' ) ),
	);
}

/**
 * Returns an array of the page title related term meta values.
 *
 * @since 1.0.0
 *
 * @param $term_id int An object ID to get the values from.
 *
 * @return array
 */
function ignition_page_title_get_term_data( $term_id ) {
	// Term meta are returned as strings, so some values need to be converted to int or empty to maintain consistency with the rest of the functions.
	return array(
		'layout_type'               => '', // 'header_layout_type' is inherited.
		'background_image'          => ignition_get_term_meta( $term_id, 'page_title_colors_background_image', '' ),
		'background_video'          => ignition_get_term_meta( $term_id, 'page_title_colors_background_video', '' ),
		'background_video_disabled' => ignition_get_term_meta( $term_id, 'page_title_colors_background_video_disabled', '' ),
		'with_background'           => '', // 'page_title_with_background_is_visible' is inherited.
		'normal_title'              => '', // 'normal_page_title_title_is_visible' is inherited.
		'normal_subtitle'           => '', // 'normal_page_title_subtitle_is_visible' is inherited.
		'breadcrumb'                => '', // 'breadcrumb_is_visible' is inherited.
	);
}

/**
 * Returns an array of the page title related values for the blog.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_page_title_get_blog_data() {
	$data        = array();
	$global_data = ignition_page_title_get_global_data();

	if ( ignition_blog_is_page() ) {
		$page_for_posts = ignition_get_blog_page_id();
		$page_data      = ignition_page_title_get_singular_data( $page_for_posts );
		$data           = $page_data;
	} else {
		$data = $global_data;
	}

	return $data;
}

/**
 * Returns an array of the page title related values for the shop page.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_page_title_get_shop_data() {
	$shop_page = false;
	if ( class_exists( 'WooCommerce' ) ) {
		$shop_page = wc_get_page_id( 'shop' );
	}

	$data        = array();
	$global_data = ignition_page_title_get_global_data();

	if ( $shop_page ) {
		$page_data = ignition_page_title_get_singular_data( $shop_page );
		$data      = $page_data;
	} else {
		$data = $global_data;
	}

	return $data;
}
