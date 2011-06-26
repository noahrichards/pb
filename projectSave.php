<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> Save Project', '/pb/');

if(!isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['keywords'])
 || !isset($_POST['status']) || !isset($_POST['progress'])
 || !isset($_POST['priority']))
{
	errAndDie('Missing data.');
}

$name = $_POST['name'];

if(projectExists($name) && !isset($_POST['modified']))
{
	errAndDie('Project already exists.');
}

if($userType != ADMIN && $userType != SITEADMIN)
{
	errAndDie('Sorry, only admins/siteadmins may save projects.');
}


$mName = mysql_escape_string($name);
$urlname = urlencode($name);
$mOwner = mysql_escape_string($userName);
$mDescription = mysql_escape_string($_POST['description']);
$mKeywords = mysql_escape_string($_POST['keywords']);
$mStatus = mysql_escape_string($_POST['status']);
$mProgress = mysql_escape_string($_POST['progress']);
$mPriority = mysql_escape_string($_POST['priority']);

$mNotes = (!isset($_POST['notes'])) ? '' : mysql_escape_string($_POST['notes']);
$mDeadline = (!isset($_POST['deadline'])) ? '' : mysql_escape_string($_POST['deadline']);

if($mPriority < 1 || $mPriority > 10)
{
	if($mPriority > 10)
		$mPriority = 10;
	elseif($mPriority < 1)
		$mPriority = 1;
	print("<h3>'priority' has been changed to: $mPriority</h3>");
}

if($mProgress < 0 || $mProgress > 100)
{
	if($mProgress > 100)
		$mProgress = 100;
	elseif($mProgress < 0)
		$mProgress = 0;
	print("<h3>'progress' has been changed to: $mProgress</h3>");
}


mysqlSetup();

if(isset($_POST['modified']))
{
	if(!isset($_POST['projid']))
	{
		errAndDie('Project ID not specified.');
	}

	$projid = $_POST['projid'];
	
	$projinfo = getProjInfoFromId($projid);
	$name = $projinfo[0];
	$owner = $projinfo[1];
	
	if($userType == ADMIN && $owner != $userName)
	{
		errAndDie('Sorry, admins may only edit their own projects.');
	}
	
	escapes('projid', $projid);
	escapes('name', $name);
	escapes('owner', $owner);
	
	if(isNews($projid))
	{
		errAndDie('You cannot edit a project with id = -1');
	}

	$sqlquery = "UPDATE `pb_projects` "
			.	"SET `name` = '$mName', "
			.	"`modified` = NOW(), "
			.	"`description` = '$mDescription', "
			.	"`keywords` = '$mKeywords', "
			.	"`status` = '$mStatus', "
			.	"`progress` = '$mProgress', "
			.	"`priority` = '$mPriority', "
			.	"`deadline` = '$mDeadline', "
			.	"`notes` = '$mNotes' "
			.	""
			.	" WHERE `id` = '$mprojid'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());
	$numrows = mysql_affected_rows();
	
	if(!$numrows)
		print('<h3>Update unsuccessful.</h3>');
	else
		print("<h3>Edited.</h3>");
}
else
{
	$sqlquery = "INSERT INTO `pb_projects` (`name`,`owner`,`added`,`modified`,"
		.	"`description`,`keywords`,`status`,`progress`,`priority`,"
		.	"`deadline`,`notes`) VALUES ('$mName','$mOwner',NOW(),NOW(),'$mDescription',"
		.	"'$mKeywords','$mStatus','$mProgress','$mPriority',"
		.	"'$mDeadline','$mNotes')";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	print("<h3>Created.</h3>");

	$projid = mysql_insert_id();
	escapes('projid',$projid);

}



print("<p><a href=\"projectInfo.php?projid=$urlprojid\">View Project Page</a></p>");

emitBottom();
?>