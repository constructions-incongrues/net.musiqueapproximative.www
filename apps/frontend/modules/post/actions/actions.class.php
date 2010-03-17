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

    // Pass data to view
    $this->post = $post;
    $this->post_next = Doctrine::getTable('Post')->getNextPost($post);
    $this->post_previous = Doctrine::getTable('Post')->getPreviousPost($post);
  }

  public function executeList(sfWebRequest $request)
  {
    $posts = Doctrine::getTable('Post')->getOnlinePosts();
    $this->posts = $posts;
  }

  public function executeSearch(sfWebRequest $request)
  {
    $results = Doctrine::getTable('Post')->search($request->getParameter('q'));
    $posts = array();
    foreach ($results as $result)
    {
      $post = Doctrine::getTable('Post')->getOnlinePostById($result['id']);
      if ($post)
      {
        $posts[] = $post;
      }
    }
    $this->posts = $posts;
  }
}
