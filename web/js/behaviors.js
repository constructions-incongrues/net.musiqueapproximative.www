window.getTime = function(nMSec, bAsString) {
  // convert milliseconds to mm:ss, return as object literal or string
  var nSec = Math.floor(nMSec / 1000);
  var min = Math.floor(nSec / 60);
  var sec = nSec - (min * 60);
  // if (min == 0 && sec == 0) return null; // return 0:00 as null
  return (bAsString ? (min + ':' + (sec < 10 ? '0' + sec : sec)) : {
    'min' : min,
    'sec' : sec
  });
};

window.notifyCurrentTrack = function() {
  var notifier = new Notifier();
  if (notifier.HasSupport()) {
    if (window.webkitNotifications.checkPermission()) {
      notifier.RequestPermission(window.notifyCurrentTrack);
    } else {
      notifier.Notify('', 'Currently playing', $('#track-infos').text());
    }
  }
};

$(document).ready(function() {

  // "Browse tracks"
    $('#index-toggle').click(function(e) {
      e.preventDefault();
      $('#loading').fadeIn();
      $('#index').load($(e.target).attr('href'), function() {
        $('#index').toggle();
        $('#loading').fadeOut();
      });
    });

    $('a.email-subscription-link').click(function(event) {
      $('div#email-subscription').toggle();
    });

    if (window.random) {
      var current_post_id = $('a#play').attr('x-js-postid');
      $
          .get(window.script_name + '/posts/random?current=' + current_post_id,
              {}, function(data) {
                $('a.past').attr(
                    'href',
                    data + '?play=' + window.autoplay + '&random='
                        + window.random);
              });
      $
          .get(window.script_name + '/posts/random?current=' + current_post_id,
              {}, function(data) {
                $('a.nav').attr(
                    'href',
                    data + '?play=' + window.autoplay + '&random='
                        + window.random);
              });
    }

    // Random button
    $('#random').click(
        function(event) {
          event.preventDefault();
          var current_post_id = $('a#play').attr('x-js-postid');
          if ($(this).hasClass('not')) {
            $(this).removeClass('not');
            window.random = 1;
            $.get(window.script_name + '/posts/random?current='
                + current_post_id, {}, function(data) {
              $('a.past').attr(
                  'href',
                  data + '?play=' + window.autoplay + '&random='
                      + window.random);
            });
            $.get(window.script_name + '/posts/random?current='
                + current_post_id, {}, function(data) {
              $('a.nav').attr(
                  'href',
                  data + '?play=' + window.autoplay + '&random='
                      + window.random);
            });
          } else {
            $(this).addClass('not');
            window.random = 0;
            $.get(
                window.script_name + '/posts/prev?current=' + current_post_id,
                {}, function(data) {
                  $('a.past').attr(
                      'href',
                      data + '?play=' + window.autoplay + '&random='
                          + window.random);
                });
            $.get(
                window.script_name + '/posts/next?current=' + current_post_id,
                {}, function(data) {
                  $('a.nav').attr(
                      'href',
                      data + '?play=' + window.autoplay + '&random='
                          + window.random);
                });
          }

          // TODO : get appropriate information and update links titles
          $('a.nav').attr('title', '');
          $('a.past').attr('title', '');
        });

    // Player
    soundManager.onload = function() {

      window.sound = soundManager.createSound( {
        id : 'track',
        url : $('a#play').attr('href'),
        autoLoad : true,
        autoPlay : window.autoplay,
        whileloading : function() {
          $('div.loading').css('width',
              (((this.bytesLoaded / this.bytesTotal) * 100) + '%'));
          $('#timing span.total').text(
              window.getTime(this.durationEstimate, true));
        },
        onplay : function() {
          window.notifyCurrentTrack();
        },
        whileplaying : function() {
          $('div.position').css('width',
              (((this.position / this.durationEstimate) * 100) + '%'));
          $('#timing span.current').text(window.getTime(this.position, true));
        },
        onfinish : function() {
          var current_post_id = $('a#play').attr('x-js-postid');
          window.location = $('a.past').attr('href');
        }
      });

      $('div.statusbar').click(
          function(event) {
            if (window.sound) {
              var position = (event.pageX - this.offsetLeft)
                  / $('div.statusbar').width() * 100;
              window.sound.setPosition(window.sound.durationEstimate / 100
                  * position);
              $('div.position').css('width', position + '%');
            }
          });

      $('a#play').click(function(event) {
        event.preventDefault();
        window.sound.play();
      });

      $('a#pause').click(function(event) {
        event.preventDefault();
        window.sound.togglePause();
      });

      $('a#stop').click(function(event) {
        event.preventDefault();
        window.sound.stop();
        $('div.position').css('width', '0%');
      });
    };
  });
