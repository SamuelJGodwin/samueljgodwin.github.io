<?php
/**
 * Template part for displaying the sidebar image and metadata of discography items
 *
 * @since 1.1.0
 */

ignition_the_post_thumbnail( 'ignition_sidebar_tall' );

$meta_array = array();
$labels     = array(
	'ignition_discography_date'       => _x( 'Release Date', 'discography meta label', 'ignition' ),
	'ignition_discography_catalog_no' => _x( 'Catalog #', 'discography meta label', 'ignition' ),
	'ignition_discography_label'      => _x( 'Label', 'discography meta label', 'ignition' ),
	'ignition_discography_producers'  => _x( 'Producers', 'discography meta label', 'ignition' ),
);

$meta_key = 'ignition_discography_date';
$value    = get_post_meta( get_queried_object_id(), $meta_key, true );
if ( $value ) {
	$release_dt = strtotime( $value );
	if ( $release_dt ) {
		$value = date_i18n( get_option( 'date_format' ), $release_dt );

		$meta_array[ $meta_key ] = $value;
	}
}

$meta_keys = array_keys( $labels );
// Remove 'ignition_discography_date' as we handled it above.
unset( $meta_keys[0] );

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
