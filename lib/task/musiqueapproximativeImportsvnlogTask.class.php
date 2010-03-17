<?php

class musiqueapproximativeImportsvnlogTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('log_file', sfCommandArgument::REQUIRED, 'SVN XML log file'),
      new sfCommandArgument('source_dir', sfCommandArgument::REQUIRED, 'Directory containing source files'),
      new sfCommandArgument('target_dir', sfCommandArgument::REQUIRED, 'Directory where to put files once processed'),
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
    if (!is_readable($arguments['source_dir']))
    {
      throw new InvalidArgumentException(sprintf('Directory "%s" is not readable', $arguments['source_dir']));
    }
    if (!is_writable($arguments['target_dir']))
    {
      throw new InvalidArgumentException(sprintf('Directory "%s" is not readable', $arguments['target_dir']));
    }

    // Load XML
    if (!$xml = simplexml_load_file($arguments['log_file']))
    {
      throw new InvalidArgumentException(sprintf('Impossible to parse XML from "%s"', $arguments['log_file']));
    }

    // Parse svn log and create a useful data structure
    $revisions = array();
    foreach ($xml->logentry as $entry)
    {
      $revisions[(int)$entry['revision']] = array('message' => (string)$entry->msg, 'date' => (string)$entry->date);
    } 

    // Aggregate informations for all files in source directory
    $iterator = new RecursiveDirectoryIterator($arguments['source_dir']);
    foreach ($iterator as $file => $info)
    {
      $filename = (array)$file;
      $filename = $filename[0];
      if (is_dir($filename))
      {
        continue;
      }
      $cmd = sprintf('/usr/bin/svn info --xml %s;', escapeshellarg($filename));
      $res = shell_exec($cmd);
      $this->logSection('info', sprintf('Importing "%s"', $filename));
      $xml = simplexml_load_string($res);
      if (!$xml)
      {
        $this->logSection('info', sprintf('Could not get versionning information from "%s"', $filename));
      }
      $revision = (string)$xml->entry->commit['revision']; 
      if (!$revision)
      {
        continue;
      }
      
      // Create object in database
      $track_parts = explode('-', basename($filename, '.mp3'));
      $post = new Post();
      $post->body = $revisions[$revision]['message'];
      $post->created_at = $revisions[$revision]['date'];
      $post->publish_on = $revisions[$revision]['date'];
      $post->track_filename = basename($filename);
      $post->track_author = $track_parts[0];
      $post->track_title = $track_parts[1];
      $post->track_md5 = md5($filename);
      $post->svn_revision = $revision;
      $post->is_online = true;
      $post->save();

      // Copy track to destination directory
      copy($filename, sprintf('%s/%s', $arguments['target_dir'], basename($filename)));
    } 
  }
}
