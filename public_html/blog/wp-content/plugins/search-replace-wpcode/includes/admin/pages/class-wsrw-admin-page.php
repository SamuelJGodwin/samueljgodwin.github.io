<?php
/**
 * Admin pages abstract class.
 *
 * @package Search_Replace_WPCode
 */

/**
 * Class Admin_Page
 */
abstract class WSRW_Admin_Page {

	/**
	 * The page slug.
	 *
	 * @var string
	 */
	public $page_slug = '';

	/**
	 * The page title.
	 *
	 * @var string
	 */
	public $page_title = '';

	/**
	 * The menu title, defaults to the page title.
	 *
	 * @var string
	 */
	public $menu_title;

	/**
	 * If there's an error message, let's store it here.
	 *
	 * @var string
	 */
	public $message_error;

	/**
	 * If there's a success message, store it here.
	 *
	 * @var string
	 */
	public $message_success;
	/**
	 * The code type to be used by CodeMirror.
	 *
	 * @var string
	 */
	public $code_type = 'html';
	/**
	 * Whether the current user can edit the code on the current page.
	 *
	 * @var bool
	 */
	protected $can_edit = false;
	/**
	 * If true, the snippet library is shown, otherwise, we display
	 * the snippet editor.
	 *
	 * @var bool
	 */
	protected $show_library = false;

	/**
	 * The current view.
	 *
	 * @var string
	 */
	public $view = '';

	/**
	 * The available views for this page.
	 *
	 * @var array
	 */
	public $views = array();

	/**
	 * If the submenu for the page should be hidden, set this to true.
	 *
	 * @var bool
	 */
	public $hide_menu = false;

	/**
	 * The capability needed for the current user to view this page.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( ! isset( $this->menu_title ) ) {
			$this->menu_title = $this->page_title;
		}

		$this->hooks();
	}

	/**
	 * Add hooks to register the page and output content.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		// Only load if we are actually on the desired page.
		if ( $this->page_slug !== $page ) {
			return;
		}
		if ( ! current_user_can( $this->capability ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'search-replace-wpcode' ) );
		}
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
		add_action( 'wsrw_admin_page', array( $this, 'output' ) );
		add_action( 'wsrw_admin_page', array( $this, 'output_footer' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'page_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'page_specific_body_class' ) );
		add_action( 'in_admin_footer', array( $this, 'wsrw_footer' ) );

		$this->setup_views();
		$this->set_current_view();
		$this->page_hooks();
	}

	/**
	 * Override in child class to define page-specific hooks that will run only
	 * after checks have been passed.
	 *
	 * @return void
	 */
	public function page_hooks() {
	}

	/**
	 * Add the submenu page.
	 *
	 * @return void
	 */
	public function add_page() {
		add_submenu_page(
			'tools.php',
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->page_slug,
			array(
				wsrw_main()->admin_page_loader,
				'admin_menu_page',
			)
		);
	}

	/**
	 * If the page has views, this is where you should assign them to $this->views.
	 *
	 * @return void
	 */
	protected function setup_views() {
	}

	/**
	 * Set the current view from the query param also checking it's a registered view for this page.
	 *
	 * @return void
	 */
	protected function set_current_view() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['view'] ) ) {
			return;
		}
		$view = sanitize_text_field( wp_unslash( $_GET['view'] ) );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		if ( array_key_exists( $view, $this->views ) ) {
			$this->view = $view;
		}
	}

	/**
	 * Output the page content.
	 *
	 * @return void
	 */
	public function output() {
		$this->output_header();
		?>
		<div class="wsrw-content">
			<?php
			$this->output_content();
			do_action( "wsrw_admin_page_content_{$this->page_slug}", $this );
			?>
		</div>
		<?php
	}

	/**
	 * Output of the header markup for admin pages.
	 *
	 * @return void
	 */
	public function output_header() {
		?>
		<div class="wsrw-header">
			<div class="wsrw-header-top">
				<div class="wsrw-header-left">
					<?php $this->output_header_left(); ?>
				</div>
				<div class="wsrw-header-right">
					<?php $this->output_header_right(); ?>
				</div>
			</div>
			<div id="wsrw-header-between">
			</div>
			<div class="wsrw-header-bottom">
				<?php $this->output_header_bottom(); ?>
			</div>
		</div>
		<?php $this->maybe_output_message(); ?>
		<?php
	}

	/**
	 * Output of the footer markup for admin pages.
	 *
	 * @return void
	 */
	public function wsrw_footer() {

		$links       = array(
			array(
				'url'    => class_exists( 'WSRW_Main_Premium' ) ? wsrw_utm_url( 'https://library.wpcode.com/account/support/', 'plugin-footer', 'contact-support' ) : 'https://wordpress.org/support/plugin/search-replace-wpcode/',
				'text'   => __( 'Support', 'search-replace-wpcode' ),
				'target' => '_blank',
			),
			array(
				'url'    => wsrw_utm_url( 'https://wpcode.com/docs/', 'plugin-footer', 'documentation' ),
				'text'   => __( 'Docs', 'search-replace-wpcode' ),
				'target' => '_blank',
			),
		);
		$heart       = '♥';
		$team        = 'WPCode';
		$links_count = count( $links );
		?>

		<div class="wsrw-footer">

			<p>
			<?php
			printf(
				// Translators: %1$s - love symbol (e.g., heart), %2$s - team name.
				esc_html__(
					'Made with %1$s by the %2$s team',
					'search-replace-wpcode'
				),
				esc_html( $heart ),
				esc_html( $team )
			);
			?>
				</p>

			<ul class="wsrw-footer-links">
				<?php foreach ( $links as $key => $item ) : ?>
					<li>
						<a href="<?php echo esc_attr( $item['url'] ); ?>" target="<?php echo esc_attr( $item['target'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
						<?php if ( $links_count !== $key + 1 ) : ?>
							<span>/</span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<ul class="wsrw-footer-social">
				<li>
					<a href="https://www.facebook.com/groups/wpbeginner" target="_blank" rel="noopener noreferrer">
						<?php wsrw_icon( 'facebook', 17, 16 ); ?>
						<span class="screen-reader-text">Facebook</span>
					</a>
				</li>
				<li>
					<a href="https://twitter.com/wpcodelibrary" target="_blank" rel="noopener noreferrer">
						<?php wsrw_icon( 'twitter', 17, 16 ); ?>
						<span class="screen-reader-text">Twitter</span>
					</a>
				</li>
			</ul>
		</div>

		<?php
	}

	/**
	 * Output footer markup, mostly used for overlays that are fixed.
	 *
	 * @return void
	 */
	public function output_footer() {
		?>
		<div class="wsrw-modal-overlay"></div>
		<span class="wsrw-loading-spinner" id="wsrw-admin-spinner"></span>
		<?php
	}

	/**
	 * Left side of the header, usually just the logo in this area.
	 *
	 * @return void
	 */
	public function output_header_left() {
		$this->logo_image();
	}

	/**
	 * Logo image.
	 *
	 * @param string $id The id of the image.
	 *
	 * @return void
	 */
	public function logo_image( $id = 'wsrw-header-logo' ) {
		$logo = wsrw_get_icon( 'logo', 48, 48, '0 0 256 256' );

		echo '<div class="wsrw-logo-with-text" id="' . esc_attr( $id ) . '">';

		echo wp_kses( $logo, wsrw_get_icon_allowed_tags() );

		echo '<div class="wsrw-logo-text">';
		echo '<span class="wsrw-header-title">Search & Replace Everything</span>';
		echo '<span class="wsrw-header-subtitle">by ' . wp_kses( wsrw_get_icon( 'logo-text', 58, 12, '0 0 112.52 23' ), wsrw_get_icon_allowed_tags() ) . '</span>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Top right area of the header, by default the notifications and help icons.
	 *
	 * @return void
	 */
	public function output_header_right() {
		$campaign = ! empty( $this->view ) ? $this->view : $this->page_slug;
		$url      = class_exists( 'WSRW_Main_Premium' ) ? wsrw_utm_url( 'https://library.wpcode.com/account/support/', 'admin-header-help', $campaign ) : 'https://wordpress.org/support/plugin/search-replace-wpcode/';
		?>
		<a class="wsrw-text-button-icon wsrw-show-help" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noreferrer noopener">
			<?php wsrw_icon( 'help', 21 ); ?>
			<?php esc_html_e( 'Help', 'search-replace-wpcode' ); ?>
		</a>
		<?php
	}

	/**
	 * This is the menu area but on some pages it's just at title.
	 * Tabs could also be used here.
	 *
	 * @return void
	 */
	public function output_header_bottom() {
	}

	/**
	 * Checks if an error or success message is available and outputs using the specific format.
	 *
	 * @return void
	 */
	public function maybe_output_message() {
		$error_message   = $this->get_error_message();
		$success_message = $this->get_success_message();
		?>
		<div class="wrap" id="wsrw-notice-area">
			<?php
			if ( $error_message ) {
				?>
				<div class="error fade notice is-dismissible">
					<p><?php echo wp_kses_post( $error_message ); ?></p>
				</div>
				<?php
			}
			if ( $success_message ) {
				?>
				<div class="updated fade notice is-dismissible">
					<p><?php echo wp_kses_post( $success_message ); ?></p>
				</div>
				<?php
			}
			do_action( 'wsrw_admin_notices' );
			?>
		</div>
		<?php
	}

	/**
	 * If no message is set return false otherwise return the message string.
	 *
	 * @return false|string
	 */
	public function get_error_message() {
		return ! empty( $this->message_error ) ? $this->message_error : false;
	}

	/**
	 * If no message is set return false otherwise return the message string.
	 *
	 * @return false|string
	 */
	public function get_success_message() {
		return ! empty( $this->message_success ) ? $this->message_success : false;
	}

	/**
	 * This is the main page content and you can't get away without it.
	 *
	 * @return void
	 */
	abstract public function output_content();

	/**
	 * If you need to page-specific scripts override this function.
	 * Hooked to 'admin_enqueue_scripts'.
	 *
	 * @return void
	 */
	public function page_scripts() {
	}

	/**
	 * Set a success message to display it in the appropriate place.
	 * Let's use a function so if we decide to display multiple messages in the
	 * same instance it's easy to change the variable to an array.
	 *
	 * @param string $message The message to store as success message.
	 *
	 * @return void
	 */
	public function set_success_message( $message ) {
		$this->message_success = $message;
	}

	/**
	 * Set an error message to display it in the appropriate place.
	 * Let's use a function so if we decide to display multiple messages in the
	 * same instance it's easy to change the variable to an array.
	 *
	 * @param string $message The message to store as error message.
	 *
	 * @return void
	 */
	public function set_error_message( $message ) {
		$this->message_error = $message;
	}

	/**
	 * Add a page-specific body class using the page slug variable..
	 *
	 * @param string $body_class The body class to append.
	 *
	 * @return string
	 */
	public function page_specific_body_class( $body_class ) {

		$body_class .= ' ' . $this->page_slug;

		if ( ! empty( $this->view ) ) {
			$body_class .= ' ' . $this->page_slug . '-' . $this->view;
		}

		$body_class .= ' wsrw-not-licensed';

		return $body_class;
	}

	/**
	 * Get the page url to be used in a form action.
	 *
	 * @return string
	 */
	public function get_page_action_url() {
		$args = array(
			'page' => $this->page_slug,
		);
		if ( ! empty( $this->view ) ) {
			$args['view'] = $this->view;
		}
		if ( ! empty( $this->snippet_id ) ) {
			$args['snippet_id'] = $this->snippet_id;
		}

		return add_query_arg( $args, admin_url( 'admin.php' ) );
	}

	/**
	 * Metabox-style layout for admin pages.
	 *
	 * @param string $title The metabox title.
	 * @param string $content The metabox content.
	 * @param string $help The helper text (optional) - if set, a help icon will show up next to the title.
	 *
	 * @return void
	 */
	public function metabox( $title, $content, $help = '' ) {
		// translators: %s is the title of the metabox.
		$button_title = sprintf( __( 'Collapse Metabox %s', 'search-replace-wpcode' ), $title )
		?>
		<div class="wsrw-metabox">
			<div class="wsrw-metabox-title">
				<div class="wsrw-metabox-title-text">
					<?php echo wp_kses_post( $title ); ?>
					<?php $this->help_icon( $help ); ?>
				</div>
				<div class="wsrw-metabox-title-toggle">
					<button class="wsrw-metabox-button-toggle" type="button" title="<?php echo esc_attr( $button_title ); ?>">
						<svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1.41 7.70508L6 3.12508L10.59 7.70508L12 6.29508L6 0.295079L-1.23266e-07 6.29508L1.41 7.70508Z" fill="#454545"/>
						</svg>
					</button>
				</div>
			</div>
			<div class="wsrw-metabox-content">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Output a help icon with the text passed to it.
	 *
	 * @param string $text The tooltip text.
	 * @param bool   $echo Whether to echo or return the output.
	 *
	 * @return void|string
	 */
	public function help_icon( $text = '', $echo = true ) {
		if ( empty( $text ) ) {
			return;
		}
		if ( ! $echo ) {
			ob_start();
		}
		?>
		<span class="wsrw-help-tooltip">
			<?php wsrw_icon( 'help', 16, 16, '0 0 20 20' ); ?>
			<span class="wsrw-help-tooltip-text"><?php echo wp_kses_post( $text ); ?></span>
		</span>
		<?php
		if ( ! $echo ) {
			return ob_get_clean();
		}
	}

	/**
	 * Get a WPCode metabox row.
	 *
	 * @param string $label The label of the field.
	 * @param string $input The field input (html).
	 * @param string $id The id for the row.
	 * @param string $show_if_id Conditional logic id, automatically hide if the value of the field with this id doesn't match show if value.
	 * @param string $show_if_value Value(s) to match against, can be comma-separated string for multiple values.
	 * @param string $description Description to show under the input.
	 * @param bool   $is_pro Whether this is a pro feature and the pro indicator should be shown next to the label.
	 *
	 * @return void
	 */
	public function metabox_row( $label, $input, $id = '', $show_if_id = '', $show_if_value = '', $description = '', $is_pro = false ) {
		$show_if_rules = '';
		if ( ! empty( $show_if_id ) ) {
			$show_if_rules = sprintf( 'data-show-if-id="%1$s" data-show-if-value="%2$s"', esc_attr( $show_if_id ), esc_attr( $show_if_value ) );
		}
		?>
		<div class="wsrw-metabox-form-row" <?php echo $show_if_rules; // phpcs:ignore ?>>
			<div class="wsrw-metabox-form-row-label">
				<label for="<?php echo esc_attr( $id ); ?>">
					<?php echo esc_html( $label ); ?>
					<?php
					if ( $is_pro ) {
						echo '<span class="wsrw-pro-pill">PRO</span>';
					}
					?>
				</label>
			</div>
			<div class="wsrw-metabox-form-row-input">
				<?php echo $input; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( ! empty( $description ) ) { ?>
					<p><?php echo wp_kses_post( $description ); ?></p>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get a checkbox wrapped with markup to be displayed as a toggle.
	 *
	 * @param bool       $checked Is it checked or not.
	 * @param string     $name The name for the input.
	 * @param string     $description Field description (optional).
	 * @param string|int $value Field value (optional).
	 * @param string     $label Field label (optional).
	 *
	 * @return string
	 */
	public function get_checkbox_toggle( $checked, $name, $description = '', $value = '', $label = '' ) {
		$markup = '<label class="wsrw-checkbox-toggle">';

		$markup .= '<input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';
		$markup .= '<span class="wsrw-checkbox-toggle-slider"></span>';
		$markup .= '</label>';
		if ( ! empty( $label ) ) {
			$markup .= '<label class="wsrw-checkbox-toggle-label" for="' . esc_attr( $name ) . '">' . esc_html( $label ) . '</label>';
		}

		if ( ! empty( $description ) ) {
			$markup .= '<p class="description">' . wp_kses_post( $description ) . '</p>';
		}

		return $markup;
	}

	/**
	 * Goes through snippets and adds the item count to the categories.
	 *
	 * @param array $categories The categories to add the item count to.
	 * @param array $snippets The snippets to count.
	 *
	 * @return array
	 */
	public function add_item_counts( $categories, $snippets ) {
		$category_counts = array();
		foreach ( $snippets as $snippet ) {
			if ( ! isset( $snippet['categories'] ) ) {
				continue;
			}
			if ( empty( $snippet['code'] ) && empty( $snippet['needs_auth'] ) ) {
				continue;
			}
			foreach ( $snippet['categories'] as $category ) {
				if ( ! isset( $category_counts[ $category ] ) ) {
					$category_counts[ $category ] = 0;
				}
				++$category_counts[ $category ];
			}
		}

		// Add counts to the categories array.
		foreach ( $categories as $category_id => $category ) {
			if ( ! isset( $category['slug'] ) ) {
				continue;
			}
			$categories[ $category_id ]['count'] = isset( $category_counts[ $category['slug'] ] ) ? $category_counts[ $category['slug'] ] : 0;
		}

		return $categories;
	}

	/**
	 * Get the full URL for a view of an admin page.
	 *
	 * @param string $view The view slug.
	 *
	 * @return string
	 */
	public function get_view_link( $view ) {
		return add_query_arg(
			array(
				'page' => $this->page_slug,
				'view' => $view,
			),
			admin_url( 'admin.php' )
		);
	}

	/**
	 * Get a text field markup.
	 *
	 * @param string $id The id of the text field.
	 * @param string $value The value of the text field.
	 * @param string $description The description of the text field.
	 * @param bool   $wide Whether the text field should be wide.
	 * @param string $type The type of the text field.
	 *
	 * @return string
	 */
	public function get_input_text( $id, $value = '', $description = '', $wide = false, $type = 'text' ) {
		$allowed_types = array(
			'text',
			'email',
			'url',
			'number',
			'password',
		);
		if ( in_array( $type, $allowed_types, true ) ) {
			$type = esc_attr( $type );
		} else {
			$type = 'text';
		}
		$class = 'wsrw-regular-text';
		if ( $wide ) {
			$class .= ' wsrw-wide-text';
		}
		if ( 'text' !== $type ) {
			$class .= ' wsrw-input-' . $type;
		}
		$markup = '<input type="' . esc_attr( $type ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '" class="' . esc_attr( $class ) . '" autocomplete="off">';
		if ( ! empty( $description ) ) {
			$markup .= '<p>' . wp_kses_post( $description ) . '</p>';
		}

		return $markup;
	}

	/**
	 * Get an email field.
	 *
	 * @param string $id The id of the text field.
	 * @param string $value The value of the text field.
	 * @param string $description The description of the text field.
	 * @param bool   $wide Whether the text field should be wide.
	 *
	 * @return string
	 */
	public function get_input_email( $id, $value = '', $description = '', $wide = false ) {
		return $this->get_input_text( $id, $value, $description, $wide, 'email' );
	}

	/**
	 * Get an upsell box markup.
	 *
	 * @param string $title The main upsell box title.
	 * @param string $text The text displayed under the title.
	 * @param string $button_1 The main CTA button.
	 * @param string $button_2 The text link below the main CTA.
	 * @param array  $features A list of features to display below the text.
	 *
	 * @return string
	 */
	public static function get_upsell_box( $title, $text = '', $button_1 = array(), $button_2 = array(), $features = array() ) {

		$container_class = array(
			'wsrw-upsell-box',
		);

		if ( ! empty( $features ) ) {
			$container_class[] = 'wsrw-upsell-box-with-features';
		}

		$html = sprintf(
			'<div class="%s">',
			esc_attr( implode( ' ', $container_class ) )
		);

		$html .= '<div class="wsrw-upsell-text-content">';

		$html .= sprintf(
			'<h2>%s</h2>',
			wp_kses_post( $title )
		);

		if ( ! empty( $text ) ) {
			$html .= sprintf(
				'<div class="wsrw-upsell-text">%s</div>',
				wp_kses_post( $text )
			);
		}

		if ( ! empty( $features ) ) {
			$html .= '<ul class="wsrw-upsell-features">';
			foreach ( $features as $feature ) {
				$html .= sprintf(
					'<li class="wsrw-upsell-feature">%s</li>',
					wp_kses_post( $feature )
				);
			}
			$html .= '</ul>';
		}
		$button_1 = wp_parse_args(
			$button_1,
			array(
				'tag'        => 'a',
				'text'       => '',
				'url'        => wsrw_utm_url( 'https://wpcode.com/srlite/' ),
				'class'      => 'wsrw-button wsrw-button-orange wsrw-button-large',
				'attributes' => array(
					'target' => '_blank',
				),
			)
		);
		$button_2 = wp_parse_args(
			$button_2,
			array(
				'tag'        => 'a',
				'text'       => '',
				'url'        => wsrw_utm_url( 'https://wpcode.com/srlite/' ),
				'class'      => 'wsrw-upsell-button-text',
				'attributes' => array(
					'target' => '_blank',
				),
			)
		);

		$html .= '</div>'; // .wsrw-upsell-text-content
		$html .= '<div class="wsrw-upsell-buttons">';

		if ( ! empty( $button_1['text'] ) ) {
			$html .= self::get_list_item_button( $button_1, false );
		}

		if ( ! empty( $button_2['text'] ) ) {
			$html .= '<br />';
			$html .= self::get_list_item_button( $button_2, false );
		}

		$html .= '</div>'; // .wsrw-upsell-buttons

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get a button for the list of items.
	 *
	 * @param array $args Arguments for the button.
	 * @param bool  $echo (optional) Whether to echo the button or return it.
	 *
	 * @return void|string
	 */
	public static function get_list_item_button( $args, $echo = true ) {
		$button_settings = wp_parse_args(
			$args,
			array(
				'tag'        => 'button',
				'url'        => '',
				'text'       => '',
				'class'      => 'wsrw-button',
				'attributes' => array(),
			)
		);

		if ( empty( $button_settings['text'] ) ) {
			return;
		}

		$button_settings['class'] = esc_attr( $button_settings['class'] );

		$parsed_attributes = "class='{$button_settings['class']}' ";
		if ( ! empty( $button_settings['url'] ) && 'a' === $button_settings['tag'] ) {
			$parsed_attributes .= 'href="' . esc_url( $button_settings['url'] ) . '" ';
		}
		if ( ! empty( $button_settings['attributes'] ) ) {
			foreach ( $button_settings['attributes'] as $key => $value ) {
				$parsed_attributes .= esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}

		if ( $echo ) {
			printf(
				'<%1$s %2$s>%3$s</%1$s>',
				$button_settings['tag'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$parsed_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				wp_kses( $button_settings['text'], wsrw_get_icon_allowed_tags() )
			);
		} else {
			return sprintf(
				'<%1$s %2$s>%3$s</%1$s>',
				$button_settings['tag'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$parsed_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				wp_kses( $button_settings['text'], wsrw_get_icon_allowed_tags() )
			);
		}
	}
}
