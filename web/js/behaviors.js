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
      $('a.media').click(function(event) {
        var sound = soundManager.createSound( {
          id : 'track',
          url : $(this).attr('href')
        });
        sound.play();
        event.preventDefault();
      });
    };
  });
