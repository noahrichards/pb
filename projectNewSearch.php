<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> New Keyword Search', $_SERVER['PHP_SELF']);

if($userType == VISITOR)
{
	print('<h3>Sorry, you must be registered to make searches.</h3>');
	emitBottom();
	die();
}

if(isset($_POST['savesearch']))
{
	mysqlSetup();

	if(!isset($_POST['keywords']) || !isset($_POST['keyname']) ||
		$_POST['keywords'] == '' || $_POST['keyname'] == '')
	{
		print('<h3>Missing information.</h3>');
	}
	else
	{
		$mKeywords = mysql_escape_string($_POST['keywords']);
		$mKeyname = mysql_escape_string($_POST['keyname']);
		$mUsername = mysql_escape_string($userName);

		mysqlSetup();

		$sqlquery = "INSERT INTO `pb_searches` (`name`,`owner`,`terms`,`lastused`) "
			.	"VALUES ('$mKeyname','$mUsername','$mKeywords',NOW())";

		$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

		print('<h3>Search has been saved.</h3>');
		print('<a href="projectOverview.php">Back to Project Overview</a>');


		emitBottom();
		die();
	}
}


?>

<h3>Enter keywords, separated by commas:</h3>
<form name="newkeywords" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table>
<tr>
<?php

if(isset($_POST['keywords']))
{
	$keywords = $_POST['keywords'];

	print("\t<th>Name:</th>\n");
	print("\t<td><input type=\"text\" name=\"keyname\" size=\"80\" /></td>\n");
	print("</tr><tr>\n");
	print("\t<th>Keywords:</th>\n");
	print("\t<td><input type=\"text\" name=\"keywords\" value=\"$keywords\" size=\"100\"/></td>\n");
	print("</tr>\n</table>\n");
	print("<input type=\"submit\" name=\"newsearch\" value=\"Try Search\" />");
	print("<input type=\"submit\" name=\"savesearch\" value=\"Save Search\" />");
	print("</form>\n");

	if($keywords != '')
	{
		$mKeywords = mysql_escape_string($keywords);

		mysqlSetup();

		$matchColl = new MatchCollection("AND");

		$words = explode(',',$keywords);

		foreach($words as $word)
			$matchColl->addMatch(new Match('Keywords','%'.mysql_escape_string($word).'%','LIKE'));

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
}
else
{
	print("\t<th>Keywords:</th>\n");
	print("\t<td><input type=\"text\" name=\"keywords\" value=\"$keywords\"  size=\"100\"/></td>\n");
	print("</tr>\n</table>\n");
	print("<input type=\"submit\" name=\"newsearch\" value=\"Try Search\" />");
	print("</form>\n");
}
emitBottom();
?>