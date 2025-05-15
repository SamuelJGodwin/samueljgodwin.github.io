<?php
/**
 * Template part for displaying the side header layout
 *
 * @since 2.1.0
 */
?>

<header class="<?php ignition_the_header_classes(); ?>">

	<?php ignition_get_template_part( 'template-parts/header/top-bar' ); ?>

	<div class="head-mast">

		<?php
		/**
		 * Hook: ignition_head_mast_before.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ignition_head_mast_before' );
		?>

		<div class="head-mast-inner">

			<?php ignition_the_site_branding(); ?>

			<div class="head-menu-slot">
				<?php ignition_get_template_part( 'template-parts/header/navigation-main' ); ?>
			</div>

			<div class="head-content-slot head-content-slot-end">
				<?php
					$header_content = get_theme_mod( 'side_mode_header_mobile_content_area', ignition_customizer_defaults( 'side_mode_header_mobile_content_area' ) );
					if ( $header_content ) :
						echo wp_kses(
							ignition_get_content_slot_string(
								$header_content,
								'head-content-slot-item head-content-slot-mobile-content'
							), ignition_get_allowed_tags_search_form()
						);
					endif;
				?>

				<div class="head-content-slot-item head-content-slot-mobile-nav">
					<?php ignition_get_template_part( 'template-parts/header/navigation-mobile-trigger' ); ?>
				</div>
			</div>

		</div>

		<?php
		/**
		 * Hook: ignition_head_mast_after.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ignition_head_mast_after' );
		?>

	</div>
</header>
