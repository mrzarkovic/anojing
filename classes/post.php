<?php

  class Post extends Core
  {
    function __construct()
    {
      parent::__construct();
      //echo $this->showPosts();
    }

    public function showPosts()
    {
      $posts = $this->getPosts();
      $this->setTemplate("posts.inc");

      foreach ( $posts as $post )
        $this->template->entries[] = $post;

      echo $this->template->generate_markup();

    }

    public function getPosts()
    {
      $posts = array();
      $link = connect_to_db();
      $query = "SELECT * FROM posts ORDER BY posted_on DESC" or die("Error in the consult.." . mysqli_error($link));
      $result = $link->query( $query );

      if (!$result)
        return 0;

      while( $row = mysqli_fetch_object( $result ) )
      {
        $posts[] = $row;
      }

      return $posts;
    }
  }
 ?>
