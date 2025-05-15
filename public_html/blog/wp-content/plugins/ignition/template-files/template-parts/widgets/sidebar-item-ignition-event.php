<?php
/**
 * The default template for displaying Event items in sidebar widgets
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

		<?php
			$is_recurring = get_post_meta( get_the_ID(), 'ignition_event_is_recurring', true );
			$recurrence   = get_post_meta( get_the_ID(), 'ignition_event_recurrence', true );
			$date         = get_post_meta( get_the_ID(), 'ignition_event_date', true );
			$location     = get_post_meta( get_the_ID(), 'ignition_event_location', true );

			$echo_date = '';

			if ( $is_recurring ) {
				$echo_date = $recurrence;
			} else {
				$event_dt = strtotime( $date );
				if ( ! empty( $date ) && false !== $event_dt ) {
					$echo_date = date_i18n( get_option( 'date_format' ), $event_dt );
				}
			}

			$info[] = $echo_date;
			$info[] = $location;
			$info   = array_filter( $info );
		?>
		<?php if ( ! empty( $info ) ) : ?>
			<div class="ignition-widget-item-subtitle">
				<?php echo wp_kses_post( implode( ' &mdash; ', $info ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
