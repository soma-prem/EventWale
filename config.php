<?php
$host = "localhost";
$user = "root";  // Default XAMPP username
$pass = "";      // Default XAMPP password is empty
$dbname = "eventwale";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
