<?php
$host = 'localhost';   // Database host (usually 'localhost')
$username = 'root';    // Database username
$password = '';        // Database password
$dbname = 'coffeshop'; // Database name
$port = '3306';

// Create a new MySQLi connection with port number
$mysqli = new mysqli($host, $username, $password, $dbname, $port);

// Check if the connection was successful
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>
