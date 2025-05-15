/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview changes asynchronously.
 *
 * https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#using-postmessage-for-improved-setting-previewing
 */

(function ( $ ) {
	//
	// Lightbox
	//
	wp.customize( 'utilities_lightbox_is_enabled', function ( value ) {
		value.bind( function ( to ) {
			if ( to ) {
				$( ".ignition-lightbox, a[data-lightbox^='gal']" ).magnificPopup( {
					type: 'image',
					mainClass: 'mfp-with-zoom',
					gallery: {
						enabled: true
					},
					zoom: {
						enabled: true
					},
					image: {
						titleSrc: function ( item ) {
							var $item          = item.el;
							var $parentCaption = $item.parents( '.wp-caption' ).first();

							if ( $item.attr( 'title' ) ) {
								return $item.attr( 'title' );
							}

							if ( $parentCaption ) {
								return $parentCaption.find( '.wp-caption-text' ).text();
							}
						},
					},
				} );
			} else {
				$( ".ignition-lightbox, a[data-lightbox^='gal']" ).off( 'click' );
			}
		} );
	} );

})( jQuery );
