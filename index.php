<?php
  include_once('connection.php');
  include_once('classes/post.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Ziro Faks</title>
    <link rel="stylesheet" href="css/style.css" media="screen" charset="utf-8">
  </head>
  <body>
    <section>
      <h1>All of my <span>faks</span>!</h1>
      <h2>just look at them, <a href="og/">or add some..</a></h2>
      <?php
        $post = new Post();
        $posts = $post->getPosts();
        if ( $posts && isset($posts) ):
          foreach ( $posts as $post ):
      ?>
      <article>
        <?php if ( $post->title != "" ) : ?>
        <h1><?php echo $post->title; ?></h1>
        <?php endif; ?>
        <img src="photos/<?php echo $post->photo_name; ?>"/>
      </article>
      <?php
          endforeach;
        endif;
      ?>
      <p>© 2015.</p>
    </section>
  </body>
</html>
