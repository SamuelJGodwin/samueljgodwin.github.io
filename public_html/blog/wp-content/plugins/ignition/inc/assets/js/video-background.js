jQuery(function ($) {
	'use strict';

	var $window = $(window);

	/* -----------------------------------------
	 Page Hero Video Backgrounds
	 ----------------------------------------- */
	var $videoBg = $('.page-hero-video-background');
	var $videoWrap = $videoBg.parents('.page-hero-video-wrap');

	// YouTube videos
	function onYouTubeAPIReady($videoBg) {
		if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
			return setTimeout(onYouTubeAPIReady.bind(null, $videoBg), 333);
		}

		var $video = $videoBg.find('div').get(0);
		var ytPlayer = new YT.Player($video, {
			videoId: $videoBg.data('video-id'),
			playerVars: {
				autoplay: 1,
				controls: 0,
				showinfo: 0,
				modestbranding: 1,
				loop: 1,
				playlist: $videoBg.data('video-id'),
				fs: 0,
				cc_load_policy: 0,
				iv_load_policy: 3,
				autohide: 0,
				start: parseInt($videoBg.data('video-start'), 10) || undefined,
			},
			events: {
				onReady: function (event) {
					event.target.mute();
					event.target.playVideo();
				},
				onStateChange: function (event) {
					if (event.data === YT.PlayerState.PLAYING) {
						$videoWrap.addClass('visible');
						adjustVideoSize();
					}
				}
			}
		});
	}

	// Vimeo videos
	function onVimeoAPIReady($videoBg) {
		if (typeof Vimeo === 'undefined' || typeof Vimeo.Player === 'undefined') {
			return setTimeout(onVimeoAPIReady.bind(null, $videoBg), 333);
		}

		var startTime = parseInt($videoBg.data('video-start'), 10) || undefined;

		var player = new Vimeo.Player($videoBg, {
			id: $videoBg.data('video-id'),
			loop: true,
			autoplay: true,
			byline: false,
			title: false,
			autopause: false,
			muted: true,
		});

		player.setVolume(0);

		if (startTime) {
			player.setCurrentTime(startTime);
		}

		player.on('play', function () {
			$videoWrap.addClass('visible');
			adjustVideoSize();
		});
	}

	function onSelfHostedVideo() {
		var source = $videoBg.data('video-id');

		var $nativeVideo = $('<video src="'+ source +'" autoplay muted loop playsinline />')
			.on('playing', function () {
				$videoWrap.addClass('visible');
			});

		$videoBg.append($nativeVideo);
		$window.off('resize.ciVideo');
	}

	var videoResizeTimer;

	$window.on( 'resize.ciVideo', function () {
		clearTimeout( videoResizeTimer );
		videoResizeTimer = setTimeout( function () {
			adjustVideoSize();
		}, 350 );
	} );

	function getVideoSize() {
		var containerWidth = $videoWrap.outerWidth();
		var containerHeight = $videoWrap.outerHeight();
		var aspectRatio = 16/9;
		var ratioWidth = containerWidth / aspectRatio;
		var ratioHeight = containerHeight * aspectRatio;
		var isWidthFixed = (containerWidth / containerHeight) > aspectRatio;

		return {
			width: isWidthFixed ? containerWidth : ratioHeight,
			height: isWidthFixed ? ratioWidth : containerHeight,
		}
	}

	function adjustVideoSize() {
		var size = getVideoSize();

		$videoBg.find('iframe').css({
			width: size.width + 'px',
			height: size.height + 'px',
		});
	}

	if ($videoBg.length && window.innerWidth > 1080) {
		$videoBg.each(function () {
			var $this = $(this);
			var firstScript = $('script');
			var videoType = $this.data('video-type');

			if (videoType === 'youtube') {
				if (!$('#youtube-api-script').length) {
					var tag = $('<script />', { id: 'youtube-api-script' });
					tag.attr('src', 'https://www.youtube.com/player_api');
					firstScript.parent().prepend(tag);
				}
				onYouTubeAPIReady($this);
			} else if (videoType === 'vimeo') {
				if (!$('#vimeo-api-script').length) {
					var tag = $('<script />', { id: 'vimeo-api-script' });
					tag.attr('src', 'https://player.vimeo.com/api/player.js');
					firstScript.parent().prepend(tag);
				}
				onVimeoAPIReady($this);
			} else if (videoType === 'self') {
				onSelfHostedVideo();
			}
		});
	}
});
