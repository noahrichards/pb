<?php
set_magic_quotes_runtime(0);

require_once('mysql.inc');
require_once('sqlMatch.inc');

function mysqlSetup()
{
	global $dbhost, $dbusern, $dbpassw, $dbname;

	mysql_connect($dbhost, $dbusern, $dbpassw) or die(mysql_error());
	mysql_select_db($dbname);
}

function emitTop($title, $refer)
{
	global $emitted;
	if($emitted)
		return;
	global $loginHTML, $logoutHTML, $loggedIn, $userType, $userName;


	$loggedIn = 0;
	$userType = VISITOR;
	$userName = '';

	if(@session_start('login'))
	{
		if($_SESSION['loggedIn'] == 1)
		{
			$loggedIn = 1;
			$userType = $_SESSION['userType'];
			$userName = $_SESSION['userName'];
		}
		session_write_close();
	}


	//Content-type and DOCTYPE

	header("Content-type: text/html");

	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ');
	print('"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
	print("\n");
	print('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');


	//head section

?>

<head>
	<title><?php echo $title;?></title>
	<link rel="shortcut icon" href="favicon.ico" />
	<style type="text/css">
		@import url("/pb/style/site.css");
	</style>

</head>

<body>

<div id="top">

<div id="header">
<p>&nbsp;<img src="images/pb.bmp" alt="Peanut Butter" /><a href="./">Peanut.Butter</a></p>
</div>

<div id="bar">
<div id="login">

<?php

$loginHTML = '<a href="login.php?refer='.urlencode($refer).'">Login</a>';
$registerHTML = '<a href="register.php">Register</a>';
$logoutHTML = '<a href="logout.php?refer='.urlencode($refer).'">Logout</a>';
$accountHTML = '<a href="accountManagement.php">Account</a>';


	if(!$loggedIn)
	{
?>
You are not logged in. <?php echo $loginHTML.'&nbsp;|&nbsp;'.$registerHTML;?></div>
<?php
	}
	else
	{
?>
You are logged in as <?php echo $userName.':&nbsp;'.$logoutHTML.'&nbsp;|&nbsp;'.$accountHTML;?></div>
<?php
	}
$pb = 'PB Menu:&nbsp;';
$newslink = '<a href="news.php">News</a>';
$projlink = '<a href="projectOverview.php">Projects</a>';
$lpostslink = '<a href="latestPosts.php">Latest Posts</a>';
$pmanagelink = '<a href="projectManagement.php">Admininstrators</a>';

$linkarray = array($newslink,$projlink,$lpostslink);

if($userType == ADMIN || $userType == SITEADMIN)
	array_push($linkarray, $pmanagelink);

?>

<div id="menu">
<?php
	print($pb.join('&nbsp;|&nbsp;',$linkarray)."\n");
?>
</div>
</div>
<div id="location"><?php echo $title?></div>
</div>

<?php

	$emitted = 1;


}

function emitBottom()
{
?>
<br />
<div id="linkbuttons">
<img src="images/graypix.bmp" alt="----------" width="75%" height="1"/>
	<br />
	&copy;2005 Noah Richards
	<br />
	Peanut Butter is free software released under the <a href="http://www.gnu.org/licenses/gpl.txt">GNU Public License</a>
	<!--
	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License as published by
	    the Free Software Foundation; either version 2 of the License, or
	    (at your option) any later version.

	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.

	    You should have received a copy of the GNU General Public License
	    along with this program; if not, write to the Free Software
    	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    -->
	<br />
	<a href="http://validator.w3.org/check?uri=referer"><img
		src="http://www.w3.org/Icons/valid-xhtml10"
		alt="Valid XHTML 1.0!" height="31" width="88" /></a>
	<a href="http://www.spreadfirefox.com/?q=affiliates&amp;id=0&amp;t=64">
	<img border="0" alt="Get Firefox!" title="Get Firefox!" src="http://sfx-images.mozilla.org/affiliates/Buttons/110x32/get.gif"/>
	</a>
</div>
</body>
</html>
<?php
}


function textToHTML($text)
{
	$text = preg_replace("/(\n)(\s*\n)+/m", "\n", $text);
	$text = preg_replace("/\s+$/", '', $text);
	$text = preg_replace("/\b_([^_\n]+)_\b/m", '<u>\1</u>', $text);
	$text = preg_replace('/\[a\](.+?)\[\/a\]/m', "<a href=\"\\1\">\\1</a>", $text);
	$text = preg_replace("/~\r\n/m", "<br />", $text);
	$text = preg_replace('/\[([biu])\](.+?)\[\/\1\]/m', "<\\1>\\2</\\1>", $text);

	$text = str_replace("\n","</p>\n<p>",$text);

	return $text;
}

/**
 * Given a variable name and data, processes this variable into the global namespace.
 * Meant to be used to process GET and POST data.  Populates three variables into
 * the global namespace:
 * 1) A variable with the variable name given.  This can be used if this webserver
 *		has magic quotes enabled (using stripslashes()).
 * 2) A variable escaped for url use (using urlencode()), named by preceding the
 *		given variable name with "url"
 * 3) A variable escaped for use with mysql (escaped with mysql_escape_string()),
 *		named by preceding the given variable name with "m"
 *
 * @param $varname the intended name of the variable
 * @param $var the data to use to create the new variables
 * @post $$varname, $"url$varname", and $"m$varname" are all set
 */
function escapes($varname, $var)
{
	$toURLName = "url$varname";
	$toMYSQLName = "m$varname";
	global $$toURLName, $$toMYSQLName, $$varname;

	if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
		$var = stripslashes($var);

	$$toURLName = urlencode($var);
	$$toMYSQLName = mysql_escape_string($var);
	$$varname = $var;
}

function getProjInfoFromID($id)
{
	$id = mysql_escape_string($id);

	if($id == -1)
		return array("News",'');

	mysqlSetup();

	$sqlquery = "SELECT `name`,`owner` FROM `pb_projects` WHERE `id` = '$id'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	$count = mysql_num_rows($result);
	$row = mysql_fetch_row($result);

	if($count > 0)
		return $row;
	else
		return array('','');
}

function projectExists($name)
{
	$mname = mysql_escape_string($name);

	if($name == '')
		return FALSE;
	mysqlSetup();

	$sqlquery = "SELECT `name` FROM `pb_projects` WHERE `name` = '$mname'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());
	$count = mysql_num_rows($result);

	return $count > 0;
}

function isNews($id)
{
	if($id == -1)
		return TRUE;
	return FALSE;
}

function errAndDie($err)
{
	print("<h3>$err</h3>\n");
	emitBottom();
	die();
}

function emitPost($post, $showlink = 1)
{
	global $userName;

	$postid = $post['id'];
	$urlpostid = urlencode($postid);
?>
	<div class="post">
	<div class="title"><?php echo $post['title'];?>
<?php
	if($post['user'] == $userName)
	{
		$urlname = urlencode($post['name']);

		print('<div class="editAndDelete">');

		print("<a href=\"postEdit.php?postid=$urlpostid\">Edit Post</a> ");
		print("<a href=\"postConfirmDelete.php?postid=$urlpostid\">Delete Post</a>");

		print('</div>');
	}

?></div>
	<div class="info">
	<p><b>Written By:</b> <?php echo $post['user'];?></p>
	<p><b>Created:</b> <?php echo $post['added'];?>, <b>Modified:</b> <?php echo $post['modified'];?></p>
	</div>

	<?php echo $post['text'];?>

	<?php
	if($showlink)
	{?>
	<em class="permalink"><a href="postView.php?<?php echo "postid=$urlpostid"?>">permalink</a></em>
	<?php } ?>
	</div>
<?php
	return;
}

function cleanValues($array)
{
	$cleaned = array();

	foreach($array as $key=>$item)
	{
		$item = htmlentities($item, ENT_QUOTES);

		if($key == 'text' || $key == 'notes' || $key == 'description')
			$item = '<p>'.textToHTML($item).'</p>';

		$cleaned[$key] = $item;
	}

	return $cleaned;
}




define('NUM_ENTRIES', 10);

//User types enum
define('VISITOR', 'visitor');
define('NORMAL', 'normal');
define('ADMIN', 'admin');
define('SITEADMIN', 'siteadmin');

//Status enum
$statusEnum[1] = 'In Progress';
$statusEnum[2] = 'Completed';
$statusEnum[3] = 'On Hold';
$statusEnum[4] = 'Cancelled';
$statusEnum[5] = 'Pending (see note)';



?>