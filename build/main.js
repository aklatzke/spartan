/*Please JS, Jordan Checkman 2014, Checkman.io, MIT Liscense, Have fun.*/
"use strict";

(function (e) {
  "use strict";function t() {
    function i(e, t) {
      return Math.floor(Math.random() * (t - e + 1)) + e;
    }function s(e, t) {
      return Math.random() * (t - e) + e;
    }function o(e, t, n) {
      if (e < t) {
        e = t;
      } else if (e > n) {
        e = n;
      }return e;
    }function u(t, n) {
      switch (t) {case "hex":
          for (var r = 0; r < n.length; r++) {
            n[r] = e.HSV_to_HEX(n[r]);
          }break;case "rgb":
          for (var r = 0; r < n.length; r++) {
            n[r] = e.HSV_to_RGB(n[r]);
          }break;case "rgb-string":
          for (var r = 0; r < n.length; r++) {
            var i = e.HSV_to_RGB(n[r]);n[r] = "rgb(" + i.r + "," + i.g + "," + i.b + ")";
          }break;case "hsv":
          break;default:
          console.log("Format not recognized.");break;}return n;
    }function a(e) {
      var t = {};for (var n in e) {
        if (e.hasOwnProperty(n)) {
          t[n] = e[n];
        }
      }return t;
    }var e = {};var t = { aliceblue: "F0F8FF", antiquewhite: "FAEBD7", aqua: "00FFFF", aquamarine: "7FFFD4", azure: "F0FFFF", beige: "F5F5DC", bisque: "FFE4C4", black: "000000", blanchedalmond: "FFEBCD", blue: "0000FF", blueviolet: "8A2BE2", brown: "A52A2A", burlywood: "DEB887", cadetblue: "5F9EA0", chartreuse: "7FFF00", chocolate: "D2691E", coral: "FF7F50", cornflowerblue: "6495ED", cornsilk: "FFF8DC", crimson: "DC143C", cyan: "00FFFF", darkblue: "00008B", darkcyan: "008B8B", darkgoldenrod: "B8860B", darkgray: "A9A9A9", darkgrey: "A9A9A9", darkgreen: "006400", darkkhaki: "BDB76B", darkmagenta: "8B008B", darkolivegreen: "556B2F", darkorange: "FF8C00", darkorchid: "9932CC", darkred: "8B0000", darksalmon: "E9967A", darkseagreen: "8FBC8F", darkslateblue: "483D8B", darkslategray: "2F4F4F", darkslategrey: "2F4F4F", darkturquoise: "00CED1", darkviolet: "9400D3", deeppink: "FF1493", deepskyblue: "00BFFF", dimgray: "696969", dimgrey: "696969", dodgerblue: "1E90FF", firebrick: "B22222", floralwhite: "FFFAF0", forestgreen: "228B22", fuchsia: "FF00FF", gainsboro: "DCDCDC", ghostwhite: "F8F8FF", gold: "FFD700", goldenrod: "DAA520", gray: "808080", grey: "808080", green: "008000", greenyellow: "ADFF2F", honeydew: "F0FFF0", hotpink: "FF69B4", indianred: "CD5C5C", indigo: "4B0082", ivory: "FFFFF0", khaki: "F0E68C", lavender: "E6E6FA", lavenderblush: "FFF0F5", lawngreen: "7CFC00", lemonchiffon: "FFFACD", lightblue: "ADD8E6", lightcoral: "F08080", lightcyan: "E0FFFF", lightgoldenrodyellow: "FAFAD2", lightgray: "D3D3D3", lightgrey: "D3D3D3", lightgreen: "90EE90", lightpink: "FFB6C1", lightsalmon: "FFA07A", lightseagreen: "20B2AA", lightskyblue: "87CEFA", lightslategray: "778899", lightslategrey: "778899", lightsteelblue: "B0C4DE", lightyellow: "FFFFE0", lime: "00FF00", limegreen: "32CD32", linen: "FAF0E6", magenta: "FF00FF", maroon: "800000", mediumaquamarine: "66CDAA", mediumblue: "0000CD", mediumorchid: "BA55D3", mediumpurple: "9370D8", mediumseagreen: "3CB371", mediumslateblue: "7B68EE", mediumspringgreen: "00FA9A", mediumturquoise: "48D1CC", mediumvioletred: "C71585", midnightblue: "191970", mintcream: "F5FFFA", mistyrose: "FFE4E1", moccasin: "FFE4B5", navajowhite: "FFDEAD", navy: "000080", oldlace: "FDF5E6", olive: "808000", olivedrab: "6B8E23", orange: "FFA500", orangered: "FF4500", orchid: "DA70D6", palegoldenrod: "EEE8AA", palegreen: "98FB98", paleturquoise: "AFEEEE", palevioletred: "D87093", papayawhip: "FFEFD5", peachpuff: "FFDAB9", peru: "CD853F", pink: "FFC0CB", plum: "DDA0DD", powderblue: "B0E0E6", purple: "800080", rebeccapurple: "663399", red: "FF0000", rosybrown: "BC8F8F", royalblue: "4169E1", saddlebrown: "8B4513", salmon: "FA8072", sandybrown: "F4A460", seagreen: "2E8B57", seashell: "FFF5EE", sienna: "A0522D", silver: "C0C0C0", skyblue: "87CEEB", slateblue: "6A5ACD", slategray: "708090", slategrey: "708090", snow: "FFFAFA", springgreen: "00FF7F", steelblue: "4682B4", tan: "D2B48C", teal: "008080", thistle: "D8BFD8", tomato: "FF6347", turquoise: "40E0D0", violet: "EE82EE", wheat: "F5DEB3", white: "FFFFFF", whitesmoke: "F5F5F5", yellow: "FFFF00", yellowgreen: "9ACD32" };var n = { hue: null, saturation: null, value: null, base_color: "", greyscale: false, grayscale: false, golden: true, full_random: false, colors_returned: 1, format: "hex" };var r = { scheme_type: "analogous", format: "hex" };e.NAME_to_HEX = function (e) {
      if (e in t) {
        return t[e];
      } else {
        console.log("Color name not recognized.");
      }
    };e.NAME_to_HSV = function (t) {
      return e.HEX_to_RGB(e.NAME_to_HEX(t));
    };e.NAME_to_HSV = function (t) {
      return e.HEX_to_HSV(e.NAME_to_HEX(t));
    };e.HEX_to_RGB = function (e) {
      var t = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;e = e.replace(t, function (e, t, n, r) {
        return t + t + n + n + r + r;
      });var n = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(e);return n ? { r: parseInt(n[1], 16), g: parseInt(n[2], 16), b: parseInt(n[3], 16) } : null;
    };e.RGB_to_HEX = function (e) {
      return "#" + ((1 << 24) + (e.r << 16) + (e.g << 8) + e.b).toString(16).slice(1);
    };e.HSV_to_RGB = function (e) {
      var t, n, r;var i = e.h / 360;var s = e.s;var o = e.v;var u = Math.floor(i * 6);var a = i * 6 - u;var f = o * (1 - s);var l = o * (1 - a * s);var c = o * (1 - (1 - a) * s);switch (u % 6) {case 0:
          t = o, n = c, r = f;break;case 1:
          t = l, n = o, r = f;break;case 2:
          t = f, n = o, r = c;break;case 3:
          t = f, n = l, r = o;break;case 4:
          t = c, n = f, r = o;break;case 5:
          t = o, n = f, r = l;break;}return { r: Math.floor(t * 255), g: Math.floor(n * 255), b: Math.floor(r * 255) };
    };e.RGB_to_HSV = function (e) {
      var t, n, r;var i = 0;var s = 0;var o = 0;t = e.r / 255;n = e.g / 255;r = e.b / 255;var u = Math.min(t, Math.min(n, r));var a = Math.max(t, Math.max(n, r));if (u == a) {
        o = u;return { h: 0, s: 0, v: o };
      }var f = t == u ? n - r : r == u ? t - n : r - t;var l = t == u ? 3 : r == u ? 1 : 5;i = 60 * (l - f / (a - u));s = (a - u) / a;o = a;return { h: i, s: s, v: o };
    };e.HSV_to_HEX = function (t) {
      return e.RGB_to_HEX(e.HSV_to_RGB(t));
    };e.HEX_to_HSV = function (t) {
      return e.RGB_to_HSV(e.HEX_to_RGB(t));
    };e.make_scheme = function (e, t) {
      function f(e) {
        return { h: e.h, s: e.s, v: e.v };
      }var n = a(r);if (t != null) {
        for (var i in t) {
          if (t.hasOwnProperty(i)) {
            n[i] = t[i];
          }
        }
      }var s = [e];switch (n.scheme_type.toLowerCase()) {case "monochromatic":case "mono":
          for (var l = 1; l <= 2; l++) {
            var c = f(e);var h = c.s + .1 * l;h = o(h, 0, 1);var p = c.v + .1 * l;p = o(p, 0, 1);c.s = h;c.v = p;s.push(c);
          }for (var l = 1; l < 2; l++) {
            var c = f(e);var h = c.s - .1 * l;h = o(h, 0, 1);var p = c.v - .1 * l;p = o(p, 0, 1);c.s = h;c.v = p;s.push(c);
          }break;case "complementary":case "complement":
          var c = f(e);c.h += 180;if (c.h > 360) {
            c.h -= 360;
          }s.push(c);break;case "split-complementary":case "split-complement":case "split":
          var c = f(e);c.h += 165;if (c.h > 360) {
            c.h -= 360;
          }s.push(c);var c = f(e);c.h -= 165;if (c.h < 0) {
            c.h += 360;
          }s.push(c);break;case "double-complementary":case "double-complement":case "double":
          var c = f(e);c.h += 180;if (c.h > 360) {
            c.h -= 360;
          }s.push(c);var c = f(e);c.h += 30;if (c.h > 360) {
            c.h -= 360;
          }var d = f(c);s.push(c);d.h += 180;if (d.h > 360) {
            d.h -= 360;
          }s.push(d);break;case "analogous":case "ana":
          for (var l = 1; l <= 5; l++) {
            var c = f(e);c.h += 20 * l;if (c.h > 360) {
              c.h -= 360;
            }s.push(c);
          }break;case "triadic":case "triad":case "tri":
          for (var l = 1; l < 3; l++) {
            var c = f(e);c.h += 120 * l;if (c.h > 360) {
              c.h -= 360;
            }s.push(c);
          }break;default:
          console.log("Color scheme not recognized.");break;}u(n.format.toLowerCase(), s);return s;
    };e.make_color = function (r) {
      var a = [];var f = {};for (var l in n) {
        if (n.hasOwnProperty(l)) {
          f[l] = n[l];
        }
      }if (r != null) {
        for (var l in r) {
          if (r.hasOwnProperty(l)) {
            f[l] = r[l];
          }
        }
      }var c;if (f.base_color.length > 0) {
        c = t[f.base_color.toLowerCase()];c = e.HEX_to_HSV(c);
      }for (var h = 0; h < f.colors_returned; h++) {
        var p = i(0, 360);var d, v, m;if (c != null) {
          d = i(c.h - 5, c.h + 5);v = s(.4, .85);m = s(.4, .85);a.push({ h: d, s: v, v: m });
        } else {
          if (f.greyscale == true || f.grayscale == true) {
            d = 0;
          } else if (f.golden == true) {
            d = p + p / .618033988749895;
          } else if (f.hue == null || f.full_random == true) {
            d = p;
          } else {
            d = o(f.hue, 0, 360);
          }if (f.greyscale == true || f.grayscale == true) {
            v = 0;
          } else if (f.full_random == true) {
            v = s(0, 1);
          } else if (f.saturation == null) {
            v = .4;
          } else {
            v = o(f.saturation, 0, 1);
          }if (f.full_random == true) {
            m = s(0, 1);
          } else if (f.greyscale == true || f.grayscale == true) {
            m = s(.15, .75);
          } else if (f.value == null) {
            m = .75;
          } else {
            m = o(f.value, 0, 1);
          }a.push({ h: d, s: v, v: m });
        }
      }u(f.format.toLowerCase(), a);if (a.length === 1) {
        return a[0];
      } else {
        return a;
      }
    };return e;
  }if (typeof Please == "undefined") {
    e.Please = t();
  }
})(window);

'use strict';

if (!window['YT']) {
  var YT = { loading: 0, loaded: 0 };
}if (!window['YTConfig']) {
  var YTConfig = { 'host': 'http://www.youtube.com' };
}if (!YT.loading) {
  YT.loading = 1;(function () {
    var l = [];YT.ready = function (f) {
      if (YT.loaded) {
        f();
      } else {
        l.push(f);
      }
    };window.onYTReady = function () {
      YT.loaded = 1;for (var i = 0; i < l.length; i++) {
        try {
          l[i]();
        } catch (e) {}
      }
    };YT.setConfig = function (c) {
      for (var k in c) {
        if (c.hasOwnProperty(k)) {
          YTConfig[k] = c[k];
        }
      }
    };var a = document.createElement('script');a.type = 'text/javascript';a.id = 'www-widgetapi-script';a.src = 'https:' + '//s.ytimg.com/yts/jsbin/www-widgetapi-vflWe_txw/www-widgetapi.js';a.async = true;var b = document.getElementsByTagName('script')[0];b.parentNode.insertBefore(a, b);
  })();
}
// This is pretty much the definitive guide for how to do things
// https://babeljs.io/docs/learn-es2015/
// For sublime text, search package control for "Javascript Next"
// for correct syntax highlighting.
window.akl = {};
window.akl.spartan = {};

// All classes should be declared in the /classes folder
// The file name should match the class name and should be appended to the
// window.akl.yourtheme object as such:
// 	class Tabs extends UIComponent
// 	{
// 			... code ...
// 	}
//
// 	window.akl.yourtheme.tabs = Tabs;
// Then access this in the other files or templates as such:
//
// var TabController = new vf.yourtheme.tabs( ... );

// All Interfaces for classes (these should be abstract and non-concretely usable)
// should be stored in the /interfaces directory. When using jQuery for re-usable components,
// extending UIComponent ensures that we have a single version and entry point of jQuery
// being used for the Magento instance.

// All Event files should be stored in the events directory and should
// be divied up as best as possible by functionality. E.G. customizations to
// events on the checkout/cart pages should be grouped into a "cart.js" file.

// Files are loaded in the following order:
// interfaces/
// classes/
// events/
//
// Meaning that anything within those directories should not have
// an immediate dependency upon one another.
//
jQuery(function ($) {

  var loader = $("<div class='loader-overlay'><div class='ripple'><div></div></div></div>");

  function init() {
    var windowHeight = $(window).height();

    $(".media-cell, .holding-cell, .container-fluid").height(windowHeight);
    $(".info-cell").css("height", windowHeight);

    $(".tile-color").height(windowHeight / 3);

    $("<style type='text/css'> .tab-inner:before{ background-image: " + $(".inner.background").css("background-image") + "; width: " + $(".inner.background").width() + "px; height: " + $(".inner.background").height() + "px; } </style>").appendTo("head");

    $(".tab-group .tab-label").on("click", function () {
      var wasActive = $(this).hasClass("active-tab");

      $(".tab-inner").hide();
      $(".active-tab").removeClass("active-tab");

      if (wasActive) return;

      $(this).addClass("active-tab").closest(".tab").find(".tab-inner").show();
    });

    window.players = {};

    function getPlayer(videoId) {
      return window.players[videoId];
    }

    $(".youtube-link").on("click", function (e) {
      e.preventDefault();
      var videoId = $(this).data("videoId");

      var icon = "<span class='attribution'><i class='fa fa-youtube'></i></span>";
      var playingIcon = "<span class='now-playing'><img src='/wp-content/themes/spartan/img/icons/radio.svg' /></span>";
      //$("<iframe height='100' width='100' src='http://www.youtube.com/embed/" + videoId + "?enablejsapi=1&version=3&playerapiid=ytplayer' id='" + videoId + "' allowfullscreen frameborder='0'>");
      var frame = $("<div id='" + videoId + "'></div>");
      var wrapper = $("<div class='player-controls' data-video-id='" + videoId + "'></div>");
      var controls = $("<div class='controls'><h5>" + $(this).data("videoName") + "</h5><a id='play-" + videoId + "' href=''><i class='fa fa-play'></i></a><a id='pause-" + videoId + "' href=''><i class='fa fa-pause'></i></a><a id='remove-" + videoId + "' href=''><i class='fa fa-trash'></i></a><div>" + icon + playingIcon + "</div></div>");
      var temp = wrapper.append(controls).append(frame);
      $(".queue-tray .queue-inner").append(temp);

      var player = new YT.Player(document.getElementById(videoId), {
        height: '100',
        width: '100',
        videoId: videoId,
        events: {
          'onReady': function onReady() {
            if ($(".queue-tray .queue-inner").children().length === 1) {
              window.setTimeout(function () {
                player.playVideo();
              }, 250);

              $(".queue-tray .queue-inner").children().addClass("playing");
            }
          },
          'onStateChange': function onStateChange(event) {
            if (event.data == YT.PlayerState.ENDED) {
              var thisTile = $("#" + videoId).closest(".player-controls").removeClass("playing");

              var nextVideo = thisTile.next();

              getPlayer(nextVideo.data("videoId")).playVideo();

              nextVideo.addClass("playing");
            }
          }
        }
      });

      window.players[videoId] = player;

      $("#play-" + videoId).on("click", function (e) {
        e.preventDefault();

        player.playVideo();

        $(this).closest(".player-controls").addClass("playing");
      });

      $("#pause-" + videoId).on("click", function (e) {
        e.preventDefault();

        player.pauseVideo();

        $(this).closest(".player-controls").removeClass("playing");
      });

      $("#remove-" + videoId).on("click", function (e) {
        e.preventDefault();

        $("#" + videoId).parent(".player-controls").remove();
      });
    });
  }

  init();

  $(document).on("click", "a", function (e) {
    var href = $(this).attr("href");

    if (href.indexOf(window.location.host) > -1 || href === "/") {
      e.preventDefault();

      $(".container-fluid").append(loader);
      $.get(href, { STRIP_ASSETS: true }, function (data) {
        $(".container-fluid").replaceWith(data);
        history.pushState({}, "href", href);
        init();
      });
    }
  });
});
