<?php
class maGuardUserAdminForm extends sfGuardUserAdminForm
{
  public function setup()
  {
    parent::setup();
    $this->embedForm('profile', new UserProfileForm($this->getObject()->UserProfile));
  }
}
