<?php
/**
 * Template part for displaying the header's top bar
 *
 * @since 1.0.0
 */

if ( ! current_theme_supports( 'ignition-top-bar' ) ) {
	return;
}

$defaults = ignition_customizer_defaults( 'all' );

$is_visible = get_theme_mod( 'top_bar_layout_is_visible', $defaults['top_bar_layout_is_visible'] );

$content1 = get_theme_mod( 'top_bar_content_area1', $defaults['top_bar_content_area1'] );
$content2 = get_theme_mod( 'top_bar_content_area2', $defaults['top_bar_content_area2'] );
$content3 = get_theme_mod( 'top_bar_content_area3', $defaults['top_bar_content_area3'] );

/**
 * Hook: ignition_before_header_top_bar.
 *
 * @since 1.0.0
 *
 * @param bool $is_visible
 */
do_action( 'ignition_before_header_top_bar', $is_visible );

if ( $is_visible ) : ?>
	<div class="head-intro <?php echo esc_attr( ignition_get_devices_visibility_class( get_theme_mod( 'top_bar_layout_visibility', $defaults['top_bar_layout_visibility'] ) ) ); ?>">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="head-intro-inner">
						<?php if ( $content1 ) : ?>
							<div class="head-content-slot">
								<?php echo wp_kses( ignition_get_content_slot_string( $content1, 'head-content-slot-item' ), ignition_get_allowed_tags_search_form() ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $content2 ) : ?>
							<div class="head-content-slot head-content-slot-center">
								<?php echo wp_kses( ignition_get_content_slot_string( $content2, 'head-content-slot-item' ), ignition_get_allowed_tags_search_form() ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $content3 ) : ?>
							<div class="head-content-slot head-content-slot-end">
								<?php echo wp_kses( ignition_get_content_slot_string( $content3, 'head-content-slot-item' ), ignition_get_allowed_tags_search_form() ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif;

/**
 * Hook: ignition_after_header_top_bar.
 *
 * @since 1.0.0
 *
 * @param bool $is_visible
 */
do_action( 'ignition_after_header_top_bar', $is_visible );
