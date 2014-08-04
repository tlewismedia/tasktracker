<?php session_start();
ini_set('display_errors', 'On');

//destroy session if logged out
if(isset($_GET['logout'])){
	session_unset();
	session_destroy();
}
//check if in a seesion, redirect to tasktracker.php
if(isset($_SESSION['loginUsername'])){
	header("Location: tasktracker.php");
	exit; //prevents executing rest of page 
}	

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Create a new Account</title>
	<script src="js/jquery-2.0.3.js"></script>
	<script src="js/parsley.min.js"></script>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/styles.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,400italic' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="bodywrap">
<div class="container promptbox">
	<h1>CREATE A NEW ACCOUNT</h1>
	<form id="accountform" data-validate="parsley">
		<table>
			<tr>
				<td><label for="username">Username: </label></td>
				<td><input type="text" id="username" name="username" 
				data-rangelength="[4,15]" required data-trigger="focusin focusout" placeholder="4 to 15 characters"></td>
			</tr>
			<tr>
				<td><label for="password">Password: </label></td>
				<td><input type="password" id="password" name="password" required 
				data-rangelength="[8,15]" data-trigger="focusin focusout" placeholder="8 to 15 characters"></td>
				
			</tr>
			<tr>
				<td></td>
				<td><input id="createAccBtn" type="button" value="create account" onclick=checkvalid()></td>
			</tr>
		</table>
		<div id="errorBox"></div>
		<div id="successBox"></div>
	</form>

</div>
</div>

	<script>

	function checkvalid(){
		if(!$('#accountform').parsley('isValid')){
				$("#errorBox").append("<br>Please check your input.<br>");
		}else{
			createAccount();
		}
		return false;
	}

	function createAccount() {
		console.log('in createAccount');
		// CLIENT VALIDATION
		$('#errorBox').text("");	//clear error box

		var userName = $("input#username").val();  
		 
		var passWord = $("input#password").val();  
		
		//AJAX CALL
		//returns 0:server error, 1:user added, 2: username exists
		$.ajax({
			type: "POST",
			url: "new_user.php",
			data: {
				username: userName,
				password: passWord
			}
		}).done(function(msg) {
			console.log(msg);
			switch (msg) {
				case "0":
				$('#errorBox').text("server error, try again later");
				break;
				case "1":
				 $('#successBox').append("account created!" + 
				 	" <a id=\"newLoginBtn\" href=\"#\">see your tasks</a>");

				break;
				case "2":
				$('#errorBox').text("That username already exists.");
				break;
				default:
				$('#errorBox').text("server error, try again later");
				break;
			} //end switch
		}).fail(function(){
			$('#errorBox').text("server error, try again later");
		}); //end ajax
		

		$( "#successBox" ).on( "click", "#newLoginBtn", function() {
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

		});

		return false;

	} //end create account

$(function() {  //same as doc ready
	
	$('.error').hide(); 
	
}); //end doc ready
</script>

</body>
</html>