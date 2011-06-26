<?php
require_once('includes/globals.inc');

session_start('keywords');

$keyname = 'FALSE';
$columns = array();

if(isset($_SESSION['words']))
{
	$keywords = $_SESSION['words'];
	$keyowner = $_SESSION['owner'];
	$keyname = $_SESSION['keyname'];
}

if(isset($_GET['keyname']))
{
	$keyerr = 0;

	$keyname = $_GET['keyname'];
	if($keyname == 'FALSE')
	{
		unset($_SESSION['words']);
		unset($_SESSION['owner']);
		unset($_SESSION['keyname']);
		$keywords = '';
		$keyowner = '';
	}
	else
	{
		if($keyname == '+ADDNEW')
		{
			session_write_close();
			print('<script type="text/javascript">document.location="projectNewSearch.php"</script>');
		}

		$mKeyname = mysql_escape_string($keyname);


		mysqlSetup();

		$sqlquery = "SELECT `terms`,`owner` FROM `pb_searches` WHERE `name` = "
			.	"'$mKeyname' LIMIT 1";

		$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

		if(!($row = mysql_fetch_row($result)))
		{
			$keyerr = 1;
		}
		else
		{
			$keywords = $row[0];
			$keyowner = $row[1];
			$_SESSION['keyname'] = $keyname;
			$_SESSION['owner'] = $keyowner;
			$_SESSION['words'] = $keywords;

			$sqlquery = "UPDATE `pb_searches` SET `lastused` = NOW() WHERE `name` "
				.	" = '$mKeyname' LIMIT 1";

			$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());
		}

		mysql_close();
	}
}

session_write_close();

emitTop('Peanut Butter -> Projects -> Overview', $_SERVER['PHP_SELF']);


$noStatus = TRUE;
$statusSelected = array();

foreach($statusEnum as $status)
{
	if(isset($_GET[str_replace(' ','_',$status)]))
	{
		$noStatus = FALSE;
		$statusSelected[] = $status;
	}
}

if($noStatus)
	$statusSelected = $statusEnum;

if($keyerr)
{
	print('<p><em>Keyword search name not found.</em></p>');
}

$selOwner = (isset($_GET['owner'])) ? $_GET['owner'] : '+ALL';

$urlOwner = urlencode($selOwner);

/*
$showAll = '<a href="'.$_SERVER['PHP_SELF'].'?owner='.$urlOwner.'">Show All</a>';

$linkString = array();

$linkString[] = $_GET['hidec'] == 1 ? $showAll : 
	'<a href="'.$_SERVER['PHP_SELF'].'?hidec=1&amp;owner='.$urlOwner. '">Hide Cancelled/Completed</a>';

$linkString[] = $_GET['hidec'] == 2 ? $showAll : 
	'<a href="'.$_SERVER['PHP_SELF'].'?hidec=2&amp;owner='.$urlOwner.'">Show Only \'In Progress\'</a>';

$linkString[] = $_GET['hidec'] == 3 ? $showAll :
	'<a href="'.$_SERVER['PHP_SELF'].'?hidec=3&amp;owner='.$urlOwner.'">Show Only \'On Hold\' and \'Pending\'</a>';

print('<p>'.implode('&nbsp;',$linkString).'</p>');
*/
?>


<form name="selectStatus" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="keyname" value="<?php echo $keyname;?>" />
<input type="hidden" name="owner" value="<?php echo $selOwner;?>" />

<h4>View projects with a status of:</h4>
<?php
foreach($statusEnum as $status)
{
	$specStatus = preg_replace('/[][\s()+-]/', '', $status);
	$checked = '';
	if(in_array($status, $statusSelected))
		$checked = ' checked="checked"';
	print("<input type=\"checkbox\" name=\"$status\" id=\"$specStatus\" value=\"1\"$checked /><a onClick='"
		.	"document.getElementById(\"$specStatus\").checked = !document.getElementById(\"$specStatus\").checked'"
		.	" style=\"cursor: pointer\">$status</a>&nbsp;&nbsp;");
}
?>
<input type="submit" value="Update Table" />
</form>

<?php

mysqlSetup();

$sqlquery = "SELECT DISTINCT `owner` FROM `pb_projects` ORDER BY `owner` ASC";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

?>
<table>
<tr>
<td>
<form name="selectOwner" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php
foreach($statusSelected as $status)
	print("<input type=\"hidden\" name=\"$status\" value=\"1\" />\n");
?>
<input type="hidden" name="keyname" value="<?php echo $keyname;?>" />

Select Owner:&nbsp;
<select name="owner" onchange="document.selectOwner.submit()">
<option value="+ALL">+ALL</option>

<?php
while($row = mysql_fetch_row($result))
{
	$owner = $row[0];
	$selected = '';
	if($selOwner == $owner)
		$selected = " selected=\"selected\"";
	print("<option value=\"$owner\"$selected>$owner</option>\n");
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

$sqlquery = "SELECT `name` FROM `pb_searches` ORDER BY `name` ASC";
$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());
?>

<form name="selectSearch" method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php
foreach($statusSelected as $status)
	print("<input type=\"hidden\" name=\"$status\" value=\"1\" />\n");
?>
<input type="hidden" name="owner" value="<?php echo $selOwner;?>" />

Select Search:&nbsp;
<select name="keyname" onchange="document.selectSearch.submit()">
<option value="FALSE">No Search</option>
<?php
while($row = mysql_fetch_row($result))
{
	$search = $row[0];
	$selected = '';
	if($search == $keyname)
		$selected = " selected=\"selected\"";
	print("<option value=\"$search\"$selected>$search</option>\n");
}
?>
<option value="+ADDNEW">Add New Search</option>
</select>
</form>
</td></tr></table>

<table id="projectlist" class="list">
<tr>
	<th>Project Name</th>
	<th>Owner</th>
	<th>Status</th>
	<th>Progress</th>
	<th>Priority</th>
	<th>Description</th>
</tr>

<?php

$where = '';

$matchColl = new MatchCollection("AND");

$statiMC = new MatchCollection("OR");

foreach($statusSelected as $status)
{
	$statiMC->addMatch(new Match('Status',$status));
}
$matchColl->addMatch($statiMC);


if(isset($_GET['owner']) && $_GET['owner'] != '+ALL')
{
	$mOwner = mysql_escape_string($_GET['owner']);
	$matchColl->addMatch(new Match('owner',$mOwner));
}

if($keyname != '')
{
	$words = explode(',',$keywords);

	foreach($words as $word)
		$matchColl->addMatch(new Match('Keywords','%'.mysql_escape_string($word).'%','LIKE'));
}

$whereSQL = $matchColl->toSQL();

$sqlquery = "SELECT `name`, LEFT(`description`,80),`status`,`progress`,`priority`,`owner`,`id` FROM "
	.	"`pb_projects` WHERE $whereSQL ORDER BY `priority` DESC";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$rownum = 0;

while($row = mysql_fetch_row($result))
{
	$urlid = urlencode($row[6]);
	$style = '';
	if($rownum++ % 2 == 0)
		$style = ' class="greyed"';
?>
<tr>
	<td<?php echo $style;?>><a href="projectInfo.php?projid=<?php echo $urlid;?>"><?php echo $row[0]?></a></td>
	<td<?php echo $style;?>><?php echo $row[5]?></td>
	<td<?php echo $style;?>><?php echo $row[2]?></td>
	<td<?php echo $style;?>><img src="pngbar.php?rating=<?php echo $row[3]?>$amp;type=progress" alt="<?php echo $row[3]?>%" /></td>
	<td<?php echo $style;?>><img src="pngbar.php?rating=<?php echo $row[4]?>&amp;type=priority" alt="<?php echo $row[4]?>" /></td>
	<td<?php echo $style;?>><?php echo $row[1]?>...</td>
</tr>
<?php
}

mysql_close();
?>

</table>

<?php emitBottom();?>