<?php
/**
 * Template part for displaying the footer info section
 *
 * @since 1.0.0
 */

$content1 = get_theme_mod( 'footer_content_area1', ignition_customizer_defaults( 'footer_content_area1' ) );
$content2 = get_theme_mod( 'footer_content_area2', ignition_customizer_defaults( 'footer_content_area2' ) );

$is_visible = $content1 || $content2;

/**
 * Hook: ignition_before_footer_info.
 *
 * @since 1.0.0
 *
 * @param bool $is_visible Whether the footer info section is visible.
 */
do_action( 'ignition_before_footer_info', $is_visible );

if ( $is_visible ) : ?>
	<div class="footer-info">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-12">
					<?php if ( get_theme_mod( 'footer_content_area1', ignition_customizer_defaults( 'footer_content_area1' ) ) ) : ?>
						<div class="footer-content-slot">
							<?php
								echo wp_kses(
									ignition_get_content_slot_string(
										get_theme_mod( 'footer_content_area1', ignition_customizer_defaults( 'footer_content_area1' ) ),
										'footer-content-slot-item'
									), ignition_get_allowed_tags_search_form()
								);
							?>
						</div>
					<?php endif; ?>
				</div>

				<div class="col-md-6 col-12">
					<?php if ( get_theme_mod( 'footer_content_area2', ignition_customizer_defaults( 'footer_content_area2' ) ) ) : ?>
						<div class="footer-content-slot footer-content-slot-end">
							<?php
								echo wp_kses(
									ignition_get_content_slot_string(
										get_theme_mod( 'footer_content_area2', ignition_customizer_defaults( 'footer_content_area2' ) ),
										'footer-content-slot-item'
									), ignition_get_allowed_tags_search_form()
								);
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;

/**
 * Hook: ignition_after_footer_info.
 *
 * @since 1.0.0
 *
 * @param bool $is_visible Whether the footer info section is visible.
 */
do_action( 'ignition_after_footer_info', $is_visible );
