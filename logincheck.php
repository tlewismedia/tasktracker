<?php 
ini_set('display_errors', 'On');


require 'authentication.inc';
require 'db.inc';

$loginUsername = $_POST['username'];
$loginPassword = $_POST['password'];
  

session_start();

//log POST and SESSION superglobals to file
$post = print_r($_POST, true);
$sess = print_r($_SESSION, true);
$log = 'POST: ' . $post . 'SESSION: ' . $sess;
file_put_contents('logincheck_log.txt', $log);



// Authenticate the user
if (authenticateUser($mysqli, $loginUsername, $loginPassword))
{

  // Register the loginUsername
  $_SESSION["loginUsername"] = $loginUsername;

  // Register the IP address that started this session
  $_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];

  // Relocate back to the first page of the application
  echo 1;
  exit;
}
else
{
  $log = $log . "\n authentication failed \n";
  file_put_contents('logincheck_log2.txt', $log);
  // The authentication failed: setup a logout message
 echo 0;

  exit;
}
?>
