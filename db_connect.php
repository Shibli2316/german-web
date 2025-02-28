<?php
$servername = "localhost";
$username = "root"; // Change if using a different database user
$password = ""; // Change if you have set a MySQL password
$dbname = "bakchodi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
