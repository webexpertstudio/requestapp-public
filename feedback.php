<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 

if(!isset($_GET["API"]) || $_GET["API"]!="108M05970r7d6u50lou187061Yws8G") {
    die("Denied!!");
}

require ("includes/functions.php");  
require ("includes/connection.php");  // Poziva kod za konekciju na bazu podataka.
    
if(isset($_GET["ACTION"]) && $_GET["ACTION"]=="HELLO") {
    $args = array(
        'REQUEST_ID' => FILTER_SANITIZE_STRING, 
        'CAMPAIGN_TID' => FILTER_SANITIZE_STRING, 
        'INFORMER_ID' => FILTER_SANITIZE_STRING, 
        'SESSION_KEY' => FILTER_SANITIZE_STRING, 
        'TITLE' => FILTER_SANITIZE_STRING,
        'URI' => FILTER_SANITIZE_STRING,
    );
    $get = filter_input_array(INPUT_GET, $args);

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $get["CLIENT_IP"] = $_SERVER['HTTP_CLIENT_IP'];
    } 
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $get["CLIENT_IP"] = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } 
    else {
        $get["CLIENT_IP"] = $_SERVER['REMOTE_ADDR'];
    }

    $client_country = "";
    if(isset($get["CLIENT_IP"]) && filter_var($get["CLIENT_IP"], FILTER_VALIDATE_IP)) {
        $ip = ip2long($get["CLIENT_IP"]);
        if($ip) {
            // Korišćenje mysqli za upit
            $sql = "SELECT `country` FROM `tbl_ip_countries` 
                    WHERE `ip_from` <= ? AND `ip_to` >= ? LIMIT 1";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $ip, $ip);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $client_country);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if($get["INFORMER_ID"] && $get["REQUEST_ID"] && $get["SESSION_KEY"]) {
        // Pripremljeni upit za unos
        $sql = "INSERT INTO `tbl_feedbacks` SET 
                `request_id` = ?, 
                `informer_id` = ?, 
                `client_ip` = ?, 
                `client_country` = ?, 
                `URI` = ?, 
                `title` = ?, 
                `campaign_tid` = ?, 
                `session_key` = ?, 
                `inserted` = NOW(), 
                `active_until` = NOW()";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", 
            input_db($get["REQUEST_ID"]), 
            input_db((int)$get["INFORMER_ID"]), 
            input_db($get["CLIENT_IP"]),
            input_db($client_country), 
            input_db($get["URI"]),
            input_db($get["TITLE"]),
            input_db($get["CAMPAIGN_TID"]),
            input_db($get["SESSION_KEY"])
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "ok";
    }
}
elseif(isset($_GET["ACTION"]) && $_GET["ACTION"]=="HEY") {
    $args = array(
        'REQUEST_ID' => FILTER_SANITIZE_STRING, 
        'SESSION_KEY' => FILTER_SANITIZE_STRING,
        'INFORMER_ID' => FILTER_SANITIZE_STRING, 
    );
    $get = filter_input_array(INPUT_GET, $args);
    
    if($get["INFORMER_ID"] && $get["REQUEST_ID"] && $get["SESSION_KEY"]) {
        // Pripremljeni upit za update
        $sql = "UPDATE `tbl_feedbacks` SET 
                `active_until` = NOW() 
                WHERE `request_id` = ? 
                AND `session_key` = ? 
                AND `informer_id` = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "sss", 
            input_db($get["REQUEST_ID"]), 
            input_db($get["SESSION_KEY"]), 
            input_db($get["INFORMER_ID"])
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "ok";
    }
}

// Zatvori konekciju sa bazom
mysqli_close($connection);
?>