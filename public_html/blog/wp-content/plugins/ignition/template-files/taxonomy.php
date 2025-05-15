<?php
/**
 * The template for the default taxonomy archives fallback
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

		<div id="site-content" class="row <?php ignition_the_row_classes(); ?>">

			<div id="content-row" class="<?php ignition_the_container_classes(); ?>">
				<?php
					if ( have_posts() ) :

						// To change the page layout for a taxonomy archive, e.g. whether it should show a sidebar and which side,
						// filter 'ignition_current_page_layout' and return one of the available values:
						// 'content_sidebar', 'sidebar_content', 'fullwidth', 'fullwidth_boxed', 'fullwidth_narrow'

						/**
						 * Filters the layout for the fallback taxonomy template.
						 *
						 * @since 1.0.0
						 *
						 * @see ignition_blog_archive_posts_layout_type_choices()
						 *
						 * @param string $layout Accepts '1col-horz', '1col', '2col', '3col'. Default '3col'.
						 */
						$layout = apply_filters( 'taxonomy_archive_posts_layout_type', '3col' );

						$classes = array();

						$columns = 1;
						preg_match( '/^\d/', $layout, $matches );
						if ( array_key_exists( 0, $matches ) && intval( $matches[0] ) > 1 ) {
							$columns = intval( $matches[0] );
						}
						$classes[] = "row-columns-{$columns}";

						$template_part = 'template-parts/item';
						if ( '1col-horz' === $layout ) {
							$template_part = 'template-parts/article-media';
						}

						if ( '1col-horz' === $layout ) {

							while ( have_posts() ) : the_post();

								ignition_get_template_part( $template_part, get_post_type() );

							endwhile;

						} else {
							?>
							<div id="content-col" class="row row-items <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

								<?php while ( have_posts() ) : the_post(); ?>

									<div class="<?php echo esc_attr( ignition_get_blog_columns_classes( $columns ) ); ?>">
										<?php ignition_get_template_part( $template_part, get_post_type() ); ?>
									</div>

								<?php endwhile; ?>

							</div>
							<?php
						}

						ignition_posts_pagination();

					else :

						ignition_get_template_part( 'template-parts/article', 'none' );

					endif;
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
