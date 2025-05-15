<?php
/**
 * The template for displaying single posts and pages
 *
 * @since 1.0.0
 */

get_header();

/**
 * Hook: ignition_before_main.
 *
 * @since 1.0.0
 *
 * @hooked ignition_the_page_title_with_background_section - 10
 * @hooked ignition_the_page_breadcrumb - 20
 */
do_action( 'ignition_before_main' );
?>

<main class="main">

	<?php
	/**
	 * Hook: ignition_main_before.
	 *
	 * @since 1.0.0
	 *
	 * @hooked ignition_the_normal_page_title_section - 10
	 */
	do_action( 'ignition_main_before' );
	?>

	<div class="container">

		<?php
		/**
		 * Hook: ignition_main_container_before.
		 *
		 * @since 1.3.0
		 *
		 * @hooked ignition_module_gsection_wrap_main_container_action_open on 'before_action_ignition_main_container_before' - 10
		 * @hooked ignition_module_gsection_wrap_main_container_action_close on 'after_action_ignition_main_container_before' - 10
		 */
		ignition_do_action( 'ignition_main_container_before' );
		?>

		<div class="row <?php ignition_the_row_classes(); ?>">

			<div id="site-content" class="<?php ignition_the_container_classes(); ?>">

				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>

					<?php ignition_get_template_part( 'template-parts/single/content', get_post_type() ); ?>
				<?php endwhile; ?>

			</div>

			<?php ignition_get_sidebar(); ?>

		</div>

		<?php
		/**
		 * Hook: ignition_main_container_after.
		 *
		 * @since 1.3.0
		 *
		 * @hooked ignition_module_gsection_wrap_main_container_action_open on 'before_action_ignition_main_container_after' - 10
		 * @hooked ignition_module_gsection_wrap_main_container_action_close on 'after_action_ignition_main_container_after' - 10
		 */
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

get_footer();
