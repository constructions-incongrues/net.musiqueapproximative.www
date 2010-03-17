<ul>
  <?php foreach ($posts as $post): ?>

    <li><?php echo link_to(sprintf('%s - %s', $post->track_author, $post->track_title), '@post_show?slug='.$post->slug) ?></li>

  <?php endforeach; ?>
</ul>
