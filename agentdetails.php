<script type="text/javascript">
              $(document).ready(
            function() {
               $("div.notification").fadeIn();
               setTimeout(function() {
                  $("div.notification").fadeOut("slow");
               }, 3000); 
          });  
</script>
<?php
$agentid = $_GET['agentid'];
$agentname = $_GET['agentname'];
$agentpic = $_GET['agentpic'];
$pendingrq = $_GET['pendingrq'];
$totalrq = $_GET['totalrq'];
$agentstatus = $_GET['agentstatus'];
$agentphone = $_GET['agentphone'];
$agentmail = $_GET['agentmail'];

// Zamena mysql_query sa mysqli_query
$agents = mysqli_query($dbconn, "SELECT * FROM member WHERE NOT id='0'");
if (!$agents) {
    die("Query failed: " . mysqli_error($conn));
}

$agent = $_SESSION['sess_user_id'];
while($agentsdata = mysqli_fetch_array($agents))	
	{
	$agentsdropbox.='<option value="'.$agentsdata["agent_name"].'">'.$agentsdata["agent_name"].'</option>';
	}
$agentsdropbox.='</select>';
$statusdropbox.='<option value="">-</option><option value="Quote Sent">Quote Sent</option><option value="Offer Reminder">Offer Reminder</option><option value="Booked">Booked</option><option value="Cancelled">Cancelled</option></select>';
?>

<script type='text/javascript'>
$(document).ready(function() {
$('.backbutton').show();
});
</script>
<div class="agentdetails">
 <div class="agentboxdetails" >
<div class="agentbuttonleft" style="float:left">
<img src="images/agents/<?php echo $agentpic; ?>"></div>
<div class="agentbuttonrightdetails" style="float:right"><span style="font-size:16px;color:#506785; font-weight:bold;"><?php echo $agentname; ?></span><br/><b>Status: <?php echo $agentstatus; ?></b><br>Phone number: <?php echo $agentphone ?><br>Email: <?php echo $agentmail ?><div style="font-size: 11px; float: right; margin-top: -40px; text-align: right;"><span style="font-size:13px;font-weight: bold;">REQUESTS #</span><br><div class="requestnumagent"><div class="requestnumleft">Pending<br><span style="font-weight:bold; color:red"><?php echo $pendingrq ?></span></div><div class="requestnumright">Total<br><b><?php echo $totalrq ?></b></div><div class="agentdeatilsbutton"></div></div><br></div></div>
   </div>
</div>
<!--  PODACI O KLIJENTU    --> 

<div id="statusbuttons">
<div style="float: left;padding: 2px;width: 65px;">Requests:</div> 
<button onclick="location.href='index.php?page=agentdetails&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">All</button>
<button onclick="location.href='index.php?page=agentdetails&status=Assigned&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">New</button>
<button onclick="location.href='index.php?page=agentdetails&status=Quote Sent&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Quote Sent</button>
<button onclick="location.href='index.php?page=agentdetails&status=Booked&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Booked</button>
<button onclick="location.href='index.php?page=agentdetails&status=Cancelled&agentid=<?php echo $agentid ?>&agentname=<?php echo $agentname ?>&agentpic=<?php echo $agentpic ?>&pendingrq=<?php echo $pendingrq ?>&totalrq=<?php echo $totalrq ?>&agentstatus=<?php echo $agentstatus ?>&agentphone=<?php echo $agentphone ?>&agentmail=<?php echo $agentmail ?>'">Cancelled</button><br>
</div>
<?php
require ("tables-manager-viewother.php");


// Open table row

if (isset($_GET['rqopen']))
{
	?>
<script>
	$(document).ready(function(){
        $(".cat<?php echo $_GET['rqopen'] ?>").show();
    });
</script>
<?php
}
?>