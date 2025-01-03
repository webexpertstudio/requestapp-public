<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
}
?>
<div id="statusbuttons">
    <button id="tab" onclick="location.href='index.php?page=affiliates'">Affiliates</button>
    <button id="tab" onclick="location.href='index.php?page=informers'">Informers</button>
    <button id="tab" onclick="location.href='index.php?page=campaigns'">Campaigns</button>
    <button id="tab" onclick="location.href='index.php?page=tracking'">Tracking Reports</button>
    <button id="tab" onclick="location.href='index.php?page=affiliate&action=new'">Add New Affiliate</button>
</div>
<div class="datatable">
    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
        <tr style="font-size: 13px;border-bottom:2px solid #000">
            <td>Affiliate Name</td>
            <td>Affiliate URL</td>
            <td>Campaigns</td>
        </tr>
        <?php
        $sql = "SELECT a.*, COUNT(c.`campaign_tid`) as counts 
                FROM `tbl_affiliates` a
                LEFT JOIN tbl_campaigns c ON (a.`affiliate_id` = c.`affiliate_id`)
                GROUP BY a.`affiliate_id`";
        
        // Zamena mysql_query sa mysqli_query (u okviru funkcije db_query)
        $query = db_query($sql);
        
        // Zamena mysql_fetch_assoc sa mysqli_fetch_assoc
        while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr style="font-weight:normal;">
                <td><a href="index.php?page=affiliate&action=update&affiliate_id=<?php echo $row["affiliate_id"]; ?>"><?php echo $row["affiliate_name"]; ?></a></td>
                <td><a href="index.php?page=affiliate&action=update&affiliate_id=<?php echo $row["affiliate_id"]; ?>"><?php echo $row["affiliate_url"]; ?></a></td>
                <?php if ($row["counts"]) { ?>
                    <td><a href="index.php?page=campaigns&affiliate_id=<?php echo $row["affiliate_id"]; ?>">Show campaigns: <?php echo $row["counts"]; ?></a></td>
                <?php } else { ?>
                    <td><a href="index.php?page=campaign&action=insert&affiliate_id=<?php echo $row["affiliate_id"]; ?>">Add campaign</a></td>
                <?php } ?>
            </tr>
        <?php }
        
        // Zamena mysql_num_rows sa mysqli_num_rows
        if (mysqli_num_rows($query) == 0) { ?>
            <tr style="font-weight:normal;">
                <td colspan="2">No saved records</td>
            </tr>
        <?php } ?>
        
        <tr style="font-weight:normal;">
            <td colspan="3"><a href="index.php?page=affiliate&action=insert">Add new Affiliate</a></td>
        </tr>
    </table>
</div>
