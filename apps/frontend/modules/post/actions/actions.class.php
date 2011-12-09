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

    // Define opengraph metadata (see http://ogp.me/)
    $this->getContext()->getConfiguration()->loadHelpers('Markdown');
    $this->getResponse()->addMeta('og:description', strip_tags(Markdown($post->body)));
    $this->getResponse()->addMeta('og:image', 'http://musiqueapproximative.net/images/logo.png');
    $this->getResponse()->addMeta('og:audio', sprintf('http://www.musiqueapproximative.net/tracks/%s', $post->track_filename));
    $this->getResponse()->addMeta('og:audio:title', $post->track_title);
    $this->getResponse()->addMeta('og:audio:artist', $post->track_author);
    $this->getResponse()->addMeta('og:audio:album', 'Unknown album');
    $this->getResponse()->addMeta('og:audio:type', 'application/mp3');
    
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

    // Formats specifics
    $formats = array(
    	'csv'  => array('layout' => false, 'contentType' => 'text/csv'),
    	'max'  => array('layout' => false, 'contentType' => 'application/maxmsp+text'),
    	'xspf' => array('layout' => false, 'contentType' => 'application/xspf+xml')
    );
    if (in_array($request->getParameter('sf_format'), array_keys($formats))) {
    	$this->setLayout($formats[$request->getParameter('sf_format')]['layout']);
    	$this->getResponse()->setContentType($formats[$request->getParameter('sf_format')]['contentType']);
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
      $track_file_url = htmlspecialchars(sprintf('http://www.musiqueapproximative.net/tracks/%s', rawurlencode($post->track_filename)));

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
        'description' => Markdown($post->body)
      ));
      $enclosure = new sfFeedEnclosure();
      $enclosure->initialize(array(
        'url'       => $track_file_url,
        'length'    => $file_size,
        'mimeType'  => 'audio/mpeg'
      ));
      $item->setEnclosure($enclosure);
      $feed->addItem($item);
    }
    $this->feed = $feed;
  }
  
  public function executeRandom(sfWebRequest $request)
  {
  	$post = Doctrine::getTable('Post')->getRandomPost();
  	
  	sfConfig::set('sf_web_debug', false);
  	
  	// Pass data to view
  	$this->post = $post;
  }
  
  public function executeNext(sfWebRequest $request)
  {
  	$post = Doctrine::getTable('Post')->getNextPost(Doctrine::getTable('Post')->find($request->getParameter('current')));
  	
  	sfConfig::set('sf_web_debug', false);
  	
  	// Pass data to view
  	$this->post = $post;
  }
  
  public function executePrev(sfWebRequest $request)
  {
  	$post = Doctrine::getTable('Post')->getPreviousPost(Doctrine::getTable('Post')->find($request->getParameter('current')));
  	
  	sfConfig::set('sf_web_debug', false);
  	
  	// Pass data to view
  	$this->post = $post;
  }
}
