/**
 * Front-end theme scripts
 *
 * @since 1.0.0
 */

jQuery(function ($) {
	'use strict';

	var $window = $(window);
	var $body = $('body');

	/* -----------------------------------------
	 Sticky Header
	 ----------------------------------------- */
	var $headMast = $('.header-sticky .head-mast-navigation');

	if ($.fn.sticky && $headMast.length > 0) {
		$headMast.sticky({
			className: 'sticky-fixed',
			topSpacing: 0,
			responsiveWidth: true,
			dynamicHeight: false,
		});
	}

	if ($.fn.shyheader && $headMast.length > 0) {
		$headMast.wrap($('<div />', { class: 'head-mast-sticky-container'}));

		$headMast.shyheader({
			classname: 'sticky-hidden',
			container: 'head-mast-sticky-container',
		});
	}

	// This + The following for Post Type Sliders
	/* -----------------------------------------
	 News Ticker
	 ----------------------------------------- */
	(function () {
		if (!$.fn.slick) {
			return;
		}

		var $ticker = $('.news-ticker-items');
		var $tickerPrev = $('.btn-news-ticker-prev');
		var $tickerNext = $('.btn-news-ticker-next');
		var $tickerTitle = $('.news-ticker-title');

		$ticker.slick({
			autoplay: true,
			arrows: false,
			dots: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			vertical: true,
		});

		$ticker.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
			var $slide = $(slick.$slides[nextSlide]);
			var color = $slide.data('color');
			$tickerTitle.css('background-color', color);
		});

		$tickerPrev.on('click', function () {
			$ticker.slick('slickPrev');
		});

		$tickerNext.on('click', function () {
			$ticker.slick('slickNext');
		});
	})();

	/* -----------------------------------------
	 Post Type Block Slideshows
	 ----------------------------------------- */
	(function () {
		if (!$.fn.slick) {
			return;
		}

		var $entryFeaturedSlide = $('.is-style-ignition-public-opinion-layout-overlay-slideshow > .row-items');

		$entryFeaturedSlide.each(function () {
			var $this = $(this);
			var columnsClass = $this.attr('class').split(' ').find(function (className) {
				return className.includes('row-columns-');
			}) || '';
			var slidesNo = Number(columnsClass.split('-').pop());

			$this.slick({
				dots: false,
				slidesToShow: slidesNo || 3,
				slidesToScroll: slidesNo || 3,
				prevArrow: '<button type="button" class="slick-prev"><span class="ignition-icons ignition-icons-angle-left"></span></button>',
				nextArrow: '<button type="button" class="slick-next"><span class="ignition-icons ignition-icons-angle-right"></span></i></button>',
				responsive: [
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: 4 === slidesNo || 3,
							slidesToScroll: 4 === slidesNo || 3,
						},
					},
					{
						breakpoint: 992,
						settings: {
							slidesToShow: 1 === slidesNo || 2,
							slidesToScroll: 1 === slidesNo || 2,
						},
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
						},
					},
				],
			});
		});
	})();
});
