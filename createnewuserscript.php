<?php
require ("includes/connection.php");  // Poziva kod za konekciju na bazu podataka.

// Uzimanje podataka sa forme
$username = $_POST['username'];
$passwordentered = $_POST["password"];
$agent_email = $_POST['agent_email'];
$agent_status = $_POST['agent_status'];
$agent_name = $_POST['agent_name'];
$agent_phone = $_POST['agent_phone'];
$agent_picture = $_FILES["fileToUpload"]["name"];

// Enkriptovanje lozinke
$hash = hash('sha256', $passwordentered);

// Funkcija za generisanje soli
function createSalt() {
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 3);
}

$salt = createSalt();
$password = hash('sha256', $salt . $hash);

// Priprema SQL upita za unos u bazu podataka
$sql = "INSERT INTO member (username, password, email, salt, agent_status, agent_name, agents_phone, agent_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $dbconn->prepare($sql);  // Koristi postojeću konekciju

if ($stmt) {
    // Bind parametri za upit
    $stmt->bind_param("ssssssss", $username, $password, $agent_email, $salt, $agent_status, $agent_name, $agent_phone, $agent_picture);

    // Izvrši upit
    $stmt->execute();

    // Zatvori pripremljeni upit
    $stmt->close();
} else {
    // Greška u pripremi upita
    echo "Error preparing the SQL query: " . $dbconn->error;
}

// Upload fajla
$target_dir = "images/agents/";  // Ciljani direktorijum za upload
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Provera da li je fajl slika
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Provera da li fajl već postoji
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Provera veličine fajla
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Dozvoljeni formati fajlova
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Ako je sve u redu, uploadujemo fajl
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Zatvori konekciju sa bazom
$dbconn->close();

// Preusmeravanje na prethodnu stranu
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>