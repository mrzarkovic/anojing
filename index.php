<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Anojing es fak</title>
    <link rel="stylesheet" href="/css/style.css" media="screen" charset="utf-8">
  </head>
  <body>
    <section>
      <h1>Fak of, <span>nobadi</span> kerz!</h1>
      <?php
        include_once('classes/site.php');
        $site = new Site;
        $posts = $site->get_posts();
        if ($posts && isset($posts)):
          foreach ( $posts as $post ):
      ?>
      <article>
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
