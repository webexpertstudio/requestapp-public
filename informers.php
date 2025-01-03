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
    <button id="tab" onclick="location.href='index.php?page=informer&action=new'">Add New Informer</button>
</div>
<div class="datatable">
    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
        <tr style="font-size: 13px;border-bottom:2px solid #000">
            <td>Informer Name</td>
            <td>Informer URL</td>
        </tr>
        <?php
        $sql = "SELECT * FROM tbl_informers";
        $query = mysqli_query($connection, $sql); // Koristimo mysqli_query sa konekcijom

        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr style="font-weight:normal;">
                    <td><a href="index.php?page=informer&action=update&informer_id=<?php echo $row["informer_id"]; ?>"><?php echo $row["informer_name"]; ?></a></td>
                    <td><a href="index.php?page=informer&action=update&informer_id=<?php echo $row["informer_id"]; ?>"><?php echo $row["informer_url"]; ?></a></td>
                </tr>
            <?php
            }
            if (mysqli_num_rows($query) == 0) { ?>
                <tr style="font-weight:normal;">
                    <td colspan="2">No saved records</td>
                </tr>
            <?php
            }
        } else { ?>
            <tr style="font-weight:normal;">
                <td colspan="2">Error fetching records</td>
            </tr>
        <?php } ?>
        <tr style="font-weight:normal;">
            <td colspan="2"><a href="index.php?page=informer&action=insert">Add new Informer</a></td>
        </tr>
    </table>
</div>