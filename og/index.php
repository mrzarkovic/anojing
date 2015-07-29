<?php
  include_once('../connection.php');
  include_once('../classes/post.php');
  include_once('../classes/admin.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Original Gangsta</title>
    <link rel="stylesheet" href="../css/style.css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="../css/admin-style.css" media="screen" charset="utf-8">
  </head>
  <script src="../js/jquery.min.js" charset="utf-8"></script>
  <script type="text/javascript">
    $( document ).ready(function() {
      $("[data-action='delete-post']").click(function(e) {
        if (!confirm('Are you really, really sure you want to trash dis?')) {
            e.preventDefault();
        }
      });
    });
  </script>
  <body>
    <section class="admin-section">
      <?php
        $admin = new Admin();
        if ( $admin->loggedIn() )
        {
          if ( $admin->getPage() == "list_posts")
          {
          ?>
            <h1>All fucks listed</h1>
            <div class="clearfix"></div>
            <a class="link" href="../index.php">Home</a>
            <a class="link" href="index.php">Add a new post</a>
            <a class="link" href="index.php?action=log_out">Log out</a>
            <p>Click on a post to delete it.</p>
            <div class="clearfix"></div>
          <?php
            if ( $notifications = $admin->getNotifications() )
            {
              foreach ( $notifications as $notification )
              {
                echo "<div class='note'>" . $notification . "</div>";
              }
            }
            elseif ( $errors = $admin->getErrors() )
            {
              foreach ( $errors as $error )
              {
                echo "<div class='error'>" . $error . "</div>";
              }
            }
            $posts = new Post();
            $posts = $posts->getPosts();
            foreach ( $posts as $post ) :
            ?>
            <article>
              <a href="?page=list_posts&action=delete_post&id=<?php echo $post->id; ?>" data-action="delete-post">
                <img src="../photos/<?php echo $post->photo_name; ?>"/>
              </a>
            </article>
            <?php
            endforeach;
          }
          else
          {
            if ( $notifications = $admin->getNotifications() )
            {
              foreach ( $notifications as $notification )
              {
                echo "<div class='note'>" . $notification . "</div>";
              }
            }
            elseif ( $errors = $admin->getErrors() )
            {
              foreach ( $errors as $error )
              {
                echo "<div class='error'>" . $error . "</div>";
              }
            }
      ?>
          <h1>Hello there.</h1>
          <form action="index.php" class="admin-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="new_post">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="" placeholder="not necessary">
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo">
            <input type="submit" name="post" value="Post it">
          </form>
          <a class="link" href="../index.php">Home</a>
          <a class="link" href="index.php?page=list_posts">List of faks</a>
          <a class="link" href="index.php?action=log_out">Log out</a>
      <?php
          }
        }
        else
        {
      ?>
      <h1>Credentials</h1>
      <?php
        if ( $errors = $admin->getErrors() )
        {
          foreach ( $errors as $error )
          {
            echo "<div class='error'>" . $error . "</div>";
          }
        }
      ?>
      <form class="admin-form" action="index.php" method="post">
        <input type="hidden" name="action" value="login">
        <label for="username">The name</label>
        <input type="text" name="username" id="username" value="">
        <label for="password">The secret word</label>
        <input type="password" name="password" id="password" value="">
        <input type="submit" name="submit" value="Enter">
      </form>
      <a class="link" href="../index.php">Go home</a>
      <?php
        }
      ?>
    </section>
  </body>
</html>
