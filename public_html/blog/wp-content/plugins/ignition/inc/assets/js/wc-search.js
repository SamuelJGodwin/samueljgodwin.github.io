/**
 * Ajax Product Search
 *
 * @since 1.5.0
 */

jQuery( function ( $ ) {
	'use strict';

	var $window = $( window );
	var $body   = $( 'body' );

	var $searchFormWrap = $( '.ignition-wc-search-form-wrap' );
	var $searchForm = $('.ignition-wc-search-form');

	/* -----------------------------------------
	 Ajax Product Search Toggle
	 ----------------------------------------- */
	var $searchTrigger = $('.ignition-wc-search-form-trigger');
	var $searchDismiss = $('.ignition-wc-search-form-dismiss');

	function dismissWCSearch(e) {
		if (e) {
			e.preventDefault();
		}

		$searchFormWrap.removeClass('ignition-wc-search-form-expanded');
		$body.focus();
	}

	function displayWCSearch(e) {
		if (e) {
			e.preventDefault();
		}

		$searchFormWrap
			.addClass('ignition-wc-search-form-expanded')
			.find('input')
			.focus();
	}

	function isWCSearchVisible() {
		return $searchFormWrap.hasClass('ignition-wc-search-form-expanded');
	}

	$searchTrigger.on('click', displayWCSearch);
	$searchDismiss.on('click', dismissWCSearch);

	/* Event propagations */
	$(document).on('keydown', function (e) {
		e = e || window.e;
		if (e.keyCode === 27 && isWCSearchVisible()) {
			dismissWCSearch(e);
		}
	});

	$body
		.on('click', function (e) {
			if (isWCSearchVisible()) {
				dismissWCSearch();
			}
		})
		.find('.ignition-wc-search-form, .ignition-wc-search-form-trigger')
		.on('click', function (e) {
			e.stopPropagation();
		});


	/* -----------------------------------------
	 Ajax Product Search
	 ----------------------------------------- */
	var $categoriesSelect = $('.ignition-wc-search-select');
	var $searchInput = $('.ignition-wc-search-input');
	var $categoryResults = $('.ignition-wc-search-results');
	var $categoryResultsTemplate = $('.ignition-wc-search-results-item');
	var $spinner = $('.ignition-wc-search-spinner');

	function dismissSearchResults() {
		$categoryResults.hide();
	}

	function queryProducts(category, string) {
		return $.ajax({
			url: ignition_wc_search.ajax_url,
			method: 'post',
			data: {
				action: 'ignition_wc_search_products',
				product_cat: category,
				s: string,
			},
		});
	}

	function queryProductsAndPopulateResults(category, string) {
		if (string.trim().length < 3) {
			dismissSearchResults();
			return;
		}

		$searchFormWrap.addClass('is-loading');

		return queryProducts(category, string)
			.done(function (response) {
				$searchFormWrap.removeClass('is-loading');

				if (response.error) {
					var $errorMessage = $categoryResultsTemplate.clone();
					var errorString = response.errors.join(', ');

					$errorMessage.find('.ignition-wc-search-results-item-thumb').remove();
					$errorMessage.find('.ignition-wc-search-results-item-excerpt').remove();
					$errorMessage.find('.ignition-wc-search-results-item-price').remove();

					$errorMessage
						.addClass('error')
						.find('.ignition-wc-search-results-item-title')
						.text(errorString);
					$categoryResults.html($errorMessage).show();

					return;
				}

				var products = response.data;

				if (products.length === 0) {
					var $notFoundMessage = $categoryResultsTemplate.clone();
					$notFoundMessage.find('.ignition-wc-search-results-item-thumb').remove();
					$notFoundMessage.find('.ignition-wc-search-results-item-excerpt').remove();
					$notFoundMessage.find('.ignition-wc-search-results-item-price').remove();
					$notFoundMessage
						.find('.ignition-wc-search-results-item-title')
						.text(ignition_wc_search.search_no_products);
					$categoryResults.html($notFoundMessage).show();

					return;
				}

				var $items = products.map(function (product) {
					var $template = $categoryResultsTemplate.clone();
					$template.find('a').attr('href', product.url);
					if ( ! product.image ) {
						$template.find('.ignition-wc-search-results-item-thumb').remove();
					} else {
						$template.find('.ignition-wc-search-results-item-thumb').html(product.image);
					}
					$template.find('.ignition-wc-search-results-item-title')
						.text(product.title);
					$template.find('.ignition-wc-search-results-item-excerpt')
						.text(product.excerpt);
					$template.find('.ignition-wc-search-results-item-price')
						.html(product.price);

					return $template;
				});

				$categoryResults.html($items);
				$categoryResults.show();
			});
	}

	var debouncedQuery = debounce(queryProductsAndPopulateResults, 500);

	if ($searchForm.hasClass('form-ajax-enabled')) {
		$searchInput.on('change keyup focus', function (event) {
			// Do nothing on arrow up / down as we're using them for navigation
			// Also ignore left/right.
			var ignoreKeys = ['ArrowDown', 'ArrowUp', 'ArrowLeft', 'ArrowRight', 'Escape'];
			if ( ignoreKeys.indexOf( event.key ) >= 0 ) {
				return;
			}

			var $this = $(this);
			var string = $this.val();

			if (string.trim().length < 3) {
				dismissSearchResults();
				return;
			}

			debouncedQuery($categoriesSelect.val(), $this.val());
		});

		// Bind up / down arrow navigation on search results
		$searchInput.on('keydown', function (event) {
			if (event.key === 'Escape') {
				dismissSearchResults();
			}

			if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') {
				return;
			}

			var $items = $categoryResults.children();
			var $highlighted = $categoryResults.find('.highlighted');
			var currentIndex = $highlighted.index();

			if ($items.length === 0 || !$items) {
				return;
			}

			if (event.key === 'ArrowDown') {
				var $next = $items.eq(currentIndex + 1);

				if ($next.length) {
					$items.removeClass('highlighted');
					$next.addClass('highlighted');
				}
				event.preventDefault();
			}

			if (event.key === 'ArrowUp') {
				var $prev = $items.eq(currentIndex - 1);

				if ($prev.length) {
					$items.removeClass('highlighted');
					$prev.addClass('highlighted');
				}
				event.preventDefault();
			}
		});

		// Bind form submit to go the highlighted item on submit
		// instead of normal search
		$searchForm.on('submit', function (event) {
			var $highlighted = $categoryResults.find('.highlighted');

			if ($highlighted.length > 0) {
				event.preventDefault();
				window.location = $highlighted.find('a').attr('href');
			}
		});
	}

	$body.on('click', function () {
		dismissSearchResults();
	}).find('.ignition-wc-search-input, .ignition-wc-search-select').on('click', function (event) {
		event.stopPropagation();
	});

	// Returns a function, that, as long as it continues to be invoked, will not
	// be triggered. The function will be called after it stops being called for
	// N milliseconds. If `immediate` is passed, trigger the function on the
	// leading edge, instead of the trailing.
	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}

} );
