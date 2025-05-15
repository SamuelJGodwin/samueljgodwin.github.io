<?php
/**
 * Template Name: Canvas
 * Template Post Type: post, page, ignition-accommodati, ignition-discography, ignition-event, ignition-podcast, ignition-portfolio, ignition-service, ignition-team, ignition-property
 *
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
<?php endwhile; ?>

<?php wp_footer(); ?>
</body>
</html>

