<?php
require_once('includes/globals.inc');

session_start('login');

unset($_SESSION['loggedIn']);
unset($_SESSION['userName']);
unset($_SESSION['userType']);

session_write_close();

emitTop('Peanut Butter -> Logged Out', '/pb/');

?>

<h2>You have been logged out.</h2>

<?php

if(isset($_GET['refer']))
{
	print('<p><a href="'.$_GET['refer'].'">Back to last page</a></p>');
}
?>
<a href="/pb/">Main Page</a>

<?php emitBottom();?>