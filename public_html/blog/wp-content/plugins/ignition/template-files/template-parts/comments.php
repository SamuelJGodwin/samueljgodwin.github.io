<?php
/**
 * The template file for displaying the comments and comment form
 *
 * This file may be included by a theme's comments.php to display the standard Ignition comments template.
 * It must not be placed in ignition/template-files/comments.php (therefore requesting it from the theme as
 * `ignition_get_template_part( 'comments' )` as the function will pick the theme's comments.php first, resulting
 * in an endless loop.
 *
 * @since 1.0.0
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
		<div id="comments" class="comments-area">
<?php endif; ?>

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php comments_number(); ?>
		</h3><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
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

	<?php comment_form(); ?>

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
