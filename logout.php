<?php
  
  session_start();

  $message = "";

  if (isset($_SESSION["loginUsername"]))
    $message .= "Thanks {$_SESSION["loginUsername"]} for
                 using the Application.";

  // logout message
  if (isset($_SESSION["message"]))
  {
    $message .= $_SESSION["message"];
    unset($_SESSION["message"]);
  }

  // Destroy the session.
  session_destroy();

  echo $message;

?>
