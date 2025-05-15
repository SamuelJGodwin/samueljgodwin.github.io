<?php
/**
 * Global Sections module functions and definitions
 *
 * @since 1.2.0
 */

add_action( 'wp', 'ignition_module_gsection_maybe_hook' );
/**
 * Determines if any Global Sections are applicable on the current request, and registers hook handlers for the ones that do.
 *
 * @since 1.2.0
 */
function ignition_module_gsection_maybe_hook() {
	if ( is_admin() ) {
		return;
	}

	$q = new WP_Query( array(
		'post_type'      => 'ignition-gsection',
		'posts_per_page' => - 1,
	) );

	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();

			$locations = get_post_meta( get_the_ID(), 'ignition_gsection_locations', true );
			$includes  = get_post_meta( get_the_ID(), 'ignition_gsection_includes', true );
			$excludes  = get_post_meta( get_the_ID(), 'ignition_gsection_excludes', true );

			$gsection_id = get_the_ID();

			if ( ! empty( $locations ) && is_array( $locations ) ) {
				foreach ( $locations as $location ) {
					if ( ignition_module_gsection_check_conditions_satisfied( $includes ) && ! ignition_module_gsection_check_conditions_satisfied( $excludes ) ) {
						if ( ! ( is_preview() && get_queried_object_id() === $gsection_id ) ) {
							add_action( $location['hook'], function () use ( $gsection_id ) {
								echo do_shortcode( sprintf( '[global-section id="%s"]', $gsection_id ) );
							}, $location['priority'] );

							/**
							 * Fires after a Global Section has be hooked to a location.
							 *
							 * @since 2.1.3
							 *
							 * @param int   $gsection_id
							 * @param array $location
							 */
							do_action( 'ignition_gsection_location_hooked', $gsection_id, $location );
						}

						if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
							$css_post = new \Elementor\Core\Files\CSS\Post( $gsection_id );
							$css_post->enqueue();
						}

						if ( function_exists( 'gutenbee_get_blocks_info' ) && function_exists( 'gutenbee_has_block_in_reusable' ) ) {
							$enqueue_css = false;
							$enqueue_js  = false;
							foreach ( gutenbee_get_blocks_info() as $block_name => $block_info ) {
								if ( has_block( $block_name, $gsection_id ) || gutenbee_has_block_in_reusable( $block_name, $gsection_id ) ) {
									if ( ! $enqueue_css && $block_info['enqueue_css'] ) {
										$enqueue_css = true;
									}
									if ( ! $enqueue_js && $block_info['enqueue_js'] ) {
										$enqueue_js = true;
									}
								}
							}
							if ( $enqueue_css ) {
								add_filter( 'gutenbee_enqueue_frontend_styles', '__return_true' );
							}
							if ( $enqueue_js ) {
								add_filter( 'gutenbee_enqueue_frontend_scripts', '__return_true' );
							}
						} elseif ( function_exists( 'gutenbee_init' ) ) {
							add_filter( 'gutenbee_enqueue_frontend_styles', '__return_true' );
							add_filter( 'gutenbee_enqueue_frontend_scripts', '__return_true' );
						}
					}
				}
			}
		}

		wp_reset_postdata();
	}
}

add_action( 'init', 'ignition_module_gsection_visible_hooks_maybe_hook' );
/**
 * Determines if the Global Section locations should become visible on the front end, and sets up the necessary hooks
 * if they do.
 *
 * @since 1.2.0
 */
function ignition_module_gsection_visible_hooks_maybe_hook() {
	if ( ! isset( $_GET['show_hooks'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

	if ( ! is_user_logged_in() || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$actions = ignition_module_gsection_visible_hooks_get_flat();
	foreach ( $actions as $action => $details ) {
		add_action( $action, 'ignition_module_gsection_visible_hooks_show_hook' );
	}
}

/**
 * Generates the markup for each visible Global Section location, according to its display type (block, inline).
 *
 * @since 1.2.0
 */
function ignition_module_gsection_visible_hooks_show_hook() {
	$actions = ignition_module_gsection_visible_hooks_get_flat();
	$action  = current_action();

	if ( ! isset( $actions[ $action ] ) ) {
		return;
	}

	$details = $actions[ $action ];

	$tag = 'div';

	if ( 'inline' === $details['type'] ) {
		$tag = 'span';
	}

	printf( '<%1$s class="ignition-visible-hook">%2$s</%1$s>',
		$tag, // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $details['name'] )
	);
}

/**
 * Returns a categorized list of Global Section locations.
 *
 * @since 1.2.0
 *
 * @return array
 */
function ignition_module_gsection_visible_hooks_get() {
	/**
	 * Filters the categorized list of Global Section locations.
	 *
	 * @since 1.2.0
	 *
	 * @param array $locations
	 */
	$hooks = apply_filters( 'ignition_gsection_visible_hooks', array(
		'global'  => array(
			'name'  => _x( 'Global', 'hook category name', 'ignition' ),
			'hooks' => array(
				'ignition_global_before' => array(
					'name' => _x( 'Global (before)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_global_after'  => array(
					'name' => _x( 'Global (after)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
			),
		),
		'footer'  => array(
			'name'  => _x( 'Footer', 'hook category name', 'ignition' ),
			'hooks' => array(
				'ignition_before_footer'      => array(
					'name' => _x( 'Before footer', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_after_footer'       => array(
					'name' => _x( 'After footer', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_footer_before'      => array(
					'name' => _x( 'Footer (before)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_footer_after'       => array(
					'name' => _x( 'Footer (after)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_before_footer_info' => array(
					'name' => _x( 'Before footer content', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_after_footer_info'  => array(
					'name' => _x( 'After footer content', 'hook name', 'ignition' ),
					'type' => 'block',
				),
			),
		),
		'content' => array(
			'name'  => _x( 'Content', 'hook category name', 'ignition' ),
			'hooks' => array(
				'ignition_before_main'           => array(
					'name' => _x( 'Before main', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_after_main'            => array(
					'name' => _x( 'After main', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_main_container_before' => array(
					'name' => _x( 'Main container (before)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_main_container_after'  => array(
					'name' => _x( 'Main container (after)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_before_entry'          => array(
					'name' => _x( 'Before entry', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_after_entry'           => array(
					'name' => _x( 'After entry', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_sidebar_before'        => array(
					'name' => _x( 'Sidebar (before)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
				'ignition_sidebar_after'         => array(
					'name' => _x( 'Sidebar (after)', 'hook name', 'ignition' ),
					'type' => 'block',
				),
			),
		),
	) );

	$gsection_support = get_theme_support( 'ignition-gsection' );
	$location_hooks   = array();

	if ( $gsection_support ) {
		if ( ! empty( $gsection_support[0]['locations'] ) ) {
			$locations = $gsection_support[0]['locations'];
			if ( in_array( 'header', $locations, true ) ) {
				$location_hooks = array_merge( $location_hooks, array(
					'ignition_gsection_location_header' => array(
						'name' => _x( 'Header', 'hook name', 'ignition' ),
						'type' => 'block',
					),
				) );
			}

			if ( in_array( 'sidebar', $locations, true ) ) {
				$location_hooks = array_merge( $location_hooks, array(
					'ignition_gsection_location_sidebar' => array(
						'name' => _x( 'Sidebar', 'hook name', 'ignition' ),
						'type' => 'block',
					),
				) );
			}

			if ( in_array( 'footer', $locations, true ) ) {
				$location_hooks = array_merge( $location_hooks, array(
					'ignition_gsection_location_footer' => array(
						'name' => _x( 'Footer', 'hook name', 'ignition' ),
						'type' => 'block',
					),
				) );
			}
		}
	}

	if ( ! empty( $location_hooks ) ) {
		$hooks = array_merge( $hooks, array(
			'replace' => array(
				'name'  => _x( 'Section Replacement', 'hook category name', 'ignition' ),
				'hooks' => $location_hooks,
			),
		) );
	}

	return $hooks;
}

/**
 * Returns a flat list of Global Section locations.
 *
 * @since 1.2.0
 *
 * @return array
 */
function ignition_module_gsection_visible_hooks_get_flat() {
	$flat_list     = array();
	$visible_hooks = ignition_module_gsection_visible_hooks_get();

	foreach ( $visible_hooks as $category ) {
		foreach ( $category['hooks'] as $hook => $details ) {
			$flat_list[ $hook ] = $details;
		}
	}

	return $flat_list;
}

/**
 * Returns the option (and optgroup) tags' markup required to display a dropdown of Global Section locations.
 *
 * @since 1.2.0
 *
 * @param string $selected Optional. The value of a preselected option, if any. Default empty.
 *
 * @return string
 */
function ignition_module_gsection_visible_hooks_get_dropdown_options( $selected = '' ) {
	$hooks = ignition_module_gsection_visible_hooks_get();
	$html  = '';

	$is_selected_in_list = false;

	$html .= sprintf( '<option value="" %s>%s</option>', selected( '', $selected, false ), esc_html__( 'Select location...', 'ignition' ) );

	foreach ( $hooks as $optgroup_slug => $optgroup ) {
		$html .= sprintf( '<optgroup label="%s">', esc_attr( $optgroup['name'] ) );
		foreach ( $optgroup['hooks'] as $hook => $hook_info ) {
			$html .= sprintf( '<option value="%s" %s>%s</option>',
				$hook,
				selected( $hook, $selected, false ),
				$hook_info['name']
			);

			if ( ! $is_selected_in_list && $selected === $hook ) {
				$is_selected_in_list = true;
			}
		}
		$html .= '</optgroup>';
	}

	if ( ! empty( $selected ) && ! $is_selected_in_list ) {
		$selected = 'custom';
	}
	$html .= sprintf( '<option value="custom" %s>%s</option>', selected( 'custom', $selected, false ), esc_html__( 'Custom hook...', 'ignition' ) );

	return $html;
}

/**
 * Returns a list of Global Section inclusion/exclusion rules.
 *
 * @since 1.2.0
 *
 * @return array
 */
function ignition_module_gsection_get_rules_array() {
	static $options = false;

	if ( ! empty( $options ) ) {
		return (array) $options;
	}

	$options = array(
		array(
			'name'         => __( 'Select...', 'ignition' ),
			'slug'         => '',
			'option_value' => '',
			'optgroup'     => false,
		),
		array(
			'name'         => __( 'Global (sitewide)', 'ignition' ),
			'slug'         => 'global',
			'option_value' => 'global',
			'optgroup'     => 'general',
		),
		array(
			'name'         => __( 'Singular (any post/page/etc)', 'ignition' ),
			'slug'         => 'singular',
			'option_value' => 'singular',
			'optgroup'     => 'general',
		),
		array(
			'name'         => __( 'Homepage', 'ignition' ),
			'slug'         => 'homepage',
			'option_value' => 'homepage',
			'optgroup'     => 'general',
		),
		array(
			'name'         => __( 'Blog', 'ignition' ),
			'slug'         => 'blog',
			'option_value' => 'blog',
			'optgroup'     => 'general',
		),
		array(
			'name'         => __( 'Search page', 'ignition' ),
			'slug'         => 'search',
			'option_value' => 'search',
			'optgroup'     => 'general',
		),
		array(
			'name'         => __( '404 page', 'ignition' ),
			'slug'         => '404',
			'option_value' => '404',
			'optgroup'     => 'general',
		),
		array(
			'name'         => __( 'All archives', 'ignition' ),
			'slug'         => 'archive',
			'option_value' => 'archive',
			'optgroup'     => 'archives',
		),
		array(
			'name'         => __( 'Author archives', 'ignition' ),
			'slug'         => 'archive-author',
			'option_value' => 'archive-author',
			'optgroup'     => 'archives',
			'entries'      => array_merge(
				array(
					array(
						'name'         => __( 'All authors', 'ignition' ),
						'option_value' => 0,
					),
				),
				ignition_module_gsection_get_all_authors()
			),
		),
		array(
			'name'         => __( 'Date archives', 'ignition' ),
			'slug'         => 'archive-date',
			'option_value' => 'archive-date',
			'optgroup'     => 'archives',
			'entries'      => array(
				array(
					'name'         => __( 'All date archives', 'ignition' ),
					'option_value' => '',
				),
				array(
					'name'         => __( 'Yearly archives', 'ignition' ),
					'option_value' => 'year',
				),
				array(
					'name'         => __( 'Monthly archives', 'ignition' ),
					'option_value' => 'month',
				),
				array(
					'name'         => __( 'Daily archives', 'ignition' ),
					'option_value' => 'day',
				),
			),
		),
		array(
			'name'         => __( 'Post Type archives', 'ignition' ),
			'slug'         => 'archive-post_type',
			'option_value' => 'archive-post_type',
			'optgroup'     => 'archives',
			'entries'      => array_merge(
				array(
					array(
						'name'         => __( 'All post types', 'ignition' ),
						'option_value' => '',
					),
				),
				ignition_module_gsection_get_post_types_with_archives()
			),
		),
		array(
			'name'         => __( 'Taxonomy archives', 'ignition' ),
			'slug'         => 'archive-taxonomy',
			'option_value' => 'archive-taxonomy',
			'optgroup'     => 'archives',
			'entries'      => array_merge(
				array(
					array(
						'name'         => __( 'All taxonomies', 'ignition' ),
						'option_value' => '',
					),
				),
				ignition_module_gsection_get_public_taxonomies()
			),
		),
	);

	$public_post_types = get_post_types( array(
		'public'             => true,
		'publicly_queryable' => true,
	), 'objects', 'or' );

	$all_post_type_templates = array();
	/** @var WP_Post_Type $post_type */
	foreach ( $public_post_types as $post_type ) {
		$all_post_type_templates = array_merge( $all_post_type_templates, get_page_templates( null, $post_type->name ) );
	}

	if ( ! empty( $all_post_type_templates ) ) {
		$entries = array();
		foreach ( $all_post_type_templates as $tpl_name => $tpl_file ) {
			$ignore_templates = array(
				'elementor_canvas',
			);

			if ( in_array( $tpl_file, $ignore_templates, true ) ) {
				continue;
			}

			$entries[] = array(
				'name'         => $tpl_name,
				'option_value' => $tpl_file,
			);
		}

		$options[] = array(
			'name'         => __( 'Page Templates', 'ignition' ),
			'slug'         => 'page_template',
			'option_value' => 'page_template',
			'optgroup'     => 'general',
			'entries'      => $entries,
		);
	}

	/** @var WP_Post_Type $post_type */
	foreach ( $public_post_types as $post_type ) {
		$options[] = array(
			'name'         => $post_type->label,
			'slug'         => "post_type-{$post_type->name}",
			'option_value' => "post_type-{$post_type->name}",
			'optgroup'     => 'post_type',
			'entries'      => array_merge(
				array(
					array(
						/* translators: %1$s is a post type name, e.g. All Pages. Use %2$s for singular if required. */
						'name'         => sprintf( _x( 'All %1$s', 'all post type items', 'ignition' ), $post_type->label, $post_type->labels->singular_name ),
						'option_value' => 0,
					),
				),
				ignition_module_gsection_get_all_post_type_posts( $post_type->name )
			),
		);
	}

	$public_taxonomies = get_taxonomies( array(
		'public'             => true,
		'publicly_queryable' => true,
	), 'objects', 'or' );

	/** @var WP_Taxonomy $post_type */
	foreach ( $public_taxonomies as $taxonomy ) {

		$pt_names = array();
		foreach ( $taxonomy->object_type as $post_type_name ) {
			$pt         = get_post_type_object( $post_type_name );
			$pt_names[] = $pt->label;
		}

		$options[] = array(
			/* translators:  */
			'name'         => sprintf( _x( '%1$s (%2$s)', 'taxonomy and post types', 'ignition' ), $taxonomy->label, implode( ', ', $pt_names ) ),
			'slug'         => "taxonomy-{$taxonomy->name}",
			'option_value' => "taxonomy-{$taxonomy->name}",
			'optgroup'     => 'taxonomy',
			'entries'      => array_merge(
				array(
					array(
						/* translators: %1$s is a taxonomy name, e.g. All Categories. Use %2$s for singular if required. */
						'name'         => sprintf( _x( 'All %1$s', 'all taxonomy items', 'ignition' ), $taxonomy->label, $taxonomy->labels->singular_name ),
						'option_value' => 0,
					),
				),
				ignition_module_gsection_get_all_taxonomy_terms( $taxonomy->name )
			),
		);
	}

	return $options;
}

/**
 * Returns the option (and optgroup) tags' markup required to display a dropdown of Global Section inclusion/exclusion
 * conditions.
 *
 * @since 1.2.0
 *
 * @param string $selected Optional. The value of a preselected option, if any. Default empty.
 *
 * @return string
 */
function ignition_module_gsection_rules_dropdown_options( $selected = '' ) {
	$options = ignition_module_gsection_get_rules_array();

	$optgroup_names = array(
		'general'   => __( 'General', 'ignition' ),
		'archives'  => __( 'Archives', 'ignition' ),
		'post_type' => __( 'Post types', 'ignition' ),
		'taxonomy'  => __( 'Taxonomies', 'ignition' ),
	);

	$optgroups = array();

	$html = '';

	// First, options without an optgroup.
	foreach ( $options as $option ) {
		if ( ! isset( $option['optgroup'] ) || ! $option['optgroup'] ) {
			$html .= sprintf( '<option value="%s" %s>%s</option>',
				esc_attr( $option['option_value'] ),
				selected( $option['option_value'], $selected, false ),
				wp_kses( $option['name'], 'strip' )
			);
		}
	}

	// Then, options with optgroup.
	foreach ( $options as $option ) {
		if ( ! empty( $option['optgroup'] ) ) {
			if ( empty( $optgroups[ $option['optgroup'] ] ) ) {
				$optgroups[ $option['optgroup'] ] = array();
			}

			$optgroups[ $option['optgroup'] ][] = $option;
		}
	}

	foreach ( $optgroups as $optgroup => $optgroup_options ) {
		$label = isset( $optgroup_names[ $optgroup ] ) ? $optgroup_names[ $optgroup ] : $optgroup;
		$html .= sprintf( '<optgroup label="%s">', wp_kses( $label, 'strip' ) );

		foreach ( $optgroup_options as $option ) {
			$html .= sprintf( '<option value="%s" %s>%s</option>',
				esc_attr( $option['option_value'] ),
				selected( $option['option_value'], $selected, false ),
				wp_kses( $option['name'], 'strip' )
			);
		}

		$html .= '</optgroup>';
	}

	return $html;
}

/**
 * Checks whether a given set of inclusion/exclusion conditions is satisfied.
 *
 * Returns true as soon as any condition is true, therefore conditions should be considered ORed.
 *
 * @param array $conditions {
 *     Required. Array of condition arrays.
 *
 *     @type array[] {
 *         @type string $type
 *         @type int|string $subtype
 *     }
 * }
 *
 * @return bool True if any condition is met, false otherwise.
 */
function ignition_module_gsection_check_conditions_satisfied( $conditions ) {

	$return = false;

	foreach ( $conditions as $condition ) {
		$condition_type_parts = explode( '-', $condition['type'] );

		$rule_type = $condition_type_parts[0];

		// In case the type's second part is also delimited by dashes, we have a problem. Let's reconstruct.
		if ( count( $condition_type_parts ) > 2 ) {
			unset( $condition_type_parts[0] );
			$object_type          = implode( '-', $condition_type_parts );
			$condition_type_parts = array( $rule_type, $object_type );
		}

		$object_type = isset( $condition_type_parts[1] ) ? $condition_type_parts[1] : '';
		$object_id   = $condition['subtype'];

		switch ( $rule_type ) {
			case 'global':
				$return = true;
				break;
			case 'singular':
				if ( is_singular() ) {
					$return = true;
				}
				break;
			case 'homepage':
				if ( is_front_page() ) {
					$return = true;
				}
				break;
			case 'blog':
				if ( is_home() ) {
					$return = true;
				}
				break;
			case 'search':
				if ( is_search() ) {
					$return = true;
				}
				break;
			case '404':
				if ( is_404() ) {
					$return = true;
				}
				break;
			case 'page_template':
				if ( is_page_template( $object_id ) ) {
					$return = true;
				}
				break;
			case 'theme_page_layout':
				if ( is_singular() && get_post_meta( get_queried_object_id(), 'global_layout_pages_layout_type', true ) === $object_id ) {
					$return = true;
				}
				break;
			case 'archive':
				switch ( $object_type ) {
					case '':
						if ( is_archive() ) {
							$return = true;
						}
						break;
					case 'author':
						if ( is_author( $object_id ) ) {
							$return = true;
						}
						break;
					case 'post_type':
						if ( is_post_type_archive( $object_id ) ) {
							$return = true;
						}
						break;
					case 'taxonomy':
						switch ( $object_id ) {
							case '':
								if ( is_category() || is_tag() || is_tax() ) {
									$return = true;
								}
								break;
							case 'category':
								if ( is_category( $object_id ) ) {
									$return = true;
								}
								break;
							case 'post_tag':
								if ( is_tag( $object_id ) ) {
									$return = true;
								}
								break;
							default:
								if ( is_tax( $object_id ) ) {
									$return = true;
								}
								break;
						}
						break;
					case 'date':
						switch ( $object_id ) {
							case 'year':
								if ( is_year() ) {
									$return = true;
								}
								break;
							case 'month':
								if ( is_month() ) {
									$return = true;
								}
								break;
							case 'day':
								if ( is_day() ) {
									$return = true;
								}
								break;
							default:
								if ( is_date() ) {
									$return = true;
								}
								break;
						}
						break;
				}
				break;
			case 'post_type':
				if ( function_exists( 'is_shop' ) && is_shop() && intval( wc_get_page_id( 'shop' ) ) === intval( $object_id ) ) {
					$return = true;
				} elseif ( is_singular( $object_type ) ) {
					if ( ! empty( $object_id ) ) {
						if ( intval( get_queried_object_id() ) === intval( $object_id ) ) {
							$return = true;
						}
					} else {
						$return = true;
					}
				}
				break;
			case 'taxonomy':
				if ( is_tax( $object_type ) ) {
					if ( ! empty( $object_id ) ) {
						if ( is_tax( $object_type, intval( $object_id ) ) ) {
							$return = true;
						}
					} else {
						$return = true;
					}
				}
				break;
		}

		// Bail as soon as a rule is true.
		if ( $return ) {
			break;
		}
	}

	return $return;
}

/**
 * Returns a list of authors and their IDs.
 *
 * Only authors with published posts are returned.
 *
 * @since 1.2.0
 *
 * @return array {
 *     array[] {
 *         @type string $name Author display name.
 *         @type int    $option_value Author ID.
 *     }
 * }
 */
function ignition_module_gsection_get_all_authors() {
	$q = new WP_User_Query( array(
		'has_published_posts' => true,
		'order'               => 'ASC',
		'orderby'             => 'display_name',
	) );

	$authors = $q->get_results();

	$array = array();

	/** @var WP_User $author */
	foreach ( $authors as $author ) {
		$array[] = array(
			'name'         => $author->display_name,
			'option_value' => $author->ID,
		);
	}

	return $array;
}

/**
 * Returns a list of all posts, from a specific post type.
 *
 * @since 1.2.0
 *
 * @param string $post_type
 *
 * @return array {
 *     array[] {
 *         @type string $name Post title.
 *         @type int    $option_value Post ID.
 *     }
 * }
 */
function ignition_module_gsection_get_all_post_type_posts( $post_type ) {
	$q = new WP_Query( array(
		'post_type'           => $post_type,
		'posts_per_page'      => - 1,
		'ignore_sticky_posts' => true,
		'order'               => 'ASC',
		'orderby'             => 'title',
	) );

	$array = array();

	/** @var WP_Post $p */
	foreach ( $q->posts as $p ) {
		$id = intval( $p->ID );

		$array[] = array(
			'name'         => get_the_title( $id ),
			'option_value' => $id,
		);
	}

	return $array;
}

/**
 * Returns a list of all taxonomy terms, from a specific taxonomy.
 *
 * @since 1.2.0
 *
 * @param string $taxonomy
 *
 * @return array {
 *     array[] {
 *         @type string $name Term name.
 *         @type int    $option_value Term ID.
 *     }
 * }
 */
function ignition_module_gsection_get_all_taxonomy_terms( $taxonomy ) {
	$terms = get_terms( array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => false,
		'order'      => 'ASC',
		'orderby'    => 'name',
	) );

	$array = array();

	/** @var WP_Term $term */
	foreach ( $terms as $term ) {
		$array[] = array(
			'name'         => $term->name,
			'option_value' => $term->term_id,
		);
	}

	return $array;
}

/**
 * Returns a list of post types that have their post type archives enabled.
 *
 * @since 1.2.0
 *
 * @return array {
 *     array[] {
 *         @type string $name Post type label.
 *         @type int    $option_value Post type name.
 *     }
 * }
 */
function ignition_module_gsection_get_post_types_with_archives() {
	$post_types = get_post_types( array(
		'has_archive' => false,
	), 'objects', 'not' );

	$array = array();

	/** @var WP_Post_Type $post_type */
	foreach ( $post_types as $post_type ) {
		$array[] = array(
			'name'         => $post_type->label,
			'option_value' => $post_type->name,
		);
	}

	return $array;
}

/**
 * Returns a list of public taxonomies.
 *
 * @since 1.2.0
 *
 * @return array {
 *     array[] {
 *         @type string $name Taxonomy label.
 *         @type int    $option_value Taxonomy name.
 *     }
 * }
 */
function ignition_module_gsection_get_public_taxonomies() {
	$taxonomies = get_taxonomies( array(
		'public'             => true,
		'publicly_queryable' => true,
	), 'objects', 'or' );

	$array = array();

	/** @var WP_Taxonomy $taxonomy */
	foreach ( $taxonomies as $taxonomy ) {
		$array[] = array(
			'name'         => $taxonomy->label,
			'option_value' => $taxonomy->name,
		);
	}

	return $array;
}

/**
 * Renders a replacement theme location, if applicable.
 *
 * @since 1.8.0
 *
 * @param string $do_location The replacement theme location to render.
 *
 * @return bool
 */
function ignition_gsection_do_location( $do_location ) {
	$action_name = "ignition_gsection_location_{$do_location}";

	if ( has_action( $action_name ) ) {
		/**
		 * Hook: ignition_gsection_location_{$do_location}
		 *
		 * @since 1.8.0
		 */
		do_action( $action_name );

		return true;
	}

	return false;
}


/**
 * Checks whether a replacement theme location exists.
 *
 * @since 1.9.1
 *
 * @param string $do_location The replacement theme location to check.
 *
 * @return bool
 */
function ignition_gsection_can_do_location( $do_location ) {
	$action_name = "ignition_gsection_location_{$do_location}";

	if ( has_action( $action_name ) ) {
		return true;
	}

	return false;
}

