<?php
/**
 * Handles CSS generation for a multitude of customizer control types.
 *
 * @since 1.0.0
 */
class Ignition_Customizer_CSS_Generator {

	/**
	 * Holds a copy of itself, so it can be referenced by the class name.
	 *
	 * @var Ignition_Customizer_CSS_Generator
	 */
	public static $instance;

	/**
	 * @var array
	 */
	protected $css_variables = array();

	/**
	 * @var array[]
	 */
	protected $css = array(
		'desktop'      => array(),
		'tablet'       => array(),
		'mobile'       => array(),
		'desktop-only' => array(),
		'tablet-only'  => array(),
		'mobile-only'  => array(),
	);

	/**
	 * 'name' => 'default' list of typography controls.
	 *
	 * @var array
	 */
	protected $typography_controls = array();

	/**
	 * @var array
	 */
	protected $fonts_list = array();

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return Ignition_Customizer_CSS_Generator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initializes the class's properties.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$fonts_list       = Ignition_Fonts_List::get_instance();
		$this->fonts_list = $fonts_list->get();
	}

	/**
	 * Returns a breakpoint's rules as an array.
	 *
	 * @since 1.0.0
	 *
	 * @param string $breakpoint
	 *
	 * @return array
	 */
	public function get_array( $breakpoint ) {
		$return = array();

		if ( array_key_exists( $breakpoint, $this->css ) ) {
			$return = $this->css[ $breakpoint ];
		}

		$return = array_map( 'trim', $return );
		$return = array_filter( $return );

		/**
		 * Filters $breakpoint's rules as an array.
		 *
		 * @since 1.0.0
		 *
		 * @param array $return
		 * @param string $breakpoint
		 */
		return apply_filters( 'ignition_customizer_css_generator_get_array', $return, $breakpoint );
	}

	/**
	 * Returns a breakpoint's rules as a string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $breakpoint
	 *
	 * @return string
	 */
	public function get( $breakpoint ) {
		$return = implode( PHP_EOL, $this->get_array( $breakpoint ) );

		/**
		 * Filters $breakpoint's rules as a string.
		 *
		 * @since 1.0.0
		 *
		 * @param string $return
		 * @param string $breakpoint
		 */
		return apply_filters( 'ignition_customizer_css_generator_get', $return, $breakpoint );
	}

	/**
	 * Adds a CSS variable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The variable's name.
	 * @param string $value The variable's value.
	 */
	public function add_variable( $name, $value ) {
		if ( ! $this->value_is_empty( $value ) ) {
			$this->css_variables[ $name ] = $value;
		}
	}

	/**
	 * Returns a CSS variable's CSS rule.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_variables_css() {
		$css = '';
		foreach ( $this->css_variables as $name => $value ) {
			$css .= sprintf( '%s: %s;%s', $name, $value, PHP_EOL );
		}

		return $css;
	}

	/**
	 * Adds CSS for a specific breakpoint.
	 *
	 * @since 1.0.0
	 *
	 * @param string $breakpoint The breakpoint to apply the CSS.
	 * @param string $css        CSS code.
	 */
	public function add( $breakpoint, $css ) {
		if ( ! array_key_exists( $breakpoint, $this->css ) ) {
			trigger_error( esc_html( sprintf( 'Invalid breakpoint: %s', $breakpoint ) ) );
		}

		$css = trim( $css );

		/**
		 * Filters the CSS that is to be added on a breakpoint.
		 *
		 * @since 1.0.0
		 *
		 * @param string $css
		 * @param string $breakpoint
		 */
		$css = apply_filters( 'ignition_customizer_css_generator_add', $css, $breakpoint );

		if ( ! empty( $css ) ) {
			$this->css[ $breakpoint ][] = $css;
		}
	}

	/**
	 * Adds CSS, if the value is not empty.
	 * If the placeholder %s is found in $css, it will be replaced with the value.
	 * When a $callback is set, the value is always passed as the first argument.
	 *
	 * Examples:
	 *  $css->add_value( 'mobile', get_theme_mod( 'header_layout_menu_type' ), 'body { background-color: %s; color: %s; }' );
	 *  $css->add_value( 'mobile', get_theme_mod( 'header_layout_menu_type' ), 'body { background-color: %s; color: %s; }', 'absint' );
	 *  $css->add_value( 'mobile', get_theme_mod( 'header_layout_menu_type' ), 'body { background-color: %s; color: %s; }', 'strtok', array( '_' ) );
	 *
	 * @since 1.0.0
	 *
	 * @param string     $breakpoint       The breakpoint to apply the css rule.
	 * @param mixed      $value            The value to be added.
	 * @param string     $css              CSS rule. May contain the placeholder %s which will be replaced with the value.
	 * @param bool       $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 * @param null|mixed $callback         Optional. If provided, the value will be passed through the callback as the first argument.
	 * @param array      $callback_args    Optional. Additional arguments to pass to the callback.
	 */
	public function add_value( $breakpoint, $value, $css, $breakpoint_limit = false, $callback = null, $callback_args = array() ) {
		if ( $this->value_is_empty( $value ) ) {
			return;
		}

		if ( ! empty( $callback ) && is_callable( $callback ) ) {
			$args  = array_merge( array( $value ), $callback_args );
			$value = call_user_func_array( $callback, $args );
		}

		$css = str_replace( '%s', $value, $css );

		if ( $breakpoint_limit ) {
			$this->add( "{$breakpoint}-only", $css );
		} else {
			$this->add( $breakpoint, $css );
		}
	}

	/**
	 * Adds CSS for simple breakpoint structured values (i.e. breakpoint => value array).
	 * Can accept a string for $breakpoints_css to apply to all breakpoint, or an array for each individual one.
	 * Examples:
	 *
	 *  $value = array(
	 *    'desktop' => '#f00',
	 *    'tablet'  => '#0f0',
	 *    'mobile'  => '#00f',
	 *  );
	 *
	 *  // Different selector per breakpoint.
	 *  $css->add_responsive( $value, array(
	 *    'desktop' => 'body { background-color: %s; }',
	 *    'tablet'  => 'body { background-color: %s; }',
	 *    'mobile'  => 'body { background-color: %s; }',
	 *  ) );
	 *
	 *  // Same selector for all breakpoints.
	 *  $css->add_responsive( $value, '.site-logo a { background-color: %s; }' );
	 *
	 * @since 1.0.0
	 *
	 * @param array        $values           breakpoint => value array.
	 * @param string|array $breakpoints_css  breakpoint => css array to apply if value exists.
	 * @param bool         $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 * @param array        $edge_cases       Array where the key is an edge case value, and the value is the CSS rule to apply.
	 */
	public function add_responsive( $values, $breakpoints_css, $breakpoint_limit = false, $edge_cases = array() ) {
		if ( ! is_array( $values ) || empty( $breakpoints_css ) ) {
			return;
		}

		foreach ( $values as $breakpoint => $value ) {
			if ( ! $this->value_is_empty( $value ) ) {
				if ( array_key_exists( $value, $edge_cases ) ) {
					$this->add_value( $breakpoint, $value, $edge_cases[ $value ], $breakpoint_limit );
				} else {
					if ( is_string( $breakpoints_css ) ) {
						$this->add_value( $breakpoint, $value, $breakpoints_css, $breakpoint_limit );
					} elseif ( is_array( $breakpoints_css ) && ! empty( $breakpoints_css[ $breakpoint ] ) ) {
						$this->add_value( $breakpoint, $value, $breakpoints_css[ $breakpoint ], $breakpoint_limit );
					}
				}
			}
		}
	}

	/**
	 * Adds CSS for the spacing control.
	 * Can accept a string for $breakpoints_css to apply to all breakpoints, or an array for each individual one.
	 *
	 * Examples:
	 *
	 *  $values = array(
	 *    'desktop' => array(
	 *      'top'    => 10,
	 *      'right'  => 20,
	 *      'bottom' => 15,
	 *      'left'   => 40,
	 *      'linked' => false,
	 *    ),
	 *    'tablet'  => array(
	 *      'top'    => 10,
	 *      'right'  => 10,
	 *      'bottom' => 10,
	 *      'left'   => 10,
	 *      'linked' => true,
	 *    ),
	 *    'mobile'  => array(
	 *      'top'    => '',
	 *      'right'  => '',
	 *      'bottom' => '',
	 *      'left'   => '',
	 *      'linked' => false,
	 *    )
	 *  );
	 *
	 *  // Same selector for all breakpoints.
	 *  $css->add_spacing( $values, '.header { %s }', 'padding', 'px' );
	 *
	 *  // Different selector per breakpoint.
	 *  $css->add_spacing( $value, array(
	 *    'desktop' => '.header { %s }',
	 *    'tablet'  => '.header { %s }',
	 *    'mobile'  => '.header { %s }',
	 *  ), 'padding', 'px' );
	 *
	 * @since 1.0.0
	 *
	 * @param array        $values           The array of values, as returned by the spacing control. It should include all breakpoints.
	 * @param string|array $breakpoints_css  breakpoint => css array to apply if value exists.
	 * @param string       $mode             The property to generate the rules for (something that gets -top, -right, etc). E.g. 'margin', 'padding', etc.
	 *                                       Special case 'position' generates top: right: etc ruls without a prefix-. Default 'margin'.
	 * @param string       $unit             The unit to suffix the values with. E.g. 'px', 'em', etc. Default 'px'.
	 * @param bool         $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 */
	public function add_spacing( $values, $breakpoints_css, $mode = 'margin', $unit = 'px', $breakpoint_limit = false ) {
		if ( ! is_string( $mode ) || empty( $mode ) ) {
			return;
		}

		if ( ! is_array( $values ) || empty( $breakpoints_css ) ) {
			return;
		}

		foreach ( $values as $breakpoint => $value ) {
			if ( ! empty( $value ) ) {
				$rules = $this->generate_spacing_rules( $value, $mode, $unit );

				if ( is_string( $breakpoints_css ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css, $breakpoint_limit );
				} elseif ( is_array( $breakpoints_css ) && ! empty( $breakpoints_css[ $breakpoint ] ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css[ $breakpoint ], $breakpoint_limit );
				}
			}
		}
	}

	/**
	 * Generates the appropriate CSS rules for "spacing" controls, e.g. padding and margin, for a single breakpoint.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $values The array of values for a single breakpoint.
	 * @param string $mode   The property to generate the rules for (something that gets -top, -right, etc). E.g. 'margin', 'padding', etc.
	 *                       Special case 'position' generates top: right: etc rules without a prefix-. Default 'margin'.
	 * @param string $unit   The unit to suffix the values with. E.g. 'px', 'em', etc. Default 'px'.
	 *
	 * @return string
	 */
	public function generate_spacing_rules( $values, $mode = 'margin', $unit = 'px' ) {
		if ( ! is_array( $values ) || empty( $values ) ) {
			return '';
		}

		$rules = array();

		$linked = false;
		if ( isset( $values['linked'] ) ) {
			$linked = $values['linked'];
			unset( $values['linked'] );
		}

		$prefix = "{$mode}-";
		if ( 'position' === $mode ) {
			$prefix = '';
		}

		foreach ( $values as $direction => $value ) {
			if ( ! $this->value_is_empty( $value ) ) {
				if ( 0 === intval( $value ) ) {
					$rules[] = "{$prefix}{$direction}: {$value};";
				} else {
					$rules[] = "{$prefix}{$direction}: {$value}{$unit};";
				}
			}
		}

		return implode( ' ', $rules );
	}

	/**
	 * Add CSS for typography control.
	 * Can accept a string for $breakpoints_css to apply to all breakpoint, or an array for each individual one.
	 *
	 * @since 1.0.0
	 *
	 * @param array        $values           The array of values, as returned by the typography control. It should include all breakpoints.
	 * @param string|array $fallback_stack   Array or comma-separated string of fallback font names. It's considered only when 'family' is set for a breakpoint.
	 * @param string|array $breakpoints_css  breakpoint => css array to apply if value exists.
	 * @param bool         $breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
	 */
	public function add_typography( $values, $fallback_stack, $breakpoints_css, $breakpoint_limit = false ) {
		if ( ! is_array( $values ) || empty( $breakpoints_css ) ) {
			return;
		}

		foreach ( $values as $breakpoint => $value ) {
			if ( ! empty( $value ) && is_array( $value ) ) {

				$rules = $this->generate_typography_rules( $value, $fallback_stack );

				if ( is_string( $breakpoints_css ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css, $breakpoint_limit );
				} elseif ( is_array( $breakpoints_css ) && ! empty( $breakpoints_css[ $breakpoint ] ) ) {
					$this->add_value( $breakpoint, $rules, $breakpoints_css[ $breakpoint ], $breakpoint_limit );
				}
			}
		}
	}

	/**
	 * Add CSS for the image background control.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $values           The array of values, as returned by the image-bg control.
	 * @param string $image_size       Image size name, e.g. 'post-thumbnail'.
	 * @param string $css              CSS selector to apply if value exists.
	 * @param string $background_color Optional background color to include in the generated CSS.
	 */
	public function add_image_background_by_id( $values, $image_size, $css, $background_color = '' ) {
		if ( ! is_array( $values ) || empty( $css ) || empty( $image_size ) ) {
			return;
		}

		$url = false;
		if ( isset( $values['image_id'] ) && ! $this->value_is_empty( $values['image_id'] ) ) {
			$url = wp_get_attachment_image_url( $values['image_id'], $image_size );
		}

		$rules = $this->generate_image_background_rules( $values, $url, $background_color );

		if ( ! empty( $rules ) ) {
			$this->add_value( 'desktop', $rules, $css );
		}
	}

	/**
	 * Add CSS for the image background control. Uses the actual size selected by the user.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $values           The array of values, as returned by the image-bg control.
	 * @param string $css              CSS selector to apply if value exists.
	 * @param string $background_color Optional background color to include in the generated CSS.
	 */
	public function add_image_background_by_url( $values, $css, $background_color = '' ) {
		if ( ! is_array( $values ) || empty( $css ) ) {
			return;
		}

		$url = false;
		if ( ! empty( $values['image_url'] ) ) {
			$url = $values['image_url'];
		}

		$rules = $this->generate_image_background_rules( $values, $url, $background_color );

		if ( ! empty( $rules ) ) {
			$this->add_value( 'desktop', $rules, $css );
		}
	}

	/**
	 * Generates the appropriate CSS rules for "image-background" controls.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $values           The array of values, as returned by the image-bg control.
	 * @param string $image_url        The image url to use as background.
	 * @param string $background_color Optional background color.
	 *
	 * @return string
	 */
	public function generate_image_background_rules( $values, $image_url, $background_color = '' ) {
		$rules = array();

		if ( ! $this->value_is_empty( $background_color ) ) {
			$rules[] = sprintf( 'background-color: %s;', $background_color );
		}

		if ( ! empty( $image_url ) ) {
			$rules[] = sprintf( 'background-image: url(%s);', esc_url_raw( $image_url ) );

			if ( ! empty( $values['repeat'] ) ) {
				$rules[] = sprintf( 'background-repeat: %s;', $values['repeat'] );
			}

			if ( ! empty( $values['position'] ) ) {
				$rules[] = sprintf( 'background-position: %s;', $values['position'] );
			}

			if ( ! empty( $values['attachment'] ) ) {
				$rules[] = sprintf( 'background-attachment: %s;', $values['attachment'] );
			}

			if ( ! empty( $values['size'] ) ) {
				$rules[] = sprintf( 'background-size: %s;', $values['size'] );
			}
		}

		return implode( ' ', $rules );
	}


	/**
	 * Generates the appropriate CSS rules for "typography" controls, for a single breakpoint.
	 *
	 * @since 1.0.0
	 *
	 * @param array        $values         The array of values for a single breakpoint.
	 * @param string|array $fallback_stack Array or comma-separated string of fallback font names. It's considered only when 'family' is set.
	 *
	 * @return string
	 */
	public function generate_typography_rules( $values, $fallback_stack = '' ) {
		$rules = array();

		if ( isset( $values['family'] ) && ! $this->value_is_empty( $values['family'] ) ) {
			$font       = $values['family'];
			$font_array = explode( ',', $font );

			$stack = array_map( function ( $font_string ) {
				$trimmed_font_string = trim( $font_string );

				if ( false !== strpos( $trimmed_font_string, ' ' ) ) {
					return '"' . $trimmed_font_string . '"';
				}

				return $trimmed_font_string;
			}, $font_array );

			if ( ! empty( $fallback_stack ) && is_string( $fallback_stack ) ) {
				$fallback_stack = explode( ',', $fallback_stack );
				$fallback_stack = array_map( 'trim', $fallback_stack );
				$fallback_stack = array_filter( $fallback_stack );
			}

			/**
			 * Filters the customizer-selected font family stack.
			 *
			 * @since 1.0.0
			 *
			 * @param array $stack
			 * @param array $values
			 * @param string|array $fallback_stack
			 */
			$stack = apply_filters( 'ignition_customizer_css_generator_generate_typography_stack', $stack, $values, $fallback_stack );

			$fallback_category = '';

			// TODO: Can we also apply this to the live preview JS?
			$font_obj = wp_filter_object_list( $this->fonts_list, array( 'family' => $values['family'] ), 'and' );
			if ( ! empty( $font_obj ) ) {
				$font_obj = array_values( $font_obj );
				$font_obj = $font_obj[0];
				if ( ! empty( $font_obj->category ) ) {
					$fallback_rewrites = array(
						'handwriting' => 'cursive',
						'display'     => '',
					);
					$fallback_category = str_replace( array_keys( $fallback_rewrites ), array_values( $fallback_rewrites ), $font_obj->category );
				}
			}

			$all_stack = $stack;

			if ( ! empty( $fallback_stack ) && is_array( $fallback_stack ) ) {
				$all_stack = array_merge( $stack, $fallback_stack );
			}

			if ( ! empty( $fallback_category ) ) {
				$all_stack = array_merge( $all_stack, array( $fallback_category ) );
			}

			$all_stack = array_filter( array_unique( $all_stack ) );

			/**
			 * Filters the full font family stack.
			 *
			 * @since 1.0.0
			 *
			 * @param string $css
			 * @param string $breakpoint
			 */
			$all_stack = apply_filters( 'ignition_customizer_css_generator_generate_typography_all_stack', $all_stack, $values, $stack, $fallback_stack );

			if ( ! empty( $all_stack ) ) {
				$rules[] = sprintf( 'font-family: %s;', implode( ', ', $all_stack ) );
			}
		}

		if ( isset( $values['variant'] ) && ! $this->value_is_empty( $values['variant'] ) ) {
			$variant = $values['variant'];

			$weight = $this->convert_font_variant_to_weight( $variant );
			$style  = $this->convert_font_variant_to_style( $variant );

			if ( ! $this->value_is_empty( $weight ) ) {
				$rules[] = sprintf( 'font-weight: %s;', $weight );
			}

			if ( ! $this->value_is_empty( $style ) ) {
				$rules[] = sprintf( 'font-style: %s;', $style );
			}
		}

		if ( isset( $values['size'] ) && ! $this->value_is_empty( $values['size'] ) ) {
			$rules[] = sprintf( 'font-size: %spx;', $values['size'] );
		}

		if ( isset( $values['lineHeight'] ) && ! $this->value_is_empty( $values['lineHeight'] ) ) {
			$rules[] = sprintf( 'line-height: %s;', $values['lineHeight'] );
		}

		if ( isset( $values['transform'] ) && ! $this->value_is_empty( $values['transform'] ) ) {
			$rules[] = sprintf( 'text-transform: %s;', $values['transform'] );
		}

		if ( isset( $values['spacing'] ) && ! $this->value_is_empty( $values['spacing'] ) ) {
			$rules[] = sprintf( 'letter-spacing: %sem;', $values['spacing'] );
		}

		return implode( ' ', $rules );
	}

	/**
	 * Checks whether the passed value should be considered empty for CSS reasons.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function value_is_empty( $value ) {
		$value = trim( $value );

		if ( false === $value || '' === $value ) {
			return true;
		}

		return false;
	}

	/**
	 * Registers an option name as a typography control. Used to build and enqueue Google Fonts automatically.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name
	 * @param array  $default A typography array. See ignition_typography_control_defaults_empty_breakpoints()
	 */
	public function register_typography_control( $option_name, $default ) {
		if ( ! array_key_exists( $option_name, $this->typography_controls ) ) {
			$this->typography_controls[ $option_name ] = $default;
		}
	}

	/**
	 * Build a Google Fonts query URL, based on the registered typography controls.
	 *
	 * @since 1.0.0
	 */
	public function get_google_fonts_url() {
		// font_name => array( variations )
		$fonts = array();

		$fonts_url = '';

		foreach ( $this->typography_controls as $option_name => $default ) {
			/**
			 * Filters whether a typography control's values should be processed, and perhaps added, into the generated fonts URL.
			 *
			 * @since 1.0.0
			 *
			 * @param bool $add_font
			 * @param string $option_name
			 * @param array $default
			 */
			$add_font = apply_filters( 'ignition_customizer_css_generator_add_font_to_url', true, $option_name, $default );

			if ( 'placeholder_preview_font' !== $option_name && ! $add_font ) {
				continue;
			}

			$global_option   = get_theme_mod( $option_name, $default );
			$override_option = false;

			if ( is_singular() ) {
				$override_option = get_post_meta( get_queried_object_id(), $option_name, true );
			}

			$breakpoints = array( 'desktop', 'tablet', 'mobile' );

			foreach ( $breakpoints as $breakpoint ) {
				$bp_values = false;

				if ( ! empty( $override_option ) && ! empty( $override_option[ $breakpoint ] ) && ! empty( $override_option[ $breakpoint ]['family'] ) ) {
					$bp_values = (array) $override_option[ $breakpoint ];
				} elseif ( ! empty( $global_option ) && ! empty( $global_option[ $breakpoint ] ) && ! empty( $global_option[ $breakpoint ]['family'] ) ) {
					$bp_values = (array) $global_option[ $breakpoint ];
				}

				if ( empty( $bp_values ) || empty( $bp_values['family'] ) ) {
					continue;
				}

				// Skip fonts that aren't Google Fonts, or any that we don't know whether they are or not.
				if ( ! array_key_exists( 'is_gfont', $bp_values ) || false === (bool) $bp_values['is_gfont'] ) {
					continue;
				}

				$family  = $bp_values['family'];
				$variant = isset( $bp_values['variant'] ) ? $bp_values['variant'] : '';

				// Create an entry in the $fonts array.
				if ( ! array_key_exists( $family, $fonts ) ) {
					$fonts[ $family ] = array();
				}

				$weight = $this->convert_font_variant_to_weight( $variant );
				$style  = $this->convert_font_variant_to_style( $variant );
				$style  = 'italic' === $style ? 'i' : '';

				$fonts[ $family ][] = $weight . $style;
			}
		}

		$font_strings = array();

		foreach ( $fonts as $font_name => $font_weights ) {

			$available_weights = array();
			$font_obj          = wp_filter_object_list( $this->fonts_list, array( 'family' => $font_name ), 'and' );
			if ( ! empty( $font_obj ) ) {
				$font_obj = array_values( $font_obj );
				$font_obj = $font_obj[0];
				if ( ! empty( $font_obj->variants ) ) {
					foreach ( $font_obj->variants as $variant ) {
						$weight = $this->convert_font_variant_to_weight( $variant );
						$style  = $this->convert_font_variant_to_style( $variant );
						$style  = 'italic' === $style ? 'i' : '';

						$available_weights[] = $weight . $style;
					}
				}
			}

			$selected_weights = array_filter( $font_weights );
			$selected_weights = array_unique( $selected_weights );

			/**
			 * Filters the Google font weights that should be loaded by default.
			 *
			 * These weights are enqueued independently of the user's selections, as we need at least these for the
			 * font to render properly in most common cases.
			 *
			 * @since 1.0.0
			 *
			 * @param array $standard_weights
			 */
			$standard_weights = apply_filters( 'ignition_customizer_css_generator_standard_gfont_weights', array(
				'400',
				'400i',
				'700',
			) );

			$enqueue_weights = array_merge( $selected_weights, $standard_weights );

			/**
			 * Filters the list of all Google font weights that will be loaded.
			 *
			 * @since 1.0.0
			 *
			 * @param array $enqueue_weights
			 * @param string $font_name
			 * @param array $available_weights
			 * @param array $selected_weights
			 * @param array $standard_weights
			 */
			$enqueue_weights = apply_filters( 'ignition_customizer_css_generator_enqueue_gfont_weights', $enqueue_weights, $font_name, $available_weights, $selected_weights, $standard_weights );

			// Make sure we request for weights that actually exist.
			$enqueue_weights = array_intersect( $enqueue_weights, $available_weights );
			$enqueue_weights = array_filter( $enqueue_weights );
			$enqueue_weights = array_unique( $enqueue_weights );

			// Example format: 'Fira Sans:400,400i,500,700,900'
			$font_strings[] = sprintf( '%s:%s', $font_name, implode( ',', $enqueue_weights ) );
		}

		if ( ! empty( $font_strings ) ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $font_strings ) ),
			), 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}

	/**
	 * Converts a font variant into a weight.
	 *
	 * E.g. 'regular' becomes '400', '800i' becomes '800'.
	 *
	 * @since 1.0.0
	 *
	 * @param string $variant
	 *
	 * @return string Default '400'.
	 */
	private function convert_font_variant_to_weight( $variant ) {
		if ( empty( $variant ) || 'regular' === $variant || 'italic' === $variant ) {
			return '400';
		} else {
			$success = preg_match( '/(\d*)(\w*)/', $variant, $matches );
			if ( $success ) {
				$weight = $matches[1];

				if ( empty( $weight ) ) {
					$weight = '400';
				}

				return $weight;
			}
		}

		return '400';
	}

	/**
	 * Converts a font variant into a style.
	 *
	 * @since 1.0.0
	 *
	 * @param $variant
	 *
	 * @return mixed|string
	 */
	private function convert_font_variant_to_style( $variant ) {
		if ( empty( $variant ) || 'regular' === $variant ) {
			return '';
		} elseif ( 'italic' === $variant ) {
			return 'italic';
		} else {
			$success = preg_match( '/(\d*)(\w*)/', $variant, $matches );
			if ( $success ) {
				$style = $matches[2];

				return $style;
			}
		}

		return '';
	}

}
