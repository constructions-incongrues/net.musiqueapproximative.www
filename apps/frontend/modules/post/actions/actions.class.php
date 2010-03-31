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
    if (!$request->getParameter('slug', null))
    {
      $post = Doctrine::getTable('Post')->getLastPost();
    }
    else
    {
      $post = Doctrine::getTable('Post')->getOnlinePostBySlug($request->getParameter('slug'));
    }

    // Throw a 404 error if no post is found
    $this->forward404Unless($post);

    $this->getResponse()->setTitle(sprintf('%s - %s | Musique Approximative', $post->track_author, $post->track_title));

    // Pass data to view
    $this->post = $post;
    $this->post_next = Doctrine::getTable('Post')->getNextPost($post);
    $this->post_previous = Doctrine::getTable('Post')->getPreviousPost($post);
  }

  public function executeList(sfWebRequest $request)
  {
    if ($this->getRequestParameter('q'))
    {
      $posts = Doctrine::getTable('Post')->search($request->getParameter('q'));
    }
    else
    {
      $posts = Doctrine::getTable('Post')->getOnlinePosts($request->getParameter('contributor'));
    }

    $this->posts = $posts;
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

      $item = new sfFeedItem();
      $item->initialize(array(
        'title'       => sprintf('%s - %s', $post->track_author, $post->track_title),
        'link'        => '@post_show?slug='.$post->slug,
        'authorName'  => $post->getSfGuardUser()->username,
        'pubDate'     => $publish_timestamp,
        'uniqueId'    => $post->slug,
        'description' => Markdown($post->body)
      ));
      $enclosure = new sfFeedEnclosure();
      $enclosure->initialize(array(
        'url'       => htmlspecialchars(sprintf('http://www.musiqueapproximative.net/tracks/%s', $post->track_filename)),
        'length'    => strlen(file_get_contents(sfConfig::get('sf_web_dir').'/tracks/'.$post->track_filename)),
        'mimeType'  => 'audio/mp3'
      ));
      $item->setEnclosure($enclosure);
      $feed->addItem($item);
    }
    $this->feed = $feed;
  }
}
