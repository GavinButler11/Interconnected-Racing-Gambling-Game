
$(document).ready(function()
{
	$.ajaxSetup({cache:false, async:false});

	// set all html buttons to be handles by jquery themed button handler
	$("button").button();
	
	// button click event handlers
	$("#btnLogin").click(function(){Login()});
	$("#btnRegister").click(function(){Register()});
	$("#btnStart").click(function(){CheckStartRace()});
	$("#btnLogout").click(function(){Logout()});
	$("#btnInstructions").click(function(){Instructions()});
	$("#btnMyAccount").click(function(){MyAccount()});
	$("#btnLeaderboard").click(function(){Leaderboard()});

	// check login status
	Status();	
});


function Status()
{
	$.getJSON("data.php", {"ACTION":"STATUS"},
		function(ReturnData)
		{
			var LoggedIn = ReturnData[0];
			var RaceStarted = ReturnData[1];

			if(LoggedIn)
			{
				$("#login").hide();
				$("#instructions").show();
				$("#myAccount").show();
				$("#leaderboard").show();
				$("#logout").show();
				$("#btnStart").show();
				$("#WaitingDetails").hide();
			}
			else
			{
				$("#logout").hide();
				$("#btnStart").hide();
				$("#login").show();
				$("#instructions").hide();
				$("#myAccount").hide();
				$("#leaderboard").hide();
			}
		}
	);
}

function Login()
{
	// jquery dialog handler
	$("#LoginDetails").dialog(
	{
		title: "Enter username and password details",
		height:275,
		width:510,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Submit", click:function(){CheckLogin()}},
			{text: "Cancel", click:function(){$(this).dialog("close")}}, 
		]
	});
	$(".error").hide();
}

function Logout()
{
	// jquery dialog handler
	$("#LogoutDetails").dialog(
	{
		title: "Logout",
		height:200,
		width:350,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Yes", click:function(){LogoutToMenu()}},
			{text: "No", click:function(){$(this).dialog("close")}}, 
		]
	});
}

function MainMenu()
{
	// jquery dialog handler
	$("#MainMenuDetails").dialog(
	{
		title: "WINNER",
		height:300,
		width:500,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Back to Main Menu", click:function(){BackToMenu()}},
					
		]
	});
}



function BackToMenu()
{
	$("#MainMenuDetails").dialog("close")
	$("#course").hide();
	$("#names").hide();
	$("#top").show();
	Status();
}

function LogoutToMenu()
{
	$.getJSON("data.php", {"ACTION":"LOGOUT"});
	$("#LogoutDetails").dialog("close");
	Status();
}

function CheckLogin()
{
	var LoginUserName = $("#loginusername").val();
	var LoginPassword = $("#loginpassword").val();
	
	$(".error").hide();
	
	// call php script to validate the username
	$.getJSON("data.php", {"ACTION":"LOGIN", "LOGINUSER":LoginUserName, "LOGINPASSWORD":LoginPassword},
			function(ReturnData)
			{
				var valid = ReturnData[0];
				if(valid)
				{
					$("#LoginDetails").dialog("close");
					Status();
				}
				else
				{
					// error message
					$(".error").html(ReturnData[1]);
					$(".error").show();
				}
			}
	);
			
}

function Register()
{
	// jquery dialog handler
	$("#RegisterDetails").dialog(
	{
		title: "Enter a username and password",
		height:275,
		width:510,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Submit", click:function(){CheckRegister()}},
			{text: "Cancel", click:function(){$(this).dialog("close")}}, 
		]
	});
	$(".error").hide();
}

function CheckRegister()
{
	var RegisterUserName = $("#registerusername").val();
	var RegisterPassword = $("#registerpassword").val();
	
	$(".error").hide();
	
	// call php script to validate the username
	$.getJSON("data.php", {"ACTION":"REGISTER", "REGISTERUSER":RegisterUserName, "REGISTERPASSWORD":RegisterPassword},
			function(ReturnData)
			{
				var valid = ReturnData[0];
				if(valid)
				{
					$("#RegisterDetails").dialog("close");
					alert("You have been registered. Now, login")
				}
				else
				{
					// error message
					$(".error").html(ReturnData[1]);
					$(".error").show();
				}
			}
		);
			
}



function CheckStartRace()
{
	// add logged in user to race
	$.getJSON("data.php", {"ACTION":"ADDME"});

	$("#WaitingDetails").dialog(
	{
		height:300,
		width:500,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode"
	});
		
	WaitForUsers();
	
	function WaitForUsers()
	{
		$.getJSON("data.php", {"ACTION":"START"},
		function(ReturnData)
		{
			var GoodToGo = ReturnData[0];
			if(GoodToGo)
			{
				$("#WaitingDetails").dialog("close");
				$("#top").hide();
				$("#leaderboardDetails").hide();		
				$("#MyAccount").hide();
				$("#course").show();
				$("#names").show();
				Race();
			}
			else
			{
				setTimeout(function() {WaitForUsers()}, 2000);			
			}
		});
	}
}


function Race()
{
	var ImageSize = 5; // 5%
	var Winner = 0;
	
	// get list of runner names
	GetUsers();
	
	
	setTimeout(function(){ MoveRunner() }, 0);

	function MoveRunner()
	{
		var Runner, MoveDistance;

		$.getJSON("data.php", {"ACTION":"RUNNING"},
			function(ReturnData)
			{
				Winner = ReturnData[0];
				if(Winner==0)
				{
					for(Runner=1; Runner<6; Runner++)
					{
						p = ReturnData[Runner]-ImageSize;
						if(p<0)
							p=0;
						$("#runner-"+Runner).animate({left: p+"%"}, {duration:0});
					}

					setTimeout(function(){ MoveRunner() }, 160);
				}
				else
				{
					$("#winner").html("The winner of the race was runner: "+Winner)
					MainMenu();
				}
			}
		);
	}

}

function GetUsers()
{	
	$.getJSON("data.php", {"ACTION":"GETUSERS"},
			function(ReturnData)
			{
				var valid = ReturnData[0];
				if(valid)
				{
					$("#username-1").html(ReturnData[1]);
					$("#username-2").html(ReturnData[2]);
					$("#username-3").html(ReturnData[3]);
					$("#username-4").html(ReturnData[4]);
					$("#username-5").html(ReturnData[5]);
					
				}
				else
				{
					// error message
					$(".error").html(ReturnData[1]);
					$(".error").show();
				}
			}
		);
}

function Instructions()
{
	// jquery dialog handler
	$("#InstructionDetails").dialog(
	{
		title: "Instructions",
		height:400,
		width:650,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Ok", click:function(){$(this).dialog("close")}} 
		]
	});
}

function MyAccount()
{
	GetAccount();
	// jquery dialog handler
	$("#MyAccount").dialog(
	{
		title: "My Account",
		height:400,
		width:300,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Ok", click:function(){$(this).dialog("close")}},
			{text: "Add Funds", click:function(){AddFundsDialog(); $(this).dialog("close")}} 
		]
	});
}

function GetAccount()
{	
	$.getJSON("data.php", {"ACTION":"GETACCOUNT"},
			function(ReturnData)
			{
				var valid = ReturnData[0];
				if(valid)
				{
					$("#username").html(ReturnData[1]);
					$("#password").html(ReturnData[2]);
					$("#money").html(ReturnData[3]);			
				}
				else
				{
					// error message
					$(".error").html(ReturnData[1]);
					$(".error").show();
				}
			}
		);
}

function AddFundsDialog()
{
	// jquery dialog handler
	$("#AddFundsDetails").dialog(
	{
		title: "Add Funds",
		height:400,
		width:300,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Yes", click:function(){AddFunds(); $(this).dialog("close")}},
			{text: "No", click:function(){$(this).dialog("close")}}

		]
	});
}

function AddFunds()
{	
	$.getJSON("data.php", {"ACTION":"ADDFUNDS"},
			function(ReturnData)
			{
				var valid = ReturnData[0];
				if(valid)
				{
					$("#username").html(ReturnData[1]);
					$("#password").html(ReturnData[2]);
					$("#money").html(ReturnData[3]);			
				}
				else
				{
					// error message
					$(".error").html(ReturnData[1]);
					$(".error").show();
				}
			}
		);
}

function Leaderboard()
{
	GetLeaderboard();
	// jquery dialog handler
	$("#leaderboardDetails").dialog(
	{
		title: "Leaderboard",
		height:400,
		width:300,
		modal:true,
		resizable:false,
		show:"fade",
		hide:"explode",
		buttons:
		[
			{text: "Ok", click:function(){$(this).dialog("close")}} 
		]
	});
}

function GetLeaderboard()
{	
	$.getJSON("data.php", {"ACTION":"GETLEADERBOARD"},
			function(ReturnData)
			{
				var valid = ReturnData[0];
				if(valid)
				{
					
					for(User=1; User<ReturnData.length; User++)
					{
						LeaderDetails = ReturnData[User];
						$("#username"+User).html(LeaderDetails[0]);
						$("#money"+User).html(LeaderDetails[1]);	
					}									
				}
				else
				{
					// error message
					$(".error").html(ReturnData[1]);
					$(".error").show();
				}
			}
		);
}

