<?php

require_once dirname(__FILE__).'/sfTaskExtraDoctrineBaseTask.class.php';

/**
 * Builds form classes in the application lib directory.
 * 
 * @package    sfTaskExtraPlugin
 * @subpackage task
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineBuildAppFormsTask.class.php 28152 2010-02-20 15:57:55Z Kris.Wallsmith $
 */
class sfDoctrineBuildAppFormsTask extends sfTaskExtraDoctrineBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
    ));

    $this->namespace = 'doctrine';
    $this->name = 'build-app-forms';

    $this->briefDescription = 'Builds form classes in the application lib directory';
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $this->checkAppExists($arguments['application']);

    // load models
    Doctrine_Core::loadModels($this->configuration->getModelDirs());
    $models = Doctrine_Core::getLoadedModels();
    $models = Doctrine_Core::initializeModels($models);
    $models = Doctrine_Core::filterInvalidModels($models);

    // skeleton directory
    if (is_readable(sfConfig::get('sf_data_dir').'/skeleton/doctrine_app_form'))
    {
      $skeletonDir = sfConfig::get('sf_data_dir').'/skeleton/doctrine_app_form';
    }
    else
    {
      $skeletonDir = dirname(__FILE__).'/skeleton/app_form';
    }

    // target directory
    if (!file_exists($file = sfConfig::get('sf_app_lib_dir').'/form/doctrine'))
    {
      $this->getFilesystem()->mkdirs($file);
    }

    // constants
    $properties = parse_ini_file(sfConfig::get('sf_config_dir').'/properties.ini', true);
    $constants = array(
      'PROJECT'     => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
      'AUTHOR'      => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here',
      'APPLICATION' => $arguments['application'],
    );

    foreach ($models as $model)
    {
      $file = sfConfig::get('sf_app_lib_dir').'/form/doctrine/'.$arguments['application'].$model.'Form.class.php';
      if (class_exists($model.'Form') && !file_exists($file))
      {
        $this->getFilesystem()->copy($skeletonDir.'/form.class.php', $file);
        $this->getFilesystem()->replaceTokens($file, '##', '##', $constants + array('MODEL' => $model));
      }
    }
  }
}
