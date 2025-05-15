<?php
/**
 * Template part for displaying the side header widgets
 *
 * @since 2.1.0
 */
?>

<?php if ( is_active_sidebar( 'header-1' ) || is_active_sidebar( 'header-2' ) ) : ?>
	<div class="site-sidebar-widgets">
		<?php if ( is_active_sidebar( 'header-1' ) ) : ?>
			<div class="site-sidebar-widgets-top">
				<?php dynamic_sidebar( 'header-1' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'header-2' ) ) : ?>
			<div class="site-sidebar-widgets-bottom">
				<?php dynamic_sidebar( 'header-2' ); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif;
