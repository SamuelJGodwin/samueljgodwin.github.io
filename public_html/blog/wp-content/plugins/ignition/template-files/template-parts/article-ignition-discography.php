<?php
/**
 * Default template part for displaying discography items in article format
 *
 * @since 1.1.0
 */

/** @var array $args */
$args                   = isset( $args ) ? $args : array();
$read_more_button_label = ! empty( $args['read-more-button-label'] ) ? $args['read-more-button-label'] : __( 'Read More', 'ignition' );
?>

<?php
/** This action is documented in template-files/template-parts/article.php */
do_action( 'ignition_before_entry', 'listing', get_the_ID() );
?>

<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry-item' ); ?>>
	<?php ignition_the_post_entry_thumbnail(); ?>

	<div class="entry-item-content-wrap">
		<?php ignition_the_post_header(); ?>

		<div class="entry-item-excerpt">
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="btn entry-more-btn">
			<?php echo wp_kses_post( $read_more_button_label ); ?>
		</a>
	</div>
</article>

<?php
/** This action is documented in template-files/template-parts/article.php */
do_action( 'ignition_after_entry', 'listing', get_the_ID() );
