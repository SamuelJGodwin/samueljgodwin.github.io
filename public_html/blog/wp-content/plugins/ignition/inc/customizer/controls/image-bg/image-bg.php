<?php
/**
 * Image Background Customizer Control
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
 * Ignition_Customize_Image_BG_Control class.
 *
 * @since 1.0.0
 */
class Ignition_Customize_Image_BG_Control extends WP_Customize_Control {
	/**
	 * The type of control.
	 *
	 * @var string
	 */
	public $type = 'ignition-image-bg';

	public $responsive = true;

	/**
	 * Display additional options (positioning, size, repeat, attachment).
	 *
	 * @var bool
	 */
	public $additional_properties = true;

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		$suffix = ignition_scripts_styles_suffix();

		wp_enqueue_script( $this->type . '-customize-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/image-bg/customizer{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
		wp_localize_script( $this->type . '-customize-control', 'customizerImageBgStrings', array(
			'selectImage' => esc_html__( 'Select an Image', 'ignition' ),
		) );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['id']                = $this->id;
		$this->json['canUpload']         = current_user_can( 'upload_files' );
		$this->json['repeatChoices']     = ignition_get_image_repeat_choices();
		$this->json['positionChoices']   = ignition_get_image_position_choices();
		$this->json['attachmentChoices'] = ignition_get_image_attachment_choices();
		$this->json['sizeChoices']       = ignition_get_image_size_choices();

		$value = $this->value();

		$this->json['values'] = $value;

		if ( ! empty( $value['image_id'] ) ) {
			$this->json['attachment'] = wp_prepare_attachment_for_js( $value['image_id'] );
		}

		$this->json['additional_properties'] = $this->additional_properties;
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
		<#
		var selectButtonId = _.uniqueId( 'ignition-image-bg-customize-control-button-' );
		var attachmentSrc = !data.attachment
			? ''
			: ((data.attachment.sizes.medium && data.attachment.sizes.medium.url) || data.attachment.sizes.full.url || data.attachment.icon || '');
		#>
		<div class="ignition-image-bg-control-wrap">
			<# if ( data.label ) { #>
				<label class="customize-control-title" for="{{ selectButtonId }}">{{ data.label }}</label>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{ data.description }}</span>
			<# } #>

			<div class="ignition-image-bg-control-group">
				<div class="ignition-image-bg-attachment-view ignition-image-bg-control-attachment-preview">
					<div class="ignition-image-bg-control-thumbnail">
						<img class="ignition-image-bg-control-attachment-thumb" alt="" src="">
					</div>

					<div class="actions">
						<# if ( data.canUpload ) { #>
						<button type="button" class="button remove-button"><?php esc_html_e( 'Remove', 'ignition' ); ?></button>
						<button type="button" class="button upload-button control-focus" id="{{ selectButtonId }}"><?php esc_html_e( 'Change image', 'ignition' ); ?></button>
						<# } #>
					</div>
				</div>

				<div class="ignition-image-bg-attachment-view ignition-image-bg-control-attachment-placeholder">
					<div class="ignition-image-bg-control-placeholder">
						<?php esc_html_e( 'No image selected', 'ignition' ); ?>
					</div>

					<div class="actions">
						<# if ( data.canUpload ) { #>
						<button type="button" class="button upload-button" id="{{ selectButtonId }}"><?php esc_html_e( 'Select image', 'ignition' ); ?></button>
						<# } #>
					</div>
				</div>
			</div>

			<div class="ignition-image-bg-control-group-grid">
				<# if( data.additional_properties ) { #>
					<div class="ignition-image-bg-control-group">
						<label for="{{data.id}}-image-bg-repeat">
							<?php esc_html_e( 'Image repeat', 'ignition' ); ?>
						</label>
						<select
							id="{{data.id}}-image-bg-repeat"
							data-property="repeat"
						>
							<# _.each( data.repeatChoices, function( label, val ) { #>
								<option value="{{val}}" {{{ (data.values.repeat === val) ? 'selected' : '' }}} >{{{label}}}</option>
							<# } ); #>
						</select>
					</div>

					<div class="ignition-image-bg-control-group">
						<label for="{{data.id}}-image-bg-position">
							<?php esc_html_e( 'Image position', 'ignition' ); ?>
						</label>
						<select
							id="{{data.id}}-image-bg-position"
							data-property="position"
						>
							<# _.each( data.positionChoices, function( label, val ) { #>
								<option value="{{val}}" {{{ (data.values.position === val) ? 'selected' : '' }}} >{{{label}}}</option>
							<# } ); #>
						</select>
					</div>

					<div class="ignition-image-bg-control-group">
						<label for="{{data.id}}-image-bg-attachment">
							<?php esc_html_e( 'Image attachment', 'ignition' ); ?>
						</label>
						<select
							id="{{data.id}}-image-bg-attachment"
							data-property="attachment"
						>
							<# _.each( data.attachmentChoices, function( label, val ) { #>
								<option value="{{val}}" {{{ (data.values.attachment === val) ? 'selected' : '' }}} >{{{label}}}</option>
							<# } ); #>
						</select>
					</div>

					<div class="ignition-image-bg-control-group">
						<label for="{{data.id}}-image-bg-size">
							<?php esc_html_e( 'Image size', 'ignition' ); ?>
						</label>
						<select
							id="{{data.id}}-image-bg-size"
							data-property="size"
						>
							<# _.each( data.sizeChoices, function( label, val ) { #>
								<option value="{{val}}" {{{ (data.values.size === val) ? 'selected' : '' }}} >{{{label}}}</option>
							<# } ); #>
						</select>
					</div>
				<# } #>
			</div>
		</div>
		<?php
	}
}
