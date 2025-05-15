<?php
/**
 * Template part for displaying the footer widgets section
 *
 * @since 1.0.0
 */

$sidebars = array( 'footer-1', 'footer-2', 'footer-3', 'footer-4' );

$layout_types   = ignition_get_footer_widgets_layout_types();
$widgets_layout = get_theme_mod( 'footer_widgets_layout_type', ignition_customizer_defaults( 'footer_widgets_layout_type' ) );

if ( ! empty( $layout_types[ $widgets_layout ] ) ) {
	$layout_info = $layout_types[ $widgets_layout ];
} else {
	// Not found. Fall back to the first available entry.
	$layout_info    = reset( $layout_types );
	$widgets_layout = key( $layout_types );
}

$sidebars_required = $layout_info['sidebars'];

$has_active_sidebar = false;
$sidebars_counted   = 0;
foreach ( $sidebars as $sidebar ) {
	$sidebars_counted++;

	if ( is_active_sidebar( $sidebar ) && $sidebars_counted <= $sidebars_required ) {
		$has_active_sidebar = true;
		break;
	}
}

if ( ! is_customize_preview() && ( ! $has_active_sidebar || $sidebars_required < 1 ) ) {
	return;
}


if ( ! empty( $layout_info ) ) {
	/**
	 * Hook: ignition_before_footer_widgets.
	 *
	 * @since 1.8.0
	 *
	 * @param string $widgets_layout
	 * @param array $layout_info
	 */
	do_action( 'ignition_before_footer_widgets', $widgets_layout, $layout_info );

	ignition_get_template_part( "template-parts/footer/widgets-{$layout_info['template_file']}", '', array(
		'layout_info' => $layout_info,
	) );

	/**
	 * Hook: ignition_after_footer_widgets.
	 *
	 * @since 1.8.0
	 *
	 * @param string $widgets_layout
	 * @param array $layout_info
	 */
	do_action( 'ignition_after_footer_widgets', $widgets_layout, $layout_info );
}
