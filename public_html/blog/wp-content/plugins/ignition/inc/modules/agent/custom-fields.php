<?php
/**
 * Agent custom fields functions and definitions
 *
 * @since 2.2.0
 */

add_action( 'admin_init', 'ignition_module_agent_setup_metabox' );
/**
 * Registers the Agent metabox.
 *
 * @since 2.2.0
 */
function ignition_module_agent_setup_metabox() {
	add_meta_box( 'ignition-single-agent', esc_html__( 'Agent Settings', 'ignition' ), 'ignition_module_agent_metabox', 'ignition-agent', 'normal', 'default' );

	add_action( 'save_post', 'ignition_module_agent_save_post' );
}

/**
 * Displays the "Agent Settings" metabox contents.
 *
 * @since 2.2.0
 *
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_module_agent_metabox( $object, $box ) {
	ignition_prepare_metabox( 'ignition-agent' );

	ignition_metabox_create_tabs( array(
		'details' => array(
			'title' => _x( 'Details', 'metabox tab title', 'ignition' ),
			'icon'  => 'dashicons dashicons-media-document',
			'tabs'  => array(
				'contact' => _x( 'Contact', 'metabox tab title', 'ignition' ),
				'social'  => _x( 'Social', 'metabox tab title', 'ignition' ),
			),
		),
	), 'agent', $object, $box );
}


/**
 * Stores the "Agent Settings" post meta.
 *
 * @since 2.2.0
 *
 * @param int $post_id
 */
function ignition_module_agent_save_post( $post_id ) {
	// Nonce verification is being done inside ignition_can_save_meta()
	// phpcs:disable WordPress.Security.NonceVerification
	if ( ! ignition_can_save_meta( get_post_type( $post_id ) ) ) {
		return;
	}

	// Contact
	if ( isset( $_POST['ignition_agent_telephone'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_telephone', sanitize_text_field( $_POST['ignition_agent_telephone'] ) );
	}

	if ( isset( $_POST['ignition_agent_cellphone'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_cellphone', sanitize_text_field( $_POST['ignition_agent_cellphone'] ) );
	}

	if ( isset( $_POST['ignition_agent_fax'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_fax', sanitize_text_field( $_POST['ignition_agent_fax'] ) );
	}

	if ( isset( $_POST['ignition_agent_email'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_email', sanitize_email( $_POST['ignition_agent_email'] ) );
	}

	if ( isset( $_POST['ignition_agent_url'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_url', esc_url_raw( $_POST['ignition_agent_url'] ) );
	}

	if ( isset( $_POST['ignition_agent_address'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_address', wp_kses_post( $_POST['ignition_agent_address'] ) );
	}

	if ( isset( $_POST['ignition_agent_contact_shortcode'] ) ) {
		update_post_meta( $post_id, 'ignition_agent_contact_shortcode', sanitize_text_field( $_POST['ignition_agent_contact_shortcode'] ) );
	}

	// Social
	if ( isset( $_POST['ignition_facebook_url'] ) ) {
		update_post_meta( $post_id, 'ignition_facebook_url', esc_url_raw( $_POST['ignition_facebook_url'] ) );
	}

	if ( isset( $_POST['ignition_twitter_url'] ) ) {
		update_post_meta( $post_id, 'ignition_twitter_url', esc_url_raw( $_POST['ignition_twitter_url'] ) );
	}

	if ( isset( $_POST['ignition_linkedin_url'] ) ) {
		update_post_meta( $post_id, 'ignition_linkedin_url', esc_url_raw( $_POST['ignition_linkedin_url'] ) );
	}


	// phpcs:enable
}

/**
 * Produces the "General" tab contents of the "Agent Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 2.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_agent_details_contact( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {

	ignition_main_metabox_input( 'ignition_agent_telephone', array(
		'title' => __( 'Landline phone number', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_agent_cellphone', array(
		'title' => __( 'Cell phone number', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_agent_fax', array(
		'title' => __( 'FAX number', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_agent_email', array(
		'title' => __( 'Email address', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_agent_url', array(
		'title' => __( 'Website / URL', 'ignition' ),
	) );

	ignition_main_metabox_textarea( 'ignition_agent_address', array(
		'title' => __( 'Address', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_agent_contact_shortcode', array(
		'title'       => __( 'Contact form shortcode', 'ignition' ),
		'description' => __( 'Pasting the shortcode of a dedicated contact form for the specific agent, allows visitors to communicate directly with the agent.', 'ignition' ),
	) );
}

/**
 * Produces the "Address" tab contents of the "Agent Settings" metabox.
 *
 * Automatically hooked by ignition_metabox_create() to 'ignition_metabox_display_tab_{$prefix}_{$h_tab}_{$v_tab}'
 *
 * @since 2.2.0
 *
 * @param string  $prefix
 * @param string  $horizontal_tab
 * @param string  $vertical_tab
 * @param array   $structure
 * @param WP_Post $object
 * @param array   $box
 */
function ignition_metabox_display_tab_agent_details_social( $prefix, $horizontal_tab, $vertical_tab, $structure, $object, $box ) {
	ignition_main_metabox_input( 'ignition_facebook_url', array(
		'title' => __( 'Facebook URL', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_twitter_url', array(
		'title' => __( 'Twitter URL', 'ignition' ),
	) );

	ignition_main_metabox_input( 'ignition_linkedin_url', array(
		'title' => __( 'LinkedIn URL', 'ignition' ),
	) );
}
