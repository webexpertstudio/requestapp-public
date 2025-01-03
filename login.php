<?php // Forma za prijavu kupaca.

if ($_SESSION['odobreno']=="da")
{
	header('Location: index.php');  // Ako je kupac već ulogovan, vrati na index.php.
}
else
{
echo '<div id="porukalogin">'.$_SESSION['porukalogin'].'</div>';  // Ako nije uspela prijava, prikaži odgovarajuću poruku iz sesije.
?>
<div class="loginbox">
<div class="loginformbox">  <form class="loginform"  id="form1" name="form1" method="post" action="logincheck.php">
<label for="user">Username: </label><br><input type="text" name="username" id="username" /><br>
<label for="pass">Password</label><br><input type="password" name="password" id="password" /><br>
 <button type="submit" name="button" id="button" />login</button>
</form></div>
<?php
}
?>

