<?php
/**
 * WooCommerce related hooks and functions
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

require_once untrailingslashit( __DIR__ ) . '/template-path-hooks.php';
require_once untrailingslashit( __DIR__ ) . '/wc-login-popup.php';
require_once untrailingslashit( __DIR__ ) . '/wc-search.php';


/**
 * Disable the default WooCommerce stylesheet.
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

add_action( 'after_setup_theme', 'ignition_woocommerce_activation' );
/**
 * Enable WooCommerce product gallery and set default settings
 *
 * @since 1.0.0
 */
function ignition_woocommerce_activation() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width'         => 750,
		'single_image_width'            => 560,
		'gallery_thumbnail_image_width' => 150,
		'product_grid'                  => array(
			'default_columns' => 3,
			'min_columns'     => 1,
			'max_columns'     => 4,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'wc-product-gallery-lightbox' );
}

// Add Page Title Image Option for post type.
add_filter( 'ignition_single_page_title_image_post_types', 'ignition_woocommerce_add_cpt_to_array' );

/**
 * Helper function that merges the post type name into a list of post types.
 *
 * Used to easily add the post type in a list of post types via filters.
 *
 * @since 1.0.0
 *
 * @param string[] $post_types
 *
 * @return array
 */
function ignition_woocommerce_add_cpt_to_array( $post_types ) {
	return array_merge( $post_types, array( 'product' ) );
}

// Add Page Title Image Option for taxonomy.
add_filter( 'ignition_page_title_image_taxonomies', 'ignition_woocommerce_add_taxonomies_to_array' );

/**
 * Helper function that merges the product taxonomy names into a list of taxonomies.
 *
 * Used to easily add the product taxonomies in a list of taxonomies via filters.
 *
 * @since 1.0.0
 *
 * @param string[] $taxonomies
 *
 * @return array
 */
function ignition_woocommerce_add_taxonomies_to_array( $taxonomies ) {
	return array_merge( $taxonomies, array( 'product_cat', 'product_tag' ) );
}

add_action( 'wp', 'ignition_woocommerce_page_title' );
/**
 * Removes the tile from the page title section, in product pages.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_page_title() {
	if ( ! is_product() ) {
		return;
	}

	remove_action( 'ignition_the_normal_page_title_elements', 'ignition_the_normal_page_title_subtitle', 20 );
	remove_action( 'ignition_the_page_title_with_background_elements', 'ignition_the_page_title_with_background_subtitle', 20 );
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

add_action( 'woocommerce_before_main_content', 'ignition_woocommerce_shop_actions', 40 );
/**
 * Generates the shop actions section.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_shop_actions() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}

	$actions_class = '';
	if ( ! is_active_sidebar( 'shop' ) ) {
		$actions_class = 'shop-actions-no-filter';
	} elseif ( ! in_array( ignition_get_current_page_layout(), array( 'fullwidth', 'fullwidth_boxed', 'fullwidth_narrow' ), true ) ) {
		$actions_class = 'with-sidebar';
	}

	?>
	<div class="row <?php ignition_the_main_width_row_classes(); ?>">
		<div class="<?php ignition_the_main_width_classes(); ?>">
			<div class="shop-actions <?php echo esc_attr( $actions_class ); ?>">
				<?php
				/**
				 * Hook: ignition_woocommerce_shop_actions.
				 *
				 * @hooked ignition_woocommerce_filters_trigger - 10 // Added by Ignition.
				 * @hooked woocommerce_result_count - 20 // Added by Ignition.
				 * @hooked woocommerce_catalog_ordering - 30 // Added by Ignition.
				 */
				do_action( 'ignition_woocommerce_shop_actions' );
				?>
			</div>
		</div>
	</div>
	<?php
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'ignition_woocommerce_shop_actions', 'ignition_woocommerce_filters_trigger', 10 );
add_action( 'ignition_woocommerce_shop_actions', 'woocommerce_result_count', 20 );
add_action( 'ignition_woocommerce_shop_actions', 'woocommerce_catalog_ordering', 30 );

/**
 * Generates the shop's Filters button.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_filters_trigger() {
	if ( ! ignition_has_sidebar() ) {
		return;
	}

	?>
	<a href="#"	class="shop-filter-toggle">
		<span class="ignition-icons ignition-icons-bars"></span> <?php esc_html_e( 'Filters', 'ignition' ); ?>
	</a>
	<?php
}

add_action( 'woocommerce_before_shop_loop', 'ignition_woocommerce_filters_drawer', 7 );
/**
 * Generates the shop's Filters Drawer.
 */
function ignition_woocommerce_filters_drawer() {
	if ( ! ignition_has_sidebar() || ! in_array( ignition_get_current_page_layout(), array( 'fullwidth', 'fullwidth_boxed', 'fullwidth_narrow' ), true ) ) {
		return;
	}

	?>
	<div class="sidebar sidebar-drawer">
		<div class="sidebar-drawer-header">
			<a href="#" class="sidebar-dismiss">&times; <span class="screen-reader-text"><?php esc_html_e( 'Close drawer', 'ignition' ); ?></span></a>
		</div>

		<div class="sidebar-drawer-content custom-scrollbar">
			<?php if ( ! function_exists( 'ignition_gsection_do_location' ) || ! ignition_gsection_do_location( 'sidebar' ) ) {
				/** This action is documented in template-files/sidebar.php */
				do_action( 'ignition_sidebar_before' );

				dynamic_sidebar( 'shop' );

				/** This action is documented in template-files/sidebar.php */
				do_action( 'ignition_sidebar_after' );
			} ?>
		</div>
	</div>
	<?php
}

/**
 * Get the product thumbnail for the loop.
 *
 * @since 1.0.0
 */
function woocommerce_template_loop_product_thumbnail() {
	?>
	<div class="entry-item-thumb">
	<?php

	echo woocommerce_get_product_thumbnail(); // phpcs:ignore WordPress.Security.EscapeOutput

	if ( get_theme_mod( 'woocommerce_alt_hover_image_is_enabled', ignition_customizer_defaults( 'woocommerce_alt_hover_image_is_enabled' ) ) ) {
		/**
		 * The global product object.
		 *
		 * @var $product WC_Product
		 */
		global $product;

		// Get the product attachments so that we can bring the second attached image.
		$attachment_ids = $product->get_gallery_image_ids();

		foreach ( $attachment_ids as $attachment ) {
			// Make sure we don't get the featured image again in case someone also assigns it in the product gallery.
			if ( get_post_thumbnail_id( get_the_ID() ) !== $attachment ) {
				echo wp_get_attachment_image( $attachment, 'woocommerce_thumbnail', false );
				break;
			}
		}
	}

	?>
	</div>
	<?php
}

add_action( 'woocommerce_single_product_summary', 'ignition_woocommerce_single_product_summary_wrap_open', 0 );
/**
 * Open single product's summary wrapper.
 *
 * @since 1.2.0
 */
function ignition_woocommerce_single_product_summary_wrap_open() {
	?><div class="ignition-product-summary-wrap"><?php
}


add_action( 'woocommerce_single_product_summary', 'ignition_woocommerce_single_product_summary_wrap_close', 100 );
/**
 * Close single product's summary wrapper.
 *
 * @since 1.2.0
 */
function ignition_woocommerce_single_product_summary_wrap_close() {
	?></div><!-- /.ignition-product-summary-wrap --><?php
}


add_filter( 'woocommerce_post_class', 'ignition_woocommerce_post_class', 10, 2 );
/**
 * Adds product classes.
 *
 * @since 1.2.0
 *
 * @param string[]   $classes
 * @param WC_Product $product
 *
 * @return array
 */
function ignition_woocommerce_post_class( $classes, $product ) {
	$images_count   = 0;
	$attachment_ids = $product->get_gallery_image_ids();

	// This check also exists in woocommerce/templates/single-product/product-thumbnails.php and determines
	// if the gallery will be show. We need to have the same check for consistent results.
	if ( $attachment_ids && $product->get_image_id() ) {
		$images_count = count( $attachment_ids );
	}

	$classes[] = "ignition-product-gallery-image-count-{$images_count}";

	return $classes;
}

add_filter( 'woocommerce_sale_flash', 'ignition_woocommerce_sale_flash_percentage', 10, 3 );
/**
 * Replaces the default "Sale!" badge text with the percentage of discount.
 * Returns the HTML code that contains the default "Sale!" badge text, replaced with the percentage of discount.
 *
 * @since 1.2.0
 *
 * @param string     $html
 * @param WP_Post    $post
 * @param WC_Product $product
 *
 * @return string
 */
function ignition_woocommerce_sale_flash_percentage( $html, $post, $product ) {
	if ( ! get_theme_mod( 'woocommerce_sale_flash_percentage_is_enabled', ignition_customizer_defaults( 'woocommerce_sale_flash_percentage_is_enabled' ) ) ) {
		return $html;
	}

	$found = preg_match( '#(<span.*?>)(.*?)(</span>)#', $html, $matches );

	if ( ! $found ) {
		return $html;
	}

	$tag_open      = $matches[1];
	$tag_close     = $matches[3];
	$original_text = $matches[2];

	$percentages = ignition_woocommerce_get_product_sale_percentages( $product );
	$label       = ignition_woocommerce_get_product_sale_percentage_label( $percentages, $original_text );

	$html = $tag_open . $label . $tag_close;

	return $html;
}

add_filter( 'woocommerce_blocks_product_grid_item_html', 'ignition_woocommerce_blocks_sale_flash_percentage', 10, 3 );
/**
 * Replaces the default "Sale!" badge text of the Product block, with the percentage of discount.
 * Returns an individual product's HTML code that contains the default "Sale!" badge text, replaced with the percentage of discount.
 *
 * @since 1.2.0
 *
 * @param string     $block_html
 * @param object     $data
 * @param WC_Product $product
 *
 * @return string
 */
function ignition_woocommerce_blocks_sale_flash_percentage( $block_html, $data, $product ) {
	if ( ! get_theme_mod( 'woocommerce_sale_flash_percentage_is_enabled', ignition_customizer_defaults( 'woocommerce_sale_flash_percentage_is_enabled' ) ) ) {
		return $block_html;
	}

	$old_badge_html = $data->badge;

	$pattern = '#(<div\ class="wc-block-grid__product-onsale">)(\s*?)(<span.*?>)(.*?)(</span>)(\s*?)(<span.*?>)(.*?)(</span>)(\s*?)(</div>)#';

	$found = preg_match( $pattern, $old_badge_html, $matches );

	if ( ! $found ) {
		return $block_html;
	}

	$original_text = $matches[4];

	$percentages = ignition_woocommerce_get_product_sale_percentages( $product );

	$label = ignition_woocommerce_get_product_sale_percentage_label( $percentages, $original_text );

	// Instead of verbosely concatenating everything, just implode everything together.
	$new_badge_html = $matches;
	unset( $new_badge_html[0] ); // We don't need the full matched string.
	$new_badge_html[4] = $label; // Replace the actual label.
	$new_badge_html    = implode( '', $new_badge_html );

	$block_html = str_replace( $old_badge_html, $new_badge_html, $block_html );

	return $block_html;
}

/**
 * Returns a product's discount as a set of minimum and maximum percentages.
 *
 * Compound products, such as grouped and variable products, may have multiple different discount percentages due to
 * on-sale children products or variations. In these cases, both 'min' and 'max' values are set, although may be the same.
 *
 * For simple products, run the returned array through max() to get the correct value.
 *
 * @since 1.2.0
 *
 * @param WC_Product $product
 *
 * @return array {
 *     @type float|false $min
 *     @type float|false $max
 * }
 */
function ignition_woocommerce_get_product_sale_percentages( $product ) {
	$percentages = array(
		'min' => false,
		'max' => false,
	);

	switch ( $product->get_type() ) {
		case 'grouped':
			$children = array_filter( array_map( 'wc_get_product', $product->get_children() ), 'wc_products_array_filter_visible_grouped' );

			foreach ( $children as $child ) {
				if ( $child->is_purchasable() && ! $child->is_type( 'grouped' ) && $child->is_on_sale() ) {
					$child_percentage = ignition_woocommerce_get_product_sale_percentages( $child );

					$percentages['min'] = false !== $percentages['min'] ? $percentages['min'] : $child_percentage['min'];
					$percentages['max'] = false !== $percentages['max'] ? $percentages['max'] : $child_percentage['max'];

					if ( $child_percentage['min'] < $percentages['min'] ) {
						$percentages['min'] = $child_percentage['min'];
					}

					if ( $child_percentage['max'] > $percentages['max'] ) {
						$percentages['max'] = $child_percentage['max'];
					}
				}
			}
			break;

		case 'variable':
			$prices = $product->get_variation_prices();

			foreach ( $prices['price'] as $variation_id => $price ) {
				$regular_price = (float) $prices['regular_price'][ $variation_id ];
				$sale_price    = (float) $prices['sale_price'][ $variation_id ];

				if ( $sale_price < $regular_price ) {
					$variation_percentage = ( ( $regular_price - $sale_price ) / $regular_price ) * 100;

					$percentages['min'] = false !== $percentages['min'] ? $percentages['min'] : $variation_percentage;
					$percentages['max'] = false !== $percentages['max'] ? $percentages['max'] : $variation_percentage;

					if ( $variation_percentage < $percentages['min'] ) {
						$percentages['min'] = $variation_percentage;
					}

					if ( $variation_percentage > $percentages['max'] ) {
						$percentages['max'] = $variation_percentage;
					}
				}
			}
			break;

		case 'external':
		case 'variation':
		case 'simple':
		default:
			$regular_price = (float) $product->get_regular_price();
			$sale_price    = (float) $product->get_sale_price();

			if ( $sale_price < $regular_price ) {
				$percentages['max'] = ( ( $regular_price - $sale_price ) / $regular_price ) * 100;
			}
	}

	return $percentages;
}

/**
 * Returns the percentage text to be displayed on the sale badge.
 *
 * @since 1.2.0
 *
 * @param array $percentages
 *
 * @return string
 */
function ignition_woocommerce_get_product_sale_percentage_label( $percentages, $original_label ) {
	$label = '';

	$rounded_percentages = $percentages;
	$rounded_percentages = array_map( 'round', $percentages );
	$rounded_percentages = array_map( 'intval', $rounded_percentages );

	if ( ( empty( $percentages['min'] ) || empty( $percentages['max'] ) ) || ( $percentages['min'] === $percentages['max'] ) ) {
		/* translators: %1$d is a discount percentage. E.g. -60% */
		$label = sprintf( _x( '-%1$d%%', 'product discount', 'ignition' ), max( $rounded_percentages ) );
	} else {
		/* translators: The whole string is a discount range. %1$d is the minimum discount percentage, %2$d is the maximum discount percentage. E.g. -10% / -60% */
		$label = sprintf( _x( '-%1$d%% / -%2$d%%', 'product discount', 'ignition' ), $rounded_percentages['min'], $rounded_percentages['max'] );
	}

	/**
	 * Filters the sale flash's percentage-based label.
	 *
	 * @since 1.2.0
	 *
	 * @param string $label The sale flash's label text.
	 * @param array $rounded_percentages {
	 *     Array of rounded sale percentages' extremes.
	 *
	 *     @type int $min
	 *     @type int $max
	 * }
	 * @param array $percentages {
	 *     Array of sale percentages' extremes.
	 *
	 *     @type float $min
	 *     @type float $max
	 * }
	 * @param string $original_label The sale flash's original label text.
	 */
	$label = apply_filters( 'ignition_woocommerce_sale_flash_percentage_label', $label, $rounded_percentages, $percentages, $original_label );

	return $label;
}

add_action( 'ignition_before_main', 'ignition_woocommerce_checkout_steps', 30 );
/**
 * Generates the checkout steps' markup.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_checkout_steps() {
	if ( ! is_cart() && ! is_checkout() && ! is_wc_endpoint_url( 'order-pay' ) && ! is_wc_endpoint_url( 'order-received' ) ) {
		return;
	}

	$classes_todo   = 'woocommerce-checkout-step';
	$classes_done   = 'woocommerce-checkout-step woocommerce-checkout-step-done';
	$classes_active = 'woocommerce-checkout-step woocommerce-checkout-step-active';

	$classes_cart         = $classes_todo;
	$classes_checkout     = $classes_todo;
	$classes_confirmation = $classes_todo;

	if ( is_cart() ) {
		$classes_cart = $classes_active;
	} elseif ( is_wc_endpoint_url( 'order-received' ) ) {
		$classes_cart         = $classes_done;
		$classes_checkout     = $classes_done;
		$classes_confirmation = $classes_active;
	} elseif ( is_checkout() || is_wc_endpoint_url( 'order-pay' ) ) {
		// is_checkout() is true in the above is_wc_endpoint_url() checks, so it needs to be checked last.
		$classes_cart     = $classes_done;
		$classes_checkout = $classes_active;
	}
	?>
	<div class="woocommerce-checkout-progress-wrap">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="woocommerce-checkout-progress">
						<div class="<?php echo esc_attr( $classes_cart ); ?>">
							<span class="woocommerce-checkout-step-number">1</span>
							<span class="woocommerce-checkout-step-label"><?php echo esc_html_x( 'Your Cart', 'checkout step', 'ignition' ); ?></span>
						</div>

						<div class="<?php echo esc_attr( $classes_checkout ); ?>">
							<span class="woocommerce-checkout-step-number">2</span>
							<span class="woocommerce-checkout-step-label"><?php echo esc_html_x( 'Checkout', 'checkout step', 'ignition' ); ?></span>
						</div>

						<div class="<?php echo esc_attr( $classes_confirmation ); ?>">
							<span class="woocommerce-checkout-step-number">3</span>
							<span class="woocommerce-checkout-step-label"><?php echo esc_html_x( 'Confirmation', 'checkout step', 'ignition' ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'woocommerce_before_cart', 'ignition_woocommerce_before_cart_wrap_open', 5 );
/**
 * Open cart wrapper.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_before_cart_wrap_open() {
	?><div class="ignition-cart-content-wrap"><?php
}

add_action( 'woocommerce_before_cart_collaterals', 'ignition_woocommerce_before_cart_collaterals_wrap_open', 50 );
/**
 * Open cart collaterals wrapper.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_before_cart_collaterals_wrap_open() {
	?><div class="ignition-cart-collaterals-wrap"><?php
}

add_action( 'woocommerce_after_cart', 'ignition_woocommerce_before_cart_collaterals_wrap_close', 50 );
/**
 * Close cart wrapper.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_before_cart_collaterals_wrap_close() {
	?></div><!-- .ignition-cart-collaterals-wrap --><?php
}

add_action( 'woocommerce_after_cart', 'ignition_woocommerce_before_cart_wrap_close', 60 );
/**
 * Close cart collaterals wrapper.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_before_cart_wrap_close() {
	?></div><!-- .ignition-cart-content-wrap --><?php
}

// Move cross sells after the cart.
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 100 );

add_action( 'woocommerce_checkout_before_order_review_heading', 'ignition_woocommerce_checkout_order_review_wrap_open', 5 );
/**
 * Open order review wrappers.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_checkout_order_review_wrap_open() {
	?><div class="ignition-checkout-order-wrap"><div class="ignition-checkout-order"><?php
}

add_action( 'woocommerce_checkout_after_order_review', 'ignition_woocommerce_checkout_order_review_wrap_close', 50 );
/**
 * Close order review wrappers.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_checkout_order_review_wrap_close() {
	?></div></div><!-- .ignition-checkout-order-wrap .ignition-checkout-order --><?php
}

// Remove customer details that appear after the order details.
remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
// Since we removed the custom details from the order details table, we need to append it everywhere the table is used.
add_action( 'woocommerce_thankyou', 'ignition_woocommerce_thankyou_show_customer_details', 10 );
add_action( 'woocommerce_view_order', 'ignition_woocommerce_thankyou_show_customer_details', 10 );

/**
 * Shows the order's customer details template.
 *
 * @since 1.4.0
 *
 * @param int $order_id
 */
function ignition_woocommerce_thankyou_show_customer_details( $order_id ) {
	$order = wc_get_order( $order_id );

	if ( $order ) {
		$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
		if ( $show_customer_details ) {
			wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
		}
	}
}

/**
 * Shows the order's customer details template.
 *
 * @since 1.0.0
 *
 * @deprecated 1.4.0 Use ignition_woocommerce_thankyou_show_customer_details()
 *
 * @param int $order_id
 */
function ignite_woocommerce_thankyou_show_customer_details( $order_id ) {
	_deprecated_function( __FUNCTION__, '1.4.0', 'ignition_woocommerce_thankyou_show_customer_details' );

	ignition_woocommerce_thankyou_show_customer_details( $order_id );
}

/**
 * Shows the order's downloads table.
 *
 * @since 1.4.0
 *
 * @param int $order_id
 */
function ignition_woocommerce_show_order_downloads( $order_id ) {
	$order          = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$downloads      = $order->get_downloadable_items();
	$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();

	if ( $show_downloads ) {
		wc_get_template(
			'order/order-downloads.php',
			array(
				'downloads'  => $downloads,
				'show_title' => true,
			)
		);
	}
}

add_action( 'woocommerce_before_add_to_cart_quantity', 'ignition_display_quantity_minus' );
/**
 * Add minus button before the quantity input.
 *
 * @since 1.0.0
 */
function ignition_display_quantity_minus() {
	echo '<div class="quantity-wrap"><button type="button" class="qty-btn qty-minus">-</button>';
}

add_action( 'woocommerce_after_add_to_cart_quantity', 'ignition_display_quantity_plus' );
/**
 * Add plus button after the quantity input.
 *
 * @since 1.0.0
 */
function ignition_display_quantity_plus() {
	echo '<button type="button" class="qty-btn qty-plus">+</button></div>';
}


add_filter( 'woocommerce_cart_item_thumbnail', 'ignition_woocommerce_cart_item_thumbnail', 10, 3 );
/**
 * Filters the cart's item thumbnails, adding a remove button and wrapping them into a wrapper.
 *
 * @since 1.0.0
 *
 * @param string $image_html
 * @param array  $cart_item
 * @param string $cart_item_key
 *
 * @return string
 */
function ignition_woocommerce_cart_item_thumbnail( $image_html, $cart_item, $cart_item_key ) {
	$_product   = $cart_item['data'];
	$product_id = $cart_item['product_id'];
	$image_html = $_product->get_image( 'ignition_minicart_item' );

	$image_html .= apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'woocommerce_cart_item_remove_link',
		sprintf(
			'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
			esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
			esc_html__( 'Remove this item', 'woocommerce' ),
			esc_attr( $product_id ),
			esc_attr( $cart_item_key ),
			esc_attr( $_product->get_sku() )
		),
		$cart_item_key
	);

	$image_html = sprintf( '<div class="widget-product-thumbnail-image">%s</div>', $image_html );

	return $image_html;
}

add_action( 'woocommerce_after_cart_item_name', 'ignition_woocommerce_after_cart_item_name_quantity', 10, 2 );
/**
 * Outputs the quantity and price, similarly to the mini-cart.
 *
 * @since 1.0.0
 *
 * @param $cart_item
 * @param $cart_item_key
 */
function ignition_woocommerce_after_cart_item_name_quantity( $cart_item, $cart_item_key ) {
	$_product      = $cart_item['data'];
	$product_id    = $cart_item['product_id'];
	$product_price = WC()->cart->get_product_price( $_product );

	echo wp_kses( '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', ignition_get_allowed_tags() );

}

add_filter( 'woocommerce_cart_item_quantity', 'ignition_woocommerce_cart_item_quantity_buttons', 10, 3 );
/**
 * Filters the quantity inputs of the cart, adding plus/minus buttons.
 *
 * @since 1.0.0
 *
 * @param $product_quantity
 * @param $cart_item_key
 * @param $cart_item
 *
 * @return string
 */
function ignition_woocommerce_cart_item_quantity_buttons( $product_quantity, $cart_item_key, $cart_item ) {
	if ( trim( $product_quantity ) ) {
		ob_start();
		ignition_display_quantity_minus();
		echo $product_quantity;
		ignition_display_quantity_plus();
		$product_quantity = ob_get_clean();
	}

	return $product_quantity;
}

add_action( 'woocommerce_cart_actions', 'ignition_woocommerce_cart_actions_continue_shopping' );
/**
 * Adds a "Continue shopping" button next to the "Update cart" button, in the cart page.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_cart_actions_continue_shopping() {
	$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
	if ( $shop_url ) {
		printf( '<a href="%s" class="continue-shopping">%s</a>',
			esc_url( $shop_url ),
			esc_html__( 'Continue Shopping', 'ignition' )
		);
	}
}

add_filter( 'woocommerce_add_to_cart_fragments', 'ignition_woocommerce_minicart_button_fragment' );
/**
 * Cart Fragments.
 *
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @since 1.0.0
 *
 * @param array $fragments Fragments to refresh via AJAX.
 *
 * @return array Fragments to refresh via AJAX.
 */
function ignition_woocommerce_minicart_button_fragment( $fragments ) {
	if ( function_exists( 'ignition_shortcode_minicart_button_trigger_text' ) ) {
		ob_start();
		ignition_shortcode_minicart_button_trigger_text();
		$fragments['span.header-mini-cart-trigger-text'] = ob_get_clean();
	}

	return $fragments;
}

add_filter( 'woocommerce_output_related_products_args', 'ignition_woocommerce_output_related_products_args' );
/**
 * Filters the related products' arguments.
 *
 * @since 1.0.0
 *
 * @param array $args
 *
 * @return array
 */
function ignition_woocommerce_output_related_products_args( $args ) {
	$columns = get_theme_mod( 'woocommerce_product_related_columns', ignition_customizer_defaults( 'woocommerce_product_related_columns' ) );

	$args['posts_per_page'] = $columns;
	$args['columns']        = $columns;

	return $args;
}

add_filter( 'woocommerce_upsells_total', 'ignition_woocommerce_upsells_total' );
add_filter( 'woocommerce_upsells_columns', 'ignition_woocommerce_upsells_total' );
/**
 * Filters upsells' count and columns.
 *
 * @since 1.0.0
 *
 * @return int
 */
function ignition_woocommerce_upsells_total() {
	$columns = get_theme_mod( 'woocommerce_product_upsell_columns', ignition_customizer_defaults( 'woocommerce_product_upsell_columns' ) );
	return (int) $columns;
}

add_filter( 'woocommerce_cross_sells_total', 'ignition_woocommerce_cross_sells_total' );
add_filter( 'woocommerce_cross_sells_columns', 'ignition_woocommerce_cross_sells_total' );
/**
 * Filters cross-sells' count and columns.
 *
 * @since 1.0.0
 *
 * @param int $limit
 *
 * @return int
 */
function ignition_woocommerce_cross_sells_total( $limit ) {
	$columns = get_theme_mod( 'woocommerce_cart_cross_sell_columns', ignition_customizer_defaults( 'woocommerce_cart_cross_sell_columns' ) );
	return (int) $columns;
}

add_action( 'wp', 'ignition_woocommerce_setup_product_images_layout' );
/**
 * Sets up the alternative single product images layouts.
 *
 * @since 1.2.0
 */
function ignition_woocommerce_setup_product_images_layout() {
	if ( ! is_product() ) {
		return;
	}

	if ( 'list' === get_theme_mod( 'woocommerce_product_images_layout', ignition_customizer_defaults( 'woocommerce_product_images_layout' ) ) ) {
		remove_theme_support( 'wc-product-gallery-zoom' );
		remove_theme_support( 'wc-product-gallery-slider' );
		add_filter( 'woocommerce_gallery_image_size', 'ignition_woocommerce_product_images_layout_list_image_size' );
	}
}

/**
 * Returns the thumbnails' image size to be used by the 'list' woocommerce_product_images_layout layout.
 *
 * @since 1.2.0
 */
function ignition_woocommerce_product_images_layout_list_image_size() {
	return 'woocommerce_single';
}

add_filter( 'body_class', 'ignition_woocommerce_body_class', 10, 2 );
/**
 * Adds WooCommerce-related classes on the body tag.
 *
 * @since 1.2.0
 *
 * @param string[] $classes An array of body class names.
 * @param string[] $class   An array of additional class names added to the body.
 *
 * @return array
 */
function ignition_woocommerce_body_class( $classes, $class ) {
	$defaults = ignition_customizer_defaults( 'all' );

	$value     = get_theme_mod( 'woocommerce_shop_layout', $defaults['woocommerce_shop_layout'] );
	$classes[] = "ignition-shop-layout-{$value}";

	if ( is_product() ) {
		$value = get_theme_mod( 'woocommerce_product_images_layout', $defaults['woocommerce_product_images_layout'] );
		if ( $value ) {
			$classes[] = "ignition-products-images-layout-{$value}";
		}
	}

	return $classes;
}

// Move share icons before the product meta.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 30 );
