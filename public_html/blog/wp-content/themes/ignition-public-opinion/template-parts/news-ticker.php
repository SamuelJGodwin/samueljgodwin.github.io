<?php
/**
 * Template part for the News Ticker
 *
 * @since 1.0.0
 */

$defaults = ignition_public_opinion_ignition_customizer_defaults( 'all' );

$ticker_title = get_theme_mod( 'theme_news_ticker_title', $defaults['theme_news_ticker_title'] );
$term_slug    = get_theme_mod( 'theme_news_ticker_term', $defaults['theme_news_ticker_term'] );
$limit        = get_theme_mod( 'theme_news_ticker_limit', $defaults['theme_news_ticker_limit'] );

$q_args = array(
	'post_type'      => 'post',
	'posts_per_page' => $limit,
);

if ( $term_slug ) {
	$q_args['tax_query'] = array(
		array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => array( $term_slug ),
		),
	);
}

$q = new WP_Query( $q_args );

if ( ! $q->have_posts() ) {
	return;
}

// Set inline the first item's category background color (if any)
$style_attr = '';
while ( $q->have_posts() ) {
	$q->the_post();

	$color      = ignition_public_opinion_get_the_post_category_color();
	$style_attr = $color ? sprintf( 'background-color: %s;', $color ) : '';
	break;
}
$q->rewind_posts();

?>
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="news-ticker">
				<div class="news-ticker-controls">
					<span class="news-ticker-title" style="<?php echo esc_attr( $style_attr ); ?>">
						<?php echo esc_html( $ticker_title ); ?>
					</span>

					<button class="btn btn-news-ticker-prev">
						<span class="ignition-icons ignition-icons-long-arrow-alt-left"></span>
					</button>

					<button class="btn btn-news-ticker-next">
						<span class="ignition-icons ignition-icons-long-arrow-alt-left"></span>
					</button>
				</div>

				<div class="news-ticker-items">
					<?php while ( $q->have_posts() ) : ?>
						<?php $q->the_post(); ?>

						<span class="news-ticker-item" data-color="<?php echo esc_attr( ignition_public_opinion_get_the_post_category_color() ); ?>">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</span>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php wp_reset_postdata();
