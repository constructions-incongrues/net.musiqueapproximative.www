<?php

/**
 * UserProfile form.
 *
 * @package    musique-approximative
 * @subpackage form
 * @author     Tristan Rivoallan <tristan@rivoallan.net>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserProfileForm extends BaseUserProfileForm
{
  public function configure()
  {
    $this->setWidget('user_id', new sfWidgetFormInputHidden());
  }
}
