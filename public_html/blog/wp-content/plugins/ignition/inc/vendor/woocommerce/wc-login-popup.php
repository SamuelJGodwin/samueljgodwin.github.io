<?php
/**
 * WooCommerce login link/popup shortcode's related hooks and functions
 *
 * @since 1.2.0
 */

add_action( 'wp_ajax_nopriv_ignition_wc_popup_ajax_login', 'ignition_woocommerce_login_popup_ajax_login' );
/**
 * AJAX handler for the WooCommerce login popup.
 *
 * @since 1.2.0
 */
function ignition_woocommerce_login_popup_ajax_login() {

	check_ajax_referer( 'woocommerce-login', 'nonce' );

	$messages = ignition_woocommerce_login_popup_get_messages();

	if ( ! empty( $_POST['username'] ) && ! empty( $_POST['password'] ) ) {
		$info                  = array();
		$info['user_login']    = wc_clean( $_POST['username'] );
		$info['user_password'] = $_POST['password'];
		$info['remember']      = false;
		if ( true === $_POST['rememberme'] || 'true' === $_POST['rememberme'] ) {
			$info['remember'] = true;
		}

		$user_signon = wp_signon( $info );
		if ( is_wp_error( $user_signon ) ) {

			$error_string = $user_signon->get_error_message();
			wp_send_json_error( array(
				'message'            => $error_string,
				'invalid_username'   => isset( $user_signon->errors['invalid_username'] ) ? true : false,
				'incorrect_password' => isset( $user_signon->errors['incorrect_password'] ) ? true : false,
			) );
		} else {
			/**
			 * Fires after the user has successfully logged in from the popup form.
			 *
			 * @since 1.2.0
			 *
			 * @param WP_User $user_signon WP_User object of the logged-in user.
			 */
			do_action( 'ignition_wc_popup_after_login', $user_signon );
			wp_send_json_success( array(
				'message'  => $messages['login_success'],
				/**
				 * Filters the URL to redirect to, after the use has logged in.
				 *
				 * When there is no URL, the same page reloads instead.
				 *
				 * @since 1.2.0
				 *
				 * @param string|false $url The URL to redirect to. false to reload the current page.
				 */
				'redirect' => apply_filters( 'ignition_wc_popup_redirect', false ),
			) );
		}
	}

	wp_send_json_error( array(
		'message' => $messages['fields_required'],
	) );
}

/**
 * Builds the markup for the WooCommerce login link/popup.
 *
 * @since 1.2.0
 *
 * @return string The WooCommerce login popup/link output.
 */
function ignition_woocommerce_login_popup_output() {
	static $displayed = false;

	$messages = ignition_woocommerce_login_popup_get_messages();

	$my_account_id  = get_option( 'woocommerce_myaccount_page_id' );
	$my_account_url = get_permalink( $my_account_id );

	if ( ! $my_account_id && current_user_can( 'manage_woocommerce' ) ) {
		return '<span class="ignition-wc-login-no-account">' .
			sprintf( __( '"My account" page is not set. Navigate to <a href="%s">WooCommerce Settings</a> to set it.', 'ignition' ),
				esc_url( add_query_arg( array(
					'page' => 'wc-settings',
					'tab'  => 'advanced',
				), admin_url( '/admin.php' ) ) )
			) .
			'</span>';
	}

	if ( is_user_logged_in() ) {
		return sprintf( '<a href="%s">%s</a>',
			esc_url( $my_account_url ),
			esc_html( $messages['my_account_link'] )
		);
	}

	if ( wp_is_mobile() ) {
		return sprintf( '<a href="%s">%s</a>',
			esc_url( $my_account_url ),
			esc_html( $messages['login_link'] )
		);
	}

	ob_start();

	?><a href="#ignition-wc-login-wrapper" class="open-ignition-wc-login-popup"><?php esc_html_e( 'Login', 'ignition' ); ?></a><?php

	if ( ! $displayed ) {
		wp_enqueue_style( 'ignition-wc-login-popup' );
		wp_enqueue_script( 'ignition-wc-login-popup' );

		?>
		<div id="ignition-wc-login-wrapper" class="mfp-hide ignition-wc-login-wrapper">
			<div class="ignition-wc-login-notices"></div>
			<?php woocommerce_login_form(); ?>
		</div>
		<?php

		$displayed = true;
	}

	return ob_get_clean();

}

/**
 * Returns strings used by the WooCommerce login link/popup shortcode.
 *
 * @since 1.2.0
 *
 * @return array
 */
function ignition_woocommerce_login_popup_get_messages() {
	return apply_filters( 'ignition_woocommerce_login_popup_messages', array(
		'login_link'      => __( 'Login', 'ignition' ),
		'my_account_link' => __( 'My account', 'ignition' ),
		'fields_required' => __( 'Please fill all required fields.', 'ignition' ),
		'loading_message' => __( 'Logging in. Please wait...', 'ignition' ),
		'login_success'   => __( 'Login successful, redirecting...', 'ignition' ),
		'ajax_error'      => __( 'Oops, something went wrong! Please try again.', 'ignition' ),
	) );
}
