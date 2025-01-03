<?php
$idrequest = $_POST['addnoteidrequest'];
$agentid = $_SESSION['sess_user_id'];
$notedate = date("Y-m-d H:i:s");
$note = $_POST['notetextbox'];

// Zamenjujemo mysql_query sa mysqli_query
$sqlnote = "INSERT INTO notes (requestID, agentID, notedate, note) VALUES ('$idrequest', '$agentid', '$notedate', '$note')";

// Koristimo mysqli_query za izvršenje upita
if (!mysqli_query($dbconn, $sqlnote)) {
    die("Baza nije dostupna!");
}

unset($_SESSION['assign_changed']);
unset($_SESSION['mail_to_agent']);
$_SESSION['requestID'] = $idrequest;

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>