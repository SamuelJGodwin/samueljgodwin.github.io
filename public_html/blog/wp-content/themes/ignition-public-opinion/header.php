<?php
/**
 * The template for displaying the header
 *
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

			
	
			<meta charset="<?php bloginfo( 'charset' ); ?>">
	
	<?php
	

	/**
	 * Hook: ignition_global_before.
	 *
	 * @since 1.0.0
	 */
	do_action( 'ignition_global_before' );
	?>

	<div class="page-wrap">

		<?php if ( ! function_exists( 'ignition_gsection_do_location' ) || ! ignition_gsection_do_location( 'header' ) ) {
			/**
			 * Hook: ignition_header.
			 *
			 * @since 1.0.0
			 *
			 * @hooked ignition_header - 10
			 */
			do_action( 'ignition_header' );
		} ?>

		<div class="site-content-wrap">
