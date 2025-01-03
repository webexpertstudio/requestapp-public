<?php // Odjava kupca.

if(ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000, $params ["path"], $params ["domain"], $params ["secure"], $params ["httponly"]);
}
unset($_SESSION);
session_unset();
session_destroy();
?>
<div id="odjava">
<?php
echo "<h2>Logout sucessfuly</h2><br/>";
echo 'Login Again <a href="index.php">HERE</a>';
?>
</div>
