<?php
/**
 * Spacing Customizer Control
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
 * Ignition_Customize_Spacing_Control class.
 *
 * @since 1.0.0
 */
class Ignition_Customize_Spacing_Control extends WP_Customize_Control {
	/**
	 * The type of control.
	 *
	 * @var string
	 */
	public $type = 'ignition-spacing';

	public $responsive = true;

	/**
	 * Mode of control.
	 * By default, all four directions are shown. Possible values: 'top-bottom', 'left-right'
	 *
	 * @var string
	 */
	public $mode = 'all';

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		$suffix = ignition_scripts_styles_suffix();

		wp_enqueue_script( $this->type . '-customize-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/spacing/customizer{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
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
		$this->json['mode']       = $this->mode;
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
		<div class="ignition-spacing-control-wrap mode-{{data.mode}}">
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{ data.description }}</span>
			<# } #>

			<# if ( data.responsive ) { #>
				<div class="button-group-devices">
					<button type="button" data-device="desktop" class="preview-desktop active">
						<span class="screen-reader-text"><?php esc_html_e( 'Desktop', 'ignition' ); ?></span>
					</button>
					<button type="button" data-device="tablet" class="preview-tablet">
						<span class="screen-reader-text"><?php esc_html_e( 'Tablet', 'ignition' ); ?></span>
					</button>
					<button type="button" data-device="mobile" class="preview-mobile">
						<span class="screen-reader-text"><?php esc_html_e( 'Mobile', 'ignition' ); ?></span>
					</button>
				</div>
			<# } #>

			<div class="ignition-spacing-control-breakpoints">
				<# _.each( data.values, function( values, breakpoint ) { #>
					<# var linked_class = values.linked ? 'button-primary' : ''; #>
					<div class="ignition-spacing-control-group-wrap ignition-responsive-controls-{{ breakpoint }}">
						<div class="ignition-spacing-control-group input-top">
							<label for="{{data.id}}-{{breakpoint}}-spacing-top">
								<?php esc_html_e( 'Top', 'ignition' ); ?>
							</label>
							<input
								type="number"
								id="{{data.id}}-{{breakpoint}}-spacing-top"
								min="{{data.inputAttrs.min}}"
								max="{{data.inputAttrs.max}}"
								step="{{data.inputAttrs.step}}"
								value="{{values.top}}"
								data-breakpoint="{{breakpoint}}"
								data-property="top"
							>
						</div>

						<div class="ignition-spacing-control-group input-right">
							<label for="{{data.id}}-{{breakpoint}}-spacing-right">
								<?php esc_html_e( 'Right', 'ignition' ); ?>
							</label>
							<input
								type="number"
								id="{{data.id}}-{{breakpoint}}-spacing-right"
								min="{{data.inputAttrs.min}}"
								max="{{data.inputAttrs.max}}"
								step="{{data.inputAttrs.step}}"
								value="{{values.right}}"
								data-breakpoint="{{breakpoint}}"
								data-property="right"
							>
						</div>

						<div class="ignition-spacing-control-group input-bottom">
							<label for="{{data.id}}-{{breakpoint}}-spacing-bottom">
								<?php esc_html_e( 'Bottom', 'ignition' ); ?>
							</label>
							<input
								type="number"
								id="{{data.id}}-{{breakpoint}}-spacing-bottom"
								min="{{data.inputAttrs.min}}"
								max="{{data.inputAttrs.max}}"
								step="{{data.inputAttrs.step}}"
								value="{{values.bottom}}"
								data-breakpoint="{{breakpoint}}"
								data-property="bottom"
							>
						</div>

						<div class="ignition-spacing-control-group input-left">
							<label for="{{data.id}}-{{breakpoint}}-spacing-left">
								<?php esc_html_e( 'Left', 'ignition' ); ?>
							</label>
							<input
								type="number"
								id="{{data.id}}-{{breakpoint}}-spacing-left"
								min="{{data.inputAttrs.min}}"
								max="{{data.inputAttrs.max}}"
								step="{{data.inputAttrs.step}}"
								value="{{values.left}}"
								data-breakpoint="{{breakpoint}}"
								data-property="left"
							>
						</div>

						<div class="ignition-spacing-control-group input-link">
							<button
								type="button"
								class="button button-link-spacing {{linked_class}}"
								data-breakpoint="{{breakpoint}}"
								data-property="linked"
							>
								<span class="dashicons dashicons-admin-links"></span>
							</button>
						</div>
					</div>

				<# } ); #>
			</div>

			<input class="ignition-spacing-control-hidden-value" type="hidden" {{{ data.link }}}>
		</div>
		<?php
	}
}
