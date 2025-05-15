(function ( $ ) {
	'use strict';

	wp.customize.controlConstructor[ 'ignition-typography' ] = wp.customize.Control.extend( {
		ready: function () {
			var control = this;

			var $fontSelect      = control.container.find( '[data-property="family"]' );
			var $variantSelect   = control.container.find( '[data-property="variant"]' );
			var $fontSizeInput   = control.container.find( '[data-property="size"]' );
			var $lineHeightInput = control.container.find( '[data-property="lineHeight"]' );
			var $transformSelect = control.container.find( '[data-property="transform"]' );
			var $spacingInput    = control.container.find( '[data-property="spacing"]' );

			//
			// Font family select
			//
			$fontSelect.on( 'change', function () {
				var $this                    = $( this );
				var fontFamily               = $this.val();
				var font                     = _.findWhere( ignition_gfonts.fonts, { family: fontFamily } );
				var $breakpointVariantSelect = $this.parents( '[data-breakpoint]' ).find( '[data-property="variant"]' );
				var breakpoint               = $this.parents( '[data-breakpoint]' ).data( 'breakpoint' );

				if ( font ) {
					// Fill in the new font family's variants and set
					// the default variant
					var $variants = font.variants.map( function ( variant ) {
						return $( '<option />', {
							value: variant,
							selected: variant === 'regular',
							text: ignition_gfonts.variant_labels[ variant ],
						} );
					} );

					$breakpointVariantSelect.html( $variants );
					$breakpointVariantSelect.parent().show();

					control.saveValues( {
						family: fontFamily,
						variant: $variantSelect.val(),
						is_gfont: font.kind === 'webfonts#webfont',
					}, breakpoint );
				} else {
					control.saveValues( {
						family: '',
						variant: '',
					}, breakpoint );
					$breakpointVariantSelect.parent().hide();
				}
			} );

			//
			// Font variant select
			//
			$variantSelect.on( 'change', function () {
				var $this      = $( this );
				var breakpoint = $this.parents( '[data-breakpoint]' ).data( 'breakpoint' );

				control.saveValues( {
					variant: $this.val(),
				}, breakpoint );
			} );

			//
			// Font size input
			//
			$fontSizeInput.on( 'change keyup', function () {
				var $this      = $( this );
				var breakpoint = $this.parents( '[data-breakpoint]' ).data( 'breakpoint' );

				control.saveValues( {
					size: $this.val(),
				}, breakpoint );
			} );

			//
			// Line height input
			//
			$lineHeightInput.on( 'change keyup', function () {
				var $this      = $( this );
				var $wrapper   = $this.parents( '.ignition-control-range' );
				var value      = $this.val();
				var breakpoint = $this.parents( '[data-breakpoint]' ).data( 'breakpoint' );

				// Sync the controls
				if ( $this.attr( 'type' ) === 'range' ) {
					$wrapper.find( 'input[type="number"]' ).val( value );
				} else {
					$wrapper.find( 'input[type="range"]' ).val( value );
				}

				control.saveValues( {
					lineHeight: $this.val(),
				}, breakpoint );
			} );

			//
			// Text transform select
			//
			$transformSelect.on( 'change', function () {
				var $this      = $( this );
				var breakpoint = $this.parents( '[data-breakpoint]' ).data( 'breakpoint' );

				control.saveValues( {
					transform: $this.val(),
				}, breakpoint );
			} );

			//
			// Letter spacing select
			//
			$spacingInput.on( 'change keyup', function () {
				var $this      = $( this );
				var breakpoint = $this.parents( '[data-breakpoint]' ).data( 'breakpoint' );

				control.saveValues( {
					spacing: parseFloat( $this.val() ),
				}, breakpoint );
			} );

			//
			// Responsive Controls
			//
			control.container.on( 'click', '.button-group-devices button', function () {
				var index  = $( this ).index();
				var device = $( this ).data( 'device' );

				control.displayDeviceControls( device );

				// Trigger the Customizer's responsive controls
				$( '.wp-full-overlay-footer .devices button' ).eq( index ).trigger( 'click' );
				$( 'body' ).trigger( 'on-responsive-mode-change', [ device, control ] );
			} );

			$( 'body' ).on( 'on-responsive-mode-change', function ( event, device, ref ) {
				if ( control !== ref ) {
					control.displayDeviceControls( device );
				}
			} );
		},

		displayDeviceControls: function ( device ) {
			var control  = this;
			var $buttons = control.container.find( '.button-group-devices button' );
			var sections = control.container.find( '.ignition-typography-control-group-wrap' );
			var $button  = $buttons.filter( '[data-device="' + device + '"]' );
			var index    = $button.index();

			$buttons.removeClass( 'active' );
			$button.addClass( 'active' );
			sections.hide().eq( index ).css( 'display', 'block' );
		},

		saveValues: function ( values, breakpoint ) {
			var control = this;
			var $input  = control.container.find( 'input[type="hidden"]' );

			breakpoint = breakpoint || 'desktop';

			var value = control.setting.get();
			_.extend( value[ breakpoint ], values );

			$input.val( JSON.stringify( value ) ).trigger( 'change' );
			control.setting.set( value );
		},
	} );
}( jQuery ));
