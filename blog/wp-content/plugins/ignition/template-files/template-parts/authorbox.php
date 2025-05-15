<?php
/**
 * Template part for displaying an author box
 *
 * @since 1.0.0
 */

/**
 * Hook: ignition_before_the_post_author_box.
 *
 * @since 1.0.0
 */
do_action( 'ignition_before_the_post_author_box' );
?>

<div class="entry-section">
	<div class="entry-author-box">
		<figure class="entry-author-thumbnail">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 200, get_option( 'avatar_default', 'mystery' ), esc_attr( get_the_author_meta( 'display_name' ) ), array( 'extra_attr' => 'itemprop="image"' ) ); ?>
		</figure>

		<div class="entry-author-desc">
			<div class="entry-author-title-wrap">
				<h4 class="entry-author-title">
					<?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?>
				</h4>

				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="entry-author-archive-link">
					<?php esc_html_e( 'View articles', 'ignition' ); ?>
				</a>
			</div>

			<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<?php echo wp_kses( wpautop( get_the_author_meta( 'description' ) ), ignition_get_allowed_tags() ); ?>
			<?php endif; ?>

			<?php if ( current_theme_supports( 'ignition-user-social-icons' ) ) {
				ignition_the_user_social_icons();
			} ?>
		</div>

	</div>
</div>

<?php
/**
 * Hook: ignition_after_the_post_author_box.
 *
 * @since 1.0.0
 */
do_action( 'ignition_after_the_post_author_box' );
