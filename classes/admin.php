<?php

session_start();
$_SESSION['errors'] = array();
//$_SESSION['newPostAdded'] = false;
$_SESSION['notifications'] = array();

  class Admin
  {

    public $admin_page = "";

    function __construct()
    {
      echo "this is Admin controller";
      if ( ( isset( $_POST['action'] ) ) && ( $_POST['action'] == "login" ) )
      {
        $username = $_POST['username'];
        $username = mysql_escape_string( $username );
        $password = $_POST['password'];
        $password = mysql_escape_string( $password );

        //$this->createAdmin();

        if ( !$this->login( $username, $password ) )
        {
          $error['login'] = "Login failed";
          $_SESSION['errors'] = $error;
        }
      }
      elseif ( ( isset( $_GET['action'] ) ) && ( $_GET['action'] == "log_out" ) )
      {
        $this->logout();
      }
      elseif ( ( isset( $_POST['action'] ) ) && ( $_POST['action'] == "new_post" ) )
      {
        $this->admin_page = "new_post";
        if ( $this->newPost() )
        {
          //$_SESSION['newPostAdded'] = true;
          $notification['new-post'] = "Post added.";
          $_SESSION['notifications'] = $notification;
        }
        else {
          $error['new-post'] = "Upload failed.";
          $_SESSION['errors'] = $error;
        }
      }
      elseif ( ( isset( $_GET['page'] ) ) && ( $_GET['page'] == "list_posts" ) )
      {
        $this->admin_page = "list_posts";
        if ( ( isset( $_GET['action'] ) ) && ( $_GET['action'] == "delete_post" ) )
        {
          if ( !isset($_GET['id']) || !$post_id = $_GET['id'])
          {
            $error['delete-post'] = "There has been an error.";
            $_SESSION['errors'] = $error;
            return 0;
          }
          if ( $this->deletePost( $post_id ) )
          {
            $notification['delete-post'] = "Post has been deleted.";
            $_SESSION['notifications'] = $notification;
          }
          else
          {
            $error['delete-post'] = "There has been an error.";
            $_SESSION['errors'] = $error;
          }
        }
      }

    }

    public function getPage()
    {
      return $this->admin_page;
    }

    private function createAdmin()
    {
      $name = "admin";
      $pass = md5( "topsecret321?" );

      $link = connect_to_db();
      $query = "INSERT INTO users (name, pass) VALUES ('$name','$pass')" or die( "Error in the consult.." . mysqli_error($link) );
      $result = $link->query( $query );

      if ( !$result )
        return 0;
    }

    private function login( $username = "", $password = "" )
    {
      $link = connect_to_db();
      $query = "SELECT * FROM users" or die( "Error in the consult.." . mysqli_error( $link ) );
      $result = $link->query( $query );

      if ( !$result )
        return 0;

      $users = array();
      //$password = md5($password);

      while( $user = mysqli_fetch_object( $result ) )
      {
        if ( $user->name == $username )
          if ( $user->pass == md5( $password ) )
          {
            $user->logged_id = true;
            $_SESSION["user"] = $user;
            return $user;
          }
      }
      return false;
    }

    private function logout()
    {
      session_destroy();
      header('Location: index.php');
    }

    private function newPost()
    {
      $target_dir = "../photos/";
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

    private function deletePost( $id = 0 )
    {
        $link = connect_to_db();
        $query = "DELETE FROM posts WHERE id='$id'" or die("Error in the consult.." . mysqli_error($link));
        $result = $link->query( $query );

        if ( !$result )
          return 0;

        return true;
    }

    public function loggedIn()
    {
      if ( isset( $_SESSION["user"] ) )
        return true;

      return false;
    }

    public function getErrors()
    {
      if ( isset( $_SESSION["errors"]) )
        return $_SESSION["errors"];

      return false;
    }

    public function getNotifications()
    {
      if ( isset( $_SESSION["notifications"]) )
        return $_SESSION["notifications"];

      return false;
    }
/*
    public function newPostAdded()
    {
      if ( isset( $_SESSION['newPostAdded'] ) )
      {
        return $_SESSION['newPostAdded'];
      }

      return false;
    }
*/
  }
 ?>
