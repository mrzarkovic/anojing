<?php

require_once('connection.php');
require_once('classes/template.php');
require_once('classes/post.php');
require_once('classes/admin.php');
require_once('classes/route.php');

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

  }
 ?>
