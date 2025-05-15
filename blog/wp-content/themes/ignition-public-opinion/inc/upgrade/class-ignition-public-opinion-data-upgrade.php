<?php
/**
 * Handles the data upgrade process.
 *
 * @see   Ignition_Public_Opinion_Data_Upgrade_Background_Process
 *
 * @since 1.0.0
 */
class Ignition_Public_Opinion_Data_Upgrade {

	/**
	 * Holds the installed database version.
	 * @var string
	 */
	private $db_version;

	/**
	 * Holds the current parent theme version.
	 * @var string
	 */
	private $theme_version;

	/**
	 * @var Ignition_Public_Opinion_Data_Upgrade_Log
	 */
	public $log;

	/**
	 * Holds registered upgrade functions in `$callbacks[version][] = 'callback'` format.
	 * @var array
	 */
	private $callbacks = array();

	/**
	 * @var Ignition_Public_Opinion_Data_Upgrade_Background_Process
	 */
	private $bg_process;

	private $is_upgrading;

	private $default_log_message;

	/**
	 * Ignition_Public_Opinion_Data_Upgrade constructor.
	 *
	 * @param bool $debug
	 */
	public function __construct( $debug = false ) {
		$this->theme_version = wp_get_theme( basename( get_template_directory() ) )->get( 'Version' );
		$this->db_version    = get_option( 'ignition_public_opinion_theme_db_version', false );
		$this->is_upgrading  = get_option( 'ignition_public_opinion_upgrade_in_progress', false );

		// Looks like a fresh installation!
		if ( empty( $this->db_version ) ) {
			$this->db_version = '';
		}

		$this->default_log_message = __( 'Updating data...', 'ignition-public-opinion' );

		$this->log        = new Ignition_Public_Opinion_Data_Upgrade_Log( 'ignition_public_opinion_upgrade_log_message', $this->default_log_message, $debug );
		$this->bg_process = new Ignition_Public_Opinion_Data_Upgrade_Background_Process( $this );

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'wp_ajax_ignition_public_opinion_data_upgrade_get_status', array( $this, 'ajax_get_status' ) );
	}

	/**
	 * Admin hooks.
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {
		if ( is_admin() && ( $this->needs_upgrade() || $this->is_upgrading() ) ) {
			wp_enqueue_script( 'ignition-public-opinion-data-upgrade', get_theme_file_uri( '/inc/upgrade/js/upgrade.js' ), array( 'jquery' ), ignition_public_opinion_asset_version(), true );
			wp_localize_script( 'ignition-public-opinion-data-upgrade', 'ignition_public_opinion_data_upgrade', array(
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
				'text_done'       => __( 'The theme finished upgrading data.', 'ignition-public-opinion' ),
				'update_interval' => apply_filters( 'ignition_public_opinion_data_upgrade_ajax_update_interval', 1 ) * 1000,
			) );
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		}
	}

	/**
	 * Returns the status of the upgrade process.
	 *
	 * @since 1.0.0
	 */
	public function ajax_get_status() {
		$data = array(
			'is_upgrading' => (bool) $this->is_upgrading(),
			'message'      => (string) $this->log->get(),
		);

		wp_send_json_success( $data );
	}

	/**
	 * Outputs an admin notice.
	 *
	 * @since 1.0.0
	 */
	public function admin_notice() {
		?>
		<div id="ignition-public-opinion-data-upgrade" class="notice-warning notice">
			<p class="primary"><?php echo wp_kses( __( 'The theme updates data in the background. Please wait.', 'ignition-public-opinion' ), ignition_public_opinion_get_allowed_tags( 'guide' ) ); ?></p>
			<p class="secondary"><?php echo esc_html( $this->default_log_message ); ?></p>
		</div>
		<?php
	}

	/**
	 * Registers an upgrade function to be run on a specific theme version.
	 *
	 * Functions that return true are considered complete, while if they return false, they should be re-run.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $version The version for which the callback will be run.
	 * @param callable $callback_fn Callable. Must be a named function.
	 */
	public function register( $version, $callback_fn ) {
		if ( empty( $version ) ) {
			$version = '';
		}

		if ( ! is_callable( $callback_fn ) || ! function_exists( $callback_fn ) ) {
			trigger_error( sprintf( "Callback '%s' must be a callable function.", $callback_fn ), E_USER_ERROR );
			return;
		}

		if ( ! array_key_exists( $version, $this->callbacks ) ) {
			$this->callbacks[ $version ] = array();
		}

		$this->callbacks[ $version ][] = $callback_fn;
	}


	/**
	 * Determines whether a data upgrade is needed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function needs_upgrade() {
		if ( ! version_compare( $this->db_version, $this->get_theme_version(), '<' ) ) {
			return false;
		}

		// Are there any version-less callbacks? We need to run them if it's a new installation or a legacy installation without a version number in the database.
		if ( empty( $this->db_version ) && array_key_exists( $this->db_version, $this->callbacks ) && ! empty( $this->callbacks[ $this->db_version ] ) ) {
			return true;
		}

		// Check bigger versions for callbacks.
		foreach ( $this->callbacks as $version => $v_callbacks ) {
			if ( ! empty( $this->db_version ) && version_compare( $this->db_version, $version, '<' ) && ! empty( $v_callbacks ) ) {
				if ( version_compare( $version, $this->get_theme_version(), '<=' ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Sets status of upgrade on and off.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_upgrading
	 */
	public function set_is_upgrading( $is_upgrading ) {
		if ( $is_upgrading ) {
			$this->is_upgrading = true;
			update_option( 'ignition_public_opinion_upgrade_in_progress', true );
		} else {
			if ( $this->is_upgrading ) {
				// Only touch the database when the state changes. Avoids unnecessary db writes.
				delete_option( 'ignition_public_opinion_upgrade_in_progress' );
			}

			$this->is_upgrading = false;
		}

		$this->log->debug( sprintf( 'Ignition_Public_Opinion_Upgrade - In progress: %s', $this->is_upgrading ? 'true' : 'false' ) );
	}

	/**
	 * Returns the status of upgrade.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_upgrading() {
		return $this->is_upgrading;
	}

	/**
	 * Determines if an upgrade is needed, and starts the upgrade if it does.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function maybe_upgrade() {
		if ( $this->is_upgrading() ) {
			$this->log->debug( 'Ignition_Public_Opinion_Upgrade - maybe_upgrade() exits because is_upgrading()' );
			return false;
		}

		if ( ! $this->bg_process->is_queue_empty() ) {
			$this->log->debug( 'Ignition_Public_Opinion_Upgrade - maybe_upgrade() exits because not is_queue_empty()' );
			return false;
		}

		if ( ! $this->needs_upgrade() ) {
			$this->log->debug( 'Ignition_Public_Opinion_Upgrade - maybe_upgrade() exits because not needs_upgrade()' );
			$this->set_db_version( $this->get_theme_version() );
			$this->log->clear();
			return false;
		}

		// This is cleared in Ignition_Public_Opinion_Data_Upgrade_Background_Process::complete()
		$this->set_is_upgrading( true );

		$this->do_upgrade();

		return true;
	}

	/**
	 * Enqueues callbacks for execution. Each call enqueues only callbacks for the immediately next version.
	 * Therefore, multiple calls (and requests) are needed to complete a multi-version upgrade.
	 *
	 * @since 1.0.0
	 */
	private function do_upgrade() {
		$callbacks    = array();
		$next_version = false;

		if ( empty( $this->db_version ) && array_key_exists( $this->db_version, $this->callbacks ) && ! empty( $this->callbacks[ $this->db_version ] ) ) {
			$callbacks    = $this->callbacks[ $this->db_version ];
			$next_version = $this->get_theme_version();
		} else {
			foreach ( $this->callbacks as $version => $v_callbacks ) {
				if ( ! empty( $this->db_version ) && version_compare( $this->db_version, $version, '<' ) && ! empty( $v_callbacks ) ) {
					$callbacks    = $v_callbacks;
					$next_version = $version;
					break;
				}
			}
		}

		if ( $callbacks && $next_version ) {
			foreach ( $callbacks as $callback ) {
				$this->log->debug( sprintf( 'Ignition_Public_Opinion_Upgrade - Queueing %s for version %s.', $callback, $next_version ) );

				$this->bg_process->push_to_queue( apply_filters( 'ignition_public_opinion_data_upgrade_callback', array(
					'callback'     => $callback,
					'this_version' => $this->db_version,
					'next_version' => $next_version,
				) ) );
			}

			$this->bg_process->save()->dispatch();
		}
	}

	/**
	 * Stores the db_version into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param string $db_version
	 */
	public function set_db_version( $db_version ) {
		if ( $this->db_version === $db_version ) {
			return;
		}

		if ( empty( $db_version ) ) {
			$db_version = '';
		}

		$this->db_version = $db_version;

		update_option( 'ignition_public_opinion_theme_db_version', $this->db_version );

		$this->log->debug( sprintf( 'Ignition_Public_Opinion_Upgrade - Theme database version set to: %s.', $this->db_version ) );
	}

	/**
	 * Returns the plugin's current version.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_theme_version() {
		return $this->theme_version;
	}
}
