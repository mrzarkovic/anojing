<?php

  //session_start();

  //require_once('connection.php');
  //require_once('engines/template.php');
  //require_once('engines/route.php');
  //require_once('controllers/core.php');
  //require_once('controllers/posts.php');
  //require_once('controllers/admin.php');
  //require_once('controllers/test.php');

  define('BASEPATH', str_replace("\\", "/", "../system"));

  // Include configuration
  require_once(BASEPATH. '/config/config.php');

  $app = new Core();

  $app->route->add('/', 'show_posts@Posts');
  $app->route->add('/new-post', 'add_post@Posts');
  $app->route->add('/list-posts', 'list_posts@Posts');
  $app->route->add('/delete-post/(:num)', 'delete_post@Posts');
  $app->route->add('/login', 'show_login@Admin');
  $app->route->add('/logout', 'logout@Admin');
  $app->route->add('/test', 'Test');
  $app->route->add('/hello', function(){
    echo "Hello there.";
  });

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ziro Faks</title>
    <link rel="stylesheet" href="/css/style.css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="/css/admin-style.css" media="screen" charset="utf-8">
    <script src="/js/jquery.min.js" charset="utf-8"></script>
    <script type="text/javascript">
      $( document ).ready(function() {
        $("[data-action='delete-post']").click(function(e) {
          if (!confirm('Are you really, really sure you want to trash dis?')) {
              e.preventDefault();
          }
        });
      });
    </script>
  </head>
  <body>
      <?php $app->route->run(); ?>
   </body>
</html>
