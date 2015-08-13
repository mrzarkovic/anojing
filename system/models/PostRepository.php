<?php

namespace Repository;

use \PDO;

class PostRepository
{
     const TABLE_NAME = "posts";
    private $connection;

    public function __construct(PDO $connection = null)
    {
        $this->connection = $connection;
        if ($this->connection === null) {
            $this->connection = new PDO(
                    'mysql:host=localhost;dbname=anojing',
                    'root',
                    ''
                );
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }
    }
    public function find($id)
    {
      // set table
		$table = static::TABLE_NAME;

      $stmt = $this->connection->prepare('
         SELECT "Post", posts.*
          FROM `{$table}`
          WHERE id = :id
      ');
      $stmt->bindParam(':id', $id);
      $stmt->execute();

      // Set the fetchmode to populate an instance of 'User'
      // This enables us to use the following:
      //     $user = $repository->find(1234);
      //     echo $user->firstname;
      $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
      return $stmt->fetch();
    }
    public function find_all()
    {
      // set table
		$table = static::TABLE_NAME;

      $stmt = $this->connection->prepare("
         SELECT * FROM `{$table}`
      ");
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');

      // fetchAll() will do the same as above, but we'll have an array. ie:
      //    $users = $repository->findAll();
      //    echo $users[0]->firstname;
      return $stmt->fetchAll();
    }
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
