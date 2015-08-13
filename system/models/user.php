<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	User model
 */
class User extends Repository
{
   const TABLE_NAME = 'users';

   static public $id_column = "id";

   static public $columns = array(
      'name'        => 'string',
      'pass'        => 'string',
   );

   public function __construct( $row = array() )
   {
     parent::__construct( $row );
   }

   /**
    * Fetch record by username
    * @param  string $username [description]
    * @return [type]           [description]
    */
   public function fetch_by_username($username = '')
	{
		return parent::fetch_by_field('name', $username);
	}

   /**
    * Create hashed password from raw input
    * @param string $raw_password
    */
	public function set_password($raw_password = '')
	{
		return $this->hash_password($raw_password);
	}

   /**
    * Check password against the database record
    * @param  string $raw_password
    * @return bool
    */
	public function check_password($raw_password = '')
	{
		if ( md5( $raw_password ) == $this->pass )
         return true;
      else
         return false;
	}

   /**
    * Return hashed password
    * @param  string $raw_password
    */
	public function hash_password($raw_password = '')
	{
		$this->pass = md5( $raw_password );

		return $this->pass;
	}
}
