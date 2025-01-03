<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
}

// Uključivanje fajla za konekciju sa bazom
require_once("db_connection.php"); // Pretpostavka: $dbconn je mysqli konekcija

// Obrada POST podataka
$args = array(
    'id' => FILTER_VALIDATE_INT, 
    'save' => FILTER_SANITIZE_STRING,
    'agent_status' => FILTER_SANITIZE_STRING,
    'agent_id' => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags' => FILTER_FORCE_ARRAY,
    ),
);
$post = filter_input_array(INPUT_POST, $args);

if (isset($post["save"]) && isset($post["id"])) {
    if (isset($post["agent_status"])) {
        $stmt = $dbconn->prepare("UPDATE member SET agent_status = ? WHERE id = ?");
        $stmt->bind_param("si", $post["agent_status"], $post["id"]);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($post["agent_id"])) {
        // Brisanje svih agenata pre ažuriranja
        $stmt = $dbconn->prepare("DELETE FROM manager_agents WHERE manager_id = ?");
        $stmt->bind_param("i", $post["id"]);
        $stmt->execute();
        $stmt->close();

        // Dodavanje agenata
        $stmt = $dbconn->prepare("INSERT INTO manager_agents (agent_id, manager_id) VALUES (?, ?)");
        foreach ($post["agent_id"] as $agent_id) {
            $stmt->bind_param("ii", $agent_id, $post["id"]);
            $stmt->execute();
        }
        $stmt->close();
    }
}

// Obrada GET podataka
$args = array(
    'id' => FILTER_VALIDATE_INT,
);
$get = filter_input_array(INPUT_GET, $args);

if (isset($get["id"])) {
    $stmt = $dbconn->prepare("SELECT * FROM member WHERE id = ?");
    $stmt->bind_param("i", $get["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}
?>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row["id"]); ?>">
    <div class="datatable">
        <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
            <tr>
                <td width="15%">Username:</td>
                <td><?php echo htmlspecialchars($row["username"] ?? ""); ?></td>
            </tr>
            <tr>
                <td width="15%">Name:</td>
                <td><?php echo htmlspecialchars($row["agent_name"] ?? ""); ?></td>
            </tr>
            <tr>
                <td width="15%">Role:</td>
                <td>
                    <select name="agent_status" OnChange="save.click();">
                        <option value="agent" <?php echo ($row["agent_status"] == "agent") ? "selected" : ""; ?>>Agent</option>
                        <option value="manager" <?php echo ($row["agent_status"] == "manager") ? "selected" : ""; ?>>Manager</option>
                        <option value="admin" <?php echo ($row["agent_status"] == "admin") ? "selected" : ""; ?>>Admin</option>
                    </select>
                </td>
            </tr>
            <?php if ($row["agent_status"] == "manager") { ?>
                <tr>
                    <td></td>
                    <td>
                        Allow to administer agents:<br>
                        <?php
                        $stmt = $dbconn->prepare("
                            SELECT m.id, m.agent_name, ma.agent_id AS my_agent
                            FROM member m
                            LEFT JOIN manager_agents ma ON (m.id = ma.agent_id AND ma.manager_id = ?)
                            WHERE agent_status = 'agent'
                        ");
                        $stmt->bind_param("i", $get["id"]);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($agent = $result->fetch_assoc()) { ?>
                            <input type="checkbox" name="agent_id[]" value="<?php echo htmlspecialchars($agent["id"]); ?>" 
                                <?php echo isset($agent["my_agent"]) ? "checked" : ""; ?>> 
                            <?php echo htmlspecialchars($agent["agent_name"]); ?><br>
                        <?php }
                        $stmt->close();
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" id="save" name="save" value="Save">
                </td>
            </tr>
        </table>
    </div>
</form>