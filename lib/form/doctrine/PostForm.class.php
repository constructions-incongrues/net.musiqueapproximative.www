<?php

/**
 * Post form.
 *
 * @package    musique-approximative
 * @subpackage form
 * @author     Tristan Rivoallan <tristan@rivoallan.net>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PostForm extends BasePostForm
{
  public function configure()
  {
    parent::setup();

    $this->widgetSchema['track_filename'] = new sfWidgetFormInputFileEditable(array('file_src' => 'web/music'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => 5, 'cols' => 80));
    $this->widgetSchema['track_title'] = new sfWidgetFormInputText();
    $this->widgetSchema['track_author'] = new sfWidgetFormInputText();
    $this->widgetSchema['is_online'] = new sfWidgetFormInputCheckbox(); 
    $this->widgetSchema['publish_on'] = new sfWidgetFormDateTime(); 

    unset($this['created_at'], $this['updated_at'], $this['slug']);
  }
}
