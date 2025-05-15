<?php
/**
 * Template part for displaying the sidebar artist metadata of team items
 *
 * @since 1.1.0
 */

$meta_array = array();
$labels     = array(
	'ignition_team_location'       => _x( 'Location', 'team meta label', 'ignition' ),
	'ignition_team_genre'          => _x( 'Genre', 'team meta label', 'ignition' ),
	'ignition_team_booking_notice' => _x( 'Booking Notice', 'team meta label', 'ignition' ),
);

$meta_keys = array_keys( $labels );

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
