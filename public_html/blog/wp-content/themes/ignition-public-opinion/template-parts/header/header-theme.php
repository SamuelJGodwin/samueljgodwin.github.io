<?php
/**
 * Template part for displaying the Theme header layout
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

						<?php ignition_the_site_branding(); ?>

						<?php $header_content = get_theme_mod( 'header_content_area', ignition_customizer_defaults( 'header_content_area' ) ); ?>
						<div class="head-content-slot head-content-slot-end">
							<?php
								if ( $header_content ) :
									// We can't escape or kses here, since need to allow any shortcodes' output,
									// so that arbitrary shortcode output (especially ads) can be displayed.
									echo ignition_get_content_slot_string( // phpcs:ignore WordPress.Security.EscapeOutput
										$header_content,
										'head-content-slot-item'
									);
								endif;
							?>
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

	<div class="head-mast-navigation">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="head-mast-navigation-inner">
						<?php ignition_get_template_part( 'template-parts/header/navigation-main' ); ?>

						<div class="head-content-slot-mobile-nav">
							<?php ignition_get_template_part( 'template-parts/header/navigation-mobile-trigger' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
