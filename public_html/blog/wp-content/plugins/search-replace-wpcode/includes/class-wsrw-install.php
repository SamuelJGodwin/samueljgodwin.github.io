<?php
/**
 * Logic to run on plugin install.
 *
 * @package Search_Replace_WPCode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPCode_Install.
 */
class WSRW_Install {

	/**
	 * WSRW_Install constructor.
	 */
	public function __construct() {
		register_activation_hook( WSRW_FILE, array( $this, 'activate' ) );
		add_action( 'admin_init', array( $this, 'maybe_run_install' ) );
	}

	/**
	 * Activation hook.
	 *
	 * @return void
	 */
	public function activate() {
		// Use an action to have a single activation hook plugin-wide.
		do_action( 'wsrw_plugin_activation' );

		set_transient( 'wsrw_just_activated', class_exists( 'WSRW_License' ) ? 'pro' : 'lite', 60 );
	}

	/**
	 * Install routine to run on plugin activation.
	 * Runs on admin_init so that we also handle updates.
	 * The ihaf_activated option was used by IHAF 1.6 and the key "lite" is for the activation date
	 * of that version of the plugin. In the WPCode plugin we use the "wpcode" key, so we have the update date
	 * and install the demo data.
	 *
	 * @return void
	 */
	public function maybe_run_install() {
		if ( ! is_admin() ) {
			return;
		}

		$activated = get_option( 'wsrw_activated', array() );

		if ( ! is_array( $activated ) ) {
			$activated = array();
		}

		if ( empty( $activated['wsrw'] ) ) {
			$activated['wsrw'] = time();

			update_option( 'wsrw_activated', $activated );

			do_action( 'wsrw_install' );
		}

		// Maybe run manually just one time.
		$install = get_option( 'wsrw_install', false );

		if ( ! empty( $install ) ) {
			$this->activate();
			delete_option( 'wsrw_install' );
		}
	}
}

new WSRW_Install();
