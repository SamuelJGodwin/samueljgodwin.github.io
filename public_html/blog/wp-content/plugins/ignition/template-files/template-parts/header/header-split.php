<?php
/**
 * Template part for displaying the split header layout
 *
 * @since 1.0.0
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

		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="head-mast-inner">
						<div class="head-slot head-slot-left">
							<div class="head-menu-slot">
								<?php
									wp_nav_menu( array(
										'theme_location'  => 'menu-2',
										'fallback_cb'     => 'ignition_secondary_menu_fallback',
										'menu_id'         => 'header-menu-2',
										'menu_class'      => 'navigation-main',
										'container'       => 'nav',
										'container_class' => 'nav',
									) );
								?>
							</div>
						</div>

						<?php ignition_the_site_branding(); ?>

						<div class="head-slot head-slot-right">
							<div class="head-menu-slot head-menu-slot-right">
								<?php ignition_get_template_part( 'template-parts/header/navigation-main' ); ?>
							</div>

							<?php $header_content = get_theme_mod( 'header_content_area', ignition_customizer_defaults( 'header_content_area' ) ); ?>
							<div class="head-content-slot head-content-slot-end">
								<?php
									if ( $header_content ) :
										echo wp_kses(
											ignition_get_content_slot_string(
												$header_content,
												'head-content-slot-item'
											), ignition_get_allowed_tags_search_form()
										);
									endif;
								?>

								<div class="head-content-slot-item head-content-slot-mobile-nav">
									<?php ignition_get_template_part( 'template-parts/header/navigation-mobile-trigger' ); ?>
								</div>
							</div>
						</div>
					</div>
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
