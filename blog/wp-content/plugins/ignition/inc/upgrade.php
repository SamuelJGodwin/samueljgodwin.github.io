<?php
/**
 * Data upgrade functions for versioned Ignition releases
 *
 * @since 1.0.0
 */

add_action( 'wp_loaded', 'ignition_register_data_upgrade', 1000 );
/**
 * Registers data upgrade functions to be run on specific plugin versions.
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
function ignition_register_data_upgrade() {
	// ONLY IF DEBUGGING, enable debug log by passing true to Ignition_Data_Upgrade().
	$upgrader = new Ignition_Data_Upgrade();

	$upgrader->register( '1.9.1.0.1', 'ignition_upgrade_migrate_events_1_9_1_0_1' );
	$upgrader->register( '1.9.1.1', 'ignition_upgrade_cleanup_1_9_1_1' );
	$upgrader->register( '2.0.1.1', 'ignition_upgrade_migrate_sticky_2_0_1_1' );
	$upgrader->register( '2.1.3.1', 'ignition_upgrade_init_blog_single_layout_type_2_1_3_1' );

	// This needs to run always, even if there are no upgrade steps, as it also takes care of updating the version in the database.
	$upgrader->maybe_upgrade();
}

/**
 * Theme upgrade library.
 */
require_once untrailingslashit( IGNITION_DIR ) . '/inc/upgrade/upgrade.php';

/**
 * Upgrades data in preparation of Ignition 2.0.0
 *
 * Marks events as non-recurring. The existence of the 'ignition_event_is_recurring' meta key is required for
 * correct event queries and sorting.
 *
 * @since 2.0.0
 *
 * @param CI_Theme_Data_Upgrade_Log $log
 * @param string                    $this_version
 * @param string                    $next_version
 *
 * @return bool
 */
function ignition_upgrade_migrate_events_1_9_1_0_1( $log, $this_version, $next_version ) {
	// Data selection. Events that have not been migrated yet.
	$q = new WP_Query( array(
		'post_type'      => 'ignition-event',
		'meta_key'       => 'migrated_1_9_1_1',
		'meta_compare'   => 'NOT EXISTS',
		'posts_per_page' => 25, // To avoid memory errors (and data corruption), don't fetch too many posts. 10-25 is reasonable.
	) );

	// No more data. Don't repeat this task.
	if ( ! $q->have_posts() ) {
		$repeat = false;
		return $repeat;
	}

	// This message may be displayed to the (backend) user, depending on how long this task runs.
	/* translators: %d is a number of posts. */
	$log->set( sprintf( __( 'Upgrading events. %d remaining...', 'ignition' ), $q->found_posts ) );

	while ( $q->have_posts() ) {
		$q->the_post();

		$custom_keys = get_post_custom_keys( get_the_ID() );
		if ( ! in_array( 'ignition_event_is_recurring', $custom_keys, true ) ) {
			// Mark existing events as not recurrent.
			update_post_meta( get_the_ID(), 'ignition_event_is_recurring', false );
		}

		update_post_meta( get_the_ID(), 'migrated_1_9_1_1', 1 );
	}

	wp_reset_postdata();

	// There may be more posts. Repeat this task.
	$repeat = true;
	return $repeat;
}

/**
 * Cleans up progress data, left by ignition_upgrade_mark_events_for_upgrade_1_9_1_0_1()
 *
 * @since 2.0.0
 *
 * @param CI_Theme_Data_Upgrade_Log $log
 * @param string                    $this_version
 * @param string                    $next_version
 */
function ignition_upgrade_cleanup_1_9_1_1( $log, $this_version, $next_version ) {
	delete_post_meta_by_key( 'migrated_1_9_1_1' );
}

/**
 * Upgrades the sticky menu option in preparation of Ignition 2.0.2
 *
 * @since 2.0.2
 *
 * @param CI_Theme_Data_Upgrade_Log $log
 * @param string                    $this_version
 * @param string                    $next_version
 */
function ignition_upgrade_migrate_sticky_2_0_1_1( $log, $this_version, $next_version ) {
	if ( 1 === get_theme_mod( 'header_layout_menu_is_sticky' ) ) {
		set_theme_mod( 'header_layout_menu_sticky_type', 'shy' );
	} elseif ( 0 === get_theme_mod( 'header_layout_menu_is_sticky' ) ) {
		set_theme_mod( 'header_layout_menu_sticky_type', 'off' );
	}
}

/**
 * Initializes the blog_single_layout_type option to be the same as blog_archive_layout_type for
 * backward compatibility.
 *
 * @since 2.1.4
 *
 * @param CI_Theme_Data_Upgrade_Log $log
 * @param string                    $this_version
 * @param string                    $next_version
 */
function ignition_upgrade_init_blog_single_layout_type_2_1_3_1( $log, $this_version, $next_version ) {
	$archive_layout = get_theme_mod( 'blog_archive_layout_type', ignition_customizer_defaults( 'blog_archive_layout_type' ) );

	if ( $archive_layout !== ignition_customizer_defaults( 'blog_single_layout_type' ) ) {
		set_theme_mod( 'blog_single_layout_type', $archive_layout );
	}
}
