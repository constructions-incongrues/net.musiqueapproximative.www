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
}

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

    // Player
    soundManager.onload = function() {

      window.sound = soundManager.createSound( {
        id : 'track',
        url : $('a#play').attr('href'),
        autoLoad : true,
        whileloading : function() {
          $('div.loading').css('width',
              (((this.bytesLoaded / this.bytesTotal) * 100) + '%'));
        },
        whileplaying : function() {
          $('div.position').css('width',
              (((this.position / this.durationEstimate) * 100) + '%'));
          $('#timing span.current').text(window.getTime(this.position, true));
        },
        onfinish : function() {
          var current_post_id = $(event.target).attr('x-js-postid');
          $.get(window.script_name + '/posts/next?current=' + current_post_id
              + '&random=' + window.random, {}, function(data) {
            window.location = data + '?play=1';
          });
        },
        onload : function() {
          $('#timing span.total').text(window.getTime(this.duration, true));
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

      if (window.autoplay) {
        $('a#play').click();
      }
    };
  });
