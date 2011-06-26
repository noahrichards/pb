<?php
require_once('includes/globals.inc');

if(isset($_GET['name']))
{
	$name = $_GET['name'];
	emitTop('Peanut Butter -> Delete User: '.$name, '/pb/');
}
else
{
	emitTop('Peanut Butter -> Delete User [ERROR]', $_SERVER['PHP_SELF']);
	print('<h3>Required information not specified.</h3>');
	emitBottom();
	die();
}

if($userType != SITEADMIN)
{
	print('<h3>Sorry, only siteadmins may delete users.</h3>');
	emitBottom();
	die();
}


if(isset($_GET['confirmed']))
{
	mysqlSetup();

	$mname = mysql_escape_string($name);

	$sqlquery = "DELETE FROM `pb_users` WHERE `name` = '$mname'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	print('<h3>User Deleted.</h3>');

	print("<p><a href=\"accountManagement.php\">Back to Account Management</a></p>");
}
else
{
	$urlname = urlencode($name);

	print("<p><a href=\"".$_SERVER['PHP_SELF']."?name=$urlname&amp;confirmed=1\">Delete user '$name'.</a></p>");
}

emitBottom();
?>