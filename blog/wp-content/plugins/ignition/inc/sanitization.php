<?php
/**
 * Sanitization functions
 *
 * @since 1.0.0
 */

/**
 * Sanitizes integer input while differentiating zero from empty string.
 *
 * @since 1.0.0
 *
 * @param mixed $input Input value to sanitize.
 *
 * @return int|string Integer value, 0, or an empty string otherwise.
 */
function ignition_sanitize_intval_or_empty( $input ) {
	if ( false === $input || '' === $input || is_null( $input ) ) {
		return '';
	}

	if ( 0 === intval( $input ) ) {
		return 0;
	}

	return intval( $input );
}

/**
 * Sanitizes float input while differentiating zero from empty string.
 *
 * @since 1.0.0
 *
 * @param mixed $input Input value to sanitize.
 *
 * @return float|string Integer value, 0, or an empty string otherwise.
 */
function ignition_sanitize_floatval_or_empty( $input ) {
	if ( false === $input || '' === $input || is_null( $input ) ) {
		return '';
	}

	if ( 0 === floatval( $input ) ) {
		return 0;
	}

	return floatval( $input );
}

/**
 * Returns a list of allowed tags and attributes for a given context.
 *
 * @since 1.0.0
 *
 * @see wp_kses()
 *
 * @param string $context Optional. The context for which to retrieve tags. Currently available contexts: guide
 *
 * @return array List of allowed tags and their allowed attributes.
 */
function ignition_get_allowed_tags( $context = '' ) {
	$allowed = array(
		'a'       => array(
			'href'   => true,
			'title'  => true,
			'class'  => true,
			'target' => true,
			'rel'    => true,
		),
		'abbr'    => array( 'title' => true ),
		'acronym' => array( 'title' => true ),
		'b'       => array( 'class' => true ),
		'br'      => array(),
		'code'    => array( 'class' => true ),
		'em'      => array( 'class' => true ),
		'i'       => array( 'class' => true ),
		'img'     => array(
			'alt'    => true,
			'class'  => true,
			'src'    => true,
			'width'  => true,
			'height' => true,
		),
		'li'      => array( 'class' => true ),
		'ol'      => array( 'class' => true ),
		'p'       => array( 'class' => true ),
		'pre'     => array( 'class' => true ),
		'span'    => array( 'class' => true ),
		'strong'  => array( 'class' => true ),
		'ul'      => array( 'class' => true ),
	);

	switch ( $context ) {
		case 'guide':
			unset( $allowed['p'] );
			break;
		default:
			break;
	}

	/**
	 * Filters the list of allowed tags.
	 *
	 * @since 1.0.0
	 *
	 * @param array $allowed  Array of allowed elements, structured for use with wp_kses()
	 * @param string $context Optional. Usage context.
	 */
	return apply_filters( 'ignition_get_allowed_tags', $allowed, $context );
}

/**
 * Return a list of allowed tags and attributes, making sure the tags needed for a search form are included.
 *
 * @since 1.0.0
 *
 * @see wp_kses()
 *
 * @return array List of allowed tags and their allowed attributes.
 */
function ignition_get_allowed_tags_search_form() {
	$allowed = array_merge( wp_kses_allowed_html( 'post' ), array(
		'form'   => array(
			'action'         => true,
			'accept'         => true,
			'accept-charset' => true,
			'enctype'        => true,
			'method'         => true,
			'name'           => true,
			'target'         => true,
			'id'             => true,
			'class'          => true,
		),
		'label'  => array(
			'for'   => true,
			'class' => true,
		),
		'input'  => array(
			'type'        => true,
			'name'        => true,
			'value'       => true,
			'id'          => true,
			'class'       => true,
			'placeholder' => true,
		),
		'select' => array(
			'id'    => true,
			'class' => true,
			'name'  => true,
			'value' => true,
		),
		'option' => array(
			'id'    => true,
			'class' => true,
			'value' => true,
		),
	) );

	/**
	 * Filters the list of allowed tags.
	 *
	 * @since 1.0.0
	 *
	 * @param array $allowed Array of allowed elements, structured for use with wp_kses()
	 */
	return apply_filters( 'ignition_get_allowed_tags_search_form', $allowed );
}


/**
 * Returns a list of valid image repeat property values, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_image_repeat_choices() {
	/**
	 * Filters the choices for image repeat properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_image_repeat_choices', array(
		'no-repeat' => esc_html__( 'No repeat', 'ignition' ),
		'repeat'    => esc_html__( 'Tile', 'ignition' ),
		'repeat-x'  => esc_html__( 'Tile Horizontally', 'ignition' ),
		'repeat-y'  => esc_html__( 'Tile Vertically', 'ignition' ),
	) );
}

/**
 * Sanitizes an image repeat property value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_image_repeat( $value ) {
	$choices = ignition_get_image_repeat_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	/**
	 * Filters the default fallback value for image repeat properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $default
	 */
	return apply_filters( 'ignition_sanitize_image_repeat_default', 'no-repeat' );
}

/**
 * Returns a list of valid image position property values, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_image_position_choices() {
	/**
	 * Filters the choices for image position properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_image_position_choices', array(
		'left top'      => esc_html__( 'Top Left', 'ignition' ),
		'center top'    => esc_html__( 'Top Center', 'ignition' ),
		'right top'     => esc_html__( 'Top Right', 'ignition' ),
		'left center'   => esc_html__( 'Center Left', 'ignition' ),
		'center center' => esc_html__( 'Center Center', 'ignition' ),
		'right center'  => esc_html__( 'Center Right', 'ignition' ),
		'left bottom'   => esc_html__( 'Bottom Left', 'ignition' ),
		'center bottom' => esc_html__( 'Bottom Center', 'ignition' ),
		'right bottom'  => esc_html__( 'Bottom Right', 'ignition' ),
	) );
}

/**
 * Sanitizes an image position property value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_image_position( $value ) {
	$choices = ignition_get_image_position_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	/**
	 * Filters the default fallback value for image position properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $default
	 */
	return apply_filters( 'ignition_sanitize_image_position_default', 'center top' );
}


/**
 * Returns a list of valid image attachment property values, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_image_attachment_choices() {
	/**
	 * Filters the choices for image attachment properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_image_attachment_choices', array(
		'scroll' => esc_html__( 'Scroll', 'ignition' ),
		'fixed'  => esc_html__( 'Fixed', 'ignition' ),
	) );
}

/**
 * Sanitizes an image attachment property value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_image_attachment( $value ) {
	$choices = ignition_get_image_attachment_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	/**
	 * Filters the default fallback value for image attachment properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $default
	 */
	return apply_filters( 'ignition_sanitize_image_attachment_default', 'scroll' );
}

/**
 * Returns a list of valid image size property values, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_image_size_choices() {
	/**
	 * Filters the choices for image size properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_image_size_choices', array(
		'cover'   => esc_html__( 'Cover', 'ignition' ),
		'contain' => esc_html__( 'Contain', 'ignition' ),
		'auto'    => esc_html__( 'Auto', 'ignition' ),
	) );
}

/**
 * Sanitizes an image size property value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_image_size( $value ) {
	$choices = ignition_get_image_size_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	/**
	 * Filters the default fallback value for image size properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $default
	 */
	return apply_filters( 'ignition_sanitize_image_size_default', 'cover' );
}

/**
 * Sanitizes a CSS color.
 *
 * Tries to validate and sanitize values in these formats:
 * - rgba()
 * - 3 and 6 digit hex values, optionally prefixed with `#`
 * - Predefined CSS named colors/keywords, such as 'transparent', 'initial', 'inherit', 'black', 'silver', etc.
 *
 * @since 1.0.0
 *
 * @param string $color       The color value to sanitize
 * @param bool   $return_hash Whether to return hex color prefixed with a `#`
 * @param string $return_fail Value to return when $color fails validation.
 *
 * @return string
 */
function ignition_sanitize_rgba_color( $color, $return_hash = true, $return_fail = '' ) {
	if ( false === $color || empty( $color ) || 'false' === $color ) {
		return $return_fail;
	}

	// Allow keywords and predefined colors
	if ( in_array( $color, array( 'transparent', 'initial', 'inherit', 'black', 'silver', 'gray', 'grey', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'orange', 'aliceblue', 'antiquewhite', 'aquamarine', 'azure', 'beige', 'bisque', 'blanchedalmond', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'limegreen', 'linen', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'oldlace', 'olivedrab', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'whitesmoke', 'yellowgreen', 'rebeccapurple' ), true ) ) {
		return $color;
	}

	preg_match( '/rgba\(\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1}\.?\d*\%?)\s*\)/', $color, $rgba_matches );
	if ( ! empty( $rgba_matches ) && 5 === count( $rgba_matches ) ) {
		for ( $i = 1; $i < 4; $i++ ) {
			if ( strpos( $rgba_matches[ $i ], '%' ) !== false ) {
				$rgba_matches[ $i ] = ignition_sanitize_0_100_percent( $rgba_matches[ $i ] );
			} else {
				$rgba_matches[ $i ] = ignition_sanitize_0_255( $rgba_matches[ $i ] );
			}
		}
		$rgba_matches[4] = ignition_sanitize_0_1_opacity( $rgba_matches[ $i ] );
		return sprintf( 'rgba(%s, %s, %s, %s)', $rgba_matches[1], $rgba_matches[2], $rgba_matches[3], $rgba_matches[4] );
	}

	// Not a color function either. Let's see if it's a hex color.

	// Include the hash if not there.
	// The regex below depends on in.
	if ( substr( $color, 0, 1 ) !== '#' ) {
		$color = '#' . $color;
	}

	preg_match( '/(#)([0-9a-fA-F]{6})/', $color, $matches );

	if ( 3 === count( $matches ) ) {
		if ( $return_hash ) {
			return $matches[1] . $matches[2];
		} else {
			return $matches[2];
		}
	}

	return $return_fail;
}

/**
 * Sanitizes a percentage value, 0% - 100%
 *
 * Accepts float values with or without the percentage sign `%`
 * Returns a string suffixed with the percentage sign `%`.
 *
 * @since 1.0.0
 *
 * @param mixed $value
 *
 * @return string A percentage value, including the percentage sign.
 */
function ignition_sanitize_0_100_percent( $value ) {
	$value = str_replace( '%', '', $value );
	if ( floatval( $value ) > 100 ) {
		$value = 100;
	} elseif ( floatval( $value ) < 0 ) {
		$value = 0;
	}

	return floatval( $value ) . '%';
}

/**
 * Sanitizes an integer percentage value, 0 - 100 while differentiating zero from empty string.
 *
 * Accepts values with or without the percentage sign `%`
 * Returns a number without the percentage sign `%`.
 *
 * @since 2.2.0
 *
 * @param mixed $value
 *
 * @return string A percentage value, including the percentage sign.
 */
function ignition_sanitize_int_percentage_or_empty( $value ) {
	if ( '' === $value ) {
		return '';
	}

	$value = str_replace( '%', '', $value );

	$value = intval( $value );
	if ( $value < 0 ) {
		return 0;
	}
	if ( $value > 100 ) {
		return 100;
	}

	return $value;
}

/**
 * Sanitizes a decimal CSS color value, 0 - 255.
 *
 * Accepts float values with or without the percentage sign `%`
 * Returns a string suffixed with the percentage sign `%`.
 *
 * @since 1.0.0
 *
 * @param mixed $value
 *
 * @return int A number between 0-255.
 */
function ignition_sanitize_0_255( $value ) {
	if ( intval( $value ) > 255 ) {
		$value = 255;
	} elseif ( intval( $value ) < 0 ) {
		$value = 0;
	}

	return intval( $value );
}

/**
 * Sanitizes a CSS opacity value, 0 - 1.
 *
 * @since 1.0.0
 *
 * @param mixed $value
 *
 * @return float A number between 0-1.
 */
function ignition_sanitize_0_1_opacity( $value ) {
	if ( floatval( $value ) > 1 ) {
		$value = 1;
	} elseif ( floatval( $value ) < 0 ) {
		$value = 0;
	}

	return floatval( $value );
}

/**
 * Returns a list of generic visibility dropdown options, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_respect_show_hide_options() {
	return array(
		''  => __( 'Respect Customizer setting', 'ignition' ),
		'1' => __( 'Show', 'ignition' ),
		'0' => __( 'Hide', 'ignition' ),
	);
}

/**
 * Sanitizes a generic visibility dropdown option value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_respect_show_hide_option( $value ) {
	$choices = ignition_get_respect_show_hide_options();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return '';
}

/**
 * Returns a list of valid header layout types, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_header_layout_type_choices() {
	$choices = array(
		'normal' => __( 'Normal', 'ignition' ),
	);

	if ( current_theme_supports( 'ignition-header-transparent' ) ) {
		$choices['transparent'] = __( 'Transparent', 'ignition' );
	}

	/**
	 * Filters the choices for the header layout type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_header_layout_type_choices', $choices );
}

/**
 * Sanitizes a header layout type value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_header_layout_type( $value ) {
	$choices = ignition_header_layout_type_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'header_layout_type' );
}

/**
 * Returns a list of valid header layout types dropdown options, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_respect_header_layout_type_choices() {
	$choices = array_merge( array(
		'' => __( 'Respect Customizer setting', 'ignition' ),
	), ignition_header_layout_type_choices() );

	return $choices;
}

/**
 * Sanitizes a header layout type dropdown option value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_respect_header_layout_type( $value ) {
	$choices = ignition_respect_header_layout_type_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return '';
}

/**
 * Returns a list of valid header menu types, and their associated details.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_header_layout_menu_types() {
	/**
	 * Filters the list of valid header menu types.
	 *
	 * @since 1.0.0
	 *
	 * @param array $menu_types
	 *
	 * @hooked ignition_side_mode_header_layout_menu_types - 10
	 */
	return apply_filters( 'ignition_header_layout_menu_types', array(
		'full_left'   => array(
			'title'         => __( 'Menu Left', 'ignition' ),
			'template_file' => 'full',
			'classes'       => array(
				'header-full',
				'header-full-nav-left',
			),
		),
		'full_center' => array(
			'title'         => __( 'Menu Center', 'ignition' ),
			'template_file' => 'full',
			'classes'       => array(
				'header-full',
				'header-full-nav-center',
			),
		),
		'full_right'  => array(
			'title'         => __( 'Menu Right', 'ignition' ),
			'template_file' => 'full',
			'classes'       => array(
				'header-full',
				'header-full-nav-right',
			),
		),
		'split'       => array(
			'title'         => __( 'Menu Split', 'ignition' ),
			'template_file' => 'split',
			'classes'       => array(
				'header-nav-split',
			),
		),
	) );
}

/**
 * Returns a list of valid header menu types choices, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_header_layout_menu_type_choices() {
	$choices = wp_list_pluck( ignition_get_header_layout_menu_types(), 'title' );

	/**
	 * Filters the choices for the header menu types.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_header_layout_menu_type_choices', $choices );
}

/**
 * Sanitizes a header menu type value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_header_layout_menu_type( $value ) {
	$choices = ignition_header_layout_menu_type_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'header_layout_menu_type' );
}

/**
 * Returns a list of valid sticky menu type choices, and their associated labels.
 *
 * @since 2.0.2
 *
 * @return array
 */
function ignition_sticky_menu_type_choices() {
	/**
	 * Filters the list of valid sticky menu type choices.
	 *
	 * @since 2.0.2
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_sticky_menu_type_choices', array(
		'off'       => esc_html__( 'Off', 'ignition' ),
		'shy'       => esc_html__( 'On - Reveal on scroll up', 'ignition' ),
		'permanent' => esc_html__( 'On - Permanent', 'ignition' ),
	) );
}

/**
 * Sanitizes a sticky menu type value.
 *
 * @since 2.0.2
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_sticky_menu_type( $value ) {
	$choices = ignition_sticky_menu_type_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	/**
	 * Filters the default fallback value for sticky menu type.
	 *
	 * @since 2.0.2
	 *
	 * @param string $default
	 */
	return apply_filters( 'ignition_sanitize_sticky_menu_type_default', 'off' );
}

/**
 * Returns a list of valid footer widgets layout types, and their associated details.
 *
 * @since 1.8.0
 *
 * @return array
 */
function ignition_get_footer_widgets_layout_types() {
	/**
	 * Filters the list of valid footer widgets layout types and their details.
	 *
	 * @since 1.8.0
	 *
	 * @param array $layout_types
	 */
	return apply_filters( 'ignition_footer_widgets_layout_types', array(
		'2-equal' => array(
			'title'         => __( '2 equal areas', 'ignition' ),
			'sidebars'      => 2,
			'template_file' => 'equal',
			'classes'       => array(
				'footer-widgets-layout-equal',
			),
		),
		'3-equal' => array(
			'title'         => __( '3 equal areas', 'ignition' ),
			'sidebars'      => 3,
			'template_file' => 'equal',
			'classes'       => array(
				'footer-widgets-layout-equal',
			),
		),
		'4-equal' => array(
			'title'         => __( '4 equal areas', 'ignition' ),
			'sidebars'      => 4,
			'template_file' => 'equal',
			'classes'       => array(
				'footer-widgets-layout-equal',
			),
		),
	) );
}

/**
 * Returns a list of valid footer widgets layout types, and their associated labels.
 *
 * @since 1.8.0
 *
 * @return array
 */
function ignition_footer_widgets_layout_type_choices() {
	$choices = wp_list_pluck( ignition_get_footer_widgets_layout_types(), 'title' );

	/**
	 * Filters the choices for the footer widgets layout types.
	 *
	 * @since 1.8.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_footer_widgets_layout_type_choices', $choices );
}

/**
 * Sanitizes a header layout type value.
 *
 * @since 1.8.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_footer_widgets_layout_type( $value ) {
	$choices = ignition_footer_widgets_layout_type_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'footer_widgets_layout_type' );
}

/**
 * Base set of Ignition's site layouts.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_get_site_layout_types() {
	/**
	 * Filters the site's layout types.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 *
	 * @hooked ignition_side_mode_site_layout_types - 10
	 */
	return apply_filters( 'ignition_site_layout_types', array(
		'content_sidebar'  => __( 'Content / Sidebar', 'ignition' ),
		'sidebar_content'  => __( 'Sidebar / Content', 'ignition' ),
		'fullwidth_boxed'  => __( 'Full width boxed', 'ignition' ),
		'fullwidth_narrow' => __( 'Full width narrow', 'ignition' ),
	) );
}

/**
 * Sanitizes a site layout value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_site_layout_type( $value ) {
	$choices = ignition_get_site_layout_types();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'site_layout_type' );
}

/**
 * Base set of Ignition's blog layouts.
 *
 * @since 1.9.0
 *
 * @return array
 */
function ignition_get_blog_layout_types() {
	/**
	 * Filters the blog's layout types.
	 *
	 * @since 1.9.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_blog_layout_types', ignition_get_site_layout_types() );
}

/**
 * Sanitizes a blog layout value.
 *
 * @since 1.9.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_blog_layout_type( $value ) {
	$choices = ignition_get_blog_layout_types();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'blog_archive_layout_type' );
}

/**
 * Base set of Ignition's Side Header mode site layouts.
 *
 * @since 2.1.0
 *
 * @return array
 */
function ignition_get_side_mode_site_layout_types() {
	/**
	 * Filters the site's layout types.
	 *
	 * @since 2.1.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_side_mode_site_layout_types', array(
		'boxed'          => __( 'Boxed site - Centered', 'ignition' ),
		'full_boxed'     => __( 'Full width site - Boxed content', 'ignition' ),
		'full_left'      => __( 'Full width site - Left-aligned content', 'ignition' ),
		'full_fullwidth' => __( 'Full width site - Full width content', 'ignition' ),
	) );
}

/**
 * Sanitizes a Side Header mode site layout value.
 *
 * @since 2.1.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_side_mode_site_layout_type( $value ) {
	$choices = ignition_get_side_mode_site_layout_types();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'side_mode_site_layout_type' );
}

/**
 * Returns a list of valid blog archive posts layout choices, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_blog_archive_posts_layout_type_choices() {
	/* translators: %d is a number of columns. */
	$nooped = _n_noop( '%d Column', '%d Columns', 'ignition' );

	$choices = array(
		'1col-horz' => __( '1 Column horizontal', 'ignition' ),
		'1col'      => sprintf( translate_nooped_plural( $nooped, 1, 'ignition' ), 1 ),
		'2col'      => sprintf( translate_nooped_plural( $nooped, 2, 'ignition' ), 2 ),
		'3col'      => sprintf( translate_nooped_plural( $nooped, 3, 'ignition' ), 3 ),
	);

	/**
	 * Filters the choices for the blog archive posts layout.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_blog_archive_posts_layout_type_choices', $choices );
}

/**
 * Sanitizes a blog archive posts layout value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_blog_archive_posts_layout_type( $value ) {
	$choices = ignition_blog_archive_posts_layout_type_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'blog_archive_posts_layout_type' );
}

/**
 * Returns a list of valid related posts columns choices, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_blog_single_related_columns_choices() {
	/* translators: %d is a number of columns. */
	$nooped = _n_noop( '%d Column', '%d Columns', 'ignition' );

	$choices = array(
		''  => __( 'Hide', 'ignition' ),
		'2' => sprintf( translate_nooped_plural( $nooped, 2, 'ignition' ), 2 ),
		'3' => sprintf( translate_nooped_plural( $nooped, 3, 'ignition' ), 3 ),
		'4' => sprintf( translate_nooped_plural( $nooped, 4, 'ignition' ), 4 ),
	);

	/**
	 * Filters the valid related posts columns choices.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_blog_single_related_columns_choices', $choices );
}

/**
 * Sanitizes a related posts columns value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_blog_single_related_columns( $value ) {
	$choices = ignition_blog_single_related_columns_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'blog_single_related_columns' );
}

/**
 * Base set of Ignition's WooCommerce product images layouts.
 *
 * @since 1.2.0
 *
 * @return array
 */
function ignition_get_woocommerce_product_images_layouts() {
	/**
	 * Filters the WooCommerce product images layouts.
	 *
	 * @since 1.2.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_woocommerce_product_images_layouts', array(
		''                 => __( 'Default', 'ignition' ),
		'list'             => __( 'List', 'ignition' ),
		'thumbnails-left'  => __( 'Thumbnails Left', 'ignition' ),
		'thumbnails-right' => __( 'Thumbnails Right', 'ignition' ),
	) );
}

/**
 * Sanitizes a WooCommerce product images layout.
 *
 * @since 1.2.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_woocommerce_product_images_layout( $value ) {
	$choices = ignition_get_woocommerce_product_images_layouts();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return ignition_customizer_defaults( 'woocommerce_product_images_layout' );
}

/**
 * Returns a list of valid alignment choices, and their associated labels.
 *
 * @since 1.0.0
 *
 * @return array
 */
function ignition_align_horizontal_choices() {
	/**
	 * Filters the list of valid alignment choices.
	 *
	 * @since 1.0.0
	 *
	 * @param array $choices Array of 'value' => 'label' choices.
	 */
	return apply_filters( 'ignition_align_horizontal_choices', array(
		'left'   => esc_html__( 'Left', 'ignition' ),
		'center' => esc_html__( 'Center', 'ignition' ),
		'right'  => esc_html__( 'Right', 'ignition' ),
	) );
}

/**
 * Sanitizes an alignment value.
 *
 * @since 1.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_align_horizontal( $value ) {
	$choices = ignition_align_horizontal_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	/**
	 * Filters the default fallback value for alignment.
	 *
	 * @since 1.0.0
	 *
	 * @param string $default
	 */
	return apply_filters( 'ignition_sanitize_align_horizontal_default', 'center' );
}


/**
 * Returns an empty breakpoints array.
 *
 * Used by responsive customizer controls that return simple values for each breakpoint.
 * Can optionally be passed an array with some initial breakpoint values.
 *
 * @since 1.0.0
 *
 * @param array $override_breakpoints
 *
 * @return array
 */
function ignition_customizer_defaults_empty_breakpoints( $override_breakpoints = array() ) {
	return array_merge( array(
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	), $override_breakpoints );
}

/**
 * Returns a breakpoints array with the same value in all breakpoints.
 *
 * @since 1.0.0
 *
 * @param mixed $value
 *
 * @return array
 */
function ignition_customizer_defaults_same_value_breakpoints( $value ) {
	return array(
		'desktop' => $value,
		'tablet'  => $value,
		'mobile'  => $value,
	);
}

/**
 * Sanitizes a breakpoints array, assuming values returned by `Ignition_Customize_Range_Control`.
 *
 * @since 1.0.0
 *
 * @param array $value
 *
 * @return array
 */
function ignition_sanitize_range_control_breakpoints( $value ) {
	if ( ! empty( $value ) && is_string( $value ) ) {
		$value = json_decode( $value, true );
	}

	if ( ! is_array( $value ) ) {
		return ignition_customizer_defaults_empty_breakpoints();
	}

	$value = array_map( 'ignition_sanitize_intval_or_empty', $value );

	return $value;
}

/**
 * Returns an empty breakpoints array for use with the `Ignition_Customize_Spacing_Control` customizer control.
 *
 * Can optionally be passed an array with some initial breakpoint values.
 *
 * @since 1.0.0
 *
 * @see Ignition_Customize_Spacing_Control
 *
 * @param array $override_breakpoints
 *
 * @return array
 */
function ignition_spacing_control_defaults_empty_breakpoints( $override_breakpoints = array() ) {
	$defaults = array(
		'desktop' => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
			'linked' => false,
		),
		'tablet'  => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
			'linked' => false,
		),
		'mobile'  => array(
			'top'    => '',
			'right'  => '',
			'bottom' => '',
			'left'   => '',
			'linked' => false,
		),
	);

	$return = array();
	foreach ( $defaults as $breakpoint => $values ) {
		if ( isset( $override_breakpoints[ $breakpoint ] ) && is_array( $override_breakpoints[ $breakpoint ] ) ) {
			$return[ $breakpoint ] = array_merge( $values, $override_breakpoints[ $breakpoint ] );
		} else {
			$return[ $breakpoint ] = $values;
		}
	}

	return $return;
}

/**
 * Sanitizes a breakpoints array as returned by the `Ignition_Customize_Spacing_Control` customizer control.
 *
 * @since 1.0.0
 *
 * @see Ignition_Customize_Spacing_Control
 *
 * @param array $values
 *
 * @return array
 */
function ignition_sanitize_spacing_control_breakpoints( $values ) {
	$defaults = ignition_spacing_control_defaults_empty_breakpoints();

	if ( ! empty( $values ) && is_string( $values ) ) {
		$values = json_decode( $values, true );
	}

	if ( ! is_array( $values ) ) {
		return $defaults;
	}

	$values = wp_parse_args( $values, $defaults );

	foreach ( $values as $breakpoint => $value ) {
		if ( ! array_key_exists( $breakpoint, $defaults ) ) {
			unset( $values[ $breakpoint ] );
		}
	}

	$new_values = array();

	foreach ( $values as $breakpoint => $breakpoint_values ) {
		if ( array_key_exists( $breakpoint, $defaults ) ) {
			$new_breakpoint_values = wp_parse_args( $breakpoint_values, $defaults[ $breakpoint ] );

			$linked = $new_breakpoint_values['linked'];

			unset( $new_breakpoint_values['linked'] );

			$new_breakpoint_values = array_map( 'ignition_sanitize_intval_or_empty', $new_breakpoint_values );

			$new_breakpoint_values['linked'] = (bool) $linked;

			$new_values[ $breakpoint ] = $new_breakpoint_values;
		}
	}

	return $new_values;
}

/**
 * Returns an empty breakpoints array for use with the `Ignition_Customize_Typography_Control` customizer control.
 *
 * Can optionally be passed an array with some initial breakpoint values.
 *
 * @since 1.0.0
 *
 * @see Ignition_Customize_Typography_Control
 *
 * @param array $override_breakpoints
 *
 * @return array
 */
function ignition_typography_control_defaults_empty_breakpoints( $override_breakpoints = array() ) {
	$defaults = array(
		'desktop' => array(
			'family'     => '',
			'variant'    => '',
			'size'       => '',
			'lineHeight' => '',
			'transform'  => '',
			'spacing'    => '',
			'is_gfont'   => false,
		),
		'tablet'  => array(
			'family'     => '',
			'variant'    => '',
			'size'       => '',
			'lineHeight' => '',
			'transform'  => '',
			'spacing'    => '',
			'is_gfont'   => false,
		),
		'mobile'  => array(
			'family'     => '',
			'variant'    => '',
			'size'       => '',
			'lineHeight' => '',
			'transform'  => '',
			'spacing'    => '',
			'is_gfont'   => false,
		),
	);

	$return = array();
	foreach ( $defaults as $breakpoint => $values ) {
		if ( isset( $override_breakpoints[ $breakpoint ] ) && is_array( $override_breakpoints[ $breakpoint ] ) ) {
			$return[ $breakpoint ] = array_merge( $values, $override_breakpoints[ $breakpoint ] );
		} else {
			$return[ $breakpoint ] = $values;
		}
	}

	return $return;
}

/**
 * Sanitizes a breakpoints array as returned by the `Ignition_Customize_Typography_Control` customizer control.
 *
 * @since 1.0.0
 *
 * @see Ignition_Customize_Typography_Control
 *
 * @param array $values
 *
 * @return array
 */
function ignition_sanitize_typography_control_breakpoints( $values ) {
	$defaults = ignition_typography_control_defaults_empty_breakpoints();

	if ( ! empty( $values ) && is_string( $values ) ) {
		$values = json_decode( $values, true );
	}

	if ( ! is_array( $values ) ) {
		return $defaults;
	}

	$values = wp_parse_args( $values, $defaults );

	foreach ( $values as $breakpoint => $value ) {
		if ( ! array_key_exists( $breakpoint, $defaults ) ) {
			unset( $values[ $breakpoint ] );
		}
	}

	$new_values = array();

	foreach ( $values as $breakpoint => $breakpoint_values ) {
		if ( array_key_exists( $breakpoint, $defaults ) ) {
			$new_breakpoint_values = wp_parse_args( $breakpoint_values, $defaults[ $breakpoint ] );

			$new_breakpoint_values['family']     = sanitize_text_field( $new_breakpoint_values['family'] );
			$new_breakpoint_values['variant']    = sanitize_text_field( $new_breakpoint_values['variant'] );
			$new_breakpoint_values['size']       = ignition_sanitize_intval_or_empty( $new_breakpoint_values['size'] );
			$new_breakpoint_values['lineHeight'] = ignition_sanitize_floatval_or_empty( $new_breakpoint_values['lineHeight'] );
			$new_breakpoint_values['transform']  = sanitize_text_field( $new_breakpoint_values['transform'] );
			$new_breakpoint_values['spacing']    = ignition_sanitize_floatval_or_empty( $new_breakpoint_values['spacing'] );
			$new_breakpoint_values['is_gfont']   = (bool) intval( $new_breakpoint_values['is_gfont'] );

			$new_values[ $breakpoint ] = $new_breakpoint_values;
		}
	}

	return $new_values;
}

/**
 * Extracts the values of a list of properties from a typography control breakpoints array.
 *
 * @see ignition_typography_control_defaults_empty_breakpoints()
 *
 * @param $values array A typography control array.
 * @param $properties array A list of properties to extract.
 *
 * @return array
 */
function ignition_typography_control_extract_properties( $values, $properties ) {
	$new_values = array();

	foreach ( $values as $breakpoint => $breakpoint_values ) {
		foreach ( $breakpoint_values as $property => $value ) {
			if ( in_array( $property, $properties, true ) ) {
				$new_values[ $breakpoint ][ $property ] = $value;
			}
		}
	}

	return ignition_sanitize_typography_control_breakpoints( $new_values );
}

/**
 * Returns an empty array for use with the `Ignition_Customize_Image_BG_Control` customizer control.
 *
 * Can optionally be passed an array with some initial values.
 *
 * @since 1.0.0
 *
 * @see Ignition_Customize_Image_BG_Control
 *
 * @param array $override_values
 *
 * @return array
 */
function ignition_image_bg_control_defaults( $override_values = array() ) {
	return array_merge( array(
		'image_id'   => '',
		'image_url'  => '',
		'repeat'     => 'no-repeat',
		'position'   => 'center center',
		'attachment' => 'scroll',
		'size'       => 'cover',
	), $override_values );
}

/**
 * Sanitizes an array as returned by the `Ignition_Customize_Image_BG_Control` customizer control.
 *
 * @since 1.0.0
 *
 * @see Ignition_Customize_Image_BG_Control
 *
 * @param array $values
 *
 * @return array
 */
function ignition_sanitize_image_bg_control( $values ) {
	$defaults = ignition_image_bg_control_defaults();

	if ( ! empty( $values ) && is_string( $values ) ) {
		$values = json_decode( $values, true );
	}

	if ( ! is_array( $values ) ) {
		return $defaults;
	}

	$values = wp_parse_args( $values, $defaults );

	$new_values = array();

	$new_values['image_id']   = ignition_sanitize_intval_or_empty( $values['image_id'] );
	$new_values['image_url']  = esc_url_raw( $values['image_url'] );
	$new_values['repeat']     = ignition_sanitize_image_repeat( $values['repeat'] );
	$new_values['position']   = ignition_sanitize_image_position( $values['position'] );
	$new_values['attachment'] = ignition_sanitize_image_attachment( $values['attachment'] );
	$new_values['size']       = ignition_sanitize_image_size( $values['size'] );

	return $new_values;
}

/**
 * Determines whether two Image Background control arrays are equal.
 *
 * @since 1.0.0
 *
 * @see Ignition_Customize_Image_BG_Control
 * @see ignition_image_bg_control_defaults()
 *
 * @param array $array1
 * @param array $array2
 *
 * @return bool
 */
function ignition_are_image_bg_arrays_equal( $array1, $array2 ) {
	$array1 = ! empty( $array1 ) ? $array1 : ignition_image_bg_control_defaults();
	$array2 = ! empty( $array2 ) ? $array2 : ignition_image_bg_control_defaults();

	ksort( $array1 );
	ksort( $array2 );

	return ( $array1 === $array2 );
}

/**
 * Returns a list of valid devices visibility dropdown options, and their associated labels.
 *
 * @since 2.0.0
 *
 * @return array
 */
function ignition_devices_visibility_choices() {
	$choices = array(
		'all'             => __( 'All Devices', 'ignition' ),
		'desktop'         => __( 'Desktop Only', 'ignition' ),
		'desktop_tablets' => __( 'Desktop & Tablets', 'ignition' ),
		'all_mobile'      => __( 'Tablets & Mobile Devices', 'ignition' ),
		'mobile'          => __( 'Mobile Devices', 'ignition' ),
	);

	return apply_filters( 'ignition_devices_visibility_choices', $choices );
}


/**
 * Sanitizes a devices visibility value.
 *
 * @since 2.0.0
 *
 * @param string $value
 *
 * @return string
 */
function ignition_sanitize_devices_visibility( $value ) {
	$choices = ignition_devices_visibility_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return 'all';
}

/**
 * Returns the appropriate class for the specific devices visibility.
 *
 * @since 2.0.0
 *
 * @param string $devices
 *
 * @return string
 */
function ignition_get_devices_visibility_class( $devices ) {
	$class = '';

	switch ( $devices ) {
		case 'desktop':
			$class = 'd-lg-block d-none';
			break;
		case 'desktop_tablets':
			$class = 'd-md-block d-none';
			break;
		case 'all_mobile':
			$class = 'd-lg-none';
			break;
		case 'mobile':
			$class = 'd-sm-none';
			break;
		case 'all':
		default:
			$class = '';
			break;
	}

	return apply_filters( 'ignition_devices_visibility_class', $class, $devices );
}
