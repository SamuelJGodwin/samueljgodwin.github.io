<?php
/**
 * Data upgrade functions for versioned theme releases
 *
 * @since 1.0.0
 */

add_action( 'wp_loaded', 'ignition_public_opinion_register_data_upgrade', 1000 );
/**
 * Registers data upgrade functions to be run on specific theme versions.
 *
 * In order to repeat a callback, it needs to return true. The callback itself must remember where it left off.
 * Repeating callbacks are re-added to the end of the queue, therefore the order of execution between different
 * callbacks should be considered unpredictable.
 * If you need a repeating callback to finish processing before proceeding to other steps, make sure to have it run
 * in a "previous" version, by itself. For example, going from v1.0 to v1.1 if 'migrate_posts' (that is repeating)
 * needs to complete before 'cleanup_posts' runs, then register it by itself in a non-existent version
 * in between, e.g. v1.0.1
 *
 *    $upgrader->register( '1.0.1', 'migrate_posts' );
 *    $upgrader->register( '1.1', 'cleanup_posts' );
 *
 * Version-less callbacks are run only when there is no version information stored in the database, and then
 * set the version to the current theme version. Therefore, no intermediate version callbacks are executed.
 *
 *    $upgrader->register( '', 'example_migrate_when_versionless' );
 *
 * Example: Latest version is 1.0 and we are about to release 1.1
 * We need to update theme_mods and posts, and clean up after posts.
 * Since the posts migration will be done in batches, we need to wait until all posts are processed. To do that,
 * we register them in a "fake" intermediary version before the target 1.1
 *
 *    $upgrader->register( '1.0.1', 'example_migrate_posts_1_0_1' );
 *    $upgrader->register( '1.1', 'example_migrate_posts_cleanup_1_1' );
 *    $upgrader->register( '1.1', 'example_migrate_mods_1_1' );
 *
 * @since 1.0.0
 */
function ignition_public_opinion_register_data_upgrade() {
	$upgrader = new Ignition_Public_Opinion_Data_Upgrade();

	// This needs to run always, even if there are no upgrade steps, as it also takes care of updating the version in the database.
	$upgrader->maybe_upgrade();
}

/**
 * Theme upgrade library.
 */
require_once get_theme_file_path( '/inc/upgrade/upgrade.php' );
