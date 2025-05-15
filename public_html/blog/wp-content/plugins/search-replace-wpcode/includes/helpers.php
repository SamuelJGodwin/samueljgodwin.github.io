<?php
/**
 * Helper functions.
 *
 * @package Search_Replace_WPCode
 */

/**
 * Get a URL with UTM parameters.
 *
 * @param string $url The URL to add the params to.
 * @param string $medium The marketing medium.
 * @param string $campaign The campaign.
 * @param string $ad_content The utm_content param.
 *
 * @return string
 */
function wsrw_utm_url( $url, $medium = '', $campaign = '', $ad_content = '' ) {
	$args = array(
		'utm_source'   => class_exists( 'WSRW_License' ) ? 'wsrwpro' : 'wsrwlite',
		'utm_medium'   => sanitize_key( $medium ),
		'utm_campaign' => sanitize_key( $campaign ),
	);

	if ( ! empty( $ad_content ) ) {
		$args['utm_content'] = sanitize_key( $ad_content );
	}

	return add_query_arg(
		$args,
		$url
	);
}


/**
 * Check WP version and include the compatible upgrader skin.
 */
function wsrw_require_upgrader() {

	global $wp_version;

	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	// WP 5.3 changes the upgrader skin.
	if ( version_compare( $wp_version, '5.3', '<' ) ) {
		require_once WSRW_PLUGIN_PATH . 'includes/admin/class-wsrw-skin-legacy.php';
	} else {
		require_once WSRW_PLUGIN_PATH . 'includes/admin/class-wsrw-skin.php';
	}
}
