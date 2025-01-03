<?php
// Assuming you have already established a mysqli connection, e.g.:
// $mysqli = new mysqli("localhost", "user", "password", "database_name");
// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Sanitize inputs
$args = array(
    'save' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_SANITIZE_STRING,
    'action' => FILTER_SANITIZE_STRING, 
    'campaign_tid' => FILTER_VALIDATE_INT, 
    'informer_id' => FILTER_VALIDATE_INT, 
    'campaign_name' => FILTER_SANITIZE_STRING,
    'campaign_url' => FILTER_SANITIZE_URL,
    'url_var' => FILTER_SANITIZE_URL,
    'affiliate_id' => FILTER_VALIDATE_INT,
    'campaign_type' => FILTER_SANITIZE_STRING,
    'campaign_name' => FILTER_SANITIZE_STRING,
);
$post = filter_input_array(INPUT_POST, $args);

// Handle save/update action
if (isset($post["save"]) && isset($post["action"]) && $post["action"] == "update" && isset($post["campaign_tid"])) {
    $stmt = $mysqli->prepare("UPDATE `tbl_campaigns` SET `campaign_tid`=?, `informer_id`=?, `affiliate_id`=?, `campaign_type`=?, `campaign_name`=?, `campaign_url`=?, `url_var`=?, `updated`=NOW() WHERE campaign_tid=?");
    $stmt->bind_param("iiissssii", 
        $post["campaign_tid"], 
        $post["informer_id"], 
        $post["affiliate_id"], 
        $post["campaign_type"], 
        $post["campaign_name"], 
        $post["campaign_url"], 
        $post["url_var"], 
        $post["campaign_tid"]
    );
    $stmt->execute();
    $stmt->close();
    header("Location: index.php?page=campaigns"); 
}

// Handle insert action
if (isset($post["save"]) && isset($post["action"]) && $post["action"] == "insert") {
    $stmt = $mysqli->prepare("INSERT INTO `tbl_campaigns` SET `campaign_tid`=?, `informer_id`=?, `affiliate_id`=?, `campaign_type`=?, `campaign_name`=?, `campaign_url`=?, `url_var`=?, `inserted`=NOW()");
    $stmt->bind_param("iiissss", 
        $post["campaign_tid"], 
        $post["informer_id"], 
        $post["affiliate_id"], 
        $post["campaign_type"], 
        $post["campaign_name"], 
        $post["campaign_url"], 
        $post["url_var"]
    );
    $stmt->execute();
    $stmt->close();
    header("Location: index.php?page=campaigns"); 
}

// Handle delete action
if (isset($post["delete"]) && isset($post["campaign_tid"])) {
    $stmt = $mysqli->prepare("DELETE FROM `tbl_campaigns` c WHERE c.`campaign_tid`=? AND c.`campaign_tid` NOT IN (SELECT f.`campaign_tid` FROM `tbl_feedbacks` f)");
    $stmt->bind_param("i", $post["campaign_tid"]);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php?page=campaigns"); 
}

// Get data for the campaign
$args = array(
    'campaign_tid' => FILTER_VALIDATE_INT, 
    'action' => FILTER_SANITIZE_STRING, 
);
$get = filter_input_array(INPUT_GET, $args);
if (isset($get["action"]) && $get["action"] == "update" && isset($get["campaign_tid"])) {
    $stmt = $mysqli->prepare("SELECT * FROM `tbl_campaigns` WHERE campaign_tid=?");
    $stmt->bind_param("i", $get["campaign_tid"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
} else {
    $row = array();
    $get["action"] = "insert";
}

$campaign_tid = isset($row["campaign_tid"]) ? $row["campaign_tid"] : rand(1000000000, 9999999999);
?>

<!-- HTML Form for Campaign details -->
<div id="statusbuttons">
    <button id="tab" onclick="location.href='index.php?page=affiliates'">Affiliates</button>
    <button id="tab" onclick="location.href='index.php?page=informers'">Informers</button>
    <button id="tab" onclick="location.href='index.php?page=campaigns'">Campaigns</button>
    <button id="tab" onclick="location.href='index.php?page=tracking'">Tracking Reports</button>
    <button id="tab" onclick="location.href='index.php?page=campaign&action=insert'">Add new Campaign</button>
</div>

<form method="POST">
    <input type="hidden" name="action" value="<?php echo $get["action"]; ?>">
    <div class="datatable">
        <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
            <tr>
                <td width="15%">Campaign Name:</td>
                <td><input type="text" name="campaign_name" style="width:98%" value="<?php echo isset($row["campaign_name"]) ? $row["campaign_name"] : ""; ?>" ></td>
            </tr>
            <!-- Additional rows for Affiliate, Informer, and Campaign Type go here... -->
        </table>
    </div>
</form>