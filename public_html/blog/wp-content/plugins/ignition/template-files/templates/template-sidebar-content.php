<?php
/**
 * Template Name: Sidebar / Content
 * Template Post Type: post, page, ignition-accommodati, ignition-discography, ignition-event, ignition-podcast, ignition-portfolio, ignition-service, ignition-team, ignition-property
 *
 * @since 1.0.0
 */

get_header(); ?>

<?php
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
		/** This action is documented in template-files/singular.php */
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

get_footer();
