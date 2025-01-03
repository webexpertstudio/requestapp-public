<?php
$agentid = $_SESSION['sess_user_id'];

// Zamena mysql_query sa mysqli_query
$reqagent = mysqli_query($dbconn, "SELECT * FROM member WHERE id='$agentid'") or die ("Baza nije dostupna!");

// Zamena mysql_fetch_array sa mysqli_fetch_array
while ($agentsdata = mysqli_fetch_array($reqagent)) {
    $agentname = $agentsdata["agent_name"];
    $agentpic = $agentsdata["agent_pic"];
    $acounter = $agentsdata["acounter"];
    $scounter = $agentsdata["scounter"];
    $agentstatus = $agentsdata["agent_status"];
    $agentphone = $agentsdata["agent_phone"];
    $agentmail = $agentsdata["agent_mail"];
    $statusdropbox .= '<option value="">-</option><option value="Quote Sent">Quote Sent</option><option value="Offer Reminder">Offer Reminder</option><option value="Booked">Booked</option><option value="Cancelled">Cancelled</option></select>';
}

// Zamena mysql_query sa mysqli_query
$statuscounter = mysqli_query($dbconn, "SELECT status FROM requests WHERE agentID='$agentid'");
$scounter = mysqli_num_rows($statuscounter);

// Zamena mysql_query sa mysqli_query
$assignedcounter = mysqli_query($dbconn, "SELECT status FROM requests WHERE agentID='$agentid' AND status='Assigned'");
$acounter = mysqli_num_rows($assignedcounter);

if ($agentsbuttonsdata["id"] == $agent) {
    $detailsview = "index.php?page=agentview&agentid=";
} else {
    $detailsview = "index.php?page=agentview&agentid=";
}
?>

<div class="agentdetails">
    <div class="agentboxdetails">
        <div class="agentbuttonleft" style="float:left">
            <img src="images/agents/<?php echo $agentpic; ?>">
        </div>
        <div class="agentbuttonrightdetails" style="float:right">
            <span style="font-size:16px;color:#506785; font-weight:bold;"><?php echo $agentname; ?></span><br/>
            <b>Status: <?php echo $agentstatus; ?></b><br>
            Phone number: <?php echo $agentphone ?><br>
            Email: <?php echo $agentmail ?>
            <div style="font-size: 11px; float: right; margin-top: -40px; text-align: right;">
                <span style="font-size:13px;font-weight: bold;">REQUESTS #</span><br>
                <div class="requestnumagent">
                    <div class="requestnumleft">Pending<br><span style="font-weight:bold; color:red"><?php echo $acounter ?></span></div>
                    <div class="requestnumright">Total<br><b><?php echo $scounter ?></b></div>
                    <div class="agentdeatilsbutton"></div>
                </div><br>
            </div>
        </div>
    </div>
</div>

<!--  PODACI O KLIJENTU -->

<div id="statusbuttons">
    <div style="float: left;padding: 2px;width: 65px;">Requests:</div> 
    <button onclick="location.href='index.php?page=agentview&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&acounter=<?php echo $acounter ?>&scounter=<?php echo $scounter ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">All</button>
    <button onclick="location.href='index.php?page=agentview&status=Assigned&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&acounter=<?php echo $acounter ?>&scounter=<?php echo $scounter ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">New</button>
    <button onclick="location.href='index.php?page=agentview&status=Quote Sent&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&acounter=<?php echo $acounter ?>&scounter=<?php echo $scounter ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Quote Sent</button>
    <button onclick="location.href='index.php?page=agentview&status=Booked&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&acounter=<?php echo $acounter ?>&scounter=<?php echo $scounter ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Booked</button>
    <button onclick="location.href='index.php?page=agentview&status=Cancelled&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&acounter=<?php echo $acounter ?>&scounter=<?php echo $scounter ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Cancelled</button><br>
</div>

<?php
require("tables-agent.php");

// Open table row
if (isset($_GET['rqopen'])) {
    ?>
    <script>
        $(document).ready(function(){
            $(".cat<?php echo $_GET['rqopen'] ?>").show();
        });
    </script>
<?php
}
?>