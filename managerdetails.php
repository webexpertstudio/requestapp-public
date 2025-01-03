<script type="text/javascript">
$(document).ready(function() {
    $("div.notification").fadeIn();
    setTimeout(function() {
        $("div.notification").fadeOut("slow");
    }, 3000);
});
</script>
<?php
require("includes/connection.php"); // Konekcija na bazu podataka

$agentid = mysqli_real_escape_string($connection, $_GET['agentid']);
$agentname = htmlspecialchars($_GET['agentname']);
$agentpic = htmlspecialchars($_GET['agentpic']);
$pendingrq = intval($_GET['pendingrq']);
$totalrq = intval($_GET['totalrq']);
$agentstatus = htmlspecialchars($_GET['agentstatus']);
$agentphone = htmlspecialchars($_GET['agentphone']);
$agentmail = htmlspecialchars($_GET['agentmail']);
$agent = $_SESSION['sess_user_id'];

// Dohvatanje agenata iz baze
$query = "SELECT * FROM member WHERE NOT id = '0'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}

$agentsdropbox = '<select>';
while ($agentsdata = mysqli_fetch_assoc($result)) {
    $agentsdropbox .= '<option value="' . htmlspecialchars($agentsdata["agent_name"]) . '">' . htmlspecialchars($agentsdata["agent_name"]) . '</option>';
}
$agentsdropbox .= '</select>';

$statusdropbox = '<select>';
$statusdropbox .= '<option value="">-</option>';
$statusdropbox .= '<option value="Quote Sent">Quote Sent</option>';
$statusdropbox .= '<option value="Offer Reminder">Offer Reminder</option>';
$statusdropbox .= '<option value="Booked">Booked</option>';
$statusdropbox .= '<option value="Cancelled">Cancelled</option>';
$statusdropbox .= '</select>';
?>
<script type='text/javascript'>
$(document).ready(function() {
    $('.backbutton').show();
});
</script>
<div class="agentdetails">
    <div class="agentboxdetails">
        <div class="agentbuttonleft" style="float:left">
            <img src="images/agents/<?php echo $agentpic; ?>">
        </div>
        <div class="agentbuttonrightdetails" style="float:right">
            <span style="font-size:16px;color:#506785; font-weight:bold;"><?php echo $agentname; ?></span><br/>
            <b>Status: <?php echo $agentstatus; ?></b><br>
            Phone number: <?php echo $agentphone ?><br>
            Email: <?php echo $agentmail ?><br>
            <div style="font-size: 11px; float: right; margin-top: -40px; text-align: right;">
                <span style="font-size:13px;font-weight: bold;">REQUESTS #</span><br>
                <div class="requestnummanager">
                    <div class="requestnumleft">Pending<br>
                        <span style="font-weight:bold; color:red"><?php echo $pendingrq ?></span>
                    </div>
                    <div class="requestnumright">Total<br><b><?php echo $totalrq ?></b></div>
                    <div class="agentdeatilsbutton"></div>
                </div><br>
            </div>
        </div>
    </div>
</div>
<!--  PODACI O KLIJENTU    --> 
<div id="statusbuttons">
    <div style="float: left;padding: 2px;width: 65px;">Requests:</div> 
    <button onclick="location.href='index.php?page=managerdetails&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">All</button>
    <button onclick="location.href='index.php?page=managerdetails&status=Assigned&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">New</button>
    <button onclick="location.href='index.php?page=managerdetails&status=Quote Sent&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Quote Sent</button>
    <button onclick="location.href='index.php?page=managerdetails&status=Booked&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Booked</button>
    <button onclick="location.href='index.php?page=managerdetails&status=Cancelled&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Cancelled</button><br>
</div>
<?php
require("tables-manager-viewother.php");

// Otvaranje reda tabele
if (isset($_GET['rqopen'])) {
    ?>
    <script>
        $(document).ready(function(){
            $(".cat<?php echo intval($_GET['rqopen']) ?>").show();
        });
    </script>
    <?php
}
?>