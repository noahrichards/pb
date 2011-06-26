<?php
require_once('includes/globals.inc');

if(isset($_GET['postid']))
{
	$postid = $_GET['postid'];
	escapes('postid',$postid);

	emitTop('Peanut Butter -> Posts -> Confirm Delete', '/pb/');
}
else
{
	emitTop('Peanut Butter -> Posts -> Confirm Delete [ERROR]', $_SERVER['PHP_SELF']);
	errAndDie('Required information not specified.');
}

if($userType == VISITOR)
{
	errAndDie('Sorry, visitors may not delete posts.');
}

mysqlSetup();
escapes('userName',$userName);

if($userType == NORMAL)
	$sqlquery = "SELECT `name` AS `project`,B.`added`,B.`modified`,B.`title`,B.`text`,B.`user` "
	.	"FROM `pb_blog` AS B LEFT JOIN `pb_projects` ON "
	.	"`pb_projects`.`id` = B.`projid` WHERE B.`id` = '$mpostid' AND "
	.	"`user` = '$muserName'";
else
	$sqlquery = "SELECT `name` AS `project`,B.`added`,B.`modified`,B.`title`,B.`text`,B.`user` "
	.	"FROM `pb_blog` AS B LEFT JOIN `pb_projects` ON "
	.	"`pb_projects`.`id` = B.`projid` WHERE B.`id` = '$mpostid'";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

if(!($row = mysql_fetch_assoc($result)))
{
	errAndDie('You are unable to access this post unless you own it or are an admin/siteadmin.');
}

if($userType == ADMIN && $row['projid'] == -1 && $userName != $row['user'])
{
	errAndDie('Admins can only delete their own news posts.');
}

?>
<h2>Post contents:</h2>
<table>
<?php
	foreach($row as $key => $value)
	{
		$value = textToHTML($value);
		print("<tr>\n");
		print("\t<th align=\"left\">$key</th>\n");
		print("\t<td>$value</td>\n");
		print("</tr>\n");
	}

	print('</table>');

	print("<p><a href=\"postDelete.php?postid=$urlpostid\">"
		.	"Delete this post.</a></p>");


emitBottom();
?>