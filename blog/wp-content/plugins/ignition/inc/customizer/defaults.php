<?php
/**
 * Customizer options' default values
 *
 * @since 1.0.0
 */

/**
 * Returns the customizer's breakpoint widths.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_customizer_breakpoints() {
	/**
	 * Filters the widths of the customizer's preview breakpoints.
	 *
	 * @since 1.0.0
	 *
	 * @param array $breakpoints
	 */
	return apply_filters( 'ignition_customizer_breakpoints', array(
		'desktop' => '',
		'tablet'  => 991,
		'mobile'  => 575,
	) );
}

/**
 * Returns the customizer's default values.
 *
 * @since 1.0.0
 *
 * @param false|string $setting
 *
 * @return mixed
 */
function ignition_customizer_defaults( $setting = false ) {
	// Font family values should match fonts.json 'family' field.
	$primary_font = '-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen-Sans, Ubuntu, Cantarell, Helvetica Neue, sans-serif';

	// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	/**
	 * Filters the default values of Ignition's customize settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults 'setting_name' => 'value' array.
	 */
	$defaults = apply_filters( 'ignition_customizer_defaults', array(
		'side_mode_site_layout_type'            => 'boxed',
		'site_layout_type'                      => 'content_sidebar',
		'side_mode_site_layout_container_width' => ignition_customizer_defaults_empty_breakpoints( array(
			'desktop' => 300,
		) ),
		'site_layout_container_width'           => ignition_customizer_defaults_empty_breakpoints( array(
			'desktop' => 1170,
		) ),
		'site_layout_content_width'             => ignition_customizer_defaults_empty_breakpoints( array(
			'desktop' => 8,
		) ),
		'site_layout_sidebar_width'             => ignition_customizer_defaults_empty_breakpoints( array(
			'desktop' => 4,
		) ),

		'site_colors_body_background'          => '',
		'site_colors_body_background_image'    => ignition_image_bg_control_defaults(),
		'site_colors_primary'                  => '',
		'site_colors_secondary'                => '',
		'site_colors_text'                     => '',
		'site_colors_secondary_text'           => '',
		'site_colors_heading'                  => '',
		'site_colors_border'                   => '',

		'site_colors_forms_background'         => '',
		'site_colors_forms_border'             => '',
		'site_colors_forms_text'               => '',

		'site_colors_buttons_background'       => '',
		'site_colors_buttons_text'             => '',
		'site_colors_buttons_border'           => '',

		'site_typo_disable_google_fonts' => 0,

		// Non-interactive option. Uses the 'site_typo_primary' option values.
		'site_base_font_size'    => true,
		'site_typo_primary'      => ignition_typography_control_defaults_empty_breakpoints( array(
			// If you want some font properties to inherit the style.css values, leave them as empty strings, i.e. ''.
			// This is especially true for numeric values such as size, lineHeight and spacing, where if set to 0
			// instead of '', the value (0) will override the stylesheet value.
			// Similarly, there is no need for 'transform' => 'none', unless there's some transformation cascaded
			// from the stylesheet or from another font option (e.g. global is 'uppercase' but h1 should be 'none').
			'desktop' => array(
				'family'     => $primary_font,
				'variant'    => 'regular',
				'size'       => 16,
				'lineHeight' => 1.56,
				'transform'  => 'none',
				'spacing'    => 0,
				'is_gfont'   => false,
			),
		) ),
		// Font attributes (size, lineHeight, etc) are disabled for this control.
		'site_typo_secondary'    => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'   => $primary_font,
				'variant'  => 'regular',
				'is_gfont' => false,
			),
		) ),
		'site_typo_navigation'   => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => $primary_font,
				'variant'    => 'regular',
				'size'       => 16,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => 0,
				'is_gfont'   => false,
			),
		) ),
		// Font attributes (size, lineHeight, etc) are disabled for this control.
		'site_typo_page_title'   => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'   => $primary_font,
				'variant'  => 'regular',
				'is_gfont' => false,
			),
		) ),
		// Font family and variant are disabled for these controls.
		'site_typo_h1'           => ignition_typography_control_defaults_empty_breakpoints(),
		'site_typo_h2'           => ignition_typography_control_defaults_empty_breakpoints(),
		'site_typo_h3'           => ignition_typography_control_defaults_empty_breakpoints(),
		'site_typo_h4'           => ignition_typography_control_defaults_empty_breakpoints(),
		'site_typo_h5'           => ignition_typography_control_defaults_empty_breakpoints(),
		'site_typo_h6'           => ignition_typography_control_defaults_empty_breakpoints(),
		'site_typo_widget_title' => ignition_typography_control_defaults_empty_breakpoints(),
		'site_typo_widget_text'  => ignition_typography_control_defaults_empty_breakpoints(),

		'site_typo_button'       => ignition_typography_control_defaults_empty_breakpoints( array(
			'desktop' => array(
				'family'     => $primary_font,
				'variant'    => 'regular',
				'size'       => 16,
				'lineHeight' => 1.2,
				'transform'  => 'none',
				'spacing'    => 0,
				'is_gfont'   => false,
			),
		) ),

		'top_bar_layout_is_visible' => (int) current_theme_supports( 'ignition-top-bar' ),
		'top_bar_layout_visibility' => 'all',

		'top_bar_content_area1' => '',
		'top_bar_content_area2' => '',
		'top_bar_content_area3' => '',

		'top_bar_colors_background' => '',
		'top_bar_colors_text'       => '',
		'top_bar_colors_border'     => '',

		'top_bar_transparent_colors_background' => '',
		'top_bar_transparent_colors_text'       => '',
		'top_bar_transparent_colors_border'     => '',

		'header_layout_type'                    => 'normal',
		'header_layout_menu_type'               => 'full_right',
		'header_layout_is_full_width'           => 0,
		'header_layout_menu_sticky_type'        => 'shy',
		'side_mode_header_layout_is_sticky'     => 0,
		'header_layout_menu_mobile_slide_right' => 0,
		'header_layout_menu_mobile_breakpoint'  => array(
			// Non-responsive control, only needs 'desktop'.
			'desktop' => 991,
		),

		'header_content_area'                  => '',
		'side_mode_header_mobile_content_area' => '',

		'header_colors_background'               => '',
		'header_colors_background_image'         => ignition_image_bg_control_defaults(),
		'header_colors_overlay'                  => '',
		'header_colors_text'                     => '',
		'header_colors_border'                   => '',
		'header_colors_submenu_background'       => '',
		'header_colors_submenu_background_hover' => '',
		'header_colors_submenu_text'             => '',
		'header_colors_submenu_text_hover'       => '',

		'header_transparent_colors_background'               => '',
		'header_transparent_colors_background_image'         => ignition_image_bg_control_defaults(),
		'header_transparent_colors_overlay'                  => '',
		'header_transparent_colors_text'                     => '',
		'header_transparent_colors_border'                   => '',
		'header_transparent_colors_submenu_background'       => '',
		'header_transparent_colors_submenu_background_hover' => '',
		'header_transparent_colors_submenu_text'             => '',
		'header_transparent_colors_submenu_text_hover'       => '',

		'header_sticky_colors_background'               => '',
		'header_sticky_colors_background_image'         => ignition_image_bg_control_defaults(),
		'header_sticky_colors_overlay'                  => '',
		'header_sticky_colors_text'                     => '',
		'header_sticky_colors_border'                   => '',
		'header_sticky_colors_submenu_background'       => '',
		'header_sticky_colors_submenu_background_hover' => '',
		'header_sticky_colors_submenu_text'             => '',
		'header_sticky_colors_submenu_text_hover'       => '',

		'header_mobile_nav_colors_background' => '',
		'header_mobile_nav_colors_link'       => '',
		'header_mobile_nav_colors_border'     => '',

		'page_title_with_background_is_visible'            => 0,
		'page_title_with_background_height'                => ignition_customizer_defaults_empty_breakpoints(),
		'page_title_with_background_text_align_horizontal' => 'left',
		'page_title_with_background_text_align_vertical'   => 'middle', // Theme config value. 'top', 'middle', 'bottom'

		'normal_page_title_title_is_visible'      => 1,
		'normal_page_title_subtitle_is_visible'   => 1,

		'breadcrumb_is_visible' => 1,

		'breadcrumb_provider' => '', // Child-Theme config value. Needs to be an alias, e.g. 'navxt', 'yoast', etc. See ignition_get_the_breadcrumb()

		'page_title_colors_background'                => '',
		'page_title_colors_background_image'          => ignition_image_bg_control_defaults(),
		'page_title_colors_background_video'          => '',
		'page_title_colors_background_video_disabled' => 0,
		'page_title_colors_overlay'                   => '',
		'page_title_colors_primary_text'              => '',
		'page_title_colors_secondary_text'            => '',

		'blog_archive_layout_type'                => 'content_sidebar',
		'blog_single_layout_type'                 => 'content_sidebar',
		'blog_archive_posts_layout_type'          => '1col-horz',
		'blog_archive_excerpt_length'             => 35,
		'blog_archive_meta_date_is_visible'       => 1,
		'blog_archive_meta_categories_is_visible' => 1,
		'blog_archive_meta_author_is_visible'     => 1,
		'blog_archive_meta_comments_is_visible'   => 1,

		'blog_single_meta_date_is_visible'       => 1,
		'blog_single_meta_categories_is_visible' => 1,
		'blog_single_meta_author_is_visible'     => 1,
		'blog_single_meta_comments_is_visible'   => 1,
		'blog_single_authorbox_is_visible'       => 1,
		'blog_single_comments_is_visible'        => 1,
		'blog_single_related_columns'            => 3,

		'footer_is_visible'                => 1,
		'footer_widgets_layout_type'       => '4-equal',
		'footer_colors_background'         => '',
		'footer_colors_background_image'   => ignition_image_bg_control_defaults(),
		'footer_colors_border'             => '',
		'footer_colors_title'              => '',
		'footer_colors_text'               => '',
		'footer_content_area1'             => '',
		'footer_content_area2'             => '',
		'footer_credits_colors_background' => '',
		'footer_credits_colors_text'       => '',
		'footer_credits_colors_link'       => '',
		'footer_credits_colors_border'     => '',

		'utilities_openweathermap_api_key'     => '',
		'utilities_openweathermap_location_id' => '2643743',
		'utilities_openweathermap_units'       => 'metric',

		'utilities_lightbox_is_enabled' => 0,

		'utilities_block_editor_dark_mode_is_enabled' => 0,

		'utilities_social_sharing_single_post_is_enabled'    => 0,
		'utilities_social_sharing_single_product_is_enabled' => 0,
		'utilities_social_sharing_facebook_is_enabled'       => 1,
		'utilities_social_sharing_twitter_is_enabled'        => 1,
		'utilities_social_sharing_pinterest_is_enabled'      => 1,
		'utilities_social_sharing_copy_url_is_enabled'       => 1,

		'utilities_button_top_is_enabled' => 1,

		'utilities_block_widgets_is_enabled' => 0,

		'utilities_google_maps_api_key' => '',

		'site_identity_custom_logo_alt'        => '',
		'site_identity_title_is_visible'       => 1,
		'site_identity_description_is_visible' => 1,

		'woocommerce_shop_layout'                          => 'content_sidebar',
		'woocommerce_alt_hover_image_is_enabled'           => 1,
		'woocommerce_sale_flash_percentage_is_enabled'     => 0,
		'woocommerce_shop_mobile_columns'                  => 2,
		'woocommerce_force_show_title_subtitle_is_enabled' => 0,
		'woocommerce_product_upsell_columns'               => 4,
		'woocommerce_product_related_columns'              => 4,
		'woocommerce_cart_cross_sell_columns'              => 4,
		'woocommerce_product_images_layout'                => '',
	) );
	// phpcs:enable

	if ( 'all' === $setting ) {
		return $defaults;
	}

	if ( ! empty( $setting ) && array_key_exists( $setting, $defaults ) ) {
		/**
		 * Filters the default value for a single Ignition's customizer setting.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $default The default value for the setting.
		 * @param string $setting The setting's name.
		 */
		return apply_filters( 'ignition_customizer_default', $defaults[ $setting ], $setting );
	}

	/**
	 * Filters the default value for a single Ignition's customizer setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $default The default value for the setting.
	 * @param string $setting The setting's name.
	 */
	return apply_filters( 'ignition_customizer_default', false, $setting );
}
