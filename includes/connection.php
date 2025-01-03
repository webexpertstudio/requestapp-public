<?php //Konekcija na bazu podataka.
ini_set('display_errors', '0');
$server = "localhost";
$user = "root";
$pass = "root";
$db = "requestapp";

$dbconn = new mysqli($server, $user, $pass, $db);

if ($dbconn->connect_error) {
    die("Baza nije dostupna: " . $dbconn->connect_error);
}

$dbconn->set_charset("utf8");

?>