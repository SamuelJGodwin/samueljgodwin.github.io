jQuery( function ( $ ) {
	'use strict';

	var $body = $( 'body' );

	/* -----------------------------------------
	Shop filters toggle && Mini Cart visibility
	----------------------------------------- */
	var $miniCartTrigger = $( '.header-mini-cart-trigger' );
	var $miniCart        = $( '.header-mini-cart-contents' );

	function isMiniCartVisible() {
		return $miniCart.is( ':visible' );
	}

	function dismissMiniCart() {
		$miniCart.removeClass( 'visible' );
		$miniCart.fadeOut( 'fast' );
	}

	function displayMiniCart() {
		$miniCart.addClass( 'visible' );
		$miniCart.fadeIn( 'fast' );
	}

	$miniCartTrigger.on( 'click', function ( event ) {
		event.preventDefault();

		if ( isMiniCartVisible() ) {
			dismissMiniCart();
		} else {
			displayMiniCart();
		}
	} );

	/* Event propagations */
	$( document ).on( 'keydown', function ( event ) {
		if ( event.keyCode === 27 ) {
			dismissMiniCart();
		}
	} );

	$body
		.on( 'click', function ( event ) {
			if ( $(event.target).parents('.head-mini-cart-wrap').length === 0 && isMiniCartVisible() ) {
				dismissMiniCart();
			}
		} );
} );
