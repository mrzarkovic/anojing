<?php

  class Admin extends Core
  {
    function __construct()
    {
      parent::__construct();
    }

    public function show_login()
    {

      $admin = new Admin();
      if( $this->logged_in() )
      {
        header('Location: /new-post');
      }

      $this->set_template('admin/login.inc');
      $to_tpl['error'] = "";

      if ( ( isset( $_POST['action'] ) ) && ( $_POST['action'] == "login" ) )
      {
        $username = $_POST['username'];
        $username = mysql_escape_string( $username );
        $password = $_POST['password'];
        $password = mysql_escape_string( $password );

        //$this->_create_admin();

        if ( !$this->_login( $username, $password ) )
        {
          $to_tpl['error'] = "Wrong credentials.";;
        }
        else
        {
          header('Location: /new-post');
        }
      }

      echo $this->template->generate_template($to_tpl);
    }

    private function _create_admin()
    {
      $name = "admin";
      $pass = md5( "topsecret321?" );

      $link = connect_to_db();
      $query = "INSERT INTO users (name, pass) VALUES ('$name','$pass')" or die( "Error in the consult.." . mysqli_error($link) );
      $result = $link->query( $query );

      if ( !$result )
        return 0;
    }

    private function _login( $username = "", $password = "" )
    {
      $link = connect_to_db();
      $query = "SELECT * FROM users" or die( "Error in the consult.." . mysqli_error( $link ) );
      $result = $link->query( $query );

      if ( !$result )
        return 0;

      $users = array();

      $password = md5( $password );
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

    public function logout()
    {
      session_destroy();
      header('Location: /');
    }

    private function _delete_post( $id = 0 )
    {
        $link = connect_to_db();
        $query = "DELETE FROM posts WHERE id='$id'" or die("Error in the consult.." . mysqli_error($link));
        $result = $link->query( $query );

        if ( !$result )
          return 0;

        return true;
    }

  }
 ?>
