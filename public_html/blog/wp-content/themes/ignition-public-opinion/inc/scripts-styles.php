<?php
/**
 * Scripts and styles definitions and enqueues
 *
 * @since 1.0.0
 */

add_action( 'wp', 'ignition_public_opinion_register_scripts' );
/**
 * Register scripts and styles unconditionally.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_register_scripts() {
	$suffix = ignition_public_opinion_ignition_scripts_styles_suffix();

	$styles_before  = array(); // Style handles to load before main stylesheet.
	$styles_after   = array(); // Style handles to load after main stylesheet.
	$scripts_before = array(); // Script handles to load before main script.
	$scripts_after  = array(); // Script handles to load after main script.

	wp_register_style( 'ignition-public-opinion-woocommerce', get_theme_file_uri( "/inc/assets/css/woocommerce{$suffix}.css" ), array(), ignition_public_opinion_asset_version() );
	if ( class_exists( 'WooCommerce' ) ) {
		$styles_after = array_merge( $styles_after, array( 'ignition-public-opinion-woocommerce' ) );
	}

	if (
		get_theme_mod( 'theme_news_ticker_is_enabled', ignition_public_opinion_ignition_customizer_defaults( 'theme_news_ticker_is_enabled' ) )
		||
		( is_category() && false !== strpos( ignition_get_term_meta( get_queried_object_id(), 'featured_layout', '' ), 'overlay-slideshow' ) )
		||
		( is_singular() && strpos( get_the_content(), 'ignition-public-opinion-layout-overlay-slideshow' ) )
	) {
		$styles_before  = array_merge( $styles_before, array( 'slick' ) );
		$scripts_before = array_merge( $scripts_before, array( 'slick' ) );
	}

	$sticky_type = get_theme_mod( 'header_layout_menu_sticky_type', ignition_customizer_defaults( 'header_layout_menu_sticky_type' ) );
	if ( 'permanent' === $sticky_type ) {
		$scripts_before = array_merge( $scripts_before, array( 'jquery-sticky' ) );
	} elseif ( 'shy' === $sticky_type ) {
		$scripts_before = array_merge( $scripts_before, array( 'jquery-shyheader' ) );
	}

	wp_register_style( 'ignition-public-opinion-style-rtl', get_template_directory_uri() . "/rtl{$suffix}.css", array(), ignition_public_opinion_asset_version() );
	if ( is_rtl() ) {
		$styles_after = array_merge( $styles_after, array( 'ignition-public-opinion-style-rtl' ) );
	}

	if ( function_exists( 'ignition_get_all_customizer_css' ) ) {
		wp_register_style( 'ignition-public-opinion-generated-styles', false, array(), ignition_public_opinion_asset_version() );
		wp_add_inline_style( 'ignition-public-opinion-generated-styles', ignition_get_all_customizer_css() );
		wp_add_inline_style( 'ignition-public-opinion-generated-styles', ignition_public_opinion_get_generated_css() );
		$styles_after = array_merge( $styles_after, array( 'ignition-public-opinion-generated-styles' ) );
	}

	if ( is_child_theme() ) {
		wp_register_style( 'ignition-public-opinion-style-child', get_stylesheet_uri(), array(), ignition_public_opinion_asset_version() );
		$styles_after = array_merge( $styles_after, array( 'ignition-public-opinion-style-child' ) );
	}

	/**
	 * Filters the list of style handles enqueued before the main stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $styles_before
	 */
	wp_register_style( 'ignition-public-opinion-main-before', false, apply_filters( 'ignition_public_opinion_styles_before_main', $styles_before ), ignition_public_opinion_asset_version() );

	/**
	 * Filters the list of style handles enqueued after the main stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $styles_after
	 */
	wp_register_style( 'ignition-public-opinion-main-after', false, apply_filters( 'ignition_public_opinion_styles_after_main', $styles_after ), ignition_public_opinion_asset_version() );

	/**
	 * Filters the list of script handles enqueued before the main script file.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $scripts_before
	 */
	wp_register_script( 'ignition-public-opinion-main-before', false, apply_filters( 'ignition_public_opinion_scripts_before_main', $scripts_before ), ignition_public_opinion_asset_version(), true );

	/**
	 * Filters the list of script handles enqueued after the main script file.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $scripts_after
	 */
	wp_register_script( 'ignition-public-opinion-main-after', false, apply_filters( 'ignition_public_opinion_scripts_after_main', $scripts_after ), ignition_public_opinion_asset_version(), true );

	wp_register_style( 'ignition-public-opinion-style', get_template_directory_uri() . "/style{$suffix}.css", array(), ignition_public_opinion_asset_version() );

	wp_register_script( 'ignition-public-opinion-front-scripts', get_theme_file_uri( "/inc/assets/js/scripts{$suffix}.js" ), array(
		'jquery',
	), ignition_public_opinion_asset_version(), true );

}

add_filter( 'ignition_scripts_before_main', 'ignition_public_opinion_filter_ignition_scripts_before_main' );
/**
 * Modifies the list of scripts enqueued before Ignition's main frontend script file.
 *
 * @since 1.0.0
 *
 * @param string[] $scripts_before
 */
function ignition_public_opinion_filter_ignition_scripts_before_main( $scripts_before ) {
	if ( get_theme_mod( 'header_layout_menu_sticky_type', ignition_public_opinion_ignition_customizer_defaults( 'header_layout_menu_sticky_type' ) ) !== 'off' ) {
		$key = array_search( 'ignition-sticky-header-init', $scripts_before, true );
		if ( false !== $key ) {
			unset( $scripts_before[ $key ] );
		}
	}

	return $scripts_before;
}

add_action( 'wp_enqueue_scripts', 'ignition_public_opinion_enqueue_scripts' );
/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_enqueue_scripts() {
	wp_enqueue_style( 'ignition-public-opinion-main-before' );
	wp_enqueue_style( 'ignition-public-opinion-style' );
	wp_enqueue_style( 'ignition-public-opinion-main-after' );

	wp_enqueue_script( 'ignition-public-opinion-main-before' );
	wp_enqueue_script( 'ignition-public-opinion-front-scripts' );
	wp_enqueue_script( 'ignition-public-opinion-main-after' );
}

add_action( 'init', 'ignition_public_opinion_register_block_editor_block_styles' );
/**
 * Registers custom block styles.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_register_block_editor_block_styles() {
	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-entries-compact',
		'label' => _x( 'Compact Posts', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-layout-overlay',
		'label' => _x( 'Overlaid Posts', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-entries-card',
		'label' => _x( 'Force Media Layout', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-layout-hero-1',
		'label' => _x( '1 Left / 2 Right', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-layout-hero-2',
		'label' => _x( '2 Left / 1 Right', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-layout-hero-3',
		'label' => _x( '1 Left / 4 Right', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-layout-hero-4',
		'label' => _x( '4 Left / 1 Right', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'gutenbee/post-types', array(
		'name'  => 'ignition-public-opinion-layout-overlay-slideshow',
		'label' => _x( 'Slideshow', 'featured posts layout', 'ignition-public-opinion' ),
	) );

	register_block_style( 'core/heading', array(
		'name'  => 'ignition-public-opinion-heading-alt',
		'label' => _x( 'Theme Style', 'heading style', 'ignition-public-opinion' ),
	) );
}

add_filter( 'locale_stylesheet_uri', 'ignition_public_opinion_remove_rtl_stylesheet', 10, 2 );
/**
 * Prohibits the parent theme's rtl.css from loading.
 *
 * It only handles the parent theme's rtl.css, as the theme enqueues it manually.
 * It also allows other stylesheets, such as $locale.css or other $text_direction.css
 *
 * Hooked on `locale_stylesheet_uri`
 *
 * @since 1.0.0
 *
 * @see get_locale_stylesheet_uri()
 *
 * @param string $stylesheet_uri
 * @param string $stylesheet_dir_uri
 *
 * @return string
 */
function ignition_public_opinion_remove_rtl_stylesheet( $stylesheet_uri, $stylesheet_dir_uri ) {
	// Only remove the rtl.css file, and only if it's a parent theme, as we enqueue it manually along with the other styles.
	if ( ! is_child_theme() && untrailingslashit( $stylesheet_dir_uri ) . '/rtl.css' === $stylesheet_uri ) {
		$stylesheet_uri = '';
	}

	return $stylesheet_uri;
}

/**
 * Returns theme-specific generated CSS.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_public_opinion_get_generated_css() {
	$css = '';

	if ( is_singular( 'post' ) ) {
		$color = ignition_public_opinion_get_the_post_category_color( get_queried_object_id() );
		if ( $color ) {
			$css .= sprintf( '
				.entry-content > p > a,
				.entry-content blockquote {
					color: %1$s;
				}

				.wp-block-gutenbee-review-item-inner,
				.entry-tags a {
					background-color: %1$s;
				}
			', $color );
		}
	}

	return $css;
}
