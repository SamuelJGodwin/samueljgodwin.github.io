<?php
/**
 * The default template for displaying Portfolio items in sidebar widgets
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

		<?php the_terms( get_the_ID(), 'ignition_portfolio_category', '<div class="ignition-widget-item-subtitle">', ', ', '</div>' ); ?>
	</div>
</div>
