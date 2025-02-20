<?php
// db.php - Database connection file

$servername = "localhost";  // Database server, typically 'localhost'
$username = "root";         // Your database username
$password = "";             // Your database password (empty if none)
$dbname = "lmsdb";          // The name of the database (change to your DB name)

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
