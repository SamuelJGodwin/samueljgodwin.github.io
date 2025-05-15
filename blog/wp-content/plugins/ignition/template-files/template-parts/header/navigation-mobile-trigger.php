<?php
/**
 * Template part for displaying the mobile menu trigger
 *
 * @since 1.0.0
 */

if ( ( ! has_nav_menu( 'menu-1' ) && ! has_nav_menu( 'menu-2' ) ) && ! current_user_can( 'edit_theme_options' ) ) {
	return;
}
?>
<a href="#mobilemenu" class="mobile-nav-trigger">
	<span class="ignition-icons ignition-icons-bars"></span>
	<?php esc_html_e( 'Menu', 'ignition' ); ?>
</a>
