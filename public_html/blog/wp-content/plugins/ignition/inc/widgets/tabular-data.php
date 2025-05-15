<?php
/**
 * Ignition_Widget_Tabular_Data class
 *
 * @since 1.7.0
 */

/**
 * Class used to implement the Tabular Data widget.
 *
 * @since 1.7.0
 *
 * @see Ignition_Widget
 * @see WP_Widget
 */
class Ignition_Widget_Tabular_Data extends Ignition_Widget {

	protected $defaults = array(
		'title'       => '',
		'text'        => '',
		'data_fields' => array(),
	);

	public function __construct() {
		$widget_ops  = array( 'description' => esc_html__( 'Display tabular data e.g. timetables, pricelists etc.', 'ignition' ) );
		$control_ops = array();
		parent::__construct( 'ignition-tabular-data', esc_html__( 'Ignition - Tabular Data', 'ignition' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$id            = isset( $args['id'] ) ? $args['id'] : '';
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$text = $this->multilingual_single_string_translate( 'text', $instance['text'] );

		foreach ( $instance['data_fields'] as $key => $row ) {
			$row['label'] = $this->multilingual_single_string_translate( 'tabular field label', $row['label'] );
			$row['data']  = $this->multilingual_single_string_translate( 'tabular field data', $row['data'] );

			$instance['data_fields'][ $key ] = $row;
		}

		$fields = $instance['data_fields'];

		echo wp_kses( $before_widget, ignition_get_allowed_sidebar_wrappers() );

		if ( $title ) {
			echo wp_kses( $args['before_title'] . $title . $args['after_title'], ignition_get_allowed_sidebar_wrappers() );
		}

		if ( $text ) {
			?><p class="ignition-tabular-data-widget-intro"><?php echo do_shortcode( wp_kses_post( $text ) ); ?></p><?php
		}

		if ( $fields ) {
			?><table class="ignition-tabular-data-widget-table"><tbody><?php

			foreach ( $fields as $field ) {
				$label = $field['label'] ? $field['label'] : '&nbsp;';
				$data  = $field['data'] ? $field['data'] : '&nbsp;';
				?>
				<tr>
					<th><?php echo esc_html( $label ); ?></th>
					<td><?php echo esc_html( $data ); ?></td>
				</tr>
				<?php
			}

			?></tbody></table><?php
		}

		echo wp_kses( $after_widget, ignition_get_allowed_sidebar_wrappers() );

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']       = sanitize_text_field( $new_instance['title'] );
		$instance['text']        = wp_kses_post( $new_instance['text'] );
		$instance['data_fields'] = $this->sanitize_data_fields( $new_instance );

		$this->multilingual_single_string_register( 'text', $instance['text'] );
		foreach ( $instance['data_fields'] as $key => $row ) {
			$this->multilingual_single_string_register( 'tabular field label', $row['label'] );
			$this->multilingual_single_string_register( 'tabular field data', $row['data'] );
		}

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$fields = $instance['data_fields'];

		$field_label_name = $this->get_field_name( 'tabular_data_field_label' ) . '[]';
		$field_data_name  = $this->get_field_name( 'tabular_data_field_data' ) . '[]';


		$this->field_input_text( 'title', array(
			'title' => __( 'Title:', 'ignition' ),
			'value' => $instance['title'],
		) );

		$this->field_textarea( 'text', array(
			'title' => __( 'Text (accepts HTML):', 'ignition' ),
			'value' => $instance['text'],
		) );
		?>

		<p><?php esc_html_e( 'Add as many items as you want by pressing the "Add Item" button. Remove any item by selecting "Remove me".', 'ignition' ); ?></p>
		<fieldset class="ignition-repeating-fields">
			<div class="inner">
				<?php
					if ( ! empty( $fields ) ) {
						$count = count( $fields );
						for ( $i = 0; $i < $count; $i++ ) {
							?>
							<div class="post-field">
								<label class="post-field-item"><?php esc_html_e( 'Label:', 'ignition' ); ?>
									<input type="text" name="<?php echo esc_attr( $field_label_name ); ?>" value="<?php echo esc_attr( $fields[ $i ]['label'] ); ?>" class="widefat" />
								</label>

								<label class="post-field-item"><?php esc_html_e( 'Data:', 'ignition' ); ?>
									<input type="text" name="<?php echo esc_attr( $field_data_name ); ?>" value="<?php echo esc_attr( $fields[ $i ]['data'] ); ?>" class="widefat" />
								</label>

								<p class="ignition-repeating-remove-action"><a href="#" class="button ignition-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'ignition' ); ?></a></p>
							</div>
							<?php
						}
					}
				?>
				<?php
				//
				// Add an empty and hidden set for jQuery
				//
				?>
				<div class="post-field field-prototype" style="display: none;">
					<label class="post-field-item"><?php esc_html_e( 'Label:', 'ignition' ); ?>
						<input type="text" name="<?php echo esc_attr( $field_label_name ); ?>" value="" class="widefat" disabled />
					</label>

					<label class="post-field-item"><?php esc_html_e( 'Data:', 'ignition' ); ?>
						<input type="text" name="<?php echo esc_attr( $field_data_name ); ?>" value="" class="widefat" disabled />
					</label>

					<p class="ignition-repeating-remove-action"><a href="#" class="button ignition-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'ignition' ); ?></a></p>
				</div>
			</div>
			<a href="#" class="ignition-repeating-add-field button"><i class="dashicons dashicons-plus-alt"></i><?php esc_html_e( 'Add Item', 'ignition' ); ?></a>
		</fieldset>

		<?php
	}

	protected function sanitize_data_fields( $instance ) {
		if ( empty( $instance ) || ! is_array( $instance ) ) {
			return array();
		}

		$labels = ! empty( $instance['tabular_data_field_label'] ) ? $instance['tabular_data_field_label'] : array();
		$data   = ! empty( $instance['tabular_data_field_data'] ) ? $instance['tabular_data_field_data'] : array();

		$count = max( count( $labels ), count( $data ) );

		$new_fields = array();

		$records_count = 0;

		for ( $i = 0; $i < $count; $i++ ) {
			if ( empty( $labels[ $i ] ) && empty( $data[ $i ] ) ) {
				continue;
			}

			$new_fields[ $records_count ]['label'] = sanitize_text_field( $labels[ $i ] );
			$new_fields[ $records_count ]['data']  = sanitize_text_field( $data[ $i ] );

			$records_count++;
		}
		return $new_fields;
	}
}
