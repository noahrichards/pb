<?php
require_once('includes/globals.inc');

if(isset($_GET['postid']))
{
	escapes('postid',$_GET['postid'];);

	emitTop('Peanut Butter -> Posts -> View Post', './');
}
else
{
	emitTop('Peanut Butter -> Posts -> View Post [ERROR]', './');
	errAndDie('Missing information.');
}

mysqlSetup();

$sqlquery = "SELECT `user`,B.`added`,B.`modified`,`text`,`title`,`pb_projects`.`name` "
	.	"FROM `pb_blog` AS B LEFT JOIN `pb_projects` ON `pb_projects`.`id` = `projid` "
	.	"WHERE B.`id` = '$mpostid'";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$count = mysql_num_rows($result);

if(!$count)
{
	print("<h3>No entry found with id $id.</h3>");
}
else
{
	$row = mysql_fetch_assoc($result);
	$row = cleanValues($row);

	emitPost($row, 0);
}

?>

<?php emitBottom();?>