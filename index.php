
<?php

function connect_to_db()
{
  $link = mysqli_connect("localhost","root","pass","anojing") or die("Error " . mysqli_error($link));
  return $link;
}

include_once('classes/entries.php');

$entries = new Entries();
$entries = $entries->getEntries();

var_dump($entries);

// Error reporting is turned up to 11 for the purposes of this demo
ini_set("display_errors",1);
ERROR_REPORTING(E_ALL);

// Exception handling
set_exception_handler('exception_handler');
function exception_handler( $exception )
{
  echo $exception->getMessage();
}

// Load the Template class
require_once 'classes/template.php';

// Create a new instance of the Template class
$template = new Template;

// Set the testing template file location
$template->template_file = 'template-test.inc';

$template->entries[] = (object) array( 'test' => 'This was inserted using template tags!' );
$extra = (object) array(
  'header' => (object) array( 'headerStuff' => 'Some extra content.' ),
  'footer' => (object) array( 'footerStuff' => 'More extra content.' )
);

// Output the template markup
echo $template->generate_markup($extra);
