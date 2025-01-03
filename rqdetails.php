<?php
unset($_SESSION['assign_changed']);
unset($_SESSION['mail_to_agent']);
unset($_SESSION['status_changed']);
$requestIDnote = $_GET['IDrequest'];

// Establish database connection
$servername = "localhost"; // Replace with your database server
$username = "alien_requestadm"; // Replace with your database username
$password = "o8^USIm]S05pd@$"; // Replace with your database password
$dbname = "alien_requestapp"; // Replace with your database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the character set
mysqli_query($conn, "SET NAMES 'UTF8'");

$req = mysqli_query($conn, "SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id=t1.agentID WHERE t1.requestID='$requestIDnote'");
$note = mysqli_query($conn, "SELECT t1.*, t2.* FROM notes t1 INNER JOIN member t2 ON t2.id=t1.agentID WHERE t1.requestID='$requestIDnote' ORDER BY noteID DESC");

$row = mysqli_fetch_array($req);
$idrequest = $ID;
?>

<div class="requestdetailsbox">
    <div class="detailsframe">
        <div class="detailshead">Quote request # <?php echo $row['requestID']?>
            <div style="float:right;font-size:14px;padding:6px 0">Request from: <span style="color:#99BF82"><?php echo $row['request_from']?></span></div>
        </div>

        <div class="detailsbox1">
            <div class="detailsclientname"><div class="titleofbox">Client</div>
                Name: <b><?php echo $row['client_first_name']; echo $row['client_last_name']?></b><br>
                Email: <b><?php echo $row['client_email']?></b><br>
                Phone number: <b><?php echo $row['client_phone']?></b><br>
                Prefers to contacted by: <b><?php echo $row['contact_by']?></b><br>
                Request received: <b><?php echo $row['received_date']?></b>
            </div>
            <div class="detailsagentsnote"><div class="titleofbox">Agent notes</div>
                <div class="notetext">
                    <?php while($requestnotes = mysqli_fetch_array($note)) {
                        echo $requestnotes['agent_name'].' ['.$requestnotes['notedate'].'] - '.$requestnotes['note'].'<br>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="changebutton">
            <button class="requestchange" id="requestchange" name="requestchange" onclick="showDiv()">Add new note</button>
        </div>

        <div class="addanotebox">
            <form action="index.php?page=addanote" method="POST">
                <input id="addnoteidrequest" type="hidden" name="addnoteidrequest" value="<?php echo $requestIDnote ?>">
                <textarea id="notetextbox" name="notetextbox" required></textarea>
                <button class="addnotesubmit" id="addnotesubmit" name="addnotesubmit" type="submit">Save a note</button>
            </form>
            <button class="canceladdnote" id="canceladdnote" name="canceladdnote" onclick="hideDiv()">Cancel</button>
        </div>
        <script type='text/javascript'>
            function showDiv() {
                $('.addanotebox').show('slow');
                $('.changebutton').hide();
                return false;
            }
            function hideDiv() {
                $('.addanotebox').hide('slow');
                $('.changebutton').show();
                return false;
            }
            $(document).ready(function() {
                $('.backbutton').show();
            });
        </script>

        <div class="detailshead2" style="height:auto;display: table-cell;">
            <div style="float:left; width:50%;text-align: left;">
                <span style="font-size:14px">Assigned to:</span><br> 
                <span style="color:#5B8652">
                    <b><?php echo $row['agent_name']?></b>
                </span>
            </div>

            <div style="float:left; width:50%;text-align: right;">
                <span style="font-size:14px">Status:</span><br>
                <?php
                if ($row['status'] == "Cancelled") {
                    echo '<span style="color:#B30505"><b>'.$row['status']; 
                    if($row['cancel_type'] != "") {
                        echo '<br><span style="font-size:12px;color:black">('.$row['cancel_type'].')</span>';
                    }
                    echo '</b></span>';
                } else {
                    echo '<span style="color:#5B8652"><b>'.$row['status']; 
                    if($row['cancel_type'] != "") {
                        echo '<br><span style="font-size:12px;color:black">('.$row['cancel_type'].')</span>';
                    }
                    echo '</b></span>';
                }
                ?>
            </div>
        </div>

        <hr>

        <div class="detailshead2" style="height:auto;display: table-cell;">
            <div style="float:left; width:50%;text-align: left;">
                <span style="font-size:14px">All itineraries:</span><br> 
                <span style="color:#5B8652">
                <?php
                $itinerarydataview = mysqli_query($conn, "SELECT * FROM itineraries WHERE request_id='$requestIDnote' ORDER BY status_id ASC");
                if (mysqli_num_rows($itinerarydataview) == 0) {
                    echo  '<div style="wight:200px;margin:0 auto;">No itineraries entered!</div>';
                } else {
                    while($itinerarylist = mysqli_fetch_assoc($itinerarydataview)) {
                        echo $itinerarylist["itinerary_id"].'; ';
                    }
                }
                ?></span>
            </div>

            <div style="float:left; width:50%;text-align: right;">
                <span style="font-size:14px">Booked itinerary:</span><br> 
                <span style="color:#5B8652">
                <?php
                $itinerarydatabooked = mysqli_query($conn, "SELECT * FROM itineraries WHERE request_id='$requestIDnote' AND itinerary_status='Booked' ORDER BY status_id ASC");
                if (mysqli_num_rows($itinerarydatabooked) == 0) {
                    echo  '<div style="wight:200px;margin:0 auto;">No booked itineraries!</div>';
                } else {
                    while($itinerarylist = mysqli_fetch_assoc($itinerarydatabooked)) {
                        echo $itinerarylist["itinerary_id"].'; ';
                    }
                }
                ?></span>
            </div>
        </div>
        <hr>
    </div>
</div>

<?php
mysqli_close($conn); // Close the database connection
?>