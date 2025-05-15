<?php
/**
 * Ignition_Widget class
 *
 * @since 1.0.0
 */

/**
 * Base class for Ignition widgets.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Ignition_Widget extends WP_Widget {

	/**
	 * @since 1.0.0
	 */
	protected function field_input_text( $fieldname, $params = array() ) {
		$params = (array) $params;

		$defaults = array(
			'title'       => '',
			'value'       => '',
			'before'      => '<p>',
			'after'       => '</p>',
			'input_class' => 'widefat',
		);

		$params = wp_parse_args( $params, $defaults );

		echo wp_kses_post( $params['before'] );
		?>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<?php echo wp_kses_post( $params['title'] ); ?>
			</label>
			<input
				type="text"
				id="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( $fieldname ) ); ?>"
				value="<?php echo esc_attr( $params['value'] ); ?>"
				class="<?php echo esc_attr( $params['input_class'] ); ?>"
			/>
		<?php
		echo wp_kses_post( $params['after'] );
	}

	/**
	 * @since 1.0.0
	 */
	protected function field_input_int( $fieldname, $params = array() ) {
		$params = (array) $params;

		$defaults = array(
			'title'       => '',
			'value'       => '',
			'before'      => '<p>',
			'after'       => '</p>',
			'input_class' => 'widefat',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 1000,
				'step' => 1,
			),
		);

		$params = wp_parse_args( $params, $defaults );

		echo wp_kses_post( $params['before'] );
		?>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<?php echo wp_kses_post( $params['title'] ); ?>
			</label>
			<input
				type="number"
				id="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( $fieldname ) ); ?>"
				value="<?php echo esc_attr( $params['value'] ); ?>"
				class="<?php echo esc_attr( $params['input_class'] ); ?>"
				min="<?php echo esc_attr( $params['input_attrs']['min'] ); ?>"
				max="<?php echo esc_attr( $params['input_attrs']['max'] ); ?>"
				step="<?php echo esc_attr( $params['input_attrs']['step'] ); ?>"
			/>
		<?php
		echo wp_kses_post( $params['after'] );
	}

	/**
	 * @since 1.7.0
	 */
	protected function field_input_url( $fieldname, $params = array() ) {
		$params = (array) $params;

		$defaults = array(
			'title'       => '',
			'value'       => '',
			'before'      => '<p>',
			'after'       => '</p>',
			'input_class' => 'widefat',
		);

		$params = wp_parse_args( $params, $defaults );

		echo wp_kses_post( $params['before'] );
		?>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<?php echo wp_kses_post( $params['title'] ); ?>
			</label>
			<input
				type="text"
				id="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( $fieldname ) ); ?>"
				value="<?php echo esc_url( $params['value'] ); ?>"
				class="<?php echo esc_attr( $params['input_class'] ); ?>"
			/>
		<?php
		echo wp_kses_post( $params['after'] );
	}

	/**
	 * @since 1.7.0
	 */
	protected function field_textarea( $fieldname, $params = array() ) {
		$params = (array) $params;

		$defaults = array(
			'title'          => '',
			'value'          => '',
			'before'         => '<p>',
			'after'          => '</p>',
			'textarea_class' => 'widefat',
		);

		$params = wp_parse_args( $params, $defaults );

		echo wp_kses_post( $params['before'] );
		?>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<?php echo wp_kses_post( $params['title'] ); ?>
			</label>
			<textarea
				id="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( $fieldname ) ); ?>"
				class="<?php echo esc_attr( $params['textarea_class'] ); ?>"
			><?php echo esc_textarea( $params['value'] ); ?></textarea>
		<?php
		echo wp_kses_post( $params['after'] );
	}

	/**
	 * @since 1.0.0
	 */
	protected function field_dropdown( $fieldname, $params = array() ) {
		$params = (array) $params;

		$defaults = array(
			'title'        => '',
			'value'        => '',
			'choices'      => array(),
			'before'       => '<p>',
			'after'        => '</p>',
			'select_class' => 'widefat',
		);

		$params = wp_parse_args( $params, $defaults );

		echo wp_kses_post( $params['before'] );
		?>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<?php echo wp_kses_post( $params['title'] ); ?>
			</label>
			<select
				id="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( $fieldname ) ); ?>"
				class="<?php echo esc_attr( $params['select_class'] ); ?>"
			>
				<?php foreach ( $params['choices'] as $opt_value => $opt_title ) : ?>
					<option value="<?php echo esc_attr( $opt_value ); ?>" <?php selected( $params['value'], $opt_value ); ?>>
						<?php echo wp_kses( $opt_title, 'strip' ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		<?php
		echo wp_kses_post( $params['after'] );
	}

	/**
	 * @since 1.7.0
	 */
	protected function field_checkbox( $fieldname, $params = array() ) {
		$params = (array) $params;

		$defaults = array(
			'title'         => '',
			'value'         => '',
			'checked_value' => '1',
			'before'        => '<p>',
			'after'         => '</p>',
		);

		$params = wp_parse_args( $params, $defaults );

		echo wp_kses_post( $params['before'] );
		?>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<input
					type="checkbox"
					name="<?php echo esc_attr( $this->get_field_name( $fieldname ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>"
					value="1"
					<?php checked( $params['checked_value'], $params['value'] ); ?>
				/>
				<?php echo wp_kses_post( $params['title'] ); ?>
			</label>
		<?php
		echo wp_kses_post( $params['after'] );
	}

	/**
	 * Registers a single string for translation.
	 *
	 * @since 1.0.0
	 *
	 * @param string $label Label for the String Translation screen. Also used to generate a unique name. DO NOT TRANSLATE THIS STRING, as it will create duplicate entries for each language.
	 * @param string $value The string to be translated.
	 */
	protected function multilingual_single_string_register( $label, $value ) {
		// Creates a unique name for the registered string. The hash at the end, is automatically hidden by WPML String Translation.
		$name = implode( ' - ', array(
			$label,
			md5( $value ),
		) );

		do_action( 'wpml_register_single_string', 'Widgets', $name, $value );
	}

	/**
	 * @since 1.0.0
	 *
	 * @param string $label Label for the String Translation screen. Also used to generate a unique name. DO NOT TRANSLATE THIS STRING, as it will create duplicate entries for each language.
	 * @param string $value The string to be translated.
	 *
	 * @return string
	 */
	protected function multilingual_single_string_translate( $label, $value ) {
		// Creates a unique name for the registered string. The hash at the end, is automatically hidden by WPML String Translation.
		$name = implode( ' - ', array(
			$label,
			md5( $value ),
		) );

		$translation = apply_filters( 'wpml_translate_single_string', $value, 'Widgets', $name );

		return $translation;
	}


	/**
	 * @since 1.0.0
	 */
	protected function get_available_post_types( $return = 'objects' ) {
		$return = in_array( $return, array( 'objects', 'names' ), true ) ? $return : 'objects';

		$post_types = get_post_types( array(
			'public' => true,
		), $return );

		/**
		 * Filters the post types that should be excluded from the dropdown options.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $excluded_cpts Array of post type names.
		 *
		 * @hooked ignition_module_gsection_add_cpt_to_array - 10
		 */
		$excluded_cpts = apply_filters( 'ignition_widget_post_types_dropdown_excluded', array(
			'elementor_library',
			'attachment',
			'product',
		), get_class( $this ) );

		foreach ( $excluded_cpts as $excluded_cpt ) {
			unset( $post_types[ $excluded_cpt ] );
		}

		/**
		 * Filters the list of post types that will be included in the dropdown options.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $post_types    Array of post type names.
		 * @param string   $class         The widget's class name.
		 * @param string[] $excluded_cpts Array of excluded post type names.
		 * @param string   $return        Whether $post_types contains post type names or objects. Possible values: 'names', 'objects'.
		 */
		$post_types = apply_filters( 'ignition_widget_post_types_dropdown', $post_types, get_class( $this ), $excluded_cpts, $return );

		return $post_types;
	}
}
