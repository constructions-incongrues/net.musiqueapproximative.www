<?php if ($sf_request->getParameter('callback')): ?>
<?php echo $sf_request->getParameter('callback'); ?>(<?php echo $sf_data->getRaw('data') ?>);
<?php else: ?>
<?php echo $sf_data->getRaw('data') ?>
<?php endif ?>
<?php
decorate_with(false);
sfConfig::set('sf_web_debug', false);
?>