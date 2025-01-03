<?php
// Dohvati sve agente iz baze podataka
$agentsquery = $dbconn->prepare("SELECT id, agent_name FROM member");
$agentsquery->execute();
$agentsresult = $agentsquery->get_result();

// Generiši opcije za padajući meni
$agentsdropbox = '';
while ($agent = $agentsresult->fetch_assoc()) {
    $agentsdropbox .= '<option value="' . $agent['id'] . '">' . $agent['agent_name'] . '</option>';
}

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $stmt = $dbconn->prepare("SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id = t1.agentID WHERE t1.status = ? ORDER BY t1.requestID");
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $reqnum = $stmt->get_result();
    $title = 'Status: ' . $_GET['status'];
} else {
    $stmt = $dbconn->prepare("SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id = t1.agentID ORDER BY t1.requestID");
    $stmt->execute();
    $reqnum = $stmt->get_result();
    $title = "All Requests";
}

$dbconn->query("SET NAMES UTF8");

if ($reqnum->num_rows == 0) {
    echo  '<div class="norequests">No request</div>';
} else {
    require ("includes/numofrows.php");

    // Query with limit for pagination
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        $stmt = $dbconn->prepare("SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id = t1.agentID WHERE t1.status = ? ORDER BY t1.requestID DESC LIMIT ?, ?");
        $stmt->bind_param("sii", $status, $offset, $rowsperpage);
    } else {
        $stmt = $dbconn->prepare("SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id = t1.agentID ORDER BY t1.requestID DESC LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $rowsperpage);
    }
    $stmt->execute();
    $req = $stmt->get_result();

    $dbconn->query("SET NAMES UTF8");
?>

<div class="datatable">
    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
        <tr>
            <td colspan="7" class="qouterequesttext" style="height:30px">Quote Request Details - <?php echo $title ?></td>
            <td colspan="4" style="background:#547743; text-align:center; color:white;font-size: 15px;">Manage Quote Requests</td>
        </tr>
        <tr style="font-size: 13px;border-bottom:2px solid #000">
            <td>ID</td>
            <td style="min-width: 80px;">Sender</td>
            <td style="text-align:center; min-width: 75px;">QR Received</td>
            <td style="background:#A7BFCB;">T</td>
            <td style="background:#A7BFCB;">Title</td>
            <td style="background:#A7BFCB; text-align:center; min-width: 75px;">Trip Starts</td>
            <td style="text-align:center; padding:0px;">View</td>
            <td style="background:#99BF82; text-align:center;">Assign to Agent</td>
            <td style="background:#99BF82; text-align:center; padding:0px;">Notify</td>
            <td style="background:#99BF82; text-align:center;">Status</td>
            <td style="background:#99BF82; text-align:center; padding:0px;">Edit</td>
        </tr>
        <?php
        // Loop through the requests and display them
        while ($data = $req->fetch_assoc()) {
            $ID = $data["requestID"];
            $agentID = $data["agentID"];
            $agentname = $data["agent_name"];
            $Sender = $data["client_first_name"] . ' ' . $data["client_last_name"];
            $Received = date("M d, Y", strtotime($data["received_date"]));
            $Firstresort = substr($data["first_lodging"], 0, 50);
            $Firstunit = substr($data["first_unit"], 0, 30);
            if ($data["first_lodging"] == "") {
                $Title = $data["additional_resort_1"] . '... ';
            } else {
                $Title = $Firstresort . '... ';
            }
            if (date("M d, Y", strtotime($data["check_in"])) == "Jan 01, 0001") {
                $Starts = "Not Specified";
                $Ends = "";
            } else {
                $Starts = date("M d, Y", strtotime($data["check_in"]));
                $Ends = $data["check_out"];
            }
            $Traveler = 'A-' . $data["adults"] . ', C-' . $data["children"];
            $Status = $data["status"];
            if ($data["request_type"] == "individual") {
                $rqtype = "I";
            } elseif ($data["request_type"] == "group") {
                $rqtype = "G";
            }
        ?>
        <tr style="font-weight:normal; background:#FFF;">
            <td><?php echo $ID ?></td>
            <td style="min-width: 80px;"><?php echo $Sender ?></td>
            <td style="text-align:center;"><?php echo $Received ?></td>
            <td style="background:#E1E9ED;text-align:center;"><?php echo $rqtype ?></td>
            <td style="background:#E1E9ED;width:330px;max-width:330px;"><?php echo $Title ?></td>
            <td style="background:#E1E9ED; text-align:center;min-width: 75px;"><?php echo $Starts ?></td>
            <td style="padding:0px;">
                <?php if ($rqtype == 'G') { ?>
                    <button onclick="location.href='index.php?page=rqdetailsgroup&IDrequest=<?php echo $ID ?>'"><img style="margin:2px;" src="images/details.png" /></button></td>
                <?php
                } else {
                ?>
                    <button onclick="location.href='index.php?page=rqdetails&IDrequest=<?php echo $ID ?>'"><img style="margin:2px;" src="images/details.png" /></button></td>
                <?php
                }
                ?>
            <td style="background:#DBEAD3; text-align:center;">
                <select class="agentchange<?php echo $ID ?>" name="agentchange" onchange="selectagent(this,<?php echo $ID ?>)">
                    <option value=""><?php echo $agentname ?></option>
                    <optgroup label="-------"></optgroup>
                    <?php echo $agentsdropbox ?>
                </select>
            </td>
            <td style="width:20px; padding:0px;background:#DBEAD3;">
                <button onclick="location.href='index.php?page=requestmailtoagent&IDrequestmail=<?php echo $ID ?>&agentnamerequestmail=<?php echo $agentname ?>'"><img style="margin:2px;" src="images/mail.png" /></button>
            </td>
            <td style="background:#DBEAD3; text-align:center;width:80px">
                <?php echo $Status ?>
            </td>
            <td style="width:20px; padding:0px;background:#DBEAD3;">
                <button href="#" class="toggler" data-prod-cat="<?php echo $ID ?>"><img id="statusimg<?php echo $ID ?>" style="margin:2px;" src="images/edit.png" /></button></td>
        </tr>
        <?php
    } // End of while loop for displaying data
}
?>
    </table>
</div>