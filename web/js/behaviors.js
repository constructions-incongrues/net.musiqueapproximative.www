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
                // TODO : TBR
                console.log(this.position / this.durationEstimate * 100 + '%');
                $('div.position').css('width',
                    (((this.position / this.durationEstimate) * 100) + '%'));
              },
              onfinish : function() {
                $.get(
                    window.relative_url_root + '/frontend_dev.php/posts/next',
                    {}, function(data) {
                      window.location = data + '?play=1';
                    });
              }
            });
            window.sound.play();

            // TODO : TBR
            window.sound.mute();
          });

      $('a#pause').click(function() {
        window.sound.pause();
      });

      $('a#stop').click(function() {
        window.sound.stop();
      });

      if (window.autoplay) {
        $('a#play').click();
      }
    };
  });
