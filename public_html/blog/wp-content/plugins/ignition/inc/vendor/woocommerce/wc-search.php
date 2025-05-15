<?php
/**
 * WooCommerce login link/popup shortcode's related hooks and functions
 *
 * @since 1.5.0
 */

/**
 * Builds the markup for the WooCommerce AJAX search shortcode.
 *
 * @since 1.5.0
 *
 * @param array $params {
 *     Array of default parameters.
 *
 *     @type int $categories Whether to show the categories dropdown. Accepts 1 or 0. Default 1.
 *     @type int $ajax       Whether to enable AJAX. Accepts 1 or 0. Default 1.
 *     @type int $image      Whether to show the images of products. Accepts 1 or 0. Default 1.
 *     @type int $excerpt    Whether to show the excerpts of products. Accepts 1 or 0. Default 1.
 *     @type int $price      Whether to show the prices of products. Accepts 1 or 0. Default 1.
 * }
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string The WooCommerce AJAX search shortcode output.
 */
function ignition_woocommerce_product_search_output( $params, $content, $shortcode ) {
	wp_enqueue_script( 'ignition-wc-search' );
	wp_enqueue_style( 'ignition-wc-search' );

	$params = shortcode_atts( array(
		'categories' => 1,
		'ajax'       => 1,
		'image'      => 1,
		'excerpt'    => 1,
		'price'      => 1,
	), $params, $shortcode );

	static $displayed = false;

	ob_start();

	?>
	<a href="#" class="ignition-wc-search-form-trigger">
		<span class="ignition-icons ignition-icons-search"></span> <span class="sr-only"><?php esc_html_e( 'Expand product search form', 'ignition' ); ?></span>
	</a>
	<?php

	if ( ! $displayed ) {

		/**
		 * Filters the AJAX products search's labels.
		 *
		 * @since 1.5.0
		 *
		 * @param array
		 */
		$labels = apply_filters( 'ignition_wc_search_labels', array(
			'label_category'    => __( 'Category name', 'ignition' ),
			'label_search'      => __( 'Search products:', 'ignition' ),
			'search_all'        => __( 'Search all categories', 'ignition' ),
			'input_placeholder' => __( 'What are you looking for?', 'ignition' ),
			'thumb_alt'         => __( 'Search result item thumbnail', 'ignition' ),
			'submit'            => _x( 'Search', 'submit button', 'ignition' ),
		) );

		/**
		 * Filters the image size's name to be returned for each result by the AJAX products search.
		 *
		 * @since 1.5.0
		 *
		 * @param string
		 */
		$image_size = apply_filters( 'ignition_wc_search_thumbnail_size', 'ignition_minicart_item' );

		$ajax_class   = '';
		$autocomplete = 'on';

		if ( (bool) $params['ajax'] ) {
			$ajax_class   = 'form-ajax-enabled';
			$autocomplete = 'off';
		}

		$show_thumb   = (bool) $params['image'];
		$show_excerpt = (bool) $params['excerpt'];
		$show_price   = (bool) $params['price'];
		?>
		<div class="ignition-wc-search-form-wrap">
			<form class="ignition-wc-search-form <?php echo esc_attr( $ajax_class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
				<?php if ( (bool) $params['categories'] ) : ?>
					<label for="ignition-wc-search-name" class="sr-only" >
						<?php echo esc_html( $labels['label_category'] ); ?>
					</label>

					<?php
						wp_dropdown_categories( array(
							'taxonomy'          => 'product_cat',
							'show_option_none'  => $labels['search_all'],
							'option_none_value' => '',
							'value_field'       => 'slug',
							'hide_empty'        => 1,
							'echo'              => 1,
							'hierarchical'      => 1,
							'name'              => 'product_cat',
							'id'                => 'ignition-wc-search-name',
							'class'             => 'ignition-wc-search-select',
						) );
					?>
				<?php endif; ?>

				<div class="ignition-wc-search-input-wrap">
					<label for="ignition-wc-search-input" class="sr-only">
						<?php echo esc_html( $labels['label_search'] ); ?>
					</label>
					<input
						type="text"
						class="ignition-wc-search-input"
						id="ignition-wc-search-input"
						placeholder="<?php echo esc_attr( $labels['input_placeholder'] ); ?>"
						name="s"
						autocomplete="<?php echo esc_attr( $autocomplete ); ?>"
					/>
					<span class="ignition-wc-search-spinner"></span>
					<input type="hidden" name="post_type" value="product" />
				</div>

				<button type="submit" class="ignition-wc-search-btn">
					<span class="ignition-icons ignition-icons-search"></span><span class="sr-only"><?php echo esc_html( $labels['submit'] ); ?></span>
				</button>

				<button type="button" class="ignition-wc-search-form-dismiss">&times;</button>
			</form>

			<ul class="ignition-wc-search-results">
				<li class="ignition-wc-search-results-item">
					<a href="">
						<?php if ( $show_thumb ) : ?>
							<div class="ignition-wc-search-results-item-thumb">
								<img src="<?php echo esc_url( wc_placeholder_img_src( $image_size ) ); ?>" alt="<?php echo esc_attr( $labels['thumb_alt'] ); ?>">
							</div>
						<?php endif; ?>

						<div class="ignition-wc-search-results-item-content">
							<p class="ignition-wc-search-results-item-title"></p>
							<?php if ( $show_price ) : ?>
								<p class="ignition-wc-search-results-item-price"></p>
							<?php endif; ?>
							<?php if ( $show_excerpt ) : ?>
								<p class="ignition-wc-search-results-item-excerpt"></p>
							<?php endif; ?>
						</div>
					</a>
				</li>
			</ul>
		</div>
		<?php

		$displayed = true;
	}

	return ob_get_clean();
}

add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'ignition_woocommerce_product_query_custom_query_var', 10, 2 );
/**
 * Handle a custom search 's' query var to search products.
 *
 * @since 1.5.0
 *
 * @param array $query      Args for WP_Query.
 * @param array $query_vars Query vars from WC_Product_Query.
 *
 * @return array Modified $query
 */
function ignition_woocommerce_product_query_custom_query_var( $query, $query_vars ) {
	if ( ! empty( $query_vars['s'] ) ) {
		$query['s'] = $query_vars['s'];
	}

	return $query;
}

add_action( 'wp_ajax_ignition_wc_search_products', 'ignition_woocommerce_ajax_products_search' );
add_action( 'wp_ajax_nopriv_ignition_wc_search_products', 'ignition_woocommerce_ajax_products_search' );
/**
 * Ajax handler that searches and returns products.
 *
 * @since 1.5.0
 */
function ignition_woocommerce_ajax_products_search() {
	$s   = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$cat = isset( $_REQUEST['product_cat'] ) ? sanitize_title_for_query( wp_unslash( $_REQUEST['product_cat'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification

	/**
	 * Filters the maximum number of results returned by the AJAX products search.
	 *
	 * @since 1.5.0
	 *
	 * @param int
	 */
	$max_results = apply_filters( 'ignition_wc_search_products_search_max_results', 5 );

	/**
	 * Filters the minimum amount of characters needed to trigger an AJAX products search.
	 *
	 * @since 1.5.0
	 *
	 * @param int
	 */
	$min_characters = apply_filters( 'ignition_wc_search_products_search_min_chars', 3 );

	/**
	 * Filters the excerpt size (in words) returned for each result by the AJAX products search.
	 *
	 * @since 1.5.0
	 *
	 * @param int
	 */
	$excerpt_length = apply_filters( 'ignition_wc_search_products_search_excerpt_length', 22 );

	/**
	 * Filters the image size's name to be returned for each result by the AJAX products search.
	 *
	 * @since 1.5.0
	 *
	 * @param string
	 */
	$image_size = apply_filters( 'ignition_wc_search_thumbnail_size', 'ignition_minicart_item' );

	if ( mb_strlen( $s ) < $min_characters ) {
		$response = array(
			'error'  => true,
			'errors' => array( __( 'Search term too short', 'ignition' ) ),
			'data'   => array(),
		);

		wp_send_json( $response );
	}

	$sku_match = wc_get_products( array(
		'limit'  => $max_results,
		'return' => 'ids',
		'sku'    => $s,
	) );

	$p_args = array(
		'limit'        => $max_results - count( $sku_match ),
		'status'       => 'publish',
		'stock_status' => 'instock',
		'return'       => 'ids',
		// This requires the ignition_woocommerce_product_query_custom_query_var() filter above.
		's'            => $s,
	);

	if ( ! empty( $cat ) ) {
		$p_args['category'] = $cat;
	}

	$p = wc_get_products( $p_args );

	$p = array_merge( $sku_match, $p );
	$p = array_unique( $p );

	if ( empty( $p ) ) {
		$response = array(
			'error'  => true,
			'errors' => array( __( 'No products match the search term', 'ignition' ) ),
			'data'   => array(),
		);

		wp_send_json( $response );
	}

	$q_args = array(
		'post__in'            => $p,
		'post_type'           => 'product',
		'posts_per_page'      => $max_results,
		'ignore_sticky_posts' => true,
	);

	$q = new WP_Query( $q_args );

	$response = array(
		'error'  => false,
		'errors' => array(),
		'data'   => array(),
	);

	while ( $q->have_posts() ) {
		$q->the_post();

		$product = wc_get_product( get_the_ID() );

		$result = array(
			'title'   => html_entity_decode( get_the_title() ),
			'url'     => get_permalink(),
			'image'   => $product->get_image( $image_size ),
			'price'   => html_entity_decode( $product->get_price_html() ),
			'excerpt' => html_entity_decode( wp_trim_words( get_the_excerpt(), $excerpt_length ) ),
		);

		$response['data'][] = $result;
	}
	wp_reset_postdata();

	wp_send_json( $response );
}
