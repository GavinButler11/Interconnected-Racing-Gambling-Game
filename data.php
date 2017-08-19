<?php
session_start();

$DB = mysqli_connect("localhost", "root", "", "RacingApp");

$Action = $_GET["ACTION"];

switch($Action)
{
	case "STATUS":
		// return logged & race running
		if(isset($_SESSION['USERNAME']) && strlen($_SESSION['USERNAME'])>0)
			$Return = array(true,false);
		else
			$Return = array(false,false);
		break;
		
	case "RESET":
		// clear out the racedetail
			$SQL = "DELETE FROM racedetail";
			$DB->query($SQL);
				
			// blank out the runners from raceheader
			$SQL = "UPDATE raceheader SET StartTime=CURTIME(), CurrentTime=CURTIME(), Runner1='', Runner2='', Runner3='', Runner4='', Runner5=''";
			$DB->query($SQL);
			
			$SQL = "INSERT INTO racedetail ( Runner ) VALUES
			('gav'), ('fred'), ('steve')";
			$DB->query($SQL);
			
			$SQL = "UPDATE raceheader SET Runner1='gav', Runner2='fred', Runner3='steve'";
			$DB->query($SQL);
			
			$Return = array(true);
			
			break;

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
			$_SESSION["USERNAME"] = $Row['username'];
			$Return = array(true);
		}		
		break;

	case "LOGOUT":
		$_SESSION['USERNAME']="";
		break;
		
	case "REGISTER":
		$UserName = $_GET["REGISTERUSER"];
		$Password = $_GET["REGISTERPASSWORD"];
		
		$Error = "";
		
		// validation
		if(strlen($UserName)>10)
			$Error = "Username cannot be longer than 10 characters";
		
		if($Error=="" && strlen($Password)<3)
			$Error = "Password cannot be less than 2 characters";
		
		if($Error=="")
		{
			// check if already there
			$SQL = "SELECT * FROM users WHERE username='$UserName'";
			$Results = $DB->query($SQL);
			if($Results->num_rows > 0)
				$Error = "User name already registered";
		}
		
		if($Error=="")
		{
			$SQL = "INSERT INTO users(username, password, money) VALUES ('$UserName', '$Password', 50)";
			$DB->query($SQL);
			$Return = array(true);
			$_SESSION["USERNAME"] = $UserName;
		}
		else
		{
			$Return = array(false,$Error);
		}
		break;
		
	case "RUNNING":
		// check whether half a second has elapsed
		$SQL = "SELECT *,CURTIME()-CurrentTime AS elapsed FROM raceheader";
		$Results = $DB->query($SQL);
		$Row = $Results->fetch_assoc();
		if($Row['elapsed'] > 0.25)
		{
			// set new values for runners
			for($i=1; $i<6; $i++)
			{
				$Runner = "Runner".strval($i);
				$Rand = (rand()/getrandmax())*3;
				$SQL = "UPDATE racedetail SET PercentCompleted=PercentCompleted+".$Rand." WHERE Runner='".$Row[$Runner]."'";
				$DB->query($SQL);
			}
			// set new current time
			$SQL = "UPDATE raceheader SET CurrentTime=CURTIME()"; 
			$DB->query($SQL);

		}
		// get current values
		$SQL = "SELECT * FROM racedetail";
		$Results = $DB->query($SQL);
		$Return = array(0);
		$Runner = 0;
		while(($Row = $Results->fetch_assoc()))
		{
			$Runner++;
			$P = (float)$Row['PercentCompleted'];
			$Return[] = $P;
			
			// 100% completed = race over
			if($P >= 100)
			{
				if(strlen($_SESSION['WINNER'])==0)
				{
					// get winner
					$SQL = "SELECT * FROM raceheader";
					$Results = $DB->query($SQL);
					$Row = $Results->fetch_assoc();
					$Col = "Runner".strval($Runner);
					$Winner = $Row[$Col];
				
					// update scores
					$SQL = "UPDATE users SET money=money+25 WHERE username='$Winner'";
					$DB->query($SQL);
					$SQL = "UPDATE users SET money=money-5 WHERE username<>'$Winner'";
					$DB->query($SQL);
				
					$_SESSION['WINNER'] = $Winner;
				}
				$Return[0] = $_SESSION['WINNER'];
			}
		}
		break;
		
	case "ADDME":
		$UserName = $_SESSION["USERNAME"];
		$NextRunner = NextRunner();
		$SQL = "INSERT into racedetail SET Runner ='$UserName'";
		$DB->query($SQL);
		$SQL = "UPDATE raceheader SET Runner$NextRunner='$UserName' WHERE ID = 1";
		$DB->query($SQL);
		$Return = array(true);
		break;
		
	case "START":
		// clear down old races
		$_SESSION['WINNER']='';
		$SQL = "SELECT MAX(PercentCompleted) AS PC FROM racedetail";
		$DB->query($SQL);
		$Results = $DB->query($SQL);
		$Row = $Results->fetch_assoc();
		if($Row["PC"]>=100)
		{
			$UserName = $_SESSION["USERNAME"];

			// clear out the racedetail
			$DB->query("DELETE FROM racedetail");
			$SQL = "INSERT into racedetail SET Runner ='$UserName'";
			$DB->query($SQL);
				
			// blank out the runners from raceheader
			$SQL = "UPDATE raceheader SET StartTime=CURTIME(), CurrentTime=CURTIME(), Runner1='$UserName', Runner2='', Runner3='', Runner4='', Runner5=''";
			$DB->query($SQL);

			$Return = array(false);			
		}
		else
		{	
			if(NextRunner() == 0)
				$Return = array(true);
			else
				$Return = array(false,"waiting on all runners");
		}
		break;
		
	case "GETUSERS":			
		$SQL = "SELECT * FROM raceheader";
		$Results = $DB->query($SQL);
		if($Results->num_rows == 0)
			$Return = array(false,"no users");
		else
		{
			$Row = $Results->fetch_assoc();
			$Return = array(true,$Row["Runner1"], $Row["Runner2"], $Row["Runner3"], $Row["Runner4"], $Row["Runner5"]);
		}
		break;
		
	case "GETACCOUNT":
		$UserName = $_SESSION["USERNAME"];	
		$SQL = "SELECT * FROM users WHERE username = '$UserName'";
		$Results = $DB->query($SQL);
		if($Results->num_rows == 0)
			$Return = array(false,"no users");
		else
		{
			$Row = $Results->fetch_assoc();
			$Return = array(true,$Row["username"], $Row["password"], $Row["money"]);
		}
		break;
		
	case "GETLEADERBOARD":
		$SQL = "SELECT username, money FROM `users` ORDER BY ( money ) DESC";
		$Results = $DB->query($SQL);
		if($Results->num_rows == 0)
			$Return = array(false,"no users");
		else
		{
			$Return = array(true);
			$Top=0;
			while(($Row = $Results->fetch_assoc()) && ++$Top < 6)
			{
				$Return[] = array($Row["username"], $Row["money"]);
			}
		}
		break;

	case "GETWINNER":			
		$SQL = "SELECT Runner FROM racedetail WHERE PercentCompleted > 100";
		$Results = $DB->query($SQL);
		if($Results->num_rows == 0)
			$Return = array(false,"no users");
		else
		{
			$Row = $Results->fetch_assoc();
			$Return = array(true,$Row["Runner"]);
		}
		break;
		
	case "ADDFUNDS":
		$UserName = $_SESSION["USERNAME"];
		$SQL = "UPDATE users SET money=money+50 WHERE username='$UserName'";
		$DB->query($SQL);
		break;
}

die(json_encode($Return));

function NextRunner()
{
	global $DB;
	
	$SQL = "SELECT * FROM raceheader";
	$Results = $DB->query($SQL);
	$Row = $Results->fetch_assoc();
	$NextRunner=0;
	$ThisRunner=1;
	while($NextRunner==0 && $ThisRunner<6)
	{
		$Runner = "Runner".strval($ThisRunner);
		if(strlen($Row[$Runner])==0)
			$NextRunner = $ThisRunner;
		else
			$ThisRunner++;
	}
	return($NextRunner);
}
?>