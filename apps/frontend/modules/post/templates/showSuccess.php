<?php use_helper('Markdown') ?>

<script type="text/javascript">
soundManager.url = '<?php echo $sf_request->getRelativeUrlRoot() ?>/swf/';
window.script_name = '<?php echo $sf_request->getScriptName() ?>';
window.autoplay = <?php echo $sf_request->getParameter('play', 0) ?>;
</script>

<div id="browse-all">
  <p>
    <?php echo link_to(sprintf('Browse all tracks (%d)', $posts_count), '@post_list', array('id' => 'index-toggle')) ?> <span id="loading" style="display: none;">(loading...)</span>
  </p>
</div>

<div id="browse"> 
  <form method="get" action="<?php echo url_for('@post_list') ?>">
    <input type="text" class="search" name="q" value="<?php echo $sf_request->getParameter('q') ?>"/>
    <input type="submit" class="submit" value="Search !" />
  </form>
</div>

<div id="index"></div>

<div id="posts">
  <div style="display: block;">
    
    <p id="navbar">
      <?php if ($post_previous): ?>
        <?php echo link_to(image_tag('left-epsilon.jpg'), '@post_show?slug='.$post_previous->slug, array('class' => 'past', 'title' => sprintf('%s - %s', $post_previous->track_author, $post_previous->track_title))) ?>
      <?php endif; ?>
      <?php if ($post_next): ?>
        <?php echo link_to(image_tag('right-epsilon.jpg'), '@post_show?slug='.$post_next->slug, array('class' => 'nav', 'title' => sprintf('%s - %s', $post_next->track_author, $post_next->track_title))) ?>
      <?php endif; ?>
    </p>
    
    <p><?php echo Markdown($post->body) ?></p>

    <p id="author">par <?php echo link_to($post->getContributorDisplayName(), '@post_list?contributor='.$post->getSfGuardUser()->username) ?></p>

    <div>
      <div class="controls">
        <div class="statusbar">
          <div class="loading">&nbsp;</div>
          <div class="position">&nbsp;</div>
        </div>
        <div class="timing">
          <div id="sm2_timing">
            <span class="sm2_position"></span>
            <span class="sm2_total"></span>
          </div>
        </div>
      </div>
    </div>
   
   <p>
   	<center><?php echo $post->track_author ?> - <?php echo $post->track_title ?></center>
   </p>
   
   <p>
   	 <a id="play" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/tracks/<?php echo $post->track_filename ?>">Play</a> |
   	 <a id="pause" href="#">Pause</a> |
   	 <a id="stop" href="#">Stop</a> |
   	 <a id="download" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/tracks/<?php echo $post->track_filename ?>">Download</a>
   </p>

  </div>
</div>
