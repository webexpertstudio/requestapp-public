<?php
// Start session
session_start();
require ("includes/connection.php");  // Ensure connection.php uses mysqli for the database connection

$agentid = $_SESSION['sess_user_id'];
$statusdate = date("Y-m-d");
$idrequest = $_GET['IDrequest'];
$itinerarysum = str_replace(array('.', ','), array('', '.'), $_POST['itinerarysum']); 
$itineraryid = $_POST['itineraryid'];
$statusid = $_POST['statusid'];

///////////////////////////////////////////// NEW ITINERARY
switch ($_GET['change']) {
    case "new":
        $itinerarystatus = "Quote Sent";
        $newitinerarydata = "INSERT INTO itineraries (itinerary_id, request_id, agent_id, itinerary_date, itinerary_status, itinerary_sum, change_date) 
                             VALUES ('$itineraryid', '$idrequest', '$agentid', '$statusdate', '$itinerarystatus', '$itinerarysum', '$statusdate')";
        mysqli_query($connection, $newitinerarydata); // Use mysqli_query instead of mysql_query

        ///////////////////////////////////////////// CHANGE GLOBAL REQUEST STATUS TO "QUOTE SENT"
        $statusfind = mysqli_query($connection, "SELECT status FROM requests WHERE requestID='$idrequest'");
        while($row = mysqli_fetch_assoc($statusfind)) {
            if ($row['status'] == "New" || $row['status'] == "Assigned") {
                $updatestatus = "UPDATE requests SET status='$itinerarystatus', last_change='$statusdate' WHERE requestID='$idrequest'";
                mysqli_query($connection, $updatestatus); // Use mysqli_query instead of mysql_query

                $changes = 'Request  #'.$idrequest.' change request status to "Quote Sent"';
                $logstats = "INSERT INTO log_stats (request_id, agent_id, change_date, change_desc) VALUES ('$idrequest', '$agentid', '$statusdate', '$changes')";
                mysqli_query($connection, $logstats); // Use mysqli_query instead of mysql_query
            }
        }
        break;

    case "update":
        $statusid = $_POST['statusid'];
        $itinerarystatus = $_POST['itinerarystatus'];

        if ($itinerarystatus == "Quote Sent") {
            $addstatus = "UPDATE itineraries SET itinerary_id='$itineraryid', itinerary_status='$itinerarystatus', itinerary_sum='$itinerarysum', change_date='$statusdate' WHERE status_id='$statusid'";
            mysqli_query($connection, $addstatus); // Use mysqli_query instead of mysql_query

            $changes = 'Updated itinerary  #'.$itineraryid.' status to '.$itinerarystatus;
            $logstats = "INSERT INTO log_stats (request_id, agent_id, change_date, change_desc) VALUES ('$idrequest', '$agentid', '$statusdate', '$changes')";
            mysqli_query($connection, $logstats); // Use mysqli_query instead of mysql_query
        } else { ///////////////////////////////////////////// UPDATE BOOKED
            $addstatus = "UPDATE itineraries SET itinerary_id='$itineraryid', itinerary_status='$itinerarystatus', itinerary_sum='$itinerarysum', change_date='$statusdate', finalized_date='$statusdate' WHERE status_id='$statusid'";
            mysqli_query($connection, $addstatus); // Use mysqli_query instead of mysql_query

            if ($itinerarystatus == "Booked") { ///////////////////////////////////////////// UPDATE GLOBAL REQUEST STATUS
                $updatestatus = "UPDATE requests SET finalized_date='$statusdate', status='$itinerarystatus', last_change='$statusdate' WHERE requestID='$idrequest'";
                mysqli_query($connection, $updatestatus); // Use mysqli_query instead of mysql_query

                $changes = 'Updated itinerary  #'.$itineraryid.' status to '.$itinerarystatus;
                $logstats = "INSERT INTO log_stats (request_id, agent_id, change_date, change_desc) VALUES ('$idrequest', '$agentid', '$statusdate', '$changes')";
                mysqli_query($connection, $logstats); // Use mysqli_query instead of mysql_query
            }
        }
        break;

    case "cancel":
        $rqstatus = "Cancelled";
        $canceltype = $_POST['canceltype'];
        $updatestatus = "UPDATE requests SET status='$rqstatus', cancel_type='$canceltype', last_change='$statusdate' WHERE requestID='$idrequest'";
        mysqli_query($connection, $updatestatus); // Use mysqli_query instead of mysql_query

        $changes = 'Cancelled request #'.$idrequest.', cancellation type: '.$canceltype.' by '.$agentid;
        $logstats = "INSERT INTO log_stats (request_id, agent_id, change_date, change_desc) VALUES ('$idrequest', '$agentid', '$statusdate', '$changes')";
        mysqli_query($connection, $logstats); // Use mysqli_query instead of mysql_query
        break;
}

if (isset($_GET['tableview'])) {
    header('Location: index.php?rqopen='.$idrequest.'');
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;
?>