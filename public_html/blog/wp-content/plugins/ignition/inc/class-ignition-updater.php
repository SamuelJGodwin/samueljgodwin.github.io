<?php
/**
 * Handles update notifications.
 *
 * @since 1.0.0
 */
class Ignition_Updater {

	/**
	 * @var string
	 *
	 * @since 1.0.0
	 */
	public $slug;

	/**
	 * @var string
	 *
	 * @since 1.0.0
	 */
	public $plugin_basename;

	/**
	 * @var string
	 *
	 * @since 1.0.0
	 */
	public $plugin_url;

	/**
	 * @var string
	 *
	 * @since 1.0.0
	 */
	public $current_version;

	/**
	 * Ignition_Updater constructor.
	 *
	 * @param string $current_version  The currently installed plugin version.
	 * @param string $plugin_file_path Absolute path to the main plugin file.
	 */
	public function __construct( $slug, $current_version, $plugin_file_path ) {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin = get_plugin_data( $plugin_file_path );

		$this->slug            = $slug;
		$this->current_version = $current_version;
		$this->plugin_basename = plugin_basename( $plugin_file_path );
		$this->plugin_url      = $plugin['PluginURI'];

		if ( class_exists( 'CSSIgniter_Updater' ) ) {
			// Let the CSSIgniter Updater plugin handle updates.
			return;
		}

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_check_admin_handler' ), 10, 2 );
	}

	/**
	 * Returns a plugin object, as expected by the Updates API.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version
	 * @param false|string $package_url The URL where $version can be downloaded from.
	 *                                  If empty, downloading is disabled. Default false.
	 *
	 * @return object
	 */
	protected function get_plugin_object( $version, $package_url = false ) {
		$plugin = (object) array(
			'id'            => $this->plugin_basename,
			'slug'          => $this->slug,
			'plugin'        => $this->plugin_basename,
			'new_version'   => $version,
			'url'           => $this->plugin_url,
			'package'       => $package_url,
			'icons'         => array(),
			'banners'       => array(),
			'banners_rtl'   => array(),
			'tested'        => '',
			'requires_php'  => '',
			'compatibility' => new stdClass(),
		);

		return $plugin;
	}

	/**
	 * Action hook handler for plugin updates checks. Intercepts plugin update data before they are written into a transient.
	 * Don't use directly.
	 *
	 * Hooked on 'pre_set_site_transient_update_plugins' action hook.
	 *
	 * @since 1.0.0
	 *
	 * @param object $value An object containing the plugin-update information returned by WordPress.org API.
	 * @param string $transient The transient name. 'update_plugins' in this case.
	 *
	 * @return mixed
	 */
	public function update_check_admin_handler( $value, $transient ) {
		$latest_version = $this->get_latest_version();
		$plugin         = $this->get_plugin_object( $latest_version, false );

		if ( false !== $latest_version ) {
			if ( version_compare( $latest_version, $this->current_version, '>' ) ) {
				$value->response[ $this->plugin_basename ] = $plugin;
			} else {
				$value->no_update[ $this->plugin_basename ] = $plugin;
			}
		}

		return $value;
	}

	/**
	 * Retrieves (if necessary) and returns the plugin's latest released version.
	 *
	 * @since 1.0.0
	 *
	 * @return false|string
	 */
	public function get_latest_version() {
		$versions_url        = apply_filters( 'ignition_latest_version_url', 'http://www.cssigniter.com/plugin_versions.json' );
		$update_period       = apply_filters( 'ignition_update_period', 24 * 60 * 60 );
		$error_update_period = apply_filters( 'ignition_update_period_after_error', 8 * 60 * 60 );
		$transient_name      = 'ignition_latest_version';

		$latest_version = get_transient( $transient_name );

		if ( false === $latest_version ) {
			$response = wp_remote_get( $versions_url );
			if ( is_wp_error( $response ) ) {
				set_transient( $transient_name, - 1, $error_update_period );

				return false;
			} else {
				if ( 200 === (int) $response['response']['code'] ) {
					$plugin_versions = $response['body'];
				} else {
					set_transient( $transient_name, - 1, $error_update_period );

					return false;
				}
			}

			if ( empty( $plugin_versions ) ) {
				set_transient( $transient_name, - 1, $error_update_period );

				return false;
			}

			$plugin_versions = json_decode( $plugin_versions, true );

			if ( null === $plugin_versions || false === $plugin_versions ) {
				set_transient( $transient_name, - 1, $error_update_period );

				return false;
			}

			if ( ! isset( $plugin_versions[ $this->slug ] ) ) {
				set_transient( $transient_name, - 1, $error_update_period );

				return false;
			}

			$latest_version = $plugin_versions[ $this->slug ];

			set_transient( $transient_name, $latest_version, $update_period );
		}

		return $latest_version;
	}
}
