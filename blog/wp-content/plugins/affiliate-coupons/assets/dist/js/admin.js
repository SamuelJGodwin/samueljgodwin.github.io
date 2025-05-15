/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/admin/index.js":
/*!***********************************!*\
  !*** ./assets/src/admin/index.js ***!
  \***********************************/
/***/ (() => {

/**
 * Controls
 * Coupons Overview: Reset Stats
 * Settings: Delete Log
 * Settings: Move wp admin notices above the Settings Nav
 * Settings: Handle "review request" admin notice buttons click
 */
jQuery(document).ready(function ($) {
  /**
   * Controls
   */
  $(".affcoups-input-colorpicker").wpColorPicker();

  /**
   * Coupons Overview: Reset Stats
   */
  $('[data-affcoups-settings-nav]').on("click", function (event) {
    // Don't follow link
    event.preventDefault();
    var navItems = $('.affcoups-settings-nav-item');
    var navItem = $(this).parent('li');
    var tab = $(this).data('affcoups-settings-nav');
    if (!tab) return;
    var content = $('[data-affcoups-settings-content="' + tab + '"]');
    if (!content) return;
    var contentItems = $('.affcoups-settings-content');
    if (!contentItems) return;

    // Remove active class from all nav items and containers
    $('[data-affcoups-settings-nav]').removeClass('active');
    navItems.removeClass('active');
    contentItems.removeClass('active');

    // Add active class to nav item and target container
    $(this).addClass('active');
    navItem.addClass('active');
    content.addClass('active');
    var inputActiveTab = $('#affcoups_settings_active_tab');
    if (inputActiveTab) inputActiveTab.val(tab);

    // Update url
    if (typeof history.pushState !== "undefined") {
      // Get current URL
      var currentURL = window.location.href;

      // Prepare new URL
      var newURL = new URL(currentURL);

      // Set or replace URL parameter
      newURL.searchParams.set('tab', tab);

      // Change URL without reloading
      history.pushState({}, null, newURL);
    }
  });

  /**
   * Settings: Delete Log
   */
  $(document).on('click', '#affcoups-delete-log-submit', function (event) {
    $('#affcoups-delete-log').val('1');
  });

  /**
   * Settings: Move wp admin notices above the Settings Nav
   */
  setTimeout(function () {
    $('#wpbody-content > .affcoups-settings .affcoups-settings-content > .card > .error, .notice').insertBefore($('#affcoups-admin-page'));
  }, 10);

  /**
   * Settings: Handle "review request" admin notice buttons click
   */
  $('.affcoups-notice-btn').on('click', function (event) {
    var nonce,
      action = $(this).data('action'),
      $this = $(this);
    if (!action) return;
    if ('affcoups_remove_review_request' === action) {
      nonce = affcoups_admin_post.nonce.remove_review_request;
    } else if ('affcoups_hide_review_request' === action) {
      nonce = affcoups_admin_post.nonce.hide_review_request;
    }
    $.ajax({
      type: "POST",
      dataType: 'JSON',
      url: affcoups_admin_post.ajax_url,
      data: {
        action: action,
        _wpnonce: nonce
      }
    }).done(function (response) {
      if (response.error) {
        console.log('response.error:');
        console.log(response.error);
        return false;
      }
      $this.closest('.affcoups-notice').fadeOut('slow');
    }).fail(function (jqXHR, textStatus) {
      console.log("Request failed: " + textStatus);
    });
  });
});

/***/ }),

/***/ "./assets/src/admin/index.less":
/*!*************************************!*\
  !*** ./assets/src/admin/index.less ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/src/public/index.less":
/*!**************************************!*\
  !*** ./assets/src/public/index.less ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/src/public/index-amp.less":
/*!******************************************!*\
  !*** ./assets/src/public/index-amp.less ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/admin": 0,
/******/ 			"css/amp": 0,
/******/ 			"css/main": 0,
/******/ 			"css/admin": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkaffiliate_coupons"] = self["webpackChunkaffiliate_coupons"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/amp","css/main","css/admin"], () => (__webpack_require__("./assets/src/admin/index.js")))
/******/ 	__webpack_require__.O(undefined, ["css/amp","css/main","css/admin"], () => (__webpack_require__("./assets/src/admin/index.less")))
/******/ 	__webpack_require__.O(undefined, ["css/amp","css/main","css/admin"], () => (__webpack_require__("./assets/src/public/index.less")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/amp","css/main","css/admin"], () => (__webpack_require__("./assets/src/public/index-amp.less")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;