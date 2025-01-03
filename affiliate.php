<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Acces denied");
}

// ako je postovano
$args = array(
    'save' => FILTER_SANITIZE_STRING,
    'delete' => FILTER_SANITIZE_STRING,
    'action' => FILTER_SANITIZE_STRING, 
    'affiliate_id' => FILTER_VALIDATE_INT, 
    'affiliate_name' => FILTER_SANITIZE_STRING,
    'affiliate_url' => FILTER_SANITIZE_URL,
);

$post = filter_input_array(INPUT_POST, $args);

if (isset($post["save"]) && isset($post["action"]) && $post["action"] == "update" && isset($post["affiliate_id"])) {
    $sql = "UPDATE `tbl_affiliates` SET 
            `affiliate_name`='" . input_db($post["affiliate_name"]) . "',
            `affiliate_url`='" . input_db($post["affiliate_url"]) . "',
            `updated`=NOW() 
            WHERE affiliate_id='" . input_db($post["affiliate_id"]) . "'";

    exec_query($sql);
    header("Location: index.php?page=affiliates"); 
}

if (isset($post["save"]) && isset($post["action"]) && $post["action"] == "insert") {
    $sql = "INSERT INTO `tbl_affiliates` SET 
            `affiliate_name`='" . input_db($post["affiliate_name"]) . "',
            `affiliate_url`='" . input_db($post["affiliate_url"]) . "',
            `inserted`=NOW() ";

    exec_query($sql);
    header("Location: index.php?page=affiliates"); 
}

if (isset($post["delete"]) && isset($post["affiliate_id"])) {
    $sql = "DELETE FROM `tbl_affiliates`  
            WHERE affiliate_id='" . input_db($post["affiliate_id"]) . "'";

    exec_query($sql);
    header("Location: index.php?page=affiliates"); 
}

// get
$args = array(
    'affiliate_id' => FILTER_VALIDATE_INT, 
    'action' => FILTER_SANITIZE_STRING, 
);

$get = filter_input_array(INPUT_GET, $args);

if (isset($get["action"]) && $get["action"] == "update" && isset($get["affiliate_id"])) {
    $sql = "SELECT * 
            FROM `tbl_affiliates` 
            WHERE affiliate_id=" . input_db($get["affiliate_id"]);
    
    // Zamenjujemo mysql_fetch_assoc sa mysqli_fetch_assoc
    $query = db_query($sql);
    $row = mysqli_fetch_assoc($query); // <-- Ovde se menja
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
    <button id="tab" onclick="location.href='index.php?page=affiliate&action=new'">Add New Affiliate</button>
</div>
<form method="POST">
    <input type="hidden" name="action" value="<?php echo $get["action"]; ?>">
    <input type="hidden" name="affiliate_id" value="<?php echo $get["affiliate_id"]; ?>">
    <div class="datatable">
        <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
            <tr>
                <td width="15%">Affiliate Name:</td>
                <td><input type="text" name="affiliate_name" style="width:98%" value="<?php echo isset($row["affiliate_name"]) ? $row["affiliate_name"] : ""; ?>" ></td>
            </tr>
            <tr>
                <td width="15%">Affiliate URL:</td>
                <td><input type="text" name="affiliate_url" style="width:98%" value="<?php echo isset($row["affiliate_url"]) ? $row["affiliate_url"] : ""; ?>" ></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" id="save" name="save" value="Save">
                    <input type="submit" id="cancel" name="cancel" value="Cancel" onclick="location.href='index.php?page=affiliates'">
                    <input type="submit" id="delete" name="delete" value="Delete" onclick="return confirm('Delete?');">
                </td>
            </tr>
         </table>
    </div>
</form>