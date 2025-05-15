<?php
/**
 * Template part for displaying the main navigation area (main menu)
 *
 * @since 1.0.0
 */

wp_nav_menu( array(
	'theme_location'  => 'menu-1',
	'fallback_cb'     => 'ignition_main_menu_fallback',
	'menu_id'         => 'header-menu-1',
	'menu_class'      => 'navigation-main',
	'container'       => 'nav',
	'container_class' => 'nav',
) );
