<?php
/**
 * Default template part for displaying terms in article media format
 *
 * @since 2.0.0
 */

/** @var array $args */
$args = isset( $args ) ? $args : array();
/** @var WP_Term $term_obj */
$term_obj = ! empty( $args['term_obj'] ) ? $args['term_obj'] : null;
$image_id = ! empty( $args['image_id'] ) ? $args['image_id'] : false;

if ( ! $term_obj ) {
	return;
}

$image_src  = wp_get_attachment_image_url( $image_id, 'ignition_article_media' );
$attachment = wp_prepare_attachment_for_js( $image_id );

$term_link   = get_term_link( $term_obj );
$description = term_description( $term_obj );
?>

<article class="entry-item entry-item-media">
	<?php if ( $image_src ) : ?>
		<figure class="entry-thumb">
			<a href="<?php echo esc_url( $term_link ); ?>">
				<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $attachment['alt'] ); ?>">
			</a>
		</figure>
	<?php endif; ?>

	<div class="entry-item-content-wrap">
		<header class="entry-header">
			<h2 class="entry-title">
				<a href="<?php echo esc_url( $term_link ); ?>"><?php echo wp_kses_post( $term_obj->name ); ?></a>
			</h2>
		</header>

		<?php if ( $description ) : ?>
			<div class="entry-item-excerpt">
				<?php echo wp_kses_post( $description ); ?>
			</div>
		<?php endif; ?>
	</div>
</article>
