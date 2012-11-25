<?php foreach ($contributors as $contributor): ?>
    <?php echo link_to($contributor->getDisplayName(), '@homepage?c='.$contributor->username, array('title' => 'Écouter la playlist de ' . $contributor->getDisplayName())) ?>
  -
<?php endforeach; ?>
