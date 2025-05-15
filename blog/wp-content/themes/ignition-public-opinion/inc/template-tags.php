<?php
/**
 * Custom template tags and hooks
 *
 * @since 1.0.0
 */

add_action( 'ignition_after_header', 'ignition_public_opinion_the_news_ticker', 10, 2 );

remove_action( 'ignition_the_post_header', 'ignition_the_post_entry_meta', 20 );

add_action( 'ignition_the_post_header', 'ignition_public_opinion_the_post_entry_meta_top', 5 );
add_action( 'ignition_the_post_header', 'ignition_public_opinion_the_post_entry_author', 20 );

add_action( 'ignition_public_opinion_the_post_entry_meta_top', 'ignition_public_opinion_the_post_entry_category', 10 );
add_action( 'ignition_public_opinion_the_post_entry_meta_top', 'ignition_public_opinion_the_post_entry_date', 20 );
add_action( 'ignition_public_opinion_the_post_entry_meta_top', 'ignition_public_opinion_the_post_entry_comments_link', 30 );

add_action( 'ignition_after_single_entry', 'ignition_public_opinion_the_post_navigation', 5 );

add_action( 'ignition_before_related', 'ignition_public_opinion_ignition_before_related' );
add_action( 'ignition_after_related', 'ignition_public_opinion_ignition_after_related' );

/**
 * Displays the news ticker template.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_the_news_ticker() {
	if ( get_theme_mod( 'theme_news_ticker_is_enabled', ignition_public_opinion_ignition_customizer_defaults( 'theme_news_ticker_is_enabled' ) ) ) {
		ignition_get_template_part( 'template-parts/news-ticker' );
	}
}

/**
 * Displays the current post's top row of meta.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_the_post_entry_meta_top() {
	ob_start();

	/**
	 * Hook: ignition_public_opinion_the_post_entry_meta_top hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked ignition_public_opinion_the_post_entry_category - 10
	 * @hooked ignition_public_opinion_the_post_entry_date - 20
	 * @hooked ignition_public_opinion_the_post_entry_comments_link - 30
	 */
	do_action( 'ignition_public_opinion_the_post_entry_meta_top' );

	$html = ob_get_clean();

	if ( trim( $html ) ) {
		$color      = ignition_public_opinion_get_the_post_category_color();
		$style_attr = $color ? sprintf( 'color: %s;', $color ) : '';
		$html       = sprintf( '<div class="entry-meta-top"><div class="entry-meta-top-wrap" style="%s">%s</div></div>', $style_attr, $html );
	}

	/**
	 * Hook: ignition_public_opinion_before_the_post_entry_meta_top.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 */
	do_action( 'ignition_public_opinion_before_the_post_entry_meta_top', $html );

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput

	/**
	 * Hook: ignition_public_opinion_after_the_post_entry_meta_top.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 */
	do_action( 'ignition_public_opinion_after_the_post_entry_meta_top', $html );

}

/**
 * Displays the current post's primary category (if applicable).
 *
 * @since 1.0.0
 */
function ignition_public_opinion_the_post_entry_category() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_categories_is_visible', ignition_customizer_defaults( 'blog_archive_meta_categories_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_categories_is_visible', ignition_customizer_defaults( 'blog_single_meta_categories_is_visible' ) ) )
	) {
		$category = ignition_public_opinion_get_primary_post_term();

		if ( ! empty( $category ) && is_object( $category ) ) {
			?>
			<span class="entry-meta-category">
				<span><a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a></span>
			</span>
			<?php
		}
	}
}

/**
 * Displays the current post's date (if applicable).
 *
 * @since 1.0.0
 */
function ignition_public_opinion_the_post_entry_date() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_date_is_visible', ignition_customizer_defaults( 'blog_archive_meta_date_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_date_is_visible', ignition_customizer_defaults( 'blog_single_meta_date_is_visible' ) ) )
	) {
		/**
		 * Filters the time limit (in seconds) that a human-readable time diff will be displayed as the post's date,
		 * instead of the actual date it was posted.
		 *
		 * @since 1.0.0
		 *
		 * @param int $seconds
		 */
		$human_diff_limit = apply_filters( 'ignition_public_opinion_the_post_entry_date_human_diff_limit', DAY_IN_SECONDS );

		$now       = current_datetime()->getTimestamp();
		$post_time = get_post_timestamp();
		$diff      = (int) abs( $now - $post_time );
		$output    = get_the_date();

		if ( $diff < $human_diff_limit ) {
			$output = sprintf(
				/* translators: %s is an already translated amount of time, e.g. '10 seconds', '1 hour', '3 days', etc. */
				_x( '%s ago', '%s = human-readable time difference', 'ignition-public-opinion' ),
				human_time_diff( $post_time, $now )
			);
		}

		?>
		<time class="entry-time" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
			<?php echo wp_kses_post( $output ); ?>
		</time>
		<?php
	}
}

/**
 * Displays the current post's comments link (if applicable).
 *
 * @since 1.0.0
 */
function ignition_public_opinion_the_post_entry_comments_link() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_comments_is_visible', ignition_customizer_defaults( 'blog_archive_meta_comments_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_comments_is_visible', ignition_customizer_defaults( 'blog_single_meta_comments_is_visible' ) ) )
	) {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			$number = get_comments_number();

			/* translators: 1: Number of comments, 2: Post title. */
			$comments_text = _n( '%1$s <span class="screen-reader-text">comment on %2$s</span>', '%1$s <span class="screen-reader-text">comments on %2$s</span>', $number );
			$comments_text = sprintf( $comments_text, number_format_i18n( $number ), get_the_title() );

			?>
			<span class="entry-meta-info">
				<span class="entry-meta-comment-no">
					<a href="<?php comments_link(); ?>">
						<?php echo wp_kses_post( $comments_text ); ?>
						<span class="ignition-icons ignition-icons-comment"></span>
					</a>
				</span>
			</span>
			<?php
		}
	}
}

/**
 * Displays the current post's author (if applicable).
 *
 * @since 1.0.0
 */
function ignition_public_opinion_the_post_entry_author() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if (
		( ! is_singular() && get_theme_mod( 'blog_archive_meta_author_is_visible', ignition_customizer_defaults( 'blog_archive_meta_author_is_visible' ) ) )
		||
		( is_singular() && get_theme_mod( 'blog_single_meta_author_is_visible', ignition_customizer_defaults( 'blog_single_meta_author_is_visible' ) ) )
	) {
		?>
		<div class="entry-author">
			<?php
				$author_html = sprintf( '<span class="entry-author-name"><a href="%1$s">%2$s</a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					get_the_author()
				);

				echo wp_kses( sprintf(
					/* translators: %s is the post's author name. */
					__( '<span class="entry-author-by">by</span> %s', 'ignition-public-opinion' ),
					$author_html
				), ignition_public_opinion_get_allowed_tags() );
			?>
		</div>
		<?php
	}
}

/**
 * Displays the post navigation (previous/next) template.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_the_post_navigation() {
	$post_type     = get_post_type();
	$post_type_obj = get_post_type_object( $post_type );

	if ( 'post' === $post_type || $post_type_obj->has_archive ) {
		ignition_get_template_part( 'template-parts/navigation', get_post_type() );
	}
}

/**
 * Changes the related posts' title tags to h4.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_ignition_before_related() {
	add_filter( 'ignition_the_archive_post_title_heading_tag', 'ignition_public_opinion_related_posts_title_tag' );
}

/**
 * Reverts the related posts' title tags to the original.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_ignition_after_related() {
	remove_filter( 'ignition_the_archive_post_title_heading_tag', 'ignition_public_opinion_related_posts_title_tag' );
}

/**
 * Returns the heading tag to be used in related posts' titles.
 *
 * @since 1.0.0
 */
function ignition_public_opinion_related_posts_title_tag() {
	return 'h4';
}
