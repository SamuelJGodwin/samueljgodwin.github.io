/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview changes asynchronously.
 *
 * https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#using-postmessage-for-improved-setting-previewing
 */

(function ( $ ) {
	var IGNITION_PREVIEW_SCRIPTS = {
		/**
		 * The available breakpoints.
		 * Note that the values are localized by PHP as strings. Use parseInt() when referencing them.
		 *
		 * @enum breakpoints
		 */
		breakpoints: IGNITION_BREAKPOINTS,

		/**
		 * Checks if a value is undefined, null, or empty string.
		 *
		 * @param {*} value - The value.
		 * @returns {boolean}
		 */
		isNil: function ( value ) {
			return value == null || value === '';
		},

		/**
		 * Injects a namespaced stylesheet in the <body> element of the document
		 * or replaces its contents with the provided styles if it already exists.
		 *
		 * @param {string} settingName - The setting's name.
		 * @param {string} styles - A string of generated styles.
		 */
		injectStylesheet: function ( settingName, styles ) {
			var $stylesheet = $( 'style.' + settingName );

			if ( $stylesheet.length ) {
				$stylesheet.text( styles );
			} else {
				$( '<style />', {
					class: settingName,
				} ).text( styles ).appendTo( 'body' );
			}
		},

		sanitizeClassName: function ( name ) {
			return name.replace( /[^a-z0-9]/g, function ( s ) {
				var c = s.charCodeAt( 0 );
				if ( c == 32 ) {
					return '-';
				}
				if ( c >= 65 && c <= 90 ) {
					return '_' + s.toLowerCase();
				}
				return '__' + ('000' + c.toString( 16 )).slice( - 4 );
			} );
		},

		injectGoogleFont: function ( fontName, weight ) {
			var className = 'ignition_preview_font_' + this.sanitizeClassName( fontName );

			var $stylesheet = $( 'link.' + className );
			var href_base = 'https://fonts.googleapis.com/css?family=' + encodeURIComponent( fontName );

			if ( ! $stylesheet.length ) {
				$( '<link />', {
					class: className,
					href: href_base + ':' + weight,
					rel: 'stylesheet',
					'data-weights': weight,
				} ).appendTo( 'body' );
			} else {
				var weights = String( $stylesheet.data( 'weights' ) );
				weights = weights.split( ',' );
				weights.push( weight );
				var old_weights = weights;
				weights = weights.filter( function ( item, pos ) {
					// Keep unique entries.
					return old_weights.indexOf( item ) === pos;
				} );
				weights = weights.join( ',' );
				$stylesheet.data( 'weights', weights );
				$stylesheet.attr( 'href', href_base + ':' + weights );
			}
		},

		/**
		 * Wraps a set of style rules in a breakpoint media query (if necessary).
		 *
		 * @param {breakpoints} breakpoint - A breakpoint.
		 * @param {string} styles - The CSS rules (styles).
		 * @returns {string} - The CSS rules optionally wrapped in a media query.
		 */
		wrapMediaQuery: function ( breakpoint, styles ) {
			if (breakpoint === 'desktop') {
				return styles;
			}

			return '@media (max-width: ' + parseInt( this.breakpoints[breakpoint] ) + 'px) { ' + styles + ' }';
		},

		/**
		 * Wraps a set of style rules in a breakpoint media query that applies
		 * only the the particular media query.
		 *
		 * @param {breakpoints} breakpoint - A breakpoint.
		 * @param {string} styles - The CSS rules (styles).
		 * @returns {string} - The CSS rules optionally wrapped in a media query.
		 */
		wrapMediaQueryOnly: function ( breakpoint, styles ) {
			var desktopMin = parseInt( this.breakpoints['tablet'] ) + 1;
			var tabletMax  = parseInt( this.breakpoints['tablet'] );
			var tabletMin  = parseInt( this.breakpoints['mobile'] ) + 1;
			var mobileMax  = parseInt( this.breakpoints['mobile'] );

			if (breakpoint === 'desktop') {
				return '@media (min-width: ' + desktopMin + 'px) { ' + styles + ' }';
			}

			if (breakpoint === 'tablet') {
				return '@media (min-width: ' + tabletMin + 'px) and (max-width: ' + tabletMax + 'px) { ' + styles + ' }';
			}

			if (breakpoint === 'mobile') {
				return '@media (max-width: ' + mobileMax + 'px) { ' + styles + ' }';
			}
		},

		/**
		 * Adds CSS, if the value is not empty.
		 * If the placeholder %s is found in `css`, it will be replaced with the value.
		 *
		 * @param {string} name - The variable's name.
		 * @param {string} value - The variable's value.
		 */
		add_variable: function ( name, value ) {
			if ( this.isNil( value ) ) {
				return '';
			}

			return ':root { ' + name + ': ' + value + '; }';
		},

		/**
		 * Adds CSS, if the value is not empty.
		 * If the placeholder %s is found in `css`, it will be replaced with the value.
		 *
		 * @param {string} breakpoint - The breakpoint to apply the css rule.
		 * @param {string} value - The value to be added.
		 * @param {string} css - CSS rule. May contain the placeholder %s which will be replaced with the value.
		 * @param {string} breakpoint_limit - Prevent cascading the CSS to the smaller breakpoints..
		 */
		add_value: function ( breakpoint, value, css, breakpoint_limit ) {
			var style = '';

			if ( this.isNil( value ) ) {
				return '';
			}

			breakpoint_limit = typeof breakpoint_limit !== 'undefined' ? breakpoint_limit : false;

			css = css.split( '%s' ).join( value );

			if ( breakpoint_limit ) {
				style += this.wrapMediaQueryOnly( breakpoint, css );
			} else {
				style += this.wrapMediaQuery( breakpoint, css );
			}

			return style;
		},

		/**
		 * Adds CSS for simple breakpoint structured values (i.e. breakpoint => value array).
		 * Can accept a string for $breakpoints_css to apply to all breakpoint, or an array for each individual one.
		 *
		 * @param {object}        values           breakpoint => value array.
		 * @param {string|object} breakpoints_css  breakpoint => css array to apply if value exists.
		 * @param {bool}          breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
		 * @param {object}        edge_cases       Array where the key is an edge case value, and the value is the CSS rule to apply.
		 */
		add_responsive: function ( values, breakpoints_css, breakpoint_limit, edge_cases ) {

			breakpoint_limit = typeof breakpoint_limit !== 'undefined' ? breakpoint_limit : false;
			edge_cases       = typeof edge_cases !== 'undefined' ? edge_cases : {};

			if ( typeof values !== 'object' || this.isNil( values ) ) {
				return '';
			}

			var style = '';
			var that  = this;

			_.forEach( values, function( value, breakpoint ) {
				if ( ! that.isNil( value ) ) {
					if ( edge_cases.hasOwnProperty( value ) ) {
						style += that.add_value( breakpoint, value, edge_cases[value], breakpoint_limit );
					} else {
						if ( 'string' === typeof breakpoints_css ) {
							style += that.add_value( breakpoint, value, breakpoints_css, breakpoint_limit );
						} else if ( 'object' === typeof breakpoints_css ) {
							style += that.add_value( breakpoint, value, breakpoints_css[breakpoint], breakpoint_limit );
						}
					}
				}
			} );

			return style;
		},

		/**
		 * Adds CSS for the spacing control.
		 * Can accept a string for breakpoints_css to apply to all breakpoints, or an array for each individual one.
		 *
		 * @param {object}        values           breakpoint => value array.
		 * @param {string|object} breakpoints_css  breakpoint => css array to apply if value exists.
		 * @param {string}        mode             The property to generate the rules for (something that gets -top, -right, etc). E.g. 'margin', 'padding', etc.
		 *                                         Special case 'position' generates top: right: etc rules without a prefix-. Default 'margin'.
		 * @param {string}        unit             The unit to suffix the values with. E.g. 'px', 'em', etc. Default 'px'.
		 * @param {bool}          breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
		 */
		add_spacing: function ( values, breakpoints_css, mode, unit, breakpoint_limit ) {
			mode             = typeof mode !== 'undefined' ? mode : 'margin';
			unit             = typeof unit !== 'undefined' ? unit : 'px';
			breakpoint_limit = typeof breakpoint_limit !== 'undefined' ? breakpoint_limit : false;

			if ( typeof mode !== 'string' || this.isNil( mode ) ) {
				return '';
			}

			if ( typeof values !== 'object' || this.isNil( breakpoints_css ) ) {
				return '';
			}

			var style = '';
			var that  = this;

			_.forEach( values, function( value, breakpoint ) {
				if ( ! that.isNil( value ) ) {
					var rules = that.generate_spacing_rules( value, mode, unit );

					if ( 'string' === typeof breakpoints_css ) {
						style += that.add_value( breakpoint, rules, breakpoints_css, breakpoint_limit );
					} else if ( 'object' === typeof breakpoints_css && breakpoints_css.hasOwnProperty( breakpoint ) ) {
						style += that.add_value( breakpoint, rules, breakpoints_css[breakpoint], breakpoint_limit );
					}
				}
			} );

			return style;
		},

		/**
		 * Generates spacing rules.
		 *
		 * @param {object} values The array of values for a single breakpoint.
		 * @param {string} mode   The property to generate the rules for (something that gets -top, -right, etc). E.g. 'margin', 'padding', etc.
		 *                        Special case 'position' generates top: right: etc rules without a prefix-. Default 'margin'.
		 * @param {string} unit   The unit to suffix the values with. E.g. 'px', 'em', etc. Default 'px'.
		 *
		 * @return {string} The CSS rules.
		 */
		generate_spacing_rules: function ( values, mode, unit ) {
			if ( typeof values !== 'object' || this.isNil( values ) ) {
				return '';
			}

			var rules  = {};
			var linked = false;
			var prefix = mode + '-';
			var that  = this;

			if ( values.hasOwnProperty( 'linked' ) ) {
				linked = values.linked;
				delete values.linked;
			}

			if ( 'position' === mode ) {
				prefix = '';
			}

			_.forEach( values, function( value, direction ) {
				if ( ! that.isNil( value ) ) {

					var property = prefix + direction;

					if ( 0 === parseInt( value, 10 ) ) {
						rules[ property ] = value;
					} else {
						rules[ property ] = value + unit;
					}
				}
			} );

			return _.reduce( rules, function ( css, value, key ) {
				return css + ' ' + key + ':' + value + ';';
			}, '' );
		},

		/**
		 * Generates a string of CSS rules for the typography control.
		 *
		 * @param {object}        values           The array of values, as returned by the typography control. It should include all breakpoints.
		 * @param {string}        fallback_stack   Array or comma-separated string of fallback font names. It's considered only when 'family' is set for a breakpoint.
		 * @param {string|object} breakpoints_css  breakpoint => css array to apply if value exists.
		 * @param {bool}          breakpoint_limit Prevent cascading the CSS to the smaller breakpoints.
		 *
		 * @return {string} The CSS rules.
		 */
		add_typography: function ( values, fallback_stack, breakpoints_css, breakpoint_limit) {
			breakpoint_limit = typeof breakpoint_limit !== 'undefined' ? breakpoint_limit : false;

			if ( typeof values !== 'object' || this.isNil( breakpoints_css ) ) {
				return '';
			}

			var style = '';
			var that  = this;

			_.forEach( values, function( value, breakpoint ) {
				if ( ! that.isNil( value ) && typeof value === 'object' ) {
					var rules = that.generate_typography_rules( value, fallback_stack );

					if ( 'string' === typeof breakpoints_css ) {
						style += that.add_value( breakpoint, rules, breakpoints_css, breakpoint_limit );
					} else if ( 'object' === typeof breakpoints_css && breakpoints_css.hasOwnProperty( breakpoint ) ) {
						style += that.add_value( breakpoint, rules, breakpoints_css[breakpoint], breakpoint_limit );
					}
				}
			} );

			return style;
		},

		/**
		 * Generates the appropriate CSS rules for "typography" controls, for a single breakpoint.
		 *
		 * @param {object}        values           The array of values for a single breakpoint.
		 * @param {string|object}        fallback_stack   Array or comma-separated string of fallback font names. It's considered only when 'family' is set for a breakpoint.
		 *
		 * @return {string} The CSS rules.
		 */
		generate_typography_rules: function ( values, fallback_stack ) {
			fallback_stack = typeof fallback_stack !== 'undefined' ? fallback_stack : '';

			var rules = [];

			if ( values.hasOwnProperty( 'family' ) && ! this.isNil( values.family ) && values.hasOwnProperty( 'variant' ) && ! this.isNil( values.variant ) && values.hasOwnProperty( 'is_gfont' ) && true === values.is_gfont ) {
				this.injectGoogleFont( values.family, values.variant );
			}

			if ( values.hasOwnProperty( 'family' ) && ! this.isNil( values.family ) ) {
				var stack = values.family
					.split( ',' )
					.map( function ( s ) {
						var trimmed = s.trim();

						if ( trimmed.indexOf( ' ' ) > - 1 ) {
							return '"' + trimmed + '"';
						}

						return trimmed;
					} );

				if ( ! this.isNil( fallback_stack ) && typeof fallback_stack === 'string' ) {
					var fallback_stack_array = fallback_stack
						.split( ',' )
						.map( function ( s ) {
							return s.trim();
						} );
				}

				var all_stack = stack;

				if ( ! this.isNil( fallback_stack ) && typeof fallback_stack_array !== 'undefined' && fallback_stack_array.length > 0 ) {
					all_stack = [].concat( stack, fallback_stack_array );
				}

				if ( all_stack.length > 0 ) {
					rules.push( 'font-family: ' + all_stack.join( ', ' ) + ';' );
				}

			}

			if ( values.hasOwnProperty( 'variant' ) && ! this.isNil( values.variant ) ) {
				var weight = this.convert_font_variant_to_weight( values.variant );
				var style = this.convert_font_variant_to_style( values.variant );

				if ( weight ) {
					rules.push( 'font-weight: ' + weight + ';' );
				}

				if ( style ) {
					rules.push( 'font-style: ' + style + ';' );
				}
			}

			if ( ! this.isNil( values.size ) ) {
				rules.push( 'font-size: ' + values.size + 'px;' );
			}

			if ( ! this.isNil( values.lineHeight ) ) {
				rules.push( 'line-height: ' + values.lineHeight + ';' )
			}

			if ( ! this.isNil( values.transform ) ) {
				rules.push( 'text-transform: ' + values.transform + ';' );
			}

			if ( ! this.isNil( values.spacing ) ) {
				rules.push( 'letter-spacing: ' + values.spacing + 'em;' );
			}

			return rules.join( ' ' );
		},

		/**
		 * Given a font variant (as defined in fonts.json) returns the font weight.
		 *
		 * @param {string} variant - The font variant setting.
		 * @return {string} - The font weight.
		 */
		convert_font_variant_to_weight: function ( variant ) {
			if ( this.isNil( variant ) || variant === 'regular' || variant === 'italic' ) {
				return '400';
			} else {
				var matches = variant.match( /(\d*)(\w*)/ );
				if ( matches && matches[1] ) {
					return matches[1];
				}

			}

			return '400';
		},

		/**
		 * Given a font variant (as defined in fonts.json) returns the font style.
		 *
		 * @param {string} variant - The font variant setting.
		 * @return {string} - The font style.
		 */
		convert_font_variant_to_style: function ( variant ) {
			if ( this.isNil( variant ) || variant === 'regular' ) {
				return '';
			} else if ( variant === 'italic' ) {
				return 'italic';
			} else {
				var matches = variant.match( /(\d*)(\w*)/ );
				if ( matches && matches[2] ) {
					return matches[2];
				}

			}

			return '';
		},

		/**
		 * Extracts the values of a list of properties from a typography control array.
		 *
		 * @param {object} values - A typography control array.
		 * @param {array} properties - A list of properties.
		 * @return {string} - The font style.
		 */
		typography_control_extract_properties: function ( values, properties ) {
			var new_values = {};

			_.forEach( values, function( breakpoint_values, breakpoint ) {
				_.forEach( breakpoint_values, function( value, property ) {
					if ( properties.indexOf( property ) > -1 ) {
						if (!new_values[breakpoint]) {
							new_values[breakpoint] = {};
						}

						new_values[breakpoint][property] = value;
					}
				} );
			} );

			return new_values;
		},

		/**
		 * Add CSS for the image background control.
		 *
		 * @param {object} values - The array of values, as returned by the image-bg control.
		 * @param {string} image_size - Image size name, e.g. 'post-thumbnail'.
		 * @param {string} css - CSS selector to apply if value exists.
		 * @param {string} background_color - Optional background color to include in the generated CSS.
		 *
		 * @return {string} The CSS rules.
		 */
		add_image_background_by_id: function ( values, image_size, css, background_color ) {
			// It's expensive to determine the image's URL via JS, so we use the user-select size instead.
			return this.add_image_background_by_url( values, css, background_color );
		},

		/**
		 * Add CSS for the image background control. Uses the actual size selected by the user.
		 *
		 * @param {object} values - The array of values, as returned by the image-bg control.
		 * @param {string} css - CSS selector to apply if value exists.
		 * @param {string} background_color - Optional background color to include in the generated CSS.
		 *
		 * @return {string} The CSS rules.
		 */
		add_image_background_by_url: function ( values, css, background_color ) {
			background_color = typeof background_color !== 'undefined' ? background_color : '';

			if ( typeof values !== 'object' || this.isNil( css ) ) {
				return '';
			}

			var url = '';
			if ( ! this.isNil( values.image_url ) ) {
				url = values.image_url
			}

			var style = '';

			var rules = this.generate_image_background_rules( values, url, background_color );

			if ( ! this.isNil( rules ) ) {
				style += this.add_value( 'desktop', rules, css );
			}

			return style;
		},

		/**
		 * Generates the appropriate CSS rules for "image-background" controls.
		 *
		 * @param {object} values - The array of values, as returned by the image-bg control.
		 * @param {string} image_url - The image url to use as background.
		 * @param {string} background_color - Optional background color.
		 *
		 * @return {string} The CSS rules.
		 */
		generate_image_background_rules: function ( values, image_url, background_color ) {
			var rules = [];

			if ( ! this.isNil( values.background_color ) ) {
				rules.push( 'background-color: ' + background_color + ';' );
			}

			if ( ! this.isNil( image_url ) ) {
				rules.push( 'background-image: url(' + image_url + ');' );

				if ( ! this.isNil( values.repeat ) ) {
					rules.push( 'background-repeat: ' + values.repeat + ';' );
				}

				if ( ! this.isNil( values.position ) ) {
					rules.push( 'background-position: ' + values.position + ';' );
				}

				if ( ! this.isNil( values.attachment ) ) {
					rules.push( 'background-attachment: ' + values.attachment + ';' );
				}

				if ( ! this.isNil( values.size ) ) {
					rules.push( 'background-size: ' + values.size + ';' );
				}
			}

			return rules.join( ' ' );
		},

		hex2rgb: function ( colour ) {
			var r, g, b;
			if ( colour.charAt( 0 ) == '#' ) {
				colour = colour.substr( 1 );
			}
			if ( colour.length == 3 ) {
				colour = colour.substr( 0, 1 ) + colour.substr( 0, 1 ) + colour.substr( 1, 2 ) + colour.substr( 1, 2 ) + colour.substr( 2, 3 ) + colour.substr( 2, 3 );
			}
			r = colour.charAt( 0 ) + '' + colour.charAt( 1 );
			g = colour.charAt( 2 ) + '' + colour.charAt( 3 );
			b = colour.charAt( 4 ) + '' + colour.charAt( 5 );
			r = parseInt( r, 16 );
			g = parseInt( g, 16 );
			b = parseInt( b, 16 );
			return r + ',' + g + ',' + b;
		}

	};

	if ( ! window.IGNITION_PREVIEW_SCRIPTS ) {
		window.IGNITION_PREVIEW_SCRIPTS = IGNITION_PREVIEW_SCRIPTS;
	}

})( jQuery );
