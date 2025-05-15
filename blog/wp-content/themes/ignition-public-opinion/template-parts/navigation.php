<?php
/**
 * Template part for displaying the post navigation (previous/next)
 *
 * @since 1.0.0
 */

$previous_post       = get_adjacent_post( false, '', true );
$next_post           = get_adjacent_post( false, '', false );
$has_post_navigation = $previous_post || $next_post;

/**
 * Hook: ignition_before_the_post_navigation.
 *
 * @since 1.0.0
 *
 * @param bool $has_post_navigation
 */
do_action( 'ignition_before_the_post_navigation', $has_post_navigation );

if ( $previous_post || $next_post ) {
	?>
	<div class="entry-section">
		<div class="entry-navigation">

			<?php if ( $previous_post ) : ?>
				<a href="<?php echo esc_url( get_permalink( $previous_post ) ); ?>" class="entry-prev">
					<span><?php esc_html_e( 'Previous Article', 'ignition-public-opinion' ); ?></span>
					<p class="entry-navigation-title"><?php echo wp_kses( get_the_title( $previous_post ), ignition_public_opinion_get_allowed_tags( 'guide' ) ); ?></p>
				</a>
			<?php endif; ?>

			<?php if ( $next_post ) : ?>
				<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="entry-next">
					<span><?php esc_html_e( 'Next Article', 'ignition-public-opinion' ); ?></span>
					<p class="entry-navigation-title"><?php echo wp_kses( get_the_title( $next_post ), ignition_public_opinion_get_allowed_tags( 'guide' ) ); ?></p>
				</a>
			<?php endif; ?>

		</div>
	</div>
	<?php
}
/**
 * Hook: ignition_after_the_post_navigation.
 *
 * @since 1.0.0
 *
 * @param bool $has_post_navigation
 */
do_action( 'ignition_after_the_post_navigation', $has_post_navigation );
