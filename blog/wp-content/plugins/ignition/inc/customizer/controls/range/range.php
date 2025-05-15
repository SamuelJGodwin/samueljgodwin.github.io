<?php
/**
 * Range Customizer Control
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
 * Ignition_Customize_Range_Control class.
 *
 * @since 1.0.0
 */
class Ignition_Customize_Range_Control extends WP_Customize_Control {
	/**
	 * The type of control.
	 *
	 * @var string
	 */
	public $type = 'ignition-range';

	public $responsive = true;

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		$suffix = ignition_scripts_styles_suffix();

		wp_enqueue_script( $this->type . '-customize-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/range/customizer{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['id']         = $this->id;
		$this->json['link']       = $this->get_link();
		$this->json['inputAttrs'] = $this->input_attrs;

		$values = $this->value();
		if ( ! $this->responsive ) {
			unset( $values['tablet'], $values['mobile'] );
		}
		$this->json['values'] = $values;

		$this->json['responsive'] = $this->responsive;
	}

	/**
	 * Don't render anything with PHP as rendering is handled via JS templates.
	 *
	 * @since 1.0.0
	 */
	public function render_content() { }

	/**
	 * The Underscore (JS) template for this control's content.
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>
		<div class="ignition-control-range-wrap">
			<# if ( data.label ) { #>
				<label
					id="{{data.id}}-label"
					for="{{data.id}}-range-input"
					class="customize-control-title"
				>
					{{ data.label }}
				</label>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{ data.description }}</span>
			<# } #>

			<# if ( data.responsive ) { #>
				<div class="button-group-devices">
					<button type="button" data-device="desktop" class="preview-desktop active">
						<span class="screen-reader-text"><?php echo esc_html_x( 'Desktop', 'device', 'ignition' ); ?></span>
					</button>
					<button type="button" data-device="tablet" class="preview-tablet">
						<span class="screen-reader-text"><?php echo esc_html_x( 'Tablet', 'device', 'ignition' ); ?></span>
					</button>
					<button type="button" data-device="mobile" class="preview-mobile">
						<span class="screen-reader-text"><?php echo esc_html_x( 'Mobile', 'device', 'ignition' ); ?></span>
					</button>
				</div>
			<# } #>

			<div class="ignition-control-range-breakpoint-wrap">
				<# _.each( data.values, function ( value, breakpoint ) { #>
					<div
						class="ignition-control-range ignition-responsive-controls-{{breakpoint}}"
						data-breakpoint="{{breakpoint}}"
					>
						<div class="ignition-control-range-slider">
							<input
								type="range"
								aria-labelledby="{{data.id}}-{{breakpoint}}-label"
								name="{{data.id}}-{{breakpoint}}-range-slider-input"
								id="{{data.id}}-{{breakpoint}}-range-slider-input"
								class="ignition-control-range-slider-input"
								min="{{data.inputAttrs.min}}"
								max="{{data.inputAttrs.max}}"
								step="{{data.inputAttrs.step}}"
								value={{value}}
								placeholder="0"
							>
						</div>

						<input
							type="number"
							name="{{data.id}}-{{breakpoint}}-range-input"
							id="{{data.id}}-{{breakpoint}}-range-input"
							class="ignition-control-range-input"
							min="{{data.inputAttrs.min}}"
							max="{{data.inputAttrs.max}}"
							step="{{data.inputAttrs.step}}"
							value="{{value}}"
						>
					</div>
				<# } ); #>
			</div>

			<input class="ignition-range-control-hidden-value" type="hidden" {{{ data.link }}}>
		</div>
		<?php
	}
}
