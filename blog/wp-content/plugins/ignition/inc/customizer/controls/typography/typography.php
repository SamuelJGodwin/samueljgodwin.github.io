<?php
/**
 * Typography Customizer Control
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
 * Ignition_Customize_Typography_Control class.
 *
 * @since 1.0.0
 */
class Ignition_Customize_Typography_Control extends WP_Customize_Control {
	/**
	 * The type of control.
	 *
	 * @var string
	 */
	public $type = 'ignition-typography';

	public $responsive = true;

	public $placeholder = false;

	public $show_family = true;

	public $show_variant = true;

	public $show_attributes = true;

	/**
	 * The inner fields (settings) for this control.
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * The rendered options markup for all fonts.
	 *
	 * @var array
	 */
	private $font_options = array();

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		if ( ! wp_script_is( $this->type . '-customize-control', 'enqueued' ) ) {
			$suffix = ignition_scripts_styles_suffix();

			wp_enqueue_script( $this->type . '-customize-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/typography/customizer{$suffix}.js", array(
				'jquery',
			), ignition_asset_version(), true );

			$fonts_list = Ignition_Fonts_List::get_instance();
			wp_localize_script( $this->type . '-customize-control', 'ignition_gfonts', array(
				'fonts'             => $fonts_list->get(),
				'font_options'      => $fonts_list->get_font_options(),
				'transform_choices' => $fonts_list->get_transform_choices(),
				'variant_labels'    => $fonts_list->get_variant_labels(),
				'variant_weights'   => array_keys( $fonts_list->get_variant_labels() ),
			) );
		}
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['id']   = $this->id;
		$this->json['link'] = $this->get_link();

		$this->json['show_family']     = $this->show_family;
		$this->json['show_variant']    = $this->show_variant;
		$this->json['show_attributes'] = $this->show_attributes;

		$values = $this->value();
		if ( ! $this->responsive ) {
			unset( $values['tablet'], $values['mobile'] );
		}
		$this->json['values'] = $values;

		$this->json['responsive'] = $this->responsive;

		$this->json['placeholder'] = $this->placeholder;
		$this->json['empty_value'] = ignition_typography_control_defaults_empty_breakpoints();
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
		<div class="ignition-typography-control-wrap">
			<# if ( data.label ) { #>
				<span class="customize-control-title">
					{{ data.label }}
				</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">
					{{{ data.description }}}
				</span>
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

			<div class="ignition-typography-control-breakpoints">
				<# _.each( data.values, function( values, breakpoint ) { #>
					<#
						var placeholder;
						if ( data.placeholder && data.placeholder[breakpoint] ) {
							placeholder = data.placeholder[breakpoint];
						} else {
							placeholder = data.empty_value;
						}
					#>
					<div class="ignition-typography-control-group-wrap ignition-responsive-controls-{{ breakpoint }}" data-breakpoint="{{breakpoint}}">
						<# if ( breakpoint === 'desktop' ) { #>
							<# if ( ignition_gfonts.fonts && ignition_gfonts.fonts.length ) { #>
								<# if ( data.show_family ) { #>
									<div class="ignition-typography-control-wrap">
										<label for="{{data.id}}-{{breakpoint}}-font-select">
											<?php esc_html_e( 'Font Family', 'ignition' ); ?>
										</label>
										<select
											name="{{data.id}}-{{breakpoint}}-font-select"
											id="{{data.id}}-{{breakpoint}}-font-select"
											class="ignition-control-typography-font-family-select"
											data-control-id="{{data.id}}"
											data-property="family"
										>
											<# _.each(ignition_gfonts.font_options, function (group) { #>
												<optgroup label="{{group.label}}">
													<# _.each(group.fonts, function (font) { #>
														<option
															value="{{ font.family }}"
															{{{ (font.family === values.family) ? 'selected' : '' }}}
														>
															{{ font.label || font.family }}
														</option>
													<# }); #>
												</optgroup>
											<# }); #>
										</select>
									</div>
								<# } #>

								<# if ( data.show_variant ) { #>
									<#
										var font = _.findWhere(ignition_gfonts.fonts, { family: values.family });
										var applicableVariants = font ? font.variants : ignition_gfonts.variant_weights;
									#>
									<# if ( applicableVariants ) { #>
										<div
											class="ignition-typography-control-wrap"
										>
											<label for="{{data.id}}-{{breakpoint}}-font-variant-select">
												<?php esc_html_e( 'Font Variant', 'ignition' ); ?>
											</label>
											<select
												name="{{data.id}}-{{breakpoint}}-font-variant-select"
												id="{{data.id}}-{{breakpoint}}-font-variant-select"
												class="ignition-control-typography-font-variant-select"
												data-property="variant"
											>
												<#	_.each(applicableVariants, function (variant) { #>
													<option
														value="{{variant}}"
														{{{ (variant === values.variant) ? 'selected' : '' }}}
													>
														{{ ignition_gfonts.variant_labels[variant] }}
													</option>
												<# }); #>
											</select>
										</div>
									<# } #>
								<# } #>
							<# } #>
						<# } #>

						<# if ( data.show_attributes ) { #>
							<div class="ignition-typography-control-split">
								<div class="ignition-typography-control-wrap">
									<label
										for="{{data.id}}-{{breakpoint}}-font-size-control-input"
										id="{{data.id}}-{{breakpoint}}-font-size-control-label"
									>
										<?php esc_html_e( 'Font Size (px)', 'ignition' ); ?>
									</label>
									<input
										type="number"
										name="{{data.id}}-{{breakpoint}}-font-size-control-input"
										id="{{data.id}}-{{breakpoint}}-font-size-control-input"
										class="ignition-control-range-input"
										min="0"
										max="100"
										step="1"
										data-property="size"
										value="{{values.size}}"
										placeholder="{{placeholder.size}}"
									>
								</div>
								<div class="ignition-typography-control-wrap">
									<label
										for="{{data.id}}-{{breakpoint}}-line-height-control-input"
										id="{{data.id}}-{{breakpoint}}-line-height-control-label"
									>
										<?php esc_html_e( 'Line Height', 'ignition' ); ?>
									</label>

									<input
										type="number"
										name="{{data.id}}-{{breakpoint}}-line-height-control-input"
										id="{{data.id}}-{{breakpoint}}-line-height-control-input"
										class="ignition-control-range-input"
										min="0"
										max="10"
										step="0.01"
										data-property="lineHeight"
										value="{{values.lineHeight}}"
										placeholder="{{placeholder.lineHeight}}"
									>
								</div>
							</div>

							<div class="ignition-typography-control-split">
								<div class="ignition-typography-control-wrap">
									<label for="{{data.id}}-{{breakpoint}}-font-transform">
										<?php esc_html_e( 'Text Transform', 'ignition' ); ?>
									</label>
									<select
										name="{{data.id}}-{{breakpoint}}-font-transform"
										id="{{data.id}}-{{breakpoint}}-font-transform"
										data-property="transform"
									>
										<# var transform = values.transform || placeholder.transform; #>
										<# _.each( ignition_gfonts.transform_choices, function (label, value) { #>
											<option
												value="{{value}}"
												{{{ (value === transform) ? 'selected' : '' }}}
											>
												{{ label }}
											</option>
										<# }); #>
									</select>
								</div>

								<div class="ignition-typography-control-wrap">
									<label for="{{data.id}}-{{breakpoint}}-font-spacing-control-input">
										<?php esc_html_e( 'Letter Spacing (em)', 'ignition' ); ?>
									</label>

									<input
										type="number"
										name="{{data.id}}-{{breakpoint}}-font-spacing-control-input"
										id="{{data.id}}-{{breakpoint}}-font-spacing-control-input"
										min="-2"
										max="10"
										step="0.01"
										data-property="spacing"
										value="{{values.spacing}}"
										placeholder="{{placeholder.spacing}}"
									>
								</div>
							</div>
						<# } #>
					</div>

				<# } ); #>

				<input class="ignition-typography-control-hidden-value" type="hidden" {{{ data.link }}}>
			</div>

		</div>
		<?php
	}
}
