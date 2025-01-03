<?php
if (isset($_GET['selecteddate']))
{
	$formatshow = 'm/d/Y';
	$reportfrom=date("Y-m-d", strtotime($_POST['reportfrom']));
	$reportto=date("Y-m-d", strtotime($_POST['reportto']));
	$reportfromshow= date ( $formatshow, strtotime ( $reportfrom ) ); 
	$reporttoshow= date ( $formatshow, strtotime ( $reportto ) ); 
}
else
{
	$format = 'Y-m-d';
	$formatshow = 'm/d/Y';
	$reportfrom = date ( "Y-m-d" );
	// - 7 days from today
	$reportfrom= date ( $format, strtotime ( '-7 day' . $reportfrom ) ); 
	$reportto=date("Y-m-d");
	$reportfromshow= date ( $formatshow, strtotime ( $reportfrom ) ); 
	$reporttoshow=date("m/d/Y");
}
?>
<div style="height:60px" class="reportingform">
<div style="width:40%;float:left;"><h1>REPORTING</h1></div>
<div style="width:60%;float:right;text-align:right"><h5>SELECT DATE RANGE</h5><form name="reportingquery" action="index.php?page=reporting&selecteddate" method="POST">
<label for="reportfrom">From</label><input type="text" class="from" name="reportfrom" value="<?php echo $reportfromshow ?>" required>
<label for="reportto">To</label><input type="text" class="to" name="reportto" value="<?php echo $reporttoshow ?>"  required>
<button class="backbutton" id="backbutton" type="submit" name="statusbutton">
<img id="statusimg<?php echo $ID ?>" style="margin:2px;" src="images/save1.png" />
</button>
</form>
</div></div>
<?php

$reqnum = mysql_query("SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id=t1.agentID WHERE t1.last_change BETWEEN '$reportfrom' AND '$reportto' GROUP BY t1.requestID ORDER BY t1.last_change DESC") or die ("Baza nije dostupna 3!");

//$reqnum = mysql_query("SELECT * FROM requests WHERE last_change BETWEEN '$reportfrom' AND '$reportto' GROUP BY requestID") or die ("Baza nije dostupna 3!");
$totalsum=0;
mysql_query("SET NAMES UTF8");
if(mysql_num_rows($reqnum) == 0)
{
echo  '<div class="norequests">No request</div>';
}
else
{
require ("includes/numofrows.php"); 

 ///////////////////

			
 /////////////////////////
?>

<div class="datatablereport">
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
	<tr style="font-size: 11px;border-bottom:2px solid #000; background:#526486; color:#fff">
		<th>ID</th>
		<th style="text-align:center;">QR Received</th>
		<th style="width:20px">Client</th>
		<th>Tracking</td>
		<th>Agent</td>
		<th style="text-align:center; ">Change</th>
		<th style="text-align:center; ">Change date</th>
				<th style="text-align:center;">Itinerary #</th>
				<th style="text-align:center;">Quote $</th>
			<th style="text-align:center;">Destination</th>
		<th style="text-align:center;">QR Status</th>
		<th style="text-align:center;color:#99BF82;">$ BOOKED</th>
	</tr>
	<?php

while($data = mysql_fetch_assoc($reqnum))
	{

			$ID=$data["requestID"];
			$agentname=$data["agent_name"];
			$Sender=$data["client_first_name"].' '.$data["client_last_name"];
			$Received=date("M d, Y", strtotime($data["received_date"]));
			$last_change=date("M d, Y", strtotime($data["last_change"] ));
			$Status=$data["status"];
			//if($data['status']=="Booked" || $data['status']=="Cancelled")
			//{$Status=$data["status"];} else {$Status="";}

			if($data['finalized_date']==NULL || $data['finalized_date']=="" || $data['finalized_date']==0 || $data['finalized_date']=="0000-00-00")
			{$Statusdate="";}
			else{$Statusdate=date("M d,Y", strtotime($data["finalized_date"]));}
		?>
			<tr style="font-weight:bold;background:#CAE6F1; ">
				<td><a href="index.php?page=rqdetails&IDrequest=<?php echo $ID ?>"><?php echo $ID ?></a></td>
				<td style="text-align:center;"><?php echo $Received ?></td>
				<td><?php echo $Sender ?></td>
				<td></td>
				<td><?php echo $agentname ?></td>
				<td></td>
				<td style="text-align:center;"><?php echo $last_change ?></td>
				<td></td>
					<td></td>
					<td></td>
					<?php if($Status=="Booked")
					{ 
						echo '<td style="text-align:center;width:80px;color:#2A9902;">'.$Status.'</td>';
					
					}
					elseif($Status=="Cancelled")
					{echo '<td style="text-align:center;width:80px;color:#5585B6;">'.$Status.'<br><span style="font-size:9px;">('.$data["cancel_type"].')</span></td>';}
					else
					{echo '<td style="text-align:center;width:80px;">'.$Status.'</td>';}
				?>
				
				
				
				<td></td>
			</tr>
				<!-- itinerary list -->
			
		<?php
		$idrequest=$ID;

	$itinerarydata = mysql_query("SELECT t1.*, t2.* FROM itineraries t1 INNER JOIN member t2 ON t2.id=t1.agent_id WHERE request_id='$idrequest' AND (change_date BETWEEN '$reportfrom' AND '$reportto') ORDER BY status_id ASC");

		mysql_query("SET NAMES UTF8");
	
			while($itdata = mysql_fetch_assoc($itinerarydata))
			{
				$last_change_itin=date("M d, Y", strtotime($itdata["change_date"] ));
				if ($itdata["itinerary_sum"]==NULL || $itdata["itinerary_sum"]=="" || $itdata["itinerary_sum"]==0)
					{$itinerarysum="";}
					else
					{$itinerarysum='$'.number_format($itdata["itinerary_sum"],2,",",".");}
				?>
				<tr style="font-weight:normal;">
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="background:#fff;"><?php echo $itdata['agent_name'] ?></td>
					<?php if($itdata['itinerary_status']=="Booked")
					{ $totalsum=$totalsum+$itdata['itinerary_sum'];
					?>
					<td style="text-align:center;background:#fff;color:#2A9902;font-weight:bold"><?php echo $itdata['itinerary_status'] ?></td>
					<td style="text-align:center;background:#fff;"><?php echo $last_change_itin ?></td>
					<td style="text-align:center;background:#fff;color:#2A9902;font-weight:bold"><?php echo $itdata['itinerary_id'] ?></td>
					<td style="text-align:right;background:#fff;"><?php echo $itinerarysum ?></td>
					<td></td>
					<td></td>
					
					<td style="text-align:right;background:#fff;color:#2A9902;font-weight:bold">$<?php echo $itinerarysum ?></td>
					<?php
					}
					else
					{
						?>
						<td style="text-align:center;background:#fff;"><?php echo $itdata['itinerary_status'] ?></td>
					<td style="text-align:center;background:#fff;"><?php echo $last_change_itin ?></td>
					<td style="text-align:center;background:#fff;"><?php echo $itdata['itinerary_id'] ?></td>
						<td style="text-align:right;background:#fff;"><?php echo $itinerarysum ?></td>
					<td></td>
					<td></td>
					
					<td style="background:#fff"></td>
						<?php
						
					}

					?>
				</tr>
				
			<?php
			}


		
		}
		$totalsum=number_format($totalsum,2,",",".");
		?>
 </table>
 </div>
<?php
	
require ("includes/pagination.php"); 
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;margin-top:15px;">
	<tr style="font-size: 14px;border-top:2px solid #000">		
					<td style="background:#F1C232; text-align:right;"><?php echo 'TOTAL BOOKED SUM:   $'.$totalsum ?></td>
					
	</tr>
 </table>
 <?php
}
?>