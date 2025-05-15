/**
 * Customizer Controls enhancements for a better user experience.
 */

jQuery( function ( $ ) {
	'use strict';

	//
	// Featured Articles
	//
	$( '#customize-control-theme_featured_articles_tag input' ).suggest( ajaxurl + '?action=ignition-public-opinion-ajax-term-search&tax=post_tag', { delay: 500, minchars: 2 } );
} );
