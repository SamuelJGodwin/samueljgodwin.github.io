<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @since 1.0.0
 */
?>

<?php
/** This action is documented in template-files/template-parts/article.php */
do_action( 'ignition_before_entry', 'listing', false );
?>

<article class="entry no-results not-found">
	<header class="entry-header">
		<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'ignition' ); ?></h1>
	</header>

	<div class="entry-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php
				/* translators: %1$s is a URL. */
				printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'ignition' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) );
			?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'ignition' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( "It seems we can't find what you're looking for. Perhaps searching can help.", 'ignition' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div>
</article>

<?php
/** This action is documented in template-files/template-parts/article.php */
do_action( 'ignition_after_entry', 'listing', false );
