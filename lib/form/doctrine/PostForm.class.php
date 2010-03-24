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

    $this->widgetSchema['track_filename'] = new sfWidgetFormInputFile();
    $this->validatorSchema['track_filename'] = new sfValidatorFile(array(
      'required'              => true,
      'path'                  => sfConfig::get('sf_web_dir').'/tracks',
      'mime_types'            => array('audio/mpeg'),
      'validated_file_class'  => 'maValidatedFile'
    ));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => 5, 'cols' => 80));
    $this->widgetSchema['track_title'] = new sfWidgetFormInputText();
    $this->widgetSchema['track_author'] = new sfWidgetFormInputText();

    $this->widgetSchema['is_online'] = new sfWidgetFormInputCheckbox(array(), array('checked' => 'checked')); 
    $this->validatorSchema['is_online'] = new sfValidatorBoolean();

    $this->widgetSchema['publish_on'] = new sfWidgetFormDateTime(array(
      'date' => array('can_be_empty'  =>  false),
      'time' => array('can_be_empty'  =>  false),
      'default' => date('Y/m/d H:i')
    ));
    $this->validatorSchema['publish_on'] = new sfValidatorDateTime();

    unset($this['created_at'], $this['updated_at'], $this['slug'], $this['contributor_id']);
  }
}
