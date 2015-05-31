
<div id='dialog' title='Editor'>

</div>
<?php
require("/mysql.inc");
$mysqli = new mysqli("localhost", $user, $password, "fffponies");
//$con = mysql_connect("localhost", "root", "");

//mysql_select_db("fffponies");

$method = $_GET['m'];

if($method == "load")
{
	if(isset($_GET['pony']))
	{
		$pony = "where name='".$_GET['pony']."';";
	} else {
		$pony = ";";
	}
	$res = $mysqli->query("select * from ponies ".$pony);

	echo("<html><title>FFF Ponies and Thoroughbreds</title><script type='text/javascript' src='classie.js'></script>");
	echo("<script type='text/javascript' src='jquery-1.11.3.min.js'></script>");
	echo("<script type='text/javascript' src='jqueryui/jquery-ui.js'></script>");
	echo("<script type='text/javascript' src='customFunc.js'></script>");
//	echo("<script type='text/javascript'>setTimeout(function () { location.reload(true); }, 60000); </script>");	
	echo("<link rel='stylesheet' href='jqueryui/jquery-ui.css'>");
	echo("<style type='text/css'>");
	echo(".tg {border-collapse:collapse;border-spacing:0;border-style:solid;text-align:center}");
	echo("header {");
	echo("width: 100%;");
	echo("height:120px;");
	echo("overflow:hidden;");
	echo("position:fixed;");
	echo("top:0;");
	echo("left:0;");
	echo("z-index:999;");
	echo("background-color: #0099CC;");
	echo("-webkit-transition: height 0.3s;");
	echo("-moz-transition: height: 0.3s;");
	echo("-o-transition: height 0.3s;");
	echo("transition: height 1.3s;");
	echo("}");
	echo("header.smaller { height: 70px; }");
	echo("</style>");
	
	
	
	
	
	
	echo("<header><div>");
	echo("<h2>");
	echo("FFF Ponies and Thoroughbreds<center> </h2><h3><center>Click a name to edit<br/>Refresh this page often</center>");
	echo("<div id='menu' style='top:0;center:0;position:fixed'>");
	echo("<button id='newUser' onclick='newUser()'>New Pony</button><button id='find' onclick='findUser()'>Find Pony</button>");
	echo("<button id='loadAll' onclick='loadAll()'>Load All ponies</button>");
	echo("<button id='help' onclick='help()'>Help</button>");
	echo("<button id='feedback' onclick='feedback()'>Feedback</button>");
	echo("<button id='contribute' onclick=\"window.location='http://github.com/zontreck/ponies.zeenai.net'\">Contribute</button>");

	echo("</div>");
	echo("</h2></div></header>");
	
	echo("<body><br/><br/><Br/><br/><br/><br/>");
		
	$res->data_seek(0);
	while($row=$res->fetch_assoc())
	{
		//echo("<center><h2><a onclick=\"load('".$row['name']."')\">".$row['name']."</a></h2><br/>");
		echo("<center><br/>");
		echo("<table class='tg' border='3' cellpadding='1' width=100%>");

		echo("<tr><th> Name </th>");
		echo("<th> Date stall claimed / updated</th>");
		echo("<th> Available for training</th>");
		echo("<th> Date last trained</th>");
		echo("<th>Last trainer</th>");
		echo("<th>Notes</th>");
		echo("<th>Thoroughbred</th>");
		echo("<th>Timezone</th>");
		echo("<th>Owner</th>");
		echo("</tr>");
			
		echo("<tr>");
		echo("<td><a style='color:blue' onclick=\"load('".$row['ID']."')\"><h3 id='".$row['ID']."'>".$row['name']."</h3></a></td>");
		echo("<td id='".$row['ID']."-dateStallClaimed'>".$row['dateStallClaimed']."</td>");
		$avail  =  $row['availableForTraining'];
		if($avail==1)
			echo("<td><p style='color:green;font-weight:bold' id='".$row['ID']."-availableForTraining'>Yes</p></td>");
		else if($avail == 0)
			echo("<td><p style='color:red;font-weight:bold' id='".$row['ID']."-availableForTraining'>No</p></td>");
		else if($avail==2)
			echo("<td><p style='color:#009999;font-weight:bold' id='".$row['ID']."-availableForTraining'>With Owners Permission</p></td>");
		echo("<td id='".$row['ID']."-dateLastTrained'>".$row['dateLastTrained']."</td>");
		echo("<td id='".$row['ID']."-lastTrainer'>".$row['lastTrainer']."</td>");
		echo("<td id='".$row['ID']."-note'><a style='color:blue' onclick='notes(\"".$row['ID']."\")'>".$row['name']."'s Notes<input type='hidden' id='".$row['ID']."-notes' value=\"".$row['notes']."\"></a></td>");
		$TB = $row['TB'];
		if($TB==1)
			echo("<td><p style='color:green;font-weight:bold' id='".$row['ID']."-thoroughbred'>Yes</p></td>");
		else if($TB==0)
			echo("<td><p style='color:red;font-weight:bold' id='".$row['ID']."-thoroughbred'>No</p></td>");
		else if($TB==2)
			echo("<td><p style='color:#BA9500;font-weight:bold' id='".$row['ID']."-thoroughbred'>Needs Exam</p></td>");
		else if($TB==3)
			echo("<td><p style='color:#6666FF;font-weight:bold' id='".$row['ID']."-thoroughbred'>Working towards</p></td>");
		echo("<td id='".$row['ID']."-timezone'>".$row['timezone']."</td>");
		echo("<td id='".$row['ID']."-owner'>".$row['owner']."</td>");
		echo("</tr>");
			
		echo("</table>");
	}
} else if($_GET['m']=="edit")
{
	// Commit the values to the database.
	// We can only have a single value that's the same in `name`
	// Since name has to be unique, this is perfect for the update command
		
	echo("SecondLife name: <input type='text' name='name' id='name'>");
	echo("<br/><br/>Date stall claimed: <input type='text' name='dateStallClaimed' id='dateStallClaimed'>");
	echo("<br/><br/>Available for training: <select id='availableForTraining'><option value='Yes'>Yes</option><option value='No'>No</option><option id='With Owners Permission'>With Owners Permission</option></select>");
	echo("<br/><br/>Date last trained: <input type='text' name='dateLastTrained' id='dateLastTrained'>");
	echo("<br/><br/>Last trainer: <input type='text' name='lastTrainer' id='lastTrainer'>");
	echo("<br/><br/>Notes:<br/> <textarea rows='5' cols='25%' id='notes'></textarea>");
	echo("<br/><br/>Thoroughbred: <select id='thoroughbred'><option id='Yes'>Yes</option><option id='No'>No</option><option id='Needs Exam'>Needs Exam</option><option id='Working towards'>Working towards</option></select>");
	echo("<br/><br/>Timezone: <input type='text' name='timezone' id='timezone'>");
	echo("<br/><br/>Owner (If unowned, simply leave empty or set it to Unowned)<br/><input type='text' id='owner' name='owner'>");

	
} else if($_GET['m']=="save")
{
	echo("Please wait... Saving");
	$name = $_GET['newname'];
	if($name=="null")
	{
		die("You must use a valid SecondLife name");
	} else if($name=="")
	{
		die("You must not use a blank name");
	}
	$dateStallClaimed=$_GET['dateStallClaimed'];
	$availableForTraining=$_GET['availableForTraining'];
	if($availableForTraining=="Yes")
	{
		$availableForTraining=1;
	} else if($availableForTraining=="No"){
		$availableForTraining=0;
	} else if($availableForTraining=="With Owners Permission")
	{
		$availableForTraining=2;
	}
	$dateLastTrained=$_GET['dateLastTrained'];
	$lastTrainer=$_GET['lastTrainer'];
	$notes=$_GET['notes'];
	$TB=$_GET['tb'];
	if($TB == "Yes")
	{
		$TB=1;
	} else if($TB=="No"){
		$TB=0;
	} else if($TB=="Needs Exam")
	{
//		echo("<script type='text/javascript'>alert('".$TB."');</script>");
		$TB=2;
	} else if($TB == "Working towards")
	{
		$TB=3;
	}
	$timezone=$_GET['timezone'];
	$owner=$_GET['owner'];
	$res = $mysqli->query("update ponies set dateStallClaimed='".$dateStallClaimed."', availableForTraining=".$availableForTraining.", dateLastTrained='".$dateLastTrained."', lastTrainer='".$lastTrainer."', notes='".$notes."', TB=".$TB.", timezone='".$timezone."', owner='".$owner."', name='".$name."' where ID=".$_GET['id'].";");
	
	echo(mysqli_error($mysqli));
	echo("<br/><br/>Update sent to server.");
	echo("<br/><pre>Please click cancel or the X. Thank you");
	
	echo("<br/><script type='text/javascript'>location.reload(true);</script>");
} else if($_GET['m']=="newUser")
{
	$name = $_GET['name'];
	$mysqli->query("insert into ponies (name) values('".$name."');");
	echo("<p style='color:green' id='creatorD'>Pony added</p>");
	
	
	
} else if($_GET['m'] =="delete")
{
	$res=$mysqli->query("select * from admin where password='".$_GET['password']."';");
	echo("<script type='text/javascript'>");
	while($row=$res->fetch_assoc())
	{
		echo("alert(\"Thank you ".$row['adminName']." deleting now...\");");
		$mysqli->query("delete from ponies where name='".$_GET['pony']."';");
	}
	echo("</script>");
} else if($_GET['m']=="feedback")
{
	$mysqli->query("insert into feedback values('".$_GET['name']."', '".$_GET['data']."');");
	echo("Sent");
} else {

	echo("<html><title>FFF Ponies and Thoroughbreds</title><script type='text/javascript' src='classie.js'></script>");
	echo("<script type='text/javascript' src='jquery-1.11.3.min.js'></script>");
	echo("<script type='text/javascript' src='jqueryui/jquery-ui.js'></script>");
	echo("<script type='text/javascript' src='customFunc.js'></script>");
//	echo("<script type='text/javascript'>setTimeout(function () { location.reload(true); }, 60000); </script>");	
	echo("<link rel='stylesheet' href='jqueryui/jquery-ui.css'>");
	echo("<style type='text/css'>");
	echo(".tg {border-collapse:collapse;border-spacing:0;border-style:solid;text-align:center}");
	echo("header {");
	echo("width: 100%;");
	echo("height:120px;");
	echo("overflow:hidden;");
	echo("position:fixed;");
	echo("top:0;");
	echo("left:0;");
	echo("z-index:999;");
	echo("background-color: #0099CC;");
	echo("-webkit-transition: height 0.3s;");
	echo("-moz-transition: height: 0.3s;");
	echo("-o-transition: height 0.3s;");
	echo("transition: height 1.3s;");
	echo("}");
	echo("header.smaller { height: 70px; }");
	echo("</style>");
	
	
	
	
	
	
	echo("<header><div>");
	echo("<h2>");
	echo("FFF Ponies and Thoroughbreds<center> </h2><h3><center>Click a name to edit<br/>Refresh this page often</center>");
	echo("<div id='menu' style='top:0;center:0;position:fixed'>");
	echo("<button id='newUser' onclick='newUser()'>New Pony</button><button id='find' onclick='findUser()'>Find Pony</button>");
	echo("<button id='loadAll' onclick='loadAll()'>Load All ponies</button>");
	echo("<button if='help' onclick='help()'>Help</button>");
	echo("</div>");
	echo("</h2></div></header>");
}

$mysqli->close();


?>

