<?php
/**
 * Ignition_Widget_Callout class
 *
 * @since 1.7.0
 */

/**
 * Class used to implement the Callout widget.
 *
 * @since 1.7.0
 *
 * @see Ignition_Widget
 * @see WP_Widget
 */
class Ignition_Widget_Callout extends Ignition_Widget {

	protected $defaults = array(
		'title'         => '',
		'content_title' => '',
		'text'          => '',
		'button_text'   => '',
		'button_url'    => '',
	);

	public function __construct() {
		$widget_ops  = array( 'description' => esc_html__( 'Callout widget with custom call to action button.', 'ignition' ) );
		$control_ops = array();
		parent::__construct( 'ignition-callout', esc_html__( 'Ignition - Callout', 'ignition' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$id            = isset( $args['id'] ) ? $args['id'] : '';
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$content_title = $this->multilingual_single_string_translate( 'content title', $instance['content_title'] );
		$text          = $this->multilingual_single_string_translate( 'text', $instance['text'] );
		$button_text   = $this->multilingual_single_string_translate( 'button text', $instance['button_text'] );
		$button_url    = $this->multilingual_single_string_translate( 'button url', $instance['button_url'] );

		echo wp_kses( $before_widget, ignition_get_allowed_sidebar_wrappers() );

		if ( $title ) {
			echo wp_kses( $args['before_title'] . $title . $args['after_title'], ignition_get_allowed_sidebar_wrappers() );
		}

		?><div class="ignition-box-callout"><?php

		if ( $content_title ) {
			?><h4 class="ignition-box-callout-title"><?php echo esc_html( $content_title ); ?></h4><?php
		}

		if ( $text ) {
			echo wp_kses( wpautop( $text ), ignition_get_allowed_tags() );
		}

		if ( ! empty( $button_text ) && ! empty( $button_url ) ) {
			?><a href="<?php echo esc_url( $button_url ); ?>" class="btn"><?php echo wp_kses( $button_text, ignition_get_allowed_tags() ); ?></a><?php
		}

		?></div><?php

		echo wp_kses( $after_widget, ignition_get_allowed_sidebar_wrappers() );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['content_title'] = sanitize_text_field( $new_instance['content_title'] );
		$instance['text']          = wp_kses( $new_instance['text'], ignition_get_allowed_tags() );
		$instance['button_text']   = wp_kses( $new_instance['button_text'], ignition_get_allowed_tags() );
		$instance['button_url']    = esc_url_raw( $new_instance['button_url'] );

		$this->multilingual_single_string_register( 'content title', $instance['content_title'] );
		$this->multilingual_single_string_register( 'text', $instance['text'] );
		$this->multilingual_single_string_register( 'button text', $instance['button_text'] );
		$this->multilingual_single_string_register( 'button url', $instance['button_url'] );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$this->field_input_text( 'title', array(
			'title' => __( 'Widget Title:', 'ignition' ),
			'value' => $instance['title'],
		) );

		$this->field_input_text( 'content_title', array(
			'title' => __( 'Title:', 'ignition' ),
			'value' => $instance['content_title'],
		) );
		$this->field_textarea( 'text', array(
			'title' => __( 'Text:', 'ignition' ),
			'value' => $instance['text'],
		) );

		$this->field_input_text( 'button_text', array(
			'title' => __( 'Button Text:', 'ignition' ),
			'value' => $instance['button_text'],
		) );
		$this->field_input_url( 'button_url', array(
			'title' => __( 'Button URL:', 'ignition' ),
			'value' => $instance['button_url'],
		) );
	}

}
