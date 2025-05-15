<?php
/**
 * Template part for displaying the site branding
 *
 * @since 1.0.0
 */
?>

<div class="site-branding">
	<?php
		$logo_url = home_url( '/' );

		if ( ( has_custom_logo() || get_theme_mod( 'site_identity_custom_logo_alt' ) ) && get_theme_mod( 'site_identity_title_is_visible', ignition_customizer_defaults( 'site_identity_title_is_visible' ) ) ) {
			the_custom_logo();

			?><div class="site-logo"><a href="<?php echo esc_url( $logo_url ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div><?php
		} elseif ( has_custom_logo() || get_theme_mod( 'site_identity_custom_logo_alt' ) ) {
			?><div class="site-logo"><?php the_custom_logo(); ?></div><?php
		} elseif ( get_theme_mod( 'site_identity_title_is_visible', ignition_customizer_defaults( 'site_identity_title_is_visible' ) ) ) {
			if ( is_customize_preview() ) {
				the_custom_logo();
			}
			?><div class="site-logo"><a href="<?php echo esc_url( $logo_url ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></div><?php
		}

		if ( get_theme_mod( 'site_identity_description_is_visible', ignition_customizer_defaults( 'site_identity_description_is_visible' ) ) ) {
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) {
				?><p class="site-tagline"><?php echo wp_kses_post( $description ); ?></p><?php
			}
		}
	?>
</div>
