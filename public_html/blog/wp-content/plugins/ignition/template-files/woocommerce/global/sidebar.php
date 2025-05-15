<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/sidebar.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$info = ignition_get_layout_info();

if ( ! $info['has_sidebar'] ) {
	return;
}

if ( is_product() ) {
	ignition_get_sidebar( 'shop' );
} else {
	?>
	<div class="<?php ignition_the_sidebar_classes(); ?>">
		<div class="sidebar sidebar-drawer with-drawer">
			<div class="sidebar-drawer-header">
				<a href="#" class="sidebar-dismiss">&times; <span class="screen-reader-text"><?php esc_html_e( 'Close drawer', 'ignition' ); ?></span></a>
			</div>

			<div class="sidebar-drawer-content custom-scrollbar">
				<?php if ( ! function_exists( 'ignition_gsection_do_location' ) || ! ignition_gsection_do_location( 'sidebar' ) ) {
					/** This action is documented in template-files/sidebar.php */
					do_action( 'ignition_sidebar_before' );

					dynamic_sidebar( 'shop' );

					/** This action is documented in template-files/sidebar.php */
					do_action( 'ignition_sidebar_after' );
				} ?>
			</div>
		</div>
	</div>
	<?php
}
