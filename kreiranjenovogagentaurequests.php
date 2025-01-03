<?php
require("includes/connection.php"); // Poziva kod za konekciju na bazu podataka.

$username = mysqli_real_escape_string($connection, $_POST['username']);
$passwordentered = md5($_POST['password']); // Ovo je nepotrebno jer koristimo SHA-256.
$agent_email = mysqli_real_escape_string($connection, $_POST['agent_email']);
$agent_status = mysqli_real_escape_string($connection, $_POST['agent_status']);
$agent_name = mysqli_real_escape_string($connection, $_POST['agent_name']);
$agent_phone = mysqli_real_escape_string($connection, $_POST['agent_phone']);
$agent_picture = mysqli_real_escape_string($connection, $_POST['agent_picture']);

$hash = hash('sha256', $_POST['password']);

function createSalt()
{
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 3);
}

$salt = createSalt();
$password = hash('sha256', $salt . $hash);

// SQL upit za umetanje podataka
$query = "INSERT INTO member (username, password, email, salt, agent_status, agent_name, agents_phone, agent_pic) 
          VALUES ('$username', '$password', '$agent_email', '$salt', '$agent_status', '$agent_name', '$agent_phone', '$agent_picture')";

if (mysqli_query($connection, $query)) {
    echo "New agent added successfully.<br>";
} else {
    echo "Error: " . mysqli_error($connection) . "<br>";
}

mysqli_close($connection);

echo "Password hash: $password<br>";
echo "Salt: $salt<br>";
echo "Username: $username<br>";
?>