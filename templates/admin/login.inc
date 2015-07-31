<section class="admin-section">
  <h1>Credentials</h1>
  {loop}
    <div class="error">{error}</div>
  {/loop}

  <form class="admin-form" action="/login" method="post">
    <input type="hidden" name="action" value="login">
    <label for="username">The name</label>
    <input type="text" name="username" id="username" value="">
    <label for="password">The secret word</label>
    <input type="password" name="password" id="password" value="">
    <input type="submit" name="submit" value="Enter">
  </form>
</section>
