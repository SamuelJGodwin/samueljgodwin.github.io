<?php
/**
 * Customizer options' and controls' parameters
 *
 * @since 1.0.0
 */

/**
 * Returns a customizer option's parameters.
 *
 * @since 1.0.0
 *
 * @param false|string $option Option name. Passing 'all' returns the array with all options.
 *
 * @return false|array False when $option doesn't exist. Array otherwise.
 */
function ignition_customizer_options( $option = false ) {
	$options  = array();
	$defaults = ignition_customizer_defaults( 'all' );

//	//
//	// Example: Panel - Section
//	//
//	$panel   = 'panel';
//	$section = 'section';
//	$options = array_merge( $options, apply_filters( 'ignition_customizer_sectionname_options', array(
//		'option_name' => array(
//			'disabled'     => false, // Setting can be disabled in themes by setting this to true. Equivalent to setting all output_args to false.
//			'output_args'  => array(
//				'control'          => true,
//				'live_preview'     => true,
//				'generated_styles' => true,
//			),
//			'setting_args' => array(
//				'customizer_setting_arg' => 'value',
//				...
//			),
//			'control_args' => array(
//				'customizer_control_arg' => 'value',
//				...
//			),
//			'render_args'  => array(
//				'php_and_js_arg' => 'value',
//				...
//			),
//		),
//	) ) );

	//
	// Site - Layout
	//
	$section = 'site_layout';
	/**
	 * Filters a customizer section's controls.
	 *
	 * The dynamic portion of the name, `$section`, refers to the customizer section id.
	 *
	 * @param array $controls {
	 *     An array that consists of the control/option id as the key, and an array of arrays as value.
	 *
	 *     @type array $control_id {
	 *         An array of arrays that consists of 'settings_args' and 'control_args', and optionally more arrays.
	 *
	 *         @type array $settings_args Args array for WP_Customize_Setting
	 *         @type array $control_args Args array for WP_Customize_Control
	 *     }
	 * }
	 *
	 * @since 1.0.0
	 */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'side_mode_site_layout_type'            => array(
			'setting_args' => array(
				'default'           => $defaults['side_mode_site_layout_type'],
				'sanitize_callback' => 'ignition_sanitize_side_mode_site_layout_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Site layout', 'ignition' ),
				'choices' => ignition_get_side_mode_site_layout_types(),
			),
		),
		'site_layout_type'                      => array(
			'setting_args' => array(
				'default'           => $defaults['site_layout_type'],
				'sanitize_callback' => 'ignition_sanitize_site_layout_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Site layout', 'ignition' ),
				'choices' => ignition_get_site_layout_types(),
			),
		),
		'blog_archive_layout_type'              => array(
			'setting_args' => array(
				'default'           => $defaults['blog_archive_layout_type'],
				'sanitize_callback' => 'ignition_sanitize_blog_layout_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Blog layout', 'ignition' ),
				'choices' => ignition_get_blog_layout_types(),
			),
		),
		'blog_single_layout_type'               => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_layout_type'],
				'sanitize_callback' => 'ignition_sanitize_blog_layout_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Single blog post layout', 'ignition' ),
				'choices' => ignition_get_blog_layout_types(),
			),
		),
		'side_mode_site_layout_container_width' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['side_mode_site_layout_container_width'],
				'sanitize_callback' => 'ignition_sanitize_range_control_breakpoints',
			),
			'control_args' => array(
				'section'     => 'site_layout',
				'label'       => __( 'Header width (px)', 'ignition' ),
				'input_attrs' => array(
					'min'  => 100,
					'max'  => 500,
					'step' => 10,
				),
				'responsive'  => false,
			),
			'render_args'  => array(
				// TODO: When support for css_var units is added into Ignition_Customizer_CSS_Generator, rewrite this.
				'breakpoints_css'  => '
				:root {
				  --ignition-site-sidebar-width: %spx;
				}
			',
				'breakpoint_limit' => false,
				'edge_cases'       => array(),
			),
		),
		'site_layout_container_width'           => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_layout_container_width'],
				'sanitize_callback' => 'ignition_sanitize_range_control_breakpoints',
			),
			'control_args' => array(
				'section'     => $section,
				'label'       => __( 'Site width (px)', 'ignition' ),
				'input_attrs' => array(
					'min'  => 800,
					'max'  => 2500,
					'step' => 10,
				),
				'responsive'  => false,
			),
			'render_args'  => array(
				// Extra 60px on the media query to accommodate for container padding (gutter)
				// and 15px whitespace on each side for the viewport.
				'breakpoints_css'  => '
					@media (min-width: calc(%spx + 60px)) {
						.container,
						.theme-grid > .wp-block-gutenbee-container-inner,
						.alignwide .maxslider-slide-content,
						.alignfull .maxslider-slide-content {
							width: %spx;
							max-width: 100%;
						}

						[class*="-template-fullwidth-narrow"] .alignwide {
							width: calc(%spx - 30px);
						}

						.theme-grid > .wp-block-gutenbee-container-inner {
							padding-left: 15px;
							padding-right: 15px;
						}
					}
				',
				'breakpoint_limit' => false,
				'edge_cases'       => array(),
			),
		),
		'site_layout_content_width'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_layout_content_width'],
				'sanitize_callback' => 'ignition_sanitize_range_control_breakpoints',
			),
			'control_args' => array(
				'section'     => $section,
				'label'       => __( 'Content width (columns)', 'ignition' ),
				'description' => __( 'The sum of the content and sidebar columns should be 12.', 'ignition' ),
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 12,
					'step' => 1,
				),
				'responsive'  => false,
			),
		),
		'site_layout_sidebar_width'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_layout_sidebar_width'],
				'sanitize_callback' => 'ignition_sanitize_range_control_breakpoints',
			),
			'control_args' => array(
				'section'     => $section,
				'label'       => __( 'Sidebar width (columns)', 'ignition' ),
				'description' => __( 'The sum of the content and sidebar columns should be 12.', 'ignition' ),
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 12,
					'step' => 1,
				),
				'responsive'  => false,
			),
		),
	) ) );

	//
	// Site - Colors
	//
	$section = 'site_colors';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'site_colors_body_background'       => array(
			'disabled'     => false,
			'output_args'  => array(
				'control'          => true,
				'live_preview'     => true,
				'generated_styles' => true,
			),
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_body_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Body background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-body-background-color',
			),
		),
		'site_colors_body_background_image' => array(
			'disabled'     => false,
			'output_args'  => array(
				'control'          => true,
				'live_preview'     => true,
				'generated_styles' => true,
			),
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_body_background_image'],
				'sanitize_callback' => 'ignition_sanitize_image_bg_control',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Body background image', 'ignition' ),
			),
			'render_args'  => array(
				'image_size' => 'full',
				'css'        => 'body { %s }',
			),
		),
		'site_colors_primary'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_primary'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Primary color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-primary-color',
			),
		),
		'site_colors_secondary'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_secondary'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Secondary color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-secondary-color',
			),
		),
		'site_colors_text'                  => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-text-color',
			),
		),
		'site_colors_secondary_text'        => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_secondary_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Secondary Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-secondary-text-color',
			),
		),
		'site_colors_heading'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_heading'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Headings color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-headings-color',
			),
		),
		'site_colors_border'                => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Borders color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-border-color',
			),
		),
		'site_colors_forms_background'      => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_forms_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Forms Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-forms-background-color',
			),
		),
		'site_colors_forms_border'          => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_forms_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Forms Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-forms-border-color',
			),
		),
		'site_colors_forms_text'            => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_forms_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Forms Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-forms-text-color',
			),
		),
		'site_colors_buttons_background'    => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_buttons_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Buttons Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-buttons-background-color',
			),
		),
		'site_colors_buttons_text'          => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_buttons_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Buttons Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-buttons-text-color',
			),
		),
		'site_colors_buttons_border'        => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_colors_buttons_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Buttons Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-buttons-border-color',
			),
		),
	) ) );

	//
	// Site - Typography
	//
	$section = 'site_typo';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'site_typo_disable_google_fonts' => array(
			'setting_args' => array(
				'default'           => $defaults['site_typo_disable_google_fonts'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Disable Google Fonts', 'ignition' ),
			),
		),
		// Non-interactive option. Uses the 'site_typo_primary' option values.
		'site_base_font_size'            => array(
			'render_args' => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'html { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_primary'              => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_primary'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Primary font', 'ignition' ),
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'body { %s }',
				'breakpoint_limit' => false,

				'css_var'          => '--ignition-primary-font-family',
			),
		),
		'site_typo_secondary'            => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_secondary'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'         => $section,
				'label'           => __( 'Secondary font', 'ignition' ),
				'show_attributes' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'h1,h2,h3,h4,h5,h6,.page-hero-title,.page-title { %s }',
				'breakpoint_limit' => false,

				'css_var'          => '--ignition-secondary-font-family',
			),
		),
		'site_typo_navigation'           => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_navigation'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Navigation font', 'ignition' ),
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => '
					.navigation-main,
					.head-mast .head-content-slot-item,
					.navigation-mobile-wrap {
						%s
					}
				',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_page_title'           => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_page_title'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'         => $section,
				'label'           => __( 'Page title', 'ignition' ),
				'show_attributes' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => '.page-hero-title, .page-title { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_h1'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_h1'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'H1 font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'h1,.page-hero-title,.page-title { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_h2'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_h2'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'H2 font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'h2 { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_h3'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_h3'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'H3 font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'h3 { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_h4'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_h4'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'H4 font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'h4 { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_h5'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_h5'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'H5 font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'h5 { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_h6'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_h6'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'H6 font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => 'h6 { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_widget_title'         => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_widget_title'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'Widget title font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => '.widget-title { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_widget_text'          => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_widget_text'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section'      => $section,
				'label'        => __( 'Widget text font', 'ignition' ),
				'show_family'  => false,
				'show_variant' => false,
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => '.widget { %s }',
				'breakpoint_limit' => false,
			),
		),
		'site_typo_button'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_typo_button'],
				'sanitize_callback' => 'ignition_sanitize_typography_control_breakpoints',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Button font', 'ignition' ),
			),
			'render_args'  => array(
				'fallback_stack'   => '',
				'breakpoints_css'  => '
					.btn,
					.button,
					.gutenbee-block-button-link,
					.wp-block-button__link,
					.comment-reply-link,
					.ci-item-filter,
					.maxslider-slide .maxslider-btn,
					.added_to_cart,
					input[type="submit"],
					input[type="reset"],
					button[type="submit"] {
						%s
					}
				',
				'breakpoint_limit' => false,
			),
		),
	) ) );


	//
	// Top Bar
	//
	$section = 'top_bar';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'top_bar_layout_is_visible'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_layout_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Show top bar', 'ignition' ),
			),
		),
		'top_bar_layout_visibility'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_layout_visibility'],
				'sanitize_callback' => 'ignition_sanitize_devices_visibility',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Top bar visibility', 'ignition' ),
				'choices' => ignition_devices_visibility_choices(),
			),
		),
		'top_bar_content_area1'                 => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_content_area1'],
				'sanitize_callback' => 'wp_kses_post',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Left content area', 'ignition' ),
				/* translators: %s is a URL */
				'description' => wp_kses( ignition_customize_get_text_field_shortcodes_description(), ignition_get_allowed_tags( 'guide' ) ),
			),
		),
		'top_bar_content_area2'                 => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_content_area2'],
				'sanitize_callback' => 'wp_kses_post',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Middle content area', 'ignition' ),
				/* translators: %s is a URL */
				'description' => wp_kses( ignition_customize_get_text_field_shortcodes_description(), ignition_get_allowed_tags( 'guide' ) ),
			),
		),
		'top_bar_content_area3'                 => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_content_area3'],
				'sanitize_callback' => 'wp_kses_post',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Right content area', 'ignition' ),
				/* translators: %s is a URL */
				'description' => wp_kses( ignition_customize_get_text_field_shortcodes_description(), ignition_get_allowed_tags( 'guide' ) ),
			),
		),
		'top_bar_colors_background'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-top-bar-background-color',
			),
		),
		'top_bar_colors_text'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-top-bar-text-color',
			),
		),
		'top_bar_colors_border'                 => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-top-bar-border-color',
			),
		),
		'top_bar_transparent_colors_background' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_transparent_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-top-bar-transparent-background-color',
			),
		),
		'top_bar_transparent_colors_text'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_transparent_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-top-bar-transparent-text-color',
			),
		),
		'top_bar_transparent_colors_border'     => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['top_bar_transparent_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-top-bar-transparent-border-color',
			),
		),
	) ) );

	//
	// Header - Layout
	//
	$section = 'header_layout';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'header_layout_type'                    => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_layout_type'],
				'sanitize_callback' => 'ignition_sanitize_header_layout_type',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'select',
				'label'       => __( 'Header Type', 'ignition' ),
				'description' => wp_kses( sprintf(
					/* translators: %1$s is the URL to the Header Colors section. */
					__( 'If you select the <strong>Transparent</strong> option make sure to adjust the <a href="%1$s">Header colors</a> accordingly.', 'ignition' ),
					admin_url( '/customize.php?autofocus[section]=header_colors' )
				), ignition_get_allowed_tags() ),
				'choices'     => ignition_header_layout_type_choices(),
			),
		),
		'header_layout_is_full_width'           => array(
			'disabled'     => false,
			'output_args'  => array(
				'control'          => true,
				'live_preview'     => true, // Not applicable.
				'generated_styles' => true, // Not applicable.
			),
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_layout_is_full_width'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Full width header', 'ignition' ),
			),
		),
		'header_layout_menu_sticky_type'        => array(
			'setting_args' => array(
				'default'           => $defaults['header_layout_menu_sticky_type'],
				'sanitize_callback' => 'ignition_sanitize_sticky_menu_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Sticky menu', 'ignition' ),
				'choices' => ignition_sticky_menu_type_choices(),
			),
		),
		'side_mode_header_layout_is_sticky'     => array(
			'setting_args' => array(
				'default'           => $defaults['side_mode_header_layout_is_sticky'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'checkbox',
				'label'       => __( 'Fixed header', 'ignition' ),
				'description' => __( 'When enabled, the header will not scroll with the rest of the content.', 'ignition' ),
			),
		),
		'header_layout_menu_type'               => array(
			'setting_args' => array(
				'default'           => $defaults['header_layout_menu_type'],
				'sanitize_callback' => 'ignition_sanitize_header_layout_menu_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Menu layout', 'ignition' ),
				'choices' => ignition_header_layout_menu_type_choices(),
			),
		),
		'header_layout_menu_mobile_slide_right' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_layout_menu_mobile_slide_right'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Mobile menu slides in from the left.', 'ignition' ),
			),
		),
		'header_layout_menu_mobile_breakpoint'  => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_layout_menu_mobile_breakpoint'],
				'sanitize_callback' => 'ignition_sanitize_range_control_breakpoints',
			),
			'control_args' => array(
				'section'     => $section,
				'label'       => __( 'Display mobile menu when viewport size is less than (px)', 'ignition' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 1600,
					'step' => 1,
				),
				'responsive'  => false,
			),
			'render_args'  => array(
				'breakpoint'       => 'desktop',
				'css'              => '
					@media (max-width: %spx) {
						#mobilemenu {
							display: block;
						}

						.head-content-slot-mobile-nav {
							display: inline-block;
						}

						.nav {
							display: none;
						}

						.header-full-nav-center .site-branding,
						.site-branding {
							max-width: 45%;
							width: auto;
							text-align: left;
						}

						.header-nav-split .site-branding {
							text-align: left;
						}

						.head-slot:first-of-type {
							display: none;
						}
					}
				',
				'breakpoint_limit' => false,
			),
		),
	) ) );

	//
	// Header - Content
	//
	$section = 'header_content';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'header_content_area'                  => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_content_area'],
				'sanitize_callback' => 'wp_kses_post',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Content area', 'ignition' ),
				'description' => wp_kses(
					ignition_customize_get_text_field_shortcodes_description() . '<br/>' . __( 'Added content will appear next or opposite your menu, depending on the <strong>Menu Layout</strong> selected.', 'ignition' ),
					ignition_get_allowed_tags( 'guide' )
				),
			),
		),
		'side_mode_header_mobile_content_area' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['side_mode_header_mobile_content_area'],
				'sanitize_callback' => 'wp_kses_post',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Mobile content area', 'ignition' ),
				'description' => wp_kses(
					ignition_customize_get_text_field_shortcodes_description() . '<br/>' . __( 'Added content will only appear on mobile viewports.', 'ignition' ),
					ignition_get_allowed_tags( 'guide' )
				),
			),
		),
	) ) );

	//
	// Header - Colors
	//
	$section = 'header_colors';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'header_colors_background'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-background-color',
			),
		),
		'header_colors_background_image'         => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_background_image'],
				'sanitize_callback' => 'ignition_sanitize_image_bg_control',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background image', 'ignition' ),
			),
			'render_args'  => array(
				'image_size' => 'full',
				'css'        => '.header-normal { %s }',
			),
		),
		'header_colors_overlay'                  => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_overlay'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Overlay color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-overlay-background-color',
			),
		),
		'header_colors_text'                     => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-text-color',
			),
		),
		'header_colors_border'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-border-color',
			),
		),
		'header_colors_submenu_background'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_submenu_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-submenu-background-color',
			),
		),
		'header_colors_submenu_background_hover' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_submenu_background_hover'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color active & hover', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-submenu-background-color-hover',
			),
		),
		'header_colors_submenu_text'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_submenu_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-submenu-text-color',
			),
		),
		'header_colors_submenu_text_hover'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_colors_submenu_text_hover'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color active & hover', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-submenu-text-color-hover',
			),
		),
	) ) );

	//
	// Header - Transparent Colors
	//
	$section = 'header_transparent_colors';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'header_transparent_colors_background'         => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-background-color',
			),
		),
		'header_transparent_colors_background_image'   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_background_image'],
				'sanitize_callback' => 'ignition_sanitize_image_bg_control',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background image', 'ignition' ),
			),
			'render_args'  => array(
				'image_size' => 'full',
				'css'        => '.header-fixed { %s }',
			),
		),
		'header_transparent_colors_overlay'            => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_overlay'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Overlay color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-overlay-background-color',
			),
		),
		'header_transparent_colors_text'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-text-color',
			),
		),
		'header_transparent_colors_border'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-border-color',
			),
		),
		'header_transparent_colors_submenu_background' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_submenu_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-submenu-bg-color',
			),
		),
		'header_transparent_colors_submenu_background_hover' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_submenu_background_hover'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color active & hover', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-submenu-bg-color-hover',
			),
		),
		'header_transparent_colors_submenu_text'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_submenu_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-submenu-text-color',
			),
		),
		'header_transparent_colors_submenu_text_hover' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_transparent_colors_submenu_text_hover'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color active & hover', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-transparent-submenu-text-color-hover',
			),
		),
	) ) );

	//
	// Header - Sticky Colors
	//
	$section = 'header_sticky_colors';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'header_sticky_colors_background'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-background-color',
			),
		),
		'header_sticky_colors_background_image' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_background_image'],
				'sanitize_callback' => 'ignition_sanitize_image_bg_control',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background image', 'ignition' ),
			),
			'render_args'  => array(
				'image_size' => 'full',
				'css'        => '.head-mast.sticky-fixed { %s }',
			),
		),
		'header_sticky_colors_overlay'          => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_overlay'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Overlay color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-overlay-background-color',
			),
		),
		'header_sticky_colors_text'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-text-color',
			),
		),
		'header_sticky_colors_border'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-border-color',
			),
		),
		'header_sticky_colors_submenu_background' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_submenu_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-submenu-background-color',
			),
		),
		'header_sticky_colors_submenu_background_hover' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_submenu_background_hover'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color active & hover', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-submenu-background-color-hover',
			),
		),
		'header_sticky_colors_submenu_text'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_submenu_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-submenu-text-color',
			),
		),
		'header_sticky_colors_submenu_text_hover' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_sticky_colors_submenu_text_hover'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color active & hover', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-header-sticky-submenu-text-color-hover',
			),
		),
	) ) );

	//
	// Header - Mobile Menu Colors
	//
	$section = 'header_mobile_nav_colors';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'header_mobile_nav_colors_background' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_mobile_nav_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-mobile-nav-background-color',
			),
		),
		'header_mobile_nav_colors_link'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_mobile_nav_colors_link'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-mobile-nav-text-color',
			),
		),
		'header_mobile_nav_colors_border'     => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['header_mobile_nav_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-mobile-nav-border-color',
			),
		),
	) ) );

	//
	// Page Title
	//
	$section = 'page_title';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'page_title_with_background_is_visible'            => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_with_background_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Show Page Title with Background', 'ignition' ),
			),
		),
		'page_title_with_background_height'                => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_with_background_height'],
				'sanitize_callback' => 'ignition_sanitize_range_control_breakpoints',
			),
			'control_args' => array(
				'section'     => $section,
				'label'       => __( 'Page Title Height (px)', 'ignition' ),
				'input_attrs' => array(
					'min'  => - 1,
					'max'  => 1000,
					'step' => 1,
				),
				'responsive'  => true,
			),
			'render_args'  => array(
				'breakpoints_css'  => '.page-hero { height: %spx; }',
				'breakpoint_limit' => true,
				'edge_cases'       => array(
					- 1 => '.page-hero { height: 100vh; }',
				),
			),
		),
		'page_title_with_background_text_align_horizontal' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_with_background_text_align_horizontal'],
				'sanitize_callback' => 'ignition_sanitize_align_horizontal',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Page Title Horizontal alignment', 'ignition' ),
				'choices' => ignition_align_horizontal_choices(),
			),
		),
		'normal_page_title_title_is_visible'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['normal_page_title_title_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => current_theme_supports( 'ignition-page-title-with-background' ) ? __( 'Show Normal Page Title', 'ignition' ) : __( 'Show Page Title', 'ignition' ),
			),
		),
		'normal_page_title_subtitle_is_visible'            => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['normal_page_title_subtitle_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => current_theme_supports( 'ignition-page-title-with-background' ) ? __( 'Show Normal Page Subtitle', 'ignition' ) : __( 'Show Page Subtitle', 'ignition' ),
			),
		),
		'breadcrumb_is_visible'                            => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['breadcrumb_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'         => $section,
				'type'            => 'checkbox',
				'label'           => __( 'Show Breadcrumbs', 'ignition' ),
				'active_callback' => 'ignition_can_show_breadcrumb',
			),
		),
		'page_title_colors_background'                     => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-hero-background-color',
			),
		),
		'page_title_colors_background_image'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_colors_background_image'],
				'sanitize_callback' => 'ignition_sanitize_image_bg_control',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background image', 'ignition' ),
			),
			'render_args'  => array(
				'image_size' => 'full',
				'css'        => '.page-hero { %s }',
			),
		),
		'page_title_colors_background_video'               => array(
			'setting_args' => array(
				'default'           => $defaults['page_title_colors_background_video'],
				'sanitize_callback' => 'esc_url_raw',
			),
			'control_args' => array(
				'section'   => $section,
				'label'     => __( 'Background video', 'ignition' ),
				'file_type' => 'video',
			),
		),
		'page_title_colors_background_video_disabled'      => array(
			'setting_args' => array(
				'default'           => $defaults['page_title_colors_background_video_disabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Disable background video', 'ignition' ),
			),
		),
		'page_title_colors_overlay'                        => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_colors_overlay'],
				'sanitize_callback' => 'sanitize_text_field',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Overlay color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-hero-overlay-background-color',
			),
		),
		'page_title_colors_primary_text'                   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_colors_primary_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Primary Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-hero-primary-text-color',
			),
		),
		'page_title_colors_secondary_text'                 => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['page_title_colors_secondary_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Secondary Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-hero-secondary-text-color',
			),
		),
	) ) );

	//
	// Blog
	//
	$section = 'blog';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		//
		// Archives section header - Priority 10
		//
		'blog_archive_posts_layout_type'          => array(
			'setting_args' => array(
				'default'           => $defaults['blog_archive_posts_layout_type'],
				'sanitize_callback' => 'ignition_sanitize_blog_archive_posts_layout_type',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 20,
				'type'     => 'select',
				'label'    => __( 'Posts layout type', 'ignition' ),
				'choices'  => ignition_blog_archive_posts_layout_type_choices(),
			),
		),
		'blog_archive_excerpt_length'             => array(
			'setting_args' => array(
				'default'           => $defaults['blog_archive_excerpt_length'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 30,
				'type'     => 'text',
				'label'    => __( 'Excerpt length (words)', 'ignition' ),
			),
		),
		'blog_archive_meta_date_is_visible'       => array(
			'setting_args' => array(
				'default'           => $defaults['blog_archive_meta_date_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 40,
				'type'     => 'checkbox',
				'label'    => __( 'Show date', 'ignition' ),
			),
		),
		'blog_archive_meta_categories_is_visible' => array(
			'setting_args' => array(
				'default'           => $defaults['blog_archive_meta_categories_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 50,
				'type'     => 'checkbox',
				'label'    => __( 'Show categories', 'ignition' ),
			),
		),
		'blog_archive_meta_author_is_visible'     => array(
			'setting_args' => array(
				'default'           => $defaults['blog_archive_meta_author_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 60,
				'type'     => 'checkbox',
				'label'    => __( 'Show author', 'ignition' ),
			),
		),
		'blog_archive_meta_comments_is_visible'   => array(
			'setting_args' => array(
				'default'           => $defaults['blog_archive_meta_comments_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 70,
				'type'     => 'checkbox',
				'label'    => __( 'Show comments', 'ignition' ),
			),
		),
		//
		// Single Post section header - Priority 100
		//
		'blog_single_meta_date_is_visible'        => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_meta_date_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 110,
				'type'     => 'checkbox',
				'label'    => __( 'Show date', 'ignition' ),
			),
		),
		'blog_single_meta_categories_is_visible'  => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_meta_categories_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 120,
				'type'     => 'checkbox',
				'label'    => __( 'Show categories', 'ignition' ),
			),
		),
		'blog_single_meta_author_is_visible'      => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_meta_author_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 130,
				'type'     => 'checkbox',
				'label'    => __( 'Show author', 'ignition' ),
			),
		),
		'blog_single_meta_comments_is_visible'    => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_meta_comments_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 140,
				'type'     => 'checkbox',
				'label'    => __( 'Show comments', 'ignition' ),
			),
		),
		'blog_single_authorbox_is_visible'        => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_authorbox_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 150,
				'type'     => 'checkbox',
				'label'    => __( 'Show author box', 'ignition' ),
			),
		),
		'blog_single_comments_is_visible'         => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_comments_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 160,
				'type'     => 'checkbox',
				'label'    => __( 'Show comments section', 'ignition' ),
			),
		),
		'blog_single_related_columns'             => array(
			'setting_args' => array(
				'default'           => $defaults['blog_single_related_columns'],
				'sanitize_callback' => 'ignition_sanitize_blog_single_related_columns',
			),
			'control_args' => array(
				'section'  => $section,
				'priority' => 170,
				'type'     => 'select',
				'label'    => __( 'Related posts', 'ignition' ),
				'choices'  => ignition_blog_single_related_columns_choices(),
			),
		),
	) ) );


	//
	// Footer
	//
	$section = 'footer';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'footer_is_visible'                => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Show footer', 'ignition' ),
			),
		),
		'footer_widgets_layout_type'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_widgets_layout_type'],
				'sanitize_callback' => 'ignition_sanitize_footer_widgets_layout_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Widgets Layout Type', 'ignition' ),
				'choices' => ignition_footer_widgets_layout_type_choices(),
			),
		),
		'footer_colors_background'         => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-background-color',
			),
		),
		'footer_colors_background_image'   => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_colors_background_image'],
				'sanitize_callback' => 'ignition_sanitize_image_bg_control',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background image', 'ignition' ),
			),
			'render_args'  => array(
				'image_size' => 'full',
				'css'        => '.footer { %s }',
			),
		),
		'footer_colors_border'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-border-color',
			),
		),
		'footer_colors_title'              => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_colors_title'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Titles color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-title-color',
			),
		),
		'footer_colors_text'               => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-text-color',
			),
		),
		'footer_content_area1'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_content_area1'],
				'sanitize_callback' => 'wp_kses_post',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Left content area', 'ignition' ),
				/* translators: %s is a URL */
				'description' => wp_kses( ignition_customize_get_text_field_shortcodes_description(), ignition_get_allowed_tags( 'guide' ) ),
			),
		),
		'footer_content_area2'             => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_content_area2'],
				'sanitize_callback' => 'wp_kses_post',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Right content area', 'ignition' ),
				/* translators: %s is a URL */
				'description' => wp_kses( ignition_customize_get_text_field_shortcodes_description(), ignition_get_allowed_tags( 'guide' ) ),
			),
		),
		'footer_credits_colors_background' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_credits_colors_background'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Background color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-credits-background-color',
			),
		),
		'footer_credits_colors_text'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_credits_colors_text'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Text color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-credits-text-color',
			),
		),
		'footer_credits_colors_link'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_credits_colors_link'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Link color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-credits-link-color',
			),
		),
		'footer_credits_colors_border'     => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['footer_credits_colors_border'],
				'sanitize_callback' => 'ignition_sanitize_rgba_color',
			),
			'control_args' => array(
				'section' => $section,
				'label'   => __( 'Border color', 'ignition' ),
			),
			'render_args'  => array(
				'css_var' => '--ignition-footer-credits-border-color',
			),
		),
	) ) );



	//
	// Utilities - Weather
	//
	$section = 'utilities_weather';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'utilities_openweathermap_api_key'     => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_openweathermap_api_key'],
				'sanitize_callback' => 'sanitize_text_field',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'API Key', 'ignition' ),
				/* translators: %s is a URL to the OpenWeatherMap.org AppID page. */
				'description' => wp_kses( sprintf( __( 'In order to use the weather feature, you need a free API key from <a href="%s" target="_blank">OpenWeatherMap.org</a>', 'ignition' ),
					'https://openweathermap.org/appid'
				), ignition_get_allowed_tags( 'guide' ) ),
			),
		),
		'utilities_openweathermap_location_id' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_openweathermap_location_id'],
				'sanitize_callback' => 'sanitize_text_field',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Location ID', 'ignition' ),
				/* translators: %1$s is the URL to openweathermap.com. %2$s is an non-linked example URL. %3$s is a location ID. */
				'description' => wp_kses( sprintf( __( 'Enter the location ID number of your city. The location ID number can be found by visiting <a href="%1$s" target="_blank">OpenWeatherMap.org</a> and searching for your city. Once you are on your city\'s page, the location ID is the last numeric part of the URL. For example, the URL for London, UK is <code>%2$s</code> and the location ID is <code>%3$s</code>.', 'ignition' ),
					'https://openweathermap.org/',
					'https://openweathermap.org/city/2643743',
					'2643743'
				), ignition_get_allowed_tags( 'guide' ) ),
			),
		),
		'utilities_openweathermap_units'       => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_openweathermap_units'],
				'sanitize_callback' => 'ignition_openweathermap_sanitize_units',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Units', 'ignition' ),
				'choices' => ignition_openweathermap_get_units_choices(),
			),
		),
	) ) );

	//
	// Utilities - Lightbox
	//
	$section = 'utilities_lightbox';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'utilities_lightbox_is_enabled' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['utilities_lightbox_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Enable lightbox', 'ignition' ),
			),
		),
	) ) );

	//
	// Utilities - Block Editor
	//
	$section = 'utilities_block_editor';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'utilities_block_editor_dark_mode_is_enabled' => array(
			'setting_args' => array(
				'transport'         => 'postMessage', // Avoid refreshing the Customizer's preview, as this option isn't applicable there.
				'default'           => $defaults['utilities_block_editor_dark_mode_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'checkbox',
				'label'       => __( 'Enable dark mode in the block editor', 'ignition' ),
				'description' => __( 'Enable this setting when your website has a dark background to improve the block editor experience.', 'ignition' ),
			),
		),
	) ) );

	//
	// Utilities - Social Sharing
	//
	$section = 'utilities_social_sharing';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'utilities_social_sharing_single_post_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_social_sharing_single_post_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Show in posts', 'ignition' ),
			),
		),
		'utilities_social_sharing_single_product_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_social_sharing_single_product_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Show in products', 'ignition' ),
			),
		),
		'utilities_social_sharing_facebook_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_social_sharing_facebook_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Facebook', 'ignition' ),
			),
		),
		'utilities_social_sharing_twitter_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_social_sharing_twitter_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Twitter', 'ignition' ),
			),
		),
		'utilities_social_sharing_pinterest_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_social_sharing_pinterest_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Pinterest', 'ignition' ),
			),
		),
//		'utilities_social_sharing_copy_url_is_enabled' => array(
//			'setting_args' => array(
//				'default'           => $defaults['utilities_social_sharing_copy_url_is_enabled'],
//				'sanitize_callback' => 'absint',
//			),
//			'control_args' => array(
//				'section' => $section,
//				'type'    => 'checkbox',
//				'label'   => __( 'Copy URL', 'ignition' ),
//			),
//		),
	) ) );

	//
	// Utilities - Lightbox
	//
	$section = 'utilities_button_top';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'utilities_button_top_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_button_top_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'checkbox',
				'label'       => __( 'Enable back to top button', 'ignition' ),
				'description' => __( 'The Back to Top Button will always be visible in the customizer if it is enabled. On your normal site it will become visible after a certain amount of scrolling.', 'ignition' ),
			),
		),
	) ) );

	//
	// Utilities - Widgets
	//
	$section = 'utilities_widgets';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'utilities_block_widgets_is_enabled' => array(
			'setting_args' => array(
				'transport'         => 'postMessage', // Avoid refreshing the Customizer's preview, as this option isn't applicable there.
				'default'           => $defaults['utilities_block_widgets_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'checkbox',
				'label'       => __( 'Enable Block Widgets support', 'ignition' ),
				'description' => __( 'Enable this setting if you want to use Blocks as widgets. Please note that after changing and publishing this setting, you need to refresh the Customizer page for the option to take effect.', 'ignition' ),
			),
		),
	) ) );

	//
	// Utilities - Google Maps API
	//
	$section = 'utilities_google_maps';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'utilities_google_maps_api_key' => array(
			'setting_args' => array(
				'default'           => $defaults['utilities_google_maps_api_key'],
				'sanitize_callback' => 'sanitize_text_field',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => __( 'Google Maps API key', 'ignition' ),
				/* translators: %1$s and %2$s are URLs. */
				'description' => sprintf( __( 'Paste here your Google Maps API Key. Maps will <strong>not</strong> be displayed without an API key. You need to issue a key from <a href="%1$s" target="_blank">Google Accounts</a>, and make sure the <strong>Google Maps JavaScript API</strong> is enabled. For instructions on issuing an API key, <a href="%2$s" target="_blank">read this article</a>.', 'ignition' ),
					'https://code.google.com/apis/console/',
					'http://www.cssigniter.com/docs/article/generate-a-google-maps-api-key/'
				),
			),
		),
	) ) );

	//
	// Site Identity
	//
	$custom_logo_args = get_theme_support( 'custom-logo' );

	$section = 'title_tagline';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'site_identity_custom_logo_alt'        => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_identity_custom_logo_alt'],
				'sanitize_callback' => 'ignition_sanitize_intval_or_empty',
			),
			'control_args' => array(
				'section'       => $section,
				'label'         => __( 'Alternative Logo', 'ignition' ),
				'description'   => __( 'Set this if you need a differently styled logo to appear when the header is transparent (i.e. appears over the content).', 'ignition' ),
				'height'        => $custom_logo_args[0]['height'],
				'width'         => $custom_logo_args[0]['width'],
				'flex_height'   => $custom_logo_args[0]['flex-height'],
				'flex_width'    => $custom_logo_args[0]['flex-width'],
				'button_labels' => array(
					'select'       => __( 'Select logo', 'ignition' ),
					'change'       => __( 'Change logo', 'ignition' ),
					'remove'       => __( 'Remove', 'ignition' ),
					'default'      => __( 'Default', 'ignition' ),
					'placeholder'  => __( 'No logo selected', 'ignition' ),
					'frame_title'  => __( 'Select logo', 'ignition' ),
					'frame_button' => __( 'Choose logo', 'ignition' ),
				),
			),
		),
		'site_identity_title_is_visible'       => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_identity_title_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Show site title', 'ignition' ),
			),
		),
		'site_identity_description_is_visible' => array(
			'setting_args' => array(
				'transport'         => 'postMessage',
				'default'           => $defaults['site_identity_description_is_visible'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Show site tagline', 'ignition' ),
			),
		),
	) ) );

	//
	// WooCommerce - Product Catalog
	//
	$section = 'woocommerce_product_catalog';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'woocommerce_shop_layout'                          => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_shop_layout'],
				'sanitize_callback' => 'ignition_sanitize_site_layout_type',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Shop layout', 'ignition' ),
				'choices' => ignition_get_site_layout_types(),
			),
		),
		'woocommerce_alt_hover_image_is_enabled'           => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_alt_hover_image_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'checkbox',
				'label'       => __( 'Show alternate image on hover.', 'ignition' ),
				'description' => __( "When enabled, the first image from the product's gallery (if available) will be displayed on hover.", 'ignition' ),
			),
		),
		'woocommerce_sale_flash_percentage_is_enabled'     => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_sale_flash_percentage_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'checkbox',
				'label'       => __( 'Show the percentage of discount on the sale badge.', 'ignition' ),
				'description' => __( 'Applies to product listings and single product pages.', 'ignition' ),
			),
		),
		'woocommerce_shop_mobile_columns'                  => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_shop_mobile_columns'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'priority'    => 20,
				'type'        => 'number',
				'label'       => __( 'Products per row (mobile)', 'ignition' ),
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 2,
					'step' => 1,
				),
			),
		),
		'woocommerce_force_show_title_subtitle_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_force_show_title_subtitle_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => __( 'Always show the Page Title/Subtitle on product taxonomies (categories, tags).', 'ignition' ),
			),
		),
		'woocommerce_product_upsell_columns'               => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_product_upsell_columns'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'priority'    => 30,
				'type'        => 'number',
				'label'       => __( 'Up-sells columns', 'ignition' ),
				'description' => __( 'Up-sells are products that you recommend instead of the currently viewed product, and are displayed in single product pages.', 'ignition' ),
				'input_attrs' => array(
					'min'  => 2,
					'max'  => 4,
					'step' => 1,
				),
			),
		),
		'woocommerce_product_related_columns'              => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_product_related_columns'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'priority'    => 40,
				'type'        => 'number',
				'label'       => __( 'Related columns', 'ignition' ),
				'description' => __( 'Related products are other products from your store, that share one or more tags or categories with the currently viewed product.', 'ignition' ),
				'input_attrs' => array(
					'min'  => 2,
					'max'  => 4,
					'step' => 1,
				),
			),
		),
		'woocommerce_cart_cross_sell_columns'              => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_cart_cross_sell_columns'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'priority'    => 50,
				'type'        => 'number',
				'label'       => __( 'Cross-sells columns', 'ignition' ),
				'description' => __( 'Cross-sells are products that you promote in the cart, based on the current product.', 'ignition' ),
				'input_attrs' => array(
					'min'  => 2,
					'max'  => 4,
					'step' => 1,
				),
			),
		),
	) ) );

	//
	// WooCommerce - Single Product
	//
	$section = 'woocommerce_single_product';
	/** This filter is documented in inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'woocommerce_product_images_layout' => array(
			'setting_args' => array(
				'default'           => $defaults['woocommerce_product_images_layout'],
				'sanitize_callback' => 'ignition_sanitize_woocommerce_product_images_layout',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'select',
				'label'   => __( 'Product images layout', 'ignition' ),
				'choices' => ignition_get_woocommerce_product_images_layouts(),
			),
		),
	) ) );

	/**
	 * Filters all Ignition customizer options.
	 *
	 * @see ignition_customizer_{$section}_options
	 *
	 * @param array $options
	 *
	 * @since 1.0.0
	 *
	 * @hooked ignition_side_mode_customizer_options - 10
	 */
	$options = apply_filters( 'ignition_customizer_options', $options );

	if ( 'all' === $option ) {
		return $options;
	}

	if ( ! empty( $option ) && array_key_exists( $option, $options ) ) {
		/**
		 * Filters a specific Ignition customizer option.
		 *
		 * @param array $option
		 * @param string $option_name
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'ignition_customizer_option', $options[ $option ], $option );
	}

	/** This filter is documented in inc/customizer/options.php */
	return apply_filters( 'ignition_customizer_option', false, $option );
}
