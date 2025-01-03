<?php
$requestIDmail = $_GET['IDrequestmail'];
$agentnamerequestmail = $_GET['agentnamerequestmail'];

// Pretpostavljam da je već postoji konekcija sa bazom, pa je neću dodavati

// Zamenjujemo mysql_* sa mysqli_*
$req = mysqli_query($conn, "SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id=t1.agentID");

// Postavljanje UTF-8 karakter seta
mysqli_set_charset($conn, "utf8");

while($data = mysqli_fetch_array($req)){
    if($data["requestID"] == $requestIDmail)
    {
        $checkindateinput = date("M d,Y", strtotime($data["check_in"]));
        $checkoutdateinput = date("M d,Y", strtotime($data["check_out"]));
        $flexibledatesinput = $data["dates_flexibility"];
        
        if ($flexibledatesinput == 0) {
            $flexibledatesinput = "No";
        }
        
        $otherconvenientdatesinput = $data["other_dates"];
        $adultsinput = $data["adults"];
        $childreninput = $data["children"];
        $child1ageinput = $data["child1"];
        $child2ageinput = $data["child2"];
        $child3ageinput = $data["child3"];
        $child4ageinput = $data["child4"];
        $child5ageinput = $data["child5"];
        $child6ageinput = $data["child6"];
        $child7ageinput = $data["child7"];
        $child8ageinput = $data["child8"];
        $child9ageinput = $data["child9"];
        $selectedresortinput = $data["first_lodging"];
        $additionalresort1input = $data["additional_resort_1"];
        $additionalresort2input = $data["additional_resort_2"];
        $additionalresort3input = $data["additional_resort_3"];
        $selectedlodginginput = $data["first_unit"];
        $lodgingoptioninput = $data["lodging_amenities"];
        $lodgingamenitiesinput = $data["unit_amenities"];
        $liftticketselection = $data["lift_tickets_type"];
        $liftticketdateinput = $data["lift_tickets_dates"];
        $lessonsinput = $data["lessons"];
        $otheractivitiesinput = $data["activities"];
        $equipmentinput = $data["equipment"];
        $rentacarinput = $data["rent_a_car"];
        
        if ($rentacarinput == 0) {
            $rentacarinput = "No";
        }
        
        $airtransportinput = $data["air_transportation"];
        
        if ($airtransportinput == 0) {
            $airtransportinput = "No";
        }
        
        $airdeparturedateinput = date("M d,Y", strtotime($data["avio_depart_date"]));
        
        if ($airdeparturedateinput == "Nov 30,-0001" || $airdeparturedateinput == "Dec 31,1969") {
            $airdeparturedateinput = "";
        }
        
        $airreturndateinput = date("M d,Y", strtotime($data["avio_return_date"]));

        if ($airreturndateinput == "Nov 30,-0001" || $airdeparturedateinput == "Dec 31,1969") {
            $airreturndateinput = "";
        }
        
        $departureairportinput = $data["depart_airport"];
        $arrivalairportinput = $data["arrival_airport"];
        $transportationfromtoairportinput = $data["airport_transp"];
        
        if ($transportationfromtoairportinput == 0) {
            $transportationfromtoairportinput = "No";
        }
        
        $transportationnoteinput = $data["transportation_note"];
        $firstnameinput = $data["client_first_name"];
        $lastnameinput = $data["client_last_name"];
        $emailinput = $data["client_email"];
        $phoneinput = $data["client_phone"];
        $contactwayinput = $data["contact_by"];
        $additionalnote = $data["additional_note"];
        $phoneinput = $data["client_phone"];
        $receiveddate = date("Y-m-d", strtotime($data["received_date"]));

        $status = "New";
        $toagent = $data["email"];
        $agentname = $data["agent_name"];
        $subject = 'Alpine Adventures - Quote Request';

        $headers = "From: Alpine Adventures <tanya@alpineadventures.net>\r\n"; 
        $headers .= "Reply-To: tanya@alpineadventures.net\r\n";
        $headers .= "Return-Path: tanya@alpineadventures.net\r\n";
        $headers .= "BCC: lanavujicic@gmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        ///////////////////////////////////////////////clientmailend
        //agent
        $messageagent = '<html><body>';
        $messageagent .= '<div style="width:657px; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:5px; border:1px solid #CCC"><img src="http://alpineadventures.net/requestapp/php/email-header.jpg">
        <div style="padding:10px 0px;"><div style="border-bottom:2px solid #4A658D; color:#546586; font-size:16px; font-weight:bold; text-align:center">QUOTE REQUEST SUMMARY</div>
        <div style="color:#546586; font-size:14px; font-weight:bold; padding-left:5px; padding-top:10px">DATES & TRAVELERS</div>
        <div style="border:1px solid #CCC; padding:1px">
          <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
          <tr>
            <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Date flexibility</td>
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
        // Nastavi sa svim ostalim delovima koda za mail
        $messageagent .= '</table>
        </div>';

        // Slanje emaila
        mail($toagent, $subject, $messageagent, $headers);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
?>