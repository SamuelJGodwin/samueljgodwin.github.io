<?php
/**
 * Customizer section options: Blog
 *
 * @since 1.0.0
 */

/** @var WP_Customize_Manager $wp_customize Reference to the customizer's manager object. */
$wp_customize->add_section( 'blog', array(
	'title'    => esc_html_x( 'Blog', 'customizer section title', 'ignition' ),
	'priority' => 5,
) );

// This call is expensive, so make sure we don't call it unnecessarily.
$customizer_options = ignition_customizer_options( 'all' );

//
// Archives
//
$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'blog_archives_sub', array(
	'section'  => 'blog',
	'priority' => 10,
	'settings' => array(),
	'label'    => esc_html__( '▸ Archives', 'ignition' ),
) ) );

if ( count( ignition_blog_archive_posts_layout_type_choices() ) > 1 ) {
	$args = $customizer_options['blog_archive_posts_layout_type'];
	$wp_customize->add_setting( 'blog_archive_posts_layout_type', $args['setting_args'] );
	$wp_customize->add_control( 'blog_archive_posts_layout_type', $args['control_args'] );
}

$args = $customizer_options['blog_archive_excerpt_length'];
$wp_customize->add_setting( 'blog_archive_excerpt_length', $args['setting_args'] );
$wp_customize->add_control( 'blog_archive_excerpt_length', $args['control_args'] );

$args = $customizer_options['blog_archive_meta_date_is_visible'];
$wp_customize->add_setting( 'blog_archive_meta_date_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_archive_meta_date_is_visible', $args['control_args'] );

$args = $customizer_options['blog_archive_meta_categories_is_visible'];
$wp_customize->add_setting( 'blog_archive_meta_categories_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_archive_meta_categories_is_visible', $args['control_args'] );

$args = $customizer_options['blog_archive_meta_author_is_visible'];
$wp_customize->add_setting( 'blog_archive_meta_author_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_archive_meta_author_is_visible', $args['control_args'] );

$args = $customizer_options['blog_archive_meta_comments_is_visible'];
$wp_customize->add_setting( 'blog_archive_meta_comments_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_archive_meta_comments_is_visible', $args['control_args'] );


//
// Singles
//
$wp_customize->add_control( new Ignition_Customize_Section_Header( $wp_customize, 'blog_single_post_sub', array(
	'section'  => 'blog',
	'priority' => 100,
	'settings' => array(),
	'label'    => esc_html__( '▸ Single Post', 'ignition' ),
) ) );

$args = $customizer_options['blog_single_meta_date_is_visible'];
$wp_customize->add_setting( 'blog_single_meta_date_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_single_meta_date_is_visible', $args['control_args'] );

$args = $customizer_options['blog_single_meta_categories_is_visible'];
$wp_customize->add_setting( 'blog_single_meta_categories_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_single_meta_categories_is_visible', $args['control_args'] );

$args = $customizer_options['blog_single_meta_author_is_visible'];
$wp_customize->add_setting( 'blog_single_meta_author_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_single_meta_author_is_visible', $args['control_args'] );

$args = $customizer_options['blog_single_meta_comments_is_visible'];
$wp_customize->add_setting( 'blog_single_meta_comments_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_single_meta_comments_is_visible', $args['control_args'] );

$args = $customizer_options['blog_single_authorbox_is_visible'];
$wp_customize->add_setting( 'blog_single_authorbox_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_single_authorbox_is_visible', $args['control_args'] );

$args = $customizer_options['blog_single_comments_is_visible'];
$wp_customize->add_setting( 'blog_single_comments_is_visible', $args['setting_args'] );
$wp_customize->add_control( 'blog_single_comments_is_visible', $args['control_args'] );

if ( get_post_types_by_support( 'ignition-related' ) ) {
	$args = $customizer_options['blog_single_related_columns'];
	$wp_customize->add_setting( 'blog_single_related_columns', $args['setting_args'] );
	$wp_customize->add_control( 'blog_single_related_columns', $args['control_args'] );
}
