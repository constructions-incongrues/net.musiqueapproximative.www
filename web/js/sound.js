soundManager.onready(function() {
  if (soundManager.supported())
  {
    $('a.mp3').click(function(event) {
      event.preventDefault();
      var track = soundManager.createSound({
        id: 'track',
        url: escape($(this).attr('href')),
        autoLoad: true,
        autoPlay: false,
        volume: 50,
        onload: function() {
          this.play();
        },
        onfinish: function() {
          $.ajax({
            url:      window.url_root + '/posts/next',
            complete: function (xhr) {
              window.location = xhr.responseText; 
            }
          });
        }
      });
    });
  }
});
