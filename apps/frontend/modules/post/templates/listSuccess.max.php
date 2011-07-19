<?php for ($i = 0; $i < count($posts); $i++): ?>
<?php $post = $posts[$i] ?>
<?php echo $i ?>, "<?php echo $post->track_author ?>" "<?php echo $post->track_title ?>" "<?php echo sprintf('%s/tracks/%s', $sf_request->getUriPrefix(), $post->track_filename) ?>" "<?php echo url_for('@post_show?slug='.$post->slug, true) ?>" "<?php echo $post->getSfGuardUser()->username ?>";
<?php endfor; ?>