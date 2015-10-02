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

      echo $this->generate_template($to_tpl);
    }

    private function _create_admin()
    {
      $admin = new User();
      $admin->name = "admin";
      $admin->pass = md5( "topsecret321?" );

      if ( !$admin->add_to_db() )
        return 0;
    }

    private function _login( $username = "", $password = "" )
    {
      $user = new User();

      if (
             ( $user->fetch_by_username( $username ) )
          && ( $user->check_password( $password ) )
          )
      {
          //$this->session->set_userdata('logged_in', TRUE);
          //$this->session->set_userdata('username', $_POST['username']);

          $_SESSION["logged_in"] = TRUE;
          $_SESSION["username"] = $username;

          return true;
      }

      return false;

    }

    public function logout()
    {
      session_destroy();
      header('Location: /');
    }

  }
