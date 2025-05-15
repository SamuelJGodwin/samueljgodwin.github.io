<?php
/**
 * Notice Customizer Control
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
 * Ignition_Customize_Notice class.
 *
 * @since 1.0.0
 */
class Ignition_Customize_Notice extends WP_Customize_Control {

	public $type = 'ignition-notice';

	const INFO    = 'info';
	const WARNING = 'warning';
	const ERROR   = 'error';

	/**
	 * Severity of the notice.
	 * Can be any one of info|notice|warning|error
	 *
	 * @var string
	 */
	public $severity = self::INFO;

	/**
	 * Renders control with PHP.
	 *
	 * @since 1.0.0
	 */
	public function render_content() {
		?>
		<div class="ignition-control-notice ignition-control-notice-<?php echo esc_attr( $this->severity ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<h3 class="ignition-control-notice-label"><?php echo esc_html( $this->label ); ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
				<p class="ignition-control-notice-description"><?php echo wp_kses_post( $this->description ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
