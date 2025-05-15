<?php
/**
 * Template part for displaying posts in item format
 *
 * @since 1.0.0
 */

/** @var array $args */
$args                   = isset( $args ) ? $args : array();
$read_more_button_label = ! empty( $args['read-more-button-label'] ) ? $args['read-more-button-label'] : __( 'Read More', 'ignition' );
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
		<h4 class="entry-item-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h4>

		<div class="entry-meta">
			<span class="entry-meta-item">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time>
			</span>
		</div>

		<a href="<?php the_permalink(); ?>" class="btn btn-entry-more">
			<?php echo wp_kses_post( $read_more_button_label ); ?>
		</a>
	</div>
</div>
