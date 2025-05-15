<?php
/**
 * Customizer section options: WooCommerce
 *
 * @since 1.2.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'woocommerce_single_product', array(
	'title' => esc_html_x( 'Single Product', 'customizer section title', 'ignition' ),
	'panel' => 'woocommerce',
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

$args = $customizer_options['woocommerce_product_images_layout'];
$wp_customize->add_setting( 'woocommerce_product_images_layout', $args['setting_args'] );
$wp_customize->add_control( 'woocommerce_product_images_layout', $args['control_args'] );
