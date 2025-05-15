<?php
/**
 * Default template part for displaying events in item format
 *
 * @since 1.0.0
 */

/** @var array $args */
$args                   = isset( $args ) ? $args : array();
$read_more_button_label = ! empty( $args['read-more-button-label'] ) ? $args['read-more-button-label'] : __( 'Learn More', 'ignition' );
?>

<div id="entry-item-<?php the_ID(); ?>" <?php post_class( 'entry-item' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-item-thumb">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'ignition_item' ); ?>
			</a>
		</figure>
	<?php endif; ?>

	<div class="entry-item-content">
		<?php ignition_the_archive_post_title( 'entry-item-title' ); ?>

		<?php ignition_get_template_part( 'template-parts/meta/listing', get_post_type() ); ?>

		<a href="<?php the_permalink(); ?>" class="btn btn-entry-more">
			<?php echo wp_kses_post( $read_more_button_label ); ?>
		</a>
	</div>
</div>
