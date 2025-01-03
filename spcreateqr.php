<?php
//require ("includes/connection.php");  // Poziva kod za konekciju na bazu podataka.
//mysql_query("SET character_set_results=utf8", $request);
//mysql_select_db('alien_requestapp', $request);
//mysql_query("set names 'utf8'",$request);
ini_set('display_errors', '0');
$request = mysqli_connect('localhost', 'alien_requestadm', 'o8^USIm]S05pd@$', 'alien_requestapp');

// Proverava konekciju
if (!$request) {
    die('Konekcija na bazu nije uspela: ' . mysqli_connect_error());
}

// Postavljanje karakter seta
mysqli_set_charset($request, 'utf8');


$agentID="0";
$requesttype=$_POST['requesttype'];
$checkindateinput= date("Y-m-d", strtotime($_POST['checkindate']));
$checkoutdateinput = date("Y-m-d", strtotime($_POST['checkoutdate']));
$flexibledatesinput = $_POST['datesflexible'];
$otherconvenientdatesinput = $_POST['otherconvenientdates'];
$adultsinput = $_POST['adultcount'];
$childreninput = $_POST['childcount'];
$child1ageinput = $_POST['childage1'];
$child2ageinput = $_POST['childage2'];
$child3ageinput = $_POST['childage3'];
$child4ageinput = $_POST['childage4'];
$child5ageinput = $_POST['childage5'];
$child6ageinput = $_POST['childage6'];
$child7ageinput = $_POST['childage7'];
$child8ageinput = $_POST['childage8'];
$child9ageinput = $_POST['childage9'];
$selectedresortinput = $_POST['selectedresort'];
$additionalresort1input = $_POST['additionalresort1'];
$additionalresort2input = $_POST['additionalresort2'];
$additionalresort3input = $_POST['additionalresort3'];
$selectedlodginginput = "";
$lodgingoptioninput = $_POST['lodginoption'];
$lodgingamenitiesinput = $_POST['lodgingamenities'];
$liftticketselection = $_POST['Liftticketsradio'];
$liftticketdateinput = $_POST['liftticketdateinput'];
$lessonsinput = $_POST['lessonsinput'];
$otheractivitiesinput = $_POST['otheractivitiesinput'];
$equipmentinput = $_POST['equipmentinput'];
$rentacarinput = $_POST['rentacar'];
$airtransportinput = $_POST['airtransport'];
if ($_POST['airdeparturedate']=="")
{$airdeparturedateinput="";}
else
{$airdeparturedateinput = date("Y-m-d", strtotime($_POST['airdeparturedate']));}
if ($_POST['airreturndate']=="")
{$airreturndateinput="";}
else
{$airreturndateinput = date("Y-m-d", strtotime($_POST['airreturndate']));}
$departureairportinput = $_POST['departureairport'];
$arrivalairportinput = $_POST['arrivalairport'];
$transportationfromtoairportinput = $_POST['transportationfromtoairport'];
$transportationnoteinput = $_POST['transportationnote'];
$firstnameinput = $_POST['firstname'];
$lastnameinput = $_POST['lastname'];
$emailinput = $_POST['email'];
$phoneinput = $_POST['PhoneNumber'];
$additionalnote = $_POST['additionalnote'];
$contactwayinput = $_POST['contactway'];
$provera = $_POST['provera'];
$subscribe = $_POST['subscribe'];
$confirm = "1";
$receiveddate= date("Y-m-d");
$subcribedate = date("Y-m-d");
$subcribetime = date("H:i:s");
$ipsubscribe = $_SERVER['REMOTE_ADDR'];
$requestfrom = 'SnowPak';
$status="New";
$toagent ='tanya@alpineadventures.net';
$sessiontrack =  (isset($_POST['SESSION_KEY']) && $_POST['SESSION_KEY'] )?"'".$_POST['SESSION_KEY']."'":"null";

$subject = 'SnowPak - Quote Request';

$headers = "From: Alpine Adventures <noreply@alpineadventures.net>\r\n";
$headers .= "BCC: lanavujicic@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$sql = "INSERT INTO requests (agentID,request_type,check_in,check_out,dates_flexibility,other_dates,adults,children,child1,child2,child3,child4,child5,child6,child7,child8,child9,first_lodging,additional_resort_1,additional_resort_2,additional_resort_3,first_unit,lodging_amenities,unit_amenities,lift_tickets_type,lift_tickets_dates,lessons,activities,equipment,rent_a_car,air_transportation,avio_depart_date,avio_return_date,depart_airport,arrival_airport,airport_transp,transportation_note,contact_by,additional_note,client_first_name,client_last_name,client_email,client_phone,received_date,status,request_from,last_change,session_key) VALUES ('$agentID','$requesttype','$checkindateinput','$checkoutdateinput','$flexibledatesinput','$otherconvenientdatesinput','$adultsinput','$childreninput','$child1ageinput','$child2ageinput','$child3ageinput','$child4ageinput','$child5ageinput','$child6ageinput','$child7ageinput','$child8ageinput','$child9ageinput','$selectedresortinput','$additionalresort1input','$additionalresort2input','$additionalresort3input','$selectedlodginginput','$lodgingoptioninput','$lodgingamenitiesinput','$liftticketselection','$liftticketdateinput','$lessonsinput','$otheractivitiesinput','$equipmentinput','$rentacarinput','$airtransportinput','$airdeparturedateinput','$airreturndateinput','$departureairportinput','$arrivalairportinput','$transportationfromtoairportinput','$transportationnoteinput','$contactwayinput','$additionalnote','$firstnameinput','$lastnameinput','$emailinput','$phoneinput','$receiveddate','$status','$requestfrom','$receiveddate',$sessiontrack)";

//clientmail
$messageclient = '<html><body>';
$messageclient .= '<div style="width:657px; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:5px; border:1px solid #CCC"><img src="http://alpineadventures.net/requestapp/images/email-header-sp1.jpg">
<div style="padding:10px; border-bottom:1px solid #999; color:#869BB6; font-weight:bold">Dear '.$firstnameinput.' '.$lastnameinput.',<br/>Thank you for chosing Alpine Adventures for your upcoming holiday in the snow. Our agents will contact you shortly.  You can find your quote request summary bellow. If you wish to make any changes or contact out agent directly, please feel free to call our toll free number: 1.800.755.1330</div><div style="padding:10px 0px;"><div style="border-bottom:2px solid #4A658D; color:#546586; font-size:16px; font-weight:bold; text-align:center">SnowPak - QUOTE REQUEST SUMMARY</div>
<div style="color:#546586; font-size:14px; font-weight:bold; padding-left:5px; padding-top:10px">DATES & TRAVELERS</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Date flexibility</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check in</td>
    <td style="padding:5px;">'.$checkindateinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check out</td>
    <td style="padding:5px;">'.$checkoutdateinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Dates are flexible</td>
    <td style="padding:5px;">'.$flexibledatesinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Other convenient dates</td>
    <td style="padding:5px;">'.$otherconvenientdatesinput.'</td>
  </tr>
   <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Travelers</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Adults</td>
    <td style="padding:5px;">'.$adultsinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Children</td>
    <td style="padding:5px;">'.$childreninput.'</td>
  </tr>';
  switch ($childreninput) {
   case "1":
 $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr>';
  break;
   case "2":
 $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr>';
  break;
   case "3":
  $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr>';
  break;
   case "4":
  $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 4 age</td>
    <td style="padding:5px;">'.$child4ageinput.'</td>
  </tr>';
  break;
   case "5":
  $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 4 age</td>
    <td style="padding:5px;">'.$child4ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 5 age</td>
    <td style="padding:5px;">'.$child5ageinput.'</td>
  </tr>';
  break;
   case "6":
   $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 4 age</td>
    <td style="padding:5px;">'.$child4ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 5 age</td>
    <td style="padding:5px;">'.$child5ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 6 age</td>
    <td style="padding:5px;">'.$child6ageinput.'</td>
  </tr>';
  break;
   case "7":
  $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 4 age</td>
    <td style="padding:5px;">'.$child4ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 5 age</td>
    <td style="padding:5px;">'.$child5ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 6 age</td>
    <td style="padding:5px;">'.$child6ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 7 age</td>
    <td style="padding:5px;">'.$child7ageinput.'</td>
  </tr>';
  break;
   case "8":
  $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 4 age</td>
    <td style="padding:5px;">'.$child4ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 5 age</td>
    <td style="padding:5px;">'.$child5ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 6 age</td>
    <td style="padding:5px;">'.$child6ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 7 age</td>
    <td style="padding:5px;">'.$child7ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 8 age</td>
    <td style="padding:5px;">'.$child8ageinput.'</td>
  </tr>';
  break;
   case "9":
   $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr> <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 4 age</td>
    <td style="padding:5px;">'.$child4ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 5 age</td>
    <td style="padding:5px;">'.$child5ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 6 age</td>
    <td style="padding:5px;">'.$child6ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 7 age</td>
    <td style="padding:5px;">'.$child7ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 8 age</td>
    <td style="padding:5px;">'.$child8ageinput.'</td>
  </tr><tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 9 age</td>
    <td style="padding:5px;">'.$child9ageinput.'</td>
  </tr>';
  break;
    };
$messageclient .= '</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold; padding-left:5px; padding-top:10px">DESTINATION</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Resort</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">First selected resort</td>
    <td style="padding:5px;">'.$selectedresortinput.'</td>
  </tr>';
  if ($additionalresort1input !=""){
 $messageclient .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional Resort 1</td>
    <td style="padding:5px;">'.$additionalresort1input.'</td>
  </tr>';
  };
   if ($additionalresort2input !=""){
 $messageclient .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional Resort 2</td>
    <td style="padding:5px;">'.$additionalresort2input.'</td>
  </tr>';
  };
   if ($additionalresort3input !=""){
 $messageclient .= '
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional Resort 3</td>
    <td style="padding:5px;">'.$additionalresort3input.'</td>
  </tr>
  ';
  };
  $messageclient .= ' <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Lodging</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">First selected lodging</td>
    <td style="padding:5px;">'.$selectedlodginginput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Lodging options</td>
    <td style="padding:5px;">'.$lodgingoptioninput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Lodging amenities</td>
    <td style="padding:5px;">'.$lodgingamenitiesinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">LIFT TICKETS</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Lift tickets</td>
    <td style="padding:5px;">'.$liftticketselection.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%"></td>
    <td style="padding:5px;">'.$liftticketdateinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">ACTIVITIES</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px;  width:35%">Lessons</td>
    <td style="padding:5px;">'.$lessonsinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional activities</td>
    <td style="padding:5px;">'.$otheractivitiesinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Equipment</td>
    <td style="padding:5px;">'.$equipmentinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">TRANSPORTATION</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
   <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Rent a car</td>
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Need rent a car</td>
    <td style="padding:5px;">'.$rentacarinput.'</td>
  </tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Air transportation</td>
	 <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Need air transportation</td>
    <td style="padding:5px;">'.$airtransportinput.'</td>
  </tr>
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Departure Date</td>
    <td style="padding:5px;">'.$airdeparturedateinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Return Date</td>
    <td style="padding:5px;">'.$airreturndateinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Departure Airport</td>
    <td style="padding:5px;">'.$departureairportinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Arrival Airport</td>
    <td style="padding:5px;">'.$arrivalairportinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Airport transportation</td>
    <td style="padding:5px;">'.$transportationfromtoairportinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Transportation note</td>
    <td style="padding:5px;">'.$transportationnoteinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">CONTACT INFO</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">First name</td>
    <td style="padding:5px;">'.$firstnameinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Last name</td>
    <td style="padding:5px;">'.$lastnameinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Email</td>
    <td style="padding:5px;">'.$emailinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Phone</td>
    <td style="padding:5px;">'.$phoneinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Want to be contacted...</td>
    <td style="padding:5px;">'.$contactwayinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">ADDITIONAL NOTE</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Message to agent</td>
    <td style="padding:5px;">'.$phoneinput.'</td>
  </tr>
</table>
</div>
</div>
</div>
</div>';
$messageclient .= "</body></html>";
///////////////////////////////////////////////clientmailend
//agent
$messageagent = '<html><body>';
$messageagent .= '<div style="width:657px; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:5px; border:1px solid #CCC"><img src="http://alpineadventures.net/requestapp/images/email-header-sp1.jpg">
<div style="padding:10px 0px;"><div style="border-bottom:2px solid #4A658D; color:#546586; font-size:16px; font-weight:bold; text-align:center">QUOTE REQUEST SUMMARY</div>
<div style="color:#546586; font-size:14px; font-weight:bold; padding-left:5px; padding-top:10px">DATES & TRAVELERS</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr style="width:650px">
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Date flexibility</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check in</td>
    <td style="padding:5px;">'.$checkindateinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check out</td>
    <td style="padding:5px;">'.$checkoutdateinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Dates are flexible</td>
    <td style="padding:5px;">'.$flexibledatesinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Other convenient dates</td>
    <td style="padding:5px;">'.$otherconvenientdatesinput.'</td>
  </tr>
   <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Travelers</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Adults</td>
    <td style="padding:5px;">'.$adultsinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Children</td>
    <td style="padding:5px;">'.$childreninput.'</td>
  </tr>';
  switch ($childreninput) {
   case "1":
 $messageagent .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 1 age</td>
    <td style="padding:5px;">'.$child1ageinput.'</td>
  </tr>';
  break;
   case "2":
 $messageagent .= '  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 2 age</td>
    <td style="padding:5px;">'.$child2ageinput.'</td>
  </tr>';
  break;
   case "3":
  $messageagent .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 3 age</td>
    <td style="padding:5px;">'.$child3ageinput.'</td>
  </tr>';
  break;
   case "4":
  $messageagent .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 4 age</td>
    <td style="padding:5px;">'.$child4ageinput.'</td>
  </tr>';
  break;
   case "5":
  $messageagent .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 5 age</td>
    <td style="padding:5px;">'.$child5ageinput.'</td>
  </tr>';
  break;
   case "6":
   $messageagent .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 6 age</td>
    <td style="padding:5px;">'.$child6ageinput.'</td>
  </tr>';
  break;
   case "7":
  $messageagent .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 7 age</td>
    <td style="padding:5px;">'.$child7ageinput.'</td>
  </tr>';
  break;
   case "8":
  $messageagent .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 8 age</td>
    <td style="padding:5px;">'.$child8ageinput.'</td>
  </tr>';
  break;
   case "9":
   $messageagent .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Child 9 age</td>
    <td style="padding:5px;">'.$child9ageinput.'</td>
  </tr>';
  break;
    };
$messageagent .= '</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold; padding-left:5px; padding-top:10px">DESTINATION</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Resort</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">First selected resort</td>
    <td style="padding:5px;">'.$selectedresortinput.'</td>
  </tr>';
  if ($additionalresort1input !=""){
 $messageagent .= '<tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional Resort 1</td>
    <td style="padding:5px;">'.$additionalresort1input.'</td>
  </tr>';
  };
   if ($additionalresort2input !=""){
 $messageagent .= ' <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional Resort 2</td>
    <td style="padding:5px;">'.$additionalresort2input.'</td>
  </tr>';
  };
   if ($additionalresort3input !=""){
 $messageagent .= '
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional Resort 3</td>
    <td style="padding:5px;">'.$additionalresort3input.'</td>
  </tr>
  ';
  };
  $messageagent .= ' <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Lodging</td>
    </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">First selected lodging</td>
    <td style="padding:5px;">'.$selectedlodginginput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Lodging options</td>
    <td style="padding:5px;">'.$lodgingoptioninput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Lodging amenities</td>
    <td style="padding:5px;">'.$lodgingamenitiesinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">LIFT TICKETS</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Lift tickets</td>
    <td style="padding:5px;">'.$liftticketselection.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%"></td>
    <td style="padding:5px;">'.$liftticketdateinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">ACTIVITIES</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px;  width:35%">Lessons</td>
    <td style="padding:5px;">'.$lessonsinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Additional activities</td>
    <td style="padding:5px;">'.$otheractivitiesinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Equipment</td>
    <td style="padding:5px;">'.$equipmentinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">TRANSPORTATION</div>
<div style="border:1px solid #CCC; padding:1px">
    <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
   <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Rent a car</td>
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Need rent a car</td>
    <td style="padding:5px;">'.$rentacarinput.'</td>
  </tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Air transportation</td>
	 <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Need air transportation</td>
    <td style="padding:5px;">'.$airtransportinput.'</td>
  </tr>
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Departure Date</td>
    <td style="padding:5px;">'.$airdeparturedateinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Return Date</td>
    <td style="padding:5px;">'.$airreturndateinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Departure Airport</td>
    <td style="padding:5px;">'.$departureairportinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Arrival Airport</td>
    <td style="padding:5px;">'.$arrivalairportinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Airport transportation</td>
    <td style="padding:5px;">'.$transportationfromtoairportinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Transportation note</td>
    <td style="padding:5px;">'.$transportationnoteinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">CONTACT INFO</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">First name</td>
    <td style="padding:5px;">'.$firstnameinput.'</td>
  </tr>
  <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Last name</td>
    <td style="padding:5px;">'.$lastnameinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Email</td>
    <td style="padding:5px;">'.$emailinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Phone</td>
    <td style="padding:5px;">'.$phoneinput.'</td>
  </tr>
   <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Want to be contacted...</td>
    <td style="padding:5px;">'.$contactwayinput.'</td>
  </tr>
</table>
</div>
<div style="color:#546586; font-size:14px; font-weight:bold;padding-left:5px; padding-top:10px">ADDITIONAL NOTE</div>
<div style="border:1px solid #CCC; padding:1px">
  <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
  
  <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Message to agent</td>
    <td style="padding:5px;">'.$additionalnote.'</td>
  </tr>
   <tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Request from</td>
    <td style="padding:5px;">'.$requestfrom.'</td>
  </tr>
</table>
</div>
</div>
</div>
</div>';
$messageagent .= "</body></html>";
///////////////////////////////////////////////////////agentmailend


	
		$result = $mysqli->query($sql);

if ($result === false) {
    echo 'Form submission failed!';
} else {
    mail($emailinput, $subject, $messageclient, $headers);
    mail($toagent, $subject, $messageagent, $headers);
    echo 'Form submission success!';
}

//header('Location: ' . $_SERVER['HTTP_REFERER'].'?message=1');
$mysqli->close();
?>