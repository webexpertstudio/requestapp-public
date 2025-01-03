<?php
unset($_SESSION['assign_changed']);
unset($_SESSION['mail_to_agent']);
unset($_SESSION['status_changed']);
$requestIDnote = $_GET['IDrequest'];

// Konekcija sa bazom
$host = 'localhost'; // Postavi na odgovarajuće vrednosti
$username = 'username';
$password = 'password';
$dbname = 'dbname';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Pripremljen upit za `requests` tabelu
$sql = "SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id = t1.agentID WHERE t1.requestID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $requestIDnote);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array(MYSQLI_ASSOC);

// Pripremljen upit za `notes` tabelu
$sql_notes = "SELECT t1.*, t2.* FROM notes t1 INNER JOIN member t2 ON t2.id = t1.agentID WHERE t1.requestID = ? ORDER BY noteID DESC";
$stmt_notes = $conn->prepare($sql_notes);
$stmt_notes->bind_param("i", $requestIDnote);
$stmt_notes->execute();
$note = $stmt_notes->get_result();
?>

<div class="requestdetailsbox" style="background:none">
    <div class="detailsframe">
        <div class="detailshead">
            Quote request # <?php echo $row['requestID']?>
            <div style="float:right;font-size:14px;padding:6px 0">Request from: <span style="color:#99BF82"><?php echo $row['request_from']?></span></div>
        </div>
        <div class="detailsbox1">
            <div class="detailsclientname">
                <div class="titleofbox">Client</div>
                Name: <b><?php echo $row['client_first_name']; echo $row['client_last_name']?></b><br>
                Email: <b><?php echo $row['client_email']?></b><br>
                Phone number: <b><?php echo $row['client_phone']?></b><br>
                Prefers to contacted by: <b><?php echo $row['contact_by']?></b><br>
                Request received: <b><?php echo $row['received_date']?></b>
            </div>
            <div class="detailsagentsnote">
                <div class="titleofbox">Agent notes</div>
                <div class="notetext">
                    <?php while($requestnotes = $note->fetch_assoc()) { 
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
        <script type="text/javascript">
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
        <div class="detailshead2" style="height:auto;display: table-cell; background:#fff ">
            <div style="float:left; width:50%;text-align: left;">
                <span style="font-size:14px">Assigned to:</span><br> 
                <span style="color:#5B8652"><b><?php echo $row['agent_name']?></b></span>
            </div>
            <div style="float:left; width:50%;text-align: right;">
                <span style="font-size:14px">Status:</span><br> 
                <?php
                if ($row['status']=="Cancelled") {
                ?>
                <span style="color:#B30505">
                    <b><?php echo $row['status']; if($row['cancel_type']!="") {echo '<br><span style="font-size:12px;color:black">('.$row['cancel_type'].')</span>';} ?></b>
                </span>
                <?php
                } else {
                ?>
                <span style="color:#5B8652">
                    <b><?php echo $row['status']; if($row['cancel_type']!="") {echo '<br><span style="font-size:12px;color:black">('.$row['cancel_type'].')</span>';} ?></b>
                </span>
                <?php
                }
                ?>
            </div>
        </div>
        <hr>
        <div class="detailshead2" style="height:auto;display: table-cell;  background:#fff ">
            <div style="float:left; width:50%;text-align: left;">
                <span style="font-size:14px">All itineraries:</span><br> 
                <span style="color:#5B8652">
                    <?php
                    $itinerarydataview = $conn->prepare("SELECT * FROM itineraries WHERE request_id=? ORDER BY status_id ASC");
                    $itinerarydataview->bind_param("i", $requestIDnote);
                    $itinerarydataview->execute();
                    $itinerarydataview_result = $itinerarydataview->get_result();
                    if($itinerarydataview_result->num_rows == 0) {
                        echo '<div style="width:200px;margin:0 auto;">No itineraries entered!</div>';
                    } else {
                        while($itinerarylist = $itinerarydataview_result->fetch_assoc()) {
                            echo $itinerarylist["itinerary_id"].'; ';
                        }
                    }
                    ?>
                </span>
            </div>
            <div style="float:left; width:50%;text-align: right;">
                <span style="font-size:14px">Booked itinerary:</span><br> 
                <span style="color:#5B8652">
                    <?php
                    $itinerarydatabooked = $conn->prepare("SELECT * FROM itineraries WHERE request_id=? AND itinerary_status='Booked' ORDER BY status_id ASC");
                    $itinerarydatabooked->bind_param("i", $requestIDnote);
                    $itinerarydatabooked->execute();
                    $itinerarydatabooked_result = $itinerarydatabooked->get_result();
                    if($itinerarydatabooked_result->num_rows == 0) {
                        echo '<div style="width:200px;margin:0 auto;">No booked itineraries!</div>';
                    } else {
                        while($itinerarylist = $itinerarydatabooked_result->fetch_assoc()) {
                            echo $itinerarylist["itinerary_id"].'; ';
                        }
                    }
                    ?>
                </span>
            </div>
        </div>
        <hr>
        <div class="detailshead2">Travel details<span style="text-align:right; float:right;font-size: 14px;">Request Type: <span style="color: #5B8652;font-size:18px"><?php echo $row['request_type'] ?></span></span></div>
        <script>
            function offeridchange(request) {
                leftPos = 0
                topPos = 0
                if (screen) {
                    leftPos = (screen.width / 2) - 125
                    topPos = (screen.height / 2) - 100
                }
                window.open('addofferid.php?id='+request,'popup','width=250,height=200,left='+leftPos+',top='+topPos+',scrollbars=0');
            }
            function bookedidchange(request) {
                leftPos = 0
                topPos = 0
                if (screen) {
                    leftPos = (screen.width / 2) - 125
                    topPos = (screen.height / 2) - 100
                }
                window.open('addbookedid.php?id='+request,'popup','width=250,height=200,left='+leftPos+',top='+topPos+',scrollbars=0');
            }
        </script>

        <!-- Static content here -->
    </div>
    <div style="text-align:right;"><button style="cursor:pointer" class="backbutton" type="button" onclick="location.href='index.php'">back to main page</button></div>
</div>

<?php
// Zatvori konekciju
$conn->close();
?>