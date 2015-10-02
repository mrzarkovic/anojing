<?php

  define('BASEPATH', str_replace("\\", "/", "../system"));

  // Include configuration
  require_once(BASEPATH . '/config/config.php');

  // Start the app
  $app = new Core();

  // Include routes
  require_once(BASEPATH . '/config/routes.php');

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
      <?php $app->run(); ?>
   </body>
</html>
