<?php
/**
 * The template for the main sidebar
 *
 * @since 1.0.0
 */

$info = ignition_get_layout_info();

if ( ! $info['has_sidebar'] ) {
	return;
}
?>
<div class="<?php ignition_the_sidebar_classes(); ?>">
	<div class="sidebar">
		<?php if ( ! function_exists( 'ignition_gsection_do_location' ) || ! ignition_gsection_do_location( 'sidebar' ) ) {
			/**
			 * Hook: ignition_sidebar_before.
			 *
			 * @since 1.0.0
			 *
			 * @hooked ignition_the_sidebar_image_metadata - 10
			 */
			do_action( 'ignition_sidebar_before' );

			dynamic_sidebar( $info['sidebar_id'] );

			/**
			 * Hook: ignition_sidebar_after.
			 *
			 * @since 1.0.0
			 */
			do_action( 'ignition_sidebar_after' );
		} ?>
	</div>
</div>
