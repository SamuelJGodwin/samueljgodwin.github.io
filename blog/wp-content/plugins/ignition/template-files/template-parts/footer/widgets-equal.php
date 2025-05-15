<?php
/**
 * Template part for displaying the footer widgets' equal layout
 *
 * @since 1.8.0
 */

/** @var array $args */
?>
<div class="footer-widgets">
	<div class="container">
		<div class="row">
			<?php for ( $i = 1; $i <= $args['layout_info']['sidebars']; $i++ ) : ?>
				<div class="<?php echo esc_attr( ignition_get_columns_classes( $args['layout_info']['sidebars'], 'footer-widgets' ) ); ?>">
					<?php if ( is_active_sidebar( "footer-{$i}" ) ) : ?>
						<?php dynamic_sidebar( "footer-{$i}" ); ?>
					<?php elseif ( is_customize_preview() ) : ?>
						<div class="widget widget_text group">
							<?php /* translators: %d is a number in the range 1-4 */ ?>
							<p><?php echo wp_kses( sprintf( __( 'To display widgets it this position, assign them to the "Footer - %d" widget area.', 'ignition' ), $i ), ignition_get_allowed_tags( 'guide' ) ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</div>
