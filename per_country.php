<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
}

// Konekcija sa bazom
require_once("db_connection.php"); // Pretpostavka: $dbconn sadrži mysqli konekciju

// Učitavanje kampanja
$sql = "SELECT `campaign_tid`, `campaign_name` FROM tbl_campaigns";
$query = $dbconn->query($sql);
if (!$query) {
    die("Error fetching campaigns: " . $dbconn->error);
}

$campaigns = array();
while ($row = $query->fetch_assoc()) {
    $campaigns[$row["campaign_tid"]] = $row["campaign_name"];
}

// Učitavanje podataka o povratnim informacijama
$sql = "SELECT f.`client_country`, f.`campaign_tid`, COUNT(DISTINCT f.`session_key`) AS num
        FROM `tbl_feedbacks` f ";
$sql .= $WHERE; // Pretpostavka: $WHERE je sigurno kreiran
$sql .= " GROUP BY f.`client_country`, f.`campaign_tid`
          ORDER BY f.`client_country`, f.`campaign_tid`";

$query = $dbconn->query($sql);
if (!$query) {
    die("Error fetching feedback data: " . $dbconn->error);
}

$total_sum = 0;
$data = array();
while ($row = $query->fetch_assoc()) {
    $data[$row["client_country"]][$row["campaign_tid"]] = $row["num"];
    $total_sum += $row["num"];
}
?>

<table>
    <tr style="font-size: 13px; border-bottom: 2px solid #000">
        <td>Country</td>
        <td width="200px">Campaign</td>
        <td width="60px">Total</td>
        <td width="60px">%</td>
    </tr>
    <?php
    foreach ($data as $client_country => $lv1) {
        foreach ($lv1 as $campaign_tid => $num) { ?>
            <tr style="font-weight:normal;">
                <td>
                    <a href="index.php?page=tracking&report=history&country=<?php echo htmlspecialchars($client_country); ?>&campaign_tid=<?php echo $campaign_tid; ?>">
                        <?php echo $client_country ? htmlspecialchars($client_country) : "(unknown)"; ?>
                    </a>
                </td>
                <td>
                    <a href="index.php?page=tracking&report=history&country=<?php echo htmlspecialchars($client_country); ?>&campaign_tid=<?php echo $campaign_tid; ?>">
                        <?php echo isset($campaigns[$campaign_tid]) ? htmlspecialchars($campaigns[$campaign_tid]) : "-"; ?>
                    </a>
                </td>
                <td><?php echo $num; ?></td>
                <td><?php echo ($total_sum) ? round($num / $total_sum * 100, 2) : "-"; ?>%</td>
            </tr>
        <?php }
    }

    if ($query->num_rows == 0) { ?>
        <tr style="font-weight:normal;">
            <td colspan="4"><font color="red">No data for selected filter!</font></td>
        </tr>
    <?php } ?>
    <tr style="font-size: 13px; border-bottom: 2px solid #000">
        <td></td>
        <td></td>
        <td><?php echo $total_sum; ?></td>
        <td>100%</td>
    </tr>
</table>