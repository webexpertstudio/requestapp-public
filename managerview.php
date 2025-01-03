<?php
if ($_SESSION["sess_agent_status"] != "admin" && $_SESSION["sess_agent_status"] != "manager") {
    die("Access denied");
}

// Priprema SQL upita na osnovu statusa korisnika
if ($_SESSION["sess_agent_status"] == "manager") {
    $sql = "SELECT m.* 
            FROM member m
            JOIN manager_agents ma ON (
                m.id = ma.agent_id 
                AND ma.manager_id = ?
            )
            WHERE m.id != '0'";
} elseif ($_SESSION["sess_agent_status"] == "admin") {
    $sql = "SELECT * FROM member WHERE NOT id = '0'";
}

// Priprema SQL izjave
$stmt = $dbconn->prepare($sql);
if (!$stmt) {
    die("Failed to prepare SQL statement: " . $dbconn->error);
}

// Bindovanje parametara za menadžera
if ($_SESSION["sess_agent_status"] == "manager") {
    $stmt->bind_param("i", $_SESSION['sess_user_id']);
}

// Izvršavanje izjave
$stmt->execute();

// Dobijanje rezultata
$result = $stmt->get_result();
if (!$result) {
    die("Failed to fetch data: " . $dbconn->error);
}

// Generisanje HTML koda za dropdown sa agentima
$agentsdropbox = '<select>';
while ($agentsdata = $result->fetch_assoc()) {
    $agentsdropbox .= '<option value="' . htmlspecialchars($agentsdata["agent_name"]) . '">' . htmlspecialchars($agentsdata["agent_name"]) . '</option>';
}
$agentsdropbox .= '</select>';

// Generisanje status dropdown
$statusdropbox = '<select>
    <option value="">-</option>
    <option value="Quote Sent">Quote Sent</option>
    <option value="Offer Reminder">Offer Reminder</option>
    <option value="Booked">Booked</option>
    <option value="Cancelled">Cancelled</option>
</select>';
?>

<div id="statusbuttons">
    <div style="float: left;padding: 2px;width: 65px;">Requests:</div> 
    <button id="tab" onclick="location.href='index.php?page=managerview'">All</button>
    <button id="tab" onclick="location.href='index.php?page=managerview&status=New'">New</button>
    <button id="tab" onclick="location.href='index.php?page=managerview&status=Quote Sent'">Quote Sent</button>
    <button id="tab" onclick="location.href='index.php?page=managerview&status=Booked'">Booked</button>
    <button id="tab" onclick="location.href='index.php?page=managerview&status=Cancelled'">Cancelled</button><br>
</div>

<?php
require("tables-manager.php");
require("workflow.php");

// Otvaranje reda tabele
if (isset($_GET['rqopen'])) {
    $rqopen = intval($_GET['rqopen']);
?>
<script>
    $(document).ready(function(){
        $(".cat<?php echo $rqopen ?>").show();
    });
</script>
<?php
}
?>