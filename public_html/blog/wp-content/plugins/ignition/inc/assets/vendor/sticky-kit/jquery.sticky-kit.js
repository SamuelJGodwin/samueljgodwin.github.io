"use strict";

/**
 * @license Sticky-kit v1.1.4 | MIT | Leaf Corcoran 2015 | http://leafo.net
 * @edited Edited by the CSSIgniter Team
 */
var $ = window.jQuery;
var win = $(window);
var doc = $(document);

$.fn.stick_in_parent = function(opts) {
  if (opts == null) {
    opts = {};
  }

  var _opts = opts,
    sticky_class = _opts.sticky_class,
    inner_scrolling = _opts.inner_scrolling,
    recalc_every = _opts.recalc_every,
    parent_selector = _opts.parent,
    offset_top = _opts.offset_top,
    manual_spacer = _opts.spacer,
    enable_bottoming = _opts.bottoming;
  var win_height = win.height();
  var doc_height = doc.height();

  if (offset_top == null) {
    offset_top = 0;
  }

  if (parent_selector == null) {
    parent_selector = undefined;
  }

  if (inner_scrolling == null) {
    inner_scrolling = true;
  }

  if (sticky_class == null) {
    sticky_class = "is_stuck";
  }

  if (enable_bottoming == null) {
    enable_bottoming = true;
  } // we need this because jquery's version (along with css()) rounds everything

  var outer_width = function outer_width(el) {
    if (window.getComputedStyle) {
      var _el = el[0];
      var computed = window.getComputedStyle(el[0]);
      var w =
        parseFloat(computed.getPropertyValue("width")) +
        parseFloat(computed.getPropertyValue("margin-left")) +
        parseFloat(computed.getPropertyValue("margin-right"));

      if (computed.getPropertyValue("box-sizing") !== "border-box") {
        w +=
          parseFloat(computed.getPropertyValue("border-left-width")) +
          parseFloat(computed.getPropertyValue("border-right-width")) +
          parseFloat(computed.getPropertyValue("padding-left")) +
          parseFloat(computed.getPropertyValue("padding-right"));
      }

      return w;
    } else {
      return el.outerWidth(true);
    }
  };

  var _arr = Array.from(this);

  for (var _i = 0; _i < _arr.length; _i++) {
    var elm = _arr[_i];

    (function(
      elm,
      padding_bottom,
      parent_top,
      parent_height,
      top,
      height,
      el_float,
      detached
    ) {
      if (elm.data("sticky_kit")) {
        return;
      }

      elm.data("sticky_kit", true);
      var last_scroll_height = doc_height;
      var parent = elm.parent();

      if (parent_selector != null) {
        parent = parent.closest(parent_selector);
      }

      if (!parent.length) {
        throw "failed to find stick parent";
      }

      var fixed = false;
      var bottomed = false;
      var spacer =
        manual_spacer != null
          ? manual_spacer && elm.closest(manual_spacer)
          : $("<div />", {
              class: "stuck"
            });

      if (spacer) {
        spacer.css("position", elm.css("position"));
      }

      var recalc = function recalc() {
        var restore;

        if (detached) {
          return;
        }

        win_height = win.height();
        doc_height = doc.height();
        last_scroll_height = doc_height;
        var border_top = parseInt(parent.css("border-top-width"), 10);
        var padding_top = parseInt(parent.css("padding-top"), 10);
        padding_bottom = parseInt(parent.css("padding-bottom"), 10);
        parent_top = parent.offset().top + border_top + padding_top;
        parent_height = parent.height();

        if (fixed) {
          fixed = false;
          bottomed = false;

          if (manual_spacer == null) {
            elm.insertAfter(spacer);
            spacer.detach();
          }

          elm
            .css({
              position: "",
              top: "",
              width: "",
              bottom: ""
            })
            .removeClass(sticky_class);
          restore = true;
        }

        top =
          elm.offset().top -
          (parseInt(elm.css("margin-top"), 10) || 0) -
          offset_top;
        height = elm.outerHeight(true);
        el_float = elm.css("float");

        if (spacer) {
          spacer.css({
            width: outer_width(elm),
            height: height,
            display: elm.css("display"),
            "vertical-align": elm.css("vertical-align"),
            float: el_float
          });
        }

        if (restore) {
          return tick();
        }
      };

      recalc();

      if (height === parent_height) {
        return;
      }

      var last_pos = undefined;
      var offset = offset_top;
      var recalc_counter = recalc_every;

      var tick = function tick() {
        var css, delta, will_bottom;

        if (detached) {
          return;
        }

        var recalced = false;

        if (recalc_counter != null) {
          recalc_counter -= 1;

          if (recalc_counter <= 0) {
            recalc_counter = recalc_every;
            recalc();
            recalced = true;
          }
        }

        if (!recalced && doc_height !== last_scroll_height) {
          recalc();
          recalced = true;
        }

        var scroll = win.scrollTop();

        if (last_pos != null) {
          delta = scroll - last_pos;
        }

        last_pos = scroll;

        if (fixed) {
          if (enable_bottoming) {
            will_bottom = scroll + height + offset > parent_height + parent_top; // unbottom

            if (bottomed && !will_bottom) {
              bottomed = false;
              elm
                .css({
                  position: "fixed",
                  bottom: "",
                  top: offset
                })
                .trigger("sticky_kit:unbottom");
            }
          } // unfixing

          if (scroll < top) {
            fixed = false;
            offset = offset_top;

            if (manual_spacer == null) {
              if (el_float === "left" || el_float === "right") {
                elm.insertAfter(spacer);
              }

              spacer.detach();
            }

            css = {
              position: "",
              width: "",
              top: ""
            };
            elm
              .css(css)
              .removeClass(sticky_class)
              .trigger("sticky_kit:unstick");
          } // updated offset

          if (inner_scrolling) {
            if (height + offset_top > win_height) {
              // bigger than viewport
              if (!bottomed) {
                offset -= delta;
                offset = Math.max(win_height - height, offset);
                offset = Math.min(offset_top, offset);

                if (fixed) {
                  elm.css({
                    top: offset + "px"
                  });
                }
              }
            }
          }
        } else {
          // fixing
          if (scroll > top) {
            fixed = true;
            css = {
              position: "fixed",
              top: offset
            };
            css.width =
              elm.css("box-sizing") === "border-box"
                ? elm.outerWidth() + "px"
                : elm.width() + "px";
            elm.css(css).addClass(sticky_class);

            if (manual_spacer == null) {
              elm.after(spacer);

              if (el_float === "left" || el_float === "right") {
                spacer.append(elm);
              }
            }

            elm.trigger("sticky_kit:stick");
          }
        } // this is down here because we can fix and bottom in same step when
        // scrolling huge

        if (fixed && enable_bottoming) {
          if (will_bottom == null) {
            will_bottom = scroll + height + offset > parent_height + parent_top;
          } // bottomed

          if (!bottomed && will_bottom) {
            // bottomed out
            bottomed = true;

            if (parent.css("position") === "static") {
              parent.css({
                position: "relative"
              });
            }

            return elm
              .css({
                position: "absolute",
                bottom: padding_bottom,
                top: "auto"
              })
              .trigger("sticky_kit:bottom");
          }
        }
      };

      var recalc_and_tick = function recalc_and_tick() {
        recalc();
        return tick();
      };

      var detach = function detach() {
        detached = true;
        win.off("touchmove", tick);
        win.off("scroll", tick);
        win.off("resize", recalc_and_tick);
        $(document.body).off("sticky_kit:recalc", recalc_and_tick);
        elm.off("sticky_kit:detach", detach);
        elm.removeData("sticky_kit");
        elm.css({
          position: "",
          bottom: "",
          top: "",
          width: ""
        });
        parent.position("position", "");

        if (fixed) {
          if (manual_spacer == null) {
            if (el_float === "left" || el_float === "right") {
              elm.insertAfter(spacer);
            }

            spacer.remove();
          }

          return elm.removeClass(sticky_class);
        }
      };

      win.on("touchmove", tick);
      win.on("scroll", tick);
      win.on("resize", recalc_and_tick);
      $(document.body).on("sticky_kit:recalc", recalc_and_tick);
      elm.on("sticky_kit:detach", detach);
      return setTimeout(tick, 0);
    })($(elm));
  }

  return this;
};
