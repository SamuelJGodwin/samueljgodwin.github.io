<?php
/**
 * Default template part for displaying posts/pages in article format
 *
 * @since 1.0.0
 */

/** @var array $args */
$args                   = isset( $args ) ? $args : array();
$read_more_button_label = ! empty( $args['read-more-button-label'] ) ? $args['read-more-button-label'] : __( 'Read More', 'ignition' );
?>

<?php
/**
 * Hook: ignition_before_entry.
 *
 * @since 1.0.0
 *
 * @param string    $context The context under which the action is triggered.
 *                           E.g. 'listing' in listings, 'main' in 404 where it's part of the main content.
 * @param int|false $post_id Post ID or false if not applicable.
 */
do_action( 'ignition_before_entry', 'listing', get_the_ID() );
?>

<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry-item' ); ?>>
	<?php ignition_the_post_entry_thumbnail(); ?>

	<div class="entry-item-content-wrap">
		<?php ignition_the_post_header(); ?>

		<?php if ( post_type_supports( get_post_type(), 'excerpt' ) && has_excerpt() ) : ?>
			<div class="entry-item-excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>

		<a href="<?php the_permalink(); ?>" class="btn entry-more-btn">
			<?php echo wp_kses_post( $read_more_button_label ); ?>
		</a>
	</div>
</article>

<?php
/**
 * Hook: ignition_after_entry.
 *
 * @since 1.0.0
 *
 * @param string    $context The context under which the action is triggered.
 *                           E.g. 'listing' in listings, 'main' in 404 where it's part of the main content.
 * @param int|false $post_id Post ID or false if not applicable.
 */
do_action( 'ignition_after_entry', 'listing', get_the_ID() );
