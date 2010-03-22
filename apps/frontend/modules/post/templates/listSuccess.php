<p><?php echo count($posts) ?> tracks !</p>
<ul>
  <?php foreach ($posts as $post): ?>

    <li>
      <?php echo link_to(sprintf('%s - %s', $post->track_author, $post->track_title), '@post_show?slug='.$post->slug) ?>
      (<?php echo link_to($post->getSfGuardUser()->username, '@post_list?contributor='.$post->getSfGuardUser()->username) ?>)
    </li>

  <?php endforeach; ?>
</ul>
