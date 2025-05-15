<?php
/**
 * Plugin Name: Disk Usage Sunburst
 * Plugin URI:  https://raidboxes.io/en/disk-usage-sunburst-plugin/
 * Description: Visualizes the size of all directories and files in your WordPress installation.
 * Author:      raidboxes.io
 * Author URI:  https://raidboxes.io
 * Version:     1.1.8
 * License:     GPL2
 * Network:     true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

defined('ABSPATH') or die("Unexpected error: Constant ABSPATH is not defined. This is normally the case in a WordPress environment.");
$RBDUSB_ABSPATH = ABSPATH;

/**
 * Enqueue the scripts that our plugin needs
 * @since    1.1
 */

function rbdusb_scripts() {
    $rbdusb_plugin_page  = 'tools_page_disk-usage-sunburst/rbdusb-disk-usage-sunburst';
    $current_screen = get_current_screen();
    if ( $rbdusb_plugin_page === $current_screen->base ) {
        wp_enqueue_script('rbdusb_d3', plugin_dir_url(__FILE__) . 'js/d3.v3.min.js', array('jquery'), 1.1, true);   // library
        wp_enqueue_script('rbdusb_script', plugin_dir_url(__FILE__) . 'js/rbdusb.js', array('jquery'), 1.1, true);  // our scripts
    }
}

/**
 * Enqueue the styles that our plugin needs
 * @since    1.1
 */

function rbdusb_styles() {
    $rbdusb_plugin_page = 'tools_page_disk-usage-sunburst/rbdusb-disk-usage-sunburst';
    $current_screen = get_current_screen();
    if ( $rbdusb_plugin_page === $current_screen->base ) {
        wp_enqueue_style('rbdusb_css', plugin_dir_url(__FILE__) . 'css/rbdusb.css');
    }
}


/**
 * Begins execution of the plugin.
 * @since    1.1
 */

function rbdusb_init() {

    /**
    * Will only execute if the user has the capability to update the core
    * (On single sites that will be the admin in most cases, on multisites it has to be the superadmin)
    */
    if ((current_user_can('update_core'))) {

        // Add the submenu to the tools page
        add_action('admin_menu', function () {
            add_submenu_page('tools.php', 'Disk Usage', 'Disk Usage', 'administrator', __FILE__, 'rbdusb_action');
        });

        // Load the styles
        add_action('current_screen', 'rbdusb_styles');

        // Load the scripts
        add_action('current_screen', 'rbdusb_scripts');

        // Include the scan
        add_action('wp_ajax_rbdusb_data', function () {
            global $RBDUSB_ABSPATH;
            include(dirname(__FILE__) . '/views/ajax.php');
            wp_die();
        });

        // include the index view
        function rbdusb_action() {
            global $RBDUSB_ABSPATH;
            include(dirname(__FILE__) . '/views/index.php');
        }

    }

}

add_action('init','rbdusb_init');
