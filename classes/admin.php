<?php

include_once('../connection.php');
session_start();

  class Admin
  {

    function __construct()
    {
      $this->post_successfull = false;
      if ( ( isset( $_POST['action'] ) ) && ( $_POST['action'] == "login" ) )
      {
        $username = $_POST['username'];
        $username = mysql_escape_string( $username );
        $password = $_POST['password'];
        $password = mysql_escape_string( $password );

        $this->login( $username, $password );
      }
      elseif ( ( isset( $_GET['log_out'] ) ) && ( $_GET['log_out'] == true ) )
      {
        $this->logout();
      }
      elseif ( ( isset( $_POST['action'] ) ) && ( $_POST['action'] == "new-post" ) )
      {
        if ( $this->newPost() )
        {
          $_SESSION["newPostAdded"] = true;
        }
      }

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
          if ( $user->pass == $password )
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

      $check = getimagesize( $_FILES["photo"]["tmp_name"] );
      if( $check == true )
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

    public function loggedIn()
    {
      if ( isset( $_SESSION["user"] ) )
        return true;

      return false;
    }

    public function newPostAdded()
    {
      if ( isset( $_SESSION["newPostAdded"] ) )
      {
        unset($_SESSION["newPostAdded"]);
        return true;
      }

      return false;
    }

  }
 ?>
