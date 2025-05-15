jQuery( document ).ready( function ( $ ) {
	var $body = $('body');

	var deactivateAllButtons = function () {
		var $buttons = $('.ignition-public-opinion-onboarding-main-content').find('.button');

		$buttons.addClass('disabled');
	};

	var activateAllButtons = function () {
		var $buttons = $('.ignition-public-opinion-onboarding-main-content').find('.button');

		$buttons.removeClass('disabled');
	};

	$body.on( 'click', '.ignition-public-opinion-onboarding-main-content .activate-variation', function () {
		var button = $( this );
		var $box   = $( this ).closest( '.ignition-public-opinion-variation' );
		var slug   = $( this ).data( 'variation-slug' );

		$.ajax( {
			type: 'post',
			url: ignition_public_opinion_onboarding.ajaxurl,
			data: {
				action: 'ignition_public_opinion_activate_variation',
				onboarding_nonce: ignition_public_opinion_onboarding.onboarding_nonce,
				variation: slug,
			},
			dataType: 'text',
			beforeSend: function() {
				button.addClass( 'updating-message' );
				button.text( ignition_public_opinion_onboarding.activating_text );
				deactivateAllButtons();
			},
			success: function( response ) {
				button.removeClass( 'updating-message' );
				button.text( ignition_public_opinion_onboarding.activate_variation_text );
				activateAllButtons();
				$( '.ignition-public-opinion-variation' ).removeClass( 'enabled' );
				$box.addClass( 'enabled' );
			}
		} );

		return false;
	} );

	$body.on( 'click', '.ignition-public-opinion-onboarding-main-content .reset-theme-mods', function () {
		var button = $( this );
		var text   = button.text();

		if ( ! window.confirm( ignition_public_opinion_onboarding.reset_mods_confirm_text ) ) {
			return false;
		}

		$.ajax( {
			type: 'post',
			url: ignition_public_opinion_onboarding.ajaxurl,
			data: {
				action: 'ignition_public_opinion_reset_theme_mods',
				onboarding_nonce: ignition_public_opinion_onboarding.onboarding_nonce,
			},
			dataType: 'text',
			beforeSend: function() {
				button.addClass( 'updating-message' );
				button.text( ignition_public_opinion_onboarding.deleting_text );
				deactivateAllButtons();
			},
			success: function( response ) {
				button.removeClass( 'updating-message' );
				button.text( text );
				activateAllButtons();
			}
		} );

		return false;
	} );

	$body.on( 'click', '.ignition-public-opinion-onboarding-main-content .backup-theme-mods', function () {
		var button = $( this );

		$.ajax( {
			type: 'post',
			url: ignition_public_opinion_onboarding.ajaxurl,
			data: {
				action: 'ignition_public_opinion_backup_theme_mods',
				onboarding_nonce: ignition_public_opinion_onboarding.onboarding_nonce,
			},
			dataType: 'json',
			beforeSend: function() {
				button.addClass( 'updating-message' );
				deactivateAllButtons();
			},
			success: function( response ) {
				button.removeClass( 'updating-message' );
				activateAllButtons();

				if ( response.success && typeof response.data.date !== 'undefined' ) {
					$( '.latest-backup-info .date' ).text( response.data.date );
				} else if ( typeof response.data.result !== 'undefined' ) {
					alert( response.data.result );
				}
			}
		} );

		return false;
	} );

	$body.on( 'click', '.ignition-public-opinion-onboarding-main-content .restore-theme-mods', function () {
		var button = $( this );

		$.ajax( {
			type: 'post',
			url: ignition_public_opinion_onboarding.ajaxurl,
			data: {
				action: 'ignition_public_opinion_restore_theme_mods',
				onboarding_nonce: ignition_public_opinion_onboarding.onboarding_nonce,
			},
			dataType: 'json',
			beforeSend: function() {
				button.addClass( 'updating-message' );
				deactivateAllButtons();
			},
			success: function( response ) {
				button.removeClass( 'updating-message' );
				activateAllButtons();
				if ( response.success && typeof response.data.variation !== 'undefined' ) {
					$( '.ignition-public-opinion-variation[data-variation="' + response.data.variation + '"] a.button' ).click();
				} else if ( typeof response.data.result !== 'undefined' ) {
					alert( response.data.result );
				}
			}
		} );

		return false;
	} );

	$body.on( 'click', '.ignition-public-opinion-onboarding-main-content .install-now', function ( e ) {
		e.preventDefault();
		var button = $(this);
		var plugin_slug = button.data('plugin-slug');

		button.addClass('updating-message');
		button.text(ignition_public_opinion_onboarding.installing_text);
		deactivateAllButtons();

		wp.updates.installPlugin( {
			slug: plugin_slug
		}).done(function () {
			// Reload the page.
			location.reload();
		}).fail(function () {
			// Reload the page.
			location.reload();
		});
	} );

	var activatePlugin = function ( $activateButton ) {
		if ($activateButton.length) {

			var url = $activateButton.attr( 'href' );
			if (typeof url !== 'undefined') {
				// Request plugin activation.
				$.ajax(
					{
						beforeSend: function () {
							$activateButton.replaceWith( '<a class="button updating-message">' + ignition_public_opinion_onboarding.activating_text + '</a>' );
							deactivateAllButtons();
						},
						async: true,
						type: 'GET',
						url: url,
						success: function () {
							// Reload the page.
							location.reload();
						}
					}
				);
			}
		}
	};


	$( document ).on( 'DOMNodeInserted','.activate-now', function () {
		activatePlugin( $(this) );
	} );

	$( document ).on( 'click','.activate-now', function ( e ) {
		e.preventDefault();
		activatePlugin( $(this) );
	} );

	$( '.ajax-install-plugin' ).on( 'click', function( e ) {
		e.preventDefault();
		var button = $(this);
		var plugin_slug = button.data('plugin-slug');

		$.ajax( {
			type: 'post',
			url: ignition_public_opinion_onboarding.ajaxurl,
			data: {
				action: 'ignition_public_opinion_install_plugin',
				onboarding_nonce: ignition_public_opinion_onboarding.onboarding_nonce,
				plugin_slug: plugin_slug,
			},
			dataType: 'text',
			beforeSend: function() {
				button.addClass('updating-message');
				button.text(ignition_public_opinion_onboarding.installing_text);
				deactivateAllButtons();
			},
			success: function( response ) {
				// Reload the page.
				location.reload();
			}
		} );
	} );

} );
