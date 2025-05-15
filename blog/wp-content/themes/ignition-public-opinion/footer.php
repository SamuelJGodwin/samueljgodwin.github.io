<?php
/**
 * The template for displaying the footer
 *
 * @since 1.0.0
 */
?>
	</div> <!-- .site-content-wrap -->

	<?php if ( ! function_exists( 'ignition_gsection_do_location' ) || ! ignition_gsection_do_location( 'footer' ) ) {
		/**
		 * Hook: ignition_footer.
		 *
		 * @since 1.0.0
		 *
		 * @hooked ignition_footer - 10
		 */
		do_action( 'ignition_footer' );
	} ?>

</div> <!-- .page-wrap -->

<?php
/**
 * Hook: ignition_global_after.
 *
 * @since 1.0.0
 *
 * @hooked ignition_the_mobile_navigation - 100
 */
do_action( 'ignition_global_after' );
?>

<?php wp_footer(); ?>

</body>
</html>
