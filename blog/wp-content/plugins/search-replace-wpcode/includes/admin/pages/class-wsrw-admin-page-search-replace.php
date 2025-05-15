<?php
/**
 * Search & Replace admin page under Tools.
 *
 * @package Search_Replace_WPCode
 */

/**
 * Class for the Tools admin page.
 */
class WSRW_Admin_Page_Search_Replace extends WSRW_Admin_Page {

	/**
	 * The page slug to be used when adding the submenu.
	 *
	 * @var string
	 */
	public $page_slug = 'wsrw-search-replace';

	/**
	 * The action used for the nonce.
	 *
	 * @var string
	 */
	private $action = 'wsrw-search-replace';

	/**
	 * Default view.
	 *
	 * @var string
	 */
	public $view = 'search_replace';

	/**
	 * The nonce name field.
	 *
	 * @var string
	 */
	private $nonce_name = '_wpnonce';

	/**
	 * Available importers.
	 *
	 * @var WPCode_Importer_Type[]
	 */
	private $importers = array();

	/**
	 * The capability required to view this page.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Call this just to set the page title translatable.
	 */
	public function __construct() {
		$this->page_title = __( 'WP Search & Replace', 'search-replace-wpcode' );
		parent::__construct();
	}


	/**
	 * The Tools page output.
	 *
	 * @return void
	 */
	public function output_content() {
		if ( method_exists( $this, 'output_view_' . $this->view ) ) {
			call_user_func( array( $this, 'output_view_' . $this->view ) );
		}
	}

	/**
	 * Add page specific data to the JS localization object.
	 *
	 * @param array $data The data array.
	 *
	 * @return array
	 */
	public function add_page_data( $data ) {
		$data['rest_nonce']                = wp_create_nonce( 'wp_rest' );
		$data['upload_url']                = rest_url( 'wsrw/v1/upload-image' );
		$data['are_you_sure']              = esc_html__( 'Are you sure?', 'search-replace-wpcode' );
		$data['replace_media_confirm']     = esc_html__( 'This will replace the media file completely with the new file. This change is permanent, please make sure you have a copy of the replaced file if you need it.', 'search-replace-wpcode' );
		$data['image_replace_complete']    = esc_html__( 'Media Replace Completed', 'search-replace-wpcode' );
		$data['no_table_selected_title']   = esc_html__( 'No Tables Selected', 'search-replace-wpcode' );
		$data['no_table_selected_message'] = esc_html__( 'Please select at least 1 table to start the search and replace process.', 'search-replace-wpcode' );
		$data['no_results_found']          = esc_html__( 'No results found.', 'search-replace-wpcode' );
		$data['finished']                  = esc_html__( 'Processing completed.', 'search-replace-wpcode' );
		$data['sr_confirm_title']          = esc_html__( 'Are you sure?', 'search-replace-wpcode' );
		$data['sr_confirm_message']        = esc_html__( 'This will perform the search and replace operation on the selected tables. Please make sure you have a backup of your database before proceeding.', 'search-replace-wpcode' );
		$data['no_search_term_title']      = esc_html__( 'No Search Term', 'search-replace-wpcode' );
		$data['no_search_term_message']    = esc_html__( 'Please enter a search term to start the search and replace process.', 'search-replace-wpcode' );

		if ( 'settings' === $this->view ) {
			$data = $this->add_connect_strings( $data );
		}

		return $data;
	}


	/**
	 * Add the strings for the connect page to the JS object.
	 *
	 * @param array $data The localized data we already have.
	 *
	 * @return array
	 */
	public function add_connect_strings( $data ) {
		$data['oops']                = esc_html__( 'Oops!', 'search-replace-wpcode' );
		$data['ok']                  = esc_html__( 'OK', 'search-replace-wpcode' );
		$data['almost_done']         = esc_html__( 'Almost Done', 'search-replace-wpcode' );
		$data['plugin_activate_btn'] = esc_html__( 'Activate', 'search-replace-wpcode' );
		$data['server_error']        = esc_html__( 'Unfortunately there was a server connection error.', 'search-replace-wpcode' );

		return $data;
	}

	/**
	 * Add hooks for this page.
	 *
	 * @return void
	 */
	public function page_hooks() {
		add_filter( 'wsrw_admin_js_data', array( $this, 'add_page_data' ) );
		add_filter( 'submenu_file', array( $this, 'change_current_menu' ), 15, 2 );
	}

	/**
	 * Highlight the correct submenu regardless of the tab selected.
	 *
	 * @param string $submenu_file The current submenu file.
	 * @param string $parent_file The parent file.
	 *
	 * @return string
	 */
	public function change_current_menu( $submenu_file, $parent_file ) {
		return 'wsrw-search-replace';
	}

	/**
	 * The Import view.
	 *
	 * @return void
	 */
	public function output_view_search_replace() {
		$history_url = add_query_arg(
			array(
				'page' => 'wsrw-search-replace',
				'view' => 'history',
			),
			admin_url( 'tools.php' )
		);
		?>
		<div class="wsrw-setting-row wsrw-tools">
			<h3><?php esc_html_e( 'Search & Replace', 'search-replace-wpcode' ); ?></h3>
			<p><?php esc_html_e( 'Use the form below to perform a search & replace operation on your database.', 'search-replace-wpcode' ); ?></p>
		</div>
		<hr/>
		<form action="<?php echo esc_url( $this->get_page_action_url() ); ?>" method="post" autocomplete="off" id="wsrw-search-replace-form">
			<div class="wsrw-metabox-form-row">
				<div class="wsrw-metabox-form-row-label">
					<label for="wsrw-search">
						<?php esc_html_e( 'Search for', 'search-replace-wpcode' ); ?>
					</label>
				</div>
				<div class="wsrw-metabox-form-row-input">
					<input type="text" id="wsrw-search" name="search" value="" placeholder="<?php esc_attr_e( 'Search for', 'search-replace-wpcode' ); ?>"/>
				</div>
			</div>
			<div class="wsrw-metabox-form-row">
				<div class="wsrw-metabox-form-row-label">
					<label for="wsrw-replace">
						<?php esc_html_e( 'Replace with', 'search-replace-wpcode' ); ?>
					</label>
				</div>
				<div class="wsrw-metabox-form-row-input">
					<input type="text" id="wsrw-replace" name="replace" value="" placeholder="<?php esc_attr_e( 'Replace with', 'search-replace-wpcode' ); ?>"/>
				</div>
			</div>
			<div class="wsrw-metabox-form-row">
				<div class="wsrw-metabox-form-row-label">
					<label>
						<?php esc_html_e( 'Case Insensitive', 'search-replace-wpcode' ); ?>
					</label>
				</div>
				<div class="wsrw-metabox-form-row-input">
					<?php
					// Everything is escaped in the method.
					echo $this->get_checkbox_toggle( false, 'wsrw-case-insensitive', '', '1' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			</div>
			<div class="wsrw-metabox-form-row">
				<div class="wsrw-metabox-form-row-label">
					<label for="wsrw-tables">
						<?php esc_html_e( 'Select tables', 'search-replace-wpcode' ); ?>
					</label>
				</div>
				<div class="wsrw-metabox-form-row-input">
					<?php
					$tables = WSRW_Search_Replace::get_all_tables();
					?>
					<div class="wsrw-checkbox-multiselect-columns">
						<div class="first-column">
							<h5 class="header"><?php esc_html_e( 'Available Tables', 'search-replace-wpcode' ); ?></h5>
							<div class="wsrw-multiselect-search">
								<input class="wsrw-input-text" type="search" placeholder="<?php esc_attr_e( 'Search tables...', 'search-replace-wpcode' ); ?>"/>
							</div>
							<ul>
								<?php
								if ( empty( $tables ) ) {
									echo '<li>' . esc_html__( 'No tables found.', 'search-replace-wpcode' ) . '</li>';
								} else {
									foreach ( $tables as $table ) {
										printf(
											'<li><label><input type="checkbox" name="tables[]" value="%1$s">%2$s</label></li>',
											esc_attr( $table ),
											esc_html( $table )
										);
									}
								}
								?>
							</ul>

							<?php if ( ! empty( $tables ) ) : ?>
								<a href="#" class="all"><?php esc_html_e( 'Select All', 'search-replace-wpcode' ); ?></a>
							<?php endif; ?>

						</div>
						<div class=" second-column">
							<h5 class="header"><?php esc_html_e( 'Tables to Include', 'search-replace-wpcode' ); ?></h5>
							<ul></ul>
						</div>
					</div>
				</div>
			</div>
			<div class="wsrw-form-row">
				<button type="submit" class="wsrw-button" id="wsrw-start-replace"><?php esc_html_e( 'Preview Search & Replace', 'search-replace-wpcode' ); ?></button>
			</div>
		</form>
		<div class="wsrw-modal" id="wsrw-search-replace-progress">
			<div class="wsrw-modal-header">
				<button type="button" class="wsrw-just-icon-button wsrw-close-modal"><?php wsrw_icon( 'close', 15, 14 ); ?></button>
				<h2><?php esc_html_e( 'Preview Search & Replace', 'search-replace-wpcode' ); ?></h2>
			</div>
			<div class="wsrw-progress">
				<div class="wsrw-progress-bar" id="wsrw-progress-bar-replace">
					<div class="wsrw-progress-bar-inner"></div>
				</div>
				<div class="wsrw-progress-text" id="wsrw-progress-text-replace"></div>
			</div>
			<h2><?php esc_html_e( 'Results', 'search-replace-wpcode' ); ?></h2>
			<div id="wsrw-results">
				<table class="wsrw-search-results widefat" id="wsrw-results-table">
					<tr>
						<th><span class="wsrw-check-row-input"><input type="checkbox" id="wsrw-check-all"/></span></th>
						<th><?php echo esc_html_x( 'Table', 'Database table name.', 'search-replace-wpcode' ); ?></th>
						<th><?php echo esc_html_x( 'Column', 'Which column in the table is affected.', 'search-replace-wpcode' ); ?></th>
						<th><?php echo esc_html_x( 'Row', 'Which row in the table is affected.', 'search-replace-wpcode' ); ?></th>
						<th class="wsrw-old-value"><?php echo esc_html_x( 'Before', 'The value before the replace.', 'search-replace-wpcode' ); ?></th>
						<th class="wsrw-new-value"><?php echo esc_html_x( 'After', 'The value after the replace.', 'search-replace-wpcode' ); ?></th>
					</tr>
				</table>
			</div>
			<div class="wsrw-modal-buttons">
				<button id="wsrw-perform-search-replace" class="wsrw-button wsrw-button-primary" disabled><?php esc_html_e( 'Replace All', 'search-replace-wpcode' ); ?></button>
				<a href="<?php echo esc_url( $history_url ); ?>" class="wsrw-button wsrw-button-secondary" id="wsrw-results-undo-button"><?php esc_html_e( 'History & Undo', 'search-replace-wpcode' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * For this page we output a menu.
	 *
	 * @return void
	 */
	public function output_header_bottom() {
		?>
		<ul class="wsrw-admin-tabs">
			<?php
			foreach ( $this->views as $slug => $label ) {
				if ( 'importer' === $slug || 'unused_media_backups' === $slug ) {
					continue;
				}
				$class = $this->view === $slug ? 'active' : '';
				?>
				<li>
					<a href="<?php echo esc_url( $this->get_view_link( $slug ) ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></a>
				</li>
			<?php } ?>
		</ul>
		<?php
	}

	/**
	 * The Replace Image view.
	 *
	 * @return void
	 */
	public function output_view_replace_media() {
		// Let's check permissions first.
		if ( ! current_user_can( 'upload_files' ) ) {
			echo '<h2>' . esc_html__( 'You do not have permission to upload files.', 'search-replace-wpcode' ) . '</h2>';

			return;
		}

		if ( ! isset( $_GET['media_id'] ) ) {
			$this->get_media_list();

			return;
		}

		if ( ! isset( $_GET[ $this->nonce_name ] ) || ! wp_verify_nonce( sanitize_key( $_GET[ $this->nonce_name ] ), 'wsrw_replace_media' ) ) {
			echo '<h2>' . esc_html__( 'Link expired, please try again.', 'search-replace-wpcode' ) . '</h2>';

			return;
		}

		$media_id = absint( $_GET['media_id'] );
		// Let's check if the id is valid for replacing.
		$media = get_post( $media_id );
		if ( ! $media || 'attachment' !== $media->post_type ) {
			echo '<h2>' . esc_html__( 'Invalid media ID.', 'search-replace-wpcode' ) . '</h2>';

			return;
		}
		?>
		<div class="wsrw-setting-row wsrw-tools">
			<h3><?php esc_html_e( 'Replace Media', 'search-replace-wpcode' ); ?></h3>
			<p>
				<?php

				$image_file = _x( 'file', 'file type', 'search-replace-wpcode' );
				$accept     = '';
				if ( wp_attachment_is_image( $media_id ) ) {
					$image_file = _x( 'image', 'file type', 'search-replace-wpcode' );
					$accept     = 'image/*';
				}

				printf(
				// Translators: %1$s is the file name. %2$s and %3$s are bold/strong tags.
					esc_html__( 'Choose a new %4$s that will replace %1$s. The new %4$s will completely replace the current %4$s. %2$sThis change cannot be undone.%3$s', 'search-replace-wpcode' ),
					'<strong>' . esc_html( basename( get_attached_file( $media_id ) ) ) . '</strong>',
					'<strong>',
					'</strong>',
					esc_html( $image_file )
				);
				?>
			</p>
		</div>
		<hr/>
		<div class="wsrw-media-replace">
			<div class="wsrw-media-replace-preview">
				<div class="wsrw-current-media-preview wsrw-current-media-preview-<?php echo esc_attr( $image_file ); ?>" id="wsrw-media-current-image">
					<?php
					if ( wp_attachment_is_image( $media_id ) ) {
						echo wp_get_attachment_image( $media_id, 'large' );
					} else {
						?>
						<div class="wsrw-media-placeholder">
							<?php echo '<img src="' . esc_url( includes_url( 'images/media/document.jpg' ) ) . '" alt="' . esc_attr( $media->post_title ) . '"/>'; ?>
							<span class="wsrw-media-placeholder-text">
								<?php echo esc_html( basename( get_attached_file( $media_id ) ) ); ?>
							</span>
						</div>
					<?php } ?>
				</div>
				<div class="wsrw-new-media-preview wsrw-current-media-preview-<?php echo esc_attr( $image_file ); ?>">
					<div class="wsrw-media-placeholder" id="wsrw-media-preview-placeholder">
						<span class="wsrw-media-placeholder-text">
							<span><?php esc_html_e( 'Drag & Drop', 'search-replace-wpcode' ); ?></span>
							<span><?php esc_html_e( 'or', 'search-replace-wpcode' ); ?></span>
							<?php
							printf(
								// Translators: %1$s is either file or image depending on the file type.
								esc_html__( 'Choose a new %1$s to see a preview here', 'search-replace-wpcode' ),
								esc_html( $image_file )
							);
							?>
						</span>
					</div>
					<div id="wsrw-media-preview"></div>
				</div>
			</div>
			<form id="wsrw-media-replace-form">
				<input type="hidden" name="media_id" id="wsrw-media-id" value="<?php echo esc_attr( $media_id ); ?>"/>

				<div class="wsrw-file-upload">
					<input type="file" name="file" id="wsrw-media-file" class="inputfile" data-multiple-caption="{count} files selected" accept="<?php echo esc_attr( $accept ); ?>">
					<label for="wsrw-media-file">
						<span class="wsrw-file-field"><span class="placeholder"><?php esc_html_e( 'No file chosen', 'search-replace-wpcode' ); ?></span></span>
						<strong class="wsrw-button wsrw-button-secondary wsrw-button-icon">
							<?php
							wsrw_icon( 'upload', 12, 12 );
							esc_html_e( 'Choose a file&hellip;', 'search-replace-wpcode' );
							?>
						</strong>
					</label>
					<button type="reset" class="wsrw-button wsrw-button-secondary" id="wsrw-clear-form" disabled><?php esc_html_e( 'Clear', 'search-replace-wpcode' ); ?></button>
				</div>
				<div class="wsrw-media-replace-buttons">
					<button type="submit" class="wsrw-button wsrw-button-primary" id="wsrw-start-replace" disabled><?php esc_html_e( 'Replace Source File', 'search-replace-wpcode' ); ?></button>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Get the list of media for quick access.
	 *
	 * @return void
	 */
	public function get_media_list() {

		// Let's query just image files.
		$media = get_posts(
			array(
				'post_type'      => 'attachment',
				'posts_per_page' => 20,
				'orderby'        => 'ID',
				'order'          => 'DESC',
			)
		);

		?>
		<div class="wsrw-setting-row wsrw-tools">
			<h3><?php esc_html_e( 'Replace Media', 'search-replace-wpcode' ); ?></h3>
			<p><?php esc_html_e( 'Choose a recently uploaded file to replace.', 'search-replace-wpcode' ); ?></p>
		</div>
		<hr/>
		<div class="wsrw-media-list">
			<?php
			if ( empty( $media ) ) {
				echo '<p>' . esc_html__( 'No media found.', 'search-replace-wpcode' ) . '</p>';
			} else {
				?>
				<ul class="wsrw-media-list-holder">
					<?php
					foreach ( $media as $attachment ) {
						$thumb       = wp_get_attachment_image_src( $attachment->ID );
						$replace_url = WSRW_Image_Replace::get_replace_page_url( $attachment );
						$is_image    = wp_attachment_is_image( $attachment );
						$filename    = esc_html( basename( get_attached_file( $attachment->ID ) ) );
						$class       = 'wsrw-media-list-item-image';
						if ( $is_image ) {
							$thumb = $thumb[0];
						} else {
							$thumb = includes_url( 'images/media/document.png' );
							$class = 'wsrw-media-list-item-file';
						}
						?>
						<li class="wsrw-media-list-item">
							<div class="<?php echo esc_attr( $class ); ?>">
								<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $attachment->post_title ); ?>"/>
								<?php
								if ( ! $is_image ) {
									?>
									<span class=""><?php echo esc_html( $filename ); ?></span>
									<?php
								}
								?>
							</div>
							<div class="wsrw-media-list-item-buttons">
								<a href="<?php echo esc_url( $replace_url ); ?>" class="wsrw-button wsrw-button-secondary">
									<?php esc_html_e( 'Replace', 'search-replace-wpcode' ); ?>
								</a>
							</div>
						</li>
						<?php
					}
					?>
				</ul>
				<?php
			}
			?>
		</div>
		<div>
			<a href="<?php echo esc_url( admin_url( 'upload.php' ) ); ?>" class="wsrw-button">
				<?php esc_html_e( 'View all media files', 'search-replace-wpcode' ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Setup page-specific views.
	 *
	 * @return void
	 */
	protected function setup_views() {
		$this->views = array(
			'search_replace'      => esc_html__( 'Search & Replace', 'search-replace-wpcode' ),
			'history'             => esc_html__( 'History', 'search-replace-wpcode' ),
			'replace_media'       => esc_html__( 'Replace Media', 'search-replace-wpcode' ),
			'remove_unused_media' => esc_html__( 'Remove Unused Media', 'search-replace-wpcode' ),
			'settings'            => esc_html__( 'Settings', 'search-replace-wpcode' ),
		);
	}

	/**
	 * Output the settings view.
	 *
	 * @return void
	 */
	public function output_view_settings() {
		$this->metabox_row(
			esc_html__( 'License Key', 'search-replace-wpcode' ),
			$this->get_license_key_field(),
			'wsrw-setting-license-key'
		);
	}

	/**
	 * Get the license key field.
	 *
	 * @return string
	 */
	public function get_license_key_field() {
		ob_start();
		?>
		<div class="wsrw-metabox-form">
			<p><?php esc_html_e( 'You\'re using Search & Replace Everything Lite - no license needed. Enjoy!', 'search-replace-wpcode' ); ?>
				<img draggable="false" role="img" class="emoji" alt="🙂" src="<?php echo esc_url( WSRW_PLUGIN_URL . '/admin/images/smiley.svg' ); ?>">
			</p>
			<p>
				<?php
				printf(
				// Translators: %1$s - Opening anchor tag, do not translate. %2$s - Closing anchor tag, do not translate.
					esc_html__( 'To unlock more features consider %1$supgrading to PRO%2$s.', 'search-replace-wpcode' ),
					'<strong><a href="' . esc_url( wsrw_utm_url( 'https://wpcode.com/srlite/', 'settings-license', 'upgrading-to-pro' ) ) . '" target="_blank" rel="noopener noreferrer">',
					'</a></strong>'
				)
				?>
			</p>
			<hr>
			<p><?php esc_html_e( 'Already purchased? Simply enter your license key below to enable Search & Replace Everything PRO!', 'search-replace-wpcode' ); ?></p>
			<p>
				<input type="password" class="wsrw-input-text" id="wsrw-settings-upgrade-license-key" placeholder="<?php esc_attr_e( 'Paste license key here', 'search-replace-wpcode' ); ?>" value="">
				<button type="button" class="wsrw-button" id="wsrw-settings-connect-btn">
					<?php esc_html_e( 'Verify Key', 'search-replace-wpcode' ); ?>
				</button>
			</p>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Output the history view.
	 *
	 * @return void
	 */
	public function output_view_history() {
		?>
		<div class="wsrw-setting-row wsrw-tools">
			<h3><?php esc_html_e( 'History', 'search-replace-wpcode' ); ?></h3>
			<p><?php esc_html_e( 'On this page you can see a list of previous search & replace operations that you can undo.', 'search-replace-wpcode' ); ?></p>
		</div>
		<hr/>
		<?php
		$operations = $this->get_operations();
		$this->history_table( $operations );
	}

	/**
	 * Output the unused media remove view.
	 *
	 * @return void
	 */
	public function output_view_remove_unused_media() {
		?>
		<div class="wsrw-setting-row wsrw-tools">
			<h3><?php esc_html_e( 'Remove Unused Media', 'search-replace-wpcode' ); ?></h3>
			<p><?php esc_html_e( 'Scan your website for unused media files and easily remove them. The scanner will search for references to files across the entire database. Please note that if files are used in template files or referenced externally they might still appear unused.', 'search-replace-wpcode' ); ?></p>
		</div>
		<hr/>
		<?php
		$this->unused_media_table();
	}

	/**
	 * Get the history up-sell box.
	 *
	 * @return void
	 */
	public function get_history_upsell() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::get_upsell_box(
			esc_html__( 'Search & Replace History is a PRO feature', 'search-replace-wpcode' ),
			'<p>' . esc_html__( 'Upgrade today and view a history of all search & replace operations performed on your database and undo them with just 1-click directly from the WordPress admin.', 'search-replace-wpcode' ) . '</p>' .
			'<p>' . esc_html__( 'Please note: the operations are only recorded after you upgrade so any replacements made using the lite plugin will not be available for undo.', 'search-replace-wpcode' ) . '</p>',
			array(
				'text' => esc_html__( 'Upgrade Now', 'search-replace-wpcode' ),
				'url'  => esc_url( wsrw_utm_url( 'https://wpcode.com/srlite/', 'settings', 'tab-' . $this->view, 'upgrade' ) ),
			),
			array(),
			array(
				esc_html__( 'Undo Search & Replace', 'search-replace-wpcode' ),
				esc_html__( 'Replace Images from the Gutenberg Editor', 'search-replace-wpcode' ),
				esc_html__( 'Streamline Content Replacements', 'search-replace-wpcode' ),
				esc_html__( 'Reduce Server Load', 'search-replace-wpcode' ),
			)
		);
	}

	/**
	 * Get the Remove Unused media up-sell box.
	 *
	 * @return void
	 */
	public function get_unused_media_upsell() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::get_upsell_box(
			esc_html__( 'Remove Unused Media is a PRO feature', 'search-replace-wpcode' ),
			'<p>' . esc_html__( 'Easily scan your website and identify media files that are not used in any posts or pages. Our scanner goes one step further and checks the whole database for other places where the media file URLs are being used. This ensures that you won\'t delete files that are being used in your site templates or plugin settings.', 'search-replace-wpcode' ) . '</p>',
			array(
				'text' => esc_html__( 'Upgrade Now', 'search-replace-wpcode' ),
				'url'  => esc_url( wsrw_utm_url( 'https://wpcode.com/srlite/', 'settings', 'tab-' . $this->view, 'upgrade' ) ),
			),
			array(),
			array(
				esc_html__( 'Easy Scanning', 'search-replace-wpcode' ),
				esc_html__( 'Save Storage Space', 'search-replace-wpcode' ),
				esc_html__( 'Scan Whole Database', 'search-replace-wpcode' ),
				esc_html__( '1-click Remove Unused Media', 'search-replace-wpcode' ),
			)
		);
	}

	/**
	 * Get the operations for the history table.
	 *
	 * @return array
	 */
	public function get_operations() {
		return $this->get_placeholder_operations();
	}

	/**
	 * Output the history table.
	 *
	 * @param object[] $operations The operations.
	 *
	 * @return void
	 */
	public function history_table( $operations ) {
		$this->before_table();
		?>
		<table class="wp-list-table widefat fixed striped">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Search for', 'search-replace-wpcode' ); ?></th>
				<th><?php esc_html_e( 'Replace with', 'search-replace-wpcode' ); ?></th>
				<th><?php esc_html_e( 'Author', 'search-replace-wpcode' ); ?></th>
				<th><?php esc_html_e( 'Created', 'search-replace-wpcode' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'search-replace-wpcode' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $operations as $operation ) {
				$author = get_user_by( 'id', $operation->author_id );
				?>
				<tr>
					<td><?php echo esc_html( $operation->search_string ); ?></td>
					<td><?php echo esc_html( $operation->replace_string ); ?></td>
					<td><?php echo esc_html( $author->display_name ); ?></td>
					<td><?php echo esc_html( $operation->created ); ?></td>
					<td>
						<?php
						if ( $operation->undo_complete ) :
							printf(
							// Translators: %1$s - user display name, %2$s - undo date.
								esc_html__( 'Undone by %1$s on %2$s', 'search-replace-wpcode' ),
								esc_html( get_user_by( 'id', $operation->undo_author )->display_name ),
								esc_html( $operation->undo_date )
							);
						else :
							?>
							<button class="button button-secondary wsrw-preview-operation" data-operation-id="<?php echo esc_attr( $operation->operation_id ); ?>"><?php esc_html_e( 'Preview', 'search-replace-wpcode' ); ?></button>
							<button class="button button-secondary wsrw-undo-operation" data-operation-id="<?php echo esc_attr( $operation->operation_id ); ?>"><?php esc_html_e( 'Undo', 'search-replace-wpcode' ); ?></button>
						<?php endif; ?>

					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>

		<div class="wsrw-modal" id="wsrw-search-replace-progress">
			<div class="wsrw-modal-header">
				<button type="button" class="wsrw-just-icon-button wsrw-close-modal"><?php wsrw_icon( 'close', 15, 14 ); ?></button>
				<h2><?php esc_html_e( 'Preview Undo Search & Replace', 'search-replace-wpcode' ); ?></h2>
			</div>
			<div id="wsrw-results">
				<table class="wsrw-search-results widefat" id="wsrw-results-table">
					<tr>
						<th><?php echo esc_html_x( 'Table', 'Database table name.', 'search-replace-wpcode' ); ?></th>
						<th><?php echo esc_html_x( 'Column', 'Which column in the table is affected.', 'search-replace-wpcode' ); ?></th>
						<th><?php echo esc_html_x( 'Row', 'Which row in the table is affected.', 'search-replace-wpcode' ); ?></th>
						<th class="wsrw-old-value"><?php echo esc_html_x( 'Before', 'The value before the replace.', 'search-replace-wpcode' ); ?></th>
						<th class="wsrw-new-value"><?php echo esc_html_x( 'After', 'The value after the replace.', 'search-replace-wpcode' ); ?></th>
					</tr>
				</table>
			</div>
			<div class="wsrw-modal-buttons">
				<button id="wsrw-perform-undo" class="wsrw-button wsrw-button-primary"><?php esc_html_e( 'Undo', 'search-replace-wpcode' ); ?></button>
				<a href="" class="wsrw-button wsrw-button-secondary" id="wsrw-close"><?php esc_html_e( 'Close', 'search-replace-wpcode' ); ?></a>
			</div>
		</div>
		<?php
		$this->after_table();
	}

	/**
	 * Output the before table content.
	 *
	 * @return void
	 */
	public function before_table() {
		echo '<div class="wsrw-blur-area">';
	}

	/**
	 * Output the after table content.
	 *
	 * @return void
	 */
	public function after_table() {
		echo '</div>';
		$this->get_history_upsell();
	}

	/**
	 * Output the Unused Media table.
	 *
	 * @return void
	 */
	public function unused_media_table() {
		$media_items = $this->get_unused_media_items();
		$this->before_table();
		$show_occurences = apply_filters( 'wsrw_unused_media_show_used_outside_posts', false );
		?>
		<div>
			<button id="wsrw-start-media-scan" class="wsrw-button "><?php esc_html_e( 'Begin Scan', 'search-replace-wpcode' ); ?></button>
			<button id="wsrw-stop-media-scan" class="wsrw-button "><?php esc_html_e( 'Stop Scan', 'search-replace-wpcode' ); ?></button>
			<a href="<?php echo esc_url( $this->get_view_link( 'unused_media_backups' ) ); ?>" class="wsrw-button wsrw-button-secondary"><?php esc_html_e( 'Backups', 'search-replace-wpcode' ); ?></a>
		</div>
		<div class="wsrw-progress-bar-container">
			<div class="wsrs-progress-steps">
				<p>
					<?php esc_html_e( 'Scanning site for unused media. You can leave this page and return later.', 'search-replace-wpcode' ); ?>
				</p>
				<p>
					<?php
					printf(
					// Translators: %s is the percentage of the scan.
						esc_html__( 'Completed so far: %s', 'search-replace-wpcode' ),
						'<span class="wsrw-percentage"></span>'
					);
					?>
				</p>
				<p>
					<?php
					printf(
						esc_html__( 'Estimated time remaining: %s', 'search-replace-wpcode' ),
						'<span class="wsrw-remaining-time"></span>'
					);
					?>
				</p>
			</div>
			<div class="wsrw-progress-bar wsrw-unused-media-progress-bar" id="wsrw-progress-bar-replace">
				<div class="wsrw-progress-bar-inner"></div>
			</div>
		</div>
		<p class="wsrw-no-results"></p>
		<main class="wsrw-unused-media-main">
			<header class="wsrw-unused-media-header">
				<div class="wsrw-control-container">
					<div class="wsrw-total-media-results"><?php $this->get_media_scan_results(); ?></div>
				</div>
				<div class="wsrw-pagination-controls">
					<button class="wsrw-pagination-first wsrw-button">«</button>
					<button class="wsrw-pagination-prev wsrw-button">‹</button>
					<span><?php esc_html_e( 'Page', 'search-replace-wpcode' ); ?></span>
					<input type="number" class="wsrw-pagination-input wsrw-input" min="1" value="1" style="width: 51px; text-align: center;"/>
					<span><?php esc_html_e( 'of', 'search-replace-wpcode' ); ?> <span class="wsrw-pagination-total-pages"></span></span>
					<button class="wsrw-pagination-next wsrw-button">›</button>
					<button class="wsrw-pagination-last wsrw-button">»</button>
				</div>
			</header>
			<div class="wsrw-selected-size-of-media"></div>
			<div id="wsrw-results" class="wsrw-unused-media-results">
				<table class="wsrw-search-results widefat" id="wsrw-results-table">
					<thead>
					<tr>
						<th>
					<span class="wsrw-check-row-input">
						<input type="checkbox" id="wsrw-check-all"/>
					</span>
						</th>
						<th><?php echo esc_html_x( 'Thumbnail', 'Media Thumbnail.', 'search-replace-wpcode' ); ?></th>
						<th><?php echo esc_html_x( 'Title', 'Media Title.', 'search-replace-wpcode' ); ?></th>
						<th><?php echo esc_html_x( 'Path', 'Absolute path of the media file.', 'search-replace-wpcode' ); ?></th>
						<?php if ( $show_occurences ) { ?>
							<th><?php echo esc_html_x( 'Database Occurrences', 'Where the media file is mentioned.', 'search-replace-wpcode' ); ?></th>
						<?php } ?>
						<th><?php echo esc_html_x( 'Size', 'File size.', 'search-replace-wpcode' ); ?></th>
					</tr>
					</thead>
					<tbody id="wsrw-unused-media-list">
					<?php
					foreach ( $media_items as $item ) {
						$title = esc_html( $item['title'] );
						if ( ! empty( $item['admin_url'] ) ) {
							$title = '<a href="' . esc_url( $item['admin_url'] ) . '">' . $title . '</a>';
						}
						echo '<tr>';
						echo '<td><span class="wsrw-check-row-input"><input type="checkbox"/></span></td>';
						echo '<td><img src="' . esc_html( $item['thumbnail'] ) . '"></td>';
						echo '<td>' . esc_html( $title ) . '</td>';
						echo '<td>' . esc_html( $item['path'] ) . '</td>';
						if ( $show_occurences ) {
							echo '<td>' . esc_html( $item['occurrences'] ) . '</td>';
						}
						echo '<td>' . esc_html( $item['size'] ) . '</td>';
						echo '</tr>';
					}
					?>
					</tbody>
					<tbody id="wsrw-skeleton-loader">
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					</tbody>
				</table>
			</div>
			<footer class="wsrw-unused-media-footer">
				<div class="wsrw-navigation-buttons">
					<button id="wsrw-delete-selected-unused-media" class="wsrw-button" disabled><?php esc_html_e( 'Delete Selected', 'search-replace-wpcode' ); ?></button>
					<button id="wsrw-delete-all-unused-media" class="wsrw-button"><?php esc_html_e( 'Delete ALL', 'search-replace-wpcode' ); ?></button>
				</div>
				<div class="wsrw-pagination-controls">
					<button class="wsrw-pagination-first wsrw-button">«</button>
					<button class="wsrw-pagination-prev wsrw-button">‹</button>
					<span><?php esc_html_e( 'Page', 'search-replace-wpcode' ); ?></span>
					<input type="number" class="wsrw-pagination-input wsrw-input" min="1" value="1" style="width: 51px; text-align: center;"/>
					<span><?php esc_html_e( 'of', 'search-replace-wpcode' ); ?> <span class="wsrw-pagination-total-pages"></span></span>
					<button class="wsrw-pagination-next wsrw-button">›</button>
					<button class="wsrw-pagination-last wsrw-button">»</button>
				</div>
			</footer>
		</main>
		<?php
		$this->after_unused_media_table();
	}

	/**
	 * Output the after unused media table content.
	 *
	 * @return void
	 */
	public function after_unused_media_table() {
		echo '</div>';
		$this->get_unused_media_upsell();
	}

	/**
	 * Returns an array of placeholder operation objects to populate the history table.
	 *
	 * @return array
	 */
	public function get_placeholder_operations() {

		$operations   = array();
		$operations[] = (object) array(
			'search_string'  => 'Hello World',
			'replace_string' => 'Welcome to WPCode',
			'author_id'      => 2,
			'created'        => '2023-04-01 09:15:00',
			'operation_id'   => 1,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'wp-admin',
			'replace_string' => 'admin-dashboard',
			'author_id'      => 3,
			'created'        => '2023-04-02 10:30:00',
			'operation_id'   => 2,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'theme',
			'replace_string' => 'template',
			'author_id'      => 4,
			'created'        => '2023-04-03 11:45:00',
			'operation_id'   => 3,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'plugin',
			'replace_string' => 'extension',
			'author_id'      => 5,
			'created'        => '2023-04-04 12:00:00',
			'operation_id'   => 4,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'post',
			'replace_string' => 'article',
			'author_id'      => 1,
			'created'        => '2023-04-05 13:15:00',
			'operation_id'   => 5,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'comment',
			'replace_string' => 'feedback',
			'author_id'      => 2,
			'created'        => '2023-04-06 14:30:00',
			'operation_id'   => 6,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'category',
			'replace_string' => 'section',
			'author_id'      => 3,
			'created'        => '2023-04-07 15:45:00',
			'operation_id'   => 7,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'tag',
			'replace_string' => 'label',
			'author_id'      => 4,
			'created'        => '2023-04-08 16:00:00',
			'operation_id'   => 8,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'user',
			'replace_string' => 'member',
			'author_id'      => 5,
			'created'        => '2023-04-09 17:15:00',
			'operation_id'   => 9,
			'undo_complete'  => false,
		);

		$operations[] = (object) array(
			'search_string'  => 'WP Code',
			'replace_string' => 'WPCode',
			'author_id'      => 1,
			'created'        => '2023-04-10 18:30:00',
			'operation_id'   => 10,
			'undo_complete'  => false,
		);

		// Let's loop through all the objects and make sure the author_id is the current user id.
		foreach ( $operations as $key => $operation ) {
			$operations[ $key ]->author_id = get_current_user_id();
		}

		return $operations;
	}

	/**
	 * Returns an array of placeholders to populate the unused media table.
	 *
	 * @return array
	 */
	protected function get_unused_media_items() {

		return array(
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy10.jpg',
				'title'       => 'Orange Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '',
				'size'        => '500 KB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy9.jpg',
				'title'       => 'Green Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '',
				'size'        => '1.2 MB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy8.jpg',
				'title'       => 'Yellow Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '',
				'size'        => '750 KB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy7.jpg',
				'title'       => 'Gray Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '',
				'size'        => '750 KB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy6.jpg',
				'title'       => 'Orange Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '',
				'size'        => '500 KB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy5.jpg',
				'title'       => 'Black Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '',
				'size'        => '1.2 MB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy4.jpg',
				'title'       => 'Gold Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '',
				'size'        => '750 KB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy3.jpg',
				'title'       => 'Brown Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '2 Occurrences',
				'size'        => '750 KB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy1.jpg',
				'title'       => 'Blue Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '2 Occurrences',
				'size'        => '750 KB',
			),
			array(
				'thumbnail'   => WSRW_PLUGIN_URL . 'admin/images/dummy2.jpg',
				'title'       => 'Pink Avatar',
				'path'        => 'wp-content/uploads/2024/02/unused2.jpg',
				'occurrences' => '5 Occurrences',
				'size'        => '750 KB',
			),
		);
	}

	/**
	 * Get the media scan results.
	 *
	 * @return void;
	 */
	public function get_media_scan_results() {
	}
}
