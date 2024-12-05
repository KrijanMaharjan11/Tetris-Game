<?php
session_start();

// Check if the user is logged in by checking if 'user_id' exists in the session
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: Authentication/login.php");
    exit();
}
