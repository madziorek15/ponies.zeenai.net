var username;
var newname;
var dateStallClaimed;
var availableForTraining;
var dateLastTrained;
var lastTrainer;
var notes;
var TB;
var timezone;
var owner;
var ID;

function load(name)
{
	a("load("+name+");");
	ID=name;
	username=$("#"+name).text();
	a(ID);
	a(username);
	
	
	
	dateStallClaimed=$("#"+ID+"-dateStallClaimed").text();
	availableForTraining=$("#"+ID+"-availableForTraining").text();
	dateLastTrained=$("#"+ID+"-dateLastTrained").text();
	lastTrainer=$("#"+ID+"-lastTrainer").text();
	notes=$("#"+ID+"-notes").val();
	TB=$("#"+ID+"-thoroughbred").text();
	timezone=$("#"+ID+"-timezone").text();
	owner=$("#"+ID+"-owner").text();
	
	
	
	
	$('#dialog').dialog({
		autoOpen: true,
		height:500,
		width:500,
		modal:true,
		title:'Editor - '+username,
		buttons:
		{
			"Save": save,
			Cancel: function()
			{
				$(this).dialog("close");
			},
			"Delete User": deleteUser
		}
	});
	
	$('#dialog').load("dat.php?m=edit&pony="+encodeURIComponent(username), populateFields);
	
}

function save()
{
	newname=$("#name").val();
	dateStallClaimed=$("#dateStallClaimed").val();
	availableForTraining=$("#availableForTraining").val();
	dateLastTrained=$("#dateLastTrained").val();
	lastTrainer=$("#lastTrainer").val();
	notes=$("#notes").val();
	TB=$("#thoroughbred").val();
	timezone=$("#timezone").val();
	owner=$("#owner").val();

	/*
	a(username);
	a(newname);
	a(dateStallClaimed);
	a(availableForTraining);
	a(dateLastTrained);
	a(lastTrainer);
	a(notes);
	a(TB);
	a(timezone);
	a(owner);

	*/
	$("#dialog").load("dat.php?m=save&name="+b(username)+"&dateStallClaimed="+b(dateStallClaimed)+"&availableForTraining="+b(availableForTraining)+"&dateLastTrained="+b(dateLastTrained)+"&lastTrainer="+b(lastTrainer)+"&notes="+b(notes)+"&tb="+b(TB)+"&timezone="+b(timezone)+"&owner="+b(owner)+"&newname="+b(newname)+"&id="+ID);
	
}

function init()
{
	window.addEventListener('scroll',function(e)
	{
		var distanceY=window.pageYOffset || document.documentElement.scrollTop,
		shrinkOn=300, header = document.querySelector('header');
		if(distanceY>shrinkOn)
		{
			classie.add(header,'smaller');
		} else {
			if(classie.has(header,'smaller')){
				classie.remove(header,'smaller');
			}
		} 
	});
}	

function a(a)
{
	console.debug(a);
}

function b(a)
{
	return encodeURIComponent(a);
}

function populateFields()
{
	//alert("Populating..");
	a(username);
	a(dateStallClaimed);
	a(availableForTraining);
	a(lastTrainer);
	a(notes);
	a(TB);
	a(timezone);
	a(owner);
	
	$("#name").val(username);
	$("#dateStallClaimed").val(dateStallClaimed);
	$("#availableForTraining").val(availableForTraining);
	$("#dateLastTrained").val(dateLastTrained);
	$("#lastTrainer").val(lastTrainer);
	$("#notes").val(notes);
	$("#thoroughbred").val(TB);
	$("#timezone").val(timezone);
	$("#owner").val(owner);
}

function newUser()
{
	var name = prompt("Enter your username.");
	a(name);
	while(name=="")
	{
		name=prompt("You must enter a valid name");
	}
	while(name=="null")
	{
		name=prompt("You must enter a valid name");
	}
	
	$('#dialog').dialog({
		autoOpen: true,
		height:500,
		width:500,
		modal:true,
		title:'New User - '+name,
		buttons:
		{
			"Save": save,
			Cancel: function()
			{
				alert("Please let the site owner know to remove you from the database.\n\ntiff589 Resident");
				location.reload(true);
				$(this).dialog("close");
			}
		}
	});
	$("#dialog").html("<div id='progressbar'></div><br/>Preparing to create user...");
	mkprog();
	setTimeout(function () {
		$("#dialog").html($("#dialog").html()+"<br/>Creating user...");
		setTimeout(function (){
			$("#dialog").html($("#dialog").html()+"<br/><div id='creator'></div>Checking server");
			$("#creator").load("dat.php?m=newUser&name="+b(name));
			setTimeout(function () {
				if($("#creatorD").html() == "Pony added")
				{
					$("#dialog").html($("#dialog").html()+"<br/>Added pony...<br/>Done.");
					//alert("Click your name in the table when the page refreshes. It'll open a new window so you can edit.");
					$("#dialog").dialog("close");
					load(name);
					location.reload(true);
				} else {
					$("#dialog").html($("#dialog").html()+"<br/>ERROR: User could not be created");
					setTimeout(function() {
						location.reload(true);
					}, 4000);
				}
			}, 2000);
		}, 2000);
	}, 2000);
}
function mkprog()
{
	$("#progressbar").progressbar({"value":false});
}

function deleteUser()
{
	var password = prompt("Enter admin password");
	$("#dialog").load("dat.php?m=delete&password="+password+"&pony="+username);
}

function findUser()
{
	var name = prompt("Enter the pony's name you'd like to find.");
	a(name);
	
	window.location="dat.php?m=load&pony="+name;
}

function loadAll()
{
	window.location="dat.php?m=load";
}

function help()
{
	
	$('#dialog').dialog({
		autoOpen: true,
		height:500,
		width:500,
		modal:true,
		title:'Help',
		buttons:
		{
			Cancel: function()
			{
				$(this).dialog("close");
			}
		}
	});
	
	$("#dialog").html("<body bgcolor='#5fedec'><br/>To make a table for your name, click New Pony.<br/>To Search for a pony by name, click on find pony. Or you can use your browser's CTRL+F windows, or COMMAND+F on a mac.<br/>");
	$("#dialog").html($("#dialog").html()+"<br/>");
}

function notes(name)
{
	a(name);
	
	$("#dialog").dialog({
		autoOpen:true,
		height:500,
		width:500,
		modal:true,
		title:username+"'s Notes",
		buttons:
			{
				"Close": function() { 
					$("#dialog").dialog("close");
				}
			}
	});

	$("#dialog").html($("#"+name+"-notes").val());
}

function feedback()
{
	var CurUser = prompt("Enter your SL Name");
	var feedback = prompt("Enter the feedback");
	$("#dialog").dialog({
		autoOpen:true,
		height:500,
		width:500,
		modal:true,
		title:"Uploading Feedback",
		buttons:
		{
			Cancel:
				function(){
					alert("You cannot stop the upload");
				}
		}
	});
	$("#dialog").load("dat.php?m=feedback&name="+CurName+"&feedback="+feedback);
	
}	
window.onload=init();

