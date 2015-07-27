<?php

include_once('connection.php');

  class Site
  {
    function __construct()
    {

    }

    public function get_posts()
    {
      $posts = array();
      $link = connect_to_db();
      $query = "SELECT title FROM posts" or die("Error in the consult.." . mysqli_error($link));
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
