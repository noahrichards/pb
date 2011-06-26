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
	emitTop('Peanut Butter -> Posts -> New Post', $_SERVER['PHP_SELF']."?projid=$urlprojid");

	if($name == '')
	{
		print('<h3>Project not found.</h3>');
		emitBottom();
		die();
	}

}
else
{
	errAndDie('Project not specified.');
}

if($userType == VISITOR)
{
	errAndDie('Sorry, visitors may not post.');
}

if($isNews && ($userType != ADMIN && $userType != SITEADMIN))
{
	errAndDie('Sorry, only admins may create news posts.');
}

?>
<h2>New Post for: <?php echo $name;?></h2>

<form name="postNew" method="post" action="postSave.php?projid=<?php echo $urlprojid;?>">

<table>
<tr>
	<th>Title:</th>
	<td><input type="text" size="80" name="title" /></td>
</tr>
<tr>
	<th>Text:</th>
	<td rowspan="2"><textarea name="text" cols="80" rows="10"></textarea></td>
</tr>
</table>

<input type="submit" value="Add Post" />
</form>

<?php emitBottom();?>