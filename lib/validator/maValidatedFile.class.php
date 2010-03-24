<?php
class maValidatedFile extends sfValidatedFile
{
  public function generateFilename()
  {
    return filter_var($this->getOriginalName(), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
  }  
}
