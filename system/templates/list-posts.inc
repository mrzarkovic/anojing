<section class="admin-section">
  <h1>All fucks listed</h1>
  <div class="clearfix"></div>
  <a class="link" href="/">Home</a>
  <a class="link" href="/new-post">Add a new post</a>
  <a class="link" href="/logout">Log out</a>
  <p>Click on a post to delete it.</p>
  <div class="clearfix"></div>
  {% for post in posts %}
    <article>
      <a href="/delete-post/{post->id}" data-action="delete-post">
        <img src="/photos/{post->photo_name}"/>
      </a>
    </article>
  {% endfor %}

</section>
