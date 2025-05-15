<?php
/**
 * Customizer section options: Utilities - News Ticker
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'theme_news_ticker', array(
	'title' => esc_html_x( 'News Ticker', 'customizer section title', 'ignition-public-opinion' ),
	'panel' => 'utilities',
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['theme_news_ticker_is_enabled'];
$wp_customize->add_setting( 'theme_news_ticker_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'theme_news_ticker_is_enabled', $args['control_args'] );

$args = $customizer_options['theme_news_ticker_title'];
$wp_customize->add_setting( 'theme_news_ticker_title', $args['setting_args'] );
$wp_customize->add_control( 'theme_news_ticker_title', $args['control_args'] );

$args = $customizer_options['theme_news_ticker_term'];
$wp_customize->add_setting( 'theme_news_ticker_term', $args['setting_args'] );
$wp_customize->add_control( new Ignition_Customize_Category( $wp_customize, 'theme_news_ticker_term', $args['control_args'] ) );

$args = $customizer_options['theme_news_ticker_limit'];
$wp_customize->add_setting( 'theme_news_ticker_limit', $args['setting_args'] );
$wp_customize->add_control( 'theme_news_ticker_limit', $args['control_args'] );
