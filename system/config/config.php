<?php

session_start();

// Include DAL
//require_once(dirname(dirname(__FILE__)) . '/models/DAL.php');
// Include DALQueryResult
//require_once(dirname(dirname(__FILE__)) . '/models/DALQueryResult.php');
// Include Helpers
require_once(dirname(dirname(__FILE__)) . '/helpers/log.php');
// Include Model Repository
require_once(dirname(dirname(__FILE__)) . '/models/repository.php');
// Include Post             Model
require_once(dirname(dirname(__FILE__)) . '/models/post.php');
// Include User             Model
require_once(dirname(dirname(__FILE__)) . '/models/user.php');
// Include Templating   Engine
require_once(dirname(dirname(__FILE__)) . '/engines/template.php');
// Include Routing      Engine
require_once(dirname(dirname(__FILE__)) . '/engines/route.php');
// Include Core   Controller
require_once(dirname(dirname(__FILE__)) . '/controllers/core.php');
// Include Posts  Controller
require_once(dirname(dirname(__FILE__)) . '/controllers/posts.php');
// Include Admin  Controller
require_once(dirname(dirname(__FILE__)) . '/controllers/admin.php');

// Database
define ( 'DB_HOST', 'localhost' );
define ( 'DB_USER', 'root' );
define ( 'DB_PASSWORD', '' );
define ( 'DB_DB', 'anojing' );

// Exception handling
set_exception_handler('exception_handler');
function exception_handler( $exception )
{
  echo $exception->getMessage();
}

?>
