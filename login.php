<?php session_start();
ini_set('display_errors', 'On');

$message = "";

//destroy session if logged out
if(isset($_GET['logout'])){
	session_unset();
	session_destroy();
	echo "<span class=\"mess\">You have been logged out.</span>";
}

//check if in a seesion, redirect to tasktracker.php
if(isset($_SESSION['loginUsername'])){
	header("Location: tasktracker.php");
	exit; //prevents executing rest of page 
}

// login in response
if (isset($_SESSION["message"])){
 $message .= $_SESSION["message"];
 unset($_SESSION["message"]);
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
   <title>Login to Task Tracker</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/styles.css">
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,400italic' rel='stylesheet' type='text/css'>
	<script src="js/jquery-2.0.3.min.js"></script>
	<script src="js/parsley.min.js"></script>
</head>
<body>
<div class="bodywrap">
<?php 
	echo "<span class=\"mess\">" . $message . "</span>";	
?>
<div class="container promptbox">
	<h1>TASK TRACKER LOGIN</h1>
	<form id="login-form" data-validate="parsley">
		<table>
			<tr>
				<td>
					<label for="username">Username:</label> 
				</td>
				<td>
					<input type="text" id="username" name="loginUsername" data-required="true" data-trigger="change">
				</td>
			</tr>
			<tr>
				<td>
					<label for="password">Password:</label>
				</td>
				<td>
					<input type="password" id="password" name="loginPassword" data-required="true" data-trigger="change">
				</td>
			</tr>
			<tr>
				<td> </td>
				<td>
					<input id="loginBtn" type="submit" value="login" onclick=checkvalid()>
				</td>
			</tr>
		</table>
		</form>
		<a id="newActBtn" href="create_account.php"> create new account </a>
	<div id="errorBox"></div>
	<div id="results"></div>
</div>
<script>
$(document).ready(function() {
	
	$( "#loginBtn" ).on( "click", function() {
		
		$('#errorBox').text("");	//clear error box

		var userName = $("input#username").val();  
		  
		var passWord = $("input#password").val();  
		
		//AJAX CALL
		$.ajax({
			type: "POST",
			url: "logincheck.php",
			data: {
				username : userName,
				password : passWord
			}
			
		})
		.done(function(data) {
			console.log(data);
			if (data === '1'){
				location = 'tasktracker.php';
			}else {
				$('#errorBox').text("Incorrect username or password, please try again.");
			}

		})
		.fail(function(){
			$('#errorBox').text("server error, try again later");
		});

		
		return false;

	});

}); //end doc ready


	function checkvalid(){
		if(!$('#login-form').parsley('isValid')){
			$("#results").append("<br>Check your input and try again.<br>");
		}
	}



</script>
</div>
</body>
</html>