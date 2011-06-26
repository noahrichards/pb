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
	emitTop('Peanut Butter -> Projects -> Edit: '.$name, '/pb/');

	if($name == '')
	{
		errAndDie('Project not found.');
	}
}
else
{
	emitTop('Peanut Butter -> Edit Project [ERROR]', $_SERVER['PHP_SELF']);
	errAndDie('Required Information not specified.');
}

if($userType != ADMIN && $userType != SITEADMIN)
{
	errAndDie('Sorry, only admins/siteadmins may edit projects.');
}

if($userType == ADMIN && $owner != $userName)
{
	errAndDie('Sorry, you may only edit your own project.');
}


mysqlSetup();

$sqlquery = "SELECT * FROM `pb_projects`"
	.	" WHERE `id` = '$mprojid'";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$count = mysql_num_rows($result);

$row = mysql_fetch_assoc($result);

?>
<h2>Edit Project: <?php echo $name;?></h2>

<form name="projectNew" method="post" action="projectSave.php">

<table>
<tr>
	<th>Name:</th>
	<td><input type="text" size="80" name="name" value="<?php echo htmlentities($name)?>" /></td>
</tr>
<tr>
	<th>Desciption:</th>
	<td><textarea name="description" cols="80" rows="10"><?php echo htmlentities($row['description'])?></textarea></td>
</tr>
<tr>
	<th>Keywords:</th>
	<td><input type="text" size="80" name="keywords" value="<?php echo htmlentities($row['keywords'])?>" /></td>
</tr>
<tr>
	<th>Status:</th>
	<td>
	<select name="status">
<?php
foreach($statusEnum as $status)
{
	if($row['status'] == $status)
		print("\t<option value=\"$status\" selected=\"yes\">$status</option>\n");
	else
		print("\t<option value=\"$status\">$status</option>\n");
}
?>
	</select>
	</td>
</tr>
<tr>
	<th>Progress (percentage):</th>
	<td><input type="text" size="4" name="progress" value="<?php echo htmlentities($row['progress'])?>" /></td>
</tr>
<tr>
	<th>Priority (1=lowest, 10=highest):</th>
	<td><input type="text" size="2" name="priority" value="<?php echo htmlentities($row['priority'])?>" /></td>
</tr>
<tr>
	<th>Deadline (yyyy-mm-dd) (optional):</th>
	<td><input type="text" size="10" name="deadline" value="<?php echo htmlentities($row['deadline'])?>" /></td>
</tr>
<tr>
	<th>Notes (optional):</th>
	<td rowspan="2"><textarea name="notes" cols="80" rows="5"><?php echo htmlentities($row['notes'])?></textarea></td>
</tr>

</table>

<input type="hidden" name="modified" value="1" />
<input type="hidden" name="projid" value="<?php echo $projid;?>" />

<input type="submit" value="Edit Project" />
</form>

<?php emitBottom();?>