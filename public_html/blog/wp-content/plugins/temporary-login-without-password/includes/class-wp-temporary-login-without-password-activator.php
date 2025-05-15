<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Wp_Temporary_Login_Without_Password_Activator' ) ) {
/**
 * Activator Class
 *
 * @package Temporary Login Without Password
 */

/**
 * Class Wp_Temporary_Login_Without_Password_Activator
 *
 * @package Temporary Login Without Password
 */
	class Wp_Temporary_Login_Without_Password_Activator {

		/**
		 * Activate Plugin.
		 *
		 * @since 1.0.0
		 */
		public static function activate() {

			/**
			 * Process
			 *
			 * - Get the previously added temporary logins data from temporary_logins_data option if available
			 * - Update user role for Temporary User if user exists into the system
			 * - Set temporary_logins_data option as empty
			 * - Set activation timestamp
			 * - Set plugin version
			 */
			$temporary_logins_data = get_option( 'temporary_logins_data', array() );

			if ( count( $temporary_logins_data ) > 0 ) {
				foreach ( $temporary_logins_data as $user_id => $user_role ) {
					wp_update_user( array(
					'ID'   => $user_id,
					'role' => $user_role,
					) );
				}
			}

			$autoload = 'yes';

			update_option( 'temporary_logins_data', array(), $autoload );
			update_option( 'tlwp_plugin_activation_time', time(), $autoload );
			update_option( 'tlwp_plugin_version', WTLWP_PLUGIN_VERSION, $autoload );

		/**
		 *  Create one-click temp user (only once)
		 */
			$one_click_status = get_option( 'wtlwp_one_click_user_status' );
			if ( false === $one_click_status || 'pending' === $one_click_status ) {
				update_option( 'wtlwp_one_click_user_status', 'pending' );
			}


		}
	}
}
