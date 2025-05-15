<?php
/**
 * Ignition_Widget_Buttons class
 *
 * @since 1.7.0
 */

/**
 * Class used to implement the Buttons widget.
 *
 * @since 1.7.0
 *
 * @see Ignition_Widget
 * @see WP_Widget
 */
class Ignition_Widget_Buttons extends Ignition_Widget {

	protected $defaults = array(
		'title' => '',
		'rows'  => array(),
	);

	public function __construct() {
		$widget_ops  = array( 'description' => esc_html__( 'A list of buttons.', 'ignition' ) );
		$control_ops = array();
		parent::__construct( 'ignition-buttons', esc_html__( 'Ignition - Buttons', 'ignition' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$id            = isset( $args['id'] ) ? $args['id'] : '';
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		foreach ( $instance['rows'] as $key => $row ) {
			$row['title']    = $this->multilingual_single_string_translate( 'button title', $row['title'] );
			$row['subtitle'] = $this->multilingual_single_string_translate( 'button subtitle', $row['subtitle'] );
			$row['url']      = $this->multilingual_single_string_translate( 'button url', $row['url'] );

			$instance['rows'][ $key ] = $row;
		}

		$rows = $instance['rows'];

		echo wp_kses( $before_widget, ignition_get_allowed_sidebar_wrappers() );

		if ( $title ) {
			echo wp_kses( $args['before_title'] . $title . $args['after_title'], ignition_get_allowed_sidebar_wrappers() );
		}

		if ( ! empty( $rows ) ) {
			?><div class="widget-button-list"><?php
				foreach ( $rows as $row ) {
					$item_classes = array( 'ignition-item-btn btn' );
					if ( empty( $row['title'] ) || empty( $row['subtitle'] ) ) {
						$item_classes[] = 'ignition-item-btn-sm';
					}

					?>
					<a href="<?php echo esc_url( $row['url'] ); ?>" class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
						<div class="ignition-item-btn-content">
							<?php if ( ! empty( $row['title'] ) ) : ?>
								<span class="ignition-item-btn-title"><?php echo esc_html( $row['title'] ); ?></span>
							<?php endif; ?>

							<?php if ( ! empty( $row['subtitle'] ) ) : ?>
								<span class="ignition-item-btn-subtitle"><?php echo esc_html( $row['subtitle'] ); ?></span>
							<?php endif; ?>
						</div>
					</a>
					<?php
				}
			?></div><?php
		}

		echo wp_kses( $after_widget, ignition_get_allowed_sidebar_wrappers() );

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['rows']  = $this->sanitize_instance_rows( $new_instance );

		foreach ( $instance['rows'] as $key => $row ) {
			$this->multilingual_single_string_register( 'button title', $row['title'] );
			$this->multilingual_single_string_register( 'button subtitle', $row['subtitle'] );
			$this->multilingual_single_string_register( 'button url', $row['url'] );
		}

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$rows = $instance['rows'];

		$row_title_name    = $this->get_field_name( 'row_title' ) . '[]';
		$row_subtitle_name = $this->get_field_name( 'row_subtitle' ) . '[]';
		$row_url_name      = $this->get_field_name( 'row_url' ) . '[]';


		$this->field_input_text( 'title', array(
			'title' => __( 'Title:', 'ignition' ),
			'value' => $instance['title'],
		) );
		?>

		<p><?php esc_html_e( 'Add as many items as you want by pressing the "Add Item" button. Remove any item by selecting "Remove me".', 'ignition' ); ?></p>
		<fieldset class="ignition-repeating-fields">
			<div class="inner">
				<?php
					if ( ! empty( $rows ) ) {
						$count = count( $rows );
						for ( $i = 0; $i < $count; $i ++ ) {
							?>
							<div class="post-field">
								<label class="post-field-item"><?php esc_html_e( 'Title:', 'ignition' ); ?>
									<input type="text" name="<?php echo esc_attr( $row_title_name ); ?>" value="<?php echo esc_attr( $rows[ $i ]['title'] ); ?>" class="widefat" />
								</label>

								<label class="post-field-item"><?php esc_html_e( 'Subtitle:', 'ignition' ); ?>
									<input type="text" name="<?php echo esc_attr( $row_subtitle_name ); ?>" value="<?php echo esc_attr( $rows[ $i ]['subtitle'] ); ?>" class="widefat" />
								</label>

								<label class="post-field-item"><?php esc_html_e( 'Link URL:', 'ignition' ); ?>
									<input type="text" name="<?php echo esc_attr( $row_url_name ); ?>" value="<?php echo esc_attr( $rows[ $i ]['url'] ); ?>" class="widefat" />
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
					<label class="post-field-item"><?php esc_html_e( 'Title:', 'ignition' ); ?>
						<input type="text" name="<?php echo esc_attr( $row_title_name ); ?>" value="" class="widefat" />
					</label>

					<label class="post-field-item"><?php esc_html_e( 'Subtitle:', 'ignition' ); ?>
						<input type="text" name="<?php echo esc_attr( $row_subtitle_name ); ?>" value="" class="widefat" />
					</label>

					<label class="post-field-item"><?php esc_html_e( 'Link URL:', 'ignition' ); ?>
						<input type="text" name="<?php echo esc_attr( $row_url_name ); ?>" value="" class="widefat" />
					</label>

					<p class="ignition-repeating-remove-action"><a href="#" class="button ignition-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'ignition' ); ?></a></p>
				</div>
			</div>
			<a href="#" class="ignition-repeating-add-field button"><i class="dashicons dashicons-plus-alt"></i><?php esc_html_e( 'Add Item', 'ignition' ); ?></a>
		</fieldset>
		<?php
	}

	protected function sanitize_instance_rows( $instance ) {
		if ( empty( $instance ) || ! is_array( $instance ) ) {
			return array();
		}

		$titles    = ! empty( $instance['row_title'] ) ? $instance['row_title'] : array();
		$subtitles = ! empty( $instance['row_subtitle'] ) ? $instance['row_subtitle'] : array();
		$urls      = ! empty( $instance['row_url'] ) ? $instance['row_url'] : array();

		$count = max(
			count( $titles ),
			count( $subtitles ),
			count( $urls )
		);

		$new_fields = array();

		$records_count = 0;

		for ( $i = 0; $i < $count; $i++ ) {
			if ( empty( $titles[ $i ] )
				&& empty( $subtitles[ $i ] )
				&& empty( $urls[ $i ] )
			) {
				continue;
			}

			$new_fields[ $records_count ]['title']    = sanitize_text_field( $titles[ $i ] );
			$new_fields[ $records_count ]['subtitle'] = sanitize_text_field( $subtitles[ $i ] );
			$new_fields[ $records_count ]['url']      = esc_url_raw( $urls[ $i ] );

			$records_count++;
		}
		return $new_fields;
	}

}
