<?php
// Funkcija za unos podataka u bazu
function input_db($input, $type="str", $dbconn) {
    if($type == "str") {
        $input = str_replace(";", "", $input);
        $input = str_replace("DELIMITER", "", $input);
        $input = str_replace("DROP", "", $input);
    }
    elseif($type == "int") {
        $input = (int) $input;
    }
    // Sanitizacija stringa
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    
    // Korišćenje mysqli_real_escape_string za sanitizaciju i sprečavanje SQL injekcija
    return $dbconn->real_escape_string($input);
}

// Funkcija za izvršavanje upita
function db_query($sql, $dbconn) {
    $query = $dbconn->query($sql);
    if (!$query) {
        die($sql . " - " . $dbconn->error);
    }
    return $query;
}

// Funkcija za izvršavanje upita bez vraćanja rezultata
function exec_query($sql, $dbconn) {
    $query = $dbconn->query($sql);
    if (!$query) {
        die($sql . " - " . $dbconn->error);
    }
}
?>