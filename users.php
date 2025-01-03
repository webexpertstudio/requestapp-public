<?php
if ($_SESSION["sess_agent_status"] != "admin") {
    die("Access denied");
} 

// Povezivanje sa bazom putem MySQLi (pretpostavljam da je `$dbconn` već definisan negde)
?>

<div class="datatable">
    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-weight:bold;font-size: 12px;">
        <tr style="font-size: 13px;border-bottom:2px solid #000">
            <td>Username</td>
            <td>Name</td>
            <td>Role</td>
        </tr> 
        <?php
        // Pripremljeni upit za dobijanje korisnika
        $query = $dbconn->query("SELECT * FROM member WHERE NOT id='0'");

        // Prolazimo kroz rezultate
        while ($row = $query->fetch_assoc()) { ?>
            <tr style="font-weight:normal;">
                <td><a href="index.php?page=permissions&id=<?php echo $row["id"]; ?>"><?php echo $row["username"] ?></a></td>
                <td><a href="index.php?page=permissions&id=<?php echo $row["id"]; ?>"><?php echo $row["agent_name"] ?></a></td>
                <td><a href="index.php?page=permissions&id=<?php echo $row["id"]; ?>"><?php echo $row["agent_status"] ?></a></td>
            </tr> 
        <?php
        }
        ?>
    </table><br/>
    
    <div class="newuser" style="padding:10px;background:#fff">
        <h2>Add new user</h2>
        <form action="createnewuserscript.php" method="post" enctype="multipart/form-data">
            <p>Username: <input type="text" name="username"></p>
            <p>Password: <input type="text" name="password"></p>
            <p>Email: <input type="text" name="agent_email"></p>
            <p>Status:  
                <select name="agent_status">
                    <option value="agent">Agent</option>
                    <option value="manager">Manager</option>
                </select>
            </p>
            <p>Agent name: <input type="text" name="agent_name"></p>
            <p>Phone: <input type="text" name="agent_phone"></p>
            <p>Agent picture: <input type="file" name="fileToUpload" id="fileToUpload"><br/> 
                <span style="font-size:10px">(picture size 70x70px and only JPG, JPEG, PNG & GIF files are allowed)</span>
            </p>
            <input type="submit" value="Submit">
        </form>
    </div>
</div>