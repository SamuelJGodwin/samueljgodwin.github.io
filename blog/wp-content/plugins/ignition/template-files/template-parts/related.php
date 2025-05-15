<?php
/**
 * Template part for displaying a related posts section
 *
 * @since 1.0.0
 */

$post_type = get_post_type();
$columns   = (int) get_theme_mod( 'blog_single_related_columns', ignition_customizer_defaults( 'blog_single_related_columns' ) );

/**
 * Filters the number of related columns.
 *
 * @since 1.0.0
 *
 * @param int    $columns
 * @param string $post_type
 */
$columns = apply_filters( 'ignition_related_columns', $columns, $post_type );

if ( $columns < 1 ) {
	return;
}

$count = $columns;

/**
 * Filters the number of related posts.
 *
 * By default, the number of related posts matches the number of columns.
 *
 * @since 1.0.0
 *
 * @param int    $count
 * @param string $post_type
 */
$related       = ignition_get_related_posts( get_the_ID(), apply_filters( 'ignition_related_count', $count, $post_type ) );
$section_title = __( 'You might be interested in &hellip;', 'ignition' );

/**
 * Hook: ignition_before_related.
 *
 * @since 1.0.0
 */
do_action( 'ignition_before_related', $related, $post_type, $section_title );

if ( $related->have_posts() ) : ?>
	<div class="entry-section">
		<?php if ( $section_title ) : ?>
			<h3 class="entry-section-title"><?php echo esc_html( $section_title ); ?></h3>
		<?php endif; ?>

		<div class="row row-items">
			<?php while ( $related->have_posts() ) : $related->the_post(); ?>
				<div class="<?php echo esc_attr( ignition_get_columns_classes( $columns, 'related' ) ); ?>">
					<?php ignition_get_template_part( 'template-parts/related-item', $post_type ); ?>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
<?php endif;

/**
 * Hook: ignition_after_related.
 *
 * @since 1.0.0
 */
do_action( 'ignition_after_related', $related, $post_type, $section_title );
