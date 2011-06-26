<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> New Project', $_SERVER['PHP_SELF']);

if($userType != ADMIN && $userType != SITEADMIN)
{
	print('<h3>Sorry, only admins/siteadmins may create new projects.</h3>');
	emitBottom();
	die();
}

?>
<h2>New Project</h2>

<form name="projectNew" method="post" action="projectSave.php">

<table>
<tr>
	<th>Name:</th>
	<td><input type="text" size="80" name="name" /></td>
</tr>
<tr>
	<th>Desciption:</th>
	<td><textarea name="description" cols="80" rows="10"></textarea></td>
</tr>
<tr>
	<th>Keywords:</th>
	<td><input type="text" size="80" name="keywords" /></td>
</tr>
<tr>
	<th>Status:</th>
	<td>
	<select name="status">
<?php
foreach($statusEnum as $status)
{
	print("\t<option value=\"$status\">$status</option>\n");
}
?>
	</select>
	</td>
</tr>
<tr>
	<th>Progress (percentage):</th>
	<td><input type="text" size="4" name="progress" /></td>
</tr>
<tr>
	<th>Priority (1=lowest, 10=highest):</th>
	<td><input type="text" size="2" name="priority" /></td>
</tr>
<tr>
	<th>Deadline (yyyy-mm-dd) (optional):</th>
	<td><input type="text" size="10" name="deadline" /></td>
</tr>
<tr>
	<th>Notes:</th>
	<td rowspan="2"><textarea name="notes" cols="80" rows="5"></textarea></td>
</tr>

</table>

<input type="submit" value="Add Project" />
</form>

<?php emitBottom();?>