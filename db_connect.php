<?php
// includes/db_connect.php

$host = "localhost";
$user = "root";         // Change if you have a different username
$pass = "";             // Add your MySQL password if any
$dbname = "classpulse"; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
