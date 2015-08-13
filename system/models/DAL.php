<?php

/**
* DAL stands for "Data Access Layer" or "Data Access Link".
* http://code.tutsplus.com/tutorials/simple-php-class-based-querying--net-11863
*
* http://slashnode.com/pdo-for-elegant-php-database-access/
*/
class DAL
{
   public function __construct()
   {

   }

   /**
   * Connect to a database
   * @return bool $conn
   */
   private function db_connect()
   {
      $conn = mysql_connect( DB_HOST, DB_USER, DB_PASSWORD )
      or die ("<br/>Could not connect to MySQL server");

      mysql_select_db( DB_DB, $conn )
      or die ("<br/>Could not select the indicated database");

      return $conn;
   }

   /**
    * Querying method that will turn
    * SELECT queries into DALQueryResult objects.
    *
    * @param  string $sql [SQL query]
    * @return array $results [array of DALQueryResult objects]
    */
   private function query( $sql )
   {
      var_dump($sql);
      $this->db_connect();

      $res = mysql_query( $sql );

      // If the query was successful and the query
      // was not a SELECT query, it will return true.
      if ( $res )
      {
         if ( strpos( $sql, 'SELECT' ) === false )
         {
            return true;
         }
      }
      // If there are not any results, it returns
      // null on a SELECT query, false on other queries.
      else
      {
         if ( strpos( $sql, 'SELECT' ) === false )
         {
            return false;
         }
         else
         {
            return null;
         }
      }
      // If it was a SELECT, then it converts the results
      // into an array of DALQueryResult objects.
      $results = array();

      while ( $row = mysql_fetch_array( $res ) )
      {

         $result = new DALQueryResult();

         foreach ( $row as $k => $v )
         {
            $result->$k = $v;
         }

         $results[] = $result;
      }

      return $results;
   }

   public function get_models_by_make_name( $name )
   {
      $sql = "SELECT models.id as id,
                     models.name as name,
                     makes.name as make
               FROM  models
               INNER JOIN makes
               ON    models.make_id = makes.id
               WHERE makes.name='$name'";

      return $this->query( $sql );
   }
}
