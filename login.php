<?php // Forma za prijavu kupaca.

// Proverite da li je 'odobreno' definisan pre nego što mu pristupite
if (isset($_SESSION['odobreno']) && $_SESSION['odobreno'] == "da") {
    header('Location: index.php');  // Ako je kupac već ulogovan, vrati na index.php.
} else {
    // Proverite da li je 'porukalogin' definisan pre nego što mu pristupite
    $porukalogin = isset($_SESSION['porukalogin']) ? $_SESSION['porukalogin'] : '';
    echo '<div id="porukalogin">' . $porukalogin . '</div>';  // Ako nije uspela prijava, prikaži odgovarajuću poruku iz sesije.
?>
<div class="loginbox">
<div class="loginformbox">  
    <form class="loginform"  id="form1" name="form1" method="post" action="logincheck.php">
        <label for="user">Username: </label><br><input type="text" name="username" id="username" /><br>
        <label for="pass">Password</label><br><input type="password" name="password" id="password" /><br>
        <button type="submit" name="button" id="button" />login</button>
    </form>
</div>
<?php
}
?>
