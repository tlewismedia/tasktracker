<?php session_start();
ini_set('display_errors', 'On');
//log POST and SESSION superglobals to file
$post = print_r($_POST, true);
$sess = print_r($_SESSION, true);
$log = 'POST: ' . $post . 'SESSION: ' . $sess;
file_put_contents('log2.txt', $log);

require "authentication.inc";
 

// Connect to an authenticated session or relocate to logout.php
sessionAuthenticate();

require 'db.inc';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
   <title>Task Tracker</title>
	<script src="js/jquery-2.0.3.min.js"></script>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/styles.css">
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,400italic' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="bodywrap">

<?php


/*****************************************
	shows log out box
	par $userName  the username to display
	*****************************************/
function showLogOut($userName){
	
	$logOutHTML = "<div class='logOut box'>Welcome, ".$userName
		."<br><a href=\"login.php?logout=1\">log out</a><br></div>";
	echo $logOutHTML;
	return;	
}

showLogOut($_SESSION['loginUsername']);

//if(isset($_SESSION['loginUserName'])){
?>

<div class="container promptbox" id="listBox">
	<h2 class="listTitle">Task Tracker</h2>


<?php

	$uid = $_SESSION['uid'];

	//INSERT into table
	if (!$stmt = $mysqli->prepare("insert into task(uid, descript) 
			values(?,?)")){
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	
	
	
	$descript = array_key_exists("newTask", $_REQUEST) ? $_REQUEST["newTask"] : '';

	if ( $descript != ''){
		//"is" stands for int, string
		if (!$stmt->bind_param("is", $uid, $descript)) {
			echo "Binding parameters failed: (" . $stmt->errno . ")" . $stmt->error;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
		}
	}
	$stmt->close();

	//clear post variable
	$_POST["newTask"] = '';


	//display table
	if (!($stmt = $mysqli->prepare("SELECT id, uid, isDone, descript FROM task WHERE uid=?"))){
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	//Bind parameters for markers
	if (!$stmt->bind_param("i", $_SESSION['uid'])) {
		echo "Binding parameters failed: (" . $stmt->errno . ")" . $stmt->error;
	}
	//Runs the statement
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
	}
	// One bound parameter for each thing selected in the same order
	if (!$stmt->bind_result($idR, $uidR, $idDoneR, $descR)){
		echo "Error binding: (" . $stmt->errno . ")" . $stmt->error;
	}
	else {
		//buffers data
		$stmt->store_result();
		
		echo "<ul id='taskList'>\n";
		//Gets rows from buffered data
		while ($stmt->fetch()){
			//will be able to refer to rows with id '[id]task'
			echo "<li><button class='remBtn' id='".$idR."'>x</button><span class='taskField'>" . $descR ."</span></li>\n" ;
		}
		echo "</ul>\n";

		//free mem allocated for buffer
		$stmt->free_result();

		//deallocate statement handle
		$stmt->close();
	}



?>

		</div> <!--end listBox-->
		<div class="centerBox">
			<form method="post">
				New task: <input name="newTask" class="required" maxlength="16" >  
				<input type="submit" value="OK">
			</form>
			<div id="errorBox"></div>
		</div>


<script>
$(document).ready(function() {
	if ($('#taskList').children().length == 0){
	 		removeBox();
	}

	

	$( "#taskList button" ).on( "click", function() {
		var id = this.id; //the id of the task to remove (matches button id)
		var btnSel = '#'+id;
		console.log('delete clicked');
		$.ajax({
			type: "POST",
			url: "removeTask.php",
			data: {
				tid: id
			}
		})

			.done(function(msg) {
				console.info( msg );
				//do the client side removing
				if (msg){
					//console.log(this);
					$(btnSel).parent().remove();
					if ($('#taskList').children().length == 0){
 	 					removeBox();
					}
				}else{
					$('#errorBox').text("server error, try again later"); //perhaps redundant
				}

			})
			.fail(function(){
				$('#errorBox').text("server error, try again later");
			});


	});

}); //end doc ready

function removeBox(){

	$('#taskList').remove();
	$('.listTitle').text("No Tasks");
}
</script>

</div>
</body>
</html>
