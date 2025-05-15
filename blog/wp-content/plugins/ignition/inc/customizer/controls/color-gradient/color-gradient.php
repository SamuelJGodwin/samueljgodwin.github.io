<?php
	/**
	 * Color Gradient Customizer Control
	 *
	 * @since 1.0.0
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! class_exists( 'WP_Customize_Control' ) ) {
		return null;
	}

	/**
	 * Ignition_Customize_Color_Gradient_Control class.
	 *
	 * @since 1.0.0
	 */
	class Ignition_Customize_Color_Gradient_Control extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'ignition-color-gradient';

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @since 1.0.0
		 */
		public function enqueue() {
			$suffix = ignition_scripts_styles_suffix();

			wp_enqueue_script( $this->type . '-customize-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/color-gradient/customizer{$suffix}.js", array(
				'jquery',
				'lc-color-picker',
			), ignition_asset_version(), true );
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control_Gradient::to_json()
		 *
		 * @since 1.0.0
		 */
		public function to_json() {
			parent::to_json();

			$this->json['default'] = $this->setting->default;
			if ( isset( $this->default ) ) {
				$this->json['default'] = $this->default;
			}
			$this->json['defaultValue'] = $this->setting->default;

			$this->json['value'] = $this->value();
			$this->json['link']  = $this->get_link();
			$this->json['id']    = $this->id;
			$this->json['label'] = esc_html( $this->label );

			$this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control_Gradient::to_json()}.
		 *
		 * @see WP_Customize_Control_Gradient::print_template()
		 *
		 * @since 1.0.0
		 */
		protected function content_template() {
			?>
			<# var defaultValue = '';

			if ( data.defaultValue && _.isString( data.defaultValue ) ) {
			defaultValue = data.defaultValue;
			} #>
			<# if ( data.label ) { #>
			<label>
				<span class="customize-control-title">{{{ data.label }}}</span>
			</label>
			<# } #>
			<# if ( data.description ) { #>
			<span class="description customize-control-description">
			{{{ data.description }}}
		</span>
			<# } #>
			<div class="customize-control-content">
				<div class="ignition-color-gradient-picker-wrapper">
					<input
						class="ignition-color-gradient-picker"
						type="text"
						maxlength="28"
						data-alpha="true"
						value="{{data.value}}"
						data-default-color="{{defaultValue}}"
					/>

					<button
						class="button ignition-color-gradient-picker-button-clear"
						data-value=""
						type="button"
					>
						<?php esc_html_e( 'Clear', 'ignition' ); ?>
					</button>

					<# if ( defaultValue ) { #>
					<button
						class="button"
						data-value="{{defaultValue}}"
						type="button"
					>
						<?php esc_html_e( 'Default', 'ignition' ); ?>
					</button>
					<# } #>
				</div>
			</div>
			<?php
		}
	}
