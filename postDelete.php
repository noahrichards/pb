<?php
require_once('includes/globals.inc');

if(isset($_GET['postid']))
{
	$postid = $_GET['postid'];
	escapes('postid',$postid);

	emitTop('Peanut Butter -> Posts -> Delete', '/pb/');
}
else
{
	emitTop('Peanut Butter -> Posts -> Delete [ERROR]', $_SERVER['PHP_SELF']);
	errAndDie('Required information not specified.');
}

if($userType == VISITOR)
{
	errAndDie('Sorry, visitors may not delete posts.');
}

mysqlSetup();
escapes('userName',$userName);

if($userType == NORMAL)
	$sqlquery = "SELECT * FROM `pb_blog` WHERE `id` = '$mpostid' AND `user` = '$muserName'";
else
	$sqlquery = "SELECT * FROM `pb_blog` WHERE `id` = '$mpostid'";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

if(!($row = mysql_fetch_assoc($result)))
{
	errAndDie('You are unable to access this post unless you own it or are an admin/siteadmin.');
}

if($userType == ADMIN && $row['projid'] == -1 && $userName != $row['user'])
{
	errAndDie('Admins can only delete their own news posts.');
}

$projid = $row['projid'];
$isNews = isNews($projid);
escapes('projid',$projid);

$sqlquery = "DELETE FROM `pb_blog` WHERE `id` = '$mpostid'";


$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());


print('<h3>Post Deleted.</h3>');

if(!$isNews)
	print("<p><a href=\"projectInfo.php?projid=$urlprojid\">Back to project page.</a></p>");
else
	print("<p><a href=\"news.php\">Peanut Butter News</a></p>");

emitBottom();
?>