<?php
/**
 * Template part for displaying the footer section
 *
 * @since 1.0.0
 */

$is_visible = (bool) get_theme_mod( 'footer_is_visible', ignition_customizer_defaults( 'footer_is_visible' ) ); ?>

<?php
/**
 * Hook: ignition_before_footer.
 *
 * @since 1.0.0
 *
 * @param bool $is_visible Whether the footer is visible.
 */
do_action( 'ignition_before_footer', $is_visible );
?>

<?php if ( $is_visible || is_customize_preview() ) : ?>
	<footer class="<?php ignition_the_footer_classes(); ?>">

		<?php
		/**
		 * Hook: ignition_footer_before.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ignition_footer_before' );
		?>

		<?php ignition_get_template_part( 'template-parts/footer/widgets' ); ?>

		<?php ignition_get_template_part( 'template-parts/footer/info' ); ?>

		<?php ignition_get_template_part( 'template-parts/footer/button-top' ); ?>

		<?php
		/**
		 * Hook: ignition_footer_after.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ignition_footer_after' );
		?>

	</footer>
<?php endif; ?>

<?php
/**
 * Hook: ignition_after_footer.
 *
 * @since 1.0.0
 *
 * @param bool $is_visible Whether the footer is visible.
 */
do_action( 'ignition_after_footer', $is_visible );
