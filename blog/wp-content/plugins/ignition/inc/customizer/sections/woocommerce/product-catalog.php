<?php
/**
 * Customizer section options: WooCommerce
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['woocommerce_shop_layout'];
$wp_customize->add_setting( 'woocommerce_shop_layout', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_shop_layout', $args['control_args'] );

$args = $customizer_options['woocommerce_shop_mobile_columns'];
$wp_customize->add_setting( 'woocommerce_shop_mobile_columns', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_shop_mobile_columns', $args['control_args'] );

$args = $customizer_options['woocommerce_alt_hover_image_is_enabled'];
$wp_customize->add_setting( 'woocommerce_alt_hover_image_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_alt_hover_image_is_enabled', $args['control_args'] );

$args = $customizer_options['woocommerce_sale_flash_percentage_is_enabled'];
$wp_customize->add_setting( 'woocommerce_sale_flash_percentage_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_sale_flash_percentage_is_enabled', $args['control_args'] );

$args = $customizer_options['woocommerce_force_show_title_subtitle_is_enabled'];
$wp_customize->add_setting( 'woocommerce_force_show_title_subtitle_is_enabled', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_force_show_title_subtitle_is_enabled', $args['control_args'] );

$args = $customizer_options['woocommerce_product_upsell_columns'];
$wp_customize->add_setting( 'woocommerce_product_upsell_columns', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_product_upsell_columns', $args['control_args'] );

$args = $customizer_options['woocommerce_product_related_columns'];
$wp_customize->add_setting( 'woocommerce_product_related_columns', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_product_related_columns', $args['control_args'] );

$args = $customizer_options['woocommerce_cart_cross_sell_columns'];
$wp_customize->add_setting( 'woocommerce_cart_cross_sell_columns', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_cart_cross_sell_columns', $args['control_args'] );

