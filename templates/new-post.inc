<section class="admin-section">
  <h1>Hello there.</h1>
  {loop}
    <div class="error">{error}</div>
  {/loop}
  <form action="/new-post" class="admin-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="new_post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="" placeholder="not necessary">
    <label for="photo">Photo</label>
    <input type="file" name="photo" id="photo">
    <input type="submit" name="post" value="Post it">
  </form>
  <a class="link" href="/">Home</a>
  <a class="link" href="/list-posts">List of faks</a>
  <a class="link" href="/logout">Log out</a>
</section>
