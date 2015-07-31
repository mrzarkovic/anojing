<?php

class Route
{
  private $_uri       = array();
  private $_function  = array();
  //private $_method    = array();

  /**
  * Builds a collection of internal URL's to look for
  * @param string $uri
  * @param string $method
  */
  public function add( $uri = '', $function = '' )
  {
    $this->_uri[] = $uri;

    if ( $function != '' )
    {
      $this->_function[] = $function;
    }
/*
    if ( $method != '' )
    {
      $this->_method[] = $method;
    }
  */
  }

  /**
   * Makes the thing run
   */
  public function submit()
  {

    $uri = isset($_REQUEST['uri']) ? '/' .$_REQUEST['uri'] : '/';

    foreach ($this->_uri as $key => $value)
    {
      if (
            ( preg_match("#^$value$#", $uri) ) /*&&
            ( $_SERVER['REQUEST_METHOD'] == $this->_method[$key] )*/
         )
      {
        $useFunction = $this->_function[$key];

        /* If $useMethod is a class name, instantiate it. */
        if ( is_string($useFunction) )
        {
          $args = explode( "@", $useFunction );

          if (!isset($args[1]))
          {
            throw new Exception( 'Invalid route: No function defined' );
          }

          $class = $args[1];
          $func = $args[0];

          $call = new $class();
          return $call->$func();
        }
        /* else, presume it is an anonymous function */
        else
        {
          return call_user_func($useFunction);
        }
      }
    }
    throw new Exception( 'Error 404: Page not found.' );
  }
}
