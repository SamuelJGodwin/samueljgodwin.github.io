<?php
/**
 * Lite-specific admin notices.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wsrw_admin_page', 'wsrw_maybe_add_lite_top_bar_notice', 4 );
add_action( 'wsrw_admin_page_content_wsrw-search-replace', 'wsrw_upsell_notice', 250 );

/**
 * Add a notice to consider more features with offer.
 *
 * @return void
 */
function wsrw_maybe_add_lite_top_bar_notice() {

	// Only add this to the WSRW pages.
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

	if ( ! $page || 0 !== strpos( $page, 'wsrw' ) ) {
		return;
	}

	$screen = get_current_screen();

	$upgrade_url = wsrw_utm_url(
		'https://wpcode.com/srlite/',
		'top-notice',
		$screen
	);

	WSRW_Notice::top(
		sprintf(
		// Translators: %1$s and %2$s add a link to the upgrade page. %3$s and %4$s make the text bold.
			__( '%3$sYou\'re using Search & Replace Everything Lite%4$s. To unlock more features consider %1$supgrading to Pro%2$s.', 'search-replace-wpcode' ),
			'<a href="' . $upgrade_url . '" target="_blank" rel="noopener noreferrer">',
			'</a>',
			'<strong>',
			'</strong>'
		),
		array(
			'dismiss' => WSRW_Notice::DISMISS_USER,
			'slug'    => 'consider-upgrading',
		)
	);
}

/**
 * Show a notice with more features at the bottom of the main page.
 *
 * @return void
 */
function wsrw_upsell_notice( $page_instance ) {

	$view = $page_instance->view;
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'search_replace' !== $view && ! isset( $_GET['media_id'] ) ) {
		return;
	}

	$html  = '<h3>' . esc_html__( 'Get Search & Replace Everything Pro and Unlock all the Powerful Features', 'search-replace-wpcode' ) . '</h3>';
	$html .= '<div class="wsrw-features-list">';
	$html .= '<ul>';
	$html .= '<li>' . esc_html__( 'Replace any text with confidence knowing you can 1-click undo', 'search-replace-wpcode' ) . '</li>';
	$html .= '<li>' . esc_html__( 'Save time by replacing images directly from the Gutenberg Editor', 'search-replace-wpcode' ) . '</li>';
	$html .= '</ul>';
	$html .= '<ul>';
	$html .= '<li>' . esc_html__( 'Replace with precision by being able to choose which results to skip', 'search-replace-wpcode' ) . '</li>';
	$html .= '<li>' . esc_html__( 'Track back results by viewing the full context of search results', 'search-replace-wpcode' ) . '</li>';
	$html .= '</ul>';
	$html .= '</div>';
	$html .= sprintf(
		'<p><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></p>',
		wsrw_utm_url( 'https://wpcode.com/srlite/', 'upsell-notice', $view ),
		esc_html__( 'Get Search & Replace Everything Pro Today and Unlock all the Powerful Features »', 'search-replace-wpcode' )
	);
	$html .= '<p>';
	$html .= sprintf(
	// Translators: Placeholders make the text bold.
		esc_html__( '%1$sBonus:%2$s Search & Replace Everything Lite users get %3$s$30 off regular price%4$s, automatically applied at checkout', 'search-replace-wpcode' ),
		'<strong>',
		'</strong>',
		'<strong style="color:#59A56D;">',
		'</strong>'
	);
	$html .= '</p>';

	// Add our custom notice for this page.
	WSRW_Notice::info(
		$html,
		array(
			'slug'    => 'wsrw-snippets',
			'dismiss' => WSRW_Notice::DISMISS_USER,
		)
	);
	// Display notice we just added so that scripts are loaded.
	wsrw_main()->notice->display();
}
