<?php
/**
 * Global Section custom fields functions and definitions
 *
 * @since 1.2.0
 */

add_action( 'admin_init', 'ignition_module_gsection_setup_metabox' );
/**
 * Registers the Global Section metabox.
 *
 * @since 1.2.0
 */
function ignition_module_gsection_setup_metabox() {
	add_meta_box( 'ignition-single-gsection', esc_html__( 'Global Section Settings', 'ignition' ), 'ignition_module_gsection_metabox', 'ignition-gsection', 'normal', 'default' );

	add_action( 'save_post', 'ignition_module_gsection_save_post' );
}

/**
 * Displays the "Global Section Settings" metabox contents.
 *
 * @since 1.2.0
 *
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_module_gsection_metabox( $object, $box ) {
	ignition_prepare_metabox( 'ignition-gsection' );

	ignition_metabox_create_tabs( array(
		'display' => array(
			'title' => _x( 'Display', 'metabox tab title', 'ignition' ),
			'icon'  => 'dashicons dashicons-media-document',
			'tabs'  => array(
				'conditions' => _x( 'Conditions', 'metabox tab title', 'ignition' ),
				'shortcode'  => _x( 'Shortcode', 'metabox tab title', 'ignition' ),
			),
		),
	), 'global_section', $object, $box );
}


/**
 * Stores the "Global Section Settings" post meta.
 *
 * @since 1.2.0
 *
 * @param int $post_id
 */
function ignition_module_gsection_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	if ( isset( $_POST['locations'] ) ) {
		$locations = ignition_module_gsection_sanitize_repeating_locations( $_POST['locations'] );
		update_post_meta( $post_id, 'ignition_gsection_locations', $locations );
	}

	if ( isset( $_POST['includes'] ) ) {
		$includes = ignition_module_gsection_sanitize_repeating_includes_excludes( $_POST['includes'] );
		update_post_meta( $post_id, 'ignition_gsection_includes', $includes );
	}

	if ( isset( $_POST['excludes'] ) ) {
		$excludes = ignition_module_gsection_sanitize_repeating_includes_excludes( $_POST['excludes'] );
		update_post_meta( $post_id, 'ignition_gsection_excludes', $excludes );
	}

	// phpcs:enable
}

/**
 * Produces the "Conditions" tab contents of the "Global Section Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 1.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_global_section_display_conditions( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {
	$blog_url = get_post_type_archive_link( 'post' );

	$hooks_query_arg = 'show_hooks';

	$hooks_url = add_query_arg( array(
		$hooks_query_arg => '',
	), $blog_url );
	ignition_metabox_separator( array(
		/* translators: %1$s is a URL. %2$s is an example query parameter prepended by a question mark, e.g.: ?show_hooks */
		'description' => wp_kses( sprintf( __( 'You can see a visual map of the available blog\'s hooks <a href="%1$s" target="_blank">here</a>. You may also append <code>%2$s</code> in any URL to see that page\'s hooks.', 'ignition' ),
			esc_url( $hooks_url ),
			'?' . $hooks_query_arg
		), ignition_get_allowed_tags( 'guide' ) ),
	) );


	$locations = get_post_meta( $object->ID, 'ignition_gsection_locations', true );
	$includes  = get_post_meta( $object->ID, 'ignition_gsection_includes', true );
	$excludes  = get_post_meta( $object->ID, 'ignition_gsection_excludes', true );

	$locations = ! empty( $locations ) ? $locations : array();
	$includes  = ! empty( $includes ) ? $includes : array();
	$excludes  = ! empty( $excludes ) ? $excludes : array();

	?>
	<div class="ignition-repeatable-wrap ignition-hooks-wrap ignition-hooks-locations-wrap">
		<div class="ignition-hooks-container">
			<div class="ignition-hooks-label">
				<?php esc_html_e( 'Locations', 'ignition' ); ?>
			</div>

			<div class="ignition-repeatable-fields ignition-hooks-selections">
				<?php foreach ( $locations as $location ) : ?>
					<?php
						$uuid               = uniqid();
						$fieldname_name     = "locations[name][{$uuid}]";
						$fieldname_hook     = "locations[hook][{$uuid}]";
						$fieldname_priority = "locations[priority][{$uuid}]";

						$hook_input_style = '';
						if ( 'custom' !== $location['hook'] ) {
							$hook_input_style = 'display: none;';
						}
					?>
					<div class="ignition-repeatable-row ignition-hook-row">
						<div class="ignition-hook-select">
							<label for="location-name-<?php echo esc_attr( $uuid ); ?>"><?php esc_html_e( 'Location name', 'ignition' ); ?></label>
							<select class="ignition-hook-select-location" name="<?php echo esc_attr( $fieldname_name ); ?>" id="location-name-<?php echo esc_attr( $uuid ); ?>">
								<?php echo ignition_module_gsection_visible_hooks_get_dropdown_options( $location['hook'] ); ?>
							</select>
						</div>

						<div class="ignition-hook-input-hook" style="<?php echo esc_attr( $hook_input_style ); ?>">
							<label for="location-hook-<?php echo esc_attr( $uuid ); ?>"><?php esc_html_e( 'Hook name', 'ignition' ); ?></label>
							<input class="ignition-hook-select-entry" name="<?php echo esc_attr( $fieldname_hook ); ?>" id="location-hook-<?php echo esc_attr( $uuid ); ?>" value="<?php echo esc_attr( $location['hook'] ); ?>" />
						</div>

						<div class="ignition-hook-input-priority">
							<label for="location-priority-<?php echo esc_attr( $uuid ); ?>"><?php esc_html_e( 'Priority', 'ignition' ); ?></label>
							<input type="number" name="<?php echo esc_attr( $fieldname_priority ); ?>" id="location-priority-<?php echo esc_attr( $uuid ); ?>" value="<?php echo esc_attr( $location['priority'] ); ?>" placeholder="10">
						</div>

						<button class="ignition-repeatable-row-dismiss ignition-hook-dismiss">
							<span class="dashicons dashicons-no-alt"></span><span class="screen-reader-text"><?php esc_html_e( 'Remove location', 'ignition' ); ?></span>
						</button>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<button type="button" class="ignition-repeatable-add-button button button-small">
			<?php esc_html_e( 'Add location', 'ignition' ); ?>
		</button>

		<div class="ignition-repeatable-row ignition-repeatable-template ignition-hook-row">
			<div class="ignition-hook-select">
				<label for="location-name-__id__"><?php esc_html_e( 'Location name', 'ignition' ); ?></label>
				<select class="ignition-hook-select-location" name="locations[name][__id__]" id="location-name-__id__">
					<?php echo ignition_module_gsection_visible_hooks_get_dropdown_options(); ?>
				</select>
			</div>

			<div class="ignition-hook-input-hook" style="display: none;">
				<label for="location-hook-__id__"><?php esc_html_e( 'Hook name', 'ignition' ); ?></label>
				<input class="ignition-hook-select-entry" name="locations[hook][__id__]" id="location-hook-__id__" />
			</div>

			<div class="ignition-hook-input-priority">
				<label for="location-priority-__id__"><?php esc_html_e( 'Priority', 'ignition' ); ?></label>
				<input type="number" name="locations[priority][__id__]" id="location-priority-__id__" placeholder="10">
			</div>

			<button class="ignition-repeatable-row-dismiss ignition-hook-dismiss">
				<span class="dashicons dashicons-no-alt"></span><span class="screen-reader-text"><?php esc_html_e( 'Remove location', 'ignition' ); ?></span>
			</button>
		</div>
	</div>



	<div class="ignition-repeatable-wrap ignition-hooks-wrap ignition-hooks-inclusions-wrap">
		<div class="ignition-hooks-container">
			<div class="ignition-hooks-label">
				<?php esc_html_e( 'Inclusions', 'ignition' ); ?>
			</div>

			<div class="ignition-repeatable-fields ignition-hooks-selections">
				<?php
					foreach ( $includes as $rule ) {
						ignition_module_gsection_metabox_render_repeatable_rule_row( $rule, 'includes' );
					}
				?>
			</div>
		</div>

		<button type="button" class="ignition-repeatable-add-button button button-small">
			<?php esc_html_e( 'Add inclusion rule', 'ignition' ); ?>
		</button>

		<div class="ignition-repeatable-row ignition-repeatable-template ignition-hook-row">
			<div class="ignition-hook-select">
				<label for="hook-type-__id__" class="screen-reader-text"><?php esc_html_e( 'Select rule type', 'ignition' ); ?></label>
				<select class="ignition-hook-select-type" name="includes[type][__id__]" id="hook-type-__id__">
					<?php echo ignition_module_gsection_rules_dropdown_options(); ?>
				</select>
			</div>

			<div class="ignition-hook-select" style="display: none;">
				<label for="hook-subtype-__id__" class="screen-reader-text"><?php esc_html_e( 'Refine rule type', 'ignition' ); ?></label>
				<select class="ignition-hook-select-entry" name="includes[subtype][__id__]" id="hook-subtype-__id__"></select>
			</div>

			<button type="button" class="ignition-repeatable-row-dismiss ignition-hook-dismiss">
				<span class="dashicons dashicons-no-alt"></span>
			</button>
		</div>
	</div>



	<div class="ignition-repeatable-wrap ignition-hooks-wrap ignition-hooks-exclusions-wrap">
		<div class="ignition-hooks-container">
			<div class="ignition-hooks-label">
				<?php esc_html_e( 'Exclusions', 'ignition' ); ?>
			</div>

			<div class="ignition-repeatable-fields ignition-hooks-selections">
				<?php
					foreach ( $excludes as $rule ) {
						ignition_module_gsection_metabox_render_repeatable_rule_row( $rule, 'excludes' );
					}
				?>
			</div>
		</div>

		<button type="button" class="ignition-repeatable-add-button button button-small">
			<?php esc_html_e( 'Add exclusion rule', 'ignition' ); ?>
		</button>

		<div class="ignition-repeatable-row ignition-repeatable-template ignition-hook-row">
			<div class="ignition-hook-select">
				<label class="screen-reader-text"><?php esc_html_e( 'Select rule type', 'ignition' ); ?></label>
				<select name="excludes[type][__id__]" class="ignition-hook-select-type">
					<?php echo ignition_module_gsection_rules_dropdown_options(); ?>
				</select>
			</div>

			<div class="ignition-hook-select" style="display: none;">
				<label class="screen-reader-text"><?php esc_html_e( 'Refine rule type', 'ignition' ); ?></label>
				<select name="excludes[subtype][__id__]" class="ignition-hook-select-entry"></select>
			</div>

			<button type="button" class="ignition-repeatable-row-dismiss ignition-hook-dismiss">
				<span class="dashicons dashicons-no-alt"></span>
			</button>
		</div>
	</div>
	<?php
}

/**
 * Produces the metabox markup for a single repeatable inclusion/exclusion rule.
 *
 * @since 1.2.0
 *
 * @param array  $rule
 * @param string $fieldname
 */
function ignition_module_gsection_metabox_render_repeatable_rule_row( $rule, $fieldname ) {
	$uuid          = uniqid();
	$options_array = ignition_module_gsection_get_rules_array();
	$option        = wp_list_filter( $options_array, array( 'option_value' => $rule['type'] ) );
	if ( count( $option ) >= 1 ) {
		$option = reset( $option );
	}

	$subtype_style = ! isset( $option['entries'] ) ? 'display: none;' : '';

	$fieldname_type    = "{$fieldname}[type][{$uuid}]";
	$fieldname_subtype = "{$fieldname}[subtype][{$uuid}]";

	?>
	<div class="ignition-repeatable-row ignition-hook-row">
		<div class="ignition-hook-select">
			<label for="hook-type-<?php echo esc_attr( $uuid ); ?>" class="screen-reader-text"><?php esc_html_e( 'Select rule type', 'ignition' ); ?></label>
			<select class="ignition-hook-select-type" name="<?php echo esc_attr( $fieldname_type ); ?>" id="hook-type-<?php echo esc_attr( $uuid ); ?>">
				<?php echo ignition_module_gsection_rules_dropdown_options( $rule['type'] ); ?>
			</select>
		</div>

		<div class="ignition-hook-select" style="<?php echo esc_attr( $subtype_style ); ?>">
			<label for="hook-subtype-<?php echo esc_attr( $uuid ); ?>" class="screen-reader-text"><?php esc_html_e( 'Refine rule type', 'ignition' ); ?></label>

			<select class="ignition-hook-select-entry" name="<?php echo esc_attr( $fieldname_subtype ); ?>" id="hook-subtype-<?php echo esc_attr( $uuid ); ?>">
				<?php if ( isset( $option['entries'] ) ) : ?>
					<?php foreach ( $option['entries'] as $entry ) : ?>
						<option value="<?php echo esc_attr( $entry['option_value'] ); ?>" <?php selected( $entry['option_value'], $rule['subtype'] ); ?>><?php echo wp_kses( $entry['name'], 'strip' ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>

		<button class="ignition-repeatable-row-dismiss ignition-hook-dismiss">
			<span class="dashicons dashicons-no-alt"></span>
		</button>
	</div>
	<?php
}

/**
 * Produces the "Shortcode" tab contents of the "Global Section Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 1.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_global_section_display_shortcode( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {

	$fieldname = 'ignition_global_section_shortcode';
	$params    = array(
		'title'       => __( 'Shortcode', 'ignition' ),
		'description' => __( "To display this section's content, copy this shortcode and paste it in any place of your website that supports shortcodes.", 'ignition' ),
	);
	?>
	<div class="ignition-setting-wrap">
		<?php if ( $params['title'] || $params['description'] ) : ?>
			<label class="ignition-setting-labels" for="<?php echo esc_attr( $fieldname ); ?>">
				<?php if ( $params['title'] ) : ?>
					<span class="ignition-setting-label"><?php echo esc_html( $params['title'] ); ?></span>
				<?php endif; ?>
				<?php if ( $params['description'] ) : ?>
					<span class="ignition-setting-description"><?php echo wp_kses( $params['description'], ignition_get_allowed_tags() ); ?></span>
				<?php endif; ?>
			</label>
		<?php endif; ?>

		<div class="ignition-setting-control">
			<input
				type="text"
				class="widefat code"
				id="<?php echo esc_attr( $fieldname ); ?>"
				name="<?php echo esc_attr( $fieldname ); ?>"
				value="<?php echo esc_attr( sprintf( '[global-section id="%s"]', $object->ID ) ); ?>"
			/>
		</div>
	</div>
	<?php

	$fieldname = 'ignition_global_section_shortcode_slug';
	$params    = array(
		'title'       => __( 'Shortcode', 'ignition' ),
		'description' => __( 'Alternatively, you can use this shortcode form as it helps you better understand what is being displayed.', 'ignition' ),
	);
	?>
	<div class="ignition-setting-wrap">
		<?php if ( $params['title'] || $params['description'] ) : ?>
			<label class="ignition-setting-labels" for="<?php echo esc_attr( $fieldname ); ?>">
				<?php if ( $params['title'] ) : ?>
					<span class="ignition-setting-label"><?php echo esc_html( $params['title'] ); ?></span>
				<?php endif; ?>
				<?php if ( $params['description'] ) : ?>
					<span class="ignition-setting-description"><?php echo wp_kses( $params['description'], ignition_get_allowed_tags() ); ?></span>
				<?php endif; ?>
			</label>
		<?php endif; ?>

		<div class="ignition-setting-control">
			<?php if ( empty( $object->post_name ) ) : ?>
				<p><?php esc_html_e( 'You need to set a title and save this Global Section before you get the slug-based shortcode.', 'ignition' ); ?></p>
			<?php else : ?>
				<input
					type="text"
					class="widefat code"
					value="<?php echo esc_attr( sprintf( '[global-section slug="%s"]', $object->post_name ) ); ?>"
				/>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Sanitizes the repeatable location entries of a Global Section.
 *
 * @see ignition_module_gsection_visible_hooks_get()
 *
 * @since 1.2.0
 *
 * @param array $set {
 *     Required. Elements with the same key belong to the same location entry.
 *
 *     @type string[] name     The name of a predefined location, a.k.a. visible hook.
 *     @type string[] hook     A custom action hook's name.
 *     @type string[] priority The priority on which the gsection should be hooked on the selected location/hook.
 * }
 *
 * @return array {
 *     @type array[] {
 *         @type string name     The name of a predefined location, a.k.a. visible hook.
 *         @type string hook     A custom action hook's name.
 *         @type int    priority The priority on which the gsection should be hooked on the selected location/hook.
 *     }
 * }
 */
function ignition_module_gsection_sanitize_repeating_locations( $set ) {
	$new_set = array();

	$set['name']     = ! empty( $set['name'] ) ? $set['name'] : array();
	$set['hook']     = ! empty( $set['hook'] ) ? $set['hook'] : array();
	$set['priority'] = ! empty( $set['priority'] ) ? $set['priority'] : array();

	foreach ( $set['name'] as $id => $name ) {
		if ( empty( $name ) ) {
			continue;
		}

		$name = sanitize_text_field( $name );
		$hook = $name;

		if ( 'custom' === $name && array_key_exists( $id, $set['hook'] ) ) {
			$hook = sanitize_text_field( $set['hook'][ $id ] );
		}

		if ( empty( $hook ) || 'custom' === $hook ) {
			continue;
		}

		$default_priority = 10;
		$priority         = $default_priority;
		if ( array_key_exists( $id, $set['priority'] ) ) {
			$priority = $set['priority'][ $id ];
			$priority = is_numeric( $priority ) ? intval( $priority ) : $default_priority;
		}

		$new_set[] = array(
			'name'     => $name,
			'hook'     => $hook,
			'priority' => $priority,
		);
	}

	return $new_set;
}

/**
 * Sanitizes the repeatable include/exclude conditions of a Global Section.
 *
 * @see ignition_module_gsection_get_rules_array()
 *
 * @since 1.2.0
 *
 * @param array $set {
 *     Required. Elements with the same key belong to the same include/excude entry.
 *
 *     @type string[] type    The type of the condition, e.g. 'blog', 'archive'.
 *                            May contain subtype information after the first dash.
 *                            E.g. 'archive-date', 'archive-author'.
 *     @type string[] subtype Subtype constraint for the type selected. May contain numeric values.
 *                            E.g. 'archive-date' => 'month', 'archive-author' => '3'.
 * }
 *
 * @return array {
 *     @type array[] {
 *         @type string type
 *         @type int|string subtype
 *     }
 * }
 */
function ignition_module_gsection_sanitize_repeating_includes_excludes( $set ) {
	$new_set = array();

	$set['type']    = ! empty( $set['type'] ) ? $set['type'] : array();
	$set['subtype'] = ! empty( $set['subtype'] ) ? $set['subtype'] : array();

	foreach ( $set['type'] as $id => $type ) {
		if ( empty( $type ) ) {
			continue;
		}

		$subtype = false;
		if ( array_key_exists( $id, $set['subtype'] ) ) {
			$subtype = $set['subtype'][ $id ];
		}

		$type    = sanitize_text_field( $type );
		$subtype = is_numeric( $subtype ) ? intval( $subtype ) : sanitize_text_field( $subtype );

		$new_set[] = array(
			'type'    => $type,
			'subtype' => $subtype,
		);
	}

	return $new_set;
}
