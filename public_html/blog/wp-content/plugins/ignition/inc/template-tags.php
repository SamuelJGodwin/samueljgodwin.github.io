<?php
/**
 * Custom template tags and hooks
 *
 * @since 1.0.0
 */

add_action( 'ignition_header', 'ignition_header', 10 );
add_action( 'ignition_footer', 'ignition_footer', 10 );

add_action( 'ignition_before_main', 'ignition_the_page_title_with_background_section', 10 );
add_action( 'ignition_before_main', 'ignition_the_page_breadcrumb', 20 );

add_action( 'ignition_main_before', 'ignition_the_normal_page_title_section', 10 );

add_action( 'ignition_the_normal_page_title_elements', 'ignition_the_normal_page_title_title', 10 );
add_action( 'ignition_the_normal_page_title_elements', 'ignition_the_normal_page_title_subtitle', 20 );

add_action( 'ignition_the_page_title_with_background_elements', 'ignition_the_page_title_with_background_title', 10 );
add_action( 'ignition_the_page_title_with_background_elements', 'ignition_the_page_title_with_background_subtitle', 20 );

add_action( 'ignition_the_post_header', 'ignition_the_post_entry_title', 10 );
add_action( 'ignition_the_post_header', 'ignition_the_post_entry_meta', 20 );

add_action( 'ignition_the_post_entry_meta', 'ignition_the_post_entry_sticky_label', 10 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_post_entry_date', 20 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_podcast_post_entry_date', 20 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_post_entry_categories', 30 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_portfolio_category_entry_terms', 30 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_discography_category_entry_terms', 30 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_post_entry_author', 40 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_post_entry_comments_link', 50 );
add_action( 'ignition_the_post_entry_meta', 'ignition_the_post_entry_rating', 60 );

// Conditional: ignition_after_single_entry - ignition_the_social_sharing_icons - 5
add_action( 'ignition_after_single_entry', 'ignition_the_post_author_box', 10 );
add_action( 'ignition_after_single_entry', 'ignition_the_post_related_posts', 20 );
add_action( 'ignition_after_single_entry', 'ignition_the_post_comments', 100 );

add_action( 'ignition_global_after', 'ignition_the_mobile_navigation', 100 );

add_action( 'ignition_sidebar_before', 'ignition_the_sidebar_image_metadata', 10 );

// Same default filters as 'the_excerpt'.
add_filter( 'ignition_the_page_subtitle', 'wptexturize' );
add_filter( 'ignition_the_page_subtitle', 'convert_smilies' );
add_filter( 'ignition_the_page_subtitle', 'convert_chars' );
add_filter( 'ignition_the_page_subtitle', 'wpautop' );
add_filter( 'ignition_the_page_subtitle', 'shortcode_unautop' );

add_action( 'wp', 'ignition_hook_conditional_template_tags' );
/**
 * Conditionally registers or unregisters hooks during the 'wp' action, for correct Customizer preview.
 *
 * get_theme_mod() doesn't return the correct preview value while in Customizer Preview Mode before the 'wp' action.
 *
 * @since 1.9.0
 */
function ignition_hook_conditional_template_tags() {
	if ( current_theme_supports( 'ignition-side-header' ) ) {
		add_action( 'ignition_header', 'ignition_the_side_header_widgets', 20 );
	}

	if ( is_singular( 'post' ) && get_theme_mod( 'utilities_social_sharing_single_post_is_enabled', ignition_customizer_defaults( 'utilities_social_sharing_single_post_is_enabled' ) ) ) {
		add_action( 'ignition_after_single_entry', 'ignition_the_social_sharing_icons', 5 );
	}

	if ( get_theme_mod( 'utilities_social_sharing_single_product_is_enabled', ignition_customizer_defaults( 'utilities_social_sharing_single_product_is_enabled' ) ) ) {
		add_action( 'woocommerce_share', 'ignition_the_social_sharing_icons' );
	}
}

/**
 * Displays the appropriate header template according to the selected option.
 *
 * @since 1.0.0
 */
function ignition_header() {
	$menu_type = get_theme_mod( 'header_layout_menu_type', ignition_customizer_defaults( 'header_layout_menu_type' ) );
	$menu_info = array();

	$menu_types = ignition_get_header_layout_menu_types();
	if ( ! empty( $menu_types[ $menu_type ] ) ) {
		$menu_info = $menu_types[ $menu_type ];
	} else {
		// Not found. Fall back to the first available entry.
		$menu_info = reset( $menu_types );
		$menu_type = key( $menu_types );
	}

	if ( ! empty( $menu_info ) ) {
		/**
		 * Hook: ignition_before_header.
		 *
		 * @since 1.0.0
		 *
		 * @param string $menu_type
		 * @param array $menu_info
		 */
		do_action( 'ignition_before_header', $menu_type, $menu_info );

		ignition_get_template_part( "template-parts/header/header-{$menu_info['template_file']}" );

		/**
		 * Hook: ignition_after_header.
		 *
		 * @since 1.0.0
		 *
		 * @param string $menu_type
		 * @param array $menu_info
		 */
		do_action( 'ignition_after_header', $menu_type, $menu_info );
	}
}

/**
 * Echoes the logo / site title / description, depending on customizer options.
 *
 * @since 1.0.0
 */
function ignition_the_site_branding() {
	/**
	 * Hook: ignition_before_site_branding.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_before_site_branding' );

	ignition_get_template_part( 'template-parts/header/site-branding' );

	/**
	 * Hook: ignition_after_site_branding.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_after_site_branding' );
}

/**
 * Echoes header classes based on customizer options
 *
 * @since 1.0.0
 */
function ignition_the_header_classes() {

	$classes = array(
		'header',
		get_theme_mod( 'header_layout_is_full_width', ignition_customizer_defaults( 'header_layout_is_full_width' ) ) ? 'header-fullwidth' : '',
	);

	$sticky_type = get_theme_mod( 'header_layout_menu_sticky_type', ignition_customizer_defaults( 'header_layout_menu_sticky_type' ) );
	$classes     = array_merge( $classes, array(
		'off' !== $sticky_type ? 'header-sticky' : '',
		"sticky-{$sticky_type}",
	) );

	$menu_type = get_theme_mod( 'header_layout_menu_type', ignition_customizer_defaults( 'header_layout_menu_type' ) );
	$menu_info = array();

	$menu_types = ignition_get_header_layout_menu_types();
	if ( ! empty( $menu_types[ $menu_type ] ) ) {
		$menu_info = $menu_types[ $menu_type ];
	} else {
		// Not found. Fall back to the first available entry.
		$menu_info = reset( $menu_types );
		$menu_type = key( $menu_types );
	}

	$classes = array_merge( $classes, $menu_info['classes'] );

	$data = ignition_page_title_get_data();
	if ( 'transparent' === $data['layout_type'] ) {
		$classes[] = 'header-fixed';
	} else {
		$classes[] = 'header-normal';
	}

	// .header-navbar-stretch may be supported in some menu layouts, in order to stretch the menu in the available width.

	/**
	 * Filters the header's section classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes
	 */
	$classes = apply_filters( 'ignition_header_classes', $classes );

	$classes = array_filter( $classes );

	echo esc_attr( implode( ' ', $classes ) );
}

/**
 * Displays the side header widgets.
 *
 * @since 2.1.0
 */
function ignition_the_side_header_widgets() {
	ignition_get_template_part( 'template-parts/header/side-header-widgets' );
}

/**
 * Displays the footer template.
 *
 * @since 1.0.0
 */
function ignition_footer() {
	ignition_get_template_part( 'template-parts/footer/footer' );
}

/**
 * Echoes footer classes based on customizer options
 *
 * @since 1.0.0
 */
function ignition_the_footer_classes() {
	/**
	 * Filters the footer's section classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes
	 */
	$classes = apply_filters( 'ignition_footer_classes', array(
		'footer',
	) );

	$classes = array_filter( $classes );

	echo esc_attr( implode( ' ', $classes ) );
}

/**
 * Displays the mobile navigation template.
 *
 * @since 1.1.0
 */
function ignition_the_mobile_navigation() {
	ignition_get_template_part( 'template-parts/footer/navigation-mobile' );
}

/**
 * Displays the current page's title section (title, subtitle, etc).
 *
 * @since 1.0.0
 */
function ignition_the_normal_page_title_section() {
	$data       = ignition_page_title_get_data();
	$is_visible = ! $data['with_background'] && ( $data['normal_title'] || $data['normal_subtitle'] );

	/**
	 * Hook: ignition_before_the_normal_page_title_section.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_visible
	 */
	do_action( 'ignition_before_the_normal_page_title_section', $is_visible );

	if ( $is_visible || is_customize_preview() ) {
		ignition_get_template_part( 'template-parts/page-title/normal' );
	}

	/**
	 * Hook: ignition_after_the_normal_page_title_section.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_visible
	 */
	do_action( 'ignition_after_the_normal_page_title_section', $is_visible );
}

/**
 * Displays the current page's title with background section (title, subtitle, etc).
 *
 * @since 1.0.0
 */
function ignition_the_page_title_with_background_section() {
	$data       = ignition_page_title_get_data();
	$is_visible = $data['with_background'];

	/**
	 * Hook: ignition_before_the_page_title_with_background_section.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_visible
	 */
	do_action( 'ignition_before_the_page_title_with_background_section', $is_visible );

	if ( $is_visible || is_customize_preview() ) {
		ignition_get_template_part( 'template-parts/page-title/with-background' );
	}

	/**
	 * Hook: ignition_after_the_page_title_with_background_section.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_visible
	 */
	do_action( 'ignition_after_the_page_title_with_background_section', $is_visible );
}


/**
 * Displays the current page's title.
 *
 * @since 1.0.0
 */
function ignition_the_normal_page_title_title() {
	$data       = ignition_page_title_get_data();
	$is_visible = $data['normal_title'];

	$page_title = ignition_get_the_page_title();
	if ( ( $is_visible && $page_title ) || is_customize_preview() ) {
		$html_tag = 'h1';
		if ( is_singular( 'post' ) || class_exists( 'WooCommerce' ) && is_product() ) {
			$html_tag = 'div';
		}

		/**
		 * Filters the normal page title's title tag.
		 *
		 * @since 1.0.0
		 *
		 * @param string $html_tag
		 */
		$html_tag = apply_filters( 'ignition_the_normal_page_title_title_tag', $html_tag );
		echo wp_kses_post( sprintf( '<%1$s class="page-title">%2$s</%1$s>', $html_tag, $page_title ) );
	}
}

/**
 * Displays the current page's subtitle.
 *
 * @since 1.0.0
 */
function ignition_the_normal_page_title_subtitle() {
	$data       = ignition_page_title_get_data();
	$is_visible = $data['normal_subtitle'];

	$subtitle = ignition_get_the_page_subtitle();
	if ( trim( $subtitle ) && ( $is_visible || is_customize_preview() ) ) {
		?>
		<div class="page-subtitle">
			<?php echo wp_kses( $subtitle, array_merge( wp_kses_allowed_html( 'post' ), array(
				'time' => array(
					'datetime' => true,
				),
			) ) ); ?>
		</div>
		<?php
	}
}

/**
 * Displays the current page's page title.
 *
 * @since 1.0.0
 */
function ignition_the_page_title_with_background_title() {
	$page_title = ignition_get_the_page_title();
	if ( $page_title ) {
		$html_tag = 'h1';
		if ( is_singular( 'post' ) || class_exists( 'WooCommerce' ) && is_product() ) {
			$html_tag = 'div';
		}

		/**
		 * Filters the page title with background's title tag.
		 *
		 * @since 1.0.0
		 *
		 * @param string $html_tag
		 */
		$html_tag = apply_filters( 'ignition_the_page_title_with_background_title_tag', $html_tag );
		echo wp_kses_post( sprintf( '<%1$s class="page-hero-title">%2$s</%1$s>', $html_tag, $page_title ) );
	}
}

/**
 * Displays the current page's page subtitle.
 *
 * @since 1.0.0
 */
function ignition_the_page_title_with_background_subtitle() {
	$subtitle = ignition_get_the_page_subtitle();
	if ( trim( $subtitle ) ) {
		?>
		<div class="page-hero-subtitle">
			<?php echo wp_kses_post( $subtitle ); ?>
		</div>
		<?php
	}
}

/**
 * Displays the current page's breadcrumb.
 *
 * @since 1.0.0
 */
function ignition_the_page_breadcrumb() {
	if ( ! ignition_can_show_breadcrumb() ) {
		return;
	}

	$data       = ignition_page_title_get_data();
	$info       = ignition_get_layout_info();
	$is_visible = (bool) $data['breadcrumb'];

	// Breadcrumb should follow the main content width unless the Normal Page Title is shown, AND the header is NOT fullwidth.
	$row_classes    = $info['main_width_row_classes'];
	$column_classes = $info['main_width_classes'];
	if ( ! $data['with_background'] && ! get_theme_mod( 'header_layout_is_full_width', ignition_customizer_defaults( 'header_layout_is_full_width' ) ) ) {
		$row_classes    = '';
		$column_classes = 'col-12';
	}

	$breadcrumb = ignition_get_the_breadcrumb();
	if ( trim( $breadcrumb ) && ( $is_visible || is_customize_preview() ) ) {
		?>
		<div class="section-pre-main page-breadcrumb">
			<div class="container">
				<div class="row <?php echo esc_attr( $row_classes ); ?>">
					<div class="<?php echo esc_attr( $column_classes ); ?>">
						<?php echo $breadcrumb; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * Returns the current page's page title.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_get_the_page_title() {
	$title = '';

	/**
	 * Filters the blog section's default page title.
	 *
	 * Applies on the default blog page, i.e. 'posts' === get_option( 'show_on_front' )
	 *
	 * @since 1.0.0
	 *
	 * @param string $blog_page_title
	 */
	$blog_page_title = apply_filters( 'ignition_blog_page_title', __( 'Blog', 'ignition' ) );
	if ( ignition_blog_is_page() ) {
		$blog_page_title = get_the_title( ignition_get_blog_page_id() );
	}

	if ( is_singular( 'post' ) ) {
		$title = $blog_page_title;
	} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
		$title = get_the_title( wc_get_page_id( 'shop' ) );
	} elseif ( is_singular() ) {
		$title = get_the_title();
	} elseif ( is_home() ) {
		$title = $blog_page_title;
	} elseif ( is_author() ) {
		$title = get_the_author();
	} elseif ( is_search() ) {
		global $wp_query;
		$found = intval( $wp_query->found_posts );

		/* translators: %d is a number of search results. */
		$title = sprintf( _n( '%d result found.', '%d results', $found, 'ignition' ), $found );
	} elseif ( is_date() ) {
		if ( is_year() ) {
			/* translators: Yearly archive title. 1: Year */
			$title = get_the_date( _x( 'Y', 'yearly archives date format', 'ignition' ) );
		} elseif ( is_month() ) {
			/* translators: Monthly archive title. 1: Month name and year */
			$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'ignition' ) );
		} elseif ( is_day() ) {
			/* translators: Daily archive title. 1: Date */
			$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'ignition' ) );
		}
	} elseif ( is_404() ) {
		$title = __( 'Not Found (404)', 'ignition' );
	} elseif ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
		$title = woocommerce_page_title( false );
	} elseif ( is_category() || is_tag() ) {
		$title = single_term_title( '', false );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}

	/**
	 * Filters the page title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $title
	 */
	return apply_filters( 'ignition_the_page_title', $title );
}

/**
 * Returns the current page's page subtitle.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_get_the_page_subtitle() {
	$subtitle = '';

	/**
	 * Filters the blog section's default page subtitle.
	 *
	 * Applies on the default blog page, i.e. 'posts' === get_option( 'show_on_front' )
	 *
	 * @since 1.0.0
	 *
	 * @param string $blog_page_subtitle
	 */
	$blog_page_subtitle = apply_filters( 'ignition_blog_page_subtitle', '' );
	if ( ignition_blog_is_page() ) {
		$blog_page_subtitle = get_the_excerpt( ignition_get_blog_page_id() );
	}

	if ( is_singular( 'post' ) ) {
		$subtitle = $blog_page_subtitle;
	} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
		$subtitle = get_the_excerpt( wc_get_page_id( 'shop' ) );
	} elseif ( is_singular() && 'ignition-podcast' === get_post_type() ) {
		$subtitle = sprintf( '<time datetime="%s">%s</time>',
			esc_attr( get_the_date( 'c' ) ),
			get_the_date()
		);
	} elseif ( is_singular() && post_type_supports( get_post_type(), 'excerpt' ) && has_excerpt() ) {
		$subtitle = get_the_excerpt();
	} elseif ( is_home() ) {
		$subtitle = $blog_page_subtitle;
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$subtitle = term_description();
	} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
		$subtitle = get_the_excerpt( wc_get_page_id( 'shop' ) );
	}

	$subtitle = trim( $subtitle );

	/**
	 * Filters the page subtitle.
	 *
	 * @since 1.0.0
	 *
	 * @param string $subtitle
	 */
	return apply_filters( 'ignition_the_page_subtitle', $subtitle );
}

/**
 * Returns a list of known breadcrumb providers and their callables.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_breadcrumb_get_providers() {
	/**
	 * Filters the list of known breadcrumb providers.
	 *
	 * @since 1.0.0
	 *
	 * @param array $provides Array of 'alias' => 'callable' entries.
	 */
	return apply_filters( 'ignition_breadcrumb_providers', array(
		'navxt'       => 'bcn_display',
		'yoast'       => 'yoast_breadcrumb',
		'rankmath'    => 'rank_math_the_breadcrumbs',
		'woocommerce' => 'woocommerce_breadcrumb',
	) );
}

/**
 * Determines whether the breadcrumb can be shown.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function ignition_can_show_breadcrumb() {
	$breadcrumb_providers = ignition_breadcrumb_get_providers();

	foreach ( $breadcrumb_providers as $alias => $callable ) {
		if ( is_callable( $callable ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Returns the current page's page breadcrumb.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_get_the_breadcrumb() {
	if ( ! ignition_can_show_breadcrumb() ) {
		return '';
	}

	$breadcrumb_providers = ignition_breadcrumb_get_providers();
	$preferred_provider   = get_theme_mod( 'breadcrumb_provider', ignition_customizer_defaults( 'breadcrumb_provider' ) );

	if ( $preferred_provider && array_key_exists( $preferred_provider, $breadcrumb_providers ) ) {
		$callable = $breadcrumb_providers[ $preferred_provider ];
		if ( is_callable( $breadcrumb_providers[ $preferred_provider ] ) ) {
			$breadcrumb_providers = array(
				$preferred_provider => $callable,
			);
		}
	}

	$html            = '';
	$structured_atts = '';

	foreach ( $breadcrumb_providers as $alias => $callable ) {
		if ( is_callable( $callable ) ) {
			if ( 'navxt' === $alias ) {
				$structured_atts = 'typeof="BreadcrumbList" vocab="https://schema.org/"';
			}

			ob_start();

			call_user_func( $callable );

			$html = ob_get_clean();

			break;
		}
	}

	if ( trim( $html ) ) {
		$html = sprintf( '<div class="ignition-breadcrumbs" %1$s>%2$s</div>', $structured_atts, $html );
	}

	return $html;
}

/**
 * Returns or echoes classes for the hero section.
 *
 * @since 1.0.0
 *
 * @param bool $echo Whether to echo or return the classes.
 *
 * @return array|void Void when $echo = true, array of classes otherwise.
 */
function ignition_the_page_title_with_background_section_classes( $echo = true ) {
	$classes = array(
		'page-hero',
		'page-hero-align-' . get_theme_mod( 'page_title_with_background_text_align_horizontal', ignition_customizer_defaults( 'page_title_with_background_text_align_horizontal' ) ),
		'page-hero-align-' . ignition_customizer_defaults( 'page_title_with_background_text_align_vertical' ),
	);

	/**
	 * Filters the page title with background section's classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes
	 */
	$classes = apply_filters( 'ignition_the_page_title_with_background_section_classes', $classes );
	$classes = array_filter( $classes );
	if ( $echo ) {
		echo esc_attr( implode( ' ', $classes ) );
	} else {
		return $classes;
	}
}

/**
 * Returns or echoes classes for the normal page title section.
 *
 * @since 1.0.0
 *
 * @param bool $echo Whether to echo or return the classes.
 *
 * @return array|void Void when $echo = true, array of classes otherwise.
 */
function ignition_the_normal_page_title_section_classes( $echo = true ) {
	$classes = array(
		'page-title-wrap',
		'page-title-align-' . get_theme_mod( 'page_title_with_background_text_align_horizontal', ignition_customizer_defaults( 'page_title_with_background_text_align_horizontal' ) ),
	);

	/**
	 * Filters the normal page title section's classes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes
	 */
	$classes = apply_filters( 'ignition_the_normal_page_title_section_classes', $classes );
	$classes = array_filter( $classes );
	if ( $echo ) {
		echo esc_attr( implode( ' ', $classes ) );
	} else {
		return $classes;
	}
}

/**
 * Displays the current post's feature image (if applicable), using the appropriate markup for singulars.
 *
 * Also tries to show the correct image size depending on the current layout.
 * Must be used within the loop.
 *
 * @since 1.0.0
 *
 * @param string|false $size Image size name. Default 'post-thumbnail'.
 *
 * @uses ignition_get_layout_info()
 */
function ignition_the_post_thumbnail( $size = false ) {
	if ( ! has_post_thumbnail() ) {
		return;
	}

	if ( ! is_singular() || get_the_ID() !== get_queried_object_id() ) {
		return;
	}

	/**
	 * Hook: ignition_before_the_post_thumbnail.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_before_the_post_thumbnail' );

	if ( ! $size ) {
		$size        = 'post-thumbnail';
		$layout_info = ignition_get_layout_info();
		if ( $layout_info['featured_size'] ) {
			$size = $layout_info['featured_size'];
		}
	}

	?>
	<figure class="entry-thumb">
		<?php the_post_thumbnail( $size ); ?>
	</figure>
	<?php

	/**
	 * Hook: ignition_after_the_post_thumbnail.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_after_the_post_thumbnail' );
}

/**
 * Displays the current post's feature image (if applicable), using the appropriate markup for listings.
 *
 * Must be used within the loop.
 *
 * @since 1.0.0
 *
 * @param string|false $size Image size name. Default 'post-thumbnail'.
 */
function ignition_the_post_entry_thumbnail( $size = false ) {
	if ( ! $size ) {
		$size = 'post-thumbnail';
	}

	if ( ! has_post_thumbnail() ) {
		return;
	}

	/**
	 * Hook: ignition_before_the_post_entry_thumbnail.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_before_the_post_entry_thumbnail' );

	?>
	<figure class="entry-thumb">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( $size ); ?>
		</a>
	</figure>
	<?php

	/**
	 * Hook: ignition_after_the_post_entry_thumbnail.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_after_the_post_entry_thumbnail' );
}

/**
 * Displays the current post's header (title, meta, etc).
 *
 * @since 1.0.0
 */
function ignition_the_post_header() {
	ob_start();

	/**
	 * Hook: ignition_the_post_header hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked ignition_the_post_entry_title - 10
	 * @hooked ignition_the_post_entry_meta - 20
	 */
	do_action( 'ignition_the_post_header' );

	$html = ob_get_clean();

	if ( trim( $html ) ) {
		$html = sprintf( '<header class="entry-header">%s</header>', $html );
	}

	/**
	 * Hook: ignition_before_the_post_header.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 */
	do_action( 'ignition_before_the_post_header', $html );

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput

	/**
	 * Hook: ignition_after_the_post_header.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 */
	do_action( 'ignition_after_the_post_header', $html );
}

/**
 * Displays the current post's title, using the appropriate markup in listings and singulars.
 *
 * @since 1.0.0
 */
function ignition_the_post_entry_title() {
	if ( is_singular() && get_the_ID() === get_queried_object_id() ) {
		ignition_the_single_post_title( 'entry-title' );
	} else {
		ignition_the_archive_post_title( 'entry-title' );
	}
}


/**
 * Displays the current post's title according to customizer options, suitable for use in blog singles.
 *
 * @since 1.0.0
 * @since 1.3.0 $heading_tag
 *
 * @param string $class       Additional html classes.
 * @param string $heading_tag Optional. The heading tag to use. Default h1.
 */
function ignition_the_single_post_title( $class = 'entry-item-title', $heading_tag = 'h1' ) {
	$heading_tag = ! empty( $heading_tag ) ? $heading_tag : 'h1';

	/**
	 * Filters the single post's title classes.
	 *
	 * @since 1.3.0
	 *
	 * @param string $class
	 */
	$class = apply_filters( 'ignition_the_single_post_title_class', $class );

	/**
	 * Filters the single post's title heading tag.
	 *
	 * @since 1.3.0
	 *
	 * @param string $heading_tag
	 */
	$heading_tag = apply_filters( 'ignition_the_single_post_title_heading_tag', $heading_tag );

	echo wp_kses_post( sprintf( '<%1$s class="%2$s">%3$s</%1$s>',
		$heading_tag,
		esc_attr( $class ),
		get_the_title()
	) );
}

/**
 * Displays the current post's title according to customizer options, suitable for use in blog listings.
 *
 * @since 1.0.0
 * @since 1.3.0 $heading_tag
 *
 * @param string $class       Additional html classes.
 * @param string $heading_tag The heading tag to use. Default h1.
 */
function ignition_the_archive_post_title( $class = 'entry-item-title', $heading_tag = 'h2' ) {
	$heading_tag = ! empty( $heading_tag ) ? $heading_tag : 'h1';

	/**
	 * Filters the archive post's title classes.
	 *
	 * @since 1.3.0
	 *
	 * @param string $class
	 */
	$class = apply_filters( 'ignition_the_archive_post_title_class', $class );

	/**
	 * Filters the archive post's title heading tag.
	 *
	 * @since 1.3.0
	 *
	 * @param string $heading_tag
	 */
	$heading_tag = apply_filters( 'ignition_the_archive_post_title_heading_tag', $heading_tag );

	echo wp_kses_post( sprintf( '<%1$s class="%2$s"><a href="%3$s">%4$s</a></%1$s>',
		$heading_tag,
		esc_attr( $class ),
		esc_url( get_the_permalink() ),
		get_the_title()
	) );
}


/**
 * Displays the current post's meta.
 *
 * @since 1.0.0
 */
function ignition_the_post_entry_meta() {
	ob_start();

	/**
	 * Hook: ignition_the_post_entry_meta hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked ignition_the_post_entry_sticky_label - 10
	 * @hooked ignition_the_post_entry_date - 20
	 * @hooked ignition_the_podcast_post_entry_date - 20
	 * @hooked ignition_the_post_entry_categories - 30
	 * @hooked ignition_the_portfolio_category_entry_terms - 30
	 * @hooked ignition_the_discography_category_entry_terms - 30
	 * @hooked ignition_the_post_entry_author - 40
	 * @hooked ignition_the_post_entry_comments_link - 50
	 */
	do_action( 'ignition_the_post_entry_meta' );

	$html = ob_get_clean();

	if ( trim( $html ) ) {
		$html = sprintf( '<div class="entry-meta">%s</div>', $html );
	}

	/**
	 * Hook: ignition_before_the_post_entry_meta.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 */
	do_action( 'ignition_before_the_post_entry_meta', $html );

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput

	/**
	 * Hook: ignition_after_the_post_entry_meta.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 */
	do_action( 'ignition_after_the_post_entry_meta', $html );
}

/**
 * Displays the current post's sticky label (if applicable).
 *
 * @since 1.0.0
 */
function ignition_the_post_entry_sticky_label() {
	if ( 'post' !== get_post_type() ) {
		return;
	}


	if (
		( ! is_singular() && is_sticky() && is_home() && ! is_paged() )
		||
		( is_singular() && is_sticky() )
	) {
		?>
		<span class="entry-meta-item entry-sticky">
			<?php esc_html_e( 'Featured', 'ignition' ); ?>
		</span>
		<?php
	}
}

/**
 * Displays the current post's date (if applicable).
 *
 * @since 1.0.0
 */
function ignition_the_post_entry_date() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_date_is_visible', ignition_customizer_defaults( 'blog_archive_meta_date_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_date_is_visible', ignition_customizer_defaults( 'blog_single_meta_date_is_visible' ) ) )
	) {
		?>
		<span class="entry-meta-item entry-date">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time>
		</span>
		<?php
	}
}

/**
 * Displays the current podcast's date.
 *
 * @since 1.8.0
 */
function ignition_the_podcast_post_entry_date() {
	if ( 'ignition-podcast' !== get_post_type() ) {
		return;
	}

	?>
	<span class="entry-meta-item">
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time>
	</span>
	<?php
}

/**
 * Displays the current post's categories (if applicable).
 *
 * @since 1.0.0
 */
function ignition_the_post_entry_categories() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_categories_is_visible', ignition_customizer_defaults( 'blog_archive_meta_categories_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_categories_is_visible', ignition_customizer_defaults( 'blog_single_meta_categories_is_visible' ) ) )
	) {
		?>
		<span class="entry-meta-item entry-categories">
			<?php the_category( ', ' ); ?>
		</span>
		<?php
	}
}

/**
 * Displays the current post's author (if applicable).
 *
 * @since 1.0.0
 */
function ignition_the_post_entry_author() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_author_is_visible', ignition_customizer_defaults( 'blog_archive_meta_author_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_author_is_visible', ignition_customizer_defaults( 'blog_single_meta_author_is_visible' ) ) )
	) {
		?>
		<span class="entry-meta-item entry-author">
			<?php
				printf(
					/* translators: %s is the author's name. */
					esc_html_x( 'by %s', 'post author', 'ignition' ),
					'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
				);
			?>
		</span>
		<?php
	}
}

/**
 * Displays the current post's comments link (if applicable).
 *
 * @since 1.0.0
 */
function ignition_the_post_entry_comments_link() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_comments_is_visible', ignition_customizer_defaults( 'blog_archive_meta_comments_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_comments_is_visible', ignition_customizer_defaults( 'blog_single_meta_comments_is_visible' ) ) )
	) {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			?>
			<span class="entry-meta-item entry-comments-link">
				<?php
					/* translators: %s: post title */
					comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ignition' ), array(
						'span' => array(
							'class' => array(),
						),
					) ), get_the_title() ) );
				?>
			</span>
			<?php
		}
	}
}

/**
 * Displays the current portfolio's entry terms (if applicable).
 *
 * @since 1.0.0
 */
function ignition_the_portfolio_category_entry_terms() {
	if ( 'ignition-portfolio' !== get_post_type() ) {
		return;
	}

	the_terms( get_the_ID(), 'ignition_portfolio_category', '<span class="entry-meta-item entry-categories">', ', ', '</span>' );
}

/**
 * Displays the current discography's entry terms (if applicable).
 *
 * @since 1.1.0
 */
function ignition_the_discography_category_entry_terms() {
	if ( 'ignition-discography' !== get_post_type() ) {
		return;
	}

	the_terms( get_the_ID(), 'ignition_discography_category', '<span class="entry-meta-item entry-categories">', ', ', '</span>' );
}

/**
 * Displays the current post's tags (if applicable).
 *
 * @since 1.0.0
 */
function ignition_the_post_tags() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if ( has_tag() ) {
		/**
		 * Filters the the_tags() call's parameters.
		 *
		 * @since 1.3.0
		 *
		 * @param array $args
		 */
		$args = apply_filters( 'ignition_the_post_tags_args', array(
			'before' => __( 'Tags: ', 'ignition' ),
			'sep'    => ', ',
			'after'  => '',
		) );

		?>
		<div class="entry-tags">
			<?php the_tags( $args['before'], $args['sep'], $args['after'] ); ?>
		</div>
		<?php
	}
}

/**
 * Displays the authorbox template.
 *
 * @since 1.0.0
 */
function ignition_the_post_author_box() {
	if ( post_type_supports( get_post_type(), 'ignition-authorbox' ) && get_theme_mod( 'blog_single_authorbox_is_visible', ignition_customizer_defaults( 'blog_single_authorbox_is_visible' ) ) ) {
		ignition_get_template_part( 'template-parts/authorbox' );
	}
}

/**
 * Displays the related posts template.
 *
 * @since 1.0.0
 */
function ignition_the_post_related_posts() {
	if ( is_singular() && post_type_supports( get_post_type( get_queried_object_id() ), 'ignition-related' ) ) {
		ignition_get_template_part( 'template-parts/related', get_post_type() );
	}
}

/**
 * Displays the comments template.
 *
 * @since 1.0.0
 */
function ignition_the_post_comments() {
	// Reviews are a type of comment.
	if ( ! post_type_supports( get_post_type(), 'comments' ) ) {
		return;
	}

	if ( is_singular( 'post' ) && ! get_theme_mod( 'blog_single_comments_is_visible', ignition_customizer_defaults( 'blog_single_comments_is_visible' ) ) ) {
		return;
	}

	if ( post_type_supports( get_post_type(), 'ignition-reviews' ) ) {
		comments_template( '/comments-reviews.php' );
	} else {
		comments_template();
	}
}

/**
 * Displays the current post's rating.
 *
 * @since 2.0.0
 */
function ignition_the_post_entry_rating() {
	if ( ! post_type_supports( get_post_type(), 'ignition-reviews' ) ) {
		return;
	}

	$rating_counts  = get_post_meta( get_the_ID(), 'review_rating_counts', true );
	$rating_average = get_post_meta( get_the_ID(), 'review_rating_average', true );
	$total_ratings  = 0;

	if ( ! empty( $rating_counts ) && is_array( $rating_counts ) ) {
		foreach ( $rating_counts as $rating => $count ) {
			$total_ratings += $count;
		}
	}

	if ( $total_ratings ) {
		$average_percentage = round( ( 100 / 5 ) * $rating_average, 1 );
		?>
		<span class="entry-meta-item entry-rating">
			<b class="ignition-star-rating">
				<b class="ignition-star-rating-inner" style="width: <?php echo esc_attr( $average_percentage ); ?>%;"></b>
			</b>
		</span>
		<?php
	}
}

/**
 * Displays the sidebar's featured image and metadata, if applicable, using the appropriate markup for sidebars.
 *
 * Used by templates/template-sidebar-image-meta.php
 *
 * @since 1.1.0
 */
function ignition_the_sidebar_image_metadata() {
	if ( is_page_template( 'templates/template-sidebar-image-meta.php' ) ) {
		ignition_get_template_part( 'template-parts/single/sidebar-image-meta/sidebar', get_post_type() );
	}
}

/**
 * Outputs the passed content, rendering any shortcodes inside a wrapper.
 *
 * @since 1.0.0
 *
 * @param string $content The content to output.
 * @param string $classes The classes that the shortcodes' wrappers will have.
 *
 * @return string
 */
function ignition_get_content_slot_string( $content, $classes ) {
	add_filter( 'do_shortcode_tag', 'ignition_wrap_shortcode_with_token', 10, 4 );

	$content = do_shortcode( $content );

	remove_filter( 'do_shortcode_tag', 'ignition_wrap_shortcode_with_token', 10 );

	$token       = '<!-- @!#$/ -->';
	$content_arr = explode( $token, $content );
	$content_arr = array_filter( $content_arr );

	foreach ( $content_arr as $key => $value ) {
		$content_arr[ $key ] = '<div class="' . esc_attr( $classes ) . '">' . $value . '</div>';
	}

	$content = implode( '', $content_arr );

	return $content;
}

/**
 * Wraps output with a predefined token.
 *
 * This is transiently hooked to 'do_shortcode_tag' by ignition_get_content_slot_string() and is needed in order to
 * split and differentiate each shortcode's output.
 *
 * @since 1.0.0
 *
 * @see ignition_get_content_slot_string()
 *
 * @param $output
 * @param $tag
 * @param $attr
 * @param $m
 *
 * @return string
 */
function ignition_wrap_shortcode_with_token( $output, $tag, $attr, $m ) {
	$token  = '<!-- @!#$/ -->';
	$output = trim( $output );

	if ( ! empty( $output ) ) {
		$output = $token . $output . $token;
	}

	return $output;
}


/**
 * Echoes row classes based on whether the current template has a visible sidebar or not,
 * and depending on sidebar visibility option on single post/pages/etc.
 *
 * @since 1.0.0
 */
function ignition_the_row_classes() {
	$info = ignition_get_layout_info();
	echo esc_attr( $info['row_classes'] );
}

/**
 * Echoes container classes based on whether the current template has a visible sidebar or not.
 *
 * @since 1.0.0
 */
function ignition_the_container_classes() {
	$info = ignition_get_layout_info();
	echo esc_attr( $info['container_classes'] );
}

/**
 * Echoes container classes based on whether the current template has a visible sidebar or not.
 *
 * @since 1.0.0
 */
function ignition_the_sidebar_classes() {
	$info = ignition_get_layout_info();
	echo esc_attr( $info['sidebar_classes'] );
}

/**
 * Echoes column classes that are either fullwidth or narrow, based on whether the current template has a visible sidebar or not.
 *
 * @since 1.8.0
 */
function ignition_the_main_width_classes() {
	$info = ignition_get_layout_info();
	echo esc_attr( $info['main_width_classes'] );
}

/**
 * Echoes row classes that are either fullwidth or narrow, based on whether the current template has a visible sidebar or not.
 *
 * @since 1.8.0
 */
function ignition_the_main_width_row_classes() {
	$info = ignition_get_layout_info();
	echo esc_attr( $info['main_width_row_classes'] );
}

/**
 * Echoes pagination links if applicable. Output depends on pagination method selected from the customizer.
 *
 * @since 1.0.0
 *
 * @uses the_post_pagination()
 * @uses previous_posts_link()
 * @uses next_posts_link()
 *
 * @param array $args An array of arguments to change default behavior.
 * @param WP_Query|null $query A WP_Query object to paginate. Defaults to null and uses the global $wp_query
 */
function ignition_posts_pagination( $args = array(), WP_Query $query = null ) {
	/**
	 * Filters the pagination's default parameters.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults
	 */
	$args = wp_parse_args( $args, apply_filters( 'ignition_posts_pagination_default_args', array(
		'mid_size'           => 1,
		'prev_text'          => _x( 'Previous', 'previous post', 'ignition' ),
		'next_text'          => _x( 'Next', 'next post', 'ignition' ),
		'screen_reader_text' => __( 'Posts navigation', 'ignition' ),
		'container_id'       => '',
		'container_class'    => '',
	), $query ) );

	global $wp_query;

	if ( ! is_null( $query ) ) {
		$old_wp_query = $wp_query;
		$wp_query     = $query;
	}

	$output = get_the_posts_pagination( $args );

	if ( ! empty( $output ) && ! empty( $args['container_id'] ) || ! empty( $args['container_class'] ) ) {
		$output = sprintf( '<div id="%2$s" class="%3$s">%1$s</div>', $output, esc_attr( $args['container_id'] ), esc_attr( $args['container_class'] ) );
	}

	if ( ! is_null( $query ) ) {
		$wp_query = $old_wp_query;
	}

	// All markup is from native WordPress functions. The wrapping div is properly escaped above.
	$output_safe = $output;

	echo $output_safe;
}

/**
 * Outputs the social sharing icons.
 *
 * @since 1.9.0
 *
 * @param int|false $post_id Optional. Post ID for which the sharing icons will be created for.
 */
function ignition_the_social_sharing_icons( $post_id = false ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	$defaults = ignition_customizer_defaults( 'all' );

	/**
	 * Filters the social sharing icons' general parameters.
	 *
	 * @since 1.9.0
	 *
	 * @param array $params
	 */
	$params = apply_filters( 'ignition_social_sharing_args', array(
		'permalink' => get_permalink( $post_id ),
		'title'     => get_the_title( $post_id ),
		'excerpt'   => get_the_excerpt( $post_id ),
		'image_id'  => get_post_thumbnail_id( $post_id ),
		'site_name' => get_bloginfo( 'name' ),
	) );

	$default_attrs = array(
		'target' => '_blank',
	);

	/**
	 * Filters the social sharing icons' general parameters.
	 *
	 * @since 1.9.0
	 *
	 * @param array $params
	 */
	$networks = apply_filters( 'ignition_social_sharing_networks', array(
		'facebook'  => array(
			'show'  => (bool) get_theme_mod( 'utilities_social_sharing_facebook_is_enabled', $defaults['utilities_social_sharing_facebook_is_enabled'] ),
			'url'   => add_query_arg( array(
				'u' => $params['permalink'],
			), 'https://www.facebook.com/sharer.php' ),
			'class' => 'entry-share-facebook',
			'icon'  => 'ignition-icons ignition-icons-facebook-square',
			'text'  => _x( 'Facebook', 'social sharing icon text', 'ignition' ),
			'attrs' => $default_attrs,
		),
		'twitter'   => array(
			'show'  => (bool) get_theme_mod( 'utilities_social_sharing_twitter_is_enabled', $defaults['utilities_social_sharing_twitter_is_enabled'] ),
			'url'   => add_query_arg( array(
				'url' => $params['permalink'],
			), 'https://twitter.com/share' ),
			'class' => 'entry-share-twitter',
			'icon'  => 'ignition-icons ignition-icons-twitter-square',
			'text'  => _x( 'Twitter', 'social sharing icon text', 'ignition' ),
			'attrs' => $default_attrs,
		),
		'pinterest' => array(
			'show'  => ! empty( $params['image_id'] ) && get_theme_mod( 'utilities_social_sharing_pinterest_is_enabled', $defaults['utilities_social_sharing_pinterest_is_enabled'] ),
			'url'   => add_query_arg( array(
				'url'         => $params['permalink'],
				'description' => $params['title'],
				'media'       => wp_get_attachment_image_url( $params['image_id'], 'large' ),
			), 'https://pinterest.com/pin/create/bookmarklet/' ),
			'class' => 'entry-share-pinterest',
			'icon'  => 'ignition-icons ignition-icons-pinterest-square',
			'text'  => _x( 'Pinterest', 'social sharing icon text', 'ignition' ),
			'attrs' => $default_attrs,
		),
//		'copy-url'  => array(
//			'show'  => (bool) get_theme_mod( 'utilities_social_sharing_copy_url_is_enabled', $defaults['utilities_social_sharing_copy_url_is_enabled'] ),
//			'url'   => $params['permalink'],
//			'class' => 'entry-share-copy-url',
//			'icon'  => 'ignition-icons ignition-icons-arrow-right',
//			'text'  => _x( 'Copy URL', 'social sharing icon text', 'ignition' ),
//			'attrs' => $default_attrs,
//		),
	) );

	if ( ! empty( $networks['copy-url']['attrs'] ) && array_key_exists( 'target', $networks['copy-url']['attrs'] ) ) {
		unset( $networks['copy-url']['attrs']['target'] );
	}

	// Make sure we have at least one icon to show.
	$show = false;
	foreach ( $networks as $network ) {
		if ( $network['show'] ) {
			$show = true;
			break;
		}
	}

	if ( ! $show ) {
		return;
	}

	ignition_get_template_part( 'template-parts/utilities/social-sharing', '', array(
		'post_id'  => $post_id,
		'params'   => $params,
		'networks' => $networks,
	) );
}
