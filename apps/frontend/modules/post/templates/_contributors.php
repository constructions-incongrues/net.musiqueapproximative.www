<?php foreach ($contributors as $contributor): ?>
  <?php if ($contributor->UserProfile->website_url): ?>
    <?php echo link_to($contributor->getDisplayName(), $contributor->UserProfile->website_url) ?>
  <?php else: ?>
    <?php echo $contributor->getDisplayName() ?>
  <?php endif; ?>
  -
<?php endforeach; ?>
