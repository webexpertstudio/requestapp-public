<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
}

// Konekcija sa bazom
require_once("db_connection.php"); // Pretpostavka: $dbconn sadrži mysqli konekciju

// Učitavanje informera
$sql = "SELECT `informer_id`, `informer_url` FROM tbl_informers i";
$query = $dbconn->query($sql);
if (!$query) {
    die("Error fetching informers: " . $dbconn->error);
}

$informers = array();
while ($row = $query->fetch_assoc()) {
    $informers[$row["informer_id"]] = $row["informer_url"];
}

// Učitavanje podataka
$sql = "SELECT 
            f.`title`, 
            f.`informer_id`, 
            f.`URI`, 
            COUNT(DISTINCT f.`session_key`) AS num, 
            AVG(TIME_TO_SEC(TIMEDIFF(`active_until`, `inserted`))) AS avg_stay
        FROM tbl_feedbacks f ";
$sql .= $WHERE; // Pretpostavka: $WHERE je sigurno kreiran
$sql .= " GROUP BY f.`informer_id`, f.`title`, f.`URI`
          ORDER BY f.`title`";

$query = $dbconn->query($sql);
if (!$query) {
    die("Error fetching feedback data: " . $dbconn->error);
}

$total_sum = 0;
$data = array();
while ($row = $query->fetch_assoc()) {
    $row["title"] = $row["title"] ?: "(no title / not found)";
    if (isset($informers[$row["informer_id"]])) {
        $row["URI"] = $informers[$row["informer_id"]] . $row["URI"];
    }
    $data[$row["title"]][$row["URI"]] = [
        "num" => $row["num"],
        "avg_stay" => $row["avg_stay"]
    ];
    $total_sum += $row["num"];
}
?>

<table>
    <tr style="font-size: 13px; border-bottom: 2px solid #000">
        <td colspan="2">Page Title</td>
        <td align="center" width="60px">Average Time on Page [s]</td>
        <td align="center" width="60px">Number of Visits</td>
        <td align="center" width="60px">%</td>
    </tr>
    <?php
    $id = 0;
    foreach ($data as $title => $lv1) {
        $id++;
        $local_sum = array_sum(array_column($lv1, "num"));
        $sum_avg = array_sum(array_map(fn($lv2) => $lv2["avg_stay"] * $lv2["num"], $lv1));
        $local_avg = ($local_sum > 0) ? $sum_avg / $local_sum : 0;

        if (count($lv1) > 1) { ?>
            <tr style="font-weight:normal;">
                <td colspan="2">
                    <a href="javascript:toggleMessage('<?php echo $id; ?>');">
                        <img border="0" align="left" id="toggle_img_<?php echo $id; ?>" src="images/plus.gif">
                    </a>
                    <a href="index.php?page=tracking&report=history&from=<?php echo $get["from"]; ?>&to=<?php echo $get["to"]; ?>&title=<?php echo urlencode($title); ?>"><?php echo htmlspecialchars($title); ?></a>
                </td>
                <td align="center"><?php echo round($local_avg, 1); ?> [s]</td>
                <td align="center"><?php echo $local_sum; ?></td>
                <td align="center"><?php echo ($total_sum > 0) ? round($local_sum / $total_sum * 100, 2) : "-"; ?> %</td>
            </tr>
            <tr id="msg_<?php echo $id; ?>" class="slideHidden">
                <td colspan="7">
                    <table width="95%" align="right">
                        <?php foreach ($lv1 as $URI => $lv2) { ?>
                            <tr>
                                <td><a target="_blank" href="<?php echo htmlspecialchars($URI); ?>"><?php echo htmlspecialchars($URI); ?></a></td>
                                <td align="center"><?php echo round($lv2["avg_stay"], 1); ?> [s]</td>
                                <td align="center"><?php echo $lv2["num"]; ?></td>
                                <td align="center"><?php echo ($local_sum > 0) ? round($lv2["num"] / $local_sum * 100, 2) : "-"; ?> %</td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        <?php } else {
            foreach ($lv1 as $URI => $lv2) { ?>
                <tr style="font-weight:normal;">
                    <td colspan="2">
                        <img border="0" align="left" id="toggle_img_<?php echo $id; ?>" src="images/empty.png">
                        <a href="index.php?page=tracking&report=history&from=<?php echo $get["from"]; ?>&to=<?php echo $get["to"]; ?>&title=<?php echo urlencode($title); ?>"><?php echo htmlspecialchars($title); ?></a>
                    </td>
                    <td align="center"><?php echo round($lv2["avg_stay"], 1); ?> [s]</td>
                    <td align="center"><?php echo $local_sum; ?></td>
                    <td align="center"><?php echo ($total_sum > 0) ? round($local_sum / $total_sum * 100, 2) : "-"; ?> %</td>
                </tr>
            <?php }
        }
    }

    if ($query->num_rows == 0) { ?>
        <tr style="font-size: 13px; border-bottom: 2px solid #000">
            <td colspan="5"><font color="red">No results</font></td>
        </tr>
    <?php } else { ?>
        <tr style="font-size: 13px; border-bottom: 2px solid #000">
            <td colspan="3"></td>
            <td align="center"><?php echo $total_sum; ?></td>
            <td align="center">100%</td>
        </tr>
    <?php } ?>
</table>