<?php
/**
 * Side Header mode hooks and definitions
 *
 * @since 2.1.0
 */

if ( ! current_theme_supports( 'ignition-side-header' ) ) {
	return;
}

remove_theme_support( 'align-wide' );
remove_theme_support( 'ignition-top-bar' );
unregister_nav_menu( 'menu-2' );

add_filter( 'ignition_customizer_options', 'ignition_side_mode_customizer_options' );
/**
 * Adds Side Header mode-related customizer options, and removes unneeded ones.
 *
 * @since 2.1.0
 *
 * @param array $options
 *
 * @return array
 */
function ignition_side_mode_customizer_options( $options ) {
	$defaults = ignition_customizer_defaults( 'all' );

	$options['site_layout_type']['control_args']['label']          = __( 'Content layout', 'ignition' );
	$options['site_layout_sidebar_width']['control_args']['label'] = __( 'Content Sidebar width (columns)', 'ignition' );

	if ( 'boxed' === get_theme_mod( 'side_mode_site_layout_type', $defaults['side_mode_site_layout_type'] ) ) {
		$options['site_layout_container_width']['render_args']['breakpoints_css'] = '
			.site-wrap {
				width: %spx;
				max-width: 100%;
			}
		';
	}

	$options['header_colors_background_image']['render_args']['css'] = '
		.site-sidebar-wrap { %s }
	';

	$options['header_layout_menu_mobile_breakpoint']['render_args']['css'] = '
		@media (max-width: %spx) {
			.content-align-left .container {
				margin-left: auto;
				margin-right: auto;
			}

			.site-wrap {
				flex-direction: column;
			}

			.head-content-slot-mobile-content {
				display: block;
			}

			.site-sidebar-sticky-on .site-sidebar-wrap-inner,
			.site-sidebar-sticky-on .site-sidebar-wrap,
			.site-sidebar-fixed .site-sidebar-wrap,
			.site-sidebar-wrap {
				min-height: 0;
				height: auto;
				width: 100%;
				position: relative;
			}

			.site-sidebar-wrap-inner {
				padding: 15px;
			}

			.site-sidebar-wrap-inner.is_stuck {
				position: relative !important;
				width: 100% !important;
			}

			.site-sidebar-wrap-inner.is_stuck + .stuck {
				display: none !important;
			}

			.site-content-wrap {
				min-height: 0;
			}

			.head-mast-inner {
				padding: 0;
				display: flex;
				flex-direction: row;
				align-items: center;
			}

			#mobilemenu {
				display: block;
			}

			.head-content-slot-mobile-nav {
				display: inline-block;
				margin: 0;
			}

			.site-sidebar-widgets,
			.head-menu-slot,
			.nav {
				display: none;
			}

			.site-branding {
				max-width: 38%;
				width: auto;
				text-align: left;
				margin-bottom: 0;
			}

			.rtl .head-content-slot-end {
				text-align: left;
				justify-content: flex-start;
				margin-left: 0;
				margin-right: auto;
			}

			.rtl .site-branding {
				text-align: right;
			}
		}
	';

	return $options;
}

add_action( 'customize_register', 'ignition_side_mode_customize_register' );
/**
 * Registers Customizer panels, sections, and controls.
 *
 * @since 2.1.0
 *
 * @param WP_Customize_Manager $wp_customize Reference to the customizer's manager object.
 */
function ignition_side_mode_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'header_layout_is_full_width' );
	$wp_customize->remove_control( 'header_layout_menu_sticky_type' );

	$wp_customize->remove_control( 'header_content_area' );

	$wp_customize->get_panel( 'header' )->title          = esc_html_x( 'Side Header', 'customizer section title', 'ignition' );
	$wp_customize->get_section( 'header_colors' )->title = esc_html_x( 'Colors', 'customizer section title', 'ignition' );

	$wp_customize->remove_section( 'header_sticky_colors' );
	$wp_customize->remove_control( 'header_sticky_colors_background' );
	$wp_customize->remove_control( 'header_sticky_colors_background_image' );
	$wp_customize->remove_control( 'header_sticky_colors_overlay' );
	$wp_customize->remove_control( 'header_sticky_colors_text' );
	$wp_customize->remove_control( 'header_sticky_colors_border' );
	$wp_customize->remove_control( 'header_sticky_colors_submenu_background' );
	$wp_customize->remove_control( 'header_sticky_colors_submenu_text' );

	$wp_customize->remove_control( 'site_identity_custom_logo_alt' );
}

add_filter( 'ignition_header_layout_menu_types', 'ignition_side_mode_header_layout_menu_types' );
/**
 * Filters the list of valid header menu types.
 *
 * @since 2.1.0
 *
 * @param array $menu_types
 *
 * @return array
 */
function ignition_side_mode_header_layout_menu_types( $menu_types ) {
	unset( $menu_types['full_left'] );
	unset( $menu_types['full_center'] );
	unset( $menu_types['full_right'] );
	unset( $menu_types['split'] );

	$menu_types = array_merge( $menu_types, array(
		'side' => array(
			'title'         => __( 'Menu on the side', 'ignition' ),
			'template_file' => 'side',
			'classes'       => array(
				'header-side',
			),
		),
	) );

	return $menu_types;
}

add_filter( 'body_class', 'ignition_side_mode_body_class', 10, 2 );
/**
 * Adds classes on the body tag.
 *
 * @since 2.1.0
 *
 * @param string[] $classes An array of body class names.
 * @param string[] $class   An array of additional class names added to the body.
 *
 * @return array
 */
function ignition_side_mode_body_class( $classes, $class ) {
	$defaults = ignition_customizer_defaults( 'all' );

	$value = get_theme_mod( 'side_mode_site_layout_type', $defaults['side_mode_site_layout_type'] );
	if ( 'full_boxed' === $value ) {
		$classes[] = 'site-sidebar-fixed';
	} elseif ( 'full_left' === $value ) {
		$classes[] = 'site-sidebar-fixed';
		$classes[] = 'content-align-left';
	} elseif ( 'full_fullwidth' === $value ) {
		$classes[] = 'site-sidebar-fixed';
		$classes[] = 'content-fullwidth';
	}

	$value     = get_theme_mod( 'side_mode_header_layout_is_sticky', $defaults['side_mode_header_layout_is_sticky'] );
	$classes[] = sprintf( 'site-sidebar-sticky-%s', ignition_to_on_off( $value ) );

	return $classes;
}

add_filter( 'ignition_site_layout_types', 'ignition_side_mode_site_layout_types' );
/**
 * Filters the site's layout types.
 *
 * @since 2.1.0
 *
 * @param array $choices Array of 'value' => 'label' choices.
 *
 * @return array
 */
function ignition_side_mode_site_layout_types( $choices ) {
	return array_merge( $choices, array(
		'fullwidth_boxed' => __( 'Full width', 'ignition' ),
	) );
}

add_filter( 'ignition_layout_info', 'ignition_side_mode_layout_info', 10, 2 );
/**
 * Filters the layout information for a given layout name.
 *
 * The current layout name can be accessed from `$info['layout']`
 *
 * @since 2.1.0
 *
 * @param array $info        Array of layout information.
 * @param bool  $has_sidebar Whether the current page has an active sidebar.
 *
 * @return array
 */
function ignition_side_mode_layout_info( $info, $has_sidebar ) {
	$side_layout = get_theme_mod( 'side_mode_site_layout_type', ignition_customizer_defaults( 'side_mode_site_layout_type' ) );

	$info['container_classes']  = str_replace( 'col-lg-', 'col-xl-', $info['container_classes'] );
	$info['sidebar_classes']    = str_replace( 'col-lg-', 'col-xl-', $info['sidebar_classes'] );
	$info['main_width_classes'] = str_replace( 'col-lg-', 'col-xl-', $info['main_width_classes'] );

	if ( 'fixed_left' === $side_layout ) {
		// .justify-content-center conflicts with body.content-align-left so it should be removed.
		$info['row_classes']            = str_replace( 'justify-content-center', '', $info['row_classes'] );
		$info['main_width_row_classes'] = str_replace( 'justify-content-center', '', $info['main_width_row_classes'] );
	}

	return $info;
}

add_filter( 'ignition_main_widget_areas', 'ignition_side_mode_main_widget_areas' );
/**
 * Filters the plugin's main widget areas.
 *
 * @since 2.1.0
 *
 * @param array $main_sidebars
 *
 * @return array
 */
function ignition_side_mode_main_widget_areas( $main_sidebars ) {
	return array_merge( array(
		'header-1' => array(
			'name'          => esc_html__( 'Header', 'ignition' ),
			'id'            => 'header-1',
			'description'   => esc_html__( 'Widgets added here will appear on the side header, after the main menu.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
		'header-2' => array(
			'name'          => esc_html__( 'Header - Bottom', 'ignition' ),
			'id'            => 'header-2',
			'description'   => esc_html__( 'Widgets added here will appear on the bottom of the side header.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	), $main_sidebars );
}
