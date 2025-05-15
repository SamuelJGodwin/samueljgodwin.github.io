const WSRInputs = window.WSRInputs || (
	function ( document, window, $ ) {
		const app = {
			init() {
				$( app.ready );
			},
			ready() {
				app.initFileUploads();
				app.initCheckboxMultiselectColumns();
			},
			initFileUploads() {
				$( '.wsrw-file-upload' ).each(
					function () {
						const $input = $( this ).find( 'input[type=file]' ),
							$label = $( this ).find( 'label' ),
							labelVal = $label.html();
						$input.on(
							'change',
							function ( event ) {
								let fileName = '';
								if ( this.files && this.files.length > 1 ) {
									fileName = (
										this.getAttribute( 'data-multiple-caption' ) || ''
									).replace( '{count}', this.files.length );
								} else if ( event.target.value ) {
									fileName = event.target.value.split( '\\' ).pop();
								}
								if ( fileName ) {
									$label.find( '.wsrw-file-field' ).html( fileName );
								} else {
									$label.html( labelVal );
								}
							}
						);
						// Firefox bug fix.
						$input.on(
							'focus',
							function () {
								$input.addClass( 'has-focus' );
							}
						).on(
							'blur',
							function () {
								$input.removeClass( 'has-focus' );
							}
						);
					}
				);
			},
			initCheckboxMultiselectColumns() {

				let checked = false;

				$( document ).on(
					'change',
					'.wsrw-checkbox-multiselect-columns input',
					function () {

						var $this = $( this ),
							$parent = $this.parent(),
							$container = $this.closest( '.wsrw-checkbox-multiselect-columns' ),
							label = $parent.text(),
							itemID = 'check-item-' + $this.val(),
							$item = $container.find( '#' + itemID );

						if ( $this.prop( 'checked' ) ) {
							$this.parent().addClass( 'checked' );
							if ( !$item.length ) {
								$container.find( '.second-column ul' ).append( '<li id="' + itemID + '">' + label + '</li>' );
							}
						} else {
							$this.parent().removeClass( 'checked' );
							$container.find( '#' + itemID ).remove();
						}
					}
				);

				$( document ).on(
					'click',
					'.wsrw-checkbox-multiselect-columns .all',
					function ( event ) {

						event.preventDefault();

						checked = !checked;

						$( this ).closest( '.wsrw-checkbox-multiselect-columns' ).find( 'input[type=checkbox]' ).prop( 'checked', checked ).trigger( 'change' );
					}
				);

				$( document ).on(
					'input',
					'.wsrw-multiselect-search input',
					function ( event ) {
						// Let's hide the li elements in the .next() ul that don't match the search term.
						const $this = $( this ),
							$container = $this.parent(),
							$ul = $container.next( 'ul' ),
							search = $this.val().toLowerCase();

						$ul.find( 'li' ).each( function () {
							const $li = $( this ),
								text = $li.text().toLowerCase();

							if ( text.indexOf( search ) === - 1 ) {
								$li.hide();
							} else {
								$li.show();
							}

						} );

					}
				);
			},

		};
		return app;
	}( document, window, jQuery )
);

WSRInputs.init();
