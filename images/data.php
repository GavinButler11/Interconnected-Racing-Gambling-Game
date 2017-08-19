<?php
session_start();

$DB = mysqli_connect("localhost", "root", "", "RacingApp");

$Action = $_GET["ACTION"];

switch($Action)
{
	case "LOGIN":
		$UserName = $_GET["LOGINUSER"];
		$Password = $_GET["LOGINPASSWORD"];
		
		$SQL = "SELECT * FROM users WHERE username='$UserName' AND password='$Password'";
		$Results = $DB->query($SQL);
		if($Results->num_rows == 0)
			$Return = array(false,"Invalid login details");
		else
		{
			$Row = $Results->fetch_assoc();
			$_SESSION["username"] = $Row['username'];
			$Return = array(true);
		}		
		
		break;
		
	case "REGISTER":
		$UserName = $_GET["REGISTERUSER"];
		$Password = $_GET["REGISTERPASSWORD"];
		
		// check if already there
		$SQL = "SELECT * FROM users WHERE username='$UserName'";
		$Results = $DB->query($SQL);
		if($Results->num_rows > 0)
			$Return = array(false,"User name already registered");
		else
		{
			$SQL = "INSERT users(username, password) VALUES ('$UserName', '$Password')";
			$DB->query($SQL);
			$Return = array(true);
			$_SESSION["username"] = $Row['username'];
		}
		break;
		
	case "RUNNING":
		// check whether half a second has elapsed
		$SQL = "SELECT *,CURTIME()-CurrentTime AS elapsed FROM raceheader";
		$Results = $DB->query($SQL);
		$Row = $Results->fetch_assoc();
		if($Row['elapsed'] > 0.5)
		{
			// set new values
			for($i=1; $i<6; $i++)
			{
				$Runner = "Runner".strval($i);
				$SQL = "UPDATE racedetail SET PercentCompleted=PercentCompleted+".rand(1,3)." WHERE Runner='".$Row[$Runner]."'";
				$DB->query($SQL);
			}
		}
		// get current values
		$SQL = "SELECT * FROM racedetail";
		$Results = $DB->query($SQL);
		$Return = array();
		while(($Row = $Results->fetch_assoc()))
		{
			$Return[] = $Row['PercentCompleted'];
		}
		break;
		
	case "START":
		$UserName = $_SESSION["username"];
		$SQL = "SELECT * FROM users WHERE username='$UserName'";
		$Results = $DB->query($SQL);
		if($Results->num_rows == 0)
			$Return = array(false,"No users ready");
		else
		{
			//$Row = $Results->fetch_assoc();
			$Return = array(true);
		}		
		
		break;
}

die(json_encode($Return));

?>