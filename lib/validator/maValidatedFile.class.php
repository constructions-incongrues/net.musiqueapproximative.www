<?php
class maValidatedFile extends sfValidatedFile
{
  public function generateFilename()
  {
    return $this->getOriginalName();
  }  
}
