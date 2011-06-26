<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> Searches -> New/Edit', $_SERVER['PHP_SELF']);

if($userType == VISITOR)
{
	errAndDie('Sorry, you must be registered to make searches.');
}

if(!isset($_GET['id']))
{
	errAndDie('No search specified.');
}

escapes('id',$_GET['id']);

if(isset($_POST['savesearch']))
{
	mysqlSetup();

	if(!isset($_POST['keywords']) ||
		!isset($_POST['keyname']) || $_POST['keywords'] == '' ||
		$_POST['keyname'] == '')
	{
		print('<h3>Missing information.</h3>');
	}
	else
	{
		escapes('keywords',$_POST['keywords']);
		escapes('keyname',$_POST['keyname']);
		escapes('username',$userName);

		mysqlSetup();

		$sqlquery = "UPDATE `pb_searches` SET `name` = '$mkeyname', `owner` = "
			.	"'$musername', `terms` = '$mkeywords', `lastused` = NOW() WHERE "
			.	"`id` = '$mid' LIMIT 1";

		$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

		print('<h3>Search has been saved.</h3>');
		print('<a href="projectManagement.php">Back to Project Management</a>');


		emitBottom();
		die();
	}
}



?>

<h3>Enter keywords, separated by commas:</h3>
<form name="newkeywords" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<table>
<tr>
<?php

mysqlSetup();

if(!isset($_POST['newsearch']))
{
	$sqlquery = "SELECT `terms`,`name` FROM `pb_searches` WHERE `id` = '$mid'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	if(!($row = mysql_fetch_assoc($result)))
	{
		print('<h3>Query not found.</h3>');
		emitBottom();
		die();
	}

	escapes('keywords', $row['terms']);
	escapes('name', $row['name']);
}
else
{
	escapes('keywords', $_POST['keywords']);
	escapes('name', $_POST['name']);
}

print("\t<th>Name:</th>\n");
print("\t<td><input type=\"text\" name=\"keyname\" size=\"80\" value=\"$name\" /></td>\n");
print("</tr><tr>\n");
print("\t<th>Keywords:</th>\n");
print("\t<td><input type=\"text\" name=\"keywords\" value=\"$keywords\" size=\"100\"/></td>\n");
print("</tr>\n</table>\n");
print("<input type=\"submit\" name=\"newsearch\" value=\"Try Search\" />");
print("<input type=\"submit\" name=\"savesearch\" value=\"Save Search\" />");
print("</form>\n");

if($keywords != '')
{
	mysqlSetup();

	$matchColl = new MatchCollection("AND");

	$words = explode(',',$mkeywords);

	foreach($words as $word)
		$matchColl->addMatch(new Match('Keywords','%'.$word.'%','LIKE'));

	$whereSQL = $matchColl->toSQL();

	$sqlquery = "SELECT `name`, LEFT(`description`,100),`status`,`progress`,`priority`,`owner`,`id` FROM "
		.	"`pb_projects` WHERE $whereSQL ORDER BY `priority` DESC";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	?>

<table id="projectlist"  class="list">
<tr>
	<th>Project Name</th>
	<th>Owner</th>
	<th>Status</th>
	<th>Progress</th>
	<th>Priority</th>
	<th>Description</th>
</tr>
	<?php

	$rownum = 0;
	while($row = mysql_fetch_row($result))
	{
		$urlname = urlencode($row[0]);
		$urlid = urlencode($row[6]);

		$style = '';
		if($rownum++ % 2 == 0)
			$style = ' class="greyed"';
	?>
	<tr>
		<td<?php echo $style;?>><a href="projectInfo.php?projid=<?php echo $urlid;?>"><?php echo $row[0]?></a></td>
		<td<?php echo $style;?>><?php echo $row[5]?></td>
		<td<?php echo $style;?>><?php echo $row[2]?></td>
		<td<?php echo $style;?>><img src="pngbar.php?rating=<?php echo $row[3]?>$amp;type=progress" /></td>
		<td<?php echo $style;?>><img src="pngbar.php?rating=<?php echo $row[4]?>&amp;type=priority" /></td>
		<td<?php echo $style;?>><?php echo $row[1]?>...</td>
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