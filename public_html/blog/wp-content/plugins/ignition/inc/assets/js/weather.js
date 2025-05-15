jQuery(function ($) {
	'use strict';

	/* -----------------------------------------
	 Weather
	 ----------------------------------------- */
	function ignitionShowWeather( $container, weather_data ) {
		if ( weather_data.error ) {
			return;
		}

		var $temp      = $container.find( '.theme-weather-temperature-value' );
		var $unit      = $container.find( '.theme-weather-temperature-unit' );
		var $location  = $container.find( '.theme-weather-location' );

		$temp.html( Math.round( weather_data.temperature ) );
		$unit.html( weather_data.units_symbol );
		$location.html( weather_data.location_formatted );
	}

	var $weather = $( '.theme-weather' );

	$weather.each( function () {
		var $this      = $( this );
		var locationId = $this.data( 'location-id' );
		var units      = $this.data( 'units' );

		var weather_key = 'ignition-weather-data' + '-' + units + '-' + locationId;
		var weather_fetch_time_key = weather_key + '-fetch-time';

		var apiHitRate = 1000 * 60 * 5;
		var runTime    = Date.now();
		var fetchTime  = localStorage.getItem( weather_fetch_time_key );


		var response = JSON.parse( localStorage.getItem( weather_key ) );
		if ( response ) {
			ignitionShowWeather( $this, response );
		}

		if ( ( fetchTime === null ) || ( ( runTime - parseInt( fetchTime ) ) > apiHitRate ) ) {
			$.ajax( {
				url: ignition_weather_vars.ajaxurl,
				data: {
					action: 'ignition_get_weather_conditions',
					weather_nonce: ignition_weather_vars.weather_nonce,
					location_id: locationId,
					units: units,
				},
				dataType: 'json',
				cache: false,
			} ).done( function ( response ) {
				localStorage.setItem( weather_fetch_time_key, Date.now() );
				localStorage.setItem( weather_key, JSON.stringify( response ) );
				ignitionShowWeather( $this, response );
			} ).fail( function ( response ) {
				console.error( 'Ignition weather module :: ', response.responseText );
			} );
		}
	} );

});
