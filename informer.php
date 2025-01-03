<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
}

// Ako je postovano
$args = array(
    'save' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_SANITIZE_STRING,
    'action' => FILTER_SANITIZE_STRING,
    'informer_id' => FILTER_VALIDATE_INT,
    'informer_name' => FILTER_SANITIZE_STRING,
    'informer_url' => FILTER_SANITIZE_URL,
);

$post = filter_input_array(INPUT_POST, $args);

if (isset($post["save"]) && isset($post["action"]) && $post["action"] == "update" && isset($post["informer_id"])) {
    // Pripremljeni upit za UPDATE
    $sql = "UPDATE `tbl_informers` SET 
            `informer_name` = ?, 
            `informer_url` = ?, 
            `updated` = NOW() 
            WHERE `informer_id` = ?";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $post["informer_name"], $post["informer_url"], $post["informer_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: index.php?page=informers");
}

if (isset($post["save"]) && isset($post["action"]) && $post["action"] == "insert") {
    // Pripremljeni upit za INSERT
    $sql = "INSERT INTO `tbl_informers` (`informer_name`, `informer_url`, `inserted`) 
            VALUES (?, ?, NOW())";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $post["informer_name"], $post["informer_url"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: index.php?page=informers");
}

if (isset($post["delete"]) && isset($post["informer_id"])) {
    // Pripremljeni upit za DELETE
    $sql = "DELETE FROM `tbl_informers` WHERE `informer_id` = ?";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $post["informer_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: index.php?page=informers");
}

// GET upiti
$args = array(
    'informer_id' => FILTER_VALIDATE_INT,
    'action' => FILTER_SANITIZE_STRING,
);
$get = filter_input_array(INPUT_GET, $args);

if (isset($get["action"]) && $get["action"] == "update" && isset($get["informer_id"])) {
    // Pripremljeni upit za SELECT
    $sql = "SELECT * FROM `tbl_informers` WHERE `informer_id` = ?";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $get["informer_id"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
} else {
    $row = array();
    $get["action"] = "insert";
}
?>

<div id="statusbuttons">
    <button id="tab" onclick="location.href='index.php?page=affiliates'">Affiliates</button>
    <button id="tab" onclick="location.href='index.php?page=informers'">Informers</button>
    <button id="tab" onclick="location.href='index.php?page=campaigns'">Campaigns</button>
    <button id="tab" onclick="location.href='index.php?page=tracking'">Tracking Reports</button>
    <button id="tab" onclick="location.href='index.php?page=informer&action=new'">Add New Informer</button>
</div>

<form method="POST">
    <input type="hidden" name="action" value="<?php echo $get["action"]; ?>">
    <input type="hidden" name="informer_id" value="<?php echo $get["informer_id"]; ?>">
    <div class="datatable">
        <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
            <tr>
                <td width="15%">Informer Name:</td>
                <td width="85%"><input type="text" name="informer_name" style="width:98%" value="<?php echo isset($row["informer_name"]) ? $row["informer_name"] : ""; ?>"></td>
            </tr>
            <tr>
                <td width="15%">Informer URL:</td>
                <td width="85%"><input type="text" name="informer_url" style="width:98%" value="<?php echo isset($row["informer_url"]) ? $row["informer_url"] : ""; ?>"></td>
            </tr>
            <?php if ($get["informer_id"]) { ?>
                <tr>
                    <td colspan="2">
                        Insert this files: <br><br>
                        <a target="_blank" href="download/tracking.jquery.min.js">tracking.jquery.min.js</a><br>
                        <a target="_blank" href="download/tracking.jquery.session.js">tracking.jquery.session.js</a><br>
                        <a target="_blank" href="download/tracking.functions.js">tracking.functions.js</a><br>
                        <br>
                        and this code in informer website:
                        <pre><?php
echo htmlspecialchars('<script src="'.$row["informer_url"].'/tracking.jquery.min.js"></script>
<script src="'.$row["informer_url"].'/tracking.jquery.session.js"></script>
<script src="'.$row["informer_url"].'/tracking.functions.js"></script>
<script type="text/javascript">
    var jQTracking = $.noConflict(true);
    jQTracking(document).ready(function() {
        setSession(jQTracking);
        var FEEBACK_URL = "http://requests.alpineadventures.net/feedback.php";
        var INFORMER_URL = "http://alpineadventures.net"
        var API = encodeURIComponent("108M05970r7d6u50lou187061Yws8G");
        var INFORMER_ID = encodeURIComponent("'.$get["informer_id"].'");
        var REQUEST_ID = encodeURIComponent(generateRequestID());
        var TITLE = encodeURIComponent(document.getElementsByTagName("title")[0].innerHTML);
        var SESSION_KEY = encodeURIComponent(jQTracking.session.get("SESSION_KEY"));
        var TID = encodeURIComponent(jQTracking.session.get("TID"));
        var URI = encodeURIComponent(jQTracking(location).attr("href").replace(INFORMER_URL, ""));
        
        var URL = FEEBACK_URL + "?API=" + API + "&ACTION=HELLO&REQUEST_ID=" + REQUEST_ID + "&CAMPAIGN_TID=" + TID 
            + "&INFORMER_ID=" + INFORMER_ID + "&SESSION_KEY=" + SESSION_KEY + "&URI=" + URI + "&TITLE=" + TITLE;
        var request = jQTracking.get(URL);
        
        if (window.console) {
            console.log(URL);
        }
        
        function trackMe(){
            var URL = FEEBACK_URL + "?API=" + API + "&ACTION=HEY&SESSION_KEY=" + SESSION_KEY 
                + "&REQUEST_ID=" + REQUEST_ID + "&INFORMER_ID=" + INFORMER_ID;
            var request = jQTracking.get(URL);
            if (window.console) {
                console.log(URL);
            }
            setTimeout(trackMe, 5000);
        }
        trackMe();
    });
</script>'); ?>
                        </pre>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" id="save" name="save" value="Save">
                    <input type="submit" id="cancel" name="cancel" value="Cancel" onclick="location.href='index.php?page=informers'">
                    <input type="submit" id="delete" name="delete" value="Delete" onclick="return confirm('Delete?');">
                </td>
            </tr>
        </table>
    </div>
</form>