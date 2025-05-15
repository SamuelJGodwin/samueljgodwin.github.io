<?php
/*
 * Plugin Name: Search & Replace Everything
 * Plugin URI: https://wpcode.com/
 * Description: Search & Replace text and images across your entire WordPress database with a simple, powerful interface.
 * Version: 1.0.7
 * Author: WPCode
 * Author URI: https://wpcode.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: search-replace-wpcode
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't allow multiple versions to be active.
if ( function_exists( 'wsrw_main' ) ) {

	if ( ! function_exists( 'wsrw_pro_just_activated' ) ) {
		/**
		 * When we activate a Pro version, we need to do additional operations:
		 * 1) deactivate a Lite version;
		 * 2) register option which help to run all activation process for Pro version (custom tables creation, etc.).
		 */
		function wsrw_pro_just_activated() {
			wsrw_deactivate();
			add_option( 'wsrw_install', 1 );
		}
	}
	add_action( 'activate_search-replace-wpcode-pro/wsrw-premium.php', 'wsrw_pro_just_activated' );

	if ( ! function_exists( 'wsrw_lite_just_activated' ) ) {
		/**
		 * Store temporarily that the Lite version of the plugin was activated.
		 * This is needed because WP does a redirect after activation and
		 * we need to preserve this state to know whether user activated Lite or not.
		 */
		function wsrw_lite_just_activated() {

			set_transient( 'wsrw_lite_just_activated', true );
		}
	}
	add_action( 'activate_search-replace-wpcode/wsrw.php', 'wsrw_lite_just_activated' );

	if ( ! function_exists( 'wsrw_lite_just_deactivated' ) ) {
		/**
		 * Store temporarily that Lite plugin was deactivated.
		 * Convert temporary "activated" value to a global variable,
		 * so it is available through the request. Remove from the storage.
		 */
		function wsrw_lite_just_deactivated() {

			global $wsrw_lite_just_activated, $wsrw_lite_just_deactivated;

			$wsrw_lite_just_activated   = (bool) get_transient( 'wsrw_lite_just_activated' );
			$wsrw_lite_just_deactivated = true;

			delete_transient( 'wsrw_lite_just_activated' );
		}
	}
	add_action( 'deactivate_search-replace-wpcode/wsrw.php', 'wsrw_lite_just_deactivated' );

	if ( ! function_exists( 'wsrw_deactivate' ) ) {
		/**
		 * Deactivate Lite if Search & Replace Everything is already activated.
		 */
		function wsrw_deactivate() {

			$plugin = 'search-replace-wpcode/wsrw.php';

			deactivate_plugins( $plugin );

			do_action( 'wsrw_plugin_deactivated', $plugin );
		}
	}
	add_action( 'admin_init', 'wsrw_deactivate' );

	if ( ! function_exists( 'wsrw_lite_notice' ) ) {
		/**
		 * Display the notice after deactivation when Pro is still active
		 * and user wanted to activate the Lite version of the plugin.
		 */
		function wsrw_lite_notice() {

			global $wsrw_lite_just_activated, $wsrw_lite_just_deactivated;

			if (
				empty( $wsrw_lite_just_activated ) ||
				empty( $wsrw_lite_just_deactivated )
			) {
				return;
			}

			// Currently tried to activate Lite with Pro still active, so display the message.
			printf(
				'<div class="notice notice-warning">
					<p>%1$s</p>
					<p>%2$s</p>
				</div>',
				esc_html__( 'Heads up!', 'search-replace-wpcode' ),
				esc_html__( 'Your site already has Search & Replace Everything Pro activated. If you want to switch to Search & Replace Everything Lite, please first go to Plugins → Installed Plugins and deactivate Search & Replace Everything Pro. Then, you can activate Search And Replace Everything Lite.', 'search-replace-wpcode' )
			);

			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			unset( $wsrw_lite_just_activated, $wsrw_lite_just_deactivated );
		}
	}
	add_action( 'admin_notices', 'wsrw_lite_notice' );

	// Do not process the plugin code further.
	return;
}

/**
 * Main plugin class.
 */
class WSRW_Main {

	/**
	 * Holds the instance of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var WSRW_Main The one true WSRW_Main
	 */
	private static $instance;

	/**
	 * Plugin version.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $version = '';

	/**
	 * The admin page loader.
	 *
	 * @var WSRW_Admin_Page_Loader
	 */
	public $admin_page_loader;

	/**
	 * The admin notices instance.
	 *
	 * @var WSRW_Notice
	 */
	public $notice;

	/**
	 * The settings instance.
	 *
	 * @var WSRW_Settings
	 */
	public $settings;

	/**
	 * Main instance of WSRW_Main.
	 *
	 * @return WSRW_Main
	 * @since 2.0.0
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WSRW_Main ) ) {
			self::$instance = new WSRW_Main();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->setup_constants();
		$this->includes();
		add_action( 'plugins_loaded', array( $this, 'load_components' ) );
	}

	/**
	 * Set up global constants.
	 *
	 * @return void
	 */
	private function setup_constants() {

		define( 'WSRW_FILE', __FILE__ );

		$plugin_headers = get_file_data( WSRW_FILE, array( 'version' => 'Version' ) );

		define( 'WSRW_VERSION', $plugin_headers['version'] );
		define( 'WSRW_PLUGIN_BASENAME', plugin_basename( WSRW_FILE ) );
		define( 'WSRW_PLUGIN_URL', plugin_dir_url( WSRW_FILE ) );
		define( 'WSRW_PLUGIN_PATH', plugin_dir_path( WSRW_FILE ) );

		$this->version = WSRW_VERSION;
	}

	/**
	 * Require the files needed for the plugin.
	 *
	 * @return void
	 */
	private function includes() {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			require_once WSRW_PLUGIN_PATH . 'includes/class-wsrw-settings.php';
			require_once WSRW_PLUGIN_PATH . 'includes/helpers.php';
			require_once WSRW_PLUGIN_PATH . 'includes/icons.php';
			require_once WSRW_PLUGIN_PATH . 'includes/admin/class-wsrw-admin-page-loader.php';
			require_once WSRW_PLUGIN_PATH . 'includes/admin/admin-scripts.php';
			require_once WSRW_PLUGIN_PATH . 'includes/class-wsrw-search-replace.php';
			require_once WSRW_PLUGIN_PATH . 'includes/class-wsrw-install.php';
			require_once WSRW_PLUGIN_PATH . 'includes/admin/class-wsrw-notice.php';
			require_once WSRW_PLUGIN_PATH . 'includes/admin/class-wsrw-review.php';
		}

		require_once WSRW_PLUGIN_PATH . 'includes/class-wsrw-image-replace.php';

		require_once WSRW_PLUGIN_PATH . 'includes/lite/loader.php';
	}

	/**
	 * Load components in the main plugin instance.
	 *
	 * @return void
	 */
	public function load_components() {
		if ( is_admin() || wp_doing_ajax() || defined( 'DOING_CRON' ) && DOING_CRON ) {
			$this->settings          = new WSRW_Settings();
			$this->admin_page_loader = new WSRW_Admin_Page_Loader();
			$this->notice            = new WSRW_Notice();

			new WSRW_Search_Replace();
		}
	}
}

require_once dirname( __FILE__ ) . '/includes/wsrw.php';

// Kick it off.
wsrw_main();
