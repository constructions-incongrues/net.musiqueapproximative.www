<?php

/**
 * post actions.
 *
 * @package    musique-approximative
 * @subpackage post
 * @author     Tristan Rivoallan <tristan@rivoallan.net>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
}
