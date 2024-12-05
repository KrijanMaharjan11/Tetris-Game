<?php
$host = 'localhost';
$dbname = 'tetris_game';
$username = 'root';         // Database username
$password = '';

// Create connection using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Here!  Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8
$conn->set_charset("utf8");
