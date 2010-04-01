<?php
class postComponents extends sfComponents
{
  public function executeContributors(sfWebRequest $request)
  {
    $contributors = array();

    // TODO : this is crappy performance-wise
    $contributors = Doctrine::getTable('sfGuardUser')->findByIsActive(true);

    // Pass data to view
    $this->contributors = $contributors;
  }
}
