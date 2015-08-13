<?php

class DALQueryResult
{
   private $_results = array();

   public function __construct()
   {

   }

   public function __set( $var, $val )
   {
      $this->_results[$var] = $val;
   }

   public function __get( $var )
   {
      if ( isset( $this->_results[$var] ) )
      {
         return $this->_results[$var];
      }
      else
      {
         return null;
      }
   }
}
