<?php
/**
 * Sidebars and widgets related functions
 *
 * @since 1.0.0
 */

add_action( 'widgets_init', 'ignition_register_widgets' );
/**
 * Registers widgets.
 *
 * @since 1.0.0
 */
function ignition_register_widgets() {
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/widgets/class-widget.php';

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/widgets/buttons.php';
	register_widget( 'Ignition_Widget_Buttons' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/widgets/callout.php';
	register_widget( 'Ignition_Widget_Callout' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/widgets/latest-post-type.php';
	register_widget( 'Ignition_Widget_Latest_Post_Type' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/widgets/page-children-menu.php';
	register_widget( 'Ignition_Widget_Page_Children_Menu' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/widgets/tabular-data.php';
	register_widget( 'Ignition_Widget_Tabular_Data' );
}

add_action( 'widgets_init', 'ignition_widgets_init' );
/**
 * Registers widget areas.
 *
 * @since 1.0.0
 */
function ignition_widgets_init() {
	$main_sidebars = array(
		'sidebar-1' => array(
			'name'          => esc_html__( 'Blog', 'ignition' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Widgets added here will appear on the blog section.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
		'sidebar-2' => array(
			'name'          => esc_html__( 'Page', 'ignition' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Widgets added here will appear on the static pages.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	);

	if ( get_theme_support( 'woocommerce' ) ) {
		$main_sidebars = array_merge( $main_sidebars, array(
			'shop' => array(
				'name'          => esc_html__( 'Shop', 'ignition' ),
				'id'            => 'shop',
				'description'   => esc_html__( 'Widgets added here will appear on the shop page.', 'ignition' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
		) );
	}

	/**
	 * Filters the plugin's main widget areas.
	 *
	 * @since 1.0.0
	 *
	 * @param array $main_sidebars
	 *
	 * @hooked ignition_side_mode_main_widget_areas - 10
	 * @hooked ignition_module_accommodation_add_widget_area - 10
	 * @hooked ignition_module_discography_add_widget_area - 10
	 * @hooked ignition_module_event_add_widget_area - 10
	 * @hooked ignition_module_package_add_widget_area - 10
	 * @hooked ignition_module_podcast_add_widget_area - 10
	 * @hooked ignition_module_portfolio_add_widget_area - 10
	 * @hooked ignition_module_service_add_widget_area - 10
	 * @hooked ignition_module_team_add_widget_area - 10
	 * @hooked ignition_module_property_add_widget_area - 10
	 */
	$main_sidebars = apply_filters( 'ignition_main_widget_areas', $main_sidebars );

	/**
	 * Filters the plugin's footer widget areas.
	 *
	 * @since 1.0.0
	 *
	 * @param array $main_sidebars
	 */
	$footer_sidebars = apply_filters( 'ignition_footer_widget_areas', array(
		'footer-1' => array(
			'name'          => esc_html__( 'Footer - 1st column', 'ignition' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Widgets added here will appear on the first footer column.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
		'footer-2' => array(
			'name'          => esc_html__( 'Footer - 2nd column', 'ignition' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Widgets added here will appear on the second footer column.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
		'footer-3' => array(
			'name'          => esc_html__( 'Footer - 3rd column', 'ignition' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Widgets added here will appear on the third footer column.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
		'footer-4' => array(
			'name'          => esc_html__( 'Footer - 4th column', 'ignition' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Widgets added here will appear on the fourth footer column.', 'ignition' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	) );

	$all_sidebars = array_merge( $main_sidebars, $footer_sidebars );

	/**
	 * Filters all the plugin's widget areas.
	 *
	 * @since 1.0.0
	 *
	 * @param array $all_sidebars
	 * @param array $main_sidebars
	 * @param array $footer_sidebars
	 */
	$all_sidebars = apply_filters( 'ignition_all_widget_areas', $all_sidebars, $main_sidebars, $footer_sidebars );

	if ( ! empty( $all_sidebars ) ) {
		foreach ( $all_sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}

}

/**
 * Returns a list of allowed tags and attributes meant to be used as widgets' wrappers.
 *
 * @since 1.0.0
 *
 * @see wp_kses()
 *
 * @return array List of allowed tags and their allowed attributes.
 */
function ignition_get_allowed_sidebar_wrappers() {
	$attributes = array(
		'id'    => true,
		'class' => true,
	);

	$allowed = array(
		'a'       => array(
			'id'     => true,
			'class'  => true,
			'href'   => true,
			'title'  => true,
			'target' => true,
		),
		'div'     => $attributes,
		'span'    => $attributes,
		'strong'  => $attributes,
		'i'       => $attributes,
		'section' => $attributes,
		'aside'   => $attributes,
		'h1'      => $attributes,
		'h2'      => $attributes,
		'h3'      => $attributes,
		'h4'      => $attributes,
		'h5'      => $attributes,
		'h6'      => $attributes,
	);

	return apply_filters( 'ignition_get_allowed_sidebar_wrappers', $allowed );
}
