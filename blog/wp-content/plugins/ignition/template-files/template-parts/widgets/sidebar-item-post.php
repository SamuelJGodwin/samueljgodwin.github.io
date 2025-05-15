<?php
/**
 * The default template for displaying posts in sidebar widgets
 *
 * @since 1.0.0
 */
?>
<div class="ignition-widget-item">
	<div class="ignition-widget-item-content">
		<h4 class="ignition-widget-item-title">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h4>

		<div class="ignition-widget-item-subtitle">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time>
		</div>
	</div>
</div>
