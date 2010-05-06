<?php use_helper('Markdown') ?>

<script type="text/javascript">
soundManager.url = '<?php echo $sf_request->getRelativeUrlRoot() ?>/swf/';
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
        <?php echo link_to(image_tag('left-epsilon.jpg'), '@post_show?slug='.$post_previous->slug, array('class' => 'past')) ?>
      <?php endif; ?>
      <?php if ($post_next): ?>
        <?php echo link_to(image_tag('right-epsilon.jpg'), '@post_show?slug='.$post_next->slug, array('class' => 'nav')) ?>
      <?php endif; ?>
    </p>
    
    <p><?php echo Markdown($post->body) ?></p>

    <p id="author">par <?php echo link_to($post->getContributorDisplayName(), '@post_list?contributor='.$post->getSfGuardUser()->username) ?></p>

    <p />
   
    <center>
      <span id="mp3">
        <a class="media" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/tracks/<?php echo $post->track_filename ?>"><?php echo $post->track_author ?> - <?php echo $post->track_title ?></a>
      </span>
      <p />
      <a title="Télécharger le morceau" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/tracks/<?php echo $post->track_filename ?>"><?php echo $post->track_author ?> - <?php echo $post->track_title ?></a>
    </center>
    <p />

  </div>
</div>
