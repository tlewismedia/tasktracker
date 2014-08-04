<?php 
ini_set('display_errors', 'On');

require 'db.inc';
$username = '';


if ($mysqli->connect_errno) {
	echo 0;
}
if (!($stmt = $mysqli->prepare("SELECT username FROM user WHERE username=?"))){
	echo 0;
}
if (!$stmt->bind_param("s", $_POST['username'])) {
	echo 0;
}
if (!$stmt->execute()) {
	echo 0;
}
if (!$stmt->bind_result($username)){
	echo 0;
}
else {
	$stmt->fetch();
}
$stmt->close();


if(strtoupper($_POST['username']) == strtoupper($username)){
	echo 2; 
}
else{
	//create account
	if (!($stmt = $mysqli->prepare("INSERT INTO user(username, password)
		VALUES( ?, ?)"))){
		echo 0;
	}
	if (!$stmt->bind_param("ss", $_POST['username'], $_POST['password'])) {
		echo 0;
	}
	if (!$stmt->execute()) {
		echo 0;
	}else{
		echo 1;
		$stmt->close();
	}
}

?>