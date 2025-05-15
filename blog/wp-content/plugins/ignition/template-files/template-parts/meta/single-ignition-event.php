<?php
	$is_recurring = get_post_meta( get_the_ID(), 'ignition_event_is_recurring', true );
	$recurrence   = get_post_meta( get_the_ID(), 'ignition_event_recurrence', true );
	$date         = get_post_meta( get_the_ID(), 'ignition_event_date', true );
	$time         = get_post_meta( get_the_ID(), 'ignition_event_time', true );
	$location     = get_post_meta( get_the_ID(), 'ignition_event_location', true );

	$labels = array(
		'ignition_event_recurrence' => _x( 'Date', 'event meta label', 'ignition' ),
		'ignition_event_date'       => _x( 'Date', 'event meta label', 'ignition' ),
		'ignition_event_time'       => _x( 'Time', 'event meta label', 'ignition' ),
		'ignition_event_location'   => _x( 'Location', 'event meta label', 'ignition' ),
	);

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
		<ul class="entry-list-meta">
			<?php if ( $is_recurring ) : ?>
				<?php if ( $echo_date ) : ?>
					<li class="entry-list-meta-item">
						<span class="entry-list-meta-label">
							<?php echo esc_html( $labels['ignition_event_recurrence'] ); ?>
						</span>

						<span class="entry-list-meta-value">
							<?php echo wp_kses_post( $echo_date ); ?>
						</span>
					</li>
				<?php endif; ?>
			<?php else : ?>
				<?php if ( $echo_date ) : ?>
					<li class="entry-list-meta-item">
						<span class="entry-list-meta-label">
							<?php echo esc_html( $labels['ignition_event_date'] ); ?>
						</span>

						<span class="entry-list-meta-value">
							<?php echo wp_kses_post( $echo_date ); ?>
						</span>
					</li>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $time ) : ?>
				<li class="entry-list-meta-item">
					<span class="entry-list-meta-label">
						<?php echo esc_html( $labels['ignition_event_time'] ); ?>
					</span>

					<span class="entry-list-meta-value">
						<?php echo esc_html( $time ); ?>
					</span>
				</li>
			<?php endif; ?>

			<?php if ( $location ) : ?>
				<li class="entry-list-meta-item">
					<span class="entry-list-meta-label">
						<?php echo esc_html( $labels['ignition_event_location'] ); ?>
					</span>

					<span class="entry-list-meta-value">
						<?php echo esc_html( $location ); ?>
					</span>
				</li>
			<?php endif; ?>
		</ul>
		<?php
	}
