<?php
$mysqli = new mysqli("localhost", "username", "password", "database");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['selecteddate'])) {
    $formatshow = 'm/d/Y';
    $reportfrom = date("Y-m-d", strtotime($_POST['reportfrom']));
    $reportto = date("Y-m-d", strtotime($_POST['reportto']));
    $reportfromshow = date($formatshow, strtotime($reportfrom));
    $reporttoshow = date($formatshow, strtotime($reportto));
} else {
    $format = 'Y-m-d';
    $formatshow = 'm/d/Y';
    $reportfrom = date("Y-m-d", strtotime('-7 day'));
    $reportto = date("Y-m-d");
    $reportfromshow = date($formatshow, strtotime($reportfrom));
    $reporttoshow = date($formatshow, strtotime($reportto));
}

$sql = "SELECT c.`campaign_tid`, c.`campaign_name`, a.`affiliate_name` 
        FROM `tbl_campaigns` c 
        JOIN `tbl_affiliates` a ON (a.`affiliate_id`=c.`affiliate_id`);";
$query = $mysqli->query($sql);
if (!$query) {
    die("Database query failed: " . $mysqli->error);
}
$campaigns = array();
while ($row = $query->fetch_assoc()) {
    $campaigns[$row["campaign_tid"]]["campaign_name"] = $row["campaign_name"];
    $campaigns[$row["campaign_tid"]]["affiliate_name"] = $row["affiliate_name"];
}

$reqnum = $mysqli->query("SELECT t1.*, t2.* 
                          FROM requests t1 
                          INNER JOIN member t2 ON t2.id=t1.agentID 
                          WHERE t1.last_change BETWEEN '$reportfrom' AND '$reportto' 
                          GROUP BY t1.requestID 
                          ORDER BY t1.last_change DESC");
if (!$reqnum) {
    die("Database query failed: " . $mysqli->error);
}
$totalsum = 0;

if ($reqnum->num_rows == 0) {
    echo '<div class="norequests">No request</div>';
} else {
    ?>
    <div class="datatablereport">
    <table id="reportingtable" width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
        <thead>
            <tr style="font-size: 11px;border-bottom:2px solid #000; background:#526486; color:#fff">
                <th>ID</th>
                <th style="text-align:center;">QR Received</th>
                <th style="width:20px">Client</th>
                <th style="text-align:center;">Client's email</th>
                <th style="text-align:center;">Destination</th>
                <th style="text-align:center;">Departure date</th>
                <th>QR from</th>
                <th>Agent</th>
                <th style="text-align:center;">QR Status</th>
                <th style="text-align:center;color:#99BF82;">$ BOOKED</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($data = $reqnum->fetch_assoc()) {
            $ID = $data["requestID"];
            $agentname = $data["agent_name"];
            $Sender = $data["client_first_name"] . ' ' . $data["client_last_name"];
            $Received = date("M d, Y", strtotime($data["received_date"]));
            $last_change = date("M d, Y", strtotime($data["last_change"]));
            $Status = $data["status"];
            $campaign_name = "";
            $affiliate_name = "";
            if ($data["session_key"]) {
                $campaign_tid = "";
                $sql = "SELECT `campaign_tid` FROM `tbl_feedbacks` WHERE `session_key`='" . $data["session_key"] . "' LIMIT 1";
                $query = $mysqli->query($sql);
                if ($query) {
                    list($campaign_tid) = $query->fetch_row();
                    if ($campaign_tid) {
                        $campaign_name = $campaigns[$campaign_tid]["campaign_name"];
                        $affiliate_name = $campaigns[$campaign_tid]["affiliate_name"];
                    }
                }
            }
            if ($data['finalized_date'] == NULL || $data['finalized_date'] == "" || $data['finalized_date'] == 0 || $data['finalized_date'] == "0000-00-00") {
                $Statusdate = "";
            } else {
                $Statusdate = date("M d,Y", strtotime($data["finalized_date"]));
            }
            ?>
            <tr style="font-weight:bold;background:#CAE6F1; ">
                <td><a href="index.php?page=rqdetails&IDrequest=<?php echo $ID ?>"><?php echo $ID ?></a></td>
                <td style="text-align:center;"><?php echo $Received ?></td>
                <td><?php echo $Sender ?></td>
                <td><?php echo $data["client_email"] ?></td>
                <td><?php echo $data["first_lodging"] ?></td>
                <td style="text-align:center;"><?php echo $data["check_in"] ?></td>
                <td><?php echo $data["request_from"] ?></td>
                <td><?php echo $agentname ?></td>
                <td style="text-align:center;"> <?php echo $Status ?></td>
                <td></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th style="text-align:center;">QR Received</th>
                <th style="width:20px">Client</th>
                <th style="text-align:center;">Client's email</th>
                <th style="text-align:center;">Destination</th>
                <th style="text-align:center;">Departure date</th>
                <th>QR from</th>
                <th>Agent</th>
                <th style="text-align:center;">QR Status</th>
                <th style="text-align:center;color:#99BF82;">$ BOOKED</th>
            </tr>
        </tfoot>
    </table>
    </div>
    <?php
}
?>