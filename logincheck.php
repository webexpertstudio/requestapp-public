<?php
session_start();
require ("includes/connection.php"); // Poziva kod za konekciju na bazu podataka.

$username = $_POST['username'];
$password = $_POST['password'];

// Očistimo unos da bismo se zaštitili od SQL injekcija
$username = $dbconn->real_escape_string($username);

// Pripremamo upit
$query = "SELECT id, username, password, salt, agent_status, agent_name
          FROM member
          WHERE username = ?";

$stmt = $dbconn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) { // User not found. So, redirect to login_form again.
    $_SESSION['odobreno'] = "ne";
    $_SESSION['porukalogin'] = "Agent username is invalid. Please try again...";
    header('Location: index.php');
    exit;
}

$userData = $result->fetch_assoc();
$hash = hash('sha256', $userData['salt'] . hash('sha256', $password));

if ($hash != $userData['password']) { // Incorrect password. So, redirect to login_form again.
    $_SESSION['odobreno'] = "ne";
    $_SESSION['porukalogin'] = "Password is incorrect. Please try again...";
    header('Location: index.php');
    exit;
} else { // Redirect to home page after successful login.
    session_regenerate_id();    
    unset($_SESSION['porukalogin']);
    $_SESSION['odobreno'] = "da";
    $_SESSION['sess_user_id'] = $userData['id'];
    $_SESSION['sess_username'] = $userData['username'];
    $_SESSION['sess_agent_name'] = $userData['agent_name'];
    $_SESSION['sess_agent_status'] = $userData['agent_status'];
    $_SESSION['assign_changed'] = "0";
    header('Location: index.php');
    exit;
}

?>