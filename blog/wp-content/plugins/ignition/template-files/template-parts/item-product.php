<?php
/**
 * Template part for displaying products in item format
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>

<div id="entry-item-<?php the_ID(); ?>" <?php post_class( 'entry-item entry-item-product' ); ?>>
	<?php
		woocommerce_template_loop_product_link_open();
		woocommerce_show_product_loop_sale_flash();
		woocommerce_template_loop_product_thumbnail();
		woocommerce_template_loop_product_link_close();
	?>

	<div class="entry-item-content">
	<?php
		woocommerce_template_loop_product_link_open();
		woocommerce_template_loop_product_title();
		woocommerce_template_loop_product_link_close();
		woocommerce_template_loop_price();
		woocommerce_template_loop_add_to_cart();
	?>
	</div>
</div>
