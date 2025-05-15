(function ( $ ) {
	wp.customize.controlConstructor[ 'ignition-color-gradient' ] = wp.customize.Control.extend( {
		ready: function () {
			var control = this;
			var $container = control.container;
			var $picker = control.container.find( '.ignition-color-gradient-picker' );

			var pickerInstance = new lc_color_picker('.ignition-color-gradient-picker', {
				modes: ['solid', 'linear-gradient', 'radial-gradient'],
				transparency: true,
				preview_style: {
					side: 'left'
				},
				fallback_colors: ['rgba(0,0,0,0.35)', 'linear-gradient(180deg, rgba(255,255,255,0), rgba(0,0,0,0.35))'],
				on_change: function ( value, target ) {
					control.setting.set( value );
				}
			});

			// Reset to default / clear value functionality
			control.container.on( 'click', '.button', function () {
				var $this = $(this);
				var value = $this.data( 'value' );
				control.setting.set( value );
				$picker.val( value );
				$container.find('.lccp-preview').css( 'background', value || 'none' );
				try {
					pickerInstance.apply_color_change( value );
				} catch (error) {}
			} );
		}
	} );
}( jQuery ));
