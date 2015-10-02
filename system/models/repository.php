<?php

use \PDO;

class Repository
{
   const TABLE_NAME = "";
   private $connection;
   private $log;

   public $list;
   public $total;

   static public $id_column = '';
	static public $columns = array();

   public function __construct( $row = array() )
   {
      if (!isset($this->log))
         $this->log = new Log();

      $this->list			= array();
		$this->total		= 0;

      if ($this->connection === null) {
         $this->connection = new PDO(
                 'mysql:host='.DB_HOST.';dbname='.DB_DB,
                 DB_USER,
                 DB_PASSWORD
             );
         $this->connection->setAttribute(
             PDO::ATTR_ERRMODE,
             PDO::ERRMODE_EXCEPTION
         );
      }
      if ( is_array( $row ) )
			$this->_set_from_db( $row );
		else
			$this->_set_from_db();
   }

   protected function _set_from_db( $row = array() )
	{
		$columns = array_merge( array( static::$id_column => 'int' ), static::$columns );

		foreach ($columns as $column => $type)
		{
			switch ($type)
			{
				case 'int':
				case 'fk_or_null':
					$this->{$column}	= (int) ( isset($row[$column]) ? $row[$column] : 0 );
					break;
				case 'bool_false':
				case 'bool':
					$this->{$column}	= isset($row[$column]) ? (bool) $row[$column] : FALSE;
					break;
				case 'bool_true':
					$this->{$column}	= isset($row[$column]) ? (bool) $row[$column] : TRUE;
					break;
				case 'datetime':
					$this->{$column}	= isset($row[$column]) ? new DateTime($row[$column]) : new DateTime();
					break;
				case 'string':
					$this->{$column}	= isset($row[$column]) ? (string) $row[$column] : '';
					break;
				default:
					$this->{$column}	= isset($row[$column]) ? new $type($row[$column]) : new $type();
			}
		}
	}

   static public function _get_pdo_data_type($type)
	{
		$type = strtolower($type);

		switch ($type)
		{
			case 'int':
			case 'bool':
			case 'fk_or_null':
				return PDO::PARAM_INT;
			case 'string':
			default:
				return PDO::PARAM_STR;
		}
	}

	static public function _get_value_by_pdo_data_type($value, $type)
	{
		$type = strtolower($type);

		switch ($type)
		{
			case 'int':
				return (int) $value;
			case 'fk_or_null':
				return ($value) ?: NULL;
			case 'bool':
				return (bool) $value;
			case 'datetime':
				return $value->format('Y-m-d H:i:s');
			case 'string':
			default:
				return (string) $value;
		}
	}

   /**
    * Return record ID
    * @return int
    */
   public function get_id()
	{
		return $this->{static::$id_column};
	}

   public function set_id($id = 0)
	{
		return $this->{static::$id_column} = (int) $id;
	}

   /**
    * Fetch record from database by ID
    * @param  integer $id
    * @return bool
    */
   public function fetch_by_id( $id = 0 )
	{
		$id = $id ?: $this->get_id();
		if ( ! $id ) return FALSE;

		// set table
		$table = static::TABLE_NAME;

		// set fields
		$columns = array_merge( array(static::$id_column), array_keys(static::$columns) );
		$fields = array();

		foreach ($columns as $field)
			$fields[] = "`{$field}`";

      $fields = implode(',', $fields);

		// set id field
		$id_field = static::$id_column;

		try
		{
			$stmt = $this->connection->prepare("SELECT
											{$fields}
										FROM
											`{$table}`
										WHERE `{$id_field}` = :id
										LIMIT 0, 1");
			$stmt->bindValue(':id', $id, PDO::PARAM_STR);
			$stmt->execute();

			if ($stmt->rowCount())
			{
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$this->_set_from_db($row);

				return TRUE;
			}
		}
		catch (PDOException $e)
		{
			$message = sprintf('Exception <%s> in file "%s" on line %s: %s', $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
         $this->log->write_log('error', $message);
		}
		return FALSE;
	}

   public function fetch_all($order_column = '', $order = 'ASC')
	{
		// set table name
		$table = static::TABLE_NAME;

		$columns = array_merge( array(static::$id_column), array_keys(static::$columns) );
		$fields = array();
		foreach ($columns as $field)
			$fields[] = "`{$field}`";
		$fields = implode(',', $fields);

		// set order clause
		$default_order_column	= isset( static::$default_order_column )	? static::$default_order_column		: '';

		if ($order_column)
			$clause_order = "ORDER BY `{$order_column}` {$order}";
		else if ($default_order_column)
			$clause_order = "ORDER BY `{$default_order_column}` {$order}";
		else
			$clause_order = '';

		try
		{
			$stmt = $this->connection->prepare(
				"SELECT
					{$fields}
				FROM
					`{$table}`
				{$clause_order}"
			);

			$stmt->execute();

         $class = get_class($this);

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->list[] = new $class($row);
				$this->total++;
			}

			return TRUE;
		}
		catch (PDOException $e)
		{
			$message = sprintf('Exception <%s> in file "%s" on line %s: %s', $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
			$this->log->write_log('error', $message);
		}

		return FALSE;
	}

   public function fetch_by_field($field_name = '', $value = '')
	{
		if ( ! $field_name )
			return FALSE;

		if ( ! isset( static::$columns[$field_name] ) )
			return FALSE;

		$value = $value ?: $this->{$field_name};
		if ( ! $value ) return FALSE;

		// set table
		$table = static::TABLE_NAME;

		// set fields
		$columns = array_merge( array(static::$id_column), array_keys(static::$columns) );
		$fields = array();
		foreach ($columns as $field)
			$fields[] = "`{$field}`";
		$fields = implode(',', $fields);

		try
		{
			$stmt = $this->connection->prepare("SELECT
											{$fields}
										FROM
											`{$table}`
										WHERE `{$field_name}` = :value
										LIMIT 0, 1");
			$stmt->bindValue(':value', $value, $this->_get_pdo_data_type( static::$columns[$field_name] ));
			$stmt->execute();

			if ($stmt->rowCount())
			{
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$this->_set_from_db($row);

				return TRUE;
			}
		}
		catch (PDOException $e)
		{
			$message = sprintf('Exception <%s> in file "%s" on line %s: %s', $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
			$this->log->write_log('error', $message);
		}

		return FALSE;
	}

   public function add_to_db()
	{
		// set table
		$table = static::TABLE_NAME;

		// set fields and parameters
		$columns = static::$columns;
		$fields = array();

		foreach ($columns as $column => $type)
			$fields["`{$column}`"] = ":{$column}";

		$insert_columns = implode(',', array_keys($fields) );
		$parameters = implode(',', $fields);

		$order_column = ( isset( static::$default_order_column ) ) ? static::$default_order_column : '';
		$should_order = ( ! isset( static::$no_autoorder ) ) || ( ! static::$no_autoorder );

		try
		{
			if ( $order_column && $should_order )
				$this->{$order_column} = $this->get_new_order();

			$stmt = $this->connection->prepare("INSERT INTO `{$table}`
											({$insert_columns})
										VALUES
											({$parameters})");

			foreach ($columns as $column => $type)
			{
				$parameter	= ":{$column}";

				$value		= self::_get_value_by_pdo_data_type($this->{$column}, $type);
				$data_type	= self::_get_pdo_data_type($type);

				$stmt->bindValue($parameter, $value, $data_type);
			}

			$stmt->execute();

			$stmt = $this->connection->query("SELECT LAST_INSERT_ID() AS `id`");
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->set_id( $row['id'] );

			return TRUE;
		}
		catch (PDOException $e)
		{
			$message = sprintf('Exception <%s> in file "%s" on line %s: %s', $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
			$this->log->write_log('error', $message);
		}

		return FALSE;
	}

   public function delete_from_db()
	{
		// set table
		$table = static::TABLE_NAME;

		// set id param
		$id_column = static::$id_column;
		$id_param = "`{$id_column}` = :{$id_column}";

		try
		{
			$stmt = $this->connection->prepare("DELETE FROM `{$table}`
										WHERE {$id_param}");
			$stmt->bindValue(":{$id_column}", $this->{$id_column}, PDO::PARAM_STR);

			$stmt->execute();

			return TRUE;
		}
		catch (PDOException $e)
		{
			$message = sprintf('Exception <%s> in file "%s" on line %s: %s', $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
			$this->log->write_log('error', $message);
		}

		return FALSE;
	}
   /*
   public function save(\Post $post)
   {
      // set table
      $table = static::TABLE_NAME;

      // If the ID is set, we're updating an existing record
      if (isset($post->id)) {
         return $this->update($post);
      }
      $stmt = $this->connection->prepare('
         INSERT INTO `{$table}`
             (title, photo_name, photo_ext, posted_on)
         VALUES
             (:title, :photo_name , :photo_ext, :posted_on)
      ');
      $stmt->bindParam(':title', $post->title);
      $stmt->bindParam(':photo_name', $post->photo_name);
      $stmt->bindParam(':photo_ext', $post->photo_ext);
      $stmt->bindParam(':posted_on', $post->posted_on);
      return $stmt->execute();
   }
   */
   public function update(\Post $post)
   {
      // set table
      $table = static::TABLE_NAME;

      if (!isset($post->id))
      {
         // We can't update a record unless it exists...
         throw new \LogicException(
             'Cannot update post that does not yet exist in the database.'
         );
      }
      $stmt = $this->connection->prepare('
         UPDATE `{$table}`
         SET title = :title,
             photo_name = :photo_name,
             photo_ext = :photo_ext,
             posted_on = :posted_on
         WHERE id = :id
      ');
      $stmt->bindParam(':title', $post->title);
      $stmt->bindParam(':photo_name', $post->photo_name);
      $stmt->bindParam(':photo_ext', $post->photo_ext);
      $stmt->bindParam(':posted_on', $post->posted_on);
      $stmt->bindParam(':id', $post->id);
      return $stmt->execute();
   }
}
