<?php
/**
 * Variation functions and definitions
 *
 * @since 1.3.0
 */

add_action( 'after_setup_theme', 'ignition_public_opinion_variation_noozbeat_setup' );
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.3.0
 *
 * Note that this function is hooked on the init hook which is too late for some features.
 * The hook after_setup_theme can't be used at all, as this file isn't loaded until it fires.
 */
function ignition_public_opinion_variation_noozbeat_setup() {
	$suffix = ignition_public_opinion_ignition_scripts_styles_suffix();
	add_editor_style( "theme-variations/noozbeat/inc/assets/css/admin/editor-styles{$suffix}.css" );

	remove_action( 'ignition_public_opinion_the_post_entry_meta_top', 'ignition_public_opinion_the_post_entry_comments_link', 30 );
	remove_action( 'ignition_the_post_header', 'ignition_public_opinion_the_post_entry_author', 20 );
}

add_action( 'wp', 'ignition_public_opinion_variation_noozbeat_register_scripts' );
/**
 * Registers scripts and styles unconditionally.
 *
 * @since 1.3.0
 */
function ignition_public_opinion_variation_noozbeat_register_scripts() {
	$suffix = ignition_public_opinion_ignition_scripts_styles_suffix();

	$variation = ignition_public_opinion_get_theme_variation();

	wp_register_style( 'ignition-public-opinion-variation-style', get_theme_file_uri( "theme-variations/{$variation}/style{$suffix}.css" ), array(), ignition_public_opinion_asset_version() );
}

add_filter( 'ignition_public_opinion_styles_after_main', 'ignition_public_opinion_variation_noozbeat_styles_after_main' );
/**
 * Enqueues theme variation styles after the main theme's stylesheet.
 *
 * @since 1.3.0
 */
function ignition_public_opinion_variation_noozbeat_styles_after_main( $styles_after ) {
	return array_merge( $styles_after, array( 'ignition-public-opinion-variation-style' ) );
}


add_filter( 'ignition_customizer_defaults', 'ignition_public_opinion_variation_noozbeat_filter_customizer_defaults', 20 );
/**
 * Modifies the customizer's default values.
 *
 * @since 1.3.0
 *
 * @param array $defaults
 *
 * @return array
 */
function ignition_public_opinion_variation_noozbeat_filter_customizer_defaults( $defaults ) {
	// Font family values should match fonts.json 'family' field.
	$secondary_font = 'Roboto';

	$colors = array();

	// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	$defaults = array_merge( $defaults, array(
		'site_layout_container_width'   => ignition_customizer_defaults_empty_breakpoints( array(
			'desktop' => 1230,
		) ),

		'site_colors_secondary' => '#000000',
		// Font attributes (size, lineHeight, etc) are disabled for this control.
		'site_typo_secondary'    => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'   => $secondary_font,
				'variant'  => '500',
				'is_gfont' => true,
			),
		) ),
		'site_typo_navigation'   => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => $secondary_font,
				'variant'    => '700',
				'size'       => 16,
				'lineHeight' => 1.25,
				'transform'  => 'none',
				'spacing'    => 0,
				'is_gfont'   => true,
			),
		) ),
		// Font family and variant are disabled for these controls.
		'site_typo_h1'           => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 36,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => '',
				'is_gfont'   => false,
			),
		) ),
		'site_typo_h2'           => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 32,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => '',
				'is_gfont'   => false,
			),
		) ),
		'site_typo_h3'           => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 28,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => '',
				'is_gfont'   => false,
			),
		) ),
		'site_typo_h4'           => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 24,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => '',
				'is_gfont'   => false,
			),
		) ),
		'site_typo_h5'           => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 20,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => '',
				'is_gfont'   => false,
			),
		) ),
		'site_typo_h6'           => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 18,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => '',
				'is_gfont'   => false,
			),
		) ),
		'site_typo_widget_title' => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 20,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => '',
				'is_gfont'   => false,
			),
		) ),
		'site_typo_widget_text'  => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => '', // Font family is disabled for this control.
				'variant'    => '', // Font variant is disabled for this control.
				'size'       => 16,
				'lineHeight' => 1.6,
				'transform'  => 'none',
				'spacing'    => 0,
				'is_gfont'   => false,
			),
		) ),

		'top_bar_colors_background' => '#0a0a0a',
		'top_bar_colors_text'       => '#ededed',
		'top_bar_colors_border'     => 'rgba(255, 255, 255, 0)',

		'header_colors_background' => '#ffffff',

		'normal_page_title_title_is_visible'    => 0,
		'normal_page_title_subtitle_is_visible' => 0,

		'blog_archive_meta_comments_is_visible' => 0,

		'footer_colors_background' => '#f7f7f7',
	) );
	// phpcs:enable

	return $defaults;
}

add_action( 'init', 'ignition_public_opinion_variation_register_block_editor_block_styles', 50 );
/**
 * Registers custom block styles.
 *
 * @since 1.3.0
 */
function ignition_public_opinion_variation_register_block_editor_block_styles() {
	if ( WP_Block_Styles_Registry::get_instance()->is_registered( 'core/heading', 'ignition-public-opinion-heading-alt' ) ) {
		unregister_block_style( 'core/heading', 'ignition-public-opinion-heading-alt' );
	}

	register_block_style( 'core/heading', array(
		'name'  => 'ignition-public-opinion-heading-underline',
		'label' => _x( 'Theme Heading', 'heading style', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/heading', array(
		'name'  => 'ignition-public-opinion-heading-underline',
		'label' => _x( 'Theme Heading', 'heading style', 'ignition-public-opinion' ),
	) );
}
