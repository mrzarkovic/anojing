<section class="admin-section">
  <h1>All fucks listed</h1>
  <div class="clearfix"></div>
  <a class="link" href="/">Home</a>
  <a class="link" href="/new-post">Add a new post</a>
  <a class="link" href="/logout">Log out</a>
  <p>Click on a post to delete it.</p>
  <div class="clearfix"></div>
  {loop}
    <article>
      <a href="/delete-post/{id}" data-action="delete-post">
        <img src="/photos/{photo_name}"/>
      </a>
    </article>
  {/loop}

</section>
