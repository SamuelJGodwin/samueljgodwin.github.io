<?php
/**
 * Ignition_Widget_Latest_Post_Type class
 *
 * @since 1.0.0
 */

/**
 * Class used to implement the Latest Post Type widget.
 *
 * @since 1.0.0
 *
 * @see Ignition_Widget
 * @see WP_Widget
 */
class Ignition_Widget_Latest_Post_Type extends Ignition_Widget {

	protected $defaults = array(
		'title'     => '',
		'post_type' => 'post',
		'count'     => 3,
	);

	public function __construct() {
		$widget_ops  = array( 'description' => esc_html__( 'Displays the latest items from a selected post type.', 'ignition' ) );
		$control_ops = array();
		parent::__construct( 'ignition-latest-post-type', esc_html__( 'Ignition - Latest Post Type', 'ignition' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$id            = isset( $args['id'] ) ? $args['id'] : '';
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$post_type = $instance['post_type'];
		$count     = $instance['count'];

		if ( empty( $post_type ) || empty( $count ) ) {
			return;
		}

		$q_args = array(
			'post_type'           => $post_type,
			'posts_per_page'      => intval( $count ),
			'ignore_sticky_posts' => true,
		);

		$tax_query_args = array();

		if ( 'product' === $post_type && taxonomy_exists( 'product_visibility' ) ) {
			$tax_query_args[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'slug',
				'terms'    => array( 'exclude-from-catalog' ),
				'operator' => 'NOT IN',
			);
		}

		if ( count( $tax_query_args ) > 1 ) {
			$tax_query_args = array_merge( array(
				'relation' => 'AND',
			), $tax_query_args );
		}

		if ( count( $tax_query_args ) >= 1 ) {
			$q_args = array_merge( $q_args, array(
				'tax_query' => $tax_query_args,
			) );
		}

		$q = new WP_Query( $q_args );

		echo wp_kses( $before_widget, ignition_get_allowed_sidebar_wrappers() );

		if ( $title ) {
			echo wp_kses( $args['before_title'] . $title . $args['after_title'], ignition_get_allowed_sidebar_wrappers() );
		}

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				ignition_get_template_part( 'template-parts/widgets/sidebar-item', get_post_type() );

			}
			wp_reset_postdata();
		}

		echo wp_kses( $after_widget, ignition_get_allowed_sidebar_wrappers() );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['post_type'] = in_array( $new_instance['post_type'], $this->get_available_post_types( 'names' ), true ) ? $new_instance['post_type'] : $this->defaults['post_type'];
		$instance['count']     = absint( $new_instance['count'] );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$this->field_input_text( 'title', array(
			'title' => __( 'Title:', 'ignition' ),
			'value' => $instance['title'],
		) );

		$post_types = $this->get_available_post_types();
		$choices    = array();
		foreach ( $post_types as $key => $pt ) {
			$choices[ $key ] = $pt->labels->name;
		}

		$this->field_dropdown( 'post_type', array(
			'title'        => __( 'Post type:', 'ignition' ),
			'value'        => $instance['post_type'],
			'choices'      => $choices,
			'select_class' => 'widefat ignition-post-type-select',
		) );

		$this->field_input_int( 'count', array(
			'title'       => __( 'Number of posts to show:', 'ignition' ),
			'value'       => $instance['count'],
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 100,
				'step' => 1,
			),
		) );
	}
}
