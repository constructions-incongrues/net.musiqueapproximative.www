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

    // Track file
    $this->widgetSchema['track_filename'] = new sfWidgetFormInputFile();
    $this->validatorSchema['track_filename'] = new sfValidatorFile(array(
      'required'              => $this->getObject()->isNew(),
      'path'                  => sfConfig::get('sf_web_dir').'/tracks',
      'mime_types'            => array('audio/mpeg'),
      'validated_file_class'  => 'maValidatedFile'
    ));

    // Track metadata
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => 5, 'cols' => 80));
    $this->widgetSchema['track_title'] = new sfWidgetFormInputText();
    $this->widgetSchema['track_author'] = new sfWidgetFormInputText();

    // Publication time
    $this->validatorSchema['publish_on'] = new sfValidatorDateTime();
    $this->widgetSchema['publish_on'] = new sfWidgetFormDateTime(array(
      'date'    => array('can_be_empty'  =>  false, 'format'  => '%day%/%month%/%year%'),
      'time'    => array('can_be_empty'  =>  false),
      'default' => date('Y/m/d H:i')
    ));
    
    // Force user to check for unicity
    $this->widgetSchema['is_unique'] = new sfWidgetFormInputCheckbox();
    $this->validatorSchema['is_unique'] = new sfValidatorBoolean(array('required' => $this->getObject()->isNew()));

    // Those field are automatically set
    unset($this['created_at'], $this['updated_at'], $this['slug'], $this['contributor_id']);
  }
}
