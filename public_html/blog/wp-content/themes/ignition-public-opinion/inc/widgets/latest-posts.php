<?php
/**
 * Ignition_Public_Opinion_Widget_Latest_Posts class
 *
 * @since 1.0.0
 */

/**
 * Class used to implement the Latest Posts widget.
 *
 * @since 1.0.0
 *
 * @see Ignition_Widget
 * @see WP_Widget
 */
class Ignition_Public_Opinion_Widget_Latest_Posts extends Ignition_Widget {

	protected $defaults = array(
		'title'         => '',
		'count'         => 3,
		'category_slug' => '',
		'tag_slug'      => '',
	);

	public function __construct() {
		$widget_ops  = array( 'description' => esc_html__( 'Displays latest posts, optionally from a selected category and tag.', 'ignition-public-opinion' ) );
		$control_ops = array();
		parent::__construct( 'ignition-public-opinion-latest-posts', esc_html__( 'Theme - Latest Posts', 'ignition-public-opinion' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$id            = isset( $args['id'] ) ? $args['id'] : '';
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$count    = $instance['count'];
		$cat_slug = $instance['category_slug'];
		$tag_slug = $instance['tag_slug'];

		if ( empty( $count ) ) {
			return;
		}

		$q_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => intval( $count ),
			'ignore_sticky_posts' => true,
		);

		$tax_query_args = array();

		if ( $cat_slug ) {
			$tax_query_args[] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( $cat_slug ),
				'operator' => 'IN',
			);
		}

		if ( $tag_slug ) {
			$tax_query_args[] = array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => array( $tag_slug ),
				'operator' => 'IN',
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

		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['count']         = absint( $new_instance['count'] );
		$instance['category_slug'] = sanitize_title( $new_instance['category_slug'] );
		$instance['tag_slug']      = sanitize_title( $new_instance['tag_slug'] );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$this->field_input_text( 'title', array(
			'title' => __( 'Title:', 'ignition-public-opinion' ),
			'value' => $instance['title'],
		) );

		$post_types = $this->get_available_post_types();
		$choices    = array();
		foreach ( $post_types as $key => $pt ) {
			$choices[ $key ] = $pt->labels->name;
		}

		$this->field_input_int( 'count', array(
			'title'       => __( 'Number of posts to show:', 'ignition-public-opinion' ),
			'value'       => $instance['count'],
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 100,
				'step' => 1,
			),
		) );

		$fieldname = 'category_slug';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<?php esc_html_e( 'Category (optional):', 'ignition-public-opinion' ); ?>
			</label>
			<?php
			wp_dropdown_categories( array(
				'show_option_none'  => ' ',
				'orderby'           => 'name',
				'show_count'        => 1,
				'hide_empty'        => 0,
				'selected'          => $instance[ $fieldname ],
				'hierarchical'      => 1,
				'name'              => $this->get_field_name( $fieldname ),
				'id'                => $this->get_field_id( $fieldname ),
				'class'             => 'widefat',
				'taxonomy'          => 'category',
				'option_none_value' => '',
				'value_field'       => 'slug',
			) );
			?>
		</p>
		<?php

		$fieldname = 'tag_slug';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $fieldname ) ); ?>">
				<?php esc_html_e( 'Tag (optional):', 'ignition-public-opinion' ); ?>
			</label>
			<?php
			wp_dropdown_categories( array(
				'show_option_none'  => ' ',
				'orderby'           => 'name',
				'show_count'        => 1,
				'hide_empty'        => 0,
				'selected'          => $instance[ $fieldname ],
				'hierarchical'      => 0,
				'name'              => $this->get_field_name( $fieldname ),
				'id'                => $this->get_field_id( $fieldname ),
				'class'             => 'widefat',
				'taxonomy'          => 'post_tag',
				'option_none_value' => '',
				'value_field'       => 'slug',
			) );
			?>
		</p>
		<?php
	}
}
