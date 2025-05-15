<?php
/**
 * Template part for displaying the normal page title
 *
 * @since 1.0.0
 */
?>

<div class="<?php ignition_the_normal_page_title_section_classes(); ?>">
	<div class="container">
		<div class="row <?php ignition_the_main_width_row_classes(); ?>">
			<div class="<?php ignition_the_main_width_classes(); ?>">
				<div class="page-title-content">
					<?php
					/**
					 * Hook: ignition_the_normal_page_title_elements hook.
					 *
					 * @since 1.0.0
					 *
					 * @hooked ignition_the_normal_page_title_title - 10
					 * @hooked ignition_the_normal_page_title_subtitle - 20
					 */
					do_action( 'ignition_the_normal_page_title_elements' );
					?>
				</div>
			</div>
		</div>
	</div>
</div>
