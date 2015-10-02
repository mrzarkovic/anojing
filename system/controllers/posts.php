<?php

  class Posts extends Core
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
      $posts = new Post();
      $posts->fetch_all();

      //var_dump($posts->list);

      $this->set_template("posts.inc");
      $to_tpl['posts'] = $posts->list;

      return $this->generate_template($to_tpl);
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
      return $this->generate_template($to_tpl);
    }

    public function edit_posts()
    {
      if( !$this->logged_in() )
      {
        header('Location: /login');
      }

      $this->set_template('edit-posts.inc');

      $posts = new Post();
      $posts->fetch_all();
      $to_tpl['posts'] = $posts->list;

      return $this->generate_template($to_tpl);
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

      $post = new Post();
      $post->fetch_by_id($post_id);

      if ($post->delete_from_db())
      {
         $to_tpl['notification'] = "The post has been deleted. Forever!";
      }
      else
      {
         $to_tpl['error'] = "Oops, there has been an error deleting the post.";
      }

      return $this->generate_template($to_tpl);

    }

    private function _new_post()
    {
      $target_dir = "photos/";
      $target_file = $target_dir . basename($_FILES["photo"]["name"]);

      $file_type = pathinfo( $target_file, PATHINFO_EXTENSION );

      if ( $_FILES["photo"]["tmp_name"] == "" )
      {
        return false;
      }

      if( $check = getimagesize( $_FILES["photo"]["tmp_name"] ) )
      {

        $title = $_POST['title'];
        $title = mysql_escape_string( $title );
        $photo_name = basename($_FILES["photo"]["name"]);
        $photo_ext = $file_type;

        $post = new Post();
        $post->title       = $title;
        $post->photo_name  = $photo_name;
        $post->photo_ext   = $photo_ext;

        if ( move_uploaded_file( $_FILES["photo"]["tmp_name"], $target_file ) )
        {
          return $post->add_to_db();
        }
        else
        {
          return false;
        }
      }

      return false;
    }
  }

 ?>
