<?php use_helper('Markdown') ?>
<?php if ($form['track_filename']->getValue()): ?>
<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_body">
  <div>
    <label>Écoute</label>
    <div class="content">
      <a href="<?php echo url_for(sprintf('%s/tracks/%s', sfConfig::get('sf_web_dir'), Markdown($form['track_filename']->getValue()))) ?>">
        Écouter
      </a>
    </div>
  </div>
</div>
<?php endif; ?>
