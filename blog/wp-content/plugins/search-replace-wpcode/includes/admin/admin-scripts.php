<?php
/**
 * Load scripts for the admin area.
 *
 * @package WPCode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_enqueue_scripts', 'wsrw_admin_scripts' );

/**
 * Load admin scripts here.
 *
 * @return void
 */
function wsrw_admin_scripts() {

	$current_screen = get_current_screen();

	if ( ! isset( $current_screen->id ) || false === strpos( $current_screen->id, 'wsrw-search-replace' ) ) {
		return;
	}

	$admin_asset_file = WSRW_PLUGIN_PATH . 'build/admin.asset.php';

	if ( ! file_exists( $admin_asset_file ) ) {
		return;
	}

	$asset = require $admin_asset_file;

	wp_enqueue_style( 'wsrw-admin-css', WSRW_PLUGIN_URL . 'build/admin.css', null, $asset['version'] );

	wp_enqueue_script( 'wsrw-admin-js', WSRW_PLUGIN_URL . 'build/admin.js', $asset['dependencies'], $asset['version'], true );

	wp_localize_script(
		'wsrw-admin-js',
		'wsrwjs',
		apply_filters(
			'wsrw_admin_js_data',
			array(
				'nonce'             => wp_create_nonce( 'wsrw_admin' ),
				'yes'               => esc_html__( 'Yes', 'search-replace-wpcode' ),
				'no'                => esc_html__( 'No', 'search-replace-wpcode' ),
				'ok'                => esc_html__( 'OK', 'search-replace-wpcode' ),
				'close'             => esc_html__( 'Close', 'search-replace-wpcode' ),
				'error_title'       => esc_html__( 'Error', 'search-replace-wpcode' ),
				'please_wait'       => esc_html__( 'Please Wait', 'search-replace-wpcode' ),
				'upgrade_to_pro'    => esc_html__( 'Upgrade to Pro', 'search-replace-wpcode' ),
				'check_row_title'   => esc_html__( 'Replacing individual items is a Pro feature', 'search-replace-wpcode' ),
				'check_row_content' => esc_html__( 'Upgrade to Search & Replace Everything Pro today and choose exactly which rows you want to replace from the results without having to do complex queries.', 'search-replace-wpcode' ),
				'check_row_url'     => esc_url( wsrw_utm_url( 'https://wpcode.com/srlite/', 'search-preview', 'check-row' ) ),
				'row_info_title'    => esc_html__( 'Full Row Info is a Pro Feature', 'search-replace-wpcode' ),
				'row_info_content'  => esc_html__( 'Upgrade to Search & Replace Everything Pro today and view the full row information and easily trace back results to the original content without having to leave the admin.', 'search-replace-wpcode' ),
				'row_info_url'      => esc_url( wsrw_utm_url( 'https://wpcode.com/srlite/', 'search-preview', 'row-info' ) ),
				'upgrade_bonus'     => wpautop(
					wp_kses(
						__( '<strong>Bonus:</strong> Search & Replace Everything Lite users get <span>$30 off</span> regular price, automatically applied at checkout.', 'search-replace-wpcode' ),
						[
							'strong' => [],
							'span'   => [],
						]
					)
				),
				'lock_icon'         => wsrw_get_icon( 'lock', '22', '28', '0 0 22 28' ),
			)
		)
	);
}
