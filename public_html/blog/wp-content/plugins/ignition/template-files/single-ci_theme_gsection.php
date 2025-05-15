<?php
/**
 * The template for previewing Global Sections
 *
 * @since 1.2.0
 */

get_header();

while ( have_posts() ) {
	the_post();

	the_content();
}

get_footer();
