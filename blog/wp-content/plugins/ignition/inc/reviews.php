<?php
/**
 * Reviews-related functions and definitions
 *
 * @since 2.0.0
 */

// Create the rating interface.
add_action( 'comment_form_logged_in_after', 'ignition_reviews_rating_field' );
add_action( 'comment_form_before_fields', 'ignition_reviews_rating_field' );
/**
 * Outputs the rating control of the review/comment form.
 *
 * @since 2.0.0
 */
function ignition_reviews_rating_field() {
	if ( ! is_singular() || ! post_type_supports( get_post_type(), 'ignition-reviews' ) ) {
		return;
	}

	?>
	<p class="comment-form-star-rating">
		<span class="label"><?php esc_html_e( 'Rate this by clicking on a star below:', 'ignition' ); ?> <span class="required">*</span></span>
		<span class="ignition-star-rating-input">
			<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
				<input type="radio" id="ignition-star-rating-<?php echo esc_attr( $i ); ?>" class="ignition-star-rating-input" name="review_rating" value="<?php echo esc_attr( $i ); ?>" />
				<label for="ignition-star-rating-<?php echo esc_attr( $i ); ?>">
					<span>
						<?php
							/* translators: %d is a number. */
							echo esc_html( sprintf( _n( '%d star', '%d stars', $i, 'ignition' ), $i ) );
						?>
					</span>
				</label>
			<?php endfor; ?>
		</span>
		<input type="hidden" name="post_type" value="<?php echo esc_attr( get_post_type() ); ?>" />
	</p>
	<?php
}

add_filter( 'preprocess_comment', 'ignition_reviews_require_rating' );
/**
 * Validates the review/comment form so that a rating is required.
 *
 * @since 2.0.0
 *
 * @param array $commentdata Comment data.
 *
 * @return array
 */
function ignition_reviews_require_rating( $commentdata ) {
	if ( ! empty( $_POST['post_type'] ) && post_type_supports( $_POST['post_type'], 'ignition-reviews' ) && empty( $_POST['review_rating'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_die(
			wp_kses_post( __( '<strong>Error</strong>: Please select a rating.', 'ignition' ) ),
			wp_kses_post( __( 'Comment Submission Failure', 'ignition' ) ),
			array(
				'back_link' => true,
			)
		);
	}

	return $commentdata;
}

add_filter( 'preprocess_comment', 'ignition_reviews_set_comment_type' );
/**
 * Sets the comment_type of reviews.
 *
 * @since 2.0.0
 *
 * @param array $commentdata Comment data.
 *
 * @return array
 */
function ignition_reviews_set_comment_type( $commentdata ) {
	if ( ! empty( $_POST['post_type'] ) && post_type_supports( $_POST['post_type'], 'ignition-reviews' ) && ! empty( $_POST['review_rating'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$commentdata['comment_type'] = 'ignition_review';
	}

	return $commentdata;
}

add_action( 'comment_post', 'ignition_reviews_save_comment_rating', 10, 3 );
/**
 * Stores the review's rating into the database, and recalculates the post's statistics.
 *
 * @since 2.0.0
 *
 * @param int        $comment_id       The comment ID.
 * @param int|string $comment_approved 1 if the comment is approved, 0 if not, 'spam' if spam.
 * @param array      $commentdata      Comment data.
 */
function ignition_reviews_save_comment_rating( $comment_id, $comment_approved, $commentdata ) {
	if ( ! empty( $_POST['post_type'] ) && post_type_supports( $_POST['post_type'], 'ignition-reviews' ) && ! empty( $_POST['review_rating'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		// Store the rating. This needs to happen always, as the comment may be queued for review.
		$rating = intval( $_POST['review_rating'] ); // phpcs:ignore WordPress.Security.NonceVerification
		update_comment_meta( $comment_id, 'review_rating', $rating );

		if ( 1 === $comment_approved ) {
			$post_id = $commentdata['comment_post_ID'];
			ignition_reviews_update_post_statistics( $post_id );
		}
	}
}

add_action( 'wp_set_comment_status', 'ignition_reviews_wp_set_comment_status', 10, 2 );
/**
 * Recalculates rating statistics when a review's status changes.
 *
 * This handler shouldn't run when $comment_status is 'delete' as the referenced comment has already been deleted, so
 * we can't know which post we need to update the statistics for.
 *
 * @since 2.0.0
 *
 * @param int    $comment_id     Comment ID.
 * @param string $comment_status Current comment status. Possible values include
 *                               'hold', '0', 'approve', '1', 'spam', and 'trash'.
 */
function ignition_reviews_wp_set_comment_status( $comment_id, $comment_status ) {
	if ( 'delete' === $comment_status ) {
		return;
	}

	$comment_id = intval( $comment_id );
	$post       = get_post( get_comment( $comment_id )->comment_post_ID );
	$post_type  = get_post_type( $post );

	if ( post_type_supports( $post_type, 'ignition-reviews' ) ) {
		ignition_reviews_update_post_statistics( $post->ID );
	}
}

add_action( 'deleted_comment', 'ignition_reviews_deleted_comment', 10, 2 );
/**
 * Recalculates rating statistics when a review is permanently deleted.
 *
 * @since 2.0.0
 *
 * @param int        $comment_id The comment ID.
 * @param WP_Comment $comment    The deleted comment.
 */
function ignition_reviews_deleted_comment( $comment_id, $comment ) {
	$comment_id = intval( $comment_id );
	$post       = get_post( $comment->comment_post_ID );
	$post_type  = get_post_type( $post );

	if ( post_type_supports( $post_type, 'ignition-reviews' ) ) {
		ignition_reviews_update_post_statistics( $post->ID );
	}
}

/**
 * Returns the rating text for a rating number.
 *
 * @since 2.0.0
 *
 * @param float $rating
 *
 * @return string
 */
function ignition_reviews_get_average_wording( $rating ) {
	$wording = '';

	if ( $rating < 1.5 ) {
		$wording = get_theme_mod( 'rating_wording_1', _x( 'Bad', 'rating text', 'ignition' ) );
	} elseif ( $rating >= 1.5 && $rating < 2.5 ) {
		$wording = get_theme_mod( 'rating_wording_2', _x( 'Poor', 'rating text', 'ignition' ) );
	} elseif ( $rating >= 2.5 && $rating < 3.5 ) {
		$wording = get_theme_mod( 'rating_wording_3', _x( 'Average', 'rating text', 'ignition' ) );
	} elseif ( $rating >= 3.5 && $rating < 4.5 ) {
		$wording = get_theme_mod( 'rating_wording_4', _x( 'Good', 'rating text', 'ignition' ) );
	} elseif ( $rating >= 4.5 ) {
		$wording = get_theme_mod( 'rating_wording_5', _x( 'Excellent', 'rating text', 'ignition' ) );
	}

	return $wording;
}

/**
 * Recalculates and updates a package's average rating and rating counts.
 *
 * @since 2.0.0
 *
 * @param false|int $post_id
 */
function ignition_reviews_update_post_statistics( $post_id = false ) {
	if ( false === $post_id ) {
		$post_id = get_the_ID();
	}

	$rating = ignition_reviews_get_average_rating( $post_id );
	if ( empty( $rating ) ) {
		$rating = 0;
	}

	update_post_meta( $post_id, 'review_rating_average', $rating );

	$counts = ignition_reviews_get_rating_counts( $post_id );
	update_post_meta( $post_id, 'review_rating_counts', $counts );
}

/**
 * Returns the average rating a of a package.
 *
 * @since 2.0.0
 *
 * @param false|int $post_id
 *
 * @return false|float
 */
function ignition_reviews_get_average_rating( $post_id = false ) {
	if ( false === $post_id ) {
		$post_id = get_the_ID();
	}

	$comments = get_approved_comments( $post_id );

	if ( $comments ) {
		$reviews_count = 0;
		$rating_sum    = 0;
		foreach ( $comments as $comment ) {
			$rating = intval( get_comment_meta( $comment->comment_ID, 'review_rating', true ) );
			if ( $rating > 0 ) {
				$reviews_count++;
				$rating_sum += $rating;
			}
		}

		if ( 0 === $reviews_count ) {
			return false;
		} else {
			return round( $rating_sum / $reviews_count, 1 );
		}
	} else {
		return false;
	}
}

/**
 * Returns a package's rating counts.
 *
 * @since 2.0.0
 *
 * @param false|int $post_id
 *
 * @return int[]
 */
function ignition_reviews_get_rating_counts( $post_id = false ) {
	if ( false === $post_id ) {
		$post_id = get_the_ID();
	}

	$comments = get_approved_comments( $post_id );

	$counts = array(
		'5' => 0,
		'4' => 0,
		'3' => 0,
		'2' => 0,
		'1' => 0,
	);

	if ( $comments ) {
		foreach ( $comments as $comment ) {
			$rate = intval( get_comment_meta( $comment->comment_ID, 'review_rating', true ) );
			if ( $rate > 0 ) {
				$counts[ (string) $rate ]++;
			}
		}
	}

	return $counts;
}
