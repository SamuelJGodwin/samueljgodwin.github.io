<?php

/**
 * Plugin Name: My Simple Space
 * Version: 1.2.9
 * Plugin URI: https://idoweb.work/resources/plugins-themes/
 * Description: Shows the diskspace and memory usage of your site.
 * Author: Michael Mann
 * Author URI: https://idoweb.work
 * License: GPL v2
 * Text-domain: my-simple-space
 * Domain Path: /languages

 * Copyright (C) 2015, Michael Mann - support@idoweb.work

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation version 2.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.

**/

if( is_admin() ) {

	class SimpleSpace {

		 // Setup the environment for the plugin
		 public function bootstrap() {
		}

		// @TODO Add class constructor description.
		function __construct() {

			// Hook into the 'wp_dashboard_setup' action to register our other functions
			add_action( 'wp_dashboard_setup', array( $this, 'mss_widget' ) );
			add_action( 'in_admin_footer', array( $this, 'mss_footer' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'mss_admin_css' ) );

		}

		// Dashboard Widget
		function mss_widget() {

			add_meta_box(
				'simple_space_widget',
				__( 'My Simple Space', 'my-simple-space' ),
				'mss_dashboard_widget',
				'dashboard',
				'side',
				'high'
			);

		}

		public function mss_admin_css() {
			wp_register_style(
				'simple_space_admin',
				plugin_dir_url( __FILE__ ) . 'space.css',
				false,
				'1.2.9'
			);
		    wp_enqueue_style( 'simple_space_admin' );
		}

		function mss_footer( $memory ) {

			$memory = mss_get_memory();

			echo '&nbsp;|&nbsp;<span id="my-simple-memory"><span class="spacedark">' . __( 'Total Memory', 'my-simple-space' ) . ':</span> ' . $memory[ 'memory_limit' ] . '&nbsp;&nbsp; <span class="spacedark">' . __( 'Used', 'my-simple-space' ) . ':</span> ' . size_format( $memory[ 'memory_usage' ], 2 );

		}

	}

	// Check if Screen id equals dashboard
	add_action( 'current_screen', 'mss_check_screen' );

	function mss_check_screen() {

		$current_screen = get_current_screen();

		// Limit Widget to Dashboard
		if( $current_screen->id === "dashboard" ) {

			global $simplespace;
			$simplespace = new SimpleSpace();
			$simplespace->bootstrap();

		}
		
	}


	function mss_get_memory() {

		$memory[ 'memory_limit' ] = ini_get( 'memory_limit' );
		$memory[ 'memory_usage' ] = function_exists( 'memory_get_usage' ) ? round( memory_get_usage(), 2 ) : 0;

		return $memory;

	}

	// Create the function to output the contents of our Dashboard Widget
	function mss_dashboard_widget() {

		global $wpdb;
		$dbname = $wpdb->dbname;

		$phpversion = PHP_VERSION;

		$memory = mss_get_memory();
		$memory_limit = $memory[ 'memory_limit' ];
		$memory_usage = $memory[ 'memory_usage' ];

		// Get Memory
		if( !empty( $memory_usage ) && !empty( $memory_limit ) ) {
			$memory_percent = round( (int)$memory_usage / (int)$memory_limit * 100, 0 );
		}

		// Get Database Size
		$result = $wpdb->get_results( 'SHOW TABLE STATUS', ARRAY_A );
		$rows = count( $result );
		$dbsize = 0;

		if( $wpdb->num_rows > 0 ) {
			foreach( $result as $row ) {
				$dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];
			}
		}

		// PHP version, memory, database size and entire site usage (may include not WP items)
		$topitems = array(
			'PHP Version' => $phpversion . ' '. ( PHP_INT_SIZE * 8 ) . ' ' . __( 'Bit OS', 'my-simple-space' ),
			'Memory' => __( 'Total: ', 'my-simple-space' ) . $memory_limit . ' ' . __( 'Used: ', 'my-simple-space' ) . size_format( $memory_usage, 2 ),
			'Database' => size_format( $dbsize, 2 )
		);

		// Check if WP_CONTENT_DIR outside of base path (ABSPATH)
		if( strpos( WP_CONTENT_DIR, ABSPATH ) !== false ) {
			// WP_CONTENT_DIR in ABSPATH
			$topitems[ 'Entire Site' ] = size_format( mss_dir_size( ABSPATH ), 2 );
		} else{
			// WP_CONTENT_DIR outside ABSPATH
			$topitems[ 'Entire Site' ] = size_format( mss_dir_size( ABSPATH ) + mss_dir_size( WP_CONTENT_DIR ), 2 );
		}

		foreach( $topitems as $name => $value ) {
			echo '<p class="halfspace"><span class="spacedark">' . $name . '</span>: ' . $value . '</p>';
		}

		echo '<div class="halfspace">
	<p><span class="spacedark">' . __( 'Contents', 'my-simple-space' ) . '</span></p>';

		$uploads = wp_get_upload_dir(); 	// Get upload directory array without creating it

		// WP Content and selected subfolders
		$contents = array(
			"wp-content" => WP_CONTENT_DIR,
			"plugins" => WP_PLUGIN_DIR,
			"themes" => get_theme_root(),
			"uploads" => $uploads[ 'basedir' ],
		);

		foreach( $contents as $name => $value ) {

			$name = __( $name, 'my-simple-space' ); // Make translatable
			if( false === ( get_transient( $value ) ) )
				echo '<span class="spacedark">' . $name . '</span>: ' . size_format( mss_dir_size( $value ), 2 ) . '<br />';
			else
				echo '<span class="spacedark">' . $name . '</span>: ' . size_format( get_transient( $value ), 2 ) . '<br />';

		}

		echo '</div>';

		// WordPress Admin and Includes folders

		$wpadmin = parse_url( get_admin_url() );
		$wpadmin = ABSPATH . ltrim( $wpadmin[ 'path' ], '/' );
		$wpincludes = parse_url( includes_url() );
		$wpincludes = ABSPATH . ltrim( $wpincludes[ 'path' ], '/' );

		echo '<div class="halfspace">
	<p><b>Other WP Folders</b></p>';

		// wp-admin and wp-includes folders
		$folders = array(
			"wp-admin" => $wpadmin,
			"wp-includes" => $wpincludes
		);

		foreach( $folders as $name => $value ) {

			$name = __( $name, 'my-simple-space' ); // Make translatable

			if( false === ( get_transient( $value ) ) )
				echo '<span class="spacedark">' . $name . '</span>: ' . size_format( mss_dir_size( $value ), 2 ) . '<br />';
			else
				echo '<span class="spacedark">' . $name . '</span>: ' . size_format( get_transient( $value ), 2 ) . '<br />';

		}

		echo '</div>';

	}

	function mss_dir_size( $path ) {

		// Add trailing slash to path if missing
		if( substr( $path, -1 ) != '/' ) $path .= '/';

		if( false === ( $total_size = get_transient( $path ) ) ) {

			$total_size = 0;
			foreach( new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $path, \FilesystemIterator::SKIP_DOTS ) ) as $file ) {
				$total_size += $file->getSize();
			}

			// Set transient, expires in 1 hour
			set_transient( $path, $total_size, 1 * HOUR_IN_SECONDS );

			return $total_size;

		} else {

			return $total_size;
		}

	}

}

?>
