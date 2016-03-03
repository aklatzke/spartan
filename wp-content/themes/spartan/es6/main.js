

if (!window['YT']) {var YT = {loading: 0,loaded: 0};}if (!window['YTConfig']) {var YTConfig = {'host': 'http://www.youtube.com'};}if (!YT.loading) {YT.loading = 1;(function(){var l = [];YT.ready = function(f) {if (YT.loaded) {f();} else {l.push(f);}};window.onYTReady = function() {YT.loaded = 1;for (var i = 0; i < l.length; i++) {try {l[i]();} catch (e) {}}};YT.setConfig = function(c) {for (var k in c) {if (c.hasOwnProperty(k)) {YTConfig[k] = c[k];}}};var a = document.createElement('script');a.type = 'text/javascript';a.id = 'www-widgetapi-script';a.src = 'https:' + '//s.ytimg.com/yts/jsbin/www-widgetapi-vflWe_txw/www-widgetapi.js';a.async = true;var b = document.getElementsByTagName('script')[0];b.parentNode.insertBefore(a, b);})();}
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
jQuery(function($){

  var loader = $("<div class='loader-overlay'><div class='ripple'><div></div></div></div>");

  function init()
  {
    var windowHeight = $(window).height();

    $(".media-cell, .holding-cell, .container-fluid").height( windowHeight );
    $(".info-cell").css("height", windowHeight);

    $(".tile-color").height( windowHeight/3 );

    $("<style type='text/css'> .tab-inner:before{ background-image: " + $(".inner.background").css("background-image") + "; width: " + ( $(".inner.background").width() ) + "px; height: " + ( $(".inner.background").height() ) + "px; } </style>").appendTo("head");

    $(".tab-group .tab-label").on("click", function(){
      var wasActive = $(this).hasClass("active-tab");

      $(".tab-inner").hide();
      $(".active-tab").removeClass("active-tab");

      if( wasActive ) return;

      $(this).addClass("active-tab").closest(".tab").find(".tab-inner").show();
    });

    window.players = {};

    function getPlayer(videoId){
        return window.players[videoId];
    }

    $(".youtube-link").on("click", function(e){
      e.preventDefault();
      var videoId = $(this).data("videoId");

      var icon = "<span class='attribution'><i class='fa fa-youtube'></i></span>";
      var playingIcon = "<span class='now-playing'><img src='/wp-content/themes/spartan/img/icons/radio.svg' /></span>";
      //$("<iframe height='100' width='100' src='http://www.youtube.com/embed/" + videoId + "?enablejsapi=1&version=3&playerapiid=ytplayer' id='" + videoId + "' allowfullscreen frameborder='0'>");
      var frame = $("<div id='" + videoId + "'></div>");
      var wrapper = $("<div class='player-controls' data-video-id='" + videoId + "'></div>");
      var controls = $("<div class='controls'><h5>" + $(this).data("videoName") + "</h5><a id='play-" + videoId + "' href=''><i class='fa fa-play'></i></a><a id='pause-" + videoId + "' href=''><i class='fa fa-pause'></i></a><a id='remove-" + videoId + "' href=''><i class='fa fa-trash'></i></a><div>" + icon + playingIcon + "</div></div>")
      var temp = wrapper.append(controls).append(frame);
      $(".queue-tray .queue-inner").append(temp);

      var player = new YT.Player(document.getElementById(videoId), {
          height: '100',
          width: '100',
          videoId: videoId,
          events: {
            'onReady': function(){
              if( $(".queue-tray .queue-inner").children().length === 1 )
              {
                window.setTimeout(function(){
                  player.playVideo();
                }, 250);

                $(".queue-tray .queue-inner").children().addClass("playing");
              }
            },
            'onStateChange': function(event){
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

      $("#play-" + videoId).on("click", function(e){
        e.preventDefault();

        player.playVideo();

        $(this).closest(".player-controls").addClass("playing");
      });

      $("#pause-" + videoId).on("click", function(e){
        e.preventDefault();

        player.pauseVideo();

        $(this).closest(".player-controls").removeClass("playing");
      });

      $("#remove-" + videoId).on("click", function(e){
        e.preventDefault();

        $("#" + videoId).parent(".player-controls").remove();
      });
    });
  }

  init();

  $(document).on("click", "a", function(e){
    var href = $(this).attr("href");

    if( href.indexOf( window.location.host ) > -1 || href === "/" )
    {
      e.preventDefault();

      $(".container-fluid").append(loader);
      $.get(href, { STRIP_ASSETS : true }, function(data){
        $(".container-fluid").replaceWith(data);
        history.pushState({}, "href", href);
        init();
      });
    }
  });
});
