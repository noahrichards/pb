<?php

$printForm = TRUE;


if(!$_POST['host'] || !$_POST['user'] || !$_POST['pass']
	|| !$_POST['db'])
{
	if($_POST['host'] || $_POST['user'] || $_POST['pass']
		|| $_POST['db'])
		print("<h2>Missing form information.</h2>");
}
else
{
	$host = $_POST['host'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$db = $_POST['db'];

	mysql_connect($host, $user, $pass);
	mysql_select_db($db);

	if(!mysql_error())
	{
		$printForm = FALSE;
		$fp = fopen('../includes/mysql.inc','w');

		$contents = "<?php\n"
			.	"\n"
			.	'$dbhost = "'.$host."\";\n"
			.	'$dbusern = "'.$user."\";\n"
			.	'$dbpassw = "'.$pass."\";\n"
			.	"\n"
			.	'$dbname = "'.$db."\";\n"
			.	"?>\n";

		fwrite($fp, $contents);
		fclose($fp);

		print("File written.");
	}
	else
	{
		print(mysql_error());
	}
}


if($printForm)
{
?>
<h2>Enter mysql database information:</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
Hostname:<input type="text" name="host" /><br />
Username:<input type="text" name="user" /><br />
Password:<input type="text" name="pass" /><br />
Database name:<input type="text" name="db" /><br />
<input type="submit" value="Save Information" />
</form>
<?php
}

?>

