<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WSRW Connect.
 *
 * WSRW Connect is our service that makes it easy to upgrade to Search & Replace Everything Pro
 * without having to manually install the Search & Replace Everything Pro plugin.
 *
 * @since 1.0.1
 */
class WSRW_Connect {

	/**
	 * Constructor.
	 *
	 * @since 1.0.1
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.0.1
	 */
	public function hooks() {
		add_action( 'wsrw_admin_page_content_wsrw-search-replace', array( $this, 'settings_enqueues' ) );
		add_action( 'wp_ajax_wsrw_connect_url', array( $this, 'generate_url' ) );
		add_action( 'wp_ajax_nopriv_wsrw_connect_process', array( $this, 'process' ) );
	}

	/**
	 * Settings page enqueues.
	 *
	 * @since 1.0.1
	 */
	public function settings_enqueues() {

		// Load this script just on the settings view.
		if ( ! isset( $_GET['view'] ) || 'settings' !== $_GET['view'] ) { // phpcs:ignore
			return;
		}

		$admin_asset_file = WSRW_PLUGIN_PATH . 'build/connect.asset.php';

		if ( ! file_exists( $admin_asset_file ) ) {
			return;
		}

		$asset = require $admin_asset_file;

		wp_enqueue_script( 'wsrw-connect-js', WSRW_PLUGIN_URL . 'build/connect.js', $asset['dependencies'], $asset['version'], true );
	}

	/**
	 * Generate and return the WPCode Connect URL.
	 *
	 * @since 1.0.1
	 */
	public function generate_url() {

		// Run a security check.
		check_ajax_referer( 'wsrw_admin' );

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to install plugins.', 'search-replace-wpcode' ) ) );
		}

		$key = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';

		if ( empty( $key ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter your license key to connect.', 'search-replace-wpcode' ) ) );
		}

		if ( class_exists( 'WSRW_Main_Premium' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Only the Lite version can be upgraded.', 'search-replace-wpcode' ) ) );
		}

		// Verify pro version is not installed.
		$active = activate_plugin( 'search-replace-wpcode-pro/wsrw-premium.php', false, false, true );

		if ( ! is_wp_error( $active ) ) {

			update_option( 'wsrw_install', 1 ); // Run install routines.
			// Deactivate Lite.
			$plugin = plugin_basename( WSRW_FILE );

			deactivate_plugins( $plugin );

			do_action( 'wsrw_plugin_deactivated', $plugin );

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Search & Replace Everything Pro is installed but not activated.', 'search-replace-wpcode' ),
					'reload'  => true,
				)
			);
		}

		// Generate URL.
		$oth        = hash( 'sha512', wp_rand() );
		$hashed_oth = hash_hmac( 'sha512', $oth, wp_salt() );

		update_option( 'wsrw_connect_token', $oth );
		update_option( 'wsrw_connect', $key );

		$version  = WSRW_VERSION;
		$endpoint = admin_url( 'admin-ajax.php' );
		$redirect = admin_url( 'admin.php?page=wsrw-search-replace&view=settings' );
		$url      = add_query_arg(
			array(
				'key'      => $key,
				'oth'      => $hashed_oth,
				'endpoint' => $endpoint,
				'version'  => $version,
				'siteurl'  => admin_url(),
				'homeurl'  => home_url(),
				'redirect' => rawurldecode( base64_encode( $redirect ) ), // phpcs:ignore
				'v'        => 2,
				'php'      => phpversion(),
				'wp'       => get_bloginfo( 'version' ),
			),
			'https://connect.wpcode.com/'
		);

		wp_send_json_success(
			array(
				'url'      => $url,
				'back_url' => add_query_arg(
					array(
						'action' => 'wsrw_connect',
						'oth'    => $oth,
					),
					$endpoint
				),
			)
		);
	}

	/**
	 * Process WPCode Connect.
	 *
	 * @since 1.0.1
	 */
	public function process() {

		$error = esc_html__( 'There was an error while installing an upgrade. Please download the plugin from wsrw.com and install it manually.', 'search-replace-wpcode' );

		// Verify params present (oth & download link).
		$post_oth = ! empty( $_REQUEST['oth'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['oth'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$post_url = ! empty( $_REQUEST['file'] ) ? esc_url_raw( wp_unslash( $_REQUEST['file'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		if ( empty( $post_oth ) || empty( $post_url ) ) {
			wp_send_json_error( $error );
		}

		// Verify oth.
		$oth = get_option( 'wsrw_connect_token' );

		if ( hash_hmac( 'sha512', $oth, wp_salt() ) !== $post_oth ) {
			wp_send_json_error( $error );
		}

		// Delete so cannot replay.
		delete_option( 'wsrw_connect_token' );

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'wsrw_page_wsrw-settings' );

		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				array(
					'page' => 'wsrw-search-replace',
					'view' => 'settings',
				),
				admin_url( 'admin.php' )
			)
		);

		// Verify pro not activated.
		if ( class_exists( 'WSRW_Main_Premium' ) ) {
			wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'search-replace-wpcode' ) );
		}

		// Verify pro not installed.
		$active = activate_plugin( 'search-replace-wpcode-pro/wsrw-premium.php', $url, false, true );

		if ( ! is_wp_error( $active ) ) {
			$plugin = plugin_basename( WSRW_FILE );

			deactivate_plugins( $plugin );

			do_action( 'wsrw_plugin_deactivated', $plugin );

			wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'search-replace-wpcode' ) );
		}

		$creds = request_filesystem_credentials( $url, '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds || ! WP_Filesystem( $creds ) ) {
			wp_send_json_error(
				esc_html__( 'There was an error while installing an upgrade. Please check file system permissions and try again. Also, you can download the plugin from library.wpcode.com and install it manually.', 'search-replace-wpcode' )
			);
		}

		/*
		 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		 */
		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		wsrw_require_upgrader();

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( new WSRW_Skin() );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) ) {
			wp_send_json_error( $error );
		}

		// Check license key.
		$key = get_option( 'wsrw_connect', false );

		if ( empty( $key ) ) {
			wp_send_json_error(
				new WP_Error(
					'403',
					esc_html__( 'No key provided.', 'search-replace-wpcode' )
				)
			);
		}

		$installer->install( $post_url ); // phpcs:ignore

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $installer->plugin_info();

		if ( $plugin_basename ) {

			// Deactivate the lite version first.
			$plugin = plugin_basename( WSRW_FILE );

			deactivate_plugins( $plugin );

			do_action( 'wsrw_plugin_deactivated', $plugin );

			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename, '', false, true );

			if ( ! is_wp_error( $activated ) ) {
				add_option( 'wsrw_install', 1 );
				wp_send_json_success( esc_html__( 'Plugin installed & activated.', 'search-replace-wpcode' ) );
			} else {
				// Reactivate the lite plugin if pro activation failed.
				activate_plugin( plugin_basename( WSRW_FILE ), '', false, true );
				wp_send_json_error( esc_html__( 'Pro version installed but needs to be activated on the Plugins page inside your WordPress admin.', 'search-replace-wpcode' ) );
			}
		}

		wp_send_json_error( $error );
	}
}

new WSRW_Connect();
