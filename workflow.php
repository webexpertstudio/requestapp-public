<h4>Agents' Workload</h4>
<div class="agentbuttons">
<table><tr>
<?php
// Konekcija sa bazom
$servername = "localhost";  // ili IP adresa servera
$username = "alien_requestadm";  // tvoje korisničko ime za bazu
$password = "o8^USIm]S05pd@$";  // tvoj password za bazu
$dbname = "alien_requestapp";  // ime baze podataka

// Kreiraj konekciju
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Proveri konekciju
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$assigned = "Assigned";

// Definisanje varijable $agent (ako se prosleđuje putem URL-a)
$agent = isset($_GET['agent']) ? $_GET['agent'] : null;  // dodaj ovo ako agent treba da bude prosleđen URL-om

// Inicijalizacija brojača $i za tabelu
$i = 0;

// Zamenjeno mysql_query sa mysqli_query
$agentbuttons = mysqli_query($connection, "SELECT * FROM member WHERE NOT id='0'");

while ($agentsbuttonsdata = mysqli_fetch_array($agentbuttons)) {
    $agentidbrojac = $agentsbuttonsdata["id"];

    // Zamenjeno mysql_query sa mysqli_query
    $statuscounter = mysqli_query($connection, "SELECT status FROM requests WHERE agentID='$agentidbrojac'");
    $scounter = mysqli_num_rows($statuscounter);

    // Zamenjeno mysql_query sa mysqli_query
    $assignedcounter = mysqli_query($connection, "SELECT status FROM requests WHERE agentID='$agentidbrojac' AND status='$assigned'");

    if ($assignedcounter) {
        $acounter = mysqli_num_rows($assignedcounter);
    } else {
        // Zamenjeno mysql_error sa mysqli_error
        die(mysqli_error($connection));
    }

    // Postavi detalje za prikaz
    if ($agentsbuttonsdata["id"] == $agent) {
        $detailsview = "index.php?page=managerdetails&agentid=";
    } else {
        $detailsview = "index.php?page=agentdetails&agentid=";
    }
?>
<td>
    <div class="agentbox">
        <div class="agentbuttonleft" style="float:left">
            <img src="images/agents/<?php echo $agentsbuttonsdata["agent_pic"]; ?>">
        </div>
        <div class="agentbuttonright" style="float:right">
            <span style="font-size:12px;color:#506785; font-weight:bold;">
                <?php echo $agentsbuttonsdata["agent_name"]; ?>
            </span>
            <div style="margin:3px; font-size:9px;">QR
                <div class="requestnum">
                    <div class="requestnumleft">Pending<br><span style="font-weight:bold; color:red"><?php echo $acounter ?></span></div>
                    <div class="requestnumright">Total<br><b><?php echo $scounter ?></b></div>
                </div>
                <div class="agentdeatilsbutton">
                    <button onClick="location='<?php echo $detailsview.''.$agentidbrojac ?>&agentname=<?php echo $agentsbuttonsdata["agent_name"] ?>&agentpic=<?php echo $agentsbuttonsdata["agent_pic"]; ?>&pendingrq=<?php echo $acounter; ?>&totalrq=<?php echo $scounter; ?>&agentstatus=<?php echo $agentsbuttonsdata["agent_status"] ?>&agentphone=<?php echo $agentsbuttonsdata["agents_phone"] ?>&agentmail=<?php echo $agentsbuttonsdata["email"] ?>'">
                        show details
                    </button>
                </div>
            </div>
        </div>
    </div>
</td>
<?php
    $i++;
    if ($i == 4) {
        echo "</tr>\n<tr>";
        $i = 0;
    }
}
?>
</tr></table>
</div>