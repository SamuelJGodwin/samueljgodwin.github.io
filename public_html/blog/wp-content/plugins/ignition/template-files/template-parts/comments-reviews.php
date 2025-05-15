<?php
/**
 * The template file for displaying the reviews and review form
 *
 * @since 2.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$show_comments = have_comments() || comments_open();

/**
 * Hook: ignition_before_comments.
 *
 * @since 1.0.0
 *
 * @param bool $show_comments
 */
do_action( 'ignition_before_comments', $show_comments ); ?>

<?php if ( $show_comments ) : ?>
	<div class="entry-section">
		<div id="comments" class="comments-area reviews-area">
<?php endif; ?>

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php comments_number( __( 'No Reviews', 'ignition' ), __( '1 Review', 'ignition' ), __( '% Reviews', 'ignition' ) ); ?>
		</h3><!-- .comments-title -->

		<?php
			$rating_counts  = get_post_meta( get_the_ID(), 'review_rating_counts', true );
			$rating_average = get_post_meta( get_the_ID(), 'review_rating_average', true );
			$total_ratings  = 0;

			if ( ! empty( $rating_counts ) && is_array( $rating_counts ) ) {
				foreach ( $rating_counts as $rating => $count ) {
					$total_ratings += $count;
				}
			}
		?>

		<?php if ( $total_ratings > 0 ) : ?>
			<div class="ratings-board">
				<div class="ratings-board-rows">
					<?php foreach ( $rating_counts as $rating => $count ) : ?>
						<?php
							$rating     = intval( $rating );
							$percentage = round( ( 100 / $total_ratings ) * $count, 1 );
						?>
						<div class="ratings-board-row">
							<span class="ignition-star-rating ignition-star-rating-<?php echo esc_attr( $rating ); ?>">
								<span class="ignition-star-rating-inner"></span>
							</span>
							<span class="star-count">(<?php echo esc_html( $count ); ?>)</span>
							<div class="ratings-bar">
								<div class="ratings-bar-inner" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="ratings-board-score">
					<?php $average_percentage = round( ( 100 / 5 ) * $rating_average, 1 ); ?>
					<span class="ignition-star-rating">
						<span class="ignition-star-rating-inner" style="width: <?php echo esc_attr( $average_percentage ); ?>%;"></span>
					</span>
					<span class="ratings-score"><?php echo esc_html( $rating_average ); ?></span>
					<span class="ratings-score-label"><?php echo esc_html( ignition_reviews_get_average_wording( $rating_average ) ); ?></span>
				</div>
			</div>
		<?php endif; ?>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'type'        => 'ignition_review',
					'walker'      => new Ignition_Walker_Review(),
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 78,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_navigation(); ?>

	<?php endif; // Check for have_comments(). ?>

	<?php // If comments are closed and there are comments, let's leave a little note, shall we? ?>
	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'ignition' ); ?></p>
	<?php endif; ?>

	<?php comment_form( array(
		'title_reply'  => __( 'Your Review', 'ignition' ),
		'label_submit' => __( 'Submit Review', 'ignition' ),
	) ); ?>

<?php if ( $show_comments ) : ?>
		</div><!-- .entry-section -->
	</div><!-- #comments -->
<?php endif;

/**
 * Hook: ignition_after_comments.
 *
 * @since 1.0.0
 *
 * @param bool $show_comments
 */
do_action( 'ignition_after_comments', $show_comments );
