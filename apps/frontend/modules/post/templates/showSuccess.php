<?php use_helper('Markdown') ?>
<div id="posts">
  <div style="display: block;">
    
    <p id="navbar">
      <?php if ($post_previous): ?>
        <?php echo link_to(image_tag('left-epsilon.jpg'), '@post_show?slug='.$post_previous->slug, array('class' => 'past')) ?>
      <?php endif; ?>
      <?php if ($post_next): ?>
        <?php echo link_to(image_tag('right-epsilon.jpg'), '@post_show?slug='.$post_next->slug, array('class' => 'nav')) ?>
      <?php endif; ?>
    </p>
    
    <p><?php echo Markdown($post->body) ?></p>

    <p id="author">par <?php echo link_to($post->getSfGuardUser()->username, '@post_list?contributor='.$post->getSfGuardUser()->username) ?></p>

    <p />
   
    <center>
      <span id="mp3">
        <a href="<?php echo $sf_request->getRelativeUrlRoot() ?>/tracks/<?php echo $post->track_filename ?>">DOWNLOAD</a>
        <a class="media" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/tracks/<?php echo $post->track_filename ?>"><?php echo $post->track_author ?> - <?php echo $post->track_title ?></a>
      </span>
      <p />
      <a href="<?php echo $sf_request->getRelativeUrlRoot() ?>/tracks/<?php echo $post->track_filename ?>"><?php echo $post->track_author ?> - <?php echo $post->track_title ?></a>
    </center>
    <p />

  </div>
</div>
<script type="text/javascript">
  $.fn.media.defaults.mp3Player = '<?php echo $sf_request->getRelativeUrlRoot() ?>/swf/mediaplayer.swf';
  $('.media').media({
    width:     470,
    height:    20,
    flashvars: {'type': 'mp3'}
  });
</script>
