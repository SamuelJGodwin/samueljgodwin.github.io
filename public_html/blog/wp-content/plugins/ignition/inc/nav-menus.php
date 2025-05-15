<?php
/**
 * Navigation menus related functions
 *
 * @since 1.0.0
 */

add_action( 'after_setup_theme', 'ignition_nav_menus_setup' );
/**
 * Registers navigation menus.
 *
 * @since 1.0.0
 */
function ignition_nav_menus_setup() {
	register_nav_menu( 'menu-1', esc_html__( 'Main Menu', 'ignition' ) );
	register_nav_menu( 'menu-2', esc_html__( 'Main Menu - Left', 'ignition' ) );
}

add_filter( 'page_css_class', 'ignition_remove_cpt_parent_class_from_blog' );
add_filter( 'nav_menu_css_class', 'ignition_remove_cpt_parent_class_from_blog' );
/**
 * Filters out menu classes that falsely show the blog page as an ancestor of custom post type singulars.
 *
 * Hooked to 'page_css_class', filter applies to wp_page_menu()
 * Hooked to 'nav_menu_css_class' filter applies to wp_nav_menu()
 *
 * @since 1.0.0
 *
 * @param string[] $classes An array of CSS classes to be applied to each list item.
 *
 * @return string[]
 */
function ignition_remove_cpt_parent_class_from_blog( $classes ) {
	$post_types = get_post_types( array(
		'public'   => true,
		'_builtin' => false,
	), 'names' );

	if ( is_singular( $post_types ) ) {
		return array_filter( $classes, function( $class ) {
			// Check for current page classes, return false if they exist.
			if ( in_array( $class, array( 'current_page_item', 'current_page_parent', 'current_page_ancestor' ), true ) ) {
				return false;
			}

			return true;
		} );
	} else {
		return $classes;
	}
}

/**
 * Determines whether the smooth scrolling feature should be enabled for a menu.
 *
 * Smooth scrolling is enabled in menus whose slugs contain the string 'smooth'.
 *
 * @since 1.4.2
 *
 * @param WP_Term|stdClass $menu
 *
 * @return bool
 */
function ignition_is_smooth_scroll_enabled_in_menu( $menu ) {
	$slug = urldecode( $menu->slug );

	return (bool) ( false !== mb_strpos( $slug, 'smooth' ) );
}

add_filter( 'wp_nav_menu_objects', 'ignition_remove_current_item_classes_from_smooth_menu', 10, 2 );
/**
 * Removes current item classes from applicable menu items.
 *
 * Only removes classes in smooth-enabled menus, items that their URL matches the current request's URL, and include
 * a target anchor in the URL.
 *
 * @since 1.4.2
 *
 * @param array    $sorted_menu_items The menu items, sorted by each menu item's menu order.
 * @param stdClass $args              An object containing wp_nav_menu() arguments.
 *
 * @return array
 */
function ignition_remove_current_item_classes_from_smooth_menu( $sorted_menu_items, $args ) {
	if ( ! isset( $_SERVER['HTTP_HOST'] ) || ! ignition_is_smooth_scroll_enabled_in_menu( $args->menu ) ) {
		return $sorted_menu_items;
	}

	global $wp_rewrite;

	// Get current URL.
	$_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );
	$current_url            = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_root_relative_current );
	$_indexless_current     = untrailingslashit( preg_replace( '/' . preg_quote( $wp_rewrite->index, '/' ) . '$/', '', $current_url ) );

	foreach ( $sorted_menu_items as $key => $menu_item ) {
		if ( 'custom' !== $menu_item->object ) {
			continue;
		}

		$raw_item_url = strpos( $menu_item->url, '#' ) ? substr( $menu_item->url, 0, strpos( $menu_item->url, '#' ) ) : $menu_item->url;
		$item_url     = set_url_scheme( untrailingslashit( $raw_item_url ) );

		$matches = array(
			$current_url,
			urldecode( $current_url ),
			$_indexless_current,
			urldecode( $_indexless_current ),
			$_root_relative_current,
			urldecode( $_root_relative_current ),
		);

		if ( $raw_item_url && in_array( $item_url, $matches, true ) ) {
			if ( false !== strpos( $menu_item->url, '#' ) ) {
				$remove_classes = array( 'current-menu-item', 'current_page_item' );
				foreach ( $remove_classes as $remove_class ) {
					$found = array_search( $remove_class, $menu_item->classes, true );
					if ( false !== $found ) {
						unset( $sorted_menu_items[ $key ]->classes[ $found ] );
					}
				}
			}
		}
	}

	return $sorted_menu_items;
}

add_filter( 'wp_nav_menu', 'ignition_nav_menu_add_classes', 10, 2 );
/**
 * Adds additional classes to custom navigation menus' ul elements.
 *
 * @since 1.4.2
 *
 * @param string   $nav_menu The HTML content for the navigation menu.
 * @param stdClass $args     An object containing wp_nav_menu() arguments.
 *
 * @return mixed|string|string[]
 */
function ignition_nav_menu_add_classes( $nav_menu, $args ) {
	if ( empty( $args->menu ) || empty( $args->menu->slug ) ) {
		return $nav_menu;
	}

	$classes = array();

	if ( preg_match( '/<ul(.*?)class="(.*?)"(.*?)>/', $nav_menu, $matches ) ) {
		$classes[] = $matches[2];
		$classes[] = urldecode( "ignition-menu-{$args->menu->slug}" );

		if ( ignition_is_smooth_scroll_enabled_in_menu( $args->menu ) ) {
			$classes[] = 'nav-smooth-scroll';
		}

		$new_ul = sprintf( '<ul%sclass="%s"%s>',
			$matches[1],
			implode( ' ', $classes ),
			$matches[3]
		);

		$pos = strpos( $nav_menu, $matches[0] );
		if ( false !== $pos ) {
			$nav_menu = substr_replace( $nav_menu, $new_ul, $pos, strlen( $matches[0] ) );
		}
	}

	return $nav_menu;
}
