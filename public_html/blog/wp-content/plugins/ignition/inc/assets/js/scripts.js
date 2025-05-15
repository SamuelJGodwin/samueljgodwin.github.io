jQuery(function ($) {
	'use strict';

	var $window = $(window);
	var $body = $('body');
	var isRTL = $body.hasClass('rtl');

	/* -----------------------------------------
	 Responsive Menu Init
	 ----------------------------------------- */
	var $navWrap = $('.nav');
	var $navSubmenus = $navWrap.find('ul');
	var $mainNav = $('.navigation-main');
	var $mobileNav = $('.navigation-mobile-wrap');
	var $mobileNavTrigger = $('.mobile-nav-trigger');
	var $mobileNavDismiss = $('.navigation-mobile-dismiss');

	$mainNav.each(function () {
		var $this = $(this);
		$this.clone()
			.find('> li')
			.removeAttr('id')
			.appendTo($mobileNav.find('.navigation-mobile'));
	});

	$mobileNav.find('li')
		.each(function () {
			var $this = $(this);
			$this.removeAttr('id');

			if ($this.find('.sub-menu').length > 0) {
				var $button = $('<button class="menu-item-sub-menu-toggle"><span class="sr-only">' + ignition_front_vars.expand_submenu + '</span></button>');

				$this.find('> a').after($button);
			}
		});

	$mobileNav.find('.menu-item-sub-menu-toggle').on('click', function (event) {
		event.preventDefault();
		var $this = $(this);
		$this.parent().toggleClass('menu-item-expanded');
	});

	$mobileNav.find('li > a').on('click', function (event) {
		var $this = $(this);
		var href = $this.attr('href');

		if (href === '#' || !href) {
			event.preventDefault();
			$this.parent().toggleClass('menu-item-expanded');
		}
	});

	$mobileNavTrigger.on('click', function (event) {
		event.preventDefault();
		mobileNavTrigger();
	});

	$mobileNavDismiss.on('click', function (event) {
		event.preventDefault();
		mobileNavDismiss();
	});

	function mobileNavDismiss() {
		$body.removeClass('mobile-nav-open');
		$mobileNavTrigger.focus();
	}

	function mobileNavTrigger() {
		$body.addClass('mobile-nav-open');
		$mobileNavDismiss.focus();
	}

	/* -----------------------------------------
	Menu classes based on available free space
	----------------------------------------- */
	function setMenuClasses() {
		if (! $navWrap.is(':visible')) {
			return;
		}

		var windowWidth = $window.width();

		$navSubmenus.each(function () {
			var $this = $(this);
			var $parent = $this.parent();
			$parent.removeClass('nav-open-left');
			var leftOffset = $this.offset().left + $this.outerWidth();

			if (leftOffset > windowWidth) {
				$parent.addClass('nav-open-left');
			}
		});
	}

	setMenuClasses();

	var resizeTimer;

	$window.on('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function () {
			setMenuClasses();
		}, 350);
	});

	/* -----------------------------------------
	Main nav smooth scrolling
	----------------------------------------- */
	(function () {
		var $mainMenuNavs = $mainNav.find('a[href*="#"]');
		var $mobileMenuNavs = $mobileNav.find('a[href*="#"]');
		var $navs = $mainMenuNavs.add($mobileMenuNavs).filter(function () {
			var href = $(this).attr('href');
			return href !== '#' &&
				href !== 'http://#' &&
				href !== 'https://#' &&
				href !== '#mobilemenu' &&
				href !== '#nav-dismiss'
		});

		if (!$navs.length || !$mainNav.hasClass('nav-smooth-scroll')) {
			return;
		}

		var offset = 85;

		$navs.on('click', function (event) {
			// Check if the element exists on page before continuing
			var $target = $(this.hash);

			if ($target.length === 0) {
				return;
			}

			event.preventDefault();

			if ($target.length && ! $target.hasClass('elementor-menu-anchor')) {
				mobileNavDismiss();

				$('html, body').animate({
					scrollTop: $target.offset().top - offset,
				}, 500);
			}
		});

		// Mark nav items active on scroll
		var scrollTimer;

		$window.on('scroll', (function () {
			clearTimeout(scrollTimer);

			scrollTimer = setTimeout(function () {
				// Assign active class to nav links while scolling
				$navs.each(function () {
					var $this = $(this);
					var hash = $this.prop('hash');
					var section = $(hash).get(0);

					if (!section) {
						return;
					}

					var sectionTop = section.getBoundingClientRect().top;

					if (sectionTop > 0 && sectionTop < window.innerHeight / 2) {
						$this.parent().addClass('current-scroll-menu-item current-menu-item');
					} else {
						$this.trigger('blur');
						$this.parent().removeClass('current-scroll-menu-item current-menu-item current_page_item');
					}
				});
			}, 150);
		})).scroll();
	}());

	/* -----------------------------------------
	Focus trapping
	----------------------------------------- */
	$('[data-set-focus]').each(function () {
		var $this = $(this);
		var selector = $this.data('set-focus');
		var $element = $(selector);

		$this.on('blur', function () {
			if ($element.length) {
				$element.focus();
			}
		});
	});

	/* -----------------------------------------
	 Back to top button
	 ----------------------------------------- */
	var $btnTop = $('.btn-to-top');

	if ($btnTop.length > 0) {
		var scrollTimer;

		$btnTop.on('click', function (event) {
			event.preventDefault();

			$('html, body').animate({ scrollTop: 0 }, 'fast');
		});

		$window.on('scroll', function (e) {
			clearTimeout(scrollTimer);
			scrollTimer = setTimeout(function () {
				if ($window.scrollTop() > 400) {
					$btnTop.addClass('btn-to-top-visible');
				} else {
					$btnTop.removeClass('btn-to-top-visible');
				}
			}, 250);
		});
	}

	/* -----------------------------------------
	 Header Search Toggle
	 ----------------------------------------- */
	var $searchTrigger = $('.global-search-form-trigger');
	var $searchDismiss = $('.global-search-form-dismiss');
	var $headSearchForm = $('.global-search-form');

	function dismissHeadSearch(e) {
		if (e) {
			e.preventDefault();
		}

		$headSearchForm.removeClass('global-search-form-expanded');
		$body.focus();
	}

	function displayHeadSearch(e) {
		if (e) {
			e.preventDefault();
		}

		$headSearchForm
			.addClass('global-search-form-expanded')
			.find('input')
			.focus();
	}

	function isHeadSearchVisible() {
		return $headSearchForm.hasClass('global-search-form-expanded');
	}

	$searchTrigger.on('click', displayHeadSearch);
	$searchDismiss.on('click', dismissHeadSearch);

	/* Event propagations */
	$(document).on('keydown', function (e) {
		e = e || window.e;
		if (e.keyCode === 27 && isHeadSearchVisible()) {
			dismissHeadSearch(e);
		}
	});

	$body
		.on('click', function (e) {
			if (isHeadSearchVisible()) {
				dismissHeadSearch();
			}
		})
		.find('.global-search-form, .global-search-form-trigger')
		.on('click', function (e) {
			e.stopPropagation();
		});

	/* -----------------------------------------
	 Category Filters
	 ----------------------------------------- */
	var $filtersWrap = $( '.ci-item-filters' );

	$filtersWrap.each( function () {
		var $wrap = $( this );
		var $filters = $wrap.find( '.ci-item-filter' );
		var $row = $wrap.next( '.row-items' );
		var $allItems = $row.find( '[class*="col"]' );

		$filters.on( 'click', function ( event ) {
			event.preventDefault();
			var $this = $( this );

			$filters.removeClass( 'filter-active' );
			$this.addClass( 'filter-active' );

			var classes = $this.data( 'filter' );
			var $items = $row.find( classes );

			if ( classes === '*' ) {
				$allItems.fadeIn( 500 );
				return;
			}

			$allItems.not( $items ).fadeOut( 200 ).promise().done( function () {
				$items.fadeIn( 200 );
			} );
		} );
	} );

	/* -----------------------------------------
	Instagram Widget
	----------------------------------------- */
	var $instagramWrap = $('.footer-widget-area');
	var $instagramWidget = $instagramWrap.find('.zoom-instagram-widget__items');

	if ( $instagramWidget.length ) {
		var auto  = $instagramWrap.data('auto');
		var speed = $instagramWrap.data('speed');

		$instagramWidget.slick({
			slidesToShow: 8,
			slidesToScroll: 3,
			arrows: false,
			autoplay: auto == 1,
			speed: speed,
			rtl: isRTL,
			responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 4
					}
				}
			]
		});
	}

	$body.on( 'click', '.entry-share-copy-url', function( event ) {
		event.preventDefault();

		copyToClipboard( $( this ).attr( 'href' ) );

		// TODO vmasto: Add some visual indicator that the text was copied?
	} );

	function copyToClipboard( text ) {
		if ( navigator.clipboard && window.isSecureContext ) {
			// Navigator clipboard api needs https or localhost.
			navigator.clipboard.writeText( text );
		} else {
			// Hidden text area method.
			var textarea            = document.createElement( 'textarea' );
			textarea.value          = text;
			textarea.style.position = 'fixed';
			textarea.style.left     = '-999999px';
			textarea.style.top      = '-999999px';
			document.body.appendChild( text );
			textarea.focus();
			textarea.select();
			document.execCommand( 'copy' );
			textarea.remove();
		}
	}

});

/**
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
(function () {
	var isIe = /(trident|msie)/i.test(navigator.userAgent);

	if (isIe && document.getElementById && window.addEventListener) {
		window.addEventListener('hashchange', function () {
			var id = location.hash.substring(1),
				element;

			if (! (/^[A-z0-9_-]+$/.test(id))) {
				return;
			}

			element = document.getElementById(id);

			if (element) {
				if (! (/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
					element.tabIndex = - 1;
				}

				element.focus();
			}
		}, false);
	}
}());
