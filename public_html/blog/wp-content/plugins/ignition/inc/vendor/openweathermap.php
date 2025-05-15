<?php
/**
 * OpenWeatherMap related hooks and functions
 *
 * @since 1.0.0
 */

add_action( 'wp_ajax_ignition_get_weather_conditions', 'ignition_ajax_openweathermap_weather_conditions' );
add_action( 'wp_ajax_nopriv_ignition_get_weather_conditions', 'ignition_ajax_openweathermap_weather_conditions' );
/**
 * AJAX handler that prints the current weather conditions in a JSON object.
 *
 * @since 1.0.0
 */
function ignition_ajax_openweathermap_weather_conditions() {
	$valid_nonce = check_ajax_referer( 'ignition-weather-check', 'weather_nonce', false );
	$units       = false;
	$location_id = false;

	if ( false === $valid_nonce ) {
		$error_msg = __( 'Invalid nonce.', 'ignition' );
		$response  = array(
			'error'      => true,
			'error_type' => 'ignition',
			'errors'     => array( $error_msg ),
			'data'       => new stdClass(),
		);

		wp_send_json( $response );
	}

	if ( ! isset( $_GET['location_id'] ) ) {
		$error_msg = __( 'Missing weather location.', 'ignition' );
		$response  = array(
			'error'      => true,
			'error_type' => 'ignition',
			'errors'     => array( $error_msg ),
			'data'       => new stdClass(),
		);

		wp_send_json( $response );
	}

	$location_id = wp_kses( wp_unslash( $_GET['location_id'] ), 'strip' );

	if ( isset( $_GET['units'] ) ) {
		$units = ignition_openweathermap_sanitize_units( wp_kses( wp_unslash( $_GET['units'] ), 'strip' ) );
	} else {
		$units = 'metric';
	}

	$response = ignition_openweathermap_get_weather_conditions( $location_id, $units, get_theme_mod( 'utilities_openweathermap_api_key' ) );

	wp_send_json( $response );
}

/**
 * Returns the valid unit systems for OpenWeatherMap.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_openweathermap_get_units_choices() {
	/**
	 * Filters the available weather unit choices.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices
	 */
	return apply_filters( 'ignition_openweathermap_units_choices', array(
		'metric'   => __( 'Celsius', 'ignition' ),
		'imperial' => __( 'Fahrenheit', 'ignition' ),
		'standard' => __( 'Kelvin', 'ignition' ),
	) );
}

/**
 * Sanitizes a units' system for OpenWeatherMap.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_openweathermap_sanitize_units( $value ) {
	$choices = ignition_openweathermap_get_units_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return 'metric';
}

/**
 * Returns a temperature symbol, depending on the units' system.
 *
 * @since 1.0.0
 *
 * @param string $unit
 *
 * @return string
 */
function ignition_openweathermap_get_temperature_unit_symbol( $unit ) {
	$k = _x( 'K', 'Temperature unit symbol (Kelvin)', 'ignition' );
	$f = _x( 'F', 'Temperature unit symbol (Fahrenheit)', 'ignition' );
	$c = _x( 'C', 'Temperature unit symbol (Celsius)', 'ignition' );

	$symbol = 'C';
	switch ( $unit ) {
		case 'standard':
			$symbol = $k;
			break;
		case 'imperial':
			$symbol = $f;
			break;
		case 'metric':
		default:
			$symbol = $c;
			break;
	}

	/**
	 * Filters the the unit symbol of a unit.
	 *
	 * @since 1.0.0
	 *
	 * @param string $symbol
	 * @param string $unit
	 */
	return apply_filters( 'ignition_openweathermap_temperature_unit_symbol', $symbol, $unit );
}

/**
 * Returns current weather conditions.
 *
 * @see ignition_openweathermap_get_current_weather()
 *
 * @since 1.0.0
 *
 * @param string      $location_id
 * @param string|bool $units
 * @param string|bool $api_key
 * @param object|null $weather_obj Use the specified request object as returned by ignition_openweathermap_get_current_weather().
 *
 * @return array
 */
function ignition_openweathermap_get_weather_conditions( $location_id, $units = false, $api_key = false, $weather_obj = null ) {

	if ( null === $weather_obj ) {
		$api_key = $api_key ? trim( $api_key ) : '';
		$units   = $units ? $units : get_theme_mod( 'utilities_openweathermap_units', ignition_customizer_defaults( 'utilities_openweathermap_units' ) );

		if ( empty( $api_key ) || empty( $location_id ) ) {
			$error_msg = '';
			if ( empty( $api_key ) ) {
				$error_msg = __( 'Missing weather location.', 'ignition' );
			} elseif ( empty( $location_id ) ) {
				$error_msg = __( 'Missing weather API key.', 'ignition' );
			}

			$response = array(
				'error'      => true,
				'error_type' => 'ignition',
				'errors'     => array( $error_msg ),
				'data'       => new stdClass(),
			);

			return $response;
		}

		$weather = ignition_openweathermap_get_current_weather( $api_key, $location_id, $units );
	} else {
		$weather = $weather_obj;
	}

	if ( is_wp_error( $weather ) ) {
		$response = array(
			'error'      => true,
			'error_type' => 'wp',
			'errors'     => $weather->get_error_messages(),
			'data'       => new stdClass(),
		);
	} elseif ( ! empty( $weather ) && isset( $weather['response']['code'] ) && 200 === intval( $weather['response']['code'] ) ) {
		$response = array(
			'error'      => false,
			'error_type' => '',
			'errors'     => array(),
			'data'       => json_decode( $weather['body'] ),
		);
	} else {
		$response = array(
			'error'      => true,
			'error_type' => 'other',
			'errors'     => array( __( 'OpenWeatherData.org Error', 'ignition' ) ),
			'data'       => new stdClass(),
		);
	}

	if ( false === $response['error'] ) {
		$unit        = ignition_openweathermap_get_temperature_unit_symbol( $units );
		$temperature = false;
		if ( ! empty( $response['data']->main->temp ) ) {
			$temperature = $response['data']->main->temp;
		}

		/**
		 * Filters the successful weather response.
		 *
		 * @since 1.0.0
		 *
		 * @param array $response
		 */
		$response = apply_filters( 'ignition_current_weather', array_merge( $response, array(
			'temperature'        => $temperature,
			'units'              => $units,
			'units_symbol'       => $unit,
			'location_id'        => $location_id,
			'location_name'      => $response['data']->name,
			'location_country'   => $response['data']->sys->country,
			'location_formatted' => sprintf(
				/* translators:  %1$s is a location name, %2$s is a country code. E.g. London, UK */
				_x( '%1$s, %2$s', 'location', 'ignition' ),
				$response['data']->name,
				$response['data']->sys->country
			),
		) ) );
	}

	return $response;
}

/**
 * Low level query to the openweathermap.org API for the current weather conditions.
 * Usually, you'll want to use ignition_openweathermap_get_weather_conditions() instead.
 *
 * @see ignition_openweathermap_get_weather_conditions()
 *
 * @since 1.0.0
 *
 * @param string      $api_key
 * @param string      $location_id
 * @param string|bool $units        May be 'metric' (Celsius), 'imperial' (Fahrenheit), or 'standard' (Kelvin).
 * @param bool        $bypass_cache Bypass all caches. Use for debugging only.
 *
 * @return WP_Error|array The response or WP_Error on failure.
 */
function ignition_openweathermap_get_current_weather( $api_key, $location_id, $units = 'metric', $bypass_cache = false ) {
	$query_hash = ignition_openweathermap_get_query_hash( $api_key, $location_id, $units );
	$trans_name = ignition_openweathermap_get_transient_name( $query_hash );

	/**
	 * Filters the weather's caching time (in minutes).
	 *
	 * @since 1.0.0
	 */
	$cache_time = apply_filters( 'ignition_openweathermap_current_weather_query_cache_time', 20 * MINUTE_IN_SECONDS );

	/**
	 * Filters the period (in minutes) between retries, in case the query fails.
	 *
	 * @since 1.0.0
	 */
	$retry_time = apply_filters( 'ignition_openweathermap_current_weather_query_retry_time', 2 * MINUTE_IN_SECONDS ); // Retry after failure.

	/**
	 * Filters the timeout (in seconds) for the query's request.
	 *
	 * @since 1.0.0
	 */
	$request_timeout = apply_filters( 'ignition_openweathermap_current_weather_query_timeout', 30 );

	// This transient will never expire. Holds the last known good response for fallback.
	$noexp_name = ignition_openweathermap_get_nonexpiring_transient_name( $query_hash );

	$api_url = 'https://api.openweathermap.org/data/2.5/weather';

	$response = get_transient( $trans_name );
	if ( false === $response || $bypass_cache ) {

		$api_params = array(
			'id'    => $location_id,
			'appid' => $api_key,
		);

		// If 'standard' then the 'units' parameter shouldn't be passed.
		if ( in_array( $units, array( 'metric', 'imperial' ), true ) ) {
			$api_params['units'] = $units;
		}

		$url = add_query_arg( $api_params, $api_url );

		/**
		 * Filters the request's URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url
		 * @param array  $api_params
		 * @param string $api_url
		 */
		$url = apply_filters( 'ignition_openweathermap_current_weather_request_url', $url, $api_params, $api_url );

		$response = wp_safe_remote_get( $url, array(
			'timeout' => $request_timeout,
		) );


		if ( ! $bypass_cache ) {
			$noexp = (array) get_transient( $noexp_name );

			if ( ! is_wp_error( $response ) && ! empty( $response ) && isset( $response['response']['code'] ) && 200 === (int) $response['response']['code'] ) {
				$json = json_decode( $response['body'], true );

				if ( ! is_null( $json ) && ! empty( $json['weather'] ) && ! empty( $json['main'] ) ) {
					$noexp['last_good_response']  = $response;
					$noexp['last_good_timestamp'] = time();
				} else {
					$cache_time = $retry_time;
				}
			}

			if ( is_wp_error( $response ) ) {
				$noexp['last_fail_message']   = $response->get_error_messages();
				$noexp['last_fail_timestamp'] = time();

				$cache_time = $retry_time;

				if ( ! empty( $noexp['last_good_response'] ) ) {
					$response = $noexp['last_good_response'];
				}
			}

			// Cache indefinitely, both for a fallback to $trans_name, as well as debugging.
			set_transient( $noexp_name, $noexp, 0 );

			set_transient( $trans_name, $response, $cache_time );
		}
	}

	return $response;
}

/**
 * Returns a unique hash for the weather query.
 *
 * @since 1.0.0
 *
 * @param $api_key
 * @param $location_id
 * @param $units
 *
 * @return string
 */
function ignition_openweathermap_get_query_hash( $api_key, $location_id, $units ) {
	return md5( $api_key . $location_id . $units . 'current_weather' );
}

/**
 * Returns a unique transient name for each weather query.
 *
 * @since 1.0.0
 *
 * @param $query_hash
 *
 * @return string
 */
function ignition_openweathermap_get_transient_name( $query_hash ) {
	$base_name = 'ignition_openweathermap_current_weather_%s';
	$name      = sprintf( $base_name, $query_hash );

	return $name;
}

/**
 * Returns a unique non-expiring transient name for each weather query.
 *
 * @since 1.0.0
 *
 * @param $query_hash
 *
 * @return string
 */
function ignition_openweathermap_get_nonexpiring_transient_name( $query_hash ) {
	$base_name = 'ignition_openweathermap_current_weather_noexp_%s';
	$name      = sprintf( $base_name, $query_hash );

	return $name;
}
