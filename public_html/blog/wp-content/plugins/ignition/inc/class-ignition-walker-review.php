<?php
/**
 * Comment API: Ignition_Walker_Review class
 *
 * @since 2.0.0
 */

/**
 * Displays a review along with its rating.
 *
 * html5_comment() copied from class Walker_Comment as it appears on WordPress v5.7.2
 *
 * @since 2.0.0
 *
 * @see Walker_Comment
 */
class Ignition_Walker_Review extends Walker_Comment {

	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @since 2.0.0
	 * @since WP 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param int        $depth   Depth of the current comment.
	 * @param array      $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		$rating = get_comment_meta( get_comment_ID(), 'review_rating', true );

		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

		$commenter          = wp_get_current_commenter();
		$show_pending_links = ! empty( $commenter['comment_author'] );

		if ( $commenter['comment_author_email'] ) {
			$moderation_note = __( 'Your review is awaiting moderation.', 'ignition' );
		} else {
			$moderation_note = __( 'Your review is awaiting moderation. This is a preview; your review will be visible after it has been approved.', 'ignition' );
		}
		?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php
						if ( 0 != $args['avatar_size'] ) {
							echo get_avatar( $comment, $args['avatar_size'] );
						}
						?>
						<?php
						$comment_author = get_comment_author_link( $comment );

						if ( '0' == $comment->comment_approved && ! $show_pending_links ) {
							$comment_author = get_comment_author( $comment );
						}

						printf(
							/* translators: %s: Comment author link. */
							__( '%s <span class="says">says:</span>', 'ignition' ),
							sprintf( '<b class="fn">%s</b>', $comment_author )
						);
						?>
					</div><!-- .comment-author -->

					<div class="comment-metadata">
						<?php if ( $rating ) : ?>
							<span class="ignition-star-rating ignition-star-rating-<?php echo esc_attr( $rating ); ?>">
								<span class="ignition-star-rating-inner"></span>
							</span>
						<?php endif; ?>

						<?php
						printf(
							'<a href="%s"><time datetime="%s">%s</time></a>',
							esc_url( get_comment_link( $comment, $args ) ),
							get_comment_time( 'c' ),
							sprintf(
								/* translators: 1: Comment date, 2: Comment time. */
								__( '%1$s at %2$s', 'ignition' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						);

						edit_comment_link( __( 'Edit', 'ignition' ), ' <span class="edit-link">', '</span>' );
						?>
					</div><!-- .comment-metadata -->

					<?php if ( '0' == $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation"><?php echo $moderation_note; ?></em>
					<?php endif; ?>
				</footer><!-- .comment-meta -->

				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->

				<?php
				if ( '1' == $comment->comment_approved || $show_pending_links ) {
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<div class="reply">',
								'after'     => '</div>',
							)
						)
					);
				}
				?>
			</article><!-- .comment-body -->
		<?php
	}
}
