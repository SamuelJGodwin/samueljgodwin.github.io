<?php
/**
 * MaxSlider related hooks and functions
 *
 * @since 1.0.0
 */

add_filter( 'maxslider_locate_template_theme_path', 'ignition_maxslider_locate_template_theme_path' );
/**
 * Filters the path that MaxSlider templates can be found under.
 *
 * @since 1.1.0
 *
 * @param string $path
 *
 * @return string
 */
function ignition_maxslider_locate_template_theme_path( $path ) {
	return 'template-parts/maxslider';
}

add_filter( 'maxslider_slider_classes', 'ignition_maxslider_replace_home_slider_classes', 10, 2 );
/**
 * Adds/renames classes needed by the home template.
 *
 * @since 1.0.0
 *
 * @param array $classes
 * @param array $slider
 *
 * @return array
 */
function ignition_maxslider_replace_home_slider_classes( $classes, $slider ) {
	if ( 'home' !== $slider['template'] ) {
		return $classes;
	}

	$maxslider = array_search( 'maxslider', $classes, true );
	if ( false !== $maxslider ) {
		unset( $classes[ $maxslider ] );
		$classes[] = 'ignition-slideshow';
		$classes[] = 'ignition-slick-slider';
	}

	$new_classes = array();
	foreach ( $classes as $class ) {
		$new_classes[] = str_replace( 'maxslider-', 'ignition-', $class );
	}

	return $new_classes;
}
