<?php

class musiqueapproximativeImportsvnlogTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('log_file', sfCommandArgument::REQUIRED, 'SVN XML log file'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'musiqueapproximative';
    $this->name             = 'import-svn-log';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [musiqueapproximative:import-svn-log|INFO] task does things.
Call it with:

  [php symfony musiqueapproximative:import-svn-log|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    // Sanity checks
    if (!is_readable($arguments['log_file']))
    {
      throw new InvalidArgumentException(sprintf('File "%s" is not readable', $arguments['log_file']));
    }

    // Load XML
    if (!$xml = simplexml_load_file($arguments['log_file']))
    {
      throw new InvalidArgumentException(sprintf('Impossible to parse XML from "%s"', $arguments['log_file']));
    }

    // Parse XML and update database
    //var_dump($xml);
    
    foreach ($xml->logentry as $entry)
    {
      $post = new Post();
      $post->body = (string)$entry->msg;
      $post->is_online = true;
      $post->publish_on = time();
      $post->save();
    } 
  }
}
