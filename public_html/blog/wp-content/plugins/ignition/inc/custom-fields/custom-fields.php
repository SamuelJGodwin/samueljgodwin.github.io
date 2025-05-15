<?php
/**
 * Custom fields functions and definitions
 *
 * @since 1.0.0
 */

require_once untrailingslashit( __DIR__ ) . '/controls/main-input/main-input.php';
require_once untrailingslashit( __DIR__ ) . '/controls/main-checkbox/main-checkbox.php';
require_once untrailingslashit( __DIR__ ) . '/controls/main-textarea/main-textarea.php';
require_once untrailingslashit( __DIR__ ) . '/controls/side-input/side-input.php';
require_once untrailingslashit( __DIR__ ) . '/controls/side-dropdown/side-dropdown.php';
require_once untrailingslashit( __DIR__ ) . '/controls/side-checkbox/side-checkbox.php';
require_once untrailingslashit( __DIR__ ) . '/controls/side-image/side-image.php';
require_once untrailingslashit( __DIR__ ) . '/controls/side-file-select/side-file-select.php';
require_once untrailingslashit( __DIR__ ) . '/controls/side-textarea/side-textarea.php';
require_once untrailingslashit( __DIR__ ) . '/controls/separator/separator.php';


/**
 * Returns the post types that have the Page Title Image option.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_single_page_title_image_post_types() {
	$post_types = array(
		'post',
		'page',
	);

	/**
	 * Filters the post types that have the Page Title Image option.
	 *
	 * @since 1.0.0
	 *
	 * @param array $post_types
	 *
	 * @hooked ignition_woocommerce_add_cpt_to_array - 10
	 * @hooked ignition_module_accommodation_add_cpt_to_array - 10
	 * @hooked ignition_module_discography_add_cpt_to_array - 10
	 * @hooked ignition_module_event_add_cpt_to_array - 10
	 * @hooked ignition_module_package_add_cpt_to_array - 10
	 * @hooked ignition_module_podcast_add_cpt_to_array - 10
	 * @hooked ignition_module_portfolio_add_cpt_to_array - 10
	 * @hooked ignition_module_service_add_cpt_to_array - 10
	 * @hooked ignition_module_team_add_cpt_to_array - 10
	 * @hooked ignition_module_property_add_cpt_to_array - 10
	 */
	$post_types = apply_filters( 'ignition_single_page_title_image_post_types', $post_types );

	if ( ! current_theme_supports( 'ignition-page-title-with-background' ) ) {
		$post_types = array();
	}

	return $post_types;
}

/**
 * Returns the post types that have Page Title options.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_single_page_title_post_types() {
	/**
	 * Filters the post types that have the Page Title options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $post_types
	 *
	 * @hooked ignition_module_accommodation_add_cpt_to_array - 10
	 * @hooked ignition_module_discography_add_cpt_to_array - 10
	 * @hooked ignition_module_event_add_cpt_to_array - 10
	 * @hooked ignition_module_package_add_cpt_to_array - 10
	 * @hooked ignition_module_podcast_add_cpt_to_array - 10
	 * @hooked ignition_module_portfolio_add_cpt_to_array - 10
	 * @hooked ignition_module_service_add_cpt_to_array - 10
	 * @hooked ignition_module_team_add_cpt_to_array - 10
	 * @hooked ignition_module_property_add_cpt_to_array - 10
	 */
	return apply_filters( 'ignition_single_page_title_post_types', array(
		'page',
	) );
}

/**
 * Returns the post types that have Feature Image Visibility options.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_single_featured_image_visibility_post_types() {
	/**
	 * Filters the post types that have Feature Image Visibility options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $post_types
	 *
	 * @hooked ignition_module_accommodation_add_cpt_to_array - 10
	 * @hooked ignition_module_discography_add_cpt_to_array - 10
	 * @hooked ignition_module_event_add_cpt_to_array - 10
	 * @hooked ignition_module_package_add_cpt_to_array - 10
	 * @hooked ignition_module_podcast_add_cpt_to_array - 10
	 * @hooked ignition_module_portfolio_add_cpt_to_array - 10
	 * @hooked ignition_module_service_add_cpt_to_array - 10
	 * @hooked ignition_module_team_add_cpt_to_array - 10
	 * @hooked ignition_module_property_add_cpt_to_array - 10
	 */
	return apply_filters( 'ignition_single_featured_image_visibility_post_types', array(
		'page',
	) );
}

/**
 * Returns the post types that have the Remove Main Margin option.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_single_remove_main_padding_post_types() {
	/**
	 * Filters the post types that have the Remove Main Margin option.
	 *
	 * @since 1.0.0
	 *
	 * @param array $post_types
	 *
	 * @hooked ignition_module_accommodation_add_cpt_to_array - 10
	 * @hooked ignition_module_discography_add_cpt_to_array - 10
	 * @hooked ignition_module_event_add_cpt_to_array - 10
	 * @hooked ignition_module_package_add_cpt_to_array - 10
	 * @hooked ignition_module_podcast_add_cpt_to_array - 10
	 * @hooked ignition_module_portfolio_add_cpt_to_array - 10
	 * @hooked ignition_module_service_add_cpt_to_array - 10
	 * @hooked ignition_module_team_add_cpt_to_array - 10
	 * @hooked ignition_module_property_add_cpt_to_array - 10
	 */
	return apply_filters( 'ignition_single_remove_main_padding_post_types', array(
		'post',
		'page',
	) );
}

add_action( 'admin_init', 'ignition_setup_single_page_title_options_metabox' );
/**
 * Registers the necessary metaboxes for each post type.
 *
 * @since 1.0.0
 */
function ignition_setup_single_page_title_options_metabox() {

	foreach ( ignition_get_single_page_title_image_post_types() as $post_type ) {
		add_meta_box( 'ignition-single-page-title-image', esc_html__( 'Page Title Image', 'ignition' ), 'ignition_single_page_title_image_options_metabox', $post_type, 'side', 'default' );
	}
	add_action( 'save_post', 'ignition_single_page_title_image_options_save_post' );

	/**
	 * Filters the list of post types that can show any options.
	 *
	 * @since 1.0.0
	 *
	 * @param array $post_types
	 */
	$page_settings_post_types = apply_filters( 'ignition_page_settings_post_types', array_unique( array_merge(
		ignition_get_single_featured_image_visibility_post_types(),
		ignition_get_single_page_title_post_types(),
		ignition_get_single_remove_main_padding_post_types()
	) ) );

	foreach ( $page_settings_post_types as $post_type ) {
		add_meta_box( 'ignition-single-page-settings', esc_html__( 'Page Settings', 'ignition' ), 'ignition_single_page_settings_metabox', $post_type, 'side', 'default' );
	}
	add_action( 'save_post', 'ignition_single_page_settings_save_post' );
}

/**
 * Displays the "Page Settings" metabox contents.
 *
 * @since 1.0.0
 *
 * @param WP_Post $object
 * @param array $box
 */
function ignition_single_page_settings_metabox( $object, $box ) {
	// Nonce generated inside ignition_prepare_metabox()
	ignition_prepare_metabox( $object->post_type );

	if ( in_array( $object->post_type, ignition_get_single_remove_main_padding_post_types(), true ) ) {
		ignition_side_metabox_checkbox( 'single_remove_main_padding', array(
			'title'         => __( 'Remove top/bottom content padding', 'ignition' ),
			'checked_value' => 1,
			'default'       => '',
		) );
	}

	if ( in_array( $object->post_type, ignition_get_single_featured_image_visibility_post_types(), true ) ) {
		$pt_name = get_post_type_object( $object->post_type )->labels->singular_name;
		ignition_side_metabox_checkbox( 'single_featured_image_is_hidden', array(
			/* translators: %s is a post type's singular name, e.g. post, page, event, etc. */
			'title'         => sprintf( __( 'Disable featured image for this %s', 'ignition' ), $pt_name ),
			'checked_value' => 1,
			'default'       => '',
		) );
	}

	if ( in_array( $object->post_type, ignition_get_single_page_title_post_types(), true ) ) {
		if ( count( ignition_header_layout_type_choices() ) > 1 ) {
			ignition_side_metabox_dropdown( 'header_layout_type', array(
				'title'   => __( 'Header Type', 'ignition' ),
				'default' => '',
				'choices' => ignition_respect_header_layout_type_choices(),
			) );
		}

		if ( current_theme_supports( 'ignition-page-title-with-background' ) ) {
			ignition_side_metabox_dropdown( 'page_title_with_background_is_visible', array(
				'title'   => __( 'Show Page Title with Background', 'ignition' ),
				'default' => '',
				'choices' => ignition_get_respect_show_hide_options(),
			) );
		}

		ignition_side_metabox_dropdown( 'normal_page_title_title_is_visible', array(
			'title'   => current_theme_supports( 'ignition-page-title-with-background' ) ? __( 'Show Normal Page Title', 'ignition' ) : __( 'Show Page Title', 'ignition' ),
			'choices' => ignition_get_respect_show_hide_options(),
		) );

		ignition_side_metabox_dropdown( 'normal_page_title_subtitle_is_visible', array(
			'title'   => current_theme_supports( 'ignition-page-title-with-background' ) ? __( 'Show Normal Page Subtitle', 'ignition' ) : __( 'Show Page Subtitle', 'ignition' ),
			'choices' => ignition_get_respect_show_hide_options(),
		) );

		if ( ignition_can_show_breadcrumb() ) {
			ignition_side_metabox_dropdown( 'breadcrumb_is_visible', array(
				'title'   => __( 'Show Breadcrumbs', 'ignition' ),
				'choices' => ignition_get_respect_show_hide_options(),
			) );
		}
	}
}

/**
 * Stores the "Page Settings" post meta.
 *
 * @since 1.0.0
 *
 * @param int $post_id
 */
function ignition_single_page_settings_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	if ( in_array( get_post_type( $post_id ), ignition_get_single_remove_main_padding_post_types(), true ) ) {
		update_post_meta( $post_id, 'single_remove_main_padding', isset( $_POST['single_remove_main_padding'] ) );
	}

	if ( in_array( get_post_type( $post_id ), ignition_get_single_featured_image_visibility_post_types(), true ) ) {
		update_post_meta( $post_id, 'single_featured_image_is_hidden', isset( $_POST['single_featured_image_is_hidden'] ) );
	}

	if ( in_array( get_post_type( $post_id ), ignition_get_single_page_title_post_types(), true ) ) {
		if ( isset( $_POST['header_layout_type'] ) && count( ignition_header_layout_type_choices() ) > 1 ) {
			update_post_meta( $post_id, 'header_layout_type', ignition_sanitize_respect_header_layout_type( $_POST['header_layout_type'] ) );
		}

		if ( isset( $_POST['page_title_with_background_is_visible'] ) && current_theme_supports( 'ignition-page-title-with-background' ) ) {
			update_post_meta( $post_id, 'page_title_with_background_is_visible', ignition_sanitize_respect_show_hide_option( $_POST['page_title_with_background_is_visible'] ) );
		}

		if ( isset( $_POST['normal_page_title_title_is_visible'] ) ) {
			update_post_meta( $post_id, 'normal_page_title_title_is_visible', ignition_sanitize_respect_show_hide_option( $_POST['normal_page_title_title_is_visible'] ) );
		}

		if ( isset( $_POST['normal_page_title_subtitle_is_visible'] ) ) {
			update_post_meta( $post_id, 'normal_page_title_subtitle_is_visible', ignition_sanitize_respect_show_hide_option( $_POST['normal_page_title_subtitle_is_visible'] ) );
		}

		if ( ignition_can_show_breadcrumb() ) {
			if ( isset( $_POST['breadcrumb_is_visible'] ) ) {
				update_post_meta( $post_id, 'breadcrumb_is_visible', ignition_sanitize_respect_show_hide_option( $_POST['breadcrumb_is_visible'] ) );
			}
		}
	}

	// phpcs:enable
}

/**
 * Displays the "Page Title Image" metabox contents.
 *
 * @since 1.0.0
 *
 * @param WP_Post $object
 * @param array $box
 */
function ignition_single_page_title_image_options_metabox( $object, $box ) {
	// Nonce generated inside ignition_prepare_metabox()
	ignition_prepare_metabox( $object->post_type );

	ignition_side_metabox_image( 'page_title_colors_background_image', array(
		'default' => ignition_image_bg_control_defaults(),
		'labels'  => array(
			'no_image'     => __( 'Set page title image', 'ignition' ),
			'remove_image' => __( 'Remove page title Image', 'ignition' ),
		),
	) );

	ignition_side_metabox_file_select( 'page_title_colors_background_video', array(
		'title'     => __( 'Background Video', 'ignition' ),
		'file_type' => 'video',
	) );

	ignition_side_metabox_checkbox( 'page_title_colors_background_video_disabled', array(
		'title' => __( 'Disable background video', 'ignition' ),
	) );
}

/**
 * Stores the "Page Title Image" post meta.
 *
 * @since 1.0.0
 *
 * @param int $post_id
 */
function ignition_single_page_title_image_options_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	if ( ! in_array( get_post_type( $post_id ), ignition_get_single_page_title_image_post_types(), true ) ) {
		return;
	}

	if ( isset( $_POST['page_title_colors_background_image'] ) ) {
		// Make sure we only store useful values, or empty string to flag that it should be inherited.
		$image = ignition_sanitize_image_bg_control( stripslashes_deep( $_POST['page_title_colors_background_image'] ) );
		$image = ! ignition_are_image_bg_arrays_equal( $image, ignition_image_bg_control_defaults() ) ? $image : '';
		update_post_meta( $post_id, 'page_title_colors_background_image', $image );
	}

	if ( isset( $_POST['page_title_colors_background_video'] ) ) {
		update_post_meta( $post_id, 'page_title_colors_background_video', esc_url_raw( $_POST['page_title_colors_background_video'] ) );
	}
	update_post_meta( $post_id, 'page_title_colors_background_video_disabled', isset( $_POST['page_title_colors_background_video_disabled'] ) );

	// phpcs:enable
}


//
// Helpers
//
/**
 * Prepares the metabox with the required nonces.
 *
 * @since 1.0.0
 *
 * @param $post_type string The post type that the metabox is being displayed at.
 */
function ignition_prepare_metabox( $post_type ) {
	wp_nonce_field( basename( __FILE__ ), $post_type . '_nonce' );
}

/**
 * Determines whether the metaboxes' values can be saved.
 * Checks nonces, capabilities, etc.
 *
 * @since 1.0.0
 *
 * @param $post_type string The post type that the metabox is being displayed at.
 *
 * @return bool True if the metabox's value should be saved, false otherwise.
 */
function ignition_can_save_meta( $post_type ) {
	global $post;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	if ( ! isset( $_POST[ $post_type . '_nonce' ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ $post_type . '_nonce' ] ) ), basename( __FILE__ ) ) ) {
		return false;
	}

	if ( isset( $_POST['post_view'] ) && 'list' === $_POST['post_view'] ) {
		return false;
	}

	if ( ! isset( $_POST['post_type'] ) || $post_type !== $_POST['post_type'] ) {
		return false;
	}

	$post_type_obj = get_post_type_object( $post->post_type );
	if ( ! current_user_can( $post_type_obj->cap->edit_post, $post->ID ) ) {
		return false;
	}

	return true;
}

/**
 * Creates the metabox markup required for a tabbed interface.
 *
 * @since 1.2.0
 *
 * @param array[] $structure {
 *     Required. The tabbed interface structure.
 *
 *     @type array $horizontal_tab_key {
 *         @type string $title The horizontal tab's label.
 *         @type string $icon Icon HTML classes. E.g. 'dashicons dashicons-media-document'
 *         @type array  $tabs {
 *             Required. key => label array of the vertical tabs.
 *
 *             @type string $vertical_tab_key The vertical tab's label.
 *         }
 *     }
 * }
 * @param string  $prefix
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_create_tabs( $structure, $prefix, $object, $box ) {
	$loading_class = isset( $_GET['ignition_tabs'] ) ? 'loading' : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$h_tabs_count  = count( $structure );
	?>
	<div class="ignition-meta-tabs ignition-meta-tabs-horizontal <?php echo esc_attr( $loading_class ); ?>">

		<?php if ( $h_tabs_count > 1 ) : ?>
			<ul class="ignition-meta-tabs-nav ignition-tabs-primary">
				<?php
					$first_h_tab_class = 'ignition-meta-tabs-nav-item-active';

					foreach ( $structure as $h_tab => $h_tab_values ) {

						if ( empty( $h_tab_values['tabs'] ) ) {
							continue;
						}

						$classes = implode( ' ', array_filter( array(
							"ignition-meta-tabs-nav-item-{$h_tab}",
							$first_h_tab_class,
						) ) );

						?>
						<li class="ignition-meta-tabs-nav-item <?php echo esc_attr( $classes ); ?>">
							<a href="#">
								<?php if ( ! empty( $h_tab_values['icon'] ) ) : ?>
									<span class="<?php echo esc_attr( $h_tab_values['icon'] ); ?>"></span>
								<?php endif; ?>

								<?php echo esc_html( $h_tab_values['title'] ); ?>
							</a>
						</li>
						<?php

						$first_h_tab_class = '';
					}
				?>
			</ul>
		<?php endif; ?>

		<?php
			foreach ( $structure as $h_tab => $h_tab_values ) {

				if ( empty( $h_tab_values['tabs'] ) ) {
					continue;
				}

				?>
				<div class="ignition-meta-tabs-content">
					<div class="ignition-meta-tabs ignition-meta-tabs-vertical">

						<ul class="ignition-meta-tabs-nav">
						<?php
							$first_v_tab_class = 'ignition-meta-tabs-nav-item-active';

							foreach ( $h_tab_values['tabs'] as $v_tab => $v_tab_value ) {

								$classes = implode( ' ', array_filter( array(
									"ignition-meta-tabs-nav-item-{$h_tab}-{$v_tab}",
									$first_v_tab_class,
								) ) );
								?>
								<li class="ignition-meta-tabs-nav-item <?php echo esc_attr( $first_v_tab_class ); ?>">
									<a href="#"><?php echo esc_html( $v_tab_value ); ?></a>
								</li>
								<?php
								$first_v_tab_class = '';
							}
						?>
						</ul>

						<?php

						foreach ( $h_tab_values['tabs'] as $v_tab => $v_tab_value ) {
							$callback = str_replace( '-', '_', "ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}" );

							if ( is_callable( $callback ) ) {
								add_action( "ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}", $callback, 10, 6 );
							}

							$tab_class = implode( '-', array(
								'ignition-meta-tabs-content',
								$prefix,
								$h_tab,
								$v_tab,
							) );

							?><div class="ignition-meta-tabs-content <?php echo esc_attr( $tab_class ); ?>"><?php

							do_action( 'ignition_metabox_display_tab', $prefix, $h_tab, $v_tab, $structure, $object, $box );
							do_action( "ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}", $prefix, $h_tab, $v_tab, $structure, $object, $box );

							?></div><?php

						}
					?>
					</div>
				</div>
				<?php
			}
		?>
	</div>

	<input type="hidden" id="ignition_current_active_tab" name="ignition_current_active_tab">
	<?php
}
