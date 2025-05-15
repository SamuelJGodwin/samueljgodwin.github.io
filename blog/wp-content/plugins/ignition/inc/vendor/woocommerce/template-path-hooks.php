<?php
/**
 * WooCommerce related hooks and functions, so that WooCommerce will load Ignition-included templates
 *
 * @since 1.0.0
 */

add_action( 'init', 'ignition_woocommerce_replace_template_loader', 20 );
/**
 * Registers hooks that affect WooCommerce's template loading.
 *
 * @since 1.0.0
 */
function ignition_woocommerce_replace_template_loader() {
	remove_filter( 'template_include', array( 'WC_Template_Loader', 'template_loader' ) );
	add_filter( 'template_include', 'ignition_woocommerce_template_loader' );
}

/**
 * Load a template.
 *
 * Handles template usage so that we can use our own templates instead of the theme's.
 *
 * Templates are in the 'templates' folder. WooCommerce looks for theme
 * overrides in /theme/woocommerce/ by default.
 *
 * For beginners, it also looks for a woocommerce.php template first. If the user adds
 * this to the theme (containing a woocommerce() inside) this will be used for all
 * WooCommerce templates.
 *
 * This function is a copy of WC_Template_Loader::template_loader() v5.2.0 with Ignition calls substituted.
 *
 * @since 1.0.0
 *
 * @param string $template Template to load.
 *
 * @return string
 */
function ignition_woocommerce_template_loader( $template ) {
	if ( is_embed() ) {
		return $template;
	}

	$default_file = ignition_woocommerce_get_template_loader_default_file();

	if ( $default_file ) {
		/**
		 * Filter hook to choose which files to find before WooCommerce does it's own logic.
		 *
		 * @since 3.0.0
		 * @var array
		 */
		$search_files = ignition_woocommerce_get_template_loader_files( $default_file );
		$template     = ignition_locate_template( $search_files );

		if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
			if ( false !== strpos( $default_file, 'product_cat' ) || false !== strpos( $default_file, 'product_tag' ) ) {
				$cs_template = str_replace( '_', '-', $default_file );
				$template    = WC()->plugin_path() . '/templates/' . $cs_template;
			} else {
				$template = WC()->plugin_path() . '/templates/' . $default_file;
			}
		}
	}

	return $template;
}

/**
 * Get the default filename for a template.
 *
 * This function is a copy of WC_Template_Loader::get_template_loader_default_file() with Ignition calls substituted.
 *
 * @since 1.0.0
 *
 * @return string
 */
function ignition_woocommerce_get_template_loader_default_file() {
	if ( is_singular( 'product' ) ) {
		$default_file = 'single-product.php';
	} elseif ( is_product_taxonomy() ) {
		$object = get_queried_object();

		if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
			$default_file = 'taxonomy-' . $object->taxonomy . '.php';
		} else {
			$default_file = 'archive-product.php';
		}
	} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
		$default_file = current_theme_supports( 'woocommerce' ) ? 'archive-product.php' : '';
	} else {
		$default_file = '';
	}
	return $default_file;
}

/**
 * Get an array of filenames to search for a given template.
 *
 * This function is a copy of WC_Template_Loader::get_template_loader_files() v5.2.0 since the original can't be used
 * as it's marked as private.
 *
 * @since 1.0.0
 *
 * @param  string $default_file The default file name.
 *
 * @return string[]
 */
function ignition_woocommerce_get_template_loader_files( $default_file ) {
	$templates   = apply_filters( 'woocommerce_template_loader_files', array(), $default_file );
	$templates[] = 'woocommerce.php';

	if ( is_page_template() ) {
		$page_template = get_page_template_slug();

		if ( $page_template ) {
			$validated_file = validate_file( $page_template );
			if ( 0 === $validated_file ) {
				$templates[] = $page_template;
			} else {
				error_log( "WooCommerce: Unable to validate template path: \"$page_template\". Error Code: $validated_file." ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
		}
	}

	if ( is_singular( 'product' ) ) {
		$object       = get_queried_object();
		$name_decoded = urldecode( $object->post_name );
		if ( $name_decoded !== $object->post_name ) {
			$templates[] = "single-product-{$name_decoded}.php";
		}
		$templates[] = "single-product-{$object->post_name}.php";
	}

	if ( is_product_taxonomy() ) {
		$object = get_queried_object();

		$templates[] = 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
		$templates[] = WC()->template_path() . 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
		$templates[] = 'taxonomy-' . $object->taxonomy . '.php';
		$templates[] = WC()->template_path() . 'taxonomy-' . $object->taxonomy . '.php';

		if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
			$cs_taxonomy = str_replace( '_', '-', $object->taxonomy );
			$cs_default  = str_replace( '_', '-', $default_file );
			$templates[] = 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
			$templates[] = WC()->template_path() . 'taxonomy-' . $cs_taxonomy . '-' . $object->slug . '.php';
			$templates[] = 'taxonomy-' . $object->taxonomy . '.php';
			$templates[] = WC()->template_path() . 'taxonomy-' . $cs_taxonomy . '.php';
			$templates[] = $cs_default;
		}
	}

	$templates[] = $default_file;
	if ( isset( $cs_default ) ) {
		$templates[] = WC()->template_path() . $cs_default;
	}
	$templates[] = WC()->template_path() . $default_file;

	return array_unique( $templates );
}

add_filter( 'woocommerce_locate_template', 'ignition_woocommerce_filter_woocommerce_locate_template', 10, 3 );
/**
 * Filters the name of the highest priority template file that exists.
 *
 * @since 1.0.0
 *
 * @param $template
 * @param $template_name
 * @param $template_path
 *
 * @return string
 */
function ignition_woocommerce_filter_woocommerce_locate_template( $template, $template_name, $template_path ) {
	$ignition_template = ignition_locate_template( array(
		trailingslashit( $template_path ) . $template_name,
		$template_name,
	) );

	if ( $ignition_template ) {
		$template = $ignition_template;
	}

	return $template;
}

add_filter( 'wc_get_template_part', 'ignition_woocommerce_filter_wc_get_template_part', 10, 3 );
/**
 * Filters the path of the WooCommerce template part file to load.
 *
 * @since 1.0.0
 *
 * @param $template
 * @param $slug
 * @param $name
 *
 * @return string
 */
function ignition_woocommerce_filter_wc_get_template_part( $template, $slug, $name ) {
	$ignition_template = ignition_locate_template( array(
		"{$slug}-{$name}.php",
		WC()->template_path() . "{$slug}-{$name}.php",
	) );

	if ( $ignition_template ) {
		$template = $ignition_template;
	}

	return $template;
}
