<?php
/**
 * Scripts and styles definitions and enqueues
 *
 * @since 1.0.0
 */

/**
 * Returns the filename suffix to be used when enqueuing scripts and styles.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_scripts_styles_suffix() {
	$suffix = '.min';

	if ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
		$suffix = '';
	}

	/**
	 * Filters the filename suffix used for scripts and styles.
	 *
	 * @since 1.0.0
	 *
	 * @param string $suffix
	 */
	return apply_filters( 'ignition_scripts_styles_suffix', $suffix );
}

add_action( 'wp', 'ignition_register_scripts' );
/**
 * Registers frontend scripts and styles.
 *
 * @since 1.0.0
 */
function ignition_register_scripts() {
	$suffix = ignition_scripts_styles_suffix();

	$styles_before  = array(); // Style handles to load before main stylesheet.
	$styles_after   = array(); // Style handles to load after main stylesheet.
	$scripts_before = array(); // Script handles to load before main script.
	$scripts_after  = array(); // Script handles to load after main script.

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		$scripts_before = array_merge( $scripts_before, array( 'comment-reply' ) );
	}

	wp_register_style( 'ignition-icons', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/ignition-icons/css/ignition-icons{$suffix}.css", array(), ignition_asset_version( '1.0.0' ) );
	$styles_before = array_merge( $styles_before, array( 'ignition-icons' ) );

	wp_register_style( 'jquery-magnific-popup', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/magnific/magnific{$suffix}.css", array(), ignition_asset_version( '1.1.0' ) );
	wp_register_script( 'jquery-magnific-popup', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/magnific/jquery.magnific-popup{$suffix}.js", array( 'jquery' ), ignition_asset_version( '1.1.0' ), true );
	wp_register_script( 'ignition-magnific-init', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/magnific-init{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	if ( get_theme_mod( 'utilities_lightbox_is_enabled', ignition_customizer_defaults( 'utilities_lightbox_is_enabled' ) ) ) {
		$styles_before  = array_merge( $styles_before, array( 'jquery-magnific-popup' ) );
		$scripts_before = array_merge( $scripts_before, array(
			'jquery-magnific-popup',
			'ignition-magnific-init',
		) );
	}

	wp_register_style( 'slick', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/slick/slick{$suffix}.css", array(), '1.6.0' );
	wp_register_script( 'slick', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/slick/slick{$suffix}.js", array( 'jquery' ), '1.6.0', true );
	wp_register_script( 'ignition-instagram', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/instagram{$suffix}.js", array( 'zoom-instagram-widget' ), ignition_asset_version(), true );
	if ( class_exists( 'Wpzoom_Instagram_Widget' ) ) {
		// 'slick' script and style is enqueued only when needed, by ignition_shortcode_instagram_feed()
		$scripts_after = array_merge( $scripts_after, array( 'ignition-instagram' ) );
	}

	$sticky_type         = get_theme_mod( 'header_layout_menu_sticky_type', ignition_customizer_defaults( 'header_layout_menu_sticky_type' ) );
	$side_mode_is_sticky = (bool) get_theme_mod( 'side_mode_header_layout_is_sticky', ignition_customizer_defaults( 'side_mode_header_layout_is_sticky' ) );
	$sticky_lib          = 'permanent' === $sticky_type ? 'jquery-sticky' : 'jquery-shyheader';
	if ( current_theme_supports( 'ignition-side-header' ) ) {
		$sticky_lib = 'jquery-sticky-kit';
	}

	wp_register_script( 'jquery-shyheader', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/shyheader/jquery.shyheader{$suffix}.js", array( 'jquery' ), ignition_asset_version( '1.0.0' ), true );
	wp_register_script( 'jquery-sticky', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/sticky/jquery.sticky{$suffix}.js", array( 'jquery' ), ignition_asset_version( '1.0.4' ), true );
	wp_register_script( 'jquery-sticky-kit', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/sticky-kit/jquery.sticky-kit{$suffix}.js", array( 'jquery' ), ignition_asset_version( '1.1.4' ), true );
	wp_register_script( 'ignition-sticky-header-init', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/sticky-header-init{$suffix}.js", array(
		$sticky_lib,
	), ignition_asset_version(), true );
	if ( 'off' !== $sticky_type || $side_mode_is_sticky ) {
		$scripts_before = array_merge( $scripts_before, array( 'ignition-sticky-header-init' ) );
	}

	wp_register_script( 'ignition-video-background', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/video-background{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );

	wp_register_style( 'ignition-woocommerce', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/woocommerce{$suffix}.css", array(), ignition_asset_version() );
	wp_register_script( 'ignition-woocommerce', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/woocommerce{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	if ( class_exists( 'WooCommerce' ) ) {
		$styles_after   = array_merge( $styles_after, array( 'ignition-woocommerce' ) );
		$scripts_before = array_merge( $scripts_before, array( 'ignition-woocommerce' ) );

		wp_register_style( 'ignition-wc-login-popup', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/wc-login-popup{$suffix}.css", array(
			'jquery-magnific-popup',
		), ignition_asset_version() );
		wp_register_script( 'ignition-wc-login-popup', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/wc-login-popup{$suffix}.js", array(
			'jquery',
			'jquery-magnific-popup',
		), ignition_asset_version(), true );
		wp_localize_script( 'ignition-wc-login-popup', 'ignition_wc_popup', array_merge( array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		), ignition_woocommerce_login_popup_get_messages() ) );

		wp_register_style( 'ignition-wc-search', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/wc-search{$suffix}.css", array(), ignition_asset_version() );
		wp_register_script( 'ignition-wc-search', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/wc-search{$suffix}.js", array(
			'jquery',
		), ignition_asset_version(), true );
		wp_localize_script( 'ignition-wc-search', 'ignition_wc_search', array(
			'ajax_url'           => admin_url( 'admin-ajax.php' ),
			'search_no_products' => __( 'No products match your query.', 'ignition' ),
		) );
	}

	wp_register_style( 'ignition-contact-forms', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/contact-forms{$suffix}.css", array(), ignition_asset_version() );
	if ( class_exists( 'WPCF7' ) ) {
		$styles_after = array_merge( $styles_after, array( 'ignition-contact-forms' ) );
	}

	wp_register_script( 'litepicker', untrailingslashit( IGNITION_DIR_URL ) . '/inc/assets/vendor/litepicker/litepicker.min.js', array(), ignition_asset_version( '2.0.11' ), true );
	wp_register_style( 'litepicker', untrailingslashit( IGNITION_DIR_URL ) . '/inc/assets/vendor/litepicker/litepicker.min.css', array(), ignition_asset_version( '2.0.11' ) );
	wp_register_script( 'litepicker-mobile', untrailingslashit( IGNITION_DIR_URL ) . '/inc/assets/vendor/litepicker/mobilefriendly.min.js', array(
		'litepicker',
	), ignition_asset_version( '2.0.11' ), true );

	wp_register_script( 'ignition-shortcode-booking-form', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/booking-form{$suffix}.js", array(
		'litepicker-mobile',
	), ignition_asset_version(), true );

	if ( current_theme_supports( 'ignition-google-maps' ) ) {
		$google_maps_api_key = trim( get_theme_mod( 'utilities_google_maps_api_key', ignition_customizer_defaults( 'utilities_google_maps_api_key' ) ) );
		wp_register_script( 'ignition-google-maps', add_query_arg( array(
			'v'   => '3',
			'key' => $google_maps_api_key,
		), 'https://maps.googleapis.com/maps/api/js' ), array(), ignition_asset_version(), true );

		if ( $google_maps_api_key ) {
			$scripts_before = array_merge( $scripts_before, array( 'ignition-google-maps' ) );
		}
	}


	wp_register_style( 'ignition-maxslider', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/maxslider{$suffix}.css", array(), ignition_asset_version() );
	wp_register_script( 'ignition-maxslider', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/maxslider{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	if ( class_exists( 'MaxSlider' ) ) {
		/**
		 * Filters whether the plugin's MaxSlider styles should be enqueued.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $enqueue
		 */
		if ( apply_filters( 'ignition_maxslider_enqueue_style', true ) ) {
			$styles_after = array_merge( $styles_after, array( 'ignition-maxslider' ) );
		}

		/**
		 * Filters whether the plugin's MaxSlider scripts should be enqueued.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $enqueue
		 */
		if ( apply_filters( 'ignition_maxslider_enqueue_script', true ) ) {
			$scripts_before = array_merge( $scripts_before, array( 'ignition-maxslider' ) );
		}
	}

	wp_register_style( 'ignition-elementor', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/elementor{$suffix}.css", array(), ignition_asset_version() );
	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		$styles_after = array_merge( $styles_after, array( 'ignition-elementor' ) );
	}

	// This should always be the last 'after' style, so that it affects all previous styles.
	if ( is_rtl() ) {
		wp_register_style( 'ignition-style-rtl', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/rtl{$suffix}.css", array( 'ignition-style' ), ignition_asset_version() );
		$styles_after = array_merge( $styles_after, array( 'ignition-style-rtl' ) );
	}

	/**
	 * Filters the list of style handles enqueued before the main stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $styles_before
	 */
	wp_register_style( 'ignition-main-before', false, apply_filters( 'ignition_styles_before_main', $styles_before ), ignition_asset_version() );

	/**
	 * Filters the list of style handles enqueued after the main stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $styles_after
	 */
	wp_register_style( 'ignition-main-after', false, apply_filters( 'ignition_styles_after_main', $styles_after ), ignition_asset_version() );

	/**
	 * Filters the list of script handles enqueued before the main script file.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $scripts_before
	 */
	wp_register_script( 'ignition-main-before', false, apply_filters( 'ignition_scripts_before_main', $scripts_before ), ignition_asset_version(), true );

	/**
	 * Filters the list of script handles enqueued after the main script file.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $scripts_after
	 */
	wp_register_script( 'ignition-main-after', false, apply_filters( 'ignition_scripts_after_main', $scripts_after ), ignition_asset_version(), true );

	wp_register_style( 'ignition-shortcode-minicart', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/minicart{$suffix}.css", array(), ignition_asset_version() );
	wp_register_script( 'ignition-shortcode-minicart', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/minicart{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	wp_register_script( 'ignition-shortcode-weather', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/weather{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	$vars = array(
		'ajaxurl'       => admin_url( 'admin-ajax.php' ),
		'weather_nonce' => wp_create_nonce( 'ignition-weather-check' ),
	);
	wp_localize_script( 'ignition-shortcode-weather', 'ignition_weather_vars', $vars );

	wp_register_style( 'ignition-style', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/style{$suffix}.css", array(), ignition_asset_version() );
	wp_register_script( 'ignition-front-scripts', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/scripts{$suffix}.js", array(), ignition_asset_version(), true );
	$vars = array(
		'expand_submenu' => __( 'Expand submenu', 'ignition' ),
	);
	wp_localize_script( 'ignition-front-scripts', 'ignition_front_vars', $vars );

}

add_action( 'wp_enqueue_scripts', 'ignition_enqueue_scripts' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function ignition_enqueue_scripts() {
	// Load the user-selected google fonts, if any.
	ignition_enqueue_google_fonts();

	wp_enqueue_style( 'ignition-main-before' );
	wp_enqueue_style( 'ignition-style' );
	wp_enqueue_style( 'ignition-main-after' );

	wp_enqueue_script( 'ignition-main-before' );
	wp_enqueue_script( 'ignition-front-scripts' );
	wp_enqueue_script( 'ignition-main-after' );
}

add_action( 'admin_enqueue_scripts', 'ignition_admin_scripts' );
/**
 * Enqueues admin scripts and styles.
 *
 * @since 1.0.0
 *
 * @param string $hook The current admin page.
 */
function ignition_admin_scripts( $hook ) {
	$suffix = ignition_scripts_styles_suffix();

	wp_register_script( 'wp-color-picker-alpha', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/wp-color-picker-alpha/wp-color-picker-alpha{$suffix}.js", array(
		'jquery',
		'wp-color-picker',
	), ignition_asset_version( '2.1.3' ), true );
	wp_localize_script( 'wp-color-picker-alpha', 'wpColorPickerL10n', array(
		'clear'            => __( 'Clear', 'ignition' ),
		'clearAriaLabel'   => __( 'Clear color', 'ignition' ),
		'defaultString'    => __( 'Default', 'ignition' ),
		'defaultAriaLabel' => __( 'Select default color', 'ignition' ),
		'pick'             => __( 'Select Color', 'ignition' ),
		'defaultLabel'     => __( 'Color value', 'ignition' ),
	) );

	wp_register_script( 'lc-color-picker', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/lc-color-picker/lc-color-picker{$suffix}.js", array(), ignition_asset_version( '1.1.1' ), true );

	wp_register_script( 'ignition-side-image-meta-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/custom-fields/controls/side-image/script{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	wp_localize_script( 'ignition-side-image-meta-control', 'sideImageBgStrings', array(
		'selectImage' => esc_html__( 'Select an Image', 'ignition' ),
	) );

	wp_register_script( 'ignition-side-file-select-meta-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/custom-fields/controls/side-file-select/script{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	wp_localize_script( 'ignition-side-file-select-meta-control', 'fileSelectStrings', array(
		'selectFile' => esc_html__( 'Select a File', 'ignition' ),
	) );

	wp_register_style( 'jquery-ui', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/jquery-ui/jquery-ui{$suffix}.css", array(), ignition_asset_version( '1.11.4' ) );

	wp_register_style( 'ignition-repeating-fields', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/ignition-repeating-fields/ignition-repeating-fields{$suffix}.css", array(), ignition_asset_version() );
	wp_register_script( 'ignition-repeating-fields', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/ignition-repeating-fields/ignition-repeating-fields{$suffix}.js", array(
		'jquery',
		'jquery-ui-sortable',
	), ignition_asset_version(), true );

	wp_register_style( 'ignition-post-meta', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/admin/meta{$suffix}.css", array(
		'jquery-ui',
		'wp-color-picker',
	), ignition_asset_version() );
	wp_register_script( 'ignition-post-meta', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/admin/meta{$suffix}.js", array(
		'jquery',
		'jquery-ui-datepicker',
		'wp-color-picker',
		'ignition-side-image-meta-control',
		'ignition-side-file-select-meta-control',
	), ignition_asset_version(), true );

	wp_register_style( 'ignition-widgets', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/css/admin/widgets{$suffix}.css", array(
		'ignition-repeating-fields',
	), ignition_asset_version() );
	wp_register_script( 'ignition-widgets', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/js/admin/widgets{$suffix}.js", array(
		'ignition-repeating-fields',
	), ignition_asset_version(), true );

	$google_maps_api_key = '';
	if ( current_theme_supports( 'ignition-google-maps' ) ) {
		$google_maps_api_key = trim( get_theme_mod( 'utilities_google_maps_api_key', ignition_customizer_defaults( 'utilities_google_maps_api_key' ) ) );
		wp_register_script( 'ignition-google-maps', add_query_arg( array(
			'v'   => '3',
			'key' => $google_maps_api_key,
		), 'https://maps.googleapis.com/maps/api/js' ), array(), ignition_asset_version(), true );

		wp_register_script( 'jquery-gmaps-latlon-picker', untrailingslashit( IGNITION_DIR_URL ) . "/inc/assets/vendor/jquery-gmaps-latlon-picker/jquery-gmaps-latlon-picker{$suffix}.js", array(), ignition_asset_version( '1.2' ), true );
	}

	if ( current_theme_supports( 'ignition-gsection' ) ) {
		$screen = get_current_screen();
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) && 'ignition-gsection' === $screen->post_type ) {
			wp_localize_script( 'ignition-post-meta', 'IgnitionGlobalSection', array(
				'includes' => ignition_module_gsection_get_rules_array(),
			) );
		}
	}

	//
	// Enqueue
	//
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		if ( current_theme_supports( 'ignition-google-maps' ) ) {
			if ( $google_maps_api_key ) {
				wp_enqueue_script( 'ignition-google-maps' );
			}

			if ( current_theme_supports( 'ignition-property' ) ) {
				wp_enqueue_script( 'jquery-gmaps-latlon-picker' );
			}
		}
	}

	if ( in_array( $hook, array( 'post.php', 'post-new.php', 'edit-tags.php', 'term.php' ), true ) ) {
		wp_enqueue_media();
		wp_enqueue_style( 'ignition-post-meta' );
		wp_enqueue_script( 'ignition-post-meta' );
	}

	if ( in_array( $hook, array( 'widgets.php', 'customize.php' ), true ) ) {
		wp_enqueue_style( 'ignition-widgets' );
		wp_enqueue_script( 'ignition-widgets' );
	}
}

add_action( 'enqueue_block_editor_assets', 'ignition_block_editor_scripts' );
/**
 * Enqueues assets for the block editor.
 *
 * @since 1.0.0
 */
function ignition_block_editor_scripts() {
	ignition_enqueue_google_fonts();

	//
	// Ignition block assets
	//
	wp_register_script( 'ignition-blocks-block-editor', untrailingslashit( IGNITION_DIR_URL ) . '/inc/blocks/build/ignition-blocks.min.js', array(
		'wp-components',
		'wp-blocks',
		'wp-element',
		'wp-block-editor',
		'wp-data',
		'wp-date',
		'wp-i18n',
		'wp-compose',
		'wp-keycodes',
		'wp-html-entities',
		'wp-server-side-render',
	), ignition_asset_version(), true );

	wp_register_style( 'ignition-blocks-block-editor', untrailingslashit( IGNITION_DIR_URL ) . '/inc/blocks/build/ignition-blocks.min.css', array(
		'wp-edit-blocks',
	), ignition_asset_version() );

	wp_enqueue_script( 'ignition-blocks-block-editor' );
	wp_enqueue_style( 'ignition-blocks-block-editor' );
}

add_filter( 'pre_http_request', 'ignition_pre_http_request_block_editor_styles', 10, 3 );
/**
 * Intercepts http requests to return a response object that includes customizer-generated CSS for the block editor.
 *
 * @see WP_Http::request()
 *
 * @since 1.0.0
 *
 * @param false|array|WP_Error $response    A preemptive return value of an HTTP request. Default false.
 * @param array                $parsed_args HTTP request arguments.
 * @param string               $url         The request URL.
 *
 * @return array
 */
function ignition_pre_http_request_block_editor_styles( $response, $parsed_args, $url ) {
	$suffix = ignition_scripts_styles_suffix();

	if ( 'https://ignition-block-editor-styles' === $url ) {
		$response = array(
			'headers'  => new Requests_Utility_CaseInsensitiveDictionary(),
			'body'     => implode( PHP_EOL, array(
				ignition_get_block_editor_customizer_css(),
				implode( '', file( untrailingslashit( IGNITION_DIR ) . "/inc/assets/css/admin/editor-styles{$suffix}.css" ) ),
			) ),
			'response' => array(
				'code'    => 200,
				'message' => 'OK',
			),
			'cookies'  => array(),
			'filename' => null,
		);
	}

	return $response;
}
