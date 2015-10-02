<?php

class Core
{

   /**
   * Stores an instance of the Template class
   * @var object
   */
   public $template;

   /**
   * Stores an instance of the Route class
   * @var object
   */
   public $route;

   function __construct()
   {
      $this->template = new Template();
      $this->route = new Route();
   }

   /**
   * Sets a path to the template file to use
   * @param string $template_file
   */
   public function set_template( $template_file = "" )
   {
      $this->template->template_file = $template_file;
   }

   /**
   * Generates a template
   * @param  array  $template_data [description]
   */
   public function generate_template( $template_data = array() )
   {
      $this->template->generate_template( $template_data );
   }

   /**
   * Cheks if a user is logged in
   * @return bool
   */
   public function logged_in()
   {
      if ( isset( $_SESSION["username"] ) )
        return true;

      return false;
   }

   public function add_route( $route, $function ) {
      $this->route->add( $route, $function );
   }

   public function run() {
      $this->route->run();
   }

}
?>
