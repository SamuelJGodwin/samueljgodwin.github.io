<?php
/**
 * Shortcode definitions
 *
 * @since 1.0.0
 */

add_shortcode( 'ignition-site-search', 'ignition_shortcode_site_search' );
/**
 * Builds the Site Search shortcode output.
 *
 * @since 1.0.0
 * @since 1.2.0 Added the shortocde parameter `$params['post_type']`.
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string Search form output.
 */
function ignition_shortcode_site_search( $params, $content, $shortcode ) {
	$params = shortcode_atts( array(
		'post_type' => '',
	), $params, $shortcode );

	static $displayed = false;

	ob_start();

	if ( ! $displayed ) {
		$post_types = array();
		$post_types = explode( ',', $params['post_type'] );
		$post_types = array_map( 'trim', $post_types );
		$post_types = array_filter( $post_types );

		?>
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="global-search-form" role="search">
			<label for="global-search-input" class="sr-only"><?php esc_html_e( 'Search for:', 'ignition' ); ?></label>
			<input type="search" id="global-search-input" name="s" class="global-search-input" value="<?php echo get_search_query(); ?>" placeholder="<?php echo esc_attr_x( 'Type and hit enter to search', 'search box placeholder', 'ignition' ); ?>" />
			<button type="submit" class="global-search-form-submit">
				<?php esc_html_e( 'Search', 'ignition' ); ?>
			</button>
			<button type="button" class="global-search-form-dismiss">&times;</button>

			<?php if ( count( $post_types ) > 1 ) : ?>
				<?php foreach ( $post_types as $post_type ) : ?>
					<input type="hidden" name="post_type[]" value="<?php echo esc_attr( $post_type ); ?>">
				<?php endforeach; ?>
			<?php elseif ( count( $post_types ) === 1 ) : ?>
				<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_types[0] ); ?>">
			<?php endif; ?>
		</form>
		<?php

		$displayed = true;
	}

	?>
	<a href="#" class="global-search-form-trigger">
		<span class="ignition-icons ignition-icons-search"></span> <span class="sr-only"><?php esc_html_e( 'Expand search form', 'ignition' ); ?></span>
	</a>
	<?php

	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'ignition-date', 'ignition_shortcode_date' );
/**
 * Builds the Date shortcode output.
 *
 * @since 1.0.0
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string
 */
function ignition_shortcode_date( $params, $content, $shortcode ) {
	$params = shortcode_atts( array(
		'format' => get_option( 'date_format' ),
	), $params, $shortcode );

	return date_i18n( $params['format'] );
}

add_shortcode( 'ignition-custom-menu', 'ignition_shortcode_custom_menu' );
/**
 * Builds the Custom Menu shortcode output.
 *
 * @since 1.0.0
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string
 */
function ignition_shortcode_custom_menu( $params, $content, $shortcode ) {
	$params = shortcode_atts( array(
		'name' => '',
	), $params, $shortcode );


	if ( empty( $params['name'] ) ) {
		return '';
	}

	$menu_object = get_term_by( 'name', $params['name'], 'nav_menu' );

	if ( false === $menu_object ) {
		return '';
	}

	ob_start();

	wp_nav_menu( array(
		'menu'      => $menu_object,
		'container' => '',
		'depth'     => 1,
	) );

	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'ignition-minicart-button', 'ignition_shortcode_minicart_button' );
/**
 * Builds the WooCommerce mini-cart shortcode output.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_shortcode_minicart_button() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return '';
	}

	if ( is_admin() || defined( 'REST_REQUEST' ) ) {
		return '';
	}

	wp_enqueue_style( 'ignition-shortcode-minicart' );
	wp_enqueue_script( 'ignition-shortcode-minicart' );

	ob_start();

	?>
	<div class="head-mini-cart-wrap">
		<div class="header-mini-cart">
			<a href="#" class="header-mini-cart-trigger">
				<?php ignition_shortcode_minicart_button_trigger_text(); ?>
			</a>

			<div class="header-mini-cart-contents">

				<div class="widget woocommerce widget_shopping_cart">
					<h3 class="widget-title"><?php esc_html_e( 'Cart', 'ignition' ); ?></h3>

					<div class="widget_shopping_cart_content">
						<?php woocommerce_mini_cart(); ?>

						<p class="buttons">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="button wc-forward"><?php esc_html_e( 'View Cart', 'ignition' ); ?></a>
							<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button checkout wc-forward"><?php esc_html_e( 'Checkout', 'ignition' ); ?></a>
						</p>
					</div>
				</div>

			</div>
		</div>
	</div>
	<?php

	$output = ob_get_clean();

	return $output;
}

/**
 * Outputs the HTML for the WooCommerce mini-cart's trigget button.
 *
 * @see ignition_shortcode_minicart_button()
 *
 * @since 1.0.0
 */
function ignition_shortcode_minicart_button_trigger_text() {
	// This span.header-mini-cart-trigger-text is needed for cart fragments updates.
	// Do not remove or modify without modifying the respective fragment code also.
	?>
	<span class="header-mini-cart-trigger-text">
		<span class="header-mini-cart-icon">
			<span class="ignition-icons ignition-icons-shopping-basket"></span>
			<span class="header-mini-cart-count">
				<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
			</span>
		</span>
		<span class="header-mini-cart-total">
			<?php echo wp_kses( WC()->cart->get_cart_subtotal(), array_merge( wp_kses_allowed_html( 'post' ), array( 'bdo' => array() ) ) ); ?>
		</span>
	</span>
	<?php
}

add_shortcode( 'ignition-current-weather', 'ignition_shortcode_current_weather' );
/**
 * Builds the Current Weather shortcode output.
 *
 * @since 1.0.0
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string
 */
function ignition_shortcode_current_weather( $params, $content, $shortcode ) {
	$params = shortcode_atts( array(
		'id'    => get_theme_mod( 'utilities_openweathermap_location_id', ignition_customizer_defaults( 'utilities_openweathermap_location_id' ) ),
		'units' => get_theme_mod( 'utilities_openweathermap_units', ignition_customizer_defaults( 'utilities_openweathermap_units' ) ),
	), $params, $shortcode );

	$location_id = $params['id'];
	$units       = $params['units'];

	$conditions = ignition_openweathermap_get_weather_conditions( $location_id, $units, get_theme_mod( 'utilities_openweathermap_api_key' ) );

	ob_start();

	if ( ! $conditions['error'] ) {
		wp_enqueue_script( 'ignition-shortcode-weather' );
		ignition_get_template_part( 'template-parts/shortcode/current-weather', '', $conditions );
	} elseif ( ! $conditions['error'] && is_customize_preview() ) {
		if ( ! empty( $conditions['error_type'] ) && 'ignition' === $conditions['error_type'] ) {
			?><span class="weather-error"><?php echo wp_kses_post( __( 'Set your API key and Location in Customize &rarr; Utilities &rarr; Weather', 'ignition' ) ); ?></span><?php
		}
	}

	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'ignition-demo-language-switcher', 'ignition_shortcode_demo_language_switcher' );
/**
 * Builds the Demo Language Switcher shortcode output.
 *
 * @since 1.0.0
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string
 */
function ignition_shortcode_demo_language_switcher( $params, $content, $shortcode ) {
	ob_start();
	?>
	<div class="theme-language-switch-wrap">
		<a href="#" class="theme-language-switch-trigger">
			<img src="<?php echo esc_url( untrailingslashit( IGNITION_DIR_URL ) . '/inc/assets/images/gb-flag.svg' ); ?>" alt=""/> <?php echo esc_html_x( 'English', 'language', 'ignition' ); ?>
		</a>

		<div class="theme-language-switch-dropdown">
			<?php esc_html_e( 'You can use WPML or Polylang and their language switchers in this area.', 'ignition' ); ?>
		</div>
	</div>
	<?php

	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'ignition-language-switcher', 'ignition_language_switcher_shortcode' );
/**
 * Builds the Language Switcher shortcode output.
 *
 * @since 1.0.0
 *
 * @param array $params {
 *     Array of default language switcher attributes.
 *
 *     @type string $display      Whether to display flags, text, or both. Accepts 'flags', 'text', 'both'.
 *                                Default 'both'.
 *     @type string $type         The language switcher layout. Accepts 'menu', 'dropdown'. Default 'menu'.
 *     @type string $untranslated Whether to hide the language entry for untranslated items, or link to the language's
 *                                homepage. Accepts 'home', 'hide'. Default 'home'.
 * }
 *
 * @return false|string The language switcher.
 */
function ignition_language_switcher_shortcode( $params ) {
	$params = shortcode_atts( array(
		'display'      => 'both',
		'type'         => 'menu',
		'untranslated' => 'home',
	), $params, 'ignition-language-switcher' );

	$flags = 0;
	$text  = 0;

	$type         = 'dropdown' === $params['type'] ? 'dropdown' : 'menu';
	$untranslated = 'hide' === $params['untranslated'] ? 'hide' : 'home';

	switch ( $params['display'] ) {
		case 'flags':
			$flags = true;
			$text  = false;
			break;
		case 'text':
			$flags = false;
			$text  = true;
			break;
		case 'both':
		default:
			$flags = true;
			$text  = true;
			break;
	}
	ob_start();

	ignition_language_switcher( $flags, $text, $type, $untranslated );

	return ob_get_clean();
}

add_shortcode( 'ignition-demo-newsletter-form', 'ignition_shortcode_demo_newsletter_form' );
/**
 * Builds the Demo Newsletter Form shortcode output.
 *
 * @since 1.0.0
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string
 */
function ignition_shortcode_demo_newsletter_form( $params, $content, $shortcode ) {
	$params = shortcode_atts( array(
		'label_text'        => __( 'Email address:', 'ignition' ),
		'input_placeholder' => __( 'E-mail', 'ignition' ),
		'button_text'       => __( 'Subscribe', 'ignition' ),
	), $params, $shortcode );

	ob_start();

	?>
	<form action="#" method="post" class="demo-newsletter-form">
		<label for="demo-newsletter-form-input" class="sr-only"><?php echo esc_html( $params['label_text'] ); ?></label>
		<input type="text" id="demo-newsletter-form-input" name="demo-newsletter-form-input" class="demo-newsletter-form-input" placeholder="<?php echo esc_attr( $params['input_placeholder'] ); ?>" />
		<button type="submit" class="demo-newsletter-form-submit">
			<?php echo esc_html( $params['button_text'] ); ?>
		</button>
	</form>
	<?php

	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'ignition-icon-link', 'ignition_shortcode_icon_link' );
/**
 * Builds the Icon Link shortcode output.
 *
 * @since 1.2.0
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string
 */
function ignition_shortcode_icon_link( $params, $content, $shortcode ) {
	$params = shortcode_atts( array(
		'icon' => '',
		'text' => '',
		'link' => '',
	), $params, $shortcode );

	if ( ! $params['link'] || ( ! $params['icon'] && ! $params['text'] ) ) {
		return '';
	}

	ob_start();

	?>
	<span class="ignition-icon-link">
		<a href="<?php echo esc_url( $params['link'] ); ?>">
			<?php
			if ( $params['icon'] ) {
				?>
				<span class="ignition-icons ignition-icons-<?php echo esc_attr( $params['icon'] ); ?>"></span>
				<?php
			}
			if ( $params['text'] ) {
				echo wp_kses_post( $params['text'] );
			}
			?>
		</a>
	</span>
	<?php

	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'ignition-instagram-feed', 'ignition_shortcode_instagram_feed' );
/**
 * Builds the WPZOOM Instagram feed shortcode output.
 *
 * @since 1.2.0
 *
 * @param array $params {
 *     Array of default Instagram feed attributes.
 *
 *     @type string $username   The username of the feed you wish to display. Accepts any valid Instagram username.
 *     @type int    $limit      The maximum number of images to display. Default 12,
 *     @type int    $videos     Whether to display video thumbnails. Accepts 1 or 0. Default 0.
 *     @type string $resolution The default image resolution. Accepts 'default' (automatic selection),
 *                              'thumbnail' (150x150px), 'low' (320x320px), 'standard' (640x640px). Default 'default'.
 *     @type int    $width      The desired width of each image, when $resolution is 'default'.
 *                              Accepts any positive integer. Default 250.
 *     @type int    $carousel   Whether to enable the slick slider carousel. Accepts 1 or 0. Default 1.
 *     @type int    $autoplay   Whether the carousel will auto slide. Requires carousel to be enabled. Accepts 1 or 0.
 *                              Default 0.
 *     @type int    $slides     The number of visible images. Requires carousel to be enabled. Accepts any positive
 *                              integer. Default 8.
 *     @type int    $arrows     Whether to show the navigation arrows. Requires carousel to be enabled. Accepts 1 or 0.
 *                              Default 0.
 *     @type int    $speed      The auto slide timeout in milliseconds. Requires carousel and autoplay to be enabled.
 *                              Accepts any positive integer. Default 3000.
 * }
 *
 * @return string The Instagram feed shortcode output.
 */
function ignition_shortcode_instagram_feed( $params ) {
	if ( ! class_exists( 'Wpzoom_Instagram_Widget_API' ) ) {
		return '';
	}

	$params = shortcode_atts( array(
		'username'   => '',
		'limit'      => 12,
		'videos'     => 0,
		'resolution' => 'default',
		'width'      => 250,
		'carousel'   => 1,
		'autoplay'   => 0,
		'slides'     => 8,
		'arrows'     => 0,
		'speed'      => 3000,
	), $params, 'ignition-instagram-feed' );

	$wpzoom_options = wp_parse_args( get_option( 'wpzoom-instagram-widget-settings' ), array(
		'username'           => '',
		'request-type'       => '',
		'basic-access-token' => '',
	) );

	$params['username'] = $params['username'] ? $params['username'] : $wpzoom_options['username'];

	switch ( $params['resolution'] ) {
		case 'thumbnail':
			// This is fine as it is.
			break;
		case 'low':
		case 'standard':
			$params['resolution'] .= '_resolution';
			break;
		case 'default':
		default:
			$params['resolution'] = 'default_algorithm';
	}

	if ( empty( $params['username'] ) && 'without-access-token' === $wpzoom_options['request-type'] && empty( $wpzoom_options['basic-access-token'] ) ) {
		return '<p class="ignition-instagram-feed-error">' . __( 'Username error. Connect your Instagram account or add a default username under Settings > Instagram Widget and/or add a username to the shortcode using the username attribute.', 'ignition' ) . '</p>';
	}

	$feed = ignition_instagram_items( $params['username'], $params['limit'], $params['videos'], $params['resolution'], $params['width'] );

	if ( ! $feed ) {
		return '';
	}

	$carousel_class = 'ignition-static-feed';

	if ( 1 === absint( $params['carousel'] ) ) {
		wp_enqueue_style( 'slick' );
		wp_enqueue_script( 'slick' );
		$carousel_class = 'ignition-carousel';
	}

	ob_start();

	?>
	<div class="ignition-instagram-wrapper <?php echo esc_attr( $carousel_class ); ?>"
		data-auto="<?php echo absint( $params['autoplay'] ); ?>"
		data-speed="<?php echo absint( $params['speed'] ); ?>"
		data-slides="<?php echo absint( $params['slides'] ); ?>"
		data-arrows="<?php echo absint( $params['arrows'] ); ?>"
	>
		<?php echo $feed; ?>
	</div>
	<?php

	return ob_get_clean();
}

add_shortcode( 'ignition-wc-login', 'ignition_shortcode_wc_login' );
/**
 * Builds the WooCommerce login link/popup shortcode output.
 *
 * @since 1.2.0
 *
 * @return string The WooCommerce login popup/link shortcode output.
 */
function ignition_shortcode_wc_login() {
	// Leave the shortcode registered even if WooCommerce is not enabled, so that nothing is output if it's
	// temporarily disabled.
	if ( ! class_exists( 'WooCommerce' ) || is_admin() ) {
		return '';
	}

	return ignition_woocommerce_login_popup_output();
}

add_shortcode( 'ignition-wc-search', 'ignition_shortcode_wc_search' );
/**
 * Builds the WooCommerce AJAX search shortcode output.
 *
 * @since 1.5.0
 *
 * @see ignition_woocommerce_product_search_output()
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string The WooCommerce AJAX search shortcode output.
 */
function ignition_shortcode_wc_search( $params, $content, $shortcode ) {
	// Leave the shortcode registered even if WooCommerce is not enabled, so that nothing is output if it's
	// temporarily disabled.
	if ( ! class_exists( 'WooCommerce' ) || is_admin() ) {
		return '';
	}

	return ignition_woocommerce_product_search_output( $params, $content, $shortcode );
}

add_shortcode( 'ignition-booking-form', 'ignition_shortcode_booking_form' );
/**
 * Builds the ignition booking form.
 *
 * @since 2.1.0
 *
 * @param array $params {
 *     Array of default booking form options.
 *
 *     @type string $url                The URL to the booking form page.
 *     @type string $layout             The layout of the booking form. Accepts 'vertical', 'horizontal', 'button-below'.
 *                                      Default 'vertical'.
 *     @type string $arrive_name        The name attribute of the arrival date field in the target form. Default 'arrive'.
 *     @type string $depart_name        The name attribute of the departure date field in the target form. Default 'depart'.
 *     @type string $persons_name       The name attribute of the number of persons field in the target form. Default 'persons'.
 *     @type string $accommodation_name The name attribute of the accommodation field in the target form. Default 'accommodation'.
 *     @type string $values             The accommodation's field that will be used as the dropdown's value.
 *                                      Accepts 'title', 'slug', 'id'. Default 'title'.
 * }
 *
 * @return string The booking form shortcode output.
 */
function ignition_shortcode_booking_form( $params ) {
	$params = shortcode_atts( array(
		'url'                => '',
		'layout'             => 'vertical',
		'arrive_name'        => 'arrive',
		'depart_name'        => 'depart',
		'persons_name'       => 'persons',
		'accommodation_name' => 'accommodation',
		'values'             => 'title',
	), $params, 'ignition-booking-form' );

	$layouts = array( 'vertical', 'horizontal', 'button-below' );

	$form_url = $params['url'];
	$layout   = in_array( $params['layout'], $layouts, true ) ? $params['layout'] : 'vertical';

	$value_field = 'post_title';
	switch ( $params['values'] ) {
		case 'slug':
			$value_field = 'post_name';
			break;
		case 'id':
			$value_field = 'ID';
			break;
		case 'title':
		default:
			$value_field = 'post_title';
	}

	wp_enqueue_script( 'ignition-shortcode-booking-form' );
	wp_enqueue_style( 'litepicker' );

	ob_start();

	if ( empty( $form_url ) ) {
		?>
		<p class="ignition-booking-form-error"><?php esc_html_e( 'No action URL found for the form, please use the url shortcode parameter to add one.', 'ignition' ); ?></p>
		<?php
	} else {
		?>
		<form action="<?php echo esc_url_raw( $form_url ); ?>" method="GET" class="ignition-booking-form ignition-booking-form-<?php echo esc_attr( $layout ); ?>">
			<div class="form-error"></div>
			<div class="form-element form-date">
				<label for="ignition-booking-form-arrive" class="ignition-booking-form-arrive-label sr-only"><?php esc_html_e( 'Arrival', 'ignition' ); ?> <span class="required">*</span></label>
				<input type="text" class="form-control ignition-booking-form-arrive" name="<?php echo esc_attr( $params['arrive_name'] ); ?>" required="required" placeholder="<?php esc_attr_e( 'Arrival', 'ignition' ); ?>">
			</div>
			<div class="form-element form-date">
				<label for="ignition-booking-form-depart" class="ignition-booking-form-depart-label sr-only"><?php esc_html_e( 'Departure', 'ignition' ); ?> <span class="required">*</span></label>
				<input type="text" class="form-control ignition-booking-form-depart" name="<?php echo esc_attr( $params['depart_name'] ); ?>" required="required" placeholder="<?php esc_attr_e( 'Departure', 'ignition' ); ?>">
			</div>
			<div class="form-element form-persons">
				<label for="ignition-booking-form-persons" class="ignition-booking-form-persons-label sr-only"><?php esc_html_e( 'Persons', 'ignition' ); ?> <span class="required">*</span></label>
				<select class="form-control" name="<?php echo esc_attr( $params['persons_name'] ); ?>" required="required">
					<option value="" disabled selected><?php esc_html_e( 'Persons', 'ignition' ); ?></option>
					<?php for ( $i = 1; $i <= 6; $i ++ ) {
						echo sprintf( '<option value="%s">%s</option>', esc_attr( $i ), absint( $i ) );
					} ?>
				</select>
			</div>
			<div class="form-element form-accommodation">
				<label for="ignition-booking-form-accommodation" class="ignition-booking-form-accommodation-label sr-only"><?php esc_html_e( 'Accommodation', 'ignition' ); ?> <span class="required">*</span></label>
				<?php
					ignition_dropdown_posts( array(
						'post_type'   => 'ignition-accommodati',
						'value_field' => $value_field,
					), $params['accommodation_name'] );
				?>
			</div>
			<div class="form-element form-button">
				<button type="submit" class="btn ignition-booking-form-submit"><?php esc_html_e( 'Book now', 'ignition' ); ?></button>
			</div>
		</form>
		<?php
	}

	return ob_get_clean();
}
