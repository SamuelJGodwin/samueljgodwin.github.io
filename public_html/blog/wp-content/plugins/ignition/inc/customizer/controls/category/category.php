<?php
/**
 * Category Customizer Control
 *
 * @since 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Ignition_Customize_Category class.
 *
 * @since 1.3.0
 */
class Ignition_Customize_Category extends WP_Customize_Control {

	public $type = 'ignition-category';

	protected $dropdown_args = false;

	/**
	 * Renders control with PHP.
	 *
	 * @since 1.0.0
	 */
	protected function render_content() {
		$input_id = '_customize-input-' . $this->id;

		?><div class="ignition-control-category"><?php

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

			$dropdown_args = wp_parse_args( $this->dropdown_args, array(
				'taxonomy'          => 'category',
				'show_option_none'  => ' ',
				'selected'          => $this->value(),
				'show_option_all'   => '',
				'orderby'           => 'id',
				'order'             => 'ASC',
				'show_count'        => 1,
				'hide_empty'        => 1,
				'child_of'          => 0,
				'exclude'           => '',
				'hierarchical'      => 1,
				'depth'             => 0,
				'tab_index'         => 0,
				'hide_if_empty'     => false,
				'option_none_value' => '',
				'value_field'       => 'slug',
			) );

			$dropdown_args['id']   = $input_id;
			$dropdown_args['echo'] = false;

			$dropdown = wp_dropdown_categories( $dropdown_args );
			$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );
			echo $dropdown;

		?></div><?php
	}
}
