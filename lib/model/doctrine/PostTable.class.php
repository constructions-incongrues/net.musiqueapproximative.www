<?php

class PostTable extends Doctrine_Table
{
  const FIELDS_BASIC = 'p.body, p.track_title, p.track_author, p.track_filename, p.slug, u.username';

  /**
   * Returns last online post.
   * 
   * @return Post
   */
  public function getLastPost()
  {
    $q = Doctrine_Query::create()
      ->select(self::FIELDS_BASIC)
      ->from('Post p')
      ->leftJoin('p.sfGuardUser u on p.contributor_id = u.id')
      ->where('p.is_online = 1 and p.publish_on <= now()')
      ->orderBy('p.publish_on DESC')
      ->limit(1);
    $post = $q->fetchOne();
    $q->free();

    return $post;
  }

  public function getOnlinePostBySlug($post_slug)
  {
    $q = Doctrine_Query::create()
      ->select(self::FIELDS_BASIC)
      ->from('Post p')
      ->leftJoin('p.sfGuardUser u on p.contributor_id = u.id')
      ->where('p.is_online = 1 and p.publish_on <= now() and p.slug = ?');
    $post = $q->fetchOne(array($post_slug));
    $q->free();

    return $post;
  }

  public function getOnlinePostById($post_id)
  {
    $q = Doctrine_Query::create()
      ->select(self::FIELDS_BASIC)
      ->from('Post p')
      ->leftJoin('p.sfGuardUser u on p.contributor_id = u.id')
      ->where('p.is_online = 1 and p.publish_on <= now() and p.id = ?');
    $post = $q->fetchOne(array($post_id));
    $q->free();

    return $post;
  }

  /**
   * Returns next post.
   *
   * @param  Post $post
   * @return Post
   */
  public function getNextPost(Post $post)
  {
    $q = Doctrine_Query::create()
      ->select(self::FIELDS_BASIC)
      ->from('Post p')
      ->leftJoin('p.sfGuardUser u on p.contributor_id = u.id')
      ->where('p.is_online = 1 and p.publish_on > ?')
      ->orderBy('p.publish_on ASC')
      ->limit(1);
    $post = $q->fetchOne(array($post->publish_on));
    $q->free();

    return $post;
  }

  /**
   * Returns previous post.
   *
   * @param  Post $post
   * @return Post
   */
  public function getPreviousPost(Post $post)
  {
    $q = Doctrine_Query::create()
      ->select(self::FIELDS_BASIC)
      ->from('Post p')
      ->leftJoin('p.sfGuardUser u on p.contributor_id = u.id')
      ->where('p.is_online = 1 and p.publish_on < ?')
      ->orderBy('p.publish_on DESC')
      ->limit(1);
    $post = $q->fetchOne(array($post->publish_on));
    $q->free();

    return $post;
  }

  public function getOnlinePosts($contributor = null)
  {
    $q = Doctrine_Query::create()
      ->select(self::FIELDS_BASIC)
      ->from('Post p')
      ->leftJoin('p.sfGuardUser u on p.contributor_id = u.id')
      ->where('p.is_online = 1')
      ->orderBy('p.publish_on DESC');

    if ($contributor)
    {
      $q->andWhere('u.username = ?', (string)$contributor);
    }
 
    return $q->execute();
  }

  public function search($query)
  {
    $results = parent::search($query);
    $posts = array();
    foreach ($results as $result)
    {
      $post = $this->getOnlinePostById($result['id']);
      if ($post)
      {
        $posts[] = $post;
      }
    }

    return $posts;
  }
}
