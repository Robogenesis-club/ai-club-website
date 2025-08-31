<?php
// Central DB configuration
$host = 'localhost';
$username = 'robouser';
$password = 'StrongPassword123';
$dbname_main = 'robogenadmin'; // Main DB name
$dbname_member = 'member';     // Member DB name

// Initialize connection variables
$conn = null;
$conn_member = null;

// Function to establish a database connection
function connect_db($db_name, $host, $username, $password) {
    $mysqli = new mysqli($host, $username, $password, $db_name);
    if ($mysqli->connect_error) {
        error_log('robophp: DB connection error to ' . $db_name . ': ' . $mysqli->connect_error);
        return null; // Return null on failure
    }
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}

// Attempt to connect to main DB
$conn = connect_db($dbname_main, $host, $username, $password);
if (!$conn) {
    // Handle main DB connection failure (e.g., display a message or redirect)
    // For now, we'll just log and let the calling script handle it.
    // echo '<p>Main database not connected. Please check your database setup.</p>';
}

// Attempt to connect to member DB
$conn_member = connect_db($dbname_member, $host, $username, $password);
if (!$conn_member) {
    // Handle member DB connection failure
    // echo '<p>Member database not connected. Please check your database setup.</p>';
}

// Start session if not already started (good practice to put it here if used across many files)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
