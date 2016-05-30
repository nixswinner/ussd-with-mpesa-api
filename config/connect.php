<?php

$servername = getenv('IP');
$username = getenv('C9_USER');
$password = "";
$database = "helb_db";
$dbport = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $database, $dbport);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>