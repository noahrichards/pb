<?php
require_once('includes/globals.inc');

if(isset($_GET['projid']))
{
	$projid = $_GET['projid'];
	$projinfo = getProjInfoFromId($projid);
	$name = $projinfo[0];
	$owner = $projinfo[1];

	escapes('projid', $projid);
	escapes('name', $name);
	escapes('owner', $owner);

	$isNews = isNews($projid);
	emitTop('Peanut Butter -> Posts -> Save Post for: '.$name, '/pb/');

	if($name == '')
	{
		print('<h3>Project not found.</h3>');
		emitBottom();
		die();
	}
}
else
{
	errAndDie('Project not specified.');
}

if($userType == VISITOR)
{
	errAndDie('Sorry, visitors may not post.');
}

if($isNews && ($userType != ADMIN && $userType != SITEADMIN))
{
	errAndDie('Sorry, only admins/siteadmins may post news events.');
}

if(!isset($_POST['text']) || !isset($_POST['title']))
{
	errAndDie('Missing data.');
}


mysqlSetup();

escapes('text',$_POST['text']);
escapes('title',$_POST['title']);
escapes('userName', $userName);


if(isset($_POST['modified']))
{
	if(!isset($_POST['postid']))
		errAndDie('Post ID not specified.');

	$mpostid = mysql_escape_string($_POST['postid']);

	$sqlquery = "UPDATE `pb_blog` SET `modified` = NOW(), "
		.	"`title` = '$mtitle', `text` = '$mtext' WHERE "
		.	"`id` = '$mpostid' AND `user` = '$muserName'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

}
else
{
	$sqlquery = "INSERT INTO `pb_blog` (`projid`,`added`,`modified`,`user`,`title`,"
		.	"`text`) VALUES ('$mprojid',NOW(),NOW(),'$muserName','$mtitle','$mtext')";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

}

if(mysql_affected_rows())
	print("<h3>Posted.</h3>");
else
	print("<h3>Post unsuccessful.</h3>");


if(!$isNews)
	print("<p><a href=\"projectInfo.php?projid=$urlprojid\">View Project Page</a></p>");
else
	print("<p><a href=\"./\">Peanut Butter Main</a></p>");



emitBottom();
?>