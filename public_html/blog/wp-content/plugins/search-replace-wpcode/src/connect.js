/* global wpcode ajaxurl */

import jconfirm from 'jquery-confirm';

/**
 * Connect functionality.
 *
 * @since 2.0.9
 */

'use strict';

var WSRWConnect = window.WSRWConnect || (
	function ( document, window, $ ) {

		// jquery-confirm defaults.
		jconfirm.defaults = {
			closeIcon: true,
			closeIconClass: 'close-icon-svg',
			backgroundDismiss: false,
			escapeKey: true,
			animationBounce: 1,
			useBootstrap: false,
			theme: 'modern',
			boxWidth: '400px',
			type: 'blue',
			animateFromElement: false,
		};
		/**
		 * Elements reference.
		 *
		 * @since 2.0.9
		 *
		 * @type {object}
		 */
		var el = {
			$connectBtn: $( '#wsrw-settings-connect-btn' ), $connectKey: $( '#wsrw-settings-upgrade-license-key' ),
		};

		/**
		 * Public functions and properties.
		 *
		 * @since 2.0.9
		 *
		 * @type {object}
		 */
		var app = {

			/**
			 * Start the engine.
			 *
			 * @since 2.0.9
			 */
			init: function () {
				console.log( 'WSRWConnect: init' );

				$( app.ready );
			},

			/**
			 * Document ready.
			 *
			 * @since 2.0.9
			 */
			ready: function () {

				app.events();
			},

			/**
			 * Register JS events.
			 *
			 * @since 2.0.9
			 */
			events: function () {

				app.connectBtnClick();
			},

			/**
			 * Register connect button event.
			 *
			 * @since 2.0.9
			 */
			connectBtnClick: function () {

				el.$connectBtn.on(
					'click',
					function () {
						WSRSpinner.show_button_spinner( $(this) );
						app.gotoUpgradeUrl();
					}
				);
			},

			/**
			 * Get the alert arguments in case of Pro already installed.
			 *
			 * @since 2.0.9
			 *
			 * @param {object} res Ajax query result object.
			 *
			 * @returns {object} Alert arguments.
			 */
			proAlreadyInstalled: function ( res ) {

				$.confirm( {
					title: wsrwjs.almost_done,
					content: res.data.message,
					type: 'blue',
					icon: 'fa fa-exclamation-circle',
					animateFromElement: false,
					buttons: {
						confirm: {
							text: wsrwjs.plugin_activate_btn,
							btnClass: 'btn-confirm',
							keys: ['enter'],
						},
					},
					onAction: function ( action ) {
						if ( action === 'confirm' ) {
							window.location.reload();
						}
					},
				} );


			},

			/**
			 * Go to upgrade url.
			 *
			 * @since 2.0.9
			 */
			gotoUpgradeUrl: function () {

				var data = {
					action: 'wsrw_connect_url', key: el.$connectKey.val(), _wpnonce: wsrwjs.nonce,
				};

				$.post( ajaxurl, data ).done(
					function ( res ) {
						if ( res.success ) {
							if ( res.data.reload ) {
								app.proAlreadyInstalled( res );
								return;
							}
							window.location.href = res.data.url;
							return;
						}

						$.confirm( {
							title: wsrwjs.oops,
							content: res.data.message,
							type: 'blue',
							icon: 'fa fa-exclamation-circle',
							animateFromElement: false,
							buttons: {
								confirm: {
									text: wsrwjs.ok,
									btnClass: 'btn-confirm',
									keys: ['enter'],
								},
							},
						} );

					}
				).fail(
					function ( xhr ) {
						app.failAlert( xhr );
					}
				).always(
					function () {
						WSRSpinner.hide_button_spinner( el.$connectBtn );
					}
				);
			},

			/**
			 * Alert in case of server error.
			 *
			 * @since 2.0.9
			 *
			 * @param {object} xhr XHR object.
			 */
			failAlert: function ( xhr ) {

				$.confirm( {
					title: wsrwjs.oops,
					content: wsrwjs.server_error + '<br>' + xhr.status + ' ' + xhr.statusText + ' ' + xhr.responseText,
					type: 'blue',
					icon: 'fa fa-exclamation-circle',
					animateFromElement: false,
					buttons: {
						confirm: {
							text: wsrwjs.ok,
							btnClass: 'btn-confirm',
							keys: ['enter'],
						},
					},
				} );

			},
		};

		// Provide access to public functions/properties.
		return app;

	}( document, window, jQuery )
);

// Initialize.
WSRWConnect.init();
