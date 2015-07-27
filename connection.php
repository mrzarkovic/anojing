<?php

//conection
function connect_to_db()
{
  $link = mysqli_connect("localhost","root","pass","anojing") or die("Error " . mysqli_error($link));
  return $link;
}

?>
