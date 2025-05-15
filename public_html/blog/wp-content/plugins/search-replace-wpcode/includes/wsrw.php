<?php

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the main instance of WSRW.
 *
 * @return WSRW_Main
 */
function wsrw_main() {// phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return WSRW_Main::instance();
}
