<?php
  require_once('classes/core.php');

  // Exception handling
  set_exception_handler('exception_handler');
  function exception_handler( $exception )
  {
    echo $exception->getMessage();
  }

  $core = new Core();

  //$route = new Route();
  $core->route->add('/', 'showPosts@Post');
  $core->route->add('/new-post', 'addPost@Post');
  $core->route->add('/list-posts', 'listPosts@Post');
  $core->route->add('/login', 'showLogin@Admin');
  $core->route->add('/logout', 'logout@Admin');
  $core->route->add('/hello', function(){
    echo "Hello.";
  });

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ziro Faks</title>
    <link rel="stylesheet" href="css/style.css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="css/admin-style.css" media="screen" charset="utf-8">
  </head>
  <body>
    <?php $core->route->submit(); ?>
  </body>
</html>
