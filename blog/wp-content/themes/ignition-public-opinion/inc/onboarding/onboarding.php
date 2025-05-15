<?php
/**
 * Class used to generate the onboarding page.
 *
 * @since 1.0.0
 */
class Ignition_Public_Opinion_Onboarding_Page {

	/**
	 * Used to pass all custom onboarding data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * The theme's name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $theme_name;

	/**
	 * The theme's slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $theme_slug;

	/**
	 * Stores the theme object.
	 *
	 * @since 1.0.0
	 *
	 * @var WP_Theme
	 */
	private $theme;

	/**
	 * The theme version.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $theme_version;

	/**
	 * The title of the onboarding page on the WP menu.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $menu_title;

	/**
	 * The title of the onboarding page on the page.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $page_title;

	/**
	 * Initializes the Onboarding page.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Custom onboarding data.
	 */
	public function init( $data ) {

		add_filter( 'extra_theme_headers', array( $this, 'add_ignition_theme_headers' ) );

		/**
		 * Intercept theme update data before they are written into the `update_themes` site transient.
		 */
		add_action( 'pre_set_site_transient_update_themes', array( $this, 'update_check_admin_handler_maybe' ) );

		if ( ! empty( $data ) && is_array( $data ) && true === $data['show_page'] ) {
			$defaults   = $this->default_data();
			$this->data = wp_parse_args( $data, $defaults );

			$this->data['theme_update'] = wp_parse_args( $this->data['theme_update'], $defaults['theme_update'] );

			$this->data['tabs'] = wp_parse_args( $this->data['tabs'], $defaults['tabs'] );
			if ( ! function_exists( 'ignition_public_opinion_get_theme_variations' ) || empty( $this->data['theme_variations_page']['variations'] ) ) {
				$this->data['tabs']['theme_variations'] = '';
				if ( 'theme_variations' === $this->data['default_tab'] ) {
					$this->data['default_tab'] = 'getting_started';
				}
			}

			foreach ( $this->data['plugins'] as $slug => $plugin ) {
				$plugin = $this->plugin_entry_defaults( $plugin );

				$this->data['plugins'][ $slug ] = $plugin;
			}

			$this->data['getting_started_page'] = wp_parse_args( $this->data['getting_started_page'], $defaults['getting_started_page'] );

			$this->themedata_setup();
			$this->page_setup();
		}
	}

	/**
	 * Returns the onboarding page's URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_page_url() {
		return add_query_arg( array(
			'page' => $this->theme_slug . '-onboard',
		), admin_url( 'themes.php' ) );
	}

	/**
	 * Sets up theme and custom data.
	 *
	 * @since 1.0.0
	 */
	public function themedata_setup() {
		$theme    = wp_get_theme();
		$defaults = $this->default_data();

		if ( is_child_theme() ) {
			$this->theme_name    = $theme->parent()->get( 'Name' );
			$this->theme_version = $theme->parent()->get( 'Version' );
			$this->theme         = $theme->parent();
		} else {
			$this->theme_name    = $theme->get( 'Name' );
			$this->theme_version = $theme->get( 'Version' );
			$this->theme         = $theme;
		}

		$this->theme_slug = $this->theme->get_template();

		$this->menu_title = ! empty( $this->data['menu_title'] ) ? $this->data['menu_title'] : $defaults['menu_title'];
		$this->page_title = ! empty( $this->data['page_title'] ) ? $this->data['page_title'] : $defaults['page_title'];

		if ( ! empty( $this->data['theme_variations_page']['variations'] ) ) {
			$variations       = $this->data['theme_variations_page']['variations'];
			$theme_screenshot = $theme->get_screenshot();

			foreach ( $this->data['theme_variations_page']['variations'] as $slug => $variation ) {
				if ( empty( $variation['screenshot'] ) ) {
					$variation['screenshot'] = $theme_screenshot;

					if ( '' !== $slug ) {
						$path            = "/theme-variations/{$slug}/screenshot.png";
						$screenshot_path = get_theme_file_path( $path );
						if ( file_exists( $screenshot_path ) ) {
							$variation['screenshot'] = get_theme_file_uri( $path );
						}
					}
				}

				$variations[ $slug ] = $variation;
			}

			$this->data['theme_variations_page']['variations'] = $variations;
		}
	}

	/**
	 * Registers actions used on the onboarding page.
	 *
	 * @since 1.0.0
	 */
	public function page_setup() {
		if ( $this->data['redirect_on_activation'] ) {
			add_action( 'after_switch_theme', array( $this, 'redirect_to_onboarding' ) );
		}

		add_action( 'admin_notices', array( $this, 'ignition_minimum_version_notice' ) );
		add_action( 'admin_notices', array( $this, 'onboarding_notice' ) );
		add_action( 'wp_ajax_ignition_public_opinion_dismiss_onboarding', array( $this, 'dismiss_onboarding' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_menu', array( $this, 'register' ) );
		add_action( 'wp_ajax_ignition_public_opinion_install_plugin', array( $this, 'install_plugin' ) );
		add_action( 'wp_ajax_ignition_public_opinion_activate_variation', array( $this, 'activate_variation' ) );
		add_action( 'wp_ajax_ignition_public_opinion_reset_theme_mods', array( $this, 'reset_theme_mods' ) );
		add_action( 'wp_ajax_ignition_public_opinion_backup_theme_mods', array( $this, 'backup_theme_mods' ) );
		add_action( 'wp_ajax_ignition_public_opinion_restore_theme_mods', array( $this, 'restore_theme_mods' ) );
	}

	/**
	 * Read Ignition headers when reading theme and plugin headers.
	 *
	 * @since 1.0.0
	 *
	 * @param array $headers Headers.
	 *
	 * @return array
	 */
	public function add_ignition_theme_headers( $headers ) {
		$headers[] = 'RequiresIgnition';

		return $headers;
	}

	/**
	 * Redirects to the onboarding page after activation.
	 *
	 * @since 1.0.0
	 */
	public function redirect_to_onboarding() {
		global $pagenow;
		if ( is_admin() && 'themes.php' === $pagenow && isset( $_GET['activated'] ) && current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_safe_redirect( $this->get_page_url() );
			exit;
		}
	}

	/**
	 * Adds an admin notice for unmatched minimum Ignition version.
	 *
	 * @since 1.0.0
	 */
	public function ignition_minimum_version_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$requires_ignition = wp_get_theme( basename( get_template_directory() ) )->get( 'RequiresIgnition' );

		if ( $requires_ignition
			&& defined( 'IGNITION_VERSION' )
			&& version_compare( IGNITION_VERSION, $requires_ignition, '<' )
		) {
			?>
			<div class="notice notice-error">
				<div class="ignition-public-opinion-onboarding-notice">
					<p>
						<?php
							echo wp_kses(
								sprintf(
									/* translators: %1$s is the theme name. %2$s URL to onboarding page. */
									__( 'The theme requires at least Ignition Framework v<strong>%1$s</strong>, but currently v<strong>%2$s</strong> is installed. Please update Ignition to the latest version, to avoid having errors on your website.', 'ignition-public-opinion' ),
									$requires_ignition,
									IGNITION_VERSION
								),
								array(
									'a'      => array(
										'href' => array(),
									),
									'strong' => array(),
								)
							);
						?>
					</p>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Adds an admin notice for the onboarding page.
	 *
	 * @since 1.0.0
	 */
	public function onboarding_notice() {
		$screen    = get_current_screen();
		$dismissed = get_theme_mod( 'dismissed_onboarding', false );

		if ( ! current_user_can( 'manage_options' ) || $dismissed || "appearance_page_{$this->theme_slug}-onboard" === $screen->id ) {
			return;
		}

		?>
		<div class="notice notice-info is-dismissible">
			<div class="ignition-public-opinion-onboarding-notice">
				<p>
					<?php
						/* translators: %1$s is the theme name. %2$s URL to onboarding page. */
						echo wp_kses( sprintf( __( 'Welcome to %1$s. Check out the <a href="%2$s">onboarding page</a> to get things started.', 'ignition-public-opinion' ),
							$this->theme_name,
							esc_url( $this->get_page_url() )
						), array( 'a' => array( 'href' => array() ) ) );
					?>
				</p>
			</div>
		</div>
		<?php

		wp_enqueue_script( 'ignition-public-opinion-onboarding-notice', get_theme_file_uri( '/inc/onboarding/assets/js/onboarding-notice.js' ), array(), $this->theme_version, true );

		$settings = array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'dismiss_nonce' => wp_create_nonce( 'ignition-public-opinion-dismiss-onboarding' ),
		);
		wp_localize_script( 'ignition-public-opinion-onboarding-notice', 'ignition_public_opinion_Onboarding', $settings );
	}

	/**
	 * Handles dismissal of the admin notice.
	 *
	 * @since 1.0.0
	 */
	public function dismiss_onboarding() {
		check_ajax_referer( 'ignition-public-opinion-dismiss-onboarding', 'nonce' );

		if ( current_user_can( 'manage_options' ) && ! empty( $_POST['dismissed'] ) && 'true' === $_POST['dismissed'] ) {
			set_theme_mod( 'dismissed_onboarding', true );
			wp_send_json_success( 'OK' );
		}

		wp_send_json_error( 'BAD' );
	}

	/**
	 * Enqueues onboarding page styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin_styles() {
		if ( get_current_screen()->id !== 'appearance_page_' . $this->theme_slug . '-onboard' ) {
			return;
		}

		wp_enqueue_style( 'plugin-install' );

		wp_enqueue_style( 'ignition-public-opinion-onboarding', get_theme_file_uri( '/inc/onboarding/assets/css/onboarding.css' ), array(), $this->theme_version );

		wp_enqueue_script( 'ignition-public-opinion-onboarding', get_theme_file_uri( '/inc/onboarding/assets/js/onboarding.js' ), array(
			'plugin-install',
			'updates',
		), $this->theme_version, true );

		wp_localize_script(
			'ignition-public-opinion-onboarding', 'ignition_public_opinion_onboarding', array(
				'onboarding_nonce'        => wp_create_nonce( 'onboarding_nonce' ),
				'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
				'template_directory'      => get_template_directory_uri(),
				'activating_text'         => esc_html__( 'Activating', 'ignition-public-opinion' ),
				'activate_text'           => esc_html__( 'Activate', 'ignition-public-opinion' ),
				'installing_text'         => esc_html__( 'Installing...', 'ignition-public-opinion' ),
				'activate_variation_text' => esc_html__( 'Activate variation', 'ignition-public-opinion' ),
				'deleting_text'           => esc_html__( 'Deleting...', 'ignition-public-opinion' ),
				'reset_mods_confirm_text' => esc_html__( 'Are you sure you want to delete your theme customizations?', 'ignition-public-opinion' ),
			)
		);
	}

	/**
	 * Registers the page in the admin menu.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_theme_page( $this->page_title, $this->menu_title, 'edit_theme_options', $this->theme_slug . '-onboard', array( $this, 'render_page' ) );
	}

	/**
	 * Renders the onboarding page.
	 *
	 * @since 1.0.0
	 */
	public function render_page() {
		$title = $this->data['title'];
		$title = str_replace(
			array( ':theme_name:', ':theme_version:' ),
			array( $this->theme_name, $this->theme_version ),
		$title );

		if ( defined( 'IGNITION_PUBLIC_OPINION_WHITELABEL' ) && IGNITION_PUBLIC_OPINION_WHITELABEL ) {
			$logo_src = ! empty( $this->data['logo_src'] ) ? $this->data['logo_src'] : '';
			$logo_url = ! empty( $this->data['logo_url'] ) ? $this->data['logo_url'] : '';
		} else {
			$logo_src = ! empty( $this->data['logo_src'] ) ? $this->data['logo_src'] : get_theme_file_uri( '/inc/onboarding/assets/images/cssigniter_logo.svg' );
			$logo_url = ! empty( $this->data['logo_url'] ) ? $this->data['logo_url'] : 'https://www.cssigniter.com/themes/' . IGNITION_PUBLIC_OPINION_NAME . '/';
		}
		?>
		<div class="wrap ignition-public-opinion-onboarding-wrap full-width-layout">
			<div class="ignition-public-opinion-onboarding-header">
				<div class="ignition-public-opinion-onboarding-header-details">
					<h2 class="ignition-public-opinion-onboarding-header-title">
						<?php echo esc_html( $title ); ?>
					</h2>

					<div class="ignition-public-opinion-onboarding-header-meta">
						<span class="ignition-public-opinion-onboarding-header-meta-item">
							<?php
								/* translators: %s is the current theme's version. */
								echo wp_kses_post( sprintf( __( 'Theme version: <b>%s</b>', 'ignition-public-opinion' ), $this->theme_version ) );
							?>
						</span>

						<?php if ( ! empty( $this->data['theme_update'] ) ) : ?>
							<?php if ( version_compare( $this->theme_version, $this->data['theme_update']['latest_version'], '<' ) ) : ?>
								<span class="ignition-public-opinion-onboarding-header-meta-item">
									<?php esc_html_e( 'A new version is available.', 'ignition-public-opinion' ); ?>

									<?php
										$links = array();
										for ( $i = 1; $i <= 2; $i++ ) {
											$url  = $this->data['theme_update'][ "link_url_{$i}" ];
											$text = $this->data['theme_update'][ "link_text_{$i}" ];
											if ( ! empty( $url ) && ! empty( $text ) ) {
												$links[] = sprintf( '<a href="%s" target="_blank" rel="noopener noreferer">%s</a>',
													esc_url( $url ),
													esc_html( $text )
												);
											}
										}
									?>
									<?php if ( $links ) : ?>
										<span class="ignition-public-opinion-onboarding-header-meta-item-links-wrap">
											<?php echo wp_kses_post( implode( '<span class="ignition-public-opinion-onboarding-header-meta-item-separator">/</span>', $links ) ); ?>
										</span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
						<?php endif; ?>

					</div>
				</div>

				<?php if ( $this->data['logo_show'] && $logo_src ) : ?>
					<div class="ignition-public-opinion-onboarding-header-logo">
						<a href="<?php echo esc_url( $logo_url ); ?>" target="_blank" rel="noopener noreferrer">
							<img src="<?php echo esc_url( $logo_src ); ?>">
						</a>
					</div>
				<?php endif; ?>
			</div>

			<div class="ignition-public-opinion-onboarding-main-wrap">

				<div class="ignition-public-opinion-onboarding-wp-notices">
					<hr class="wp-header-end">
				</div>

				<div class="ignition-public-opinion-onboarding-main">
					<?php if ( array_key_exists( 'tabs', $this->data ) && ! empty( $this->data['tabs'] ) ) {
						$this->generate_tabs();
					} ?>
				</div>

				<?php $this->sidebar_widgets_section(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Creates the navigation tabs.
	 *
	 * @since 1.0.0
	 */
	public function generate_tabs() {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : $this->data['default_tab']; // phpcs:ignore WordPress.Security.NonceVerification
		?>
		<div class="ignition-public-opinion-onboarding-main-content-nav-header">
			<?php foreach ( $this->data['tabs'] as $tab => $title ) : ?>
				<?php if ( empty( $title ) ) {
					continue;
				} ?>
				<?php /*<a href="<?php echo esc_url( add_query_arg( array( 'tab' => $tab ), $this->get_page_url() ) ); ?>" class="ignition-public-opinion-onboarding-main-content-nav-link <?php echo esc_attr( $active_tab === $tab ? 'is-active' : '' ); ?>" role="tab" data-toggle="tab"><?php echo esc_html( $title ); ?></a>*/ ?>
				<a href="<?php echo esc_url( add_query_arg( array( 'tab' => $tab ), $this->get_page_url() ) ); ?>" class="ignition-public-opinion-onboarding-main-content-nav-link <?php echo esc_attr( $active_tab === $tab ? 'is-active' : '' ); ?>"><?php echo esc_html( $title ); ?></a>
			<?php endforeach; ?>
		</div>

		<div class="ignition-public-opinion-onboarding-main-content tab-content-<?php echo esc_attr( $active_tab ); ?>">
			<?php if ( is_callable( array( $this, $active_tab ) ) ) {
				$this->$active_tab();
			} ?>
		</div>
		<?php
	}

	/**
	 * Processes and outputs an onboarding page's content.
	 *
	 * The function also replaces instances of [onbarding-plugin:plugin-slug] with the appropriate markup.
	 *
	 * @param $content
	 */
	public function output_content( $content ) {
		$content = wpautop( $content );

		preg_match_all( '/\[onboarding-plugin:(.*?)\]/', $content, $matches );
		foreach ( $matches[1] as $slug ) {
			$content = str_replace( "[onboarding-plugin:{$slug}]", $this->get_plugin_box( $slug ), $content );
		}

		$content = str_replace( '[onboarding-import-box]', $this->get_sample_content_ready_box(), $content );

		preg_match_all( '/\[onboarding-plugins:(.*?)\]/', $content, $matches );
		// $filter values: 'required', 'recommended', 'required_by_sample'.
		foreach ( $matches[1] as $filter ) {
			$plugins_html = '';
			$plugins      = wp_list_filter( $this->data['plugins'], array( $filter => true ) );
			foreach ( $plugins as $slug => $data ) {
				$plugins_html .= $this->get_plugin_box( $slug );
			}
			if ( $plugins_html ) {
				$content = str_replace( "[onboarding-plugins:{$filter}]", $plugins_html, $content );
			}
		}

		echo wp_kses_post( $content );
	}

	/**
	 * Populates the required plugins tab.
	 *
	 * @since 1.0.0
	 */
	public function getting_started() {
		if ( ! empty( $this->data['getting_started_page']['text_before'] ) ) {
			?><div class="ignition-public-opinion-onboarding-main-content-text-before"><?php $this->output_content( $this->data['getting_started_page']['text_before'] ); ?></div><?php
		}

		if ( ! empty( $this->data['getting_started_page']['content'] ) ) {
			?><div class="ignition-public-opinion-onboarding-main-content-text"><?php $this->output_content( $this->data['getting_started_page']['content'] ); ?></div><?php
		}

		if ( ! empty( $this->data['getting_started_page']['text_after'] ) ) {
			?><div class="ignition-public-opinion-onboarding-main-content-text-after"><?php $this->output_content( $this->data['getting_started_page']['text_after'] ); ?></div><?php
		}
	}

	/**
	 * Populates the sample content tab.
	 *
	 * @since 1.0.0
	 */
	public function sample_content() {
		if ( ! empty( $this->data['sample_content_page']['text_before'] ) ) {
			?><div class="ignition-public-opinion-onboarding-main-content-text-before"><?php $this->output_content( $this->data['sample_content_page']['text_before'] ); ?></div><?php
		}

		if ( ! empty( $this->data['sample_content_page']['content'] ) ) {
			?><div class="ignition-public-opinion-onboarding-main-content-text"><?php $this->output_content( $this->data['sample_content_page']['content'] ); ?></div><?php
		}

		if ( ! empty( $this->data['sample_content_page']['text_after'] ) ) {
			?><div class="ignition-public-opinion-onboarding-main-content-text-after"><?php $this->output_content( $this->data['sample_content_page']['text_after'] ); ?></div><?php
		}
	}

	/**
	 * Populates the theme variations tab
	 *
	 * @since 1.0.0
	 */
	public function theme_variations() {
		?>
		<h2><?php esc_html_e( 'Select a theme variation.', 'ignition-public-opinion' ); ?></h2>
		<p><?php esc_html_e( 'You can always come back and pick another.', 'ignition-public-opinion' ); ?></p>
		<?php

		if ( ! current_user_can( 'edit_theme_options' ) ) :
			?><p><?php echo wp_kses( __( 'You do not have sufficient permissions to switch theme variations. Please contact your administrator.', 'ignition-public-opinion' ), ignition_public_opinion_get_allowed_tags( 'guide' ) ); ?></p><?php

			return;
		endif;

		?><div class="ignition-public-opinion-onboarding-variations"><?php
			$variations = $this->data['theme_variations_page']['variations'];

			$this->get_variation_boxes( $variations );
		?></div><?php

		$reset_button = isset( $this->data['theme_variations_page']['reset_mods_button'] ) && true === (bool) $this->data['theme_variations_page']['reset_mods_button'];
		if ( $reset_button ) {
			?>
			<div class="reset-theme-mods-wrap">
				<p><?php esc_html_e( "Your existing theme customizations made via the Customizer (theme mods) may prevent you from viewing the selected variation's defaults correctly. In this case, you might want to delete your customizations.", 'ignition-public-opinion' ); ?></p>

				<div class="latest-backup-info">
					<?php
						$mods_backup = get_option( 'ignition_public_opinion_mods_backup', false );
						$backup_date = ! empty( $mods_backup['date'] ) ? $mods_backup['date'] : __( 'Never', 'ignition-public-opinion' );

						/* translators: %s is a date, or the word "Never". */
						echo wp_kses_post( sprintf( __( 'Latest backup: %s', 'ignition-public-opinion' ),
							'<span class="date">' . $backup_date . '</span>'
						) );
					?>
				</div>

				<p>
					<a href="#" class="button button-primary backup-theme-mods"><?php esc_html_e( 'Backup customizations', 'ignition-public-opinion' ); ?></a>
					<a href="#" class="button restore-theme-mods"><?php esc_html_e( 'Restore from backup', 'ignition-public-opinion' ); ?></a>
					<a href="#" class="button reset-theme-mods"><?php esc_html_e( 'Delete customizations', 'ignition-public-opinion' ); ?></a>
				</p>
				<p><em><?php esc_html_e( 'Warning: Deleting your customizations cannot be undone. Make sure you keep a backup before deleting your customizations.', 'ignition-public-opinion' ); ?></em></p>
			</div>
			<?php
		}
	}

	/**
	 * Returns the variations markup, for the variations tab.
	 *
	 * @param array $variations Array of the available variations.
	 *
	 * @since 1.0.0
	 */
	public function get_variation_boxes( $variations ) {
		if ( ! function_exists( 'ignition_public_opinion_get_theme_variation' ) ) {
			return;
		}

		$current_variation = ignition_public_opinion_get_theme_variation();

		foreach ( $variations as $slug => $variation ) {
			$this->get_variation_box( $slug, $current_variation );
		}
	}

	/**
	 * Returns the markup for a variation entry.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug              The slug of the variation to output.
	 * @param string $current_variation The currently active variation.
	 */
	public function get_variation_box( $slug, $current_variation ) {
		$data = $this->data['theme_variations_page']['variations'][ $slug ];

		$status_class = '';

		if ( $current_variation === $slug ) {
			$status_class = 'enabled';
		}

		?>
		<div class="ignition-public-opinion-onboarding-item ignition-public-opinion-variation <?php echo esc_attr( $status_class ); ?>" data-variation="<?php echo esc_attr( $slug ); ?>">
			<figure class="ignition-public-opinion-variation-thumb">
				<img src="<?php echo esc_url( $data['screenshot'] ); ?>" alt="">
			</figure>

			<h4 class="ignition-public-opinion-onboarding-item-title"><?php echo esc_html( $data['title'] ); ?></h4>

			<?php if ( ! empty( $data['description'] ) ) : ?>
				<p class="ignition-public-opinion-onboarding-item-description"><?php echo wp_kses( $data['description'], ignition_public_opinion_get_allowed_tags( 'guide' ) ); ?></p>
			<?php endif; ?>

			<p>
				<a href="#" class="button activate-variation" data-variation-slug="<?php echo esc_attr( $slug ); ?>">
					<?php esc_html_e( 'Activate variation', 'ignition-public-opinion' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Populates the sidebar section.
	 *
	 * @since 1.0.0
	 */
	public function sidebar_widgets_section() {
		if ( empty( $this->data['sidebar_widgets'] ) ) {
			return;
		}

		?>
		<div class="ignition-public-opinion-onboarding-main-sidebar">
			<?php foreach ( $this->data['sidebar_widgets'] as $widget_id => $widget ) : ?>
				<div class="ignition-public-opinion-onboarding-widget <?php echo esc_attr( $widget_id ); ?>">
					<h4 class="ignition-public-opinion-onboarding-widget-title"><?php echo esc_html( $widget['title'] ); ?></h4>

					<?php if ( ! empty( $widget['description'] ) ) {
						echo wp_kses_post( wpautop( $widget['description'] ) );
					} ?>

					<?php if ( ! empty( $widget['button_url'] ) && ! empty( $widget['button_text'] ) ) : ?>
						<a href="<?php echo esc_url( $widget['button_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary"><?php echo esc_html( $widget['button_text'] ); ?></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Returns the markup for the sample content import box.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_sample_content_ready_box() {
		$action = $this->get_plugin_action( 'one-click-demo-import' );

		ob_start();

		if ( in_array( $action, array( 'install-plugin', 'activate' ), true ) ) {
			?>
			<div class="ignition-public-opinion-onboarding-item ignition-public-opinion-onboarding-item-warning">
				<h4 class="ignition-public-opinion-onboarding-item-title"><?php esc_html_e( 'Please note:', 'ignition-public-opinion' ); ?></h4>

				<p class="ignition-public-opinion-onboarding-item-description"><?php echo wp_kses( __( 'You need to install and activate <strong>One Click Demo Import</strong> before proceeding.', 'ignition-public-opinion' ), ignition_public_opinion_get_allowed_tags( 'guide' ) ); ?></p>
			</div>
			<?php
		} else {
			?>
			<div class="ignition-public-opinion-onboarding-item ignition-public-opinion-onboarding-item-success">
				<h4 class="ignition-public-opinion-onboarding-item-title"><?php esc_html_e( 'Good to go!', 'ignition-public-opinion' ); ?></h4>

				<p class="ignition-public-opinion-onboarding-item-description"><?php esc_html_e( 'Now you can import the sample content and have your theme set up like the demo using the One Click Demo Import Plugin.', 'ignition-public-opinion' ); ?></p>

				<div class="ignition-public-opinion-onboarding-item-meta">
					<p><a
						href="<?php echo esc_url( add_query_arg( array( 'page' => 'one-click-demo-import' ), admin_url( 'themes.php' ) ) ); ?>"
						class="button button-primary"
					>
						<?php esc_html_e( 'Get Started', 'ignition-public-opinion' ); ?>
					</a></p>
				</div>
			</div>
			<?php
		}

		return ob_get_clean();
	}

	/**
	 * Returns the plugins markup, for the plugin tabs.
	 *
	 * @since 1.0.0
	 *
	 * @see Ignition_Public_Opinion_Onboarding_Page::plugin_entry_defaults()
	 * @see Ignition_Public_Opinion_Onboarding_Page::get_plugins_actions()
	 *
	 * @param array $plugins Array of plugins' arrays.
	 * @param array $actions Array of plugin actions.
	 */
	public function get_plugin_boxes( $plugins, $actions ) {
		$boxes = array();

		foreach ( $actions as $slug => $action ) {
			if ( ! array_key_exists( $slug, $plugins ) ) {
				continue;
			}

			$data = $plugins[ $slug ];

			$boxes[] = $this->get_plugin_box( $slug );
		}

		return implode( PHP_EOL, $boxes );
	}

	/**
	 * Returns the markup for a plugin entry.
	 *
	 * @since 1.0.0
	 *
	 * @see Ignition_Public_Opinion_Onboarding_Page::get_plugins_actions()
	 * @see Ignition_Public_Opinion_Onboarding_Page::plugin_entry_defaults()
	 *
	 * @param string $slug A plugins' slug.
	 *
	 * @return string
	 */
	public function get_plugin_box( $slug ) {
		$data   = $this->data['plugins'][ $slug ];
		$action = $this->get_plugin_action( $slug );

		$plugin_file = ! empty( $data['plugin_file'] ) ? $slug . '/' . $data['plugin_file'] : $slug . '/' . $slug . '.php';

		$status_class = false;
		$status_text  = false;

		$button_classes = false;
		$button_url     = false;
		$button_text    = false;
		$button_target  = '';

		if ( 'upload-plugin' === $action ) {
			/* translators: %s is the plugin name. */
			$status_text  = sprintf( __( 'The %s plugin was not found. Click to install.', 'ignition-public-opinion' ), $data['title'] );
			$status_class = 'ignition-public-opinion-onboarding-item-warning';

			$button_classes = 'ajax-install-plugin';
			$button_text    = __( 'Install', 'ignition-public-opinion' );
			$button_url     = add_query_arg( array(
				'action'   => 'activate',
				'plugin'   => rawurlencode( $plugin_file ),
				'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $plugin_file ),
			), admin_url( 'plugins.php' ) );
		} elseif ( 'install-plugin' === $action ) {
			/* translators: %s is the plugin name. */
			$status_text  = sprintf( __( 'The %s plugin was not found. Click to install.', 'ignition-public-opinion' ), $data['title'] );
			$status_class = 'ignition-public-opinion-onboarding-item-warning';

			$button_classes = 'install-now';
			$button_text    = __( 'Install', 'ignition-public-opinion' );
			$button_url     = add_query_arg( array(
				'action'   => $action,
				'plugin'   => $slug,
				'_wpnonce' => wp_create_nonce( $action . '_' . $slug ),
			), admin_url( 'update.php' ) );
		} elseif ( 'activate' === $action ) {
			/* translators: %s is the plugin name. */
			$status_text  = sprintf( __( 'The %s plugin is installed but not active. Click to activate.', 'ignition-public-opinion' ), $data['title'] );
			$status_class = 'ignition-public-opinion-onboarding-item-info';

			$button_classes = 'activate-now button-primary';
			$button_text    = __( 'Activate', 'ignition-public-opinion' );
			$button_url     = add_query_arg( array(
				'action'   => 'activate',
				'plugin'   => rawurlencode( $plugin_file ),
				'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $plugin_file ),
			), admin_url( 'plugins.php' ) );
		} else {
			/* translators: %s is the plugin name. */
			$status_text  = sprintf( __( '%s is installed and activated.', 'ignition-public-opinion' ), $data['title'] );
			$status_class = 'ignition-public-opinion-onboarding-item-success';
		}

		if ( in_array( $action, array( 'upload-plugin', 'install-plugin' ), true ) ) {
			if ( $data['download_url'] ) {
				/* translators: %s is the plugin name. */
				$status_text = sprintf( __( 'The %s plugin was not found. You need to download and install it.', 'ignition-public-opinion' ), $data['title'] );

				$button_classes = '';
				$button_text    = __( 'Download', 'ignition-public-opinion' );
				$button_url     = $data['download_url'];
				$button_target  = 'target="_blank"';
			}
		}

		ob_start();

		?>
		<div class="ignition-public-opinion-onboarding-item <?php echo esc_attr( $status_class ); ?>">
			<h4 class="ignition-public-opinion-onboarding-item-title"><?php echo esc_html( $data['title'] ); ?></h4>

			<?php if ( ! empty( $data['description'] ) ) : ?>
				<p class="ignition-public-opinion-onboarding-item-description"><?php echo wp_kses( $data['description'], ignition_public_opinion_get_allowed_tags( 'guide' ) ); ?></p>
			<?php endif; ?>

			<div class="ignition-public-opinion-onboarding-item-meta">
				<?php echo wp_kses_post( wpautop( $status_text ) ); ?>
				<?php if ( $button_url && $button_text ) : ?>
					<p><a
						data-plugin-slug="<?php echo esc_attr( $slug ); ?>"
						href="<?php echo esc_url( $button_url ); ?>"
						class="button <?php echo esc_attr( $button_classes ); ?>"
						<?php echo wp_kses_post( $button_target ); ?>
					>
						<?php echo esc_html( $button_text ); ?>
					</a></p>
				<?php endif; ?>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Checks if a list of plugins are installed, active or absent.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugins Array of plugins' arrays.
	 *
	 * @return array
	 */
	public function get_plugins_actions( $plugins ) {
		$plugin_actions = array();

		foreach ( $plugins as $slug => $data ) {
			$plugin_actions[ $slug ] = $this->get_plugin_action( $slug );
		}

		return $plugin_actions;
	}

	/**
	 * Checks if a plugin is installed, active or absent.
	 *
	 * @since 1.0.0
	 *
	 * @see Ignition_Public_Opinion_Onboarding_Page::plugin_entry_defaults()
	 *
	 * @param string $slug A plugin's slug.
	 *
	 * @return string
	 */
	public function get_plugin_action( $slug ) {
		$action = '';
		$data   = $this->data['plugins'][ $slug ];

		$plugin_file      = ! empty( $data['plugin_file'] ) ? $slug . '/' . $data['plugin_file'] : $slug . '/' . $slug . '.php';
		$plugin_file_path = WP_PLUGIN_DIR . '/' . $plugin_file;

		$is_callable  = ! empty( $data['is_callable'] ) ? is_callable( $data['is_callable'] ) : false;
		$is_active    = is_plugin_active( $plugin_file );
		$is_installed = file_exists( $plugin_file_path );
		$is_bundled   = isset( $data['bundled'] ) && true === $data['bundled'] ? true : false;

		if ( $is_callable || $is_active ) {
			$action = 'none';
		} elseif ( $is_installed ) {
			$action = 'activate';
		} else {
			if ( $is_bundled ) {
				$action = 'upload-plugin';
			} else {
				$action = 'install-plugin';
			}
		}

		return $action;
	}

	/**
	 * Installs theme specific plugins.
	 *
	 * @since 1.0.0
	 */
	public function install_plugin() {
		if ( ! current_user_can( 'upload_plugins' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to install plugins on this site.', 'ignition-public-opinion' ) );
		}

		// Verify nonce.
		if ( ! isset( $_POST['onboarding_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['onboarding_nonce'] ) ), 'onboarding_nonce' ) ) {
			die( 'Permission denied' );
		}

		$plugin_slug = isset( $_POST['plugin_slug'] ) ? sanitize_key( wp_unslash( $_POST['plugin_slug'] ) ) : '';

		$plugin_source_url = get_template_directory_uri() . '/plugins/' . $plugin_slug . '.zip';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		/* translators: %s is a URL. */
		$title    = sprintf( __( 'Installing Plugin from URL: %s', 'ignition-public-opinion' ), esc_html( $plugin_source_url ) );
		$url      = 'update.php?action=install-plugin';
		$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( array(
			'title' => $title,
			'url'   => $url,
		) ) );
		$upgrader->install( $plugin_source_url );
	}

	/**
	 * Replaces database values of nav menu items with custom URLs.
	 *
	 * @since 1.3.0
	 *
	 * @param string $find_url
	 * @param string $replace_url
	 */
	public static function replace_custom_nav_menu_items( $find_url, $replace_url ) {
		$menus = wp_get_nav_menus( array(
			'hide_empty' => true,
		) );

		foreach ( $menus as $menu ) {
			$menu_items = wp_get_nav_menu_items( $menu );
			foreach ( $menu_items as $menu_item ) {
				if ( 'custom' === $menu_item->type ) {
					if ( substr( $menu_item->url, 0, strlen( $find_url ) ) === $find_url ) {
						$new_url = str_replace( $find_url, $replace_url, $menu_item->url );
						update_post_meta( $menu_item->ID, '_menu_item_url', $new_url );
					}
				}
			}
		}
	}

	/**
	 * Sets up the shop pages' custom fields.
	 *
	 * @since 1.3.0
	 *
	 * @param array $wc_pages
	 */
	public static function set_woocommerce_pages_custom_fields( $wc_pages ) {
		// Setup shop page custom fields.
		foreach ( $wc_pages as $page ) {
			if ( $page['page_id'] ) {
				foreach ( $page['meta_values'] as $key => $value ) {
					update_post_meta( $page['page_id'], $key, $value );
				}
			}
		}
	}

	/**
	 * Sets up the shop pages' in the menus.
	 *
	 * @since 1.3.0
	 *
	 * @param array $wc_pages
	 */
	public static function replace_woocommerce_nav_menu_items( $wc_pages ) {
		$menus = wp_get_nav_menus( array(
			'hide_empty' => true,
		) );

		foreach ( $menus as $menu ) {
			$menu_items = wp_get_nav_menu_items( $menu );
			foreach ( $menu_items as $menu_item ) {
				if ( 'post_type' === $menu_item->type ) {
					foreach ( $wc_pages as $wc_page ) {
						if ( ! empty( $wc_page['demo_page_id'] ) && (string) $menu_item->object_id === (string) $wc_page['demo_page_id'] ) {
							update_post_meta( $menu_item->ID, '_menu_item_object', 'page' );
							update_post_meta( $menu_item->ID, '_menu_item_object_id', (string) $wc_page['page_id'] );
						}
					}
				}
			}
		}
	}

	/**
	 * Clears/refreshes WooCommerce's transient data.
	 *
	 * @since 1.3.0
	 */
	public static function renew_woocommerce_transient_data() {
		if ( class_exists( 'WC_REST_System_Status_Tools_Controller' ) ) {
			$tools_controller = new WC_REST_System_Status_Tools_Controller();
			$tools_controller->execute_tool( 'clear_transients' );
			$tools_controller->execute_tool( 'clear_template_cache' );
			$tools_controller->execute_tool( 'recount_terms' );
			$tools_controller->execute_tool( 'regenerate_product_lookup_tables' );
		}
	}

	/**
	 * Clears/refreshes WooCommerce's transient data.
	 *
	 * @since 1.3.0
	 */
	public static function force_terms_recount() {
		// Try to force a term recount.
		// wp_defer_term_counting( false ) doesn't work properly as there are posts imported from different AJAX requests.
		$taxonomies = get_taxonomies( array(), 'names' );
		foreach ( $taxonomies as $taxonomy ) {
			$terms             = get_terms( $taxonomy, array( 'hide_empty' => false ) );
			$term_taxonomy_ids = wp_list_pluck( $terms, 'term_taxonomy_id' );

			wp_update_term_count( $term_taxonomy_ids, $taxonomy );
		}
	}

	/**
	 * Activates a theme variation.
	 *
	 * @since 1.0.0
	 */
	public function activate_variation() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to change theme options on this site.', 'ignition-public-opinion' ) );
		}

		// Verify nonce.
		if ( ! isset( $_POST['onboarding_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['onboarding_nonce'] ) ), 'onboarding_nonce' ) ) {
			die( 'Permission denied' );
		}

		$variation = isset( $_POST['variation'] ) ? sanitize_key( wp_unslash( $_POST['variation'] ) ) : '';

		if ( array_key_exists( $variation, ignition_public_opinion_get_theme_variations() ) ) {
			set_theme_mod( 'theme_variation', $variation );
		}

		die;
	}

	/**
	 * Resets theme modifications.
	 *
	 * @since 1.0.0
	 */
	public function reset_theme_mods() {
		// Check that we can actually perform this action.
		if ( ! isset( $this->data['theme_variations_page']['reset_mods_button'] ) || false === (bool) $this->data['theme_variations_page']['reset_mods_button'] ) {
			die( 'Permission denied' );
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to change theme options on this site.', 'ignition-public-opinion' ) );
		}

		// Verify nonce.
		if ( ! isset( $_POST['onboarding_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['onboarding_nonce'] ) ), 'onboarding_nonce' ) ) {
			die( 'Permission denied' );
		}

		$defaults   = ignition_public_opinion_ignition_customizer_defaults( 'all' );
		$theme_mods = get_theme_mods();

		// Only remove theme-specific theme mods.
		foreach ( $defaults as $key => $value ) {
			if ( array_key_exists( $key, $theme_mods ) ) {
				remove_theme_mod( $key );
			}
		}

		die;
	}

	/**
	 * Backs up the theme's modifications (theme_mods).
	 *
	 * Although the respective restore method only restores the theme's registered theme mods, this methods backs up all
	 * mods, just in case. This will likely come in handy in future updates, or manually recovering users' installations.
	 *
	 * @since 1.0.0
	 */
	public function backup_theme_mods() {
		// Check that we can actually perform this action.
		if ( ! isset( $this->data['theme_variations_page']['reset_mods_button'] ) || false === (bool) $this->data['theme_variations_page']['reset_mods_button'] ) {
			die( 'Permission denied' );
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to change theme options on this site.', 'ignition-public-opinion' ) );
		}

		// Verify nonce.
		if ( ! isset( $_POST['onboarding_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['onboarding_nonce'] ) ), 'onboarding_nonce' ) ) {
			die( 'Permission denied' );
		}

		$backup = array(
			'date'      => date_i18n( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) ),
			'variation' => function_exists( 'ignition_public_opinion_get_theme_variation' ) ? ignition_public_opinion_get_theme_variation() : '',
			'mods'      => get_theme_mods(),
		);

		update_option( 'ignition_public_opinion_mods_backup', $backup, false );

		// We don't want to return the actual mods in the response.
		unset( $backup['mods'] );

		wp_send_json_success( $backup );
	}

	/**
	 * Restores the backed up theme modifications (theme_mods).
	 *
	 * Although the respective backup method backs up all theme mods, only the theme's registered mods are restored.
	 *
	 * @since 1.0.0
	 */
	public function restore_theme_mods() {
		// Check that we can actually perform this action.
		if ( ! isset( $this->data['theme_variations_page']['reset_mods_button'] ) || false === (bool) $this->data['theme_variations_page']['reset_mods_button'] ) {
			die( 'Permission denied' );
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to change theme options on this site.', 'ignition-public-opinion' ) );
		}

		// Verify nonce.
		if ( ! isset( $_POST['onboarding_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['onboarding_nonce'] ) ), 'onboarding_nonce' ) ) {
			die( 'Permission denied' );
		}

		$theme_mods = get_theme_mods();
		$defaults   = ignition_public_opinion_ignition_customizer_defaults( 'all' );
		$backup     = get_option( 'ignition_public_opinion_mods_backup', false );

		if ( ! isset( $backup['mods'] ) || ! is_array( $backup['mods'] ) ) {
			wp_send_json_error( array(
				'code'   => '',
				'result' => 'Invalid or non-existing backup.',
			) );
		}

		foreach ( $defaults as $key => $value ) {
			unset( $theme_mods[ $key ] );
			if ( array_key_exists( $key, $backup['mods'] ) ) {
				$theme_mods[ $key ] = $backup['mods'][ $key ];
			}
		}

		$theme = get_option( 'stylesheet' );
		update_option( "theme_mods_{$theme}", $theme_mods );

		// We don't want to return the actual mods in the response.
		unset( $backup['mods'] );

		wp_send_json_success( $backup );
	}

	/**
	 * Action hook handler for theme updates checks. Intercepts theme update data before they are written into a transient.
	 * Don't use directly.
	 *
	 * Hooked on 'pre_set_site_transient_update_themes' action hook.
	 *
	 * @since 1.0.0
	 *
	 * @param object $transient An object containing the theme-update information returned by WordPress.org API.
	 *
	 * @return object
	 */
	public function update_check_admin_handler_maybe( $transient ) {
		if ( class_exists( 'CSSIgniter_Updater' ) ) {
			// Let the CSSIgniter Updater plugin handle updates.
			return $transient;
		}

		$latest_version = self::get_latest_theme_version();

		if ( ( false !== $latest_version ) && version_compare( $latest_version, $this->theme_version, '>' ) ) {
			$transient->checked[ $this->theme_slug ]  = $this->theme_version;
			$transient->response[ $this->theme_slug ] = array(
				'theme'       => $this->theme_slug,
				'new_version' => $latest_version,
				'url'         => "https://www.cssigniter.com/changelog/{$this->theme_slug}",
				'package'     => false,
			);
		}

		return $transient;
	}

	/**
	 * Checks and returns for the theme's latest version.
	 *
	 * @since 1.0.0
	 *
	 * @return false|string Theme version on success, false on error.
	 */
	public static function get_latest_theme_version() {
		/**
		 * Filters the frequency (in hours), that theme will check for updates.
		 *
		 * @since 1.0.0
		 *
		 * @param array $hours The number of hours between the update checks.
		 */
		$check_every     = apply_filters( 'ignition_public_opinion_check_updates_every_hours', 3 * 24 ) * HOUR_IN_SECONDS;
		$themes_json_url = 'https://www.cssigniter.com/theme_versions.json';

		$versions = get_site_transient( 'ignition_public_opinion_latest_theme_versions_json' );

		if ( false === $versions ) {
			$request = wp_safe_remote_get( $themes_json_url );

			if ( is_wp_error( $request ) || ( isset( $request['response']['code'] ) && 200 !== $request['response']['code'] ) ) {
				return false;
			}

			$body = wp_remote_retrieve_body( $request );

			$versions = json_decode( $body, true );

			set_site_transient( 'ignition_public_opinion_latest_theme_versions_json', $versions, $check_every );
		}

		if ( ! defined( 'IGNITION_PUBLIC_OPINION_NAME' ) || empty( $versions[ IGNITION_PUBLIC_OPINION_NAME ] ) ) {
			return false;
		}

		return $versions[ IGNITION_PUBLIC_OPINION_NAME ];
	}


	/**
	 * Returns the onboarding page's default configuration data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function default_data() {
		return array(
			// Required. Turns the onboarding page on/off.
			'show_page'                => true,
			// Optional. Turns the redirection to the onboarding page on/off.
			'redirect_on_activation'   => true,
			// Optional. The text to be used for the admin menu. If empty, defaults to "Theme options"
			'menu_title'               => __( 'Theme options', 'ignition-public-opinion' ),
			// Optional. The text to be displayed in the page's title tag. If empty, defaults to "Theme options"
			'page_title'               => __( 'Theme options', 'ignition-public-opinion' ),
			// Optional. The onboarding page's title, placeholders available :theme_name:, :theme_version:.
			'title'                    => ':theme_name:',
			// Optional. Boolean. Whether to show the logo. Default: true in normal mode. false if whitelabel.
			'logo_show'                => defined( 'IGNITION_PUBLIC_OPINION_WHITELABEL' ) && IGNITION_PUBLIC_OPINION_WHITELABEL ? false : true,
			// Optional. The logo's image source URL. Defaults to the bundled logo.
			'logo_src'                 => get_theme_file_uri( '/inc/onboarding/assets/images/cssigniter_logo.svg' ),
			// Required. The logo's link URL. Defaults to 'https://www.cssigniter.com/themes/' . IGNITION_PUBLIC_OPINION_NAME . '/'
			'logo_url'                 => 'https://www.cssigniter.com/themes/' . IGNITION_PUBLIC_OPINION_NAME . '/',
			// Optional. Set a 'latest_version' to compare against the current theme version. Set 'theme_update' to false to disable the version check.
			'theme_update'             => array(
				'latest_version' => self::get_latest_theme_version(),
				'link_url_1'     => '',
				'link_text_1'    => __( 'Download Now', 'ignition-public-opinion' ),
				'link_url_2'     => '',
				'link_text_2'    => __( 'Learn how to upgrade', 'ignition-public-opinion' ),
			),
			// Optional. The default active tab. Default 'getting_started'. Must be one of the keys in the tabs[] array.
			'default_tab'              => 'getting_started',
			// Optional. slug => label pairs for each tab. Empty label to disable. Defaults are as follows:
			'tabs'                     => array(
				'getting_started'  => __( 'Getting Started', 'ignition-public-opinion' ),
				'sample_content'   => __( 'Sample Content', 'ignition-public-opinion' ),
				'theme_variations' => __( 'Theme Variations', 'ignition-public-opinion' ),
			),
			'plugins' => array(
//				// Each plugin is registered as 'slug' => array()
//				'plugin-slug' => array(
//					// Required. The plugin's title.
//					'title'              => __( 'Plugin Title', 'ignition-public-opinion' ),
//					// Optional. The plugin's description, or why the plugin is required.
//					'description'        => '',
//					// Optional. If both 'version' and 'bundle' are set, the theme will prompt for a plugin update if applicable.
//					'version'            => '1.0',
//					// Optional. If true, the plugin zip will be searched in the theme's plugins/ directory, named "plugin-slug.zip". Default false.
//					'bundled'            => false,
//					// Optional. If passed string or array is callable, then the plugin will appear as activated.
//					'is_callable'        => '',
//					// Optional. If not passed, it's assumed to be "plugin-slug.php". Only pass a filename. It gets combined with the plugin slug as needed.
//					'plugin_file'        => '',
//					// Optional. Declares that the plugin is required. Default false.
//					'required'           => false,
//					// Optional. Declares that the plugin is recommended. Default false.
//					'recommended'        => false,
//					// Optional. Declares that the plugin must be active for sample content import to succeed. Default false.
//					'required_by_sample' => false,
//				),
			),
			'theme_variations_page'    => array(
				'reset_mods_button' => true,
				'variations'        => array(
//					// Each variation is registered as 'slug' => array()
//					'variation-slug' => array(
//						// Required. The variation's title.
//						'title'       => __( 'Variation Title', 'ignition-public-opinion' ),
//						// Optional. The variation's description.
//						'description' => '',
//						// Required. The variation's screenshot. Defaults to /theme-variations/variation-slug/screenshot.png and falls back to the theme's screenshot.
//						'screenshot'  => '',
//					),
				),
			),
			/*
			 * The following "shortcodes" can be used inside the page's content, and will be replaced by the onboarding page:
			 *
			 * [onboarding-plugin:plugin-slug] e.g. [onboarding-plugin:gutenbee]
			 * Outputs a plugin box.
			 *
			 * [onboarding-plugins:filter] e.g. [onboarding-plugins:required_by_sample]
			 * Outputs all plugins for which their "filter" part is true. "filter" corresponds to an array key inside the plugin's entry.
			 *
			 * [onboarding-import-box]
			 * Displays the get_sample_content_ready_box() function, i.e. the box that detects whether the requirements
			 * are satisfied to install the sample content.
			 */
			'getting_started_page'    => array(
				'text_before' => '',
				'content'     => '',
				'text_after'  => '',
			),
			'sample_content_page'      => array(
				'text_before' => '',
				'content'     => '',
				'text_after'  => '',
			),
			'sidebar_widgets'          => array(
//				'documentation' => array(
//					'title'       => __( 'Theme Documentation', 'ignition-public-opinion' ),
//					'description' => __( "If you don't want to import our demo sample content, just visit this page and learn how to set things up individually.", 'ignition-public-opinion' ),
//					'button_text' => __( 'View Documentation', 'ignition-public-opinion' ),
//					'button_url'  => 'https://www.cssigniter.com/docs/' . IGNITION_PUBLIC_OPINION_NAME . '/',
//				),
//				'kb'            => array(
//					'title'       => __( 'Knowledge Base', 'ignition-public-opinion' ),
//					'description' => __( 'Browse our library of step by step how-to articles, tutorials, and guides to get quick answers.', 'ignition-public-opinion' ),
//					'button_text' => __( 'View Knowledge Base', 'ignition-public-opinion' ),
//					'button_url'  => 'https://www.cssigniter.com/docs/knowledgebase/',
//				),
//				'support'       => array(
//					'title'       => __( 'Request Support', 'ignition-public-opinion' ),
//					'description' => __( 'Got stuck? No worries, just visit our support page, submit your ticket and we will be there for you within 24 hours.', 'ignition-public-opinion' ),
//					'button_text' => __( 'Request Support', 'ignition-public-opinion' ),
//					'button_url'  => 'https://www.cssigniter.com/support/',
//				),
//				'service-install' => array(
//					'title'       => __( 'Theme Installation Service', 'ignition-public-opinion' ),
//					'description' => __( 'We can install the theme for you and set everything up exactly like our demo website for only $69. Get in touch for more details.', 'ignition-public-opinion' ),
//					'button_text' => __( 'Get in touch', 'ignition-public-opinion' ),
//					'button_url'  => 'https://www.cssigniter.com/support-hub/',
//				),
			),
		);

	}

	/**
	 * Returns a properly formatted array for a plugin entry.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugin
	 *
	 * @return array
	 */
	public function plugin_entry_defaults( $plugin ) {
		return wp_parse_args( $plugin, array(
			// Required. The plugin's title.
			'title'              => __( 'Plugin Title', 'ignition-public-opinion' ),
			// Optional. The plugin's description, or why the plugin is required.
			'description'        => '',
			// Optional. E.g. '1.0'. If both 'version' and 'bundle' are set, the theme will prompt for a plugin update if applicable.
			'version'            => '',
			// Optional. If true, the plugin zip will be searched in the theme's plugins/ directory, named "plugin-slug.zip". Default false.
			'bundled'            => false,
			// Optional. If passed string or array is callable, then the plugin will appear as activated.
			'is_callable'        => '',
			// Optional. A URL where this plugin can be downloaded from. A download button appears only when 'download_url' is set and the plugin is not installed.
			'download_url'       => '',
			// Optional. Declares that the plugin must be active for sample content import to succeed. Default false.
			'required_by_sample' => false,
		) );
	}
}
