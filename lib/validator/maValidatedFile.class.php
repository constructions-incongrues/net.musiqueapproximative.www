<?php
class maValidatedFile extends sfValidatedFile
{
  public function generateFilename()
  {
  	$filename = str_replace("'", ' ', $this->getOriginalName());
  	return filter_var($filename, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
  }  
}
