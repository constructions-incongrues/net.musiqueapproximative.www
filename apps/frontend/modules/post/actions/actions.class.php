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
   * Displays a post. if no id explicitely set, last post is shown. 
   *
   * @param sfRequest $request A request object
   */
  public function executeShow(sfWebRequest $request)
  {
    // Retrieve appropriate post from database
    $post = Doctrine::getTable('Post')->getOnlinePostBySlug($request->getParameter('slug'));

    // Throw a 404 error if no post is found
    $this->forward404Unless($post);

    // Set specific page title
    $this->getResponse()->setTitle(sprintf('%s - %s | Musique Approximative', $post->track_author, $post->track_title));

    // Get number of online posts
    $posts_count = Doctrine::getTable('Post')->countOnlinePosts();

    // Pass data to view
    $this->post = $post;
    $this->posts_count = $posts_count;
    $this->post_next = Doctrine::getTable('Post')->getNextPost($post);
    $this->post_previous = Doctrine::getTable('Post')->getPreviousPost($post);
  }

  public function executeHome(sfWebRequest $request)
  {
    $this->forward404Unless($post = Doctrine::getTable('Post')->getLastPost());
    $this->redirect('@post_show?slug='.$post->slug);
  }

  public function executeList(sfWebRequest $request)
  {
    $list_title = null;
    if ($this->getRequestParameter('q'))
    {
      $posts = Doctrine::getTable('Post')->search($request->getParameter('q'));
      $list_title = sprintf('%d résultat(s) pour la recherche "%s"', count($posts), $request->getParameter('q'));
    }
    else
    {
      $posts = Doctrine::getTable('Post')->getOnlinePosts($request->getParameter('contributor'));
      if ($request->getParameter('contributor'))
      {
        $list_title = sprintf('%s a posté %d morceau(x) à ce jour', $request->getParameter('contributor'), count($posts));
      }
    }

    // Pass data to view
    $this->posts = $posts;
    $this->list_title = $list_title;
  }

  public function executeFeed(sfWebRequest $request)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Markdown');
    $feed = sfFeedPeer::newInstance('rss201');
    $feed->initialize(array(
      'title'         => 'Musique Approximative',
      'link'          => 'http://www.musiqueapproximative.net',
      'authorEmail'   => 'bertier@musiqueapproximative.net',
    ));

    $posts = Doctrine::getTable('Post')->getOnlinePosts($request->getParameter('contributor'), $request->getParameter('count', 50));
    foreach ($posts as $post)
    {
      $strf = strptime($post->publish_on, '%Y-%m-%d %H:%M:%S');
      $publish_timestamp = mktime($strf['tm_hour'], $strf['tm_min'], $strf['tm_sec'], $strf['tm_mon'] + 1, $strf['tm_mday'], $strf['tm_year'] + 1900);

      // Canonical URL to post's associated file
      $track_file_url = htmlspecialchars(sprintf('http://www.musiqueapproximative.net/tracks/%s', $post->track_filename));

      // Build post description
      $routing = $this->getContext()->getRouting();
      $description = sprintf(
        '%s<ul><li><a href="%s">Écouter</a></li><li><a href="%s">Télécharger</a></li></ul>',
        Markdown($post->body),
        $routing->generate('post_show', array('slug' => $post->slug)),
        $track_file_url
      );

      // Make sure no errors are generated when files do not exist (useful in dev mode)
      if (!is_readable(sfConfig::get('sf_web_dir').'/tracks/'.$post->track_filename))
      {
        $file_size = 0;
      }
      else
      {
        $file_size = strlen(file_get_contents(sfConfig::get('sf_web_dir').'/tracks/'.$post->track_filename));
      }

      $item = new sfFeedItem();
      $item->initialize(array(
        'title'       => sprintf('%s - %s', $post->track_author, $post->track_title),
        'link'        => '@post_show?slug='.$post->slug,
        'authorName'  => $post->getSfGuardUser()->username,
        'pubDate'     => $publish_timestamp,
        'uniqueId'    => $post->slug,
        'description' => $description
      ));
      $enclosure = new sfFeedEnclosure();
      $enclosure->initialize(array(
        'url'       => $track_file_url,
        'length'    => $file_size,
        'mimeType'  => 'audio/mp3'
      ));
      $item->setEnclosure($enclosure);
      $feed->addItem($item);
    }
    $this->feed = $feed;
  }

  // TODO : rename to postData
  public function executeUrl(sfWebRequest $request)
  {
    $post = Doctrine::getTable('Post')->getRandomPost();
    $post_data = array(
      'url' => $this->getContext()->getRouting()->generate('post_show', array('slug' => $post->getSlug()))
    );

    // Pass data to view
    $this->post_data = $post_data['url'];

    // Select and configure template
    $this->setLayout(false);
    sfConfig::set('sf_web_debug', false);
    return sfView::SUCCESS;
  }
}
