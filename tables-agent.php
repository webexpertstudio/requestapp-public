<?php
if (isset($_GET['status']))
{
    $status = $_GET['status'];
    $reqnum = mysqli_query($dbconn, "SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id=t1.agentID WHERE t1.status='$status' AND t2.id='$agentid' ORDER BY t1.requestID DESC") or die ("Baza nije dostupna 1!");
    $title = 'Status: '.$_GET['status'];
}
else
{
    $reqnum = mysqli_query($dbconn, "SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id=t1.agentID WHERE t2.id='$agentid' ORDER BY t1.requestID") or die ("Baza nije dostupna 3!");
    $title = "All Requests";
}

mysqli_query($dbconn, "SET NAMES UTF8");
if (mysqli_num_rows($reqnum) == 0)
{
    echo '<div class="norequests">No request</div>';
}
else
{
    require ("includes/numofrows.php");

    ///////////////////
    if (isset($_GET['status']))
    {
        $status = $_GET['status'];
        $req = mysqli_query($dbconn, "SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id=t1.agentID WHERE t1.status='$status' AND t2.id='$agentid' ORDER BY t1.requestID DESC LIMIT $offset, $rowsperpage");
    }
    else
    {
        $req = mysqli_query($dbconn, "SELECT t1.*, t2.* FROM requests t1 INNER JOIN member t2 ON t2.id=t1.agentID WHERE t2.id='$agentid' ORDER BY t1.requestID DESC LIMIT $offset, $rowsperpage");
    }
    /////////////////////////

    mysqli_query($dbconn, "SET NAMES UTF8");
?>

<div class="datatable">
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
    <tr>
        <td colspan="7" class="qouterequesttext" style="height:30px">Quote Request Details - <?php echo $title ?></td>
        <td colspan="2" style="background:#547743; text-align:center; color:white;font-size: 15px;">Manage Quote Requests</td>
    </tr>
    <tr style="font-size: 13px;border-bottom:2px solid #000">
        <td>ID</td>
        <td style="min-width: 80px;">Sender</td>
        <td style="text-align:center; min-width: 75px;">QR Received</td>
        <td style="background:#A7BFCB;">T</td>
        <td style="background:#A7BFCB;">Title</td>
        <td style="background:#A7BFCB; text-align:center; min-width: 75px;">Trip Starts</td>
        <td style="text-align:center; padding:0px;">View</td>
        <td style="background:#99BF82; text-align:center;">Status</td>
        <td style="background:#99BF82; text-align:center; padding:0px;">Edit</td>
    </tr>
    <?php
    while($data = mysqli_fetch_assoc($req))
    {
        $ID = $data["requestID"];
        $agentID = $data["agentID"];
        $agentname = $data["agent_name"];
        $Sender = $data["client_first_name"].' '.$data["client_last_name"];
        $Received = date("M d, Y", strtotime($data["received_date"]));
        $Firstresort = substr($data["first_lodging"], 0, 50);
        $Firstunit = substr($data["first_unit"], 0, 30);
        if ($data["first_lodging"] == "")
        {
            $Title = $data["additional_resort_1"].'... ';
        }
        else
        {
            $Title = $Firstresort.'... ';
        }
        if (date("M d, Y", strtotime($data["check_in"])) == "Jan 01, 0001")
        {
            $Starts = "Not Specified";
            $Ends = "";
        }
        else
        {
            $Starts = date("M d, Y", strtotime($data["check_in"]));
            $Ends = $data["check_out"];
        }
        $Traveler = 'A-'.$data["adults"].', C-'.$data["children"];
        $Status = $data["status"];

        if ($data["request_type"] == "individual")
        {
            $rqtype = "I";
        }
        elseif ($data["request_type"] == "group")
        {
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
        <?php if ($rqtype == 'G')
        {?>
        <button onclick="location.href='index.php?page=rqdetailsgroup&IDrequest=<?php echo $ID ?>'"><img style="margin:2px;" src="images/details.png" /></button></td>
        <?php
        }
        else
        {
        ?>
        <button onclick="location.href='index.php?page=rqdetails&IDrequest=<?php echo $ID ?>'"><img style="margin:2px;" src="images/details.png" /></button></td>
        <?php
        }
        ?>
        <td style="background:#DBEAD3; text-align:center;width:80px">
        <?php echo $Status ?>
        </td>
        <td style="width:20px; padding:0px;background:#DBEAD3;">
        <button href="#" class="toggler" data-prod-cat="<?php echo $ID ?>"><img id="statusimg<?php echo $ID ?>" style="margin:2px;" src="images/edit.png" /></button></td>
    </tr>
    <tr class="cat<?php echo $ID ?>" style="display:none">
        <td colspan="10" style="width:auto;padding:0px">

        <div class="manageitinerary" style="width:320px;background:#DBEAD3;float:right;border-left: 1px solid #8099b3;">
            <h1 style="font-size:13px;font-weight:bold;margin: 0 0 5px 7px;">MANAGE ITINERARIES</h1>

            <?php
            $idrequest = $ID;
            $itinerarydata = mysqli_query($dbconn, "SELECT * FROM itineraries WHERE request_id='$idrequest' ORDER BY status_id ASC");
            if(mysqli_num_rows($itinerarydata) == 0)
            {
                echo '<div style="wight:200px;margin:0 auto;padding:15px;">No itineraries entered!</div><hr>';
            }
            else
            {
            ?>

            <table>
            <tr>
            <th>Itinerary ID</th>
            <th>SUM $</th>
            <th>Status</th>
            <th></th>
            <th></th>
            </tr>
            <?php
            while($data = mysqli_fetch_assoc($itinerarydata))
            {
                if ($data["itinerary_sum"] == NULL || $data["itinerary_sum"] == "")
                {
                    $itinerarysum = "";
                }
                else
                {
                    $itinerarysum = number_format($data["itinerary_sum"], 2, ",", ".");
                }
            ?>
            <tr>
            <form name="bookedid" action="index.php?page=changerecordstatus&IDrequest=<?php echo $idrequest ?>&change=update&tableview" method="POST">
            <td><input type="hidden" name="statusid" value="<?php echo $data["status_id"] ?>"><input class="id" type="text" name="itineraryid" value="<?php echo $data["itinerary_id"] ?>"></td>
            <td><input class="sum" type="text" name="itinerarysum" style="text-align:right" value="<?php echo $itinerarysum ?>" required="required"></td>
            <td><select name="itinerarystatus">
                <option value="<?php echo $data['itinerary_status'] ?>"><?php echo $data['itinerary_status'] ?></option>
                <optgroup label="-------"></optgroup>
                <option value="Quote Sent">Quote Sent</option>
                <option value="Booked">Booked</option>
                </select>
            </td>
            <td style="padding: 0 0 0 3px;"><button class="statusbutton" id="statusbutton" type="submit" name="statusbutton">
            <img id="statusimg<?php echo $ID ?>" style="margin:2px;" src="images/save1.png" />
            </button></td>
            </form>
            </tr>
            <?php
            }
            ?>
            </table>
            </fieldset>
            <hr><?php
            }
            ?>
            <h1 style="font-size:13px;font-weight:bold;margin: 0 0 5px 7px;">ADD NEW ITINERARY</h1>
            <table>
            <tr>
            <th>Itinerary ID</th>
            <th>SUM $</th>
            <th></th>
            <th></th>
            </tr>
            <tr>
            <form name="bookedid" action="index.php?page=changerecordstatus&IDrequest=<?php echo $idrequest ?>&change=new&tableview" method="POST">
            <td><input class="id" type="text" name="itineraryid"></td>
            <td><input class="sum" type="text" name="itinerarysum" style="text-align:right" required="required"></td>
            <td style="padding: 0 0 0 3px;"><button class="statusbutton" id="statusbutton" type="submit" name="statusbutton">
            <img id="statusimg<?php echo $ID ?>" style="margin:2px;" src="images/save1.png" />
            </button></td>
            </form>
            </tr>
            </table>
            <hr>
            <h1 style="font-size:13px;font-weight:bold;margin: 0 0 5px 7px;">CANCEL QUOTE REQUEST</h1>
            <table>
            <tr>
            <th>Status</th>
            <th></th>
            </tr>
            <tr>
            <form name="bookedid" action="index.php?page=changerecordstatus&IDrequest=<?php echo $idrequest ?>&change=cancel&tableview" method="POST">
            <td><select name="canceltype">
            <option></option>
            <option value="Duplicate">Duplicate</option>
            <option value="Expired">Expired</option>
            <option value="Not Valid">Not Valid</option>
            </select>
            </td>
            <td style="padding: 0 0 0 3px;"><button class="statusbutton" id="statusbutton" type="submit" name="statusbutton">
            <img id="statusimg<?php echo $ID ?>" style="margin:2px;" src="images/save1.png" />
            </button></td>
            </form>
            </tr>
            </table>
        </div>
    </td>
    </tr>
    <?php
    }
    ?>
 </table>
 </div>
<?php
require ("includes/pagination.php");
}
?>
<script>
function selectagent(sel, request)
{
    window.open('changerecord.php?IDrequest='+request+'&agentchange='+sel.value, '_self');
}

function Clear(index)
{
   document.getElementById(index).value = "";
}

$(document).ready(function(){
    $(".toggler").click(function(e){
        e.preventDefault();
        $('.cat'+$(this).attr('data-prod-cat')).toggle();
    });
});
</script>