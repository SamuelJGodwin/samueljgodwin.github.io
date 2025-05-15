<?php
	$is_recurring = get_post_meta( get_the_ID(), 'ignition_event_is_recurring', true );
	$recurrence   = get_post_meta( get_the_ID(), 'ignition_event_recurrence', true );
	$date         = get_post_meta( get_the_ID(), 'ignition_event_date', true );
	$time         = get_post_meta( get_the_ID(), 'ignition_event_time', true );
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

	if ( $echo_date || $time || $location ) {
		?>
		<ul class="entry-item-list-meta entry-item-excerpt">
			<?php if ( $echo_date || $time ) : ?>
				<li class="entry-item-list-meta-item">
					<?php
						echo $echo_date ? wp_kses_post( $echo_date ) : '';
						echo $time && $echo_date ? ' &ndash; ' : '';
						echo $time ? esc_html( $time ) : '';
					?>
				</li>
			<?php endif; ?>

			<?php if ( $location ) : ?>
				<li class="entry-item-list-meta-item">
					<?php echo esc_html( $location ); ?>
				</li>
			<?php endif; ?>
		</ul>
		<?php
	}
