<?php
	session_start(); 
	$_SESSION['USERNAME']="";
?>

<!DOCTYPE html>
	<head>
		<title> Project </title>
		
		<meta charset="utf-8">
		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/start/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
		
		<script type="text/javascript" src="main.js"></script>
		<link type="text/css" href="main.css" rel="stylesheet"/>
	</head>
	<body>
		<div id = "top">
			<div id = "logo">
				<img src = "images/logo2.png"/>
			</div>
			
			<div id = "title">
					<h1>Virtual Horse Racing</h1>
			</div>
			
			<div id="login">
				<button id="btnLogin">Login</button>
				<button id="btnRegister">Register</button>
			</div>

			<div id="logout">
				<button id="btnLogout">Logout</button>
			</div>
			
			<div id="instructions">
				<button id="btnInstructions">Instructions</button>
			</div>
			
			<div id="myAccount">
				<button id="btnMyAccount">My Account</button>
			</div>
			
			<div id="leaderboard">
				<button id="btnLeaderboard">Leaderboard</button>
			</div>
		
			<button id="btnStart">PLAY</button>
		</div>
		
		<table class="dialog" id="LoginDetails" border=0>
				<tr><td>User Name:</td><td><input type="text" id="loginusername" size="30"></td></tr>
				<tr><td>Password: </td><td><input type="text" id="loginpassword" size="30"></td></tr>
				<tr><td class="error" colspan='2'>???</td></tr>
		</table>
		
		<table class="dialog" id="InstructionDetails" border=0>
				<tr><td>Weve given you  free €50 amount to bet on our game.</td><tr><br>
				<tr><td>When you press play you load in to a race with 5 other users. Each horse randomly works its way to the finish.</tr></td><br>
				<tr><td>The winner recieves €25 which is added to their account.The losers each lose €5 from their accounts as each time you play you get €5 at 5/1 odds.</td><tr>
		</table>
		
		
		<table class="dialog" id="RegisterDetails" border=0>
				<tr><td>User Name:</td><td><input type="text" id="registerusername" size="30"></td></tr>
				<tr><td>Password: </td><td><input type="text" id="registerpassword" size="30"></td></tr>
				<tr><td class="error" colspan='2'>???</td></tr>
		</table>
		
		<table class="dialog" id="LogoutDetails" border=0>
			<tr><td>Are you sure?</td></tr>
		</table>
		
		<table class="dialog" id="AddFundsDetails" border=0>
			<tr><td>50 euro will be taken from your bank account and stored on your game account.</td></tr>
			<tr><td>Are you sure you want to do this?</td></tr>
		</table>
		
		<div class="dialog" id="StartRace">
			<p>Press play button to start the race</p>
		</div>
		
		<div class="dialog" id="WaitingDetails">
			<p>Waiting for other players...</p><br>
			<p>You have bet €5 on a random 1 of 5 horses at 5/1 odds</p>
		</div>
		
		<div id="MainMenuDetails" title="WINNER">
			<table border=0>
				<tr><td id="winner"></td></tr>
			</table>
		</div>
		
		<div class = "fullCourse">
			<table id="names">
				<tr><td><p id = "username-1">test1</p></td></tr>
				<tr><td><p id = "username-2">test2</p></td></tr>
				<tr><td><p id = "username-3">test3</p></td></tr>
				<tr><td><p id = "username-4">test4</p></td></tr>
				<tr><td><p id = "username-5">test5</p></td></tr>
			</table>
			<table id="course" border=1 align="center">
				<tr><td><img class="runner" id="runner-1" src="images/runner-1.png"></td></tr>
				<tr><td><img class="runner" id="runner-2" src="images/runner-2.png"></td></tr>
				<tr><td><img class="runner" id="runner-3" src="images/runner-3.png"></td></tr>
				<tr><td><img class="runner" id="runner-4" src="images/runner-4.png"></td></tr>
				<tr><td><img class="runner" id="runner-5" src="images/runner-5.png"></td></tr>
			</table>
		</div>
		
		<table id="MyAccount">
			<tr><td><p>Username: </p><p id = "username">test1</p></td></tr>
			<tr><td><p>Password: </p><p id = "password">test2</p></td></tr>
			<tr><td><p>Money: </p><p id = "money">test3</p></td></tr>
		</table>
			
		<table id="leaderboardDetails">
			<tr><td>Username</td><td>Money</td></tr><tr><td id = "username1" width="120">test1</td><td id = "money1">test1</td></tr>
			<tr><td id = "username2">test1</td><td id = "money2">test1</td></tr>
			<tr><td id = "username3">test1</td><td id = "money3">test1</td></tr>
			<tr><td id = "username4">test1</td><td id = "money4">test1</td></tr>
			<tr><td id = "username5">test1</td><td id = "money5">test1</td></tr>
		</table>
		
	</body>
</html>