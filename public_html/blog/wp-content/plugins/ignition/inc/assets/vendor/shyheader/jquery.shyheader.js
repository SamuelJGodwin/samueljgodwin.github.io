/*
 * BASED ON THE ORIGINAL IDEA OF Marius Craciunoiu
 * https://medium.com/@mariusc23/hide-header-on-scroll-down-show-on-scroll-up-67bbaae9a78c#.wc32ja29i
 *
 *
 * jQuery.shyheader v0.1.0
 * https://github.com/alejandromur/shyheader
 * Copyright 2016, alejandro@mamutlove.es
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

(function ($, window, document, undefined) {

	"use strict";

	$.shyheader = function (el, options) {

		var base = this;

		base.$el = $(el);
		base.el = el;

		base.$el.data('shyheader', base);

		var IS_SCROLLING = false;
		var SCROLL = 0;
		var OLD_OFFSET = 0;
		var CURRENT_OFFSET = 0;
		var DELTA = 20;
		var HEADER_HEIGHT = 0;
		var BODY = "";

		base.initialize = function () {
			base.options = $.extend({}, $.shyheader.defaultOptions, options);

			HEADER_HEIGHT = base.$el.outerHeight(true);

			if (base.options.container !== "undefined") {
				BODY = $('.' + base.options.container);
				BODY.css('height', HEADER_HEIGHT + "px");
				base.options.offsetContentFlag = true;
			}

			window.addEventListener("scroll", base.triggerScroll, false);

		};

		base.triggerScroll = function () {
			IS_SCROLLING = true;
			SCROLL = document.body.scrollTop || window.pageYOffset;
			base.checkScrollPosition();
		};

		base.checkScrollPosition = function () {
			if (base.options.offsetContentFlag) {
				var OFFSET_TOP = base.$el.offset().top;
				if (SCROLL >= OFFSET_TOP && !base.$el.hasClass(base.options.stuckclass)) {
					base.$el.addClass(base.options.stuckclass);

					if (base.options.onStick) {
						base.options.onStick();
					}
				}

				// Remove the sticky-fixed class when the header
				// reaches its starting point or the top of the header.
				var topThreshold = 5;
				var isTopped = OFFSET_TOP < topThreshold && BODY.offset().top < topThreshold && SCROLL < topThreshold;
				if (isTopped || (OFFSET_TOP < BODY.offset().top && base.$el.hasClass(base.options.stuckclass))) {
					base.$el.removeClass(base.options.stuckclass);

					if (base.options.onUnstick) {
						base.options.onUnstick();
					}
				}

				// If it's already "stuck activated" do not calculate its offset
				if (base.$el.hasClass(base.options.activeclass)) {
					if (SCROLL >= HEADER_HEIGHT) {
						base.watch();
					} else if (base.$el.offset().top <= BODY.offset().top) {
						base.$el.removeClass(base.options.activeclass);
					}
				} else {
					// Otherwise (not stuck activated) add its offset in the calculation.
					if (SCROLL >= HEADER_HEIGHT) {
						base.watch();
					}
				}
			} else {
				base.watch();
			}
		};

		base.watch = function () {
			if (IS_SCROLLING) {
				base.getDirection();
				IS_SCROLLING = false;
			}
		};

		base.getDirection = function () {

			CURRENT_OFFSET = SCROLL;

			if (Math.abs(OLD_OFFSET - CURRENT_OFFSET) <= DELTA || SCROLL < BODY.offset().top) {
				return;
			}

			if (!base.$el.hasClass(base.options.activeclass)) {
				base.$el.addClass(base.options.activeclass);
			}

			if (CURRENT_OFFSET > OLD_OFFSET) {
				base.$el.addClass(base.options.classname);
			} else if (CURRENT_OFFSET + $(window).height() < $(document).height()) {
				base.$el.removeClass(base.options.classname);
			}

			OLD_OFFSET = CURRENT_OFFSET;
		};

		base.destroy = function () {
			base.$el.removeClass(base.options.classname);
			base.$el.removeClass(base.options.activeclass);
			base.$el.removeClass(base.options.stuckclass);
			window.removeEventListener('scroll', base.triggerScroll, false);
			base.$el.shyheader = null;
			base.$el.removeData('shyheader');

			if (base.options.offsetContentFlag) {
				BODY.css('height', '');
			}
		};

		base.initialize();
	};


	$.shyheader.defaultOptions = {
		classname: 'sticky-hidden',
		container: undefined,
		activeclass: 'sticky-active',
		stuckclass: 'sticky-fixed',
		offsetContentFlag: false,
		onStick: function() {},
		onUnstick: function() {},
	};

	$.fn.shyheader = function (options) {

		return this.each(function () {
			var shyheader = new $.shyheader(this, options);
		});
	};

}(jQuery, window, document));
