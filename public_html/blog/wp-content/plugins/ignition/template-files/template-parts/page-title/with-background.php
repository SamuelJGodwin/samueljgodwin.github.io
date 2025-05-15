<?php
/**
 * Template part for displaying the page title with background
 *
 * @since 1.0.0
 */

$video_url      = ignition_page_title_get_data()['background_video'];
$video_disabled = ignition_page_title_get_data()['background_video_disabled'];
$video_info     = ignition_get_video_url_info( $video_url );
?>

<div class="<?php ignition_the_page_title_with_background_section_classes(); ?>">
	<div class="container">
		<div class="row <?php ignition_the_main_width_row_classes(); ?>">
			<div class="<?php ignition_the_main_width_classes(); ?>">
				<div class="page-hero-content">
					<?php
					/**
					 * Hook: ignition_the_page_title_with_background_elements hook.
					 *
					 * @since 1.0.0
					 *
					 * @hooked ignition_the_page_title_with_background_title - 10
					 * @hooked ignition_the_page_title_with_background_subtitle - 20
					 */
					do_action( 'ignition_the_page_title_with_background_elements' );
					?>
				</div>
			</div>
		</div>
	</div>

	<?php if ( $video_info['supported'] && ! $video_disabled ) : ?>
		<?php wp_enqueue_script( 'ignition-video-background' ); ?>

		<div class="page-hero-video-wrap">
			<div
				class="page-hero-video-background"
				data-video-id="<?php echo esc_attr( $video_info['video_id'] ); ?>"
				data-video-type="<?php echo esc_attr( $video_info['provider'] ); ?>"
				data-video-start="<?php echo esc_attr( $video_info['start_time'] ); ?>"
			>
				<div></div>
			</div>
		</div>
	<?php endif; ?>
</div>
