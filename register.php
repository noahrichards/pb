<?php
require_once('includes/globals.inc');

$printForm = 1;

if(isset($_POST['regName']) && isset($_POST['regPass'])
	&& isset($_POST['regPass2']))
{
	if($_POST['regPass'] != $_POST['regPass2'])
	{
		$tempName = $_POST['regName'];
		emitTop('Peanut Butter -> Registration', $_SERVER['PHP_SELF']);
?>
<p><em>Passwords do not match.</em></p>
<?php
	}
	else
	{
		mysqlSetup();

		$regName = mysql_escape_string($_POST['regName']);
		$regPass = mysql_escape_string($_POST['regPass']);

		$sqlquery = "SELECT `category` FROM `pb_users` WHERE `name` = '$regName'";

		$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

		if(mysql_num_rows($result))
		{
			emitTop('Peanut Butter -> Registration', $_SERVER['PHP_SELF']);
	?>
	<p><em>Username already exists.</em></p>
	<?php
		}
		else
		{
			$printForm = 0;
			$sqlquery = "INSERT INTO `pb_users` (`name`,`password`,`category`,`created`) VALUES "
				.	"('$regName','$regPass','".NORMAL."',NOW())";

			$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

			session_start('login');

			$_SESSION['loggedIn'] = 1;
			$_SESSION['userName'] = $_POST['regName'];
			$_SESSION['userType'] = NORMAL;

			session_write_close();

			emitTop('Peanut Butter -> Registration', $_SERVER['PHP_SELF']);

	?>

		<h2>Registration</h2>

		<h3>You have successfully registered and are now logged in.</h3>

	<?php
		}
	}

}
elseif(isset($_POST['regName']) || isset($_POST['regPass'])
	|| isset($_POST['regPass2']))
{
?>
<p><em>Missing information.</em></p>
<?php
}

if($printForm)
{

emitTop('Peanut Butter -> Registration', $_SERVER['PHP_SELF']);

?>

<h2>Registration</h2>

<p>You must register to post comments to projects.</p>
<p><em>NOTE: Do <b>not</b> use your Infineon passwords here.</em></p>

<form name="registerForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<h3>Please enter username and password</h3>

<table>
<tr>
	<th>Username:</th>
	<td><input type="text" name="regName" size="15" value="<?php echo $tempName;?>" /></td>
</tr>
<tr>
	<th>Password:</th>
	<td><input type="password" name="regPass" size="15" /></td>
</tr>
<tr>
	<th>Password (again):</th>
	<td><input type="password" name="regPass2" size="15" /></td>
</table>

<input type="submit" name="Login" value="Login" />
</form>

<?php
}

	emitBottom();
?>
