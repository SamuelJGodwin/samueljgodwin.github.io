<?php
/**
 * Customizer-based generated CSS hooks and functions
 *
 * @since 1.0.0
 */

/**
 * Includes files used for customizer-generated styles.
 *
 * @since 1.0.0
 */
function ignition_include_generated_styles_files() {
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/generated-styles/site.php';

	if ( current_theme_supports( 'ignition-top-bar' ) ) {
		require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/generated-styles/top-bar.php';
	}

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/generated-styles/header.php';

	if ( current_theme_supports( 'ignition-page-title-with-background' ) ) {
		require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/generated-styles/page-title.php';
	}

	require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/generated-styles/footer.php';

	/**
	 * Hook: ignition_include_generated_styles_files.
	 *
	 * Fires when the generated styles files are included.
	 *
	 * @since 2.1.2
	 */
	do_action( 'ignition_include_generated_styles_files' );
}

/**
 * Returns a list of registered Typography customizer controls and their default values.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_registered_typography_controls() {
	$defaults = ignition_customizer_defaults( 'all' );

	$controls = array(
		'site_typo_primary'      => $defaults['site_typo_primary'],
		'site_typo_secondary'    => $defaults['site_typo_secondary'],
		'site_typo_h1'           => $defaults['site_typo_h1'],
		'site_typo_h2'           => $defaults['site_typo_h2'],
		'site_typo_h3'           => $defaults['site_typo_h3'],
		'site_typo_h4'           => $defaults['site_typo_h4'],
		'site_typo_h5'           => $defaults['site_typo_h5'],
		'site_typo_h6'           => $defaults['site_typo_h6'],
		'site_typo_widget_title' => $defaults['site_typo_widget_title'],
		'site_typo_widget_text'  => $defaults['site_typo_widget_text'],
	);

	if ( get_theme_support( 'ignition-typography-page-title' ) ) {
		$controls = array_merge( $controls, array(
			'site_typo_page_title' => $defaults['site_typo_page_title'],
		) );
	}

	if ( get_theme_support( 'ignition-typography-navigation' ) ) {
		$controls = array_merge( $controls, array(
			'site_typo_navigation' => $defaults['site_typo_navigation'],
		) );
	}

	if ( get_theme_support( 'ignition-typography-button' ) ) {
		$controls = array_merge( $controls, array(
			'site_typo_button' => $defaults['site_typo_button'],
		) );
	}

	/**
	 * Filters the list of registered typography controls, and their default values.
	 *
	 * @since 1.0.0
	 *
	 * @param array $controls
	 */
	return apply_filters( 'ignition_registered_typography_controls', $controls );
}

/**
 * Enqueues user-selected Google Fonts, based on user-selected settings.
 *
 * @since 1.0.0
 */
function ignition_enqueue_google_fonts() {
	$css = Ignition_Customizer_CSS_Generator::get_instance();

	if ( is_customize_preview() ) {
		$css->register_typography_control( 'placeholder_preview_font', ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => 'Open Sans',
				'variant'    => 'regular',
				'size'       => '',
				'lineHeight' => '',
				'transform'  => '',
				'spacing'    => '',
				'is_gfont'   => true,
			),
		) ) );
	}

	foreach ( ignition_get_registered_typography_controls() as $option => $default ) {
		$css->register_typography_control( $option, $default );
	}

	$url = $css->get_google_fonts_url();

	wp_enqueue_style( 'ignition-user-google-fonts', $url, array(), ignition_asset_version() );
}

add_filter( 'ignition_customizer_css_generator_add_font_to_url', 'ignition_disable_gfonts_add_font_to_url', 10, 3 );
/**
 * Conditionally short-circuits loading of fonts.
 *
 * @since 1.0.0
 *
 * @param bool $add_font
 * @param string $option_name
 * @param array $default
 *
 * @return bool
 */
function ignition_disable_gfonts_add_font_to_url( $add_font, $option_name, $default ) {
	// Don't fiddle with the value if it isn't directly affected by this options.
	if ( get_theme_mod( 'site_typo_disable_google_fonts', ignition_customizer_defaults( 'site_typo_disable_google_fonts' ) ) ) {
		$add_font = false;
	}

	return $add_font;
}

add_filter( 'ignition_customizer_css_generator_generate_typography_stack', 'ignition_disable_gfonts_font_family', 10, 3 );
/**
 * Removes the first font family from a font stack.
 *
 * Assumes that font stacks are a Google font first, followed by zero or more non-Google fonts.
 *
 * @since 1.0.0
 *
 * @param array $stack
 * @param array $values
 * @param string|array $fallback_stack
 *
 * @return array
 */
function ignition_disable_gfonts_font_family( $stack, $values, $fallback_stack ) {
	if ( ! get_theme_mod( 'site_typo_disable_google_fonts', ignition_customizer_defaults( 'site_typo_disable_google_fonts' ) ) ) {
		return $stack;
	}

	if ( array_key_exists( 'is_gfont', $values ) && true === $values['is_gfont'] ) {
		// Remove the first font from the stack. Assumes that gfont stacks are made up of one gfont and 0 or more non-gfont fonts.
		$first = array_shift( $stack );

		if ( is_null( $first ) ) {
			$stack = array();
		}
	}

	return $stack;
}

/**
 * Generates CSS based on customizer settings.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_get_customizer_css() {
	ignition_include_generated_styles_files();

	$generator = Ignition_Customizer_CSS_Generator::get_instance();

	$css = '';

	$breakpoints = ignition_customizer_breakpoints();

	$desktop_min = $breakpoints['tablet'] + 1;
	$tablet_min  = $breakpoints['mobile'] + 1;

	$variables_css = $generator->get_variables_css();
	if ( $variables_css ) {
		$css .= sprintf( ':root { %s }', $variables_css ) . PHP_EOL;
	}

	$breakpoint_css = $generator->get( 'desktop' );
	if ( trim( $breakpoint_css ) ) {
		$css .= $breakpoint_css . PHP_EOL;
	}

	$breakpoint_css = $generator->get( 'tablet' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (max-width: {$breakpoints['tablet']}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	$breakpoint_css = $generator->get( 'desktop-only' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (min-width: {$desktop_min}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	$breakpoint_css = $generator->get( 'tablet-only' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (min-width: {$tablet_min}px) and (max-width: {$breakpoints['tablet']}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	// 'mobile' breakpoint only applies to mobile anyway, but we have 'mobile-only' as well, for completeness.
	// Merge the two under one media query.
	$breakpoint_css  = $generator->get( 'mobile' );
	$breakpoint_css .= $generator->get( 'mobile-only' );
	if ( trim( $breakpoint_css ) ) {
		$css .= "@media (max-width: {$breakpoints['mobile']}px) {
			{$breakpoint_css}
		}" . PHP_EOL;
	}

	/**
	 * Filters the customizer-based generated styles.
	 *
	 * @since 1.0.0
	 *
	 * @param string $css
	 */
	return apply_filters( 'ignition_customizer_css', $css );
}

/**
 * Returns all customizer-generated styles.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_get_all_customizer_css() {
	$styles = array(
		'customizer' => ignition_get_customizer_css(),
	);

	/**
	 * Filters all customizer-related generated styles.
	 *
	 * @since 1.0.0
	 *
	 * @param array $styles
	 */
	$styles = apply_filters( 'ignition_all_customizer_css', $styles );

	if ( is_customize_preview() ) {
		$styles[] = '/* Placeholder for preview. */';
	}

	return implode( PHP_EOL, $styles );
}

/**
 * Returns customizer-generated styles that are to be used inside the block editor.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_get_block_editor_customizer_css() {
	add_filter( 'ignition_customizer_css_generator_get_array', 'ignition_remove_non_typography_rules' );
	remove_filter( 'ignition_customizer_css', 'ignition_minimize_css' );

	$css = ignition_get_customizer_css();

	add_filter( 'ignition_customizer_css', 'ignition_minimize_css' );
	remove_filter( 'ignition_customizer_css_generator_get_array', 'ignition_remove_non_typography_rules' );

	return $css;
}

/**
 * Removes non-typography CSS rules.
 *
 * @since 1.0.0
 *
 * @param array $rules
 *
 * @return array
 */
function ignition_remove_non_typography_rules( $rules ) {
	$keep_rules = array(
		'font-family',
		'font-weight',
		'font-style',
		'font-size',
		'line-height',
		'text-transform',
		'letter-spacing',
	);

	foreach ( $rules as $key => $ruleset ) {
		// Match patterns:   <selector> { <rules> }
		$ruleset = preg_replace_callback( '/(.*?\{\s*)(.*?)(\s*\})/', function( $ruleset_matches ) use ( $keep_rules ) {
			// Match patterns:   <rule>: <value>;
			$rules = preg_replace_callback( '/(.*?)(\s*:.*?;)/', function( $rule_matches ) use ( $keep_rules ) {
				if ( in_array( trim( $rule_matches[1] ), $keep_rules, true ) ) {
					return $rule_matches[0];
				}

				return '';
			}, $ruleset_matches[2] );

			return $ruleset_matches[1] . $rules . $ruleset_matches[3];
		}, $ruleset );

		$rules[ $key ] = $ruleset;
	}

	return $rules;
}

add_filter( 'ignition_customizer_css', 'ignition_minimize_css' );
/**
 * Minimizes a CSS string.
 *
 * Contracts multiple whitespaces into one space character.
 *
 * @param string $css
 *
 * @return string
 */
function ignition_minimize_css( $css ) {
	$css = preg_replace( '/\s+/', ' ', $css );
	return $css;
}
