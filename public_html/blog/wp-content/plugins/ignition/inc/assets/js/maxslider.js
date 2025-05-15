jQuery(window).on('load', function () {
	'use strict';

	var $body = jQuery('body');
	var isRTL = $body.hasClass('rtl');

	if (jQuery.fn.slick) {
		var $ignitionSlideshow = jQuery('.ignition-slideshow');

		if ($ignitionSlideshow.length) {
			$ignitionSlideshow.each(function () {
				var $this = jQuery(this);
				var navigation = $this.data('navigation');
				var effect = $this.data('effect');
				var speed = $this.data('slide-speed');
				var auto = $this.data('autoslide');

				$this.slick({
					dots: navigation === 'dots' || navigation === 'all',
					arrows: navigation === 'arrows' || navigation === 'all',
					fade: effect === 'fade',
					autoplaySpeed: speed,
					autoplay: auto === true,
					rtl: isRTL,
					prevArrow: '<button type="button" class="slick-arrow-prev"><span class="ignition-icons ignition-icons-angle-left"></span></button>',
					nextArrow: '<button type="button" class="slick-arrow-next"><span class="ignition-icons ignition-icons-angle-right"></span></button>',
					responsive: [
						{
							breakpoint: 767,
							settings: {
								arrows: false,
								dots: ! ! navigation
							}
						}
					]
				});
			});
		}
	}
});
