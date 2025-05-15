<?php
/**
 * The default fallback template for displaying items in sidebar widgets
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

		<?php if ( post_type_supports( get_post_type(), 'excerpt' ) && has_excerpt() ) : ?>
			<div class="ignition-widget-item-subtitle">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
