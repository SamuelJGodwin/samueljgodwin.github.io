<?php
/**
 * Customizer options' and controls' parameters, and Ignition overrides
 *
 * @since 1.0.0
 */

add_filter( 'ignition_customizer_options', 'ignition_public_opinion_filter_ignition_customizer_options' );
/**
 * Modifies the customizer's options parameters.
 *
 * @since 1.0.0
 *
 * @param array $options
 *
 * @return array
 */
function ignition_public_opinion_filter_ignition_customizer_options( $options ) {
	$defaults = ignition_customizer_defaults( 'all' );

	$options['header_layout_is_full_width']['disabled'] = true;

	//
	// Featured Articles
	//
	$section = 'theme_featured_articles';
	/** This filter is documented in ignition/inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'theme_featured_articles_tag' => array(
			'setting_args' => array(
				'default'           => $defaults['theme_featured_articles_tag'],
				'sanitize_callback' => 'sanitize_title',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => esc_html__( 'Tag slug', 'ignition-public-opinion' ),
				'description' => esc_html__( 'Featured sections throughout your website will only show articles that include this tag. The selected tag will be automatically hidden from tag lists.', 'ignition-public-opinion' ),
			),
		),
	) ) );

	//
	// Utilities - News Ticker
	//
	$section = 'theme_news_ticker';
	/** This filter is documented in ignition/inc/customizer/options.php */
	$options = array_merge( $options, apply_filters( "ignition_customizer_{$section}_options", array(
		'theme_news_ticker_is_enabled' => array(
			'setting_args' => array(
				'default'           => $defaults['theme_news_ticker_is_enabled'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section' => $section,
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Enable the news ticker', 'ignition-public-opinion' ),
			),
		),
		'theme_news_ticker_title'      => array(
			'setting_args' => array(
				'default'           => $defaults['theme_news_ticker_title'],
				'sanitize_callback' => 'sanitize_text_field',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => esc_html__( 'Title', 'ignition-public-opinion' ),
				'description' => __( "The ticker's title appears before all news items.", 'ignition-public-opinion' ),
			),
		),
		'theme_news_ticker_term'       => array(
			'setting_args' => array(
				'default'           => $defaults['theme_news_ticker_term'],
				'sanitize_callback' => 'sanitize_title',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => esc_html__( 'Source Category', 'ignition-public-opinion' ),
				'description' => __( 'The category from which to display news items.', 'ignition-public-opinion' ),
			),
		),
		'theme_news_ticker_limit'      => array(
			'setting_args' => array(
				'default'           => $defaults['theme_news_ticker_limit'],
				'sanitize_callback' => 'absint',
			),
			'control_args' => array(
				'section'     => $section,
				'type'        => 'text',
				'label'       => esc_html__( 'Posts limit', 'ignition-public-opinion' ),
				'description' => __( 'The maximum number of news items to display.', 'ignition-public-opinion' ),
			),
		),
	) ) );

	//
	// Secondary Typography
	//
	$options['site_typo_secondary']['render_args']['breakpoints_css'] = '
		h1,h2,h3,h4,h5,h6,
		label,
		.label,
		.ignition-widget-item-title,
		.ignition-widget-item-subtitle,
		.site-logo,
		.mobile-nav-trigger,
		.page-hero-title,
		.page-title,
		.entry-meta-top,
		.entry-navigation,
		.news-ticker,
		.widget_archive li,
		.widget_categories li,
		.widget_meta li,
		.widget_nav_menu li,
		.widget_pages li,
		.widget_product_categories li,
		.widget_rating_filter li,
		.woocommerce-widget-layered-nav li,
		li.wc-block-grid__product .wc-block-grid__product-title,
		.has-drop-cap:not(:focus)::first-letter,
		.product_list_widget li > a,
		.product_list_widget .widget-product-content-wrap > a,
		.entry-list-meta-value,
		.wp-block-gutenbee-review .entry-rating-final-score strong,
		.wp-block-latest-posts > li > a,
		.wp-block-pullquote.is-style-solid-color,
		.wp-block-quote {
			%s
		}
	';

	//
	// Header mobile breakpoint
	//
	$options['header_layout_menu_mobile_breakpoint']['render_args']['css'] = '
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

			.head-content-slot-item {
				flex: auto;
			}

			.head-intro-inner {
				display: flex;
				flex-direction: column;
			}

			.head-intro-inner .head-content-slot {
				justify-content: center;
				text-align: center;
				flex-wrap: wrap;
			}

			.head-intro-inner .head-content-slot-item {
				padding: 5px 10px;
				display: flex;
				justify-content: center;
				flex: none;
				border: 0 !important;
			}

			.site-branding {
				max-width: 100%;
				width: 100%;
				margin-bottom: 15px;
			}

			.head-mast-inner {
				flex-direction: column;
				margin: 0;
				padding: 15px 0;
			}

			.head-mast-inner::after {
				width: 100%;
				left: 0;
			}

			.head-mast-inner .head-content-slot-item {
				margin: 0;
			}
		}
	';

	return $options;
}
