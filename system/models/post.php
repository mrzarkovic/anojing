<?php

class Post extends Repository
{
   const TABLE_NAME = 'posts';

   static public $id_column = "id";

   static public $columns = array(
      'title'        => 'string',
      'photo_name'   => 'string',
      'photo_ext'    => 'string',
      'posted_on'    => 'datetime',
   );
   /*
   public $id;
   public $title;
   public $photo_name;
   public $photo_ext;
   public $posted_on;
   */

   public function __construct( $row = array() )
   {
     parent::__construct( $row );
   }

   public function get_photo_name()
   {
    echo $this->photo_name;
   }
}
