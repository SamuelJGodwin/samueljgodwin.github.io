/* global wsrw_admin_notices */

/**
 * WSRW Dismissible Notices.
 *
 * @since 1.6.7.1
 */

'use strict';

var WSRWAdminWideNotices = window.WSRWAdminWideNotices || ( function ( document, window, $ ) {

	/**
	 * Public functions and properties.
	 *
	 * @since 1.6.7.1
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * Start the engine.
		 *
		 * @since 1.6.7.1
		 */
		init: function () {

			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 1.6.7.1
		 */
		ready: function () {

			app.events();
		},

		/**
		 * Dismissible notices events.
		 *
		 * @since 1.6.7.1
		 */
		events: function () {

			$( document ).on(
				'click',
				'.wsrw-notice .notice-dismiss, .wsrw-notice .wsrw-notice-dismiss',
				app.dismissNotice
			);
		},

		/**
		 * Dismiss notice event handler.
		 *
		 * @since 1.6.7.1
		 *
		 * @param {object} e Event object.
		 * */
		dismissNotice: function ( e ) {

			if ( e.target.classList.contains( 'wsrw-notice-dismiss' ) ) {
				$( this ).closest( '.wsrw-notice' ).slideUp();
			}

			$.post(
				wsrw_admin_notices.ajax_url,
				{
					action: 'wsrw_notice_dismiss',
					_wpnonce:   wsrw_admin_notices.nonce,
					id: 	 ( $( this ).closest( '.wsrw-notice' ).attr( 'id' ) || '' ).replace( 'wsrw-notice-', '' ),
				}
			);
		},
	};

	return app;

}( document, window, jQuery ) );

// Initialize.
WSRWAdminWideNotices.init();
