<?php

function authenticateUser($mysqli, $username, $password){
  // Test the username and password parameters
  if (!isset($username) || !isset($password))
    return false;

  if (!($stmt = $mysqli->prepare("SELECT COUNT(password) FROM user WHERE username= ? AND password =  ? "))){
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if (!$stmt->bind_param("ss", $username, $password)) {
    echo "Binding parameters failed: (" . $stmt->errno . ")" . $stmt->error;
  }
  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
  }
  if (!$stmt->bind_result($res)){
    echo "Error binding: (" . $stmt->errno . ")" . $stmt->error;
  }
  else {
    $stmt->fetch(); //result in $res
  }
  $stmt->close();

  //one result means there's a match
  if ($res != 1){
    return false;
  }
  else{

    //get user id
    if (!($stmt = $mysqli->prepare("SELECT id FROM user WHERE username= ? AND password =  ? "))){
      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("ss", $username, $password)) {
      echo "Binding parameters failed: (" . $stmt->errno . ")" . $stmt->error;
    }
    if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
    }
    if (!$stmt->bind_result($res)){
      echo "Error binding: (" . $stmt->errno . ")" . $stmt->error;
    }
    else {
    $stmt->fetch(); //result in $res
    }
    $stmt->close();

    $_SESSION['uid'] = $res;


    return true;
  }
}

// Connects to a session and checks that the user has
// authenticated and that the remote IP address matches
// the address used to create the session.
function sessionAuthenticate(){
// Check if the user hasn't logged in
  if (!isset($_SESSION["loginUsername"]))
  {
  // The request does not identify a session
    $_SESSION["message"] = "Please log in";

    header("Location: login.php");
    exit;
  }

// Check if the request is from a different IP address to previously
  if (!isset($_SESSION["loginIP"]) || 
   ($_SESSION["loginIP"] != $_SERVER["REMOTE_ADDR"]))
  {

    $_SESSION["message"] = "Please log in";

    header("Location: tasktracker.php");
    exit;
  }
}

?>
