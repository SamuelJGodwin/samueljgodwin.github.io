<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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
		/**
		 * This action is documented in ignition/template-files/singular.php
		 *
		 * @since 1.0.0
		 */
		ignition_do_action( 'ignition_main_container_before' );
		?>

		<div id="site-content" class="row <?php ignition_the_row_classes(); ?>">
			<div id="content-row" class="<?php ignition_the_container_classes(); ?>">
				<?php
					if ( have_posts() ) :

						$layout  = get_theme_mod( 'blog_archive_posts_layout_type', ignition_public_opinion_ignition_customizer_defaults( 'blog_archive_posts_layout_type' ) );
						$classes = array();

						$columns = 1;
						preg_match( '/^\d/', $layout, $matches );
						if ( array_key_exists( 0, $matches ) && intval( $matches[0] ) > 1 ) {
							$columns = intval( $matches[0] );
						}
						$classes[] = "row-columns-{$columns}";

						$template_part = 'template-parts/article';
						if ( '1col-horz' === $layout ) {
							$template_part = 'template-parts/article-media';
						}

						if ( '1col-horz' === $layout ) {

							while ( have_posts() ) :

								the_post();

								ignition_get_template_part( $template_part, get_post_type() );

							endwhile;

						} else {
							?>
							<div id="content-col" class="row row-items <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

								<?php while ( have_posts() ) : ?>

									<?php the_post(); ?>

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
		/**
		 * This action is documented in ignition/template-files/singular.php
		 *
		 * @since 1.0.0
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
