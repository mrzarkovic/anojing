<?php
  include_once('../classes/admin.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Original Gangsta</title>
    <link rel="stylesheet" href="/css/style.css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="/css/admin-style.css" media="screen" charset="utf-8">
  </head>
  <body>
    <section class="admin-section">
      <?php
        $admin = new Admin();
        if ( $admin->loggedIn() )
        {
          if ( $admin->newPostAdded() )
          {
            echo "Post added.";
          }
      ?>
          <h1>Hello there.</h1>
          <form action="/og/index.php" class="admin-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="new-post">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="">
            <label for="photo">Photo:</label>
            <input type="file" name="photo" id="photo">
            <input type="submit" name="post" value="Post it">
          </form>
          <a href="../index.php">Home</a>
          <a href="index.php?log_out=true">Log out</a>
      <?php
        }
        else
        {
      ?>
      <h1>Credentials</h1>
      <form class="admin-form" action="/og/index.php" method="post">
        <input type="hidden" name="action" value="login">
        <label for="username">The name:</label>
        <input type="text" name="username" id="username" value="">
        <label for="password">The secret word:</label>
        <input type="password" name="password" id="password" value="">
        <input type="submit" name="submit" value="Shoot">
      </form>
      <?php
        }
      ?>
    </section>
  </body>
</html>
