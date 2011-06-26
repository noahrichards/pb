<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> Searches -> REMOVE', $_SERVER['PHP_SELF']);

if($userType != ADMIN && $userType != SITEADMIN)
{
	print('<h3>Sorry, only admins/siteadmins can remove searches.</h3>');
	emitBottom();
	die();
}

if(!isset($_GET['age']))
{
	errAndDie('No age specified.');
}

$age = mysql_escape_string($_GET['age']);
$urlage = urlencode($_GET['age']);

if(isset($_GET['confirm']))
{

	mysqlSetup();
	
	$sqlquery = "DELETE FROM `pb_searches` WHERE `lastused` < SUBDATE(NOW(),$age)";
	
	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());
	
	print("<h3>Searches removed.</h3>");
	
	print('<a href="projectManagement.php">Back to Project Management</a>');


}
else
{
	mysqlSetup();

	$sqlquery = "SELECT `name`,`owner`,`lastused` FROM `pb_searches` WHERE "
		.	"`lastused` < SUBDATE(NOW(),$age) ORDER BY `name` ASC";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());
	
	$count = mysql_num_rows($result);
	
	$es = '';
	if($count != 1)
		$es = 'es';
	
	print("<h3>$count search$es older than $age days:</h3>\n");
	
	if($count)
		print("<a href=\"".$_SERVER['PHP_SELF']."?age=$urlage&amp;confirm=1\">Remove these searches.</a>\n");

?>


<table id="searches">
<tr>
	<th>Search Name</th>
	<th>Owner</th>
	<th>Last Used</th>
</tr>

<?php


	while($row = mysql_fetch_row($result))
	{
		$urlname = urlencode($row[0]);
?>
<tr>
	<td><?php echo $row[0]?></td>
	<td><?php echo $row[1]?></td>
	<td><?php echo $row[2]?></td>
</tr>
<?php
	}
	
	mysql_close();
?>
</table>

<?php
}
emitBottom();

?>