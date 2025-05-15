<?php
/**
 * Class used to load admin pages allowing child classes
 *  to replace or add pages by changing the classes used.
 *
 * @package Search_Replace_WPCode
 */

/**
 * Class WFR admin page loader.
 */
class WSRW_Admin_Page_Loader {

	/**
	 * Array of admin pages to load.
	 *
	 * @var array
	 */
	public $pages = array();

	/**
	 * Slugs of pages that should not be visible in the submenu.
	 *
	 * @var array
	 */
	public $hidden_pages = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->require_files();

		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 9 );
		add_filter( 'plugin_action_links_' . WSRW_PLUGIN_BASENAME, array( $this, 'add_plugin_action_links' ) );
	}

	/**
	 * Load required files for the admin pages.
	 *
	 * @return void
	 */
	public function require_files() {
		require_once WSRW_PLUGIN_PATH . 'includes/admin/pages/class-wsrw-admin-page.php';
		require_once WSRW_PLUGIN_PATH . 'includes/admin/pages/class-wsrw-admin-page-search-replace.php';
	}

	/**
	 * Load the pages classes allowing child classes to replace.
	 *
	 * @return void
	 */
	public function prepare_pages() {
		$this->pages['search_replace'] = 'WSRW_Admin_Page_Search_Replace';
	}

	/**
	 * Load the pages using their specific classes.
	 *
	 * @return void
	 */
	public function load_pages() {

		$this->prepare_pages();

		do_action( 'wsrw_before_admin_pages_loaded', $this->pages );

		foreach ( $this->pages as $page_class ) {
			if ( ! class_exists( $page_class ) ) {
				continue;
			}
			/**
			 * The page class.
			 *
			 * @var WSRW_Admin_Page $new_page
			 */
			$new_page = new $page_class();
			if ( $new_page->hide_menu ) {
				$this->hidden_pages[] = $new_page->page_slug;
			}
		}
	}

	/**
	 * Handler for registering the admin menu & loading pages.
	 *
	 * @return void
	 */
	public function register_admin_menu() {
		$this->load_pages();
	}

	/**
	 * Generic handler for the wpcode pages.
	 *
	 * @return void
	 */
	public function admin_menu_page() {
		do_action( 'wsrw_admin_page' );
	}

	/**
	 * Add a link to the code snippets list in the plugins list view.
	 *
	 * @param array $links The links specific to our plugin.
	 *
	 * @return array
	 */
	public function add_plugin_action_links( $links ) {
		$url  = add_query_arg(
			array(
				'page' => 'wsrw-search-replace',
			),
			admin_url( 'tools.php' )
		);
		$text = esc_html__( 'Search & Replace', 'search-replace-wpcode' );

		$custom = array();

		$custom['settings'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			$url,
			$text
		);

		return array_merge( $custom, $links );
	}
}
