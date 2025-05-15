<?php
/**
 * Handles logging for the data upgrade process.
 *
 * @since 1.0.0
 */
class Ignition_Data_Upgrade_Log {
	private $option_name;
	private $debug = false;

	public $default_message = '';
	public $current_message = '';

	/**
	 * Ignition_Data_Upgrade_Log constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name
	 * @param string $default_message
	 * @param false  $debug
	 */
	public function __construct( $option_name, $default_message = '', $debug = false ) {
		$this->option_name     = $option_name;
		$this->debug           = (bool) $debug;
		$this->default_message = $default_message;
		$this->current_message = get_option( $this->option_name );
	}

	/**
	 * Returns the current log message that should be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get() {
		return $this->current_message;
	}

	/**
	 * Sets the current log message that should be displayed.
	 *
	 * @since 1.0.0
	 */
	public function set( $message ) {
		if ( $this->current_message !== $message ) {
			$this->current_message = $message;
			update_option( $this->option_name, wp_kses_post( $this->current_message ) );
		}
	}

	/**
	 * Resets the current log message to the default.
	 *
	 * @since 1.0.0
	 */
	public function reset() {
		$this->set( $this->default_message );
	}

	/**
	 * Prints a message to the debug log.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 */
	public function debug( $message ) {
		if ( $this->debug && defined( 'WP_DEBUG' ) && true === (bool) WP_DEBUG ) {
			error_log( $message );
		}
	}

	/**
	 * Clears the current message.
	 *
	 * @since 1.0.0
	 */
	public function clear() {
		if ( $this->current_message ) {
			$this->current_message = '';
			delete_option( $this->option_name );
		}
	}
}
