<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> Latest Posts', $_SERVER['PHP_SELF']);

if(isset($_GET['start']))
	escapes('start',$_GET['start']);
else
	escapes('start',0);

$where = '';

$matchColl = new MatchCollection("AND");

$owner = '+ALL';
$project = '+ALL';


if(isset($_GET['owner']) && $_GET['owner'] != '+ALL')
{
	escapes('owner',$_GET['owner']);
	$matchColl->addMatch(new Match('user',$mowner));
}
if(isset($_GET['project']) && $_GET['project'] != '+ALL')
{
	escapes('project',$_GET['project']);
	$matchColl->addMatch(new Match('name', $mproject));
}

$whereSQL = $matchColl->toSQL();

mysqlSetup();

$sqlquery = "SELECT DISTINCT `user` "
	.	"FROM `pb_blog` ORDER BY `user` ASC";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());


?>

<table>
<tr>
<td>
<form name="selectOwner" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="start" value="<?php echo $start;?>" />
<input type="hidden" name="project" value="<?php echo $project;?>" />

Select Owner:&nbsp;
<select name="owner" onchange="document.selectOwner.submit()">
<option value="+ALL">+ALL</option>

<?php
while($row = mysql_fetch_row($result))
{
	$curOwner = $row[0];
	$selected = '';
	if($curOwner == $owner)
		$selected = " selected=\"selected\"";
	print("<option value=\"$curOwner\"$selected>$curOwner</option>\n");
}
?>
</select>
</form>
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
<?php

$sqlquery = "SELECT DISTINCT `pb_projects`.`name` FROM `pb_blog` "
	.	"LEFT JOIN `pb_projects` ON `pb_projects`.`id` = `projid` "
	.	"WHERE `projid` != '-1' ORDER BY `name` ASC";
$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());
?>

<form name="selectProject" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="start" value="<?php echo $start;?>" />
<input type="hidden" name="owner" value="<?php echo $owner;?>" />

Select Project:&nbsp;
<select name="project" onchange="document.selectProject.submit()">
<option value="+ALL">+ALL</option>
<?php
while($row = mysql_fetch_row($result))
{
	$curProject = $row[0];
	$selected = '';
	if($curProject == $project)
		$selected = " selected=\"selected\"";
	print("<option value=\"$curProject\"$selected>$curProject</option>\n");
}
?>
</select>
</form>
</td></tr></table>

<?php


$sqlquery = "SELECT B.`title`,B.`user`,B.`added`,B.`modified`,B.`text`,`pb_projects`.`name` AS `project`,B.`id` "
	.	"FROM `pb_blog` AS B LEFT JOIN `pb_projects` ON `pb_projects`.`id` = `projid` "
	.	"WHERE $whereSQL AND `projid` != '-1'"
	.	"ORDER BY `modified` DESC LIMIT $mstart,"
	.	($start + NUM_ENTRIES + 1);


$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$count = mysql_num_rows($result);

if($count > NUM_ENTRIES)
	$showCount = NUM_ENTRIES;
else
	$showCount = $count;

print("<h3>Posts ".($start + 1)."-".($start + $showCount)."</h3>\n");

print("<p>");
$self = $_SERVER['PHP_SELF'];

if(!$start)
{
	print("Newest&nbsp;&nbsp;&lt;&lt;Prev ".NUM_ENTRIES."&nbsp;&nbsp;");
}
else
{
	$newval = $start - NUM_ENTRIES;
	if($newval < 0) $newval = 0;

	print("<a href=\"$self\">Newest</a>&nbsp;&nbsp;"
	.	"<a href=\"$self?start=$newval\">&lt;&lt; Prev ".NUM_ENTRIES
	.	"</a>&nbsp;&nbsp;");
}

if($count > NUM_ENTRIES)
{
	$newval = $start + NUM_ENTRIES;
	print("<a href=\"$self?start=$newval\">Next ".NUM_ENTRIES."&gt;&gt;</a>");
	$count = NUM_ENTRIES;
}
else
{
	print("Next ".NUM_ENTRIES."&gt;&gt;");
}

print("</p><br />");

$lastProject = '';

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

		if($row['project'] != $lastProject)
			print("<h3>Project: ".$row['project']."</h3>\n");

		$lastProject = $row['project'];

		emitPost($row);
	}

}

mysql_close();


?>

<?php emitBottom();?>