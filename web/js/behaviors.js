$(document).ready(function() {
  $('#index-toggle').click(function(e) {
    e.preventDefault();
    $('#loading').fadeIn();
    $('#index').load($(e.target).attr('href'), function() {
      $('#index').toggle();
      $('#loading').fadeOut();
    });
  });
});

