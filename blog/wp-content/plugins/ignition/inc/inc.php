<?php
/**
 * Top-level file includes
 *
 * Individual files may include more files themselves, under their respective file hierarchy.
 *
 * @since 1.0.0
 */

add_action( 'plugins_loaded', 'ignition_load_vendor_plugins_support' );
/**
 * Vendor plugins' support.
 *
 * @since 1.0.0
 */
function ignition_load_vendor_plugins_support() {
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/vendor/openweathermap.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/vendor/gutenbee.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/vendor/maxslider.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/vendor/woocommerce/woocommerce.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/vendor/instagram.php';
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/vendor/elementor.php';
}

add_action( 'after_setup_theme', 'ignition_after_setup_theme_includes', 1000 );
/**
 * Includes that need to be done after `after_setup_theme` but before `init`.
 *
 * @since 2.1.0
 */
function ignition_after_setup_theme_includes() {
	/**
	 * Side Header mode.
	 */
	require_once untrailingslashit( IGNITION_DIR ) . '/inc/side-header.php';
}

/**
 * Update notifications.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/class-ignition-updater.php';

/**
 * Data upgrade.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/upgrade.php';

/**
 * Layout information.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/layout.php';

/**
 * Helper functions.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/helpers.php';

/**
 * Standard sanitization functions.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/sanitization.php';

/**
 * Scripts and styles.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/scripts-styles.php';

/**
 * Template files handling.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/template-files.php';

/**
 * Navigation menus.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/nav-menus.php';

/**
 * Sidebars and widgets.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/sidebars-widgets.php';

/**
 * Customizer.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/customizer/customizer.php';

/**
 * Plugin modules.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/modules/modules.php';

/**
 * Shortcodes.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/shortcodes.php';

/**
 * Default hooks.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/default-hooks.php';

/**
 * Template tags.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/template-tags.php';

/**
 * Custom fields.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/custom-fields/custom-fields.php';

/**
 * Term metadata.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/term-meta.php';

/**
 * User metadata.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/user-meta.php';

/**
 * Internationalization / Localization handling.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/localization.php';

/**
 * Reviews / Ratings.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/reviews.php';
require_once untrailingslashit( IGNITION_DIR ) . '/inc/class-ignition-walker-review.php';

/**
 * Ignition blocks.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/blocks/blocks.php';
