<?php
/**
 * Block: Taxonomy Terms
 *
 * @since 2.0.0
 */

add_action( 'init', 'ignition_block_taxonomy_terms_init' );
/**
 * Registers the Taxonomy Terms block.
 *
 * @since 2.0.0
 */
function ignition_block_taxonomy_terms_init() {
	register_block_type( 'ignition/taxonomy-terms', array(
		'attributes'      => array(
			'uniqueId'          => array(
				'type' => 'string',
			),
			'className'         => array(
				'type' => 'string',
			),
			'taxonomySlug'      => array(
				'type'    => 'string',
				'default' => '',
			),
			'includedTermSlugs' => array(
				'type'    => 'array',
				'items'   => array(
					'type' => 'string',
				),
				'default' => array(),
			),
			'columns'           => array(
				'type'    => 'number',
				'default' => 3,
			),
		),
		'render_callback' => 'ignition_block_taxonomy_terms_render_callback',
	) );
}

/**
 * Returns the Taxonomy Terms block's default values.
 *
 * @since 2.0.0
 *
 * @return array
 */
function ignition_block_taxonomy_terms_defaults() {
	return array(
		'uniqueId'          => '',
		'className'         => '',
		'taxonomySlug'      => '',
		'includedTermSlugs' => array(),
		'columns'           => 3,
	);
}

/**
 * Renders the Taxonomy Terms block's output.
 *
 * @since 2.0.0
 *
 * @param array $attributes Block attributes.
 *
 * @return string
 */
function ignition_block_taxonomy_terms_render_callback( $attributes ) {
	$attributes = wp_parse_args( $attributes, ignition_block_taxonomy_terms_defaults() );

	$unique_id           = $attributes['uniqueId'];
	$class_name          = $attributes['className'];
	$taxonomy            = $attributes['taxonomySlug'];
	$included_term_slugs = $attributes['includedTermSlugs'];
	$columns             = intval( $attributes['columns'] );

	$block_classes = array_merge( array(
		'wp-block-ignition-taxonomy-terms',
	), explode( ' ', $class_name ) );

	ob_start();

	?>
	<div id="<?php echo esc_attr( 'ignition-block-' . $unique_id ); ?>" class="<?php echo esc_attr( implode( ' ', $block_classes ) ); ?>">
		<div class="row">
			<?php
				foreach ( $included_term_slugs as $term_slug ) {
					$term_obj = get_term_by( 'slug', $term_slug, $taxonomy );
					if ( empty( $term_obj ) || is_null( $term_obj ) || is_wp_error( $term_obj ) ) {
						continue;
					}

					$cover_image = ignition_get_term_meta( $term_obj->term_id, 'cover_image', ignition_image_bg_control_defaults() );
					$image_id    = ! empty( $cover_image['image_id'] ) ? $cover_image['image_id'] : false;

					$item_template_vars = array(
						'columns'  => $columns,
						'classes'  => array_filter( array_map( 'trim', explode( ' ', $class_name ) ) ),
						'term_obj' => $term_obj,
						'image_id' => $image_id,
					);

					?><div class="<?php echo esc_attr( ignition_get_columns_classes( $columns ) ); ?>"><?php
						if ( 1 === $columns ) {
							ignition_get_template_part( 'template-parts/block/taxonomy-terms/term-article-media', $taxonomy, $item_template_vars );
						} else {
							ignition_get_template_part( 'template-parts/block/taxonomy-terms/term-item', $taxonomy, $item_template_vars );
						}
					?></div><?php
				}
			?>
		</div>
	</div>
	<?php

	$response = ob_get_clean();

	return $response;
}

