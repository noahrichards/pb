<?php
require_once('includes/globals.inc');

emitTop('Peanut Butter -> News', $_SERVER['PHP_SELF']);

mysqlSetup();

$sqlquery = "SELECT `title`,`user`,`added`,`modified`,`text`,`id` FROM `pb_blog` "
	.	" WHERE `projid` = '-1' ORDER BY `modified` DESC LIMIT 10";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

$count = mysql_num_rows($result);

if(isset($_GET['start']))
	escapes('start',$_GET['start']);
else
	escapes('start',0);

print("<h3>News:</h3>\n");
if($count > NUM_ENTRIES)
	$showCount = NUM_ENTRIES;
else
	$showCount = $count;

print("<h3>Posts ".($start + 1)."-".($start + $showCount)."</h3>\n");

print("<p>");
$self = $_SERVER['PHP_SELF'];

if($userType == ADMIN || $userType == SITEADMIN)
{
	print('<a href="postNew.php?projid=-1">New Post</a>&nbsp;');
	print('<a href="postManagement.php?projid=-1">Manage Posts</a><br /><br />');
}

/***
**	Set up links for "newest" "previous" and "next", based upon where we are
**  in the results
**/
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

print("</p><br />");

if(!$count)
{
	print("<h3>No news.</h3>");
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

emitBottom();
?>