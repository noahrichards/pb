<?php
require_once('includes/globals.inc');

if(isset($_GET['postid']))
{
	$postid = $_GET['postid'];

	escapes('postid', $postid);

	$isNews = isNews($projid);
	emitTop('Peanut Butter -> Posts -> Edit', $_SERVER['PHP_SELF']."?postid=$urlpostid");
}
else
{
	emitTop('Peanut Butter -> Edit Post [ERROR]', $_SERVER['PHP_SELF']);
	errAndDie('Required Information not specified.');
}

if($userType == VISITOR)
{
	errAndDie('Sorry, visitors may not edit posts.');
}

mysqlSetup();

$sqlquery = "SELECT `title`,`text`,`user`,`projid` FROM `pb_blog`"
	.	" WHERE `id` = '$mpostid'";

$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

if(!($row = mysql_fetch_row($result)))
{
	print('<h3>Post not found.</h3>');
	emitBottom();
	die();
}

$user = $row[2];

if($userType == NORMAL && $userName != $user)
{
	errAndDie('Sorry, you may only edit your own posts.');
}

escapes('projid',$row[3]);

$projinfo = getProjInfoFromId($projid);
$name = $projinfo[0];

?>
<h2>Editing Post for: <?php echo $name;?></h2>

<?php

$title = htmlentities($row[0]);
$text = $row[1];

?>
<form name="postEdit" method="post" action="postSave.php?projid=<?php echo $urlprojid;?>">

<table>
<tr>
	<th>Title:</th>
	<td><input type="text" size="80" name="title" value="<?php echo $title;?>"/></td>
</tr>
<tr>
	<th>Text:</th>
	<td rowspan="2"><textarea name="text" cols="80" rows="10"><?php echo $text;?></textarea></td>
</tr>
</table>

<input type="hidden" name="modified" value="1" />
<input type="hidden" name="postid" value="<?php echo $postid;?>" />

<input type="submit" value="Edit Post" />
</form>

<?php
?>



<?php emitBottom();?>