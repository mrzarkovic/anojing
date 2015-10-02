<?php

   $app->add_route('/', 'show_posts@Posts');
   $app->add_route('/new-post', 'add_post@Posts');
   $app->add_route('/edit-posts', 'edit_posts@Posts');
   $app->add_route('/delete-post/(:num)', 'delete_post@Posts');
   $app->add_route('/login', 'show_login@Admin');
   $app->add_route('/logout', 'logout@Admin');
   $app->add_route('/test', 'Test');
   $app->add_route('/hello', function(){
     echo "Hello there.";
   });
