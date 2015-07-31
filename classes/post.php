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

    public function addPost()
    {

      if( !$this->loggedIn() )
      {
        header('Location: /login');
      }

      $this->setTemplate('new-post.inc');

      if ( ( isset( $_POST['action'] ) ) && ( $_POST['action'] == "new_post" ) )
      {
        if ( $this->newPost() )
        {
          //$_SESSION['newPostAdded'] = true;
          $notification['new-post'] = "Post added.";
          $_SESSION['notifications'] = $notification;
          echo "post has been added.";
        }
        else {
          $error = (object) array( "error" => "Upload failed.");
          $this->template->entries[] = $error;
        }
      }
      echo $this->template->generate_markup();
    }

    public function listPosts()
    {
      if( !$this->loggedIn() )
      {
        header('Location: /login');
      }

      $this->setTemplate('list-posts.inc');

      $posts = $this->getPosts();
      foreach ( $posts as $post )
        $this->template->entries[] = $post;

      echo $this->template->generate_markup();
    }

    private function newPost()
    {
      $target_dir = "photos/";
      $target_file = $target_dir . basename($_FILES["photo"]["name"]);
      $upload_ok = 1;
      $file_type = pathinfo( $target_file, PATHINFO_EXTENSION );

      if ( $_FILES["photo"]["tmp_name"] == "" )
      {
        return false;
      }

      if( $check = getimagesize( $_FILES["photo"]["tmp_name"] ) )
      {
        $link = connect_to_db();

        $title = $_POST['title'];
        $title = mysql_escape_string( $title );
        $photo_name = basename($_FILES["photo"]["name"]);
        $photo_ext = $file_type;

        $datetime = new DateTime();
        $datetime = $datetime->format('Y-m-d H:i:s');

        $query = "INSERT INTO posts (title, photo_name, photo_ext, posted_on) VALUES ('$title','$photo_name','$photo_ext','$datetime')" or die("Error in the consult.." . mysqli_error($link));
        $result = $link->query( $query );

        if ( !$result )
          return 0;

        $upload_ok = 1;
      }
      else
      {
        //echo "File is not an image.";
        $upload_ok = 0;
      }

      if ( $upload_ok == 0 )
      {
        //var_dump("nije slika");exit;
        return false;
      }
      else
      {
        if ( move_uploaded_file( $_FILES["photo"]["tmp_name"], $target_file ) )
        {
          //var_dump("upload done");
          return true;
        }
        else
        {
          //var_dump("upload error");exit;
          return false;
        }
      }

      return false;
    }

    private function getPosts()
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
