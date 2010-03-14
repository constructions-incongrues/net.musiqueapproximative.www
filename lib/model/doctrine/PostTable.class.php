<?php

class PostTable extends Doctrine_Table
{
  /**
   * Returns last online post.
   * 
   * @return Post
   */
  public function getLastPost()
  {
    // Stub
    return $this->find(1);
  }

  /**
   * Returns next post.
   *
   * @param  Post $post
   * @return Post
   */
  public function getNextPost(Post $post)
  {
    // Stub
    return $this->find(1);
  }

  /**
   * Returns previous post.
   *
   * @param  Post $post
   * @return Post
   */
  public function getPreviousPost(Post $post)
  {
    // Stub
    return $this->find(1);
  }
}
