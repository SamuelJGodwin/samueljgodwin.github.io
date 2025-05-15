<?php
/**
 * Template part for Ignition Widgets for Elementor's horizontal "media" template (1 column) specifically for posts
 *
 * @since 1.0.0
 */

/** @var array $args */
$args = isset( $args ) ? $args : array();

$image_size = ! empty( $args['image-size'] ) ? $args['image-size'] : 'ignition_article_media';

/**
 * Hook: ignition_before_entry.
 *
 * @since 1.0.0
 */
do_action( 'ignition_before_entry', 'listing', get_the_ID() );
?>

<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry-item entry-item-media' ); ?>>
	<?php ignition_the_post_entry_thumbnail( $image_size ); ?>

	<div class="entry-item-content">
		<?php ignition_the_post_header(); ?>

		<div class="entry-item-excerpt">
			<?php
			if ( function_exists( 'the_advanced_excerpt' ) ) {
				the_advanced_excerpt();
			} else {
				the_excerpt();
			}
			?>
		</div>
	</div>
</article>

<?php
/**
 * Hook: ignition_after_entry.
 *
 * @since 1.0.0
 */
do_action( 'ignition_after_entry', 'listing', get_the_ID() );
