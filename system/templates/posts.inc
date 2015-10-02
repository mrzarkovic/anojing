<section>
  <h1>All of my <span>posts</span>!</h1>
  <h2>just look at them, <a href="/login">or add some..</a></h2>
  {% for post in posts %}
  <article>
    <h1>{post->title}</h1>
    <img src="photos/{post->photo_name}"/>
  </article>
  {% endfor %}
  <p>Copyright © 2015 Who Gives a Fak.</p>
</section>
