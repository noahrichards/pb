<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> Account -> Management', $_SERVER['PHP_SELF']);

if($userType == SITEADMIN)
{
?>
	<h2>Site users</h2>

	<table class="list">
	<thead>
		<tr>
			<th>User</th>
			<th>User Type</th>
			<th>Created</th>
			<th>Delete User</th>
			<th>Promote/Demote User</th>
		</tr>
	</thead>
	<tbody>

	<?php

	mysqlSetup();

	$sqlquery = "SELECT `name`,`category`,`created` FROM `pb_users` ORDER BY `name` ASC";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	$rownum = 0;

	while($row = mysql_fetch_row($result))
	{
		print("<tr>");
		$style = '';
		if($rownum++ % 2 == 0)
			$style = ' class="greyed"';

	foreach($row as $value)
			print("\t<td$style>$value</td>\n");

		$urlname = urlencode($row[0]);

		if($row[0] == $userName)
			print("<td$style>------</td>\n");
		else
			print("\t<td$style><a href=\"userDelete.php?name=$urlname\">Delete</a></td>\n");

		if($row[1] == NORMAL)
		{
			print("<td$style><a href=\"usertypeChange.php?type=promote&amp;"
				.	"name=$urlname\">Promote to admin</a></td>");
		}
		elseif($row[1] == ADMIN)
		{
			print("<td$style><a href=\"usertypeChange.php?type=demote&amp;"
				.	"name=$urlname\">Demote to normal user</a></td>");
		}
		elseif($row[1] == SITEADMIN)
		{
			print("<td$style>------</td>\n");
		}


	}

	mysql_close();

	?>
	</tbody>
	</table>

<?php

}

emitBottom();
?>