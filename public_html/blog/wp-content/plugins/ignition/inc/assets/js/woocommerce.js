jQuery( function ( $ ) {
	'use strict';

	var $body = $('body');

	/* -----------------------------------------
	 WooCommerce plus minus
	 ----------------------------------------- */
	$body.on( 'click', '.qty-btn', function () {
		var $this        = $( this );
		var $input       = $this.parent().find( 'input[type="number"]' );
		var placeholder  = parseInt( $input.attr('placeholder') ) || 0;
		var min          = parseInt( $input.attr( 'min' ) || 0, 10 );
		var max          = parseInt( $input.attr( 'max' ) || 99999, 10 );
		var currentVal   = parseInt($input.val(), 10) ? parseInt($input.val(), 10) : placeholder;
		var isMinus      = $this.hasClass( 'qty-minus' );

		if ( isMinus ) {
			$input.val( Math.max( currentVal - 1, min ) );
		} else {
			$input.val( Math.min( currentVal + 1, max ) );
		}

		$input.trigger( 'change' );
	} );


	/* -----------------------------------------
	Shop filters toggle
	----------------------------------------- */
	var $filtersWrap    = $( '.sidebar-drawer' );
	var $filtersToggle  = $( '.shop-filter-toggle' );
	var $filtersDismiss = $( '.sidebar-dismiss' );

	function isFiltersVisible() {
		return $body.hasClass('sidebar-drawer-visible');
	}

	function dismissFilters(event) {
		if (event) {
			event.preventDefault();
		}
		$body.removeClass('sidebar-drawer-visible');
	}

	function displayFilters(event) {
		if (event) {
			event.preventDefault();
		}
		$body.addClass('sidebar-drawer-visible')
	}

	$filtersToggle.on('click', displayFilters);
	$filtersDismiss.on('click', dismissFilters);

	/* Event propagations */
	$(document).on('keydown', function (event) {
		if (event.keyCode === 27) {
			dismissFilters(event);
		}
	});

	$body
		.on('click', function (event) {
			if (!$filtersWrap.length) {
				return;
			}

			// Instead of event.stopPropagation inside the sidebar
			// which can break functionality from 3rd party plugins
			// we check if the element is part of the sidebar
			// and prevent the sidebar from closing if true.
			if ($(event.target).parents('.sidebar-drawer').length > 0) {
				return;
			}

			if (isFiltersVisible()) {
				dismissFilters();
			}
		})
		.find('.shop-filter-toggle, ' +
			'.category-search-input ',
			'.category-search-select')
		.on('click', function (event) {
			event.stopPropagation();
		});
} );
