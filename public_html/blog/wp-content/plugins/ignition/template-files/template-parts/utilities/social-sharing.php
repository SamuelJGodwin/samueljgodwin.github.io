<?php
/**
 * Template part for displaying the social sharing icons
 *
 * @since 1.9.0
 */

/** @var array $args */
$_post_id = $args['post_id'];
$params   = $args['params'];
$networks = $args['networks'];

/**
 * Hook: ignition_before_social_share_section.
 *
 * @since 2.0.0
 */
do_action( 'ignition_before_social_share_section' );
?>

<div class="entry-section entry-section-social-share">
	<ul class="list-social-share-icons">
		<?php foreach ( $networks as $network ) : ?>
			<?php
				if ( ! $network['show'] ) {
					continue;
				}

				$attrs = array();
				if ( ! empty( $network['attrs'] ) ) {
					foreach ( $network['attrs'] as $key => $value ) {
						$attrs[] = sprintf( '%s="%s"', $key, esc_attr( $value ) );
					}
				}
			?>
			<li>
				<a
					class="ignition-social-sharing-icon entry-share <?php echo esc_attr( $network['class'] ); ?>"
					href="<?php echo esc_attr( $network['url'] ); ?>"
					<?php echo implode( ' ', $attrs ); ?>
				>
					<span class="<?php echo esc_attr( $network['icon'] ); ?>"></span>
					<span class="sr-only"> <?php echo esc_attr( $network['text'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php
/**
 * Hook: ignition_after_social_share_section.
 *
 * @since 2.0.0
 */
do_action( 'ignition_after_social_share_section' );
