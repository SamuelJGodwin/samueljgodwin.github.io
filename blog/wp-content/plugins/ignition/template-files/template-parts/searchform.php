<?php
/**
 * Template part for displaying a search form.
 *
 * This file may be included by a theme's searchform.php to display the standard Ignition search form.
 * It must not be placed in ignition/template-files/searchform.php (therefore requesting it from the theme as
 * `ignition_get_template_part( 'searchform' )` as the function will pick the theme's searchform.php first, resulting
 * in an endless loop.
 *
 * @since 1.0.0
 */

?>
<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="searchform" role="search">
	<div>
		<label for="s" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'ignition' ); ?></label>
		<input type="search" id="s" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php echo esc_attr_x( 'Search', 'search box placeholder', 'ignition' ); ?>">
		<button class="searchsubmit" type="submit"><span class="ignition-icons ignition-icons-search"></span><span class="screen-reader-text"> <?php echo esc_html_x( 'Search', 'submit button', 'ignition' ); ?></span></button>
	</div>
</form>
