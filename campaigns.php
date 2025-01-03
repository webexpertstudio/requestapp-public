<?php
if($_SESSION["sess_agent_status"]!="admin") {
    die("Access denied");
}

$args = array(
    'page' => FILTER_SANITIZE_STRING, 
    'informer_id' => FILTER_VALIDATE_INT, 
    'affiliate_id' => FILTER_VALIDATE_INT, 
);
$get = filter_input_array(INPUT_GET, $args);
?>
<div id="statusbuttons">
    <button id="tab" onclick="location.href='index.php?page=affiliates'">Affiliates</button>
    <button id="tab" onclick="location.href='index.php?page=informers'">Informers</button>
    <button id="tab" onclick="location.href='index.php?page=campaigns'">Campaigns</button>
    <button id="tab" onclick="location.href='index.php?page=tracking'">Tracking Reports</button>
    <button id="tab" onclick="location.href='index.php?page=campaign&action=insert'">Add new Campaign</button>

    <form method="GET">
        <input type="hidden" name="page" value="<?php echo $get["page"]; ?>">
        Filter: 
        Affiliate: 
        <select name="affiliate_id" OnClick="this.form.submit();">
            <option value="">All</option><?php
            // Use mysqli_query for database connection
            $sql = "SELECT `affiliate_id`, `affiliate_name`
                    FROM `tbl_affiliates` 
                    ORDER BY `affiliate_name`";
            $query = mysqli_query($connection, $sql); // assuming $connection is your mysqli connection
            while(list($affiliate_id, $affiliate_name) = mysqli_fetch_row($query)) {?>
                <option value="<?php echo $affiliate_id; ?>" <?php echo (isset($get["affiliate_id"]) && $get["affiliate_id"]==$affiliate_id)?"selected":""; ?> ><?php echo $affiliate_name; ?></option><?php
            } ?>
        </select>
        Informer: 
        <select name="informer_id" OnClick="this.form.submit();">
            <option value="">All</option><?php
            // Use mysqli_query for database connection
            $sql = "SELECT `informer_id`, `informer_name`
                    FROM `tbl_informers` 
                    ORDER BY `informer_name`";
            $query = mysqli_query($connection, $sql); // assuming $connection is your mysqli connection
            while(list($informer_id, $informer_name) = mysqli_fetch_row($query)) {?>
                <option value="<?php echo $informer_id; ?>" <?php echo (isset($get["informer_id"]) && $get["informer_id"]==$informer_id)?"selected":""; ?> ><?php echo $informer_name; ?></option><?php
            } ?>
        </select>
        <input type="submit" value="Filter">
    </form>
</div>    
<div class="datatable">
    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
        <tr style="font-size: 13px;border-bottom:2px solid #000">
            <td>Campaign</td>
            <td>Type</td>
            <td>Affiliate</td>
            <td>Toward Informer</td>
            <td>Campaign link</td>
        </tr> <?php
        $sql = "SELECT * 
                FROM tbl_campaigns c
                JOIN tbl_informers i ON (i.informer_id=c.informer_id)
                JOIN tbl_affiliates a ON (a.affiliate_id=c.affiliate_id)
                WHERE 1=1 ";
        if(isset($get["affiliate_id"]) && $get["affiliate_id"]) {
            $sql.= " AND c.`affiliate_id`='".input_db($get["affiliate_id"])."' ";
        }
        if(isset($get["informer_id"]) && $get["informer_id"]) {
            $sql.= " AND c.`informer_id`='".input_db($get["informer_id"])."' ";
        }        
        $query = mysqli_query($connection, $sql); // assuming $connection is your mysqli connection
        while($row = mysqli_fetch_assoc($query)) { ?>
            <tr style="font-weight:normal;">
                <td><a href="index.php?page=campaign&action=update&campaign_tid=<?php echo $row["campaign_tid"]; ?>"><?php echo $row["campaign_name"] ?></a></td>
                <td><a href="index.php?page=campaign&action=update&campaign_tid=<?php echo $row["campaign_tid"]; ?>"><?php echo $row["campaign_type"] ?></a></td>
                <td><a href="index.php?page=affiliate&action=update&affiliate_id=<?php echo $row["affiliate_id"]; ?>"><?php echo $row["affiliate_name"] ?></a></td>
                <td><a href="index.php?page=informer&action=update&informer_id=<?php echo $row["informer_id"]; ?>"><?php echo $row["informer_name"] ?></a></td>
                <td><a target="_blank" href="<?php echo $row["campaign_url"]; ?><?php echo $row["url_var"]; ?><?php echo $row["campaign_tid"]; ?>"><?php echo $row["campaign_url"]; ?><?php echo $row["url_var"]; ?><?php echo $row["campaign_tid"]; ?></a></td>
            </tr> <?php
        } 
        if(!mysqli_num_rows($query)) { ?>
            <tr style="font-weight:normal;">
                <td colspan="2"><font color="red">No saved records for this filter!! Try "<a href="index.php?page=campaigns">All data</a>"</td>
            </tr><?php
        } ?>
        <tr style="font-weight:normal;">
            <td colspan="5"><a href="index.php?page=campaign&action=insert<?php echo isset($get["affiliate_id"])?"&affiliate_id=".$get["affiliate_id"]:""?><?php echo isset($get["informer_id"])?"&informer_id=".$get["informer_id"]:""?>">Add new Campaign</a></td>
        </tr>
    </table>
</div>