<?php

class Route
{
  private $_uri     = array();
  private $_method  = array();

  /**
  * Builds a collection of internal URL's to look for
  * @param string $uri
  * @param string $method
  */
  public function add( $uri = '', $method = '' )
  {
    $this->_uri[] = $uri;

    if ( $method != '' )
    {
      $this->_method[] = $method;
    }
  }

  /**
   * Makes the thing run
   */
  public function submit()
  {

    $uri = isset($_REQUEST['uri']) ? '/' .$_REQUEST['uri'] : '/';

    foreach ($this->_uri as $key => $value)
    {
      if (preg_match("#^$value$#", $uri))
      {
        $useMethod = $this->_method[$key];

        /* If $useMethod is a class name, instantiate it. */
        if ( is_string($useMethod) )
        {
          new $useMethod();
        }
        /* else, presume it is an anonymous function */
        else
        {
          call_user_func($useMethod);
        }
      }
    }
  }
}
