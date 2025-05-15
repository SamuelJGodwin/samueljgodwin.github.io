<?php
/**
 * Template part for displaying the output of the [ignition-current-weather] shortcode
 *
 * @since 1.0.0
 */

/** @var array $args */
?>
<div class="theme-weather"
	data-location-id="<?php echo esc_attr( $args['location_id'] ); ?>"
	data-units="<?php echo esc_attr( $args['units'] ); ?>"
	data-units-symbol="<?php echo esc_attr( $args['units_symbol'] ); ?>"
>
	<span class="theme-weather-location">
		<?php echo wp_kses_post( $args['location_formatted'] ); ?>
	</span>
	&ndash;
	<span class="theme-weather-temperature">
		<span class="theme-weather-temperature-value"><?php echo intval( round( $args['temperature'] ) ); ?></span>
		<span class="theme-weather-temperature-unit"><?php echo wp_kses_post( $args['units_symbol'] ); ?></span>
	</span>
</div>
