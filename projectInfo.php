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
	emitTop('Peanut Butter -> Projects -> Info: '.$name, $_SERVER['PHP_SELF']."?projid=$urlprojid");

	if($name == '')
	{
		errAndDie('Project not found.');
	}
}
else
{
	emitTop('Peanut Butter -> Projects -> Info [ERROR]', $_SERVER['PHP_SELF']);
	errAndDie('Project id not specified.');
}


mysqlSetup();

$sqlquery = "SELECT * FROM `pb_projects` WHERE `id` = '$mprojid'";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

if($row = mysql_fetch_assoc($result))
{
	$name = $row['name'];
?>
<h2><?php echo $name?>
<?php
	if($userType == SITEADMIN || ($userType == ADMIN && $row['owner'] == $userName))
	{
		print("&nbsp;<a href=\"projectEdit.php?projid=$urlprojid\">Edit</a>");
		print("&nbsp;<a href=\"projectDelete.php?projid=$urlprojid\">Delete</a>");
	}

?>
</h2>
<table id="projectinfo">
<?php
	$row = cleanValues($row);
	foreach($row as $key => $value)
	{

		if($key == 'progress')
			$value = "<img src=\"pngbar.php?rating=$value&amp;type=progress\" />";
		elseif($key == 'priority')
			$value = "<img src=\"pngbar.php?rating=$value&amp;type=priority\" />";
		elseif($key == 'owner')
			$owner = $value;


		print("<tr>\n");
		print("\t<th align=\"left\">$key</th>\n");
		print("\t<td>$value</td>\n");
		print("</tr>\n");
	}
}
?>

</table>

<?php

if(isset($_GET['start']))
	escapes('start',$_GET['start']);
else
	escapes('start',0);

escapes('name',$name);

$sqlquery = "SELECT B.`title`,B.`user`,B.`added`,B.`modified`,B.`text`,`pb_projects`.`name` AS `project`,B.`id` "
	.	"FROM `pb_blog` AS B LEFT JOIN `pb_projects` ON `pb_projects`.`id` = `projid` "
	.	"WHERE `projid` = '$mprojid'"
	.	"ORDER BY `modified` DESC LIMIT $mstart,"
	.	($start + NUM_ENTRIES + 1);

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$count = mysql_num_rows($result);

if($count > NUM_ENTRIES)
	$showCount = NUM_ENTRIES;
else
	$showCount = $count;

print("<h3>Posts ".($start + 1)."-".($start + $showCount)."</h3>\n");

if($userType != VISITOR)
{
?><a href="postNew.php?projid=<?php echo $urlprojid;?>">New Post</a><?php
}

if($userType == SITEADMIN || ($userType == ADMIN && $owner == $userName))
{
?> <a href="postManagement.php?projid=<?php echo $urlprojid;?>">Manage Posts</a><?php
}


print("<p>");
$self = $_SERVER['PHP_SELF']."?id=$urlid";

if(!$start)
{
	print("Newest&nbsp;&nbsp;&lt;&lt;Prev ".NUM_ENTRIES."&nbsp;&nbsp;");
}
else
{
	$newval = $start - NUM_ENTRIES;
	if($newval < 0) $newval = 0;

	print("<a href=\"$self\">Newest</a>&nbsp;&nbsp;"
	.	"<a href=\"$self&amp;start=$newval\">&lt;&lt; Prev ".NUM_ENTRIES
	.	"</a>&nbsp;&nbsp;");
}

if($count > NUM_ENTRIES)
{
	$newval = $start + NUM_ENTRIES;
	print("<a href=\"$self&amp;start=$newval\">Next ".NUM_ENTRIES."&gt;&gt;</a>");
	$count = NUM_ENTRIES;
}
else
{
	print("Next ".NUM_ENTRIES."&gt;&gt;");
}

print("<br />");

if(!$count)
{
	print("<h3>No entries.</h3>");
}
else
{

	for($i = $start; $i < $start + $count; $i++)
	{
		$row = mysql_fetch_assoc($result);

		$row = cleanValues($row);
		emitPost($row);
	}
}

mysql_close();

?>

<?php emitBottom();?>