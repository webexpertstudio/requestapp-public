<?php
// Start session
session_start();
require ("includes/connection.php");  // Ensure this file uses mysqli for database connection

$agentid = $_SESSION['sess_user_id'];
$idrequest = $_GET['IDrequest'];
$assignedto = $_GET['agentchange'];
$statusdate = date("Y-m-d");
$status = "Assigned";

// Use mysqli_query instead of mysql_query
$agentidfind = mysqli_query($connection, "SELECT * FROM member WHERE agent_name='$assignedto'");
$agentsdataselected = mysqli_fetch_array($agentidfind);
$agentidselected = $agentsdataselected["id"];
$changes = 'Request #'.$idrequest.' assigned to '.$assignedto;

$reqstatus = mysqli_query($connection, "SELECT * FROM requests WHERE requestID='$idrequest'");
while($data = mysqli_fetch_assoc($reqstatus)) {
    if ($data['status'] == "New") {
        // Use mysqli_query instead of mysql_query
        $statuschanging = mysqli_query($connection, "UPDATE requests SET agentID='$agentidselected', status='$status', last_change='$statusdate' WHERE requestID='$idrequest'");
    } else {
        // Use mysqli_query instead of mysql_query
        $statuschanging = mysqli_query($connection, "UPDATE requests SET agentID='$agentidselected', last_change='$statusdate' WHERE requestID='$idrequest'");
    }
}

// Insert log entry using mysqli_query
$logstats = "INSERT INTO log_stats (request_id, agent_id, change_date, change_desc) VALUES ('$idrequest', '$agentid', '$statusdate', '$changes')";
mysqli_query($connection, $logstats);

// Redirect back to the referring page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>