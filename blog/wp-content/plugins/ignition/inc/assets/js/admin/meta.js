jQuery( document ).ready( function ( $ ) {
	var $body = $('body');

	//
	// Datepickers
	//
	if ( $.isFunction( $.fn.datepicker ) ) {
		var ignitionDatePicker = $( '.ignition-datepicker' );

		ignitionDatePicker.each( function() {
			$( this ).datepicker( {
				dateFormat: 'yy-mm-dd'
			} );
		} );
	}

	//
	// Recurring Events
	//
	function ignitionEventsRecurringVisibility() {
		var $event_is_recurring      = $( '#ignition_event_is_recurring' );
		var $event_recurrence_fields = $( '.ignition-event-meta-fields-recurring' );
		var $event_dated_fields      = $( '.ignition-event-meta-fields-dated' );

		if ( $event_is_recurring.prop( 'checked' ) ) {
			$event_recurrence_fields.show();
			$event_dated_fields.hide();
		} else {
			$event_recurrence_fields.hide();
			$event_dated_fields.show();
		}
	}
	$body.on( 'change', '#ignition_event_is_recurring', ignitionEventsRecurringVisibility );
	ignitionEventsRecurringVisibility();

	//
	// Color Pickers
	//
	if ( $.isFunction( $.fn.wpColorPicker ) ) {
		var ignitionColorPicker = $( '.ignition-color-picker' );

		ignitionColorPicker.each( function() {
			$( this ).wpColorPicker();
		} );
	}

	//
	// Tabs
	//
	var INDEX_MAP_SEPARATOR = '_';
	var $tabs = $( '.ignition-meta-tabs' );

	$tabs.each( function () {
		var $this = $(this);
		var $navs = $this
			.find( '.ignition-meta-tabs-nav' )
			.first()
			.find( '.ignition-meta-tabs-nav-item' );
		var $contents = $this.children( '.ignition-meta-tabs-content' );

		$navs.on( 'click', function ( event ) {
			event.preventDefault();
			var $nav    = $( this );
			var index   = $nav.index();
			var $target = $contents.eq( index );

			if ( $target.length && ! $target.is( ':visible' ) ) {
				$nav
					.addClass( 'ignition-meta-tabs-nav-item-active' )
					.siblings()
					.removeClass( 'ignition-meta-tabs-nav-item-active' );
				$contents.hide();
				$target.fadeIn( 'fast' );

				if ( typeof google === 'object' && typeof google.maps === 'object' ) {
					if ( $target.find( '.gllpLatlonPicker' ).length > 0 ) {
						google.maps.event.trigger( window, 'resize', {} );
					}
				}

				updateNavStore(getNavIndexHierarchy($nav));
			}
		} );
	} );

	initializeTabPositions();

	/**
	 * Given a tab navigation item it traverses all parent tabs and
	 * returns an index-based hierarchy.
	 *
	 * @param {jquery} $nav - The tab item jQuery object.
	 * @return {string} - The index based hierarchy, e.g. '1-2', '0-1', etc.
	 */
	function getNavIndexHierarchy( $nav ) {
		var $hierarchy = $nav
			.parents('.ignition-meta-tabs')
			.find('> .ignition-meta-tabs-nav')
			.find('.ignition-meta-tabs-nav-item-active');

		return $hierarchy.map( function () {
			return $( this ).index();
		} )
			.get()
			.reduce(function ( hierarchy, index ) {
				return hierarchy + INDEX_MAP_SEPARATOR + index;
			});
	}

	/**
	 * Updates the nav store with the given value.
	 *
	 * @param {string} value - The value.
	 */
	function updateNavStore( value ) {
		var $input = $('#ignition_current_active_tab');
		$input.val(value)
	}

	/**
	 * Gets a query param from the URL.
	 *
	 * @param {string} param - The param to get.
	 * @return {string}
	 */
	function getURLParam( param ) {
		if (!window.URLSearchParams) {
			return undefined;
		}

		var url = new URLSearchParams(window.location.search);
		return url.get(param);
	}

	/**
	 * Recursively initializes tab positions based on URL param.
	 */
	function initializeTabPositions() {
		var indexMap = getURLParam( 'ignition_tabs' );
		var $root = $('.ignition-meta-tabs').first();

		if ( !indexMap ) {
			return;
		}

		initializeTabPosition( $root, indexMap.split( INDEX_MAP_SEPARATOR ) );
		$root.removeClass( 'loading' );

		function initializeTabPosition( $tab, indexes ) {
			var index = indexes[ 0 ];

			if ( !index || !$tab ) {
				return;
			}

			var $navs = $tab
				.find( '.ignition-meta-tabs-nav' )
				.first( )
				.find( '.ignition-meta-tabs-nav-item' );
			var $childTab = $tab.find( '.ignition-meta-tabs' ).eq( index );

			$navs.eq( index ).trigger( 'click' );
			initializeTabPosition( $childTab, indexes.slice( 1 ) );
		}
	}

	//
	// Field validation
	//
	var $controls = $('.ignition-setting-control');
	$controls.on('change keyup', '[type="number"]', function () {
		var $this = $(this);
		var min = parseFloat($this.attr('min'));
		var max = parseFloat($this.attr('max'));
		var value = parseFloat($this.val());

		if (value < min || value > max) {
			var message = 'Please enter a value between ' + min + ' and ' + max + '.';
			attachControlAlert($this, message);

			if (value < min) {
				$this.val(min);
			}

			if (value > max) {
				$this.val(max);
			}

			return;
		}

		detachControlAlert($this);
	});

	/**
	 * Attaches a danger alert to a setting's control.
	 *
	 * @param {jQuery} $input - The input whose parent should get have the alert attached.
	 * @param {string} message - The message to display.
	 */
	function attachControlAlert($input, message) {
		var $parent = $input.closest('.ignition-setting-control');
		var $existingAlert = $parent.find('.ignition-alert');

		if ($existingAlert.length > 0) {
			$existingAlert.text(message);
			return;
		}

		var $alert = $('<div />', {
			class: 'ignition-alert ignition-alert-danger',
			text: message,
		});
		$parent.prepend($alert);
	}

	/**
	 * Remove any alerts from a setting's control.
	 *
	 * @param {jQuery} $input - The input whose parent should get have the alert detached.
	 */
	function detachControlAlert($input) {
		var $parent = $input.closest('.ignition-setting-control');
		var $alert = $parent.find('.ignition-alert');

		if ($alert.length > 0) {
			$parent.find('.ignition-alert').remove();
		}
	}

	//
	// Repeatable fields
	//
	var $buttonAdd = $( '.ignition-repeatable-add-button' );
	var $buttonRemove = $( '.ignition-repeatable-row-dismiss' );
	var $filtersTypeSelect = $( '.ignition-hook-select-type' );
	var $locationsTypeSelect = $( '.ignition-hook-select-location' );

	if ( window.IgnitionGlobalSection ) {
		var filterFields = window.IgnitionGlobalSection.includes;
		$filtersTypeSelect.on( 'change', function () {
			var $this = $( this );
			var selection = $this.val();
			var field = filterFields.find(function ( field ) {
				return field.option_value === selection;
			} );

			var $entrySelect = $this
				.parents('.ignition-repeatable-row')
				.find('.ignition-hook-select-entry');

			if ( field && field.entries ) {
				var $options = field.entries.map(function ( entry ) {
					return $('<option />', {
						value: entry.option_value,
					}).text( entry.name );
				} );

				$entrySelect
					.html('')
					.append( $options )
					.parent()
					.fadeIn( 'fast' );
			} else {
				$entrySelect.html('').parent().hide();
			}
		} );
	}

	$locationsTypeSelect.on( 'change', showHideCustomHookInput );
	$locationsTypeSelect.each( showHideCustomHookInput );

	/**
	 * Shows or hides the custom hook input of Global Section locations.
	 */
	function showHideCustomHookInput() {
		var $this = $( this );
		var value = $this.val();
		var $hookInput = $this
			.parents( '.ignition-repeatable-row' )
			.find( '.ignition-hook-input-hook' );

		if ( value === 'custom' ) {
			$hookInput.fadeIn( 'fast' );
		} else {
			$hookInput.fadeOut( 'fast' );
		}
	}

	$buttonAdd.each( function () {
		var $this = $( this );

		$this.on( 'click', function ( event ) {
			event.preventDefault();

			var $wrap = $this.parent();
			var $fields = $wrap.find('.ignition-repeatable-fields');
			var $template = $wrap
				.find( '.ignition-repeatable-template' )
				.clone(true)
				.removeClass( 'ignition-repeatable-template' );

			var $subs = $template.find('[name*="__id__"]');
			var rand_id = (Math.random() * 10e16).toString();

			$subs.each( function () {
				var $this = $( this );
				$this.attr( 'name', $this.attr( 'name' ).replace('__id__', rand_id ) );
			});

			$fields.append( $template );
		} );
	} );

	$buttonRemove.on( 'click', function ( event ) {
		event.preventDefault();

		var $this = $( this );
		$this.parent().remove();
	} );
} );
