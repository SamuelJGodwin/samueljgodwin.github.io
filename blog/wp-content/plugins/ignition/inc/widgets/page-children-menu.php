<?php
/**
 * Ignition_Widget_Page_Children_Menu class
 *
 * @since 1.7.0
 */

/**
 * Class used to implement the Page Children Menu widget.
 *
 * @since 1.7.0
 *
 * @see Ignition_Widget
 * @see WP_Widget
 */
class Ignition_Widget_Page_Children_Menu extends Ignition_Widget {

	protected $defaults = array(
		'title'        => '',
		'hierarchical' => 1,
		'show_root'    => 1,
	);

	public function __construct() {
		$widget_ops  = array( 'description' => esc_html__( "Displays the current page's hierarchy as a menu.", 'ignition' ) );
		$control_ops = array();
		parent::__construct( 'ignition-page-children-menu', esc_html__( 'Ignition - Page Children Menu', 'ignition' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$id            = isset( $args['id'] ) ? $args['id'] : '';
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		$title        = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$hierarchical = $instance['hierarchical'];
		$show_root    = $instance['show_root'];

		if ( ! is_post_type_hierarchical( get_post_type() ) ) {
			return;
		}

		$this_page    = get_post();
		$base_page_id = $this_page->ID;
		$ancestors    = get_post_ancestors( $this_page );

		if ( ! empty( $ancestors ) ) {
			$base_page_id = end( $ancestors );
			reset( $ancestors );
		}

		$pages = new WP_Query( array(
			'post_type'           => get_post_type(),
			'posts_per_page'      => - 1,
			'ignore_sticky_posts' => true,
		) );

		$hierarchy = get_page_hierarchy( $pages->posts, $base_page_id );

		if ( empty( $hierarchy ) ) {
			return;
		}

		echo wp_kses( $before_widget, ignition_get_allowed_sidebar_wrappers() );

		if ( $title ) {
			echo wp_kses( $args['before_title'] . $title . $args['after_title'], ignition_get_allowed_sidebar_wrappers() );
		}

		if ( $show_root ) {
			$page_ids = array_merge( array( $base_page_id ), array_keys( $hierarchy ) );
		} else {
			$page_ids = array_keys( $hierarchy );
		}

		?><ul class="menu"><?php

		$list = wp_list_pages( array(
			'include'  => implode( ',', $page_ids ),
			'title_li' => '',
			'echo'     => false,
		) );

		if ( ! $hierarchical ) {
			$list = preg_replace( '#<ul.*?>#', '', $list );
			$list = preg_replace( '#</ul>#', '', $list );
		}

		echo wp_kses( $list, ignition_get_allowed_tags() );

		?></ul><?php

		echo wp_kses( $after_widget, ignition_get_allowed_sidebar_wrappers() );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['hierarchical'] = isset( $new_instance['hierarchical'] );
		$instance['show_root']    = isset( $new_instance['show_root'] );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$this->field_input_text( 'title', array(
			'title' => __( 'Title:', 'ignition' ),
			'value' => $instance['title'],
		) );

		$this->field_checkbox( 'hierarchical', array(
			'title' => __( 'Show hierarchy.', 'ignition' ),
			'value' => $instance['hierarchical'],
		) );

		$this->field_checkbox( 'show_root', array(
			'title' => __( 'Show the first page of the hierarchy.', 'ignition' ),
			'value' => $instance['show_root'],
		) );

		?><p><?php esc_html_e( 'This widget only displays something when the page currently viewed is part of a hierarchy (either has children or is a child of another page itself). In all other cases, it displays nothing.', 'ignition' ); ?></p><?php
	}

}
