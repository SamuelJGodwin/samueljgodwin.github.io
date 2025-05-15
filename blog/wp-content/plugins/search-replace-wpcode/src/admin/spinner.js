const WSRSpinner = window.WSRSpinner || (
	function ( document, window, $ ) {
		const app                    = {
			init() {
				window.WSRSpinner = app;
				app.spinner          = $( '#wsrw-admin-spinner' );
			},
			show_button_spinner( $button, position = 'right' ) {
				if( ! $button.length ) {
					return;
				}
				$button.prop( 'disabled', true );
				const offset     = $button.offset();
				const menu_width = $( '#adminmenuwrap' ).width();
				const admin_bar  = $( '#wpadminbar' ).height();
				let css          = {};
				app.spinner.show();
				if ( 'right' === position ) {
					css = {
						left: offset.left - menu_width + $button.outerWidth(),
						top: offset.top - admin_bar + $button.outerHeight() / 2 - app.spinner.height() / 2,
					};
				} else {
					css = {
						left: offset.left - menu_width - app.spinner.outerWidth() - 20,
						top: offset.top - admin_bar + $button.outerHeight() / 2 - app.spinner.height() / 2,
					};
				}

				app.spinner.css( css );
			},
			hide_button_spinner( $button ) {
				$button.prop( 'disabled', false );
				app.spinner.hide();
			},
		};
		return app;
	}( document, window, jQuery )
);

WSRSpinner.init();
