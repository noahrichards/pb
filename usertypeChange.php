<?php
require_once('includes/globals.inc');

if(isset($_GET['name']) && isset($_GET['type']))
{
	$name = $_GET['name'];
	$type = $_GET['type'];
	emitTop('Peanut Butter -> Change User: '.$name, '/pb/');
}
else
{
	emitTop('Peanut Butter -> Change User [ERROR]', $_SERVER['PHP_SELF']);
	print('<h3>Required information not specified.</h3>');
	emitBottom();
	die();
}

if($userType != SITEADMIN)
{
	print('<h3>Sorry, only siteadmins may change user privileges.</h3>');
	emitBottom();
	die();
}


if($type == 'promote')
{
	mysqlSetup();

	$mname = mysql_escape_string($name);

	$sqlquery = "UPDATE `pb_users` SET `category` = '".ADMIN
		.	"' WHERE `name` = '$mname'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	print('<h3>User promoted.</h3>');

	print("<p><a href=\"accountManagement.php\">Back to Account Management</a></p>");
	
	mysql_close();
}
elseif($type == 'demote')
{
	mysqlSetup();
	
	$mname = mysql_escape_string($name);

	$sqlquery = "UPDATE `pb_users` SET `category` = '".NORMAL
		.	"' WHERE `name` = '$mname'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	print('<h3>User demoted.</h3>');

	print("<p><a href=\"accountManagement.php\">Back to Account Management</a></p>");
		
	mysql_close();
}
else
{
	print('<h3>Incorrect type of userchange specified.</h3>');
	emitBottom();
	die();
}	

emitBottom();
?>