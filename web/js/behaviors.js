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
                alert('Done !');
              }
            });
            window.sound.play();
          });

      $('a#pause').click(function() {
        window.sound.pause();
      });

      $('a#stop').click(function() {
        window.sound.stop();
      });
    };
  });
