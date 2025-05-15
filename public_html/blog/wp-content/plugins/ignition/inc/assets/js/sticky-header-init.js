jQuery(function( $ ) {
	'use strict';

	var $window = $(window);

	/* -----------------------------------------
	 Sticky Header - Normal Mode
	 ----------------------------------------- */
	(function () {
		var $headerSticky = $('.header-sticky');
		var $headMast = $headerSticky.find('.head-mast');

		if ((!$.fn.shyheader && !$.fn.sticky) || !$headMast.length) {
			return;
		}

		var onStick = function () {
			var $logoImg = $('.site-branding img');
			var logoNormalUrl = $logoImg.data('logo');
			var headerTransparent = $('.header-fixed').length > 0;

			if (!headerTransparent || !logoNormalUrl) {
				return;
			}

			$logoImg.attr('src', logoNormalUrl);
		};

		var onUnstick = function() {
			var $logoImg = $('.site-branding img');
			var logoAltUrl = $logoImg.data('logo-alt');
			var headerTransparent = $('.header-fixed').length > 0;

			if (!headerTransparent || !logoAltUrl) {
				return;
			}

			$logoImg.attr('src', logoAltUrl);
		};

		if ($headerSticky.hasClass('sticky-shy')) {
			$headMast.wrap($('<div />', { class: 'head-mast-sticky-container'}));

			$headMast.shyheader({
				classname: 'sticky-hidden',
				container: 'head-mast-sticky-container',
				onStick: onStick,
				onUnstick: onUnstick,
			});
		} else {
			$headMast.sticky({
				className: 'sticky-fixed',
				topSpacing: 0,
				responsiveWidth: true,
				dynamicHeight: false,
			});

			$headMast.on( 'sticky-start', onStick );
			$headMast.on( 'sticky-end', onUnstick );
		}
	})();

	/* -----------------------------------------
	 Sticky Header - Sidebar Mode
	 ----------------------------------------- */
	(function () {
		var $body = $(document.body);
		var mobileBreakpoint = $('.site-wrap').data('mobile-breakpoint');
		var stickyEnabled = $('.site-sidebar-sticky-on').length > 0;
		var $sticky = $('.site-sidebar-wrap-inner');

		if (!$.fn.stick_in_parent || !stickyEnabled || mobileBreakpoint > $window.outerWidth()) {
			return;
		}

		$sticky.stick_in_parent({
			parent: '.site-wrap',
			bottoming: true,
		});

		if (typeof window.ResizeObserver !== 'undefined') {
			var resizeObserver = new ResizeObserver(function () {
				$body.trigger('sticky_kit:recalc');
			});
			resizeObserver.observe(document.body);
		}
	})();
});
