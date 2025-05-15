<?php
/**
 * Placeholder functions normally coming from the Ignition plugin, so that there won't be fatal errors if it's not active.
 *
 * @since 1.0.0
 */


/**
 * Returns the customizer's default values.
 *
 * @since 1.0.0
 *
 * @param false|string $setting
 *
 * @return mixed
 */
function ignition_public_opinion_ignition_customizer_defaults( $setting = false ) {
	if ( function_exists( 'ignition_customizer_defaults' ) ) {
		return ignition_customizer_defaults( $setting );
	}

	/**
	 * Filters the default values of Ignition's customize settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults 'setting_name' => 'value' array.
	 */
	$defaults = apply_filters( 'ignition_customizer_defaults', array() );

	if ( 'all' === $setting ) {
		return $defaults;
	}

	if ( ! empty( $setting ) && array_key_exists( $setting, $defaults ) ) {
		/**
		 * Filters the default value for a single Ignition's customizer setting.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $default The default value for the setting.
		 * @param string $setting The setting's name.
		 */
		return apply_filters( 'ignition_customizer_default', $defaults[ $setting ], $setting );
	}

	/**
	 * Filters the default value for a single Ignition's customizer setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $default The default value for the setting.
	 * @param string $setting The setting's name.
	 */
	return apply_filters( 'ignition_customizer_default', false, $setting );
}

/**
 * Returns the filename suffix to be used when enqueuing scripts and styles.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_public_opinion_ignition_scripts_styles_suffix() {
	if ( function_exists( 'ignition_scripts_styles_suffix' ) ) {
		return ignition_scripts_styles_suffix();
	}

	return '.min';
}

/**
 * Retrieves a theme_mod value, or it's overridden value by post/term meta.
 * Assumes that an empty string value means we want to fall back to the customizer value.
 *
 * @since 1.0.0
 *
 * @param string $name    Option name
 * @param bool   $default Default value to pass to get_theme_mod()
 *
 * @return mixed
 */
function ignition_public_opinion_ignition_get_mod( $name, $default = false ) {
	if ( function_exists( 'ignition_get_mod' ) ) {
		return ignition_get_mod( $name, $default );
	}

	return get_theme_mod( $name, $default );
}
