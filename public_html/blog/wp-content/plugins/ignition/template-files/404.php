<?php
/**
 * The template for displaying 404 pages (not found)
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
		/** This action is documented in template-files/singular.php */
		ignition_do_action( 'ignition_main_container_before' );
		?>

		<div class="row <?php ignition_the_row_classes(); ?>">

			<div id="site-content" class="<?php ignition_the_container_classes(); ?>">

				<?php
				/** This action is documented in template-files/template-parts/article.php */
				do_action( 'ignition_before_entry', 'main', false );
				?>

				<article class="entry error-404 not-found">

					<h1 class="entry-title">
						<?php esc_html_e( "Oops! The page can't be found.", 'ignition' ); ?>
					</h1>

					<div class="entry-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Navigate our website or try searching instead.', 'ignition' ); ?></p>

						<?php get_search_form(); ?>
					</div>

				</article>

				<?php
				/** This action is documented in template-files/template-parts/article.php */
				do_action( 'ignition_after_entry', 'main', false );
				?>

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
