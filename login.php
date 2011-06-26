<?php
require_once('includes/globals.inc');

$refer = $_GET['refer'];

$printForm = 1;

if(isset($_POST['loginName']) && isset($_POST['loginPass']))
{
	mysqlSetup();

	escapes('loginName', $_POST['loginName']);
	escapes('loginPass', $_POST['loginPass']);

	$sqlquery = "SELECT `category` FROM `pb_users` WHERE `name` = '$mloginName' AND "
		.	"`password` = '$mloginPass'";

	$result = mysql_query($sqlquery) or die('Invalid query: ' . mysql_error());

	if(!mysql_num_rows($result))
	{
		emitTop('Peanut Butter -> Login', $refer);
?>
<p><em>Invalid username/password.</em></p>
<?php
	}
	else
	{
		$printForm = 0;
		$row = mysql_fetch_row($result);
		session_start('login');

		$_SESSION['loggedIn'] = 1;
		$_SESSION['userName'] = $loginName;
		$_SESSION['userType'] = $row[0];

		session_write_close();

		emitTop('Peanut Butter -> Logged In', $refer);

?>
	<script type="text/javascript">
	document.location = "<?php echo $refer;?>";
	</script>

	<?php emitBottom();?>

<?php
		}
	mysql_close();
}
elseif(isset($_POST['loginName']) || isset($_POST['loginPass']))
{
?>
<p><em>Missing information.</em></p>
<?php
}

if($printForm)
{

emitTop('Peanut Butter -> Login Form', $refer);
?>
<form name="loginForm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?refer=<?php echo $refer;?>">
<h3>Please enter username and password</h3>

<table>
<tr>
	<th>Username:</th>
	<td><input type="text" name="loginName" size="15" /></td>
</tr>
<tr>
	<th>Password:</th>
	<td><input type="password" name="loginPass" size="15" /></td>
</tr>
</table>

<input type="submit" name="Login" value="Login" />
</form>

<?php
}
?>


<?php emitBottom();?>