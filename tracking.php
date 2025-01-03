<?php
// Provera da li je korisnik admin
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
}

// Sanitizacija ulaznih podataka
$args = array(
    'page' => FILTER_SANITIZE_STRING, 
    'report' => FILTER_SANITIZE_STRING, 
    'from' => FILTER_SANITIZE_STRING, 
    'to' => FILTER_SANITIZE_STRING, 
    'informer_id' => FILTER_VALIDATE_INT, 
    'affiliate_id' => FILTER_VALIDATE_INT, 
    'campaign_tid' => FILTER_VALIDATE_INT, 
    'country' => FILTER_SANITIZE_STRING, 
    'title' => FILTER_SANITIZE_STRING, 
    'session_key' => FILTER_SANITIZE_STRING,
);
$get = filter_input_array(INPUT_GET, $args);

// Postavljanje podrazumevanih vrednosti
if (!$get["from"]) {
    $get["from"] = date("Y-m-01");
}
if (!$get["to"]) {
    $get["to"] = date("Y-m-d");
}
if (!$get["report"]) {
    $get["report"] = "timeline";
}

// Preuzimanje podataka iz baze prema ID-evima
if (isset($get["informer_id"]) && $get["informer_id"]) {
    $query2 = db_query("SELECT `informer_name` FROM `tbl_informers` WHERE `informer_id`='" . input_db($get["informer_id"]) . "'");
    list($get["informer_name"]) = mysqli_fetch_row($query2);
}

if (isset($get["affiliate_id"]) && $get["affiliate_id"]) {
    $query2 = db_query("SELECT `affiliate_name` FROM `tbl_affiliates` WHERE `affiliate_id`='" . input_db($get["affiliate_id"]) . "'");
    list($get["affiliate_name"]) = mysqli_fetch_row($query2);
}

if (isset($get["campaign_tid"]) && $get["campaign_tid"]) {
    $query2 = db_query("SELECT `campaign_name` FROM `tbl_campaigns` WHERE `campaign_tid`='" . input_db($get["campaign_tid"]) . "'");
    list($get["campaign_name"]) = mysqli_fetch_row($query2);
}

// Priprema WHERE uslova za SQL upit
$WHERE = " WHERE 1=1 ";
if ($get["from"] && $get["to"]) {
    $WHERE .= " AND f.`inserted` BETWEEN '" . input_db($get["from"] . " 00:00:00") . "' AND '" . input_db($get["to"] . " 23:59:59") . "'";
}
if ($get["informer_id"]) {
    $WHERE .= " AND f.`informer_id`='" . input_db($get["informer_id"]) . "' ";
}
if ($get["campaign_tid"]) {
    $WHERE .= " AND f.`campaign_tid`='" . input_db($get["campaign_tid"]) . "' ";
}
if ($get["country"]) {
    $WHERE .= " AND f.`client_country` = '" . input_db($get["country"]) . "' ";
}
if ($get["title"]) {
    $get["title"] = urldecode($get["title"]);
    $WHERE .= " AND f.`title` LIKE '" . input_db($get["title"]) . "' ";
}
if ($get["session_key"]) {
    $WHERE .= " AND f.`session_key` LIKE '" . input_db($get["session_key"]) . "' ";
}

// Includovanje jQuery datepickera
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
$(function() {
    $( "#from" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#to" ).datepicker({ dateFormat: "yy-mm-dd" });
});
</script>

<div id="statusbuttons">
    <button id="tab" onclick="location.href='index.php?page=affiliates'">Affiliates</button>
    <button id="tab" onclick="location.href='index.php?page=informers'">Informers</button>
    <button id="tab" onclick="location.href='index.php?page=campaigns'">Campaigns</button>
    <button id="tab" onclick="location.href='index.php?page=tracking'">Tracking Reports</button>
    <br><br>
    Reports:
    <button id="tab" onclick="location.href='index.php?page=tracking&report=timeline'">Visits Timeline</button>
    <button id="tab" onclick="location.href='index.php?page=tracking&report=per_country'">Visits Per Country</button>
    <button id="tab" onclick="location.href='index.php?page=tracking&report=per_page'">Visits Per Page</button>
    <br><br>
    <form method="GET">
        <input type="hidden" name="page" value="<?php echo $get["page"]; ?>">
        <input type="hidden" name="report" value="<?php echo $get["report"]; ?>">
        From: <input type="text" id="from" name="from" value="<?php echo $get["from"]; ?>" style="width:70px;" OnChange="this.form.submit();">
        To: <input type="text" id="to" name="to" value="<?php echo $get["to"]; ?>" style="width:70px;" OnChange="this.form.submit();">
        Website (Informer): 
        <select name="informer_id" OnChange="this.form.submit();">
            <option value="">All informers</option>
            <?php
            $sql = "SELECT `informer_id`, `informer_name` FROM `tbl_informers` ORDER BY `informer_name`";
            $query = db_query($sql);
            while(list($informer_id, $informer_name) = mysqli_fetch_row($query)) {
                echo "<option value='$informer_id' " . ($get["informer_id"] == $informer_id ? "selected" : "") . ">$informer_name</option>";
            }
            ?>
        </select>
        Campaign:
        <select name="campaign_tid" OnChange="this.form.submit();">
            <option value="">All campaigns</option>
            <?php
            $sql = "SELECT c.`campaign_tid`, c.`campaign_name`, a.`affiliate_name`
                    FROM `tbl_campaigns` c
                    JOIN `tbl_affiliates` a ON (a.`affiliate_id`=c.`affiliate_id`)
                    WHERE 1=1";
            if ($get["informer_id"]) {
                $sql .= " AND `informer_id`='" . input_db($get["informer_id"]) . "'";
            }
            if ($get["affiliate_id"]) {
                $sql .= " AND `affiliate_id`='" . input_db($get["affiliate_id"]) . "'";
            }
            $sql .= " ORDER BY `campaign_name`";
            $query = db_query($sql);
            while(list($campaign_tid, $campaign_name, $affiliate_name) = mysqli_fetch_row($query)) {
                echo "<option value='$campaign_tid' " . ($get["campaign_tid"] == $campaign_tid ? "selected" : "") . ">$affiliate_name - $campaign_name</option>";
            }
            ?>
        </select>
        <br>
        Country: 
        <select id="country" name="country" OnChange="this.form.submit();">
            <option value="">All countries</option>
            <?php
            $sql = "SELECT DISTINCT f.`client_country`, COUNT(f.`client_country`) as counts
                    FROM `tbl_feedbacks` f " . $WHERE . " AND f.`client_country`!='' 
                    GROUP BY f.`client_country`
                    ORDER BY f.`client_country`";
            $query = db_query($sql);
            while(list($client_country, $counts) = mysqli_fetch_row($query)) {
                echo "<option value='$client_country' " . ($get["country"] == $client_country ? "selected" : "") . ">$client_country ($counts)</option>";
            }
            ?>
        </select>    
        Page: 
        <select id="title" name="title" style="width:150px;" OnChange="this.form.submit();">
            <option value="">All pages</option>
            <?php
            $sql = "SELECT DISTINCT f.`title` FROM `tbl_feedbacks` f WHERE f.`title`!='' ORDER BY f.`title`";
            $query = db_query($sql);
            while(list($title) = mysqli_fetch_row($query)) {
                echo "<option value='" . urlencode($title) . "' " . ($get["title"] == $title ? "selected" : "") . ">$title</option>";
            }
            ?>
        </select>
        <input type="submit" value="Filter">
    </form>
</div>

<div class="datatable">
    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
        <?php
        // Includovanje odgovarajućih fajlova na osnovu izveštaja
        if ($get["report"] == "per_country") {
            include "per_country.php";
        } elseif ($get["report"] == "per_page") {
            include "per_page.php";
        } elseif ($get["report"] == "history") {
            include "history.php";
        } else {
            include "timeline.php";
        }
        ?>
    </table>
</div>