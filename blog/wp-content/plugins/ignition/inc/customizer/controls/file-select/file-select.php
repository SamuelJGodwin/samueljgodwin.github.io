<?php
/**
 * File Select Customizer Control
 *
 * @since 1.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Ignition_Customize_File_Select class.
 *
 * @since 1.9.0
 */
class Ignition_Customize_File_Select extends WP_Customize_Control {

	public $type = 'ignition-file-select';

	protected $file_type = 'image'; // Valid types: image, audio, video

	protected $placeholder = 'https://';

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.9.0
	 */
	public function enqueue() {
		$suffix = ignition_scripts_styles_suffix();

		wp_enqueue_script( $this->type . '-customize-control', untrailingslashit( IGNITION_DIR_URL ) . "/inc/customizer/controls/file-select/customizer{$suffix}.js", array( 'jquery' ), ignition_asset_version(), true );
		wp_localize_script( $this->type . '-customize-control', 'customizerFileSelectStrings', array(
			'selectFile' => esc_html__( 'Select a file', 'ignition' ),
		) );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 *
	 * @since 1.9.0
	 */
	public function to_json() {
		parent::to_json();

		$this->json['file_type'] = $this->file_type;
	}

	/**
	 * Renders control with PHP.
	 *
	 * @since 1.9.0
	 */
	protected function render_content() {
		$input_id = '_customize-input-' . $this->id;

		$can_upload = current_user_can( 'upload_files' );

		?><div class="ignition-control-file-select-wrap"><?php

			if ( ! empty( $this->label ) ) :
				?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</label>
				<?php
			endif;

			if ( ! empty( $this->description ) ) :
				?><span class="description customize-control-description"><?php echo $this->description; ?></span><?php
			endif;

			?>
			<div class="ignition-control-file-select">

				<input
					type="url"
					id="<?php echo esc_attr( $input_id ); ?>"
					class="ignition-control-file-select-input"
					value="<?php echo esc_url( $this->value() ); ?>"
					placeholder="<?php echo esc_attr( $this->placeholder ); ?>"
					<?php echo $this->get_link(); ?>
				>

				<div class="actions">
					<?php if ( $can_upload ) : ?>
						<button
							type="button"
							class="button upload-button control-focus"
							data-type="<?php echo esc_attr( $this->file_type ); ?>"
						>
							<?php esc_html_e( 'Select file', 'ignition' ); ?>
						</button>
					<?php endif; ?>
				</div>
			</div>

		</div>
		<?php
	}
}
