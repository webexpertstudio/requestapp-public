<?php
require ("includes/connection.php");  // Poziva kod za konekciju na bazu podataka.

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, 'alien_requestapp');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($connection, 'utf8');

// Podaci iz POST zahteva
$agentID = "0";
$requesttype = "group";
$checkindateinput = date("Y-m-d", strtotime($_POST['checkindate']));
$checkoutdateinput = date("Y-m-d", strtotime($_POST['checkoutdate']));
$flexibledatesinput = $_POST['datesflexible'];
$otherconvenientdatesinput = $_POST['otherconvenientdates'];
$adultsinput = $_POST['adultcount'];
$childreninput = $_POST['childcount'];
$departurecountry = $_POST['departurecountry'];
$selectedresortinput = $_POST['selectedresort'];
$additionalresort1input = $_POST['additionalresort1'];
$lodgingtypeinput = $_POST['lodgintype'];
$mealtypeinput = $_POST['mealtype'];
$groundtransportationinput = $_POST['groundtransportation'];
$transportationnoteinput = $_POST['transportationnote'];
$firstnameinput = $_POST['firstname'];
$lastnameinput = $_POST['lastname'];
$emailinput = $_POST['email'];
$phoneinput = $_POST['PhoneNumber'];

$addressinput = $_POST['address'];
$cityinput = $_POST['city'];
$stateinput = $_POST['state'];
$zippostalinput = $_POST['zippostal'];
$countryinput = $_POST['country'];

$additionalnote = $_POST['additionalnote'];
$contactwayinput = $_POST['contactway'];
$additionalnotes = $_POST['additionalnotes'];
$provera = $_POST['provera'];
$subscribe = $_POST['subscribe'];
$confirm = "1";
$receiveddate = date("Y-m-d");
$subcribedate = date("Y-m-d");
$subcribetime = date("H:i:s");
$ipsubscribe = $_SERVER['REMOTE_ADDR'];
$requestfrom = $_POST['qrfrom'];
$status = "New";
$toagent = 'tanya@alpineadventures.net';
$sessiontrack =  (isset($_POST['SESSION_KEY']) && $_POST['SESSION_KEY']) ? "'" . $_POST['SESSION_KEY'] . "'" : "null";

// Upit za unos u bazu
$sql = "INSERT INTO requests (agentID, request_type, check_in, check_out, dates_flexibility, other_dates, adults, children, departure_group, first_lodging, additional_resort_1, lodging_type_group, meal_plan_group, ground_transport_group, transportation_note, contact_by, additional_note, client_first_name, client_last_name, client_email, client_phone, client_address_group, client_city_group, client_state_group, client_zipcode_group, client_country_group, received_date, status, request_from, last_change, session_key)
VALUES ('$agentID', '$requesttype', '$checkindateinput', '$checkoutdateinput', '$flexibledatesinput', '$otherconvenientdatesinput', '$adultsinput', '$childreninput', '$departurecountry', '$selectedresortinput', '$additionalresort1input', '$lodgingtypeinput', '$mealtypeinput', '$groundtransportationinput', '$transportationnoteinput', '$contactwayinput', '$additionalnote', '$firstnameinput', '$lastnameinput', '$emailinput', '$phoneinput', '$addressinput', '$cityinput', '$stateinput', '$zippostalinput', '$countryinput', '$receiveddate', '$status', '$requestfrom', '$receiveddate', $sessiontrack)";

if (mysqli_query($connection, $sql)) {
    // Poslato je u bazu
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
}

$subject = 'Ski Europe - Group Quote Request';

$headers = "From: Alpine Adventures <noreply@alpineadventures.net>\r\n";
$headers .= "BCC: lanavujicic@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

// Provera vrednosti provere
if ($provera == "Eight" || $provera == "eight" || $provera == "EIGHT") {
    // E-mail za klijenta
    $messageclient = '<html><body>';
    $messageclient .= '<div style="width:657px; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:5px; border:1px solid #CCC"><img src="http://alpineadventures.net/requestapp/php/email-header.jpg">
    <div style="padding:10px; border-bottom:1px solid #999; color:#869BB6; font-weight:bold">Dear ' . $firstnameinput . ' ' . $lastnameinput . ',<br/>Thank you for chosing Alpine Adventures for your upcoming holiday in the snow. Our agents will contact you shortly. You can find your quote request summary below. If you wish to make any changes or contact our agent directly, please feel free to call our toll-free number: 1.800.755.1330</div>
    <div style="padding:10px 0px;"><div style="border-bottom:2px solid #4A658D; color:#546586; font-size:16px; font-weight:bold; text-align:center">SKI EUROPE - GROUP QUOTE REQUEST SUMMARY</div>
    <div style="color:#546586; font-size:14px; font-weight:bold; padding-left:5px; padding-top:10px">DATES & TRAVELERS</div>
    <div style="border:1px solid #CCC; padding:1px">
    <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
    <tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Date flexibility</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check in</td>
    <td style="padding:5px;">' . $checkindateinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check out</td>
    <td style="padding:5px;">' . $checkoutdateinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Dates are flexible</td>
    <td style="padding:5px;">' . $flexibledatesinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Other convenient dates</td>
    <td style="padding:5px;">' . $otherconvenientdatesinput . '</td>
    </tr>
    <tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Travelers</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Adults</td>
    <td style="padding:5px;">' . $adultsinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Children</td>
    <td style="padding:5px;">' . $childreninput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Country of Departure</td>
    <td style="padding:5px;">' . $departurecountry . '</td>
    </tr>
    </table>
    </div>';
    $messageclient .= "</body></html>";

    // Pošaljite e-mail klijentu
    mail($emailinput, $subject, $messageclient, $headers);

    // E-mail za agenta
    $messageagent = '<html><body>';
    $messageagent .= '<div style="width:657px; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding:5px; border:1px solid #CCC"><img src="http://alpineadventures.net/requestapp/php/email-header.jpg">
    <div style="padding:10px 0px;"><div style="border-bottom:2px solid #4A658D; color:#546586; font-size:16px; font-weight:bold; text-align:center">QUOTE REQUEST SUMMARY</div>
    <div style="color:#546586; font-size:14px; font-weight:bold; padding-left:5px; padding-top:10px">DATES & TRAVELERS</div>
    <div style="border:1px solid #CCC; padding:1px">
    <table width="100%" border="0" cellspacing="3" cellpadding="0" style="font-size:12px">
    <tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold; width:650px">Date flexibility</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check in</td>
    <td style="padding:5px;">' . $checkindateinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Check out</td>
    <td style="padding:5px;">' . $checkoutdateinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Dates are flexible</td>
    <td style="padding:5px;">' . $flexibledatesinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Other convenient dates</td>
    <td style="padding:5px;">' . $otherconvenientdatesinput . '</td>
    </tr>
    <tr>
    <td colspan="2" style="background:#869BB6; padding:5px; color:#fff; font-size:13px; font-weight:bold">Travelers</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Adults</td>
    <td style="padding:5px;">' . $adultsinput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Children</td>
    <td style="padding:5px;">' . $childreninput . '</td>
    </tr>
    <tr style="background:#ECEFF4;">
    <td style="padding:5px; width:35%">Country of Departure</td>
    <td style="padding:5px;">' . $departurecountry . '</td>
    </tr>
    </table>
    </div>';
    $messageagent .= "</body></html>";

    // Pošaljite e-mail agentu
    mail($toagent, $subject, $messageagent, $headers);

    echo "<script>window.location='thank-you.php';</script>";
} else {
    echo "Error: " . mysqli_error($connection);
}

mysqli_close($connection);
?>