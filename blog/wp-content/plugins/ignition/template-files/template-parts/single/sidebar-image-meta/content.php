<?php
/**
 * Default template part for displaying the main content of posts/pages, without a featured image
 *
 * @since 1.1.0
 */
?>

<?php
/**
 * Hook: ignition_before_single_entry.
 *
 * @since 1.0.0
 */
do_action( 'ignition_before_single_entry' );
?>

<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

	<div class="entry-content">
		<?php the_content(); ?>

		<?php wp_link_pages(); ?>
	</div>

</article>

<?php
/**
 * Hook: ignition_after_single_entry.
 *
 * @since 1.0.0
 *
 * @hooked ignition_the_social_sharing_icons - 5
 * @hooked ignition_the_post_author_box - 10
 * @hooked ignition_the_post_related_posts - 20
 * @hooked ignition_the_post_comments - 100
*/
do_action( 'ignition_after_single_entry' );
