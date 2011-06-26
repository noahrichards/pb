<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> Management', $_SERVER['PHP_SELF']);

if($userType != ADMIN && $userType != SITEADMIN)
{
	print('<h3>Sorry, only admins/siteadmins can manage projects.</h3>');
	emitBottom();
	die();
}

?>


<a name='projects'></a><h2>Projects</h2>

<p><a href="projectNew.php">New Project</a></p>

<table id="projectlist" class="list">
<tr>
	<th>Project Name</th>
	<th>Status</th>
	<th>Progress</th>
	<th>Priority</th>
	<th>Edit Project</th>
	<th>Delete Project</th>

</tr>

<?php

mysqlSetup();

$where = '';

$muser = mysql_escape_string($userName);

if($userType == ADMIN)
	$where = " WHERE `owner` = '$muser'";

$sqlquery = "SELECT `name`, `status`,`progress`,`priority`,`owner`,`id` FROM "
	.	"`pb_projects`$where ORDER BY `name` ASC";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$rownum = 0;
while($row = mysql_fetch_row($result))
{
	$urlprojid = urlencode($row[5]);
	$style = '';
	if($rownum++ % 2 == 0)
		$style = ' class="greyed"';

?>
<tr>
	<td<?php echo $style;?>><?php echo $row[0]?></td>
	<td<?php echo $style;?>><?php echo $row[1]?></td>
	<td<?php echo $style;?>><img src="pngbar.php?rating=<?php echo $row[2]?>$amp;type=progress" /></td>
	<td<?php echo $style;?>><img src="pngbar.php?rating=<?php echo $row[3]?>&amp;type=priority" /></td>
	<td<?php echo $style;?>><a href="projectEdit.php?projid=<?php echo $urlprojid;?>">Edit</a></td>
	<td<?php echo $style;?>><a href="projectDelete.php?projid=<?php echo $urlprojid;?>">Delete</a></td>
</tr>
<?php
}

?>

</table>

<a name='searches'></a>

<h2>Searches</h2>

<form name="selectAge" method="get" action="projectDeleteSearches.php">
<p>Find searches unused for:
<select name="age">
<option value="10">10</option>
<option value="15">15</option>
<option value="25">25</option>
<option value="60">60</option>
</select>
days.</p>
<input type="submit" name="submit" value="Find searches" />
</form>

<table id="searchlist" class="list">
<tr>
	<th>Search Name</th>
	<th>Owner</th>
	<th>Last Used</th>
	<th>Edit Search</th>
</tr>

<?php

mysqlSetup();

$sqlquery = "SELECT `name`,`owner`,`lastused`,`id` FROM `pb_searches` ORDER BY `name` ASC";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$rownum = 0;
while($row = mysql_fetch_assoc($result))
{
	$urlname = urlencode($row[0]);
	$style = '';
	if($rownum++ % 2 == 0)
		$style = ' class="greyed"';
?>
<tr>
	<td<?php echo $style;?>><?php echo $row['name']?></td>
	<td<?php echo $style;?>><?php echo $row['owner']?></td>
	<td<?php echo $style;?>><?php echo $row['lastused']?></td>
	<td<?php echo $style;?>><a href="projectEditSearch.php?id=<?php echo $row['id'];?>">Edit</a></td>
</tr>
<?php
}



mysql_close();
?>

</table>
<?php emitBottom();?>