<h1>Hello there.</h1>
<form action="/" class="admin-form" method="post" enctype="multipart/form-data">
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
