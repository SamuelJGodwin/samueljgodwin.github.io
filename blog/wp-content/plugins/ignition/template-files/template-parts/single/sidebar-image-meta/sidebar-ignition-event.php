<?php
/**
 * Template part for displaying the sidebar image and metadata of event items
 *
 * @since 1.1.0
 */

ignition_the_post_thumbnail( 'ignition_sidebar_tall' );

$meta_array = array();
$meta_keys  = array();
$labels     = array(
	'ignition_event_recurrence' => _x( 'Date', 'event meta label', 'ignition' ),
	'ignition_event_date'       => _x( 'Date', 'event meta label', 'ignition' ),
	'ignition_event_time'       => _x( 'Time', 'event meta label', 'ignition' ),
	'ignition_event_location'   => _x( 'Location', 'event meta label', 'ignition' ),
);

$is_recurring = get_post_meta( get_queried_object_id(), 'ignition_event_is_recurring', true );

if ( $is_recurring ) {
	unset( $labels['ignition_event_date'] );
	unset( $labels['ignition_event_time'] );

	$meta_keys = array_keys( $labels );
} else {
	unset( $labels['ignition_event_recurrence'] );

	$meta_key = 'ignition_event_date';
	$value    = get_post_meta( get_queried_object_id(), $meta_key, true );
	if ( $value ) {
		$event_dt = strtotime( $value );
		if ( $event_dt ) {
			$value = date_i18n( get_option( 'date_format' ), $event_dt );

			$meta_array[ $meta_key ] = $value;
		}
	}

	$meta_keys = array_keys( $labels );
	// Remove 'ignition_event_date' as we handled it above.
	unset( $meta_keys[0] );
}

foreach ( $meta_keys as $meta_key ) {
	$value = get_post_meta( get_queried_object_id(), $meta_key, true );
	if ( $value ) {
		$meta_array[ $meta_key ] = $value;
	}
}

if ( $meta_array ) {
	?>
	<div class="entry-meta">
		<?php foreach ( $meta_array as $key => $value ) : ?>
			<div class="entry-meta-item">
				<span class="entry-meta-item-label"><?php echo wp_kses_post( $labels[ $key ] ); ?></span>
				<span class="entry-meta-item-value"><?php echo wp_kses_post( $value ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}
