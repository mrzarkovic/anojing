<?php

session_start();

require_once('connection.php');
require_once('engines/template.php');
require_once('engines/route.php');
require_once('classes/post.php');
require_once('classes/admin.php');

  class Core
  {

    public $template;
    public $route;

    function __construct()
    {
      $this->template = new Template();
      $this->route = new Route();
    }

    public function setTemplate( $template_file = "" )
    {
      $this->template->template_file = $template_file;
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

  }
 ?>
