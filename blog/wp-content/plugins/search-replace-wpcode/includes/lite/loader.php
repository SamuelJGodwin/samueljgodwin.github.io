<?php
/**
 * File used for importing lite-only files.
 *
 * @package Search_Replace_WPCode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_admin() || wp_doing_ajax() || defined( 'DOING_CRON' ) && DOING_CRON ) {
	// Connect to upgrade.
	require_once WSRW_PLUGIN_PATH . 'includes/lite/admin/class-wsrw-connect.php';
	// Load lite notices.
	require_once WSRW_PLUGIN_PATH . 'includes/lite/admin/notices.php';
}
