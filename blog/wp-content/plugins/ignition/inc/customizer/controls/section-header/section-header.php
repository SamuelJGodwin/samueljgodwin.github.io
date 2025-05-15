<?php
/**
 * Section Header Customizer Control
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
 * Ignition_Customize_Section_Header class.
 *
 * @since 1.0.0
 */
class Ignition_Customize_Section_Header extends WP_Customize_Control {

	public $type = 'ignition-section-header';

	/**
	 * Renders control with PHP.
	 *
	 * @since 1.0.0
	 */
	public function render_content() {
		?>
		<div class="ignition-control-section-header">
			<h3 class="ignition-control-section-header-label"><?php echo esc_html( $this->label ); ?></h3>
			<?php
				if ( ! empty( $this->description ) ) {
					?><p class="ignition-control-section-header-description"><?php echo wp_kses_post( $this->description ); ?></p><?php
				}
			?>
		</div>
		<?php
	}
}
