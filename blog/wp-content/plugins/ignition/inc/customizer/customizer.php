<?php
/**
 * Standard Customizer Sections and Settings
 *
 * @since 1.0.0
 */

add_action( 'customize_register', 'ignition_customize_register' );
/**
 * Registers Customizer panels, sections, and controls.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Reference to the customizer's manager object.
 */
function ignition_customize_register( $wp_customize ) {
	// This partial needs to be available for both tob_bar and header options.
	$wp_customize->selective_refresh->add_partial( 'header_layout', array(
		'selector'            => '.header',
		'render_callback'     => 'ignition_customize_preview_header',
		'settings'            => array(),
		'container_inclusive' => true,
	) );

	$wp_customize->selective_refresh->add_partial( 'footer_layout', array(
		'selector'            => '.footer',
		'render_callback'     => 'ignition_customize_preview_footer',
		'settings'            => array(),
		'container_inclusive' => true,
	) );



	//
	// Site
	//
	$wp_customize->add_panel( 'site', array(
		'title'    => esc_html_x( 'Site Options', 'customizer section title', 'ignition' ),
		'priority' => 1,
	) );
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/site/layout.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/site/colors.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/site/typography.php';



	//
	// Top Bar
	//
	if ( current_theme_supports( 'ignition-top-bar' ) ) {
		require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/top-bar.php';
	}



	//
	// Header Options
	//
	$wp_customize->add_panel( 'header', array(
		'title'    => esc_html_x( 'Header', 'customizer section title', 'ignition' ),
		'priority' => 3,
	) );
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/header/layout.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/header/content.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/header/colors.php';
	if ( current_theme_supports( 'ignition-header-transparent' ) ) {
		require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/header/colors-transparent.php';
	}
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/header/colors-sticky.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/header/colors-mobile-nav.php';



	//
	// Page Title
	//
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/page-title.php';



	//
	// Blog Options
	//
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/blog.php';


	//
	// Footer
	//
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/footer.php';


	//
	// Utilities
	//
	$wp_customize->add_panel( 'utilities', array(
		'title'    => esc_html_x( 'Utilities', 'customizer section title', 'ignition' ),
		'priority' => 10,
	) );
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/utilities/weather.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/utilities/lightbox.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/utilities/block-editor.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/utilities/social-sharing.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/utilities/button-top.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/utilities/widgets.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/utilities/google-maps.php';


	//
	// Site Identity
	//
	$wp_customize->selective_refresh->add_partial( 'site_branding', array(
		'selector'            => '.site-branding',
		'render_callback'     => 'ignition_the_site_branding',
		'settings'            => array( 'custom_logo' ),
		'container_inclusive' => true,
	) );
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/site-identity.php';


	//
	// WooCommerce
	//
	if ( class_exists( 'WooCommerce' ) ) {
		require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/woocommerce/product-catalog.php';
		require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/sections/woocommerce/single-product.php';
	}
}

add_action( 'customize_preview_init', 'ignition_customize_preview_js' );
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 */
function ignition_customize_preview_js() {
	$suffix = ignition_scripts_styles_suffix();

	// Generic preview code.
	wp_enqueue_script( 'ignition-customizer-preview', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/preview{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	wp_localize_script( 'ignition-customizer-preview', 'IGNITION_OPTIONS', ignition_customizer_options( 'all' ) );
	wp_localize_script( 'ignition-customizer-preview', 'IGNITION_BREAKPOINTS', ignition_customizer_breakpoints() );
	wp_enqueue_style( 'ignition-customizer-preview', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/preview{$suffix}.css", array(), ignition_asset_version() );

	// Options-specific preview code.
	wp_enqueue_script( 'ignition-customizer-preview-site', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/site{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	if ( current_theme_supports( 'ignition-top-bar' ) ) {
		wp_enqueue_script( 'ignition-customizer-preview-top-bar', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/top-bar{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	}
	wp_enqueue_script( 'ignition-customizer-preview-header', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/header{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	wp_enqueue_script( 'ignition-customizer-preview-page-title', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/page-title{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	wp_enqueue_script( 'ignition-customizer-preview-footer', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/footer{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	wp_enqueue_script( 'ignition-customizer-preview-utilities', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/preview/utilities{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
}


add_action( 'customize_register', 'ignition_customize_register_custom_controls', 9 );
/**
 * Registers custom Customizer controls.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Reference to the customizer's manager object.
 */
function ignition_customize_register_custom_controls( $wp_customize ) {

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/section-header/section-header.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/notice/notice.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/category/category.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/file-select/file-select.php';

	// Only call $wp_customize->register_control_type() on JS-rendered controls.
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/range/range.php';
	$wp_customize->register_control_type( 'Ignition_Customize_Range_Control' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/color/color.php';
	$wp_customize->register_control_type( 'Ignition_Customize_Color_Control' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/color-gradient/color-gradient.php';
	$wp_customize->register_control_type( 'Ignition_Customize_Color_Gradient_Control' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/typography/typography.php';
	$wp_customize->register_control_type( 'Ignition_Customize_Typography_Control' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/spacing/spacing.php';
	$wp_customize->register_control_type( 'Ignition_Customize_Spacing_Control' );

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/controls/image-bg/image-bg.php';
	$wp_customize->register_control_type( 'Ignition_Customize_Image_BG_Control' );

}


add_action( 'customize_controls_enqueue_scripts', 'ignition_customize_controls_js' );
/**
 * Registers/Enqueues styles and scripts for customizer controls.
 *
 * @since 1.0.0
 */
function ignition_customize_controls_js() {
	$suffix = ignition_scripts_styles_suffix();

	wp_enqueue_style( 'ignition-customizer-controls', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/style{$suffix}.css", array(), ignition_asset_version() );
	wp_enqueue_script( 'ignition-customizer-controls', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/scripts{$suffix}.js", array(), ignition_asset_version(), true );
}

/**
 * Returns the default text for customizer fields that accept text, HTML, and shortcodes.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_customize_get_text_field_shortcodes_description() {
	return apply_filters( 'ignition_customize_text_field_shortcodes_description', __( 'You can add text, HTML, and shortcodes.', 'ignition' ) );
}

/**
 * Fonts list.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/class-ignition-fonts-list.php';

/**
 * CSS Generator.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/class-ignition-customizer-css-generator.php';

/**
 * Customizer defaults.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/defaults.php';

/**
 * Customizer options.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/options.php';

/**
 * Customizer partial callbacks.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/partial-callbacks.php';

/**
 * Customizer generated styles.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/generated-styles.php';
