<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = "rsvr@msi"; // Change if necessary
$dbname = "online_exam";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
