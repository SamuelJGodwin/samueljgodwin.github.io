<?php
/**
 * Global Section shortcode definitions
 *
 * @since 1.2.0
 */

add_shortcode( 'global-section', 'ignition_module_gsection_shortcode_global_section' );
/**
 * Builds the Global Section shortcode output.
 *
 * @since 1.2.0
 *
 * @param array       $params
 * @param null|string $content
 * @param string      $shortcode
 *
 * @return string
 */
function ignition_module_gsection_shortcode_global_section( $params, $content, $shortcode ) {
	$params = shortcode_atts( array(
		'id'   => '',
		'slug' => '',
	), $params, $shortcode );

	$id      = intval( $params['id'] );
	$slug    = $params['slug'];
	$post_id = false;

	if ( empty( $id ) && empty( $slug ) ) {
		return '';
	} elseif ( ! empty( $id ) && $id > 0 ) {
		if ( 'publish' === get_post_status( $id ) ) {
			$post_id = $id;
		}
	} elseif ( ! empty( $slug ) ) {
		$slug = sanitize_title_with_dashes( $slug, '', 'save' );

		$p = get_page_by_path( $slug, OBJECT, 'ignition-gsection' );
		if ( is_object( $p ) && 'WP_Post' === get_class( $p ) ) {
			if ( 'publish' === get_post_status( $id ) ) {
				$post_id = $p->ID;
			}
		}
	}

	if ( empty( $post_id ) && current_user_can( 'publish_posts' ) ) {
		return sprintf( '<div class="ignition-global-section-error">%s</div>',
			/**
			 * Filters the error message displayed to admin users when a non public or published Global Section is being displayed.
			 *
			 * @since 1.2.0
			 *
			 * @param string $error_message
			 */
			apply_filters( 'ignition_gsection_shortcode_global_section_error_msg_non_public', __( 'Cannot show non-public or non-published Global Sections.', 'ignition' ) )
		);
	}

	ob_start();

	$q = new WP_Query( array(
		'post_type'      => 'ignition-gsection',
		'p'              => $post_id,
		'posts_per_page' => 1,
	) );

	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();

			the_content();
		}

		wp_reset_postdata();
	}

	$output = ob_get_clean();

	return $output;
}
