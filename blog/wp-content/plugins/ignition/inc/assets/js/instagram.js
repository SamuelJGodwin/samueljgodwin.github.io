jQuery(function ($) {
	'use strict';

	/* -----------------------------------------
	Instagram Widget
	----------------------------------------- */
	var $instagramLists = $('.ignition-instagram-list');

	if (typeof $.fn.zoomLoadAsyncImages !== 'undefined' && $instagramLists.length) {
		$instagramLists.zoomLoadAsyncImages();
	}

	var $instagramWrap = $('.ignition-instagram-wrapper.ignition-carousel');

	if (typeof $.fn.slick !== 'undefined' && $instagramWrap.length) {
		$instagramWrap.each(function () {
			var $this = $(this);

			var $instagramWidget = $this.find('.ignition-instagram-list');
			var auto = $this.data('auto');
			var speed = $this.data('speed');
			var slides = $this.data('slides');
			var arrows = $this.data('arrows');

			$instagramWidget.slick({
				slidesToShow: slides,
				slidesToScroll: 1,
				arrows: arrows == 1,
				autoplay: auto == 1,
				swipeToSlide: true,
				speed: speed,
				prevArrow: '<span class="slick-arrow-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path d="M25.1 247.5l117.8-116c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L64.7 256l102.2 100.4c4.7 4.7 4.7 12.3 0 17l-7.1 7.1c-4.7 4.7-12.3 4.7-17 0L25 264.5c-4.6-4.7-4.6-12.3.1-17z"/></svg></span>',
				nextArrow: '<span class="slick-arrow-next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"/></svg></span>',
				responsive: [
					{
						breakpoint: 992,
						settings: {
							arrows: false,
							slidesToShow: 6
						}
					},
					{
						breakpoint: 576,
						settings: {
							arrows: false,
							slidesToShow: 4
						}
					}
				]
			});
		})
	}
});
