<?php

class Entries
{
  public function getEntries()
  {
    $entries = array();
    $link = connect_to_db();
    $query = "SELECT * FROM entries" or die("Error in the consult.." . mysqli_error($link));
    $result = $link->query( $query );

    if (!$result)
      return 0;

    while( $row = mysqli_fetch_object( $result ) )
    {
      $entries[] = $row;
    }

    return $entries;
  }
}
