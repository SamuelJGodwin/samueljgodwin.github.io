jQuery( function ( $ ) {
	'use strict';

	$( '.open-ignition-wc-login-popup' ).magnificPopup( {
		type: 'inline',
		mainClass: 'ignition-wc-login-popup',
		midClick: true
	} );

	$( '.ignition-wc-login-wrapper .woocommerce-form-login' ).on( 'submit', function ( e ) {

		var $form      = $( this );
		var $notices   = $form.siblings( '.ignition-wc-login-notices' );
		var username   = $form.find( '#username' );
		var password   = $form.find( '#password' );
		var rememberme = $form.find( '#rememberme' );
		var error;

		$notices.find( 'div' ).remove();

		if ( username.val() === '' ) {
			ignitionWCLoginPopupShowError( username );
			error = true;
		} else {
			ignitionWCLoginPopupHideError( username );
		}

		if ( password.val() === '' ) {
			ignitionWCLoginPopupShowError( password );
			error = true;
		} else {
			ignitionWCLoginPopupHideError( password );
		}

		if ( error === true ) {
			$notices.append( '<div class="msg failure">' + ignition_wc_popup.fields_required + '</div>' );
			return false;
		}

		$notices.append( '<div class="loading">' + ignition_wc_popup.loading_message + '</div>' );
		$notices.find( '.msg' ).remove();
		$.ajax( {
			type: 'POST',
			dataType: 'json',
			url: ignition_wc_popup.ajax_url,
			data: {
				action: 'ignition_wc_popup_ajax_login',
				username: username.val(),
				password: password.val(),
				rememberme: rememberme.is( ':checked' ),
				nonce: $form.find( '#woocommerce-login-nonce' ).val(),
			},
			success: function ( response ) {
				$notices.find( '.loading' ).remove();
				if ( response.success === true ) {
					$notices.append( '<div class="loading">' + response.data.message + '</div>' );
					if ( response.data.redirect !== false ) {
						window.location = response.data.redirect;
					} else {
						window.location.reload();
					}
				} else {
					if ( response.data.invalid_username === true ) {
						ignitionWCLoginPopupShowError( username );
					} else {
						ignitionWCLoginPopupHideError( username );
					}
					if ( response.data.incorrect_password === true ) {
						ignitionWCLoginPopupShowError( password );
					} else {
						ignitionWCLoginPopupHideError( password );
					}
					$notices.append( '<div class="msg failure">' + response.data.message + '</div>' );
				}
			},
			error: function () {
				$notices.append( '<div class="msg failure">' + ignition_wc_popup.ajax_error + '</div>' );
			},
		} );
		e.preventDefault();
	} );

	function ignitionWCLoginPopupShowError( element ) {
		element.addClass( 'error' );
	}

	function ignitionWCLoginPopupHideError( element ) {
		element.removeClass( 'error' );
	}

} );
