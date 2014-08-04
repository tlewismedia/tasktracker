<?php session_start();
ini_set('display_errors', 'On');
require 'db.inc';

//log POST and SESSION superglobals to file
$post = print_r($_POST, true);
$sess = print_r($_SESSION, true);
$log = 'POST: ' . $post . 'SESSION: ' . $sess;
file_put_contents('log.txt', $log);

if (!empty($_POST["tid"])) {

	$id = $_POST["tid"];

	//DELETE

	//prepare statement
	if ( !($stmt = $mysqli->prepare("DELETE FROM task WHERE id=?"))){
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//bind parameters
	if (!$stmt->bind_param("i", $id)) {
		echo "Binding parameters failed: (" . $stmt->errno . ")" . $stmt->error;
	}
	//execute statement
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
	}

	$stmt->close();

	echo 1;

}else{
	echo 0;
}

?>