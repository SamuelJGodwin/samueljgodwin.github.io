<?php
/**
 * Localization / internationalization related functions
 *
 * @since 1.0.0
 */

/**
 * Gathers data required to create the language switcher.
 *
 * Checks if WPML or Polylang is active, gets the required data to create the language switcher and stores them in
 * a custom array.
 *
 * @since 1.0.0
 *
 * @return array|bool A custom array with the language switcher data, or false if the supported plugins are not
 *                    found.
 */
function ignition_language_switcher_get_data() {
	$fields_map    = array();
	$list_data     = array();
	$switcher_data = array();

	if ( class_exists( 'Polylang' ) ) {
		// ignition key => plugin key
		$fields_map = array(
			'url'            => 'url',
			'flag'           => 'flag',
			'name'           => 'name',
			'slug'           => 'slug',
			'active'         => 'current_lang',
			'no_translation' => 'no_translation',
		);

		$switcher_data = pll_the_languages( array(
			'raw' => 1,
		) );
	} elseif ( class_exists( 'SitePress' ) ) {
		// ignition key => plugin key
		$fields_map = array(
			'url'            => 'url',
			'flag'           => 'country_flag_url',
			'name'           => 'native_name',
			'slug'           => 'code',
			'active'         => 'active',
			'no_translation' => 'missing',
		);

		/** This filter is documented in WPML. */
		$switcher_data = apply_filters( 'wpml_active_languages', null, 'orderby=custom' );
	} else {
		return false;
	}

	if ( is_array( $switcher_data ) && ! empty( $switcher_data ) ) {
		foreach ( $switcher_data as $lang => $lang_data ) {
			foreach ( $fields_map as $ignition_key => $plugin_key ) {
				if ( isset( $switcher_data[ $lang ][ $plugin_key ] ) ) {
					$list_data[ $lang ][ $ignition_key ] = $lang_data[ $plugin_key ];
				}
			}
		}
	}

	return $list_data;
}

/**
 * Generates home URL for a particular language.
 *
 * Returns the home URL for a selected language both from WPML and Polylang.
 *
 * @since 1.0.0
 *
 * @param string $lang the language slug.
 *
 * @return string the home URL for the requested language.
 */
function ignition_localization_get_home_url( $lang ) {
	$home_url = get_home_url();

	if ( class_exists( 'Polylang' ) ) {
		$home_url = pll_home_url( $lang );
	} elseif ( class_exists( 'SitePress' ) ) {
		global $sitepress;
		$home_url = $sitepress->language_url( $lang );
	}

	return $home_url;
}

/**
 * Outputs a language switcher.
 *
 * @since 1.0.0
 *
 * @param bool   $show_flags     If true, flag icons are displayed on the switcher. Default true.
 * @param bool   $show_text      If true, the language name is displayed on the switcher. Default true.
 * @param string $type           The language switcher layout. Accepts: 'menu', 'dropdown'. Default 'menu'.
 * @param string $no_translation Handles untranslated items, hides flags or links to homepage. Accepts: 'home',
 *                               'hide'. Default 'home'.
 */
function ignition_language_switcher( $show_flags = true, $show_text = true, $type = 'menu', $no_translation = 'home' ) {
	$switcher_data = ignition_language_switcher_get_data();

	if ( ! $switcher_data ) {
		return;
	}

	$classes = array(
		'ignition-language-switcher-wrapper',
		'ignition-language-switcher-type-' . $type,
		$show_flags && $show_text ? 'ignition-language-switcher-text-flags' : '',
		! $show_flags && $show_text ? 'ignition-language-switcher-text-only' : '',
		$show_flags && ! $show_text ? 'ignition-language-switcher-flags-only' : '',
	);
	$classes = array_filter( $classes );

	?><div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"><?php

	if ( 'menu' === $type ) {
		foreach ( $switcher_data as $lang ) {
			$item_html = ignition_language_switcher_get_item_html( $lang, $show_flags, $show_text, $no_translation );
			if ( trim( $item_html ) ) {
				echo wp_kses_post( $item_html );
			}
		}
	} elseif ( 'dropdown' === $type ) {
		?><div class="ignition-language-switcher-dropdown"><?php

		$active_lang_html    = '';
		$inactive_langs_html = array();

		foreach ( $switcher_data as $lang ) {
			$item_html = ignition_language_switcher_get_item_html( $lang, $show_flags, $show_text, $no_translation );
			if ( trim( $item_html ) ) {
				if ( $lang['active'] ) {
					$active_lang_html = $item_html;
				} else {
					$inactive_langs_html[] = '<li>' . $item_html . '</li>';
				}
			}
		}

		if ( ! empty( $active_lang_html ) ) {
			echo wp_kses_post( $active_lang_html );
		}

		if ( ! empty( $inactive_langs_html ) ) {
			echo wp_kses_post( '<ul>' . implode( '', $inactive_langs_html ) . '</ul>' );
		}

		?></div><?php
	}

	?></div><?php
}

/**
 * Returns the HTML markup for a language switcher item.
 *
 * @see ignition_language_switcher()
 *
 * @since 1.0.0
 *
 * @param        $lang
 * @param bool   $show_flags
 * @param bool   $show_text
 * @param string $no_translation
 *
 * @return string
 */
function ignition_language_switcher_get_item_html( $lang, $show_flags = true, $show_text = true, $no_translation = 'home' ) {
	$flag_html = $show_flags ? sprintf( '<img src="%s" alt="%s"/>', esc_url( $lang['flag'] ), esc_attr( $lang['name'] ) ) : '';
	$name_html = $show_text ? sprintf( '<span>%s</span>', esc_html( $lang['name'] ) ) : '';
	$lang_url  = '';
	$item_html = '';

	if ( ! empty( $lang['no_translation'] ) && 'hide' === $no_translation ) {
		return '';
	} elseif ( ! empty( $lang['no_translation'] ) && 'home' === $no_translation ) {
		$lang_url = ignition_localization_get_home_url( $lang['slug'] );
	} else {
		$lang_url = $lang['url'];
	}

	$classes = array( 'ignition-language-switcher-language' );
	if ( $lang['active'] ) {
		$classes[] = 'active-language';
	}

	if ( $lang_url && $lang['active'] ) {
		$item_html = sprintf(
			'<span class="%s">%s%s</span>',
			esc_attr( implode( ' ', $classes ) ),
			$flag_html,
			$name_html
		);
	} elseif ( $lang_url ) {
		$item_html = sprintf(
			'<a href="%s" class="%s">%s%s</a>',
			esc_url( $lang_url ),
			esc_attr( implode( ' ', $classes ) ),
			$flag_html,
			$name_html
		);
	}

	return $item_html;
}
