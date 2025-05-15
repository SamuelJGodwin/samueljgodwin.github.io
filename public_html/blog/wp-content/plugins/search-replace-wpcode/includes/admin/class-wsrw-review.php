<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ask for some love.
 */
class WSRW_Review {

	/**
	 * Primary class constructor.
	 */
	public function __construct() {
		// Admin footer text.
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
	}

	/**
	 * When user is on a WSRW related admin page, display custom footer text.
	 *
	 * @param string $text Footer text.
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {

		global $current_screen;

		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'wsrw' ) !== false ) {
			$url  = 'https://wordpress.org/support/plugin/search-replace-wpcode/reviews/?filter=5';
			$text = sprintf(
				wp_kses( /* translators: $1$s - WSRW plugin name, $2$s - WP.org review link, $3$s - WP.org review link. */
					__( 'Please rate %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the WPCode team!', 'search-replace-wpcode' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'<strong>Search & Replace Everything</strong>',
				$url,
				$url
			);
		}

		return $text;
	}
}

new WSRW_Review();
