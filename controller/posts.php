<?php

  class Post extends Core
  {
    function __construct()
    {
      parent::__construct();
    }

    /**
     * Shows a page with all the posts
     */
    public function show_posts()
    {
      $posts = $this->_get_posts();
      $this->set_template("posts.inc");
      $to_tpl['posts'] = $posts;

      return $this->template->generate_template($to_tpl);
    }

    /**
     * Show a page to add a new post
     */
    public function add_post()
    {

      if( !$this->logged_in() )
      {
        header('Location: /login');
      }

      $to_tpl['error'] = "";
      $to_tpl['notification'] = "";

      $this->set_template('new-post.inc');

      if ( ( isset( $_POST['action'] ) ) && ( $_POST['action'] == "new_post" ) )
      {
        if ( $this->_new_post() )
        {
          $to_tpl['notification'] = "Post added.";
        }
        else {
          $to_tpl['error'] = "Upload failed.";
        }
      }
      return $this->template->generate_template($to_tpl);
    }

    public function list_posts()
    {
      if( !$this->logged_in() )
      {
        header('Location: /login');
      }

      $this->set_template('list-posts.inc');

      $posts = $this->_get_posts();
      $to_tpl['posts'] = $posts;

      return $this->template->generate_template($to_tpl);
    }

    public function delete_post( $post_id )
    {
      if( !$this->logged_in() )
      {
        header('Location: /login');
      }

      $this->set_template('delete-post.inc');
      $to_tpl['error'] = "";
      $to_tpl['notification'] = "";

      $link = connect_to_db();
      $query = "DELETE FROM posts WHERE id='$post_id'" or die("Error in the consult.." . mysqli_error($link));
      $result = $link->query( $query );

      if ( !$result )
      {
        $to_tpl['error'] = "Oops, there has been an error deleting the post.";
      }
      else
      {
        $to_tpl['notification'] = "The post has been deleted. Forever!";
      }

      return $this->template->generate_template($to_tpl);
    }

    private function _new_post()
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
        return false;
      }
      else
      {
        if ( move_uploaded_file( $_FILES["photo"]["tmp_name"], $target_file ) )
        {
          return true;
        }
        else
        {
          return false;
        }
      }

      return false;
    }

    private function _get_posts()
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
