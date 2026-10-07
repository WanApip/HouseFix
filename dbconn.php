
<?php
// Start a session so we can carry booking choices and customer info from page to page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$username = "root"; 
$password = "";     
$dbname = "housefix"; 

// Establish connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection failed
if( !$conn ){
    die("Database Failed to Connect !: " . mysqli_connect_error());
}
?>