<?php
/**
 * Template part for displaying posts in item format
 *
 * @since 1.0.0
 */
?>

<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry-item' ); ?>>
	<?php ignition_the_post_entry_thumbnail( 'ignition_item' ); ?>

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
