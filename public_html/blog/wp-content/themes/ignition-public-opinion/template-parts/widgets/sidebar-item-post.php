<div class="ignition-widget-item">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="ignition-widget-item-thumb">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'ignition_minicart_item' ); ?>
			</a>
		</div>
	<?php endif; ?>

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
