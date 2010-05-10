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
      $('a#play').click(
          function(event) {
            event.preventDefault();
            window.sound = soundManager.createSound( {
              id : 'track',
              url : $(this).attr('href'),
              whileloading : function() {
                $('div.loading').css('width',
                    (((this.bytesLoaded / this.bytesTotal) * 100) + '%'));
              },
              whileplaying : function() {
                $('div.position').css('width',
                    (((this.position / this.durationEstimate) * 100) + '%'));
              },
              onfinish : function() {
                $.get(window.script_name + '/posts/next', {}, function(data) {
                  window.location = data + '?play=1';
                });
              }
            });
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
