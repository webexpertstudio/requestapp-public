<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
}
?>
<tr style="font-size: 13px; border-bottom: 2px solid #000">
    <td><?php echo $get['title'] ? $get['title'] : "All pages"; ?></td>
    <td width="80px" align="center">Campaign</td>
    <td width="80px" align="center">Country</td>
    <td width="120px" align="center">Time of access</td>
    <td width="60px" align="center">Time spent on page</td>
</tr>

<?php
// Korišćenje mysqli za upite
$sql = "SELECT `informer_id`, `informer_url` FROM tbl_informers i";
$query = mysqli_query($connection, $sql); // db_query() zamenjeno sa mysqli_query
$informers = array();
while ($row = mysqli_fetch_assoc($query)) {
    $informers[$row["informer_id"]] = $row["informer_url"];
}

$sql = "SELECT `campaign_tid`, `campaign_name` FROM tbl_campaigns";
$query = mysqli_query($connection, $sql); // db_query() zamenjeno sa mysqli_query
$campaigns = array();
while ($row = mysqli_fetch_assoc($query)) {
    $campaigns[$row["campaign_tid"]] = $row["campaign_name"];
}

$sql = "SELECT 
            f.`informer_id`, 
            f.`URI`, 
            f.`client_country`, 
            f.`campaign_tid`, 
            DATE_FORMAT(f.`inserted`, '%H:%i:%s %d.%m.%Y') as arrived, 
            TIME_TO_SEC(TIMEDIFF(f.`active_until`, f.`inserted`)) as stay
        FROM `tbl_feedbacks` f";
// Dodaj WHERE ako postoji
$sql .= $WHERE;
$sql .= " ORDER BY f.`inserted` DESC";
$query = mysqli_query($connection, $sql); // db_query() zamenjeno sa mysqli_query

$total_sum = 0;
while ($row = mysqli_fetch_assoc($query)) {
    $row["URI"] = $informers[$row["informer_id"]] . $row["URI"];
?>
<tr style="font-weight: normal;">
    <td><a href="<?php echo $row["URI"]; ?>"><?php echo substr($row["URI"], 0, 100) . "<br>" . substr($row["URI"], 100, 100) .  "<br>" . substr($row["URI"], 200); ?></a></td>
    <td align="center"><a href=""><?php echo $campaigns[$row["campaign_tid"]] ? $campaigns[$row["campaign_tid"]] : "-"; ?></a></td>
    <td align="center"><a href=""><?php echo $row["client_country"] ? $row["client_country"] : "(unknown)"; ?></a></td>
    <td align="center"><a href=""><?php echo $row["arrived"] ?></a></td>
    <td align="center"><a href=""><?php echo $row["stay"] ? $row["stay"] : "< 5"; ?> [s]</a></td>
</tr>
<?php
}

if (mysqli_num_rows($query) == 0) { ?>
    <tr style="font-weight: normal;">
        <td colspan="5"><font color="red">No data for selected filter!</font></td>
    </tr>
<?php
}
?>