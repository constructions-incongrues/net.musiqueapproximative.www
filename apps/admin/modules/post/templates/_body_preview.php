<?php use_helper('Markdown') ?>
<?php if ($form['body']->getValue()): ?>
<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_body">
  <div>
    <label>Prévisualisation</label>
    <div class="content">
      <?php echo Markdown($form['body']->getValue()) ?>
    </div>
    <div class="help">À quoi va ressembler le message en ligne.</div>
  </div>
</div>
<?php endif; ?>
