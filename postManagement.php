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

	emitTop('Peanut Butter -> Posts -> Management', $_SERVER['PHP_SELF']."?projid=$urlprojid");

	if($name == '')
	{
		print('<h3>Project not found.</h3>');
		emitBottom();
		die();
	}
}
else
{
	emitTop('Peanut Butter -> Posts -> Management [ERROR]', $_SERVER['PHP_SELF']);
	errAndDie('Project not specified.');
}

if($userType != ADMIN && $userType != SITEADMIN)
{
	errAndDie('Sorry, only admins/siteadmins can manage posts.');
}
if(!$isNews && $userType == ADMIN && $userName != $owner)
{
	errAndDie('Sorry, admins can only manage posts for their own projects.');
}

?>

<h2>Posts for: <?php echo $name;?></h2>
<?php
if(!$isNews)
	print('<p><a href="projectInfo.php?projid='.$urlprojid.'">Back to project page</a></p>');
else
	print('<p><a href="news.php">Back to news</a></p>');
?>

<table>
<tr>
	<th>Title</th>
	<th>Poster</th>
	<th>Date Added</th>
	<th>Date Modified</th>
	<th>ID</th>
	<th>Edit Post</th>
	<th>Delete Post</th>
</tr>

<?php

$sqlquery = "SELECT `title`,`user`,`added`,`modified`,`id` FROM `pb_blog` "
	.	" WHERE `projid` = '$mprojid' ORDER BY `modified` DESC";

mysqlSetup();


$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$count = mysql_num_rows($result);

if(!$count)
{
	print("<h3>No entries.</h3>");
}
else
{

	while($row = mysql_fetch_row($result))
	{
		print("<tr>");
		foreach($row as $value)
			print("\t<td>$value</td>\n");

		$postid = $row[4];
		escapes('postid', $postid);

		$title = urlencode($row[0]);
		$user = urlencode($row[1]);

		print("\t<td><a href=\"postEdit.php?postid=$urlpostid\">Edit</a></td>\n");
		print("\t<td><a href=\"postConfirmDelete.php?postid=$urlpostid\">Delete</a></td>\n");

	}

}

mysql_close();

?>
</table>


<?php emitBottom();?>