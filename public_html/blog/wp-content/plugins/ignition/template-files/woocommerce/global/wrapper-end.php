<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
		<?php
		/** This action is documented in template-files/singular.php */
		ignition_do_action( 'ignition_main_container_after' );
		?>

	</div>

	<?php
	/**
	 * Hook: ignition_main_after.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_main_after' );
	?>

</main>

<?php
/**
 * Hook: ignition_after_main.
 *
 * @since 1.0.0
 */
do_action( 'ignition_after_main' );
